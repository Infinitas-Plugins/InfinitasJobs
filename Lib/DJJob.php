<?php

# This system is mostly a port of delayed_job: http://github.com/tobi/delayed_job

class DJException extends Exception { }

class DJRetryException extends DJException {

    private $delay_seconds = 7200;

    public function setDelay($delay) {
        $this->delay_seconds = $delay;
    }
    public function getDelay() {
        return $this->delay_seconds;
    }
}

class DJBase {

    // error severity levels
    const CRITICAL = 4;
    const    ERROR = 3;
    const     WARN = 2;
    const     INFO = 1;
    const    DEBUG = 0;

    private static $log_level = self::DEBUG;

    private static $db = null;

    private static $dsn = "";
    private static $options = array(
      "mysql_user" => null,
      "mysql_pass" => null,
    );

    // use either `configure` or `setConnection`, depending on if
    // you already have a PDO object you can re-use
    public static function configure($dsn, $options = array()) {
        self::$dsn = $dsn;
        self::$options = array_merge(self::$options, $options);
    }

    public static function setLogLevel($const) {
        self::$log_level = $const;
    }

    public static function setConnection(PDO $db) {
        self::$db = $db;
    }

    protected static function getConnection() {
        if (self::$db === null) {
            if (!self::$dsn) {
                throw new DJException("Please tell DJJob how to connect to your database by calling DJJob::configure(\$dsn, [\$options = array()]) or re-using an existing PDO connection by calling DJJob::setConnection(\$pdoObject). If you're using MySQL you'll need to pass the db credentials as separate 'mysql_user' and 'mysql_pass' options. This is a PDO limitation, see [http://stackoverflow.com/questions/237367/why-is-php-pdo-dsn-a-different-format-for-mysql-versus-postgresql] for an explanation.");
            }
            try {
                // http://stackoverflow.com/questions/237367/why-is-php-pdo-dsn-a-different-format-for-mysql-versus-postgresql
                if (self::$options["mysql_user"] !== null) {
                    self::$db = new PDO(self::$dsn, self::$options["mysql_user"], self::$options["mysql_pass"]);
                } else {
                    self::$db = new PDO(self::$dsn);
                }
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception("DJJob couldn't connect to the database. PDO said [{$e->getMessage()}]");
            }
        }
        return self::$db;
    }

    public static function runQuery($sql, $params = array()) {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);

        $ret = array();
        if ($stmt->rowCount()) {
            // calling fetchAll on a result set with no rows throws a
            // "general error" exception
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) $ret []= $r;
        }

        $stmt->closeCursor();
        return $ret;
    }

    public static function runUpdate($sql, $params = array()) {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    protected static function log($mesg, $severity=self::CRITICAL) {
        if ($severity >= self::$log_level) {
            printf("[%s] %s\n", date('Y-m-d H:i:s'), $mesg);
        }
    }
}

class DJWorker extends DJBase {
    # This is a singleton-ish thing. It wouldn't really make sense to
    # instantiate more than one in a single request (or commandline task)

    public function __construct($options = array()) {
        $options = array_merge(array(
            "queue" => "default",
            "count" => 0,
            "sleep" => 5,
            "max_attempts" => 5
        ), $options);
        list($this->queue, $this->count, $this->sleep, $this->max_attempts) =
            array($options["queue"], $options["count"], $options["sleep"], $options["max_attempts"]);

        list($hostname, $pid) = array(trim(`hostname`), getmypid());
        $this->name = "host::$hostname pid::$pid";

		$this->pidFile = InfinitasPlugin::path('InfinitasJobs') . 'Config' . DS .'Pid' . DS . $this->queue .'.pid';
		$this->pid = $pid;

        if (function_exists("pcntl_signal")) {
            pcntl_signal(SIGTERM, array($this, "handleSignal"));
            pcntl_signal(SIGINT, array($this, "handleSignal"));
        }
    }

    public function handleSignal($signo) {
        $signals = array(
            SIGTERM => "SIGTERM",
            SIGINT  => "SIGINT"
        );
        $signal = $signals[$signo];

        $this->log("[WORKER] Received received {$signal}... Shutting down", self::INFO);
        $this->releaseLocks();
        die(0);
    }

