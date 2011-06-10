<?php
if (!class_exists('ConnectionManager')) {
    App::import('Model', 'ConnectionManager');
}
if (!class_exists('CakeJob')) {
    App::import('Lib', 'CakeDjjob.cake_job', array(
        'file' => 'jobs' . DS . 'cake_job.php',
    ));
}
if (!class_exists('DJJob')) {
    App::import('Vendor', 'Djjob.DJJob', array(
        'file' => 'DJJob.php',
    ));
}
/**
 * CakeDjjob Model Behavior
 * 
 * Wrapper around DJJob library
 *
 * @copyright     Copyright 2011, Jose Diaz-Gonzalez. (http://josediazgonzalez.com)
 * @link          http://github.com/josegonzalez/cake_djjob
 * @package       cake_djjob
 * @subpackage    cake_djjob.models.behaviors
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class CakeDjjobBehavior extends ModelBehavior {

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
        'type'      => 'mysql',
    );

/**
 * Initiate CakeDjjob Behavior
 *
 * @param object $model
 * @param array $config
 * @return void
 * @access public
 */
    function setup(&$model, $config = array()) {
        $this->settings = array_merge($this->settings, $config);
        $connection = ConnectionManager::getDataSource($this->settings['connection']);

        if ($this->settings['type'] == 'mysql') {
            DJJob::configure(
                implode(';', array(
                    "{$this->settings['type']}:host={$connection->config['host']}",
                    "dbname={$connection->config['database']}",
                    "port={$connection->config['port']}",
                )), array(
                    'mysql_user' => $connection->config['login'],
                    'mysql_pass' => $connection->config['password']
                )
            );
        } else {
            DJJob::configure(
                implode(';', array(
                    "{$this->settings['type']}:host={$connection->config['host']}",
                    "dbname={$connection->config['database']}",
                    "port={$connection->config['port']}",
                    "user={$connection->config['login']}",
                    "password={$connection->config['password']}"
                ))
            );
        }
    }


/**
 * Load loads Jobs using DJJob
 * 	- it auto imports and passes through the constructor parameters to newly created job.
 * 	- *Note: (PHP 5 >= 5.1.3) - requires ReflectionClass
 *
 * @param string $jobName
 * @param mixed $passthrough params
 * @param mixed $passthrough, ...unlimited OPTIONAL number of additional variables to pass through
 * @return job 
 */
    function load($model, $jobName) {
		App::import("Lib", $jobName);
		$args = func_get_args();
		//Remove the first param, because its the Job Name, if there is anything else, pass it along to the new Jobs Controller.
		array_shift($args);
		if(empty($args))
			return new $jobName();                                                                                                                                                          
		else {
			$ref = new ReflectionClass($jobName);
			return $ref->newInstanceArgs($args);
		}
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
    function enqueue(&$model, $job, $queue = "default", $run_at = null) {
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
    function bulkEnqueue(&$model, $jobs, $queue = "default", $run_at = null) {
        return DJJob::bulkEnqueue($jobs, $queue, $run_at);
    }

/**
 * Returns an array containing the status of a given queue
 *
 * @param string $queue
 * @return array
 * @author Jose Diaz-Gonzalez
 **/
    function status(&$model, $queue = "default") {
        return DJJob::status($queue);
    }

}