<?php
App::uses('CakeLogInterface', 'Log');

class InfinitasJobLogger implements CakeLogInterface {
	public function __construct($options = array()) {

    }

    public function write($type, $message) {
		return (bool)ClassRegistry::init('InfinitasJobs.InfinitasJobLog')->save(
			array(
				'infinitas_job_id' => $message['id'],
				'error' => $message['message'],
				'error' => $message['error']
			)
		);
    }
}
