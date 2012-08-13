<?php
App::uses('ConnectionManager', 'Model');
App::uses('CakeJob', 'InfinitasJobs.Job');
App::uses('DJJob', 'InfinitasJobs.Lib');

/**
 * InfinitasJobs Component
 *
 * Wrapper around DJJob library
 *
 * @copyright   Copyright 2011, Jose Diaz-Gonzalez. (http://josediazgonzalez.com)
 * @link        http://github.com/josegonzalez/cake_djjob
 * @package     cake_djjob
 * @subpackage  cake_djjob.controller.components
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class InfinitasJobsComponent extends Component {

	public $settings = array(
		'connection'=> 'default',
		'type' => 'mysql',
	);

/**
 * Constructor.
 *
 * @param ComponentCollection $collection
 * @param array $settings
 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = array_merge($this->settings, $settings);
		parent::__construct($collection, $settings);
	}

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
	public function load() {
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
 * $job can be a single job or an array of jobs that will use the same queue and $runAt date
 *
 * @param Job $job
 * @param string $queue
 * @param string $run_at
 * @return boolean True if enqueue is successful, false on failure
 */
	public function enqueue($job, $queue = 'default', $runAt = null) {
		return ClassRegistry::init('InfinitasJobs.InfinitasJob')->enqueue($job, $queue, $runAt);
	}

/**
 * Returns an array containing the status of a given queue
 *
 * @param string $queue
 * @return array
 **/
	public function status($queue = "default") {
		return DJJob::status($queue);
	}
}