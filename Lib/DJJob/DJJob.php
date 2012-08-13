<?php
App::uses('DJBase', 'InfinitasJobs.Lib/DJJob');

class DJJob extends DJBase {

    public function __construct($worker_name, $job_id, $options = array()) {
        $options = array_merge(
			array('max_attempts' => 5),
			$options
		);

        $this->worker_name = $worker_name;
        $this->job_id = $job_id;
        $this->max_attempts = $options['max_attempts'];
    }

    public function run() {
        $handler = self::model()->find('handler', $this->job_id);
        if (!is_object($handler)) {
			self::model()->finishJob($this->job_id, 'Error running job handler');
            return false;
        }

        try {
            $handler->perform();

			self::model()->finishJob($this->job_id);
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