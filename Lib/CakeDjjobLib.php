<?php
App::uses('ConnectionManager', 'Model');
App::uses('CakeJob', 'CakeDjjob.Job');
App::uses('DJJob', 'Djjob.Vendor');

/**
 * CakeDjjob Component
 *
 * Wrapper around DJJob library
 *
 * @copyright   Copyright 2011, Jose Diaz-Gonzalez. (http://josediazgonzalez.com)
 * @link        http://github.com/josegonzalez/cake_djjob
 * @package     cake_djjob
 * @subpackage  cake_djjob.controller.components
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class CakeDjjobLib extends Object {
/**
 * Enqueues Jobs using DJJob
 *
 * Note that all Jobs enqueued using this system must extend the base CakeJob
 * class which is included in this plugin
 *
 * @param Job $job
 * @param string $queue
 * @param string $run_at
 * @return boolean True if enqueue is successful, false on failure
 */
	public static function enqueue($job, $queue = "default", $run_at = null) {
		return DJJob::enqueue($job, $queue, $run_at);
	}

/**
 * Bulk Enqueues Jobs using DJJob
 *
 * @param array $jobs
 * @param string $queue
 * @param string $run_at
 * @return boolean True if bulk enqueue is successful, false on failure
 */
	public static function bulkEnqueue($jobs, $queue = "default", $run_at = null) {
		return DJJob::bulkEnqueue($jobs, $queue, $run_at);
	}

/**
 * Returns an array containing the status of a given queue
 *
 * @param string $queue
 * @return array
 **/
	public static function status($queue = "default") {
		return DJJob::status($queue);
	}

}