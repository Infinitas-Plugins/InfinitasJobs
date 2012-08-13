<?php
/**
 * This system is mostly a port of delayed_job
 *
 * @link http://github.com/tobi/delayed_job
 */

class DJBase {
    const CRITICAL = 4;

    const ERROR = 3;

    const WARN = 2;

    const INFO = 1;

    const DEBUG = 0;

    private static $log_level = self::DEBUG;

	public static function model() {
		return ClassRegistry::init('InfinitasJobs.InfinitasJob');
	}

    public static function setLogLevel($const) {
        self::$log_level = $const;
    }

    protected static function log($mesg, $severity=self::CRITICAL) {
        if ($severity >= self::$log_level) {
            printf("[%s] %s\n", date('Y-m-d H:i:s'), $mesg);
        }
    }
}

/**
 * This is a singleton-ish thing. It wouldn't really make sense to
 * instantiate more than one in a single request (or commandline task)
 */
class DJWorker extends DJBase {
/**
 * @brief setup the worker based on the options
 *
 * @param array $options
 */
    public function __construct($options = array()) {
        $options = array_merge(array(
            'queue' => 'default',
            'count' => 0,
			'sleep' => 5,
            'max_attempts' => 5
        ), $options);

        list($this->queue, $this->count, $this->sleep, $this->max_attempts) =
            array($options["queue"], $options["count"], $options["sleep"], $options["max_attempts"]);

		$hostname = self::model()->hostname();
		$pid = self::model()->pid();
        $this->name = "host::{$hostname} pid::$pid";

		$this->pidFile = InfinitasPlugin::path('InfinitasJobs') . 'Config' . DS .'Pid' . DS . $this->queue . '.pid';
		$this->pid = $pid;

        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGTERM, array($this, 'handleSignal'));
            pcntl_signal(SIGINT, array($this, 'handleSignal'));
        }
    }

/**
 * @brief custom callback to handle signals
 *
 * @param type $signo
 */
    public function handleSignal($signo) {
        $signals = array(
            SIGTERM => 'SIGTERM',
            SIGINT  => 'SIGINT'
        );
        $signal = $signals[$signo];

        $this->log("[WORKER] Received received {$signal}... Shutting down", self::INFO);
        self::model()->unlock();
        die(0);
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
                $job = self::model()->find('job', $this->queue);

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
        $options = array_merge(
			array('max_attempts' => 5),
			$options
		);

        $this->worker_name = $worker_name;
        $this->job_id = $job_id;
        $this->max_attempts = $options["max_attempts"];
    }

    public function run() {
        $handler = self::model()->find('handler', $this->job_id);
        if (!is_object($handler)) {
			self::model()->finishJob($this->job_id, 'Error running job handler');
            return false;
        }

        try {
            $handler->perform();

            $this->finish();
            return true;

        } catch (DJRetryException $e) {
            $attempts = self::model()->find('attempts', $this->job_id) + 1;

            if($attempts == $this->max_attempts) {
				self::model()->finishJob($this->job_id, $e->getMessage());
            } else {
                self::model()->retryJob($e->getDelay());
            }
            return false;

        } catch (Exception $e) {
            self::model()->finishJob($this->job_id, $e->getMessage());
            return false;
        }
    }
}