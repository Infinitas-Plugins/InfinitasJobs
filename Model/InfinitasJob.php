<?php
/**
 * InfinitasJob model
 *
 * @brief Add some documentation for InfinitasJob model.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/InfinitasJobs
 * @package	   InfinitasJobs.Model
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

class InfinitasJob extends InfinitasJobsAppModel {
/**
 * Additional behaviours that are attached to this model
 *
 * @access public
 * @var array
 */
	public $actsAs = array(
		// 'Libs.Feedable',
		// 'Libs.Rateable'
	);

/**
 * How the default ordering on this model is done
 *
 * @access public
 * @var array
 */
	public $order = array();

/**
 * @brief custom finds
 *
 * @var array
 */
	public $findMethods = array(
		'job' => true,
		'handler' => true,
		'attempts' => true,
		'status' => true
	);

/**
 * hasOne relations for this model
 *
 * @access public
 * @var array
 */
	public $hasOne = array(
	);

/**
 * belongsTo relations for this model
 *
 * @access public
 * @var array
 */
	public $belongsTo = array(
		'InfinitasJobQueue' => array(
			'className' => 'InfinitasJobs.InfinitasJobQueue',
			'foreignKey' => 'infinitas_job_queue_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => false,
		),
		'InfinitasJobQueuePending' => array(
			'className' => 'InfinitasJobs.InfinitasJobQueue',
			'foreignKey' => 'infinitas_job_queue_id',
			'counterCache' => 'pending_job_count',
			'counterScope' => array(
				'InfinitasJob.completed' => null,
				'InfinitasJob.locked' => null,
				'InfinitasJob.failed' => null
			)
		),
		'InfinitasJobQueueLocked' => array(
			'className' => 'InfinitasJobs.InfinitasJobQueue',
			'foreignKey' => 'infinitas_job_queue_id',
			'counterCache' => 'locked_job_count',
			'counterScope' => array(
				'InfinitasJob.locked IS NOT NULL',
			)
		),
		'InfinitasJobQueueCompleted' => array(
			'className' => 'InfinitasJobs.InfinitasJobQueue',
			'foreignKey' => 'infinitas_job_queue_id',
			'counterCache' => 'completed_job_count',
			'counterScope' => array(
				'InfinitasJob.completed IS NOT NULL',
			)
		),
		'InfinitasJobQueueFailed' => array(
			'className' => 'InfinitasJobs.InfinitasJobQueue',
			'foreignKey' => 'infinitas_job_queue_id',
			'counterCache' => 'failed_job_count',
			'counterScope' => array(
				'InfinitasJob.failed IS NOT NULL',
			)
		)
	);

/**
 * hasMany relations for this model
 *
 * @access public
 * @var array
 */
	public $hasMany = array(
		'InfinitasJobError' => array(
			'className' => 'InfinitasJobs.InfinitasJobError',
			'foreignKey' => 'infinitas_job_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * hasAndBelongsToMany relations for this model
 *
 * @access public
 * @var array
 */
	public $hasAndBelongsToMany = array(
	);

/**
 * overload the construct method so that you can use translated validation
 * messages.
 *
 * @access public
 *
 * @param mixed $id string uuid or id
 * @param string $table the table that the model is for
 * @param string $ds the datasource being used
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->order = array(
			$this->alias . '.created' => 'asc'
		);

		$this->validate = array(
			'handler' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'infinitas_job_queue_id' => array(
				'validateRecordExists' => array(
					'rule' => array('validateRecordExists'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'attempts' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);
	}

/**
 * General method for the view pages. Gets the required data and relations
 * and can be used for the admin preview also.
 *
 * @param array $conditions conditions for the find
 * @return array the data that was found
 */
	public function getViewData($conditions = array()) {
		if(!$conditions) {
			return false;
		}

		$data = $this->find(
			'first',
			array(
				'fields' => array(
				),
				'conditions' => $conditions,
				'contain' => array(
				)
			)
		);

		return $data;
	}

/**
 * acquireLock
 *
 * @param type $id
 * @param type $process
 */
	public function lockJob($id, $process) {

	}

/**
 * releaseLocks
 *
 * @param type $processId
 */
	public function unlockJobs($processId) {

	}

/**
 * releaseLock
 *
 * @param type $processId
 */
	public function unlockJob($processId) {

	}

/**
 * finish
 *
 * @param type $id
 */
	public function finishJob($id, $error = null) {

	}

/**
 * retryLater
 *
 * @param type $id
 * @param type $delay
 */
	public function retryJob($id, $delay = null) {

	}

/**
 * enque
 *
 * @param type $handler
 * @param type $queue
 * @param type $run_at
 */
	public function enque($handler, $queue = 'default', $run_at = null) {
		if(is_array($handler)) {
			foreach($handler as $data) {
				self::enque($data, $queue, $run_at);
			}
		}
	}

/**
 * bulkEnqueue
 *
 * @param type $handlers
 * @param type $queue
 * @param type $run_at
 */
	public function bulkEnqueue($handlers, $queue = 'default', $run_at = null) {

	}

/**
 * getNewJob
 *
 * @param type $state
 * @param type $query
 * @param type $results
 * @return type
 */
	protected function _findJob($state, $query = array(), $results = array()) {
		if($state == 'before') {

			return $query;
		}

		return $results;
	}

/**
 * getHandler
 *
 * @param type $state
 * @param type $query
 * @param type $results
 * @return type
 */
	protected function _findHandler($state, $query = array(), $results = array()) {
		if($state == 'before') {

			return $query;
		}

		return $results;
	}

/**
 * getAttempts
 *
 * @param type $state
 * @param type $query
 * @param type $results
 * @return type
 */
	protected function _findAttempts($state, $query = array(), $results = array()) {
		if($state == 'before') {

			return $query;
		}

		return $results;
	}

/**
 * getHandler
 *
 * @param type $state
 * @param type $query
 * @param type $results
 * @return type
 */
	protected function _findStatus($state, $query = array(), $results = array()) {
		if($state == 'before') {

			return $query;
		}

		return $results;
	}
}
