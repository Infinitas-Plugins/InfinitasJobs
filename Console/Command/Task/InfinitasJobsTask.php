<?php
App::uses('AppShell', 'Console/Command');
App::uses('ConnectionManager', 'Model');
App::uses('CakeJob', 'InfinitasJobs.Lib/Job');
App::uses('DJJob', 'InfinitasJobs.Lib/DJJob');

/**
 * InfinitasJobs Task
 *
 * Wrapper around DJJob library for shells
 *
 * @copyright     Copyright 2011, Jose Diaz-Gonzalez. (http://josediazgonzalez.com)
 * @link          http://github.com/josegonzalez/cake_djjob
 * @package       cake_djjob
 * @subpackage    cake_djjob.shells.tasks
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class InfinitasJobsTask extends AppShell {

/**
 * Contains configuration settings for use with individual model objects.
 * Individual model settings should be stored as an associative array,
 * keyed off of the model name.
 *
 * @var array
 * @access public
 * @see Model::$alias
 */
	var $settings = array(
		'connection'=> 'default',
		'type' => 'mysql',
	);

	public $uses = array(
		'InfinitasJobs.InfinitasJob'
	);

/**
 * Returns a job
 *
 * Auto imports and passes through the constructor parameters to newly created job
 * Note: (PHP 5 >= 5.1.3) - requires ReflectionClass if passing arguments
 *
 * @param string $jobName Name of job being loaded
 * @param mixed $argument Some argument to pass to the job
 * @param mixed ... etc.
 * @return mixed Job instance if available, null otherwise
 */
	function load() {
		$args = func_get_args();
		if (empty($args) || !is_string($args[0])) {
			return null;
		}

		$jobName = array_shift($args);
		list($plugin, $className) = pluginSplit($jobName);
		if ($plugin) {
			$plugin = "{$plugin}.";
		}

		if (!class_exists($className)) {
			App::uses($className, "{$plugin}Job");
		}

		if (empty($args)) {
			return new $className();
		}

		if (!class_exists('ReflectionClass')) {
			return null;
		}

		$ref = new ReflectionClass($className);
		return $ref->newInstanceArgs($args);
	}

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
	function enqueue($job, $queue = 'default', $run_at = null) {
		return $this->InfinitasJob->enqueue($job, $queue, $run_at);
	}

/**
 * Returns an array containing the status of a given queue
 *
 * @param string $queue
 * @return array
 **/
	function status($queue = "default") {
		return $this->InfinitasJob->status($queue);
	}
}