    public function releaseLocks() {
        $this->runUpdate("
            UPDATE infinitas_jobs
            SET locked = NULL, pid = NULL
            WHERE pid = ?",
            array($this->name)
        );
    }

    /**
     * Returns a new job ordered by most recent first
     * why this?
     *     run newest first, some jobs get left behind
     *     run oldest first, all jobs get left behind
     * @return DJJob
     */
    public function getNewJob() {
        # we can grab a locked job if we own the lock
        $rs = $this->runQuery("
            SELECT id
            FROM   infinitas_jobs
            WHERE  queue = ?
            AND    (run IS NULL OR NOW() >= run)
            AND    (locked IS NULL OR pid = ?)
            AND    failed IS NULL
            AND    attempts < ?
            ORDER BY created DESC
            LIMIT  10
        ", array($this->queue, $this->name, $this->max_attempts));

        // randomly order the 10 to prevent deadlock issues
        shuffle($rs);

        foreach ($rs as $r) {
            $job = new DJJob($this->name, $r["id"], array(
                "max_attempts" => $this->max_attempts
            ));
            if ($job->acquireLock()) return $job;
        }

        return false;
    }

    public function start() {
		$pid = $this->__checkPid();
		if($pid) {
			$this->log(sprintf('Already running (PID: %s)', $pid), self::INFO);
			exit;
		}

        $this->log("[JOB] Starting worker {$this->name} on queue::{$this->queue}", self::INFO);
		$this->__writePid();

        $count = 0;
        $job_count = 0;
        try {
            while ($this->count == 0 || $count < $this->count) {
                if (function_exists("pcntl_signal_dispatch")) pcntl_signal_dispatch();

                $count += 1;
                $job = $this->getNewJob($this->queue);

                if (!$job) {
                    $this->log("[JOB] Failed to get a job, queue::{$this->queue} may be empty", self::DEBUG);
                    sleep($this->sleep);
                    continue;
                }

                $job_count += 1;
                $job->run();
            }
        } catch (Exception $e) {
            $this->log("[JOB] unhandled exception::\"{$e->getMessage()}\"", self::ERROR);
        }

        $this->log("[JOB] worker shutting down after running {$job_count} jobs, over {$count} polling iterations", self::INFO);
    }

	/**
	 * @brief check if the process is still running
	 *
	 * @return boolean
	 */
	private function __checkPid() {
		if(!is_file($this->pidFile)) {
			return false;
		}

		$File = new File($this->pidFile);
		$pid = $File->read();

		if(posix_getsid($pid)) {
			return $pid;
		}

		return false;
	}

	/**
	 * @brief save the current pid to disk for future checks
	 */
	private function __writePid() {
		$File = new File($this->pidFile, true);
		$File->write($this->pid);
	}
}

class DJJob extends DJBase {

    public function __construct($worker_name, $job_id, $options = array()) {
        $options = array_merge(array(
            "max_attempts" => 5
        ), $options);
        $this->worker_name = $worker_name;
        $this->job_id = $job_id;
        $this->max_attempts = $options["max_attempts"];
    }

    public function run() {
        # pull the handler from the db
        $handler = $this->getHandler();
        if (!is_object($handler)) {
            $this->log("[JOB] bad handler for job::{$this->job_id}", self::ERROR);
            $this->finishWithError("bad handler for job::{$this->job_id}");
            return false;
        }

        # run the handler
        try {
            $handler->perform();

            # cleanup
            $this->finish();
            return true;

        } catch (DJRetryException $e) {
            # attempts hasn't been incremented yet.
            $attempts = $this->getAttempts()+1;

            $msg = "Caught DJRetryException \"{$e->getMessage()}\" on attempt $attempts/{$this->max_attempts}.";

            if($attempts == $this->max_attempts) {
                $this->log("[JOB] job::{$this->job_id} $msg Giving up.");
                $this->finishWithError($msg);
            } else {
                $this->log("[JOB] job::{$this->job_id} $msg Try again in {$e->getDelay()} seconds.", self::WARN);
                $this->retryLater($e->getDelay());
            }
            return false;

        } catch (Exception $e) {

            $this->finishWithError($e->getMessage());
            return false;

        }
    }

