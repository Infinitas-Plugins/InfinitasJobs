<?php
App::uses('DJBase', 'InfinitasJobs.Lib/DJJob');

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

		$this->pidFile = CakePlugin::path('InfinitasJobs') . 'Config' . DS .'Pid' . DS . $this->queue . '.pid';
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

                if (empty($job)) {
                    $this->log("[JOB] Failed to get a job, queue::{$this->queue} may be empty", self::DEBUG);
                    sleep($this->sleep);
                    continue;
                }

                $job_count += 1;
				$job = new DJJob(
					$job['InfinitasJobQueue']['slug'],
					$job['InfinitasJob']['id'],
					array(
						'max_attempts' => $job['InfinitasJobQueue']['max_attempts']
					)
				);
                $job->run();
            }
        } catch (Exception $e) {
            pr($e);
            exit;
            $this->log("[JOB] unhandled exception::\"{$e->getMessage()}\"", self::ERROR);
        }

        $notice = "[JOB] worker shutting down after running {$job_count} jobs, over {$count} polling iterations";
        $this->log($notice, self::INFO);
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