    public function acquireLock() {
        $this->log("[JOB] attempting to acquire lock for job::{$this->job_id} on {$this->worker_name}", self::INFO);

        $lock = $this->runUpdate("
            UPDATE infinitas_jobs
            SET    locked = NOW(), pid = ?
            WHERE  id = ? AND (locked IS NULL OR pid = ?) AND failed IS NULL
        ", array($this->worker_name, $this->job_id, $this->worker_name));

        if (!$lock) {
            $this->log("[JOB] failed to acquire lock for job::{$this->job_id}", self::INFO);
            return false;
        }

        return true;
    }

    public function releaseLock() {
        $this->runUpdate("
            UPDATE infinitas_jobs
            SET locked = NULL, pid = NULL
            WHERE id = ?",
            array($this->job_id)
        );
    }

    public function finish() {
        $this->runUpdate(
            "DELETE FROM infinitas_jobs WHERE id = ?",
            array($this->job_id)
        );
        $this->log("[JOB] completed job::{$this->job_id}", self::INFO);
    }

    public function finishWithError($error) {
        $this->runUpdate("
            UPDATE infinitas_jobs
            SET attempts = attempts + 1,
                failed = IF(attempts >= ?, NOW(), NULL),
                error = IF(attempts >= ?, ?, NULL)
            WHERE id = ?",
            array(
                $this->max_attempts,
                $this->max_attempts,
                $error,
                $this->job_id
            )
        );
        $this->log("[JOB] failure in job::{$this->job_id}", self::ERROR);
        $this->releaseLock();
    }

    public function retryLater($delay) {
        $this->runUpdate("
            UPDATE infinitas_jobs
            SET run = DATE_ADD(NOW(), INTERVAL ? SECOND),
                attempts = attempts + 1
            WHERE id = ?",
            array(
              $delay,
              $this->job_id
            )
        );
        $this->releaseLock();
    }

    public function getHandler() {
        $rs = $this->runQuery(
            "SELECT handler FROM infinitas_jobs WHERE id = ?",
            array($this->job_id)
        );
        foreach ($rs as $r) return unserialize($r["handler"]);
        return false;
    }

    public function getAttempts() {
        $rs = $this->runQuery(
            "SELECT attempts FROM infinitas_jobs WHERE id = ?",
            array($this->job_id)
        );
        foreach ($rs as $r) return $r["attempts"];
        return false;
    }

    public static function enqueue($handler, $queue = "default", $run = null) {
        $affected = self::runUpdate(
            "INSERT INTO infinitas_jobs (id, handler, queue, run, created) VALUES(?, ?, ?, ?, NOW())",
            array(String::uuid(), serialize($handler), (string)$queue, $run)
        );

        if ($affected < 1) {
            self::log("[JOB] failed to enqueue new job", self::ERROR);
            return false;
        }

        return true;
    }

    public static function bulkEnqueue($handlers, $queue = "default", $run = null) {
        $sql = "INSERT INTO infinitas_jobs (id, handler, queue, run, created) VALUES";
        $sql .= implode(",", array_fill(0, count($handlers), "(?, ?, ?, ?, NOW())"));

        $parameters = array();
        foreach ($handlers as $handler) {
			$parameters[] = String::uuid();
            $parameters[] = serialize($handler);
            $parameters[] = (string) $queue;
            $parameters[] = $run;
        }
        $affected = self::runUpdate($sql, $parameters);

        if ($affected < 1) {
            self::log("[JOB] failed to enqueue new jobs", self::ERROR);
            return false;
        }

        if ($affected != count($handlers))
            self::log("[JOB] failed to enqueue some new jobs", self::ERROR);

        return true;
    }

    public static function status($queue = "default") {
        $rs = self::runQuery("
            SELECT COUNT(*) as total, COUNT(failed) as failed, COUNT(locked) as locked
            FROM `infinitas_jobs`
            WHERE queue = ?
        ", array($queue));
        $rs = $rs[0];

        $failed = $rs["failed"];
        $locked = $rs["locked"];
        $total  = $rs["total"];
        $outstanding = $total - $locked - $failed;

        return array(
            "outstanding" => $outstanding,
            "locked" => $locked,
            "failed" => $failed,
            "total"  => $total
        );
    }
}