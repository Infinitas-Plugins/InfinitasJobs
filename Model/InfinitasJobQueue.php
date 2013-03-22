<?php
/**
 * InfinitasJobQueue model
 *
 * @brief Add some documentation for InfinitasJobQueue model.
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

class InfinitasJobQueue extends InfinitasJobsAppModel {

/**
 * hasMany relations for this model
 *
 * @access public
 * @var array
 */
	public $hasMany = array(
		'InfinitasJob' => array(
			'className' => 'InfinitasJobs.InfinitasJob',
			'foreignKey' => 'infinitas_job_queue_id',
			'dependent' => false,
			'conditions' => ''
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

	public $findMethods = array(
		'idFromSlug' => true,
		'status' => true
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

		$this->validate = array(
			'name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
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
 * @brief fetch the queue id based on the slug passed in
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return string|boolean
 */
	protected function _findIdFromSlug($state, $query = array(), $results = array()) {
		if($state == 'before') {
			if(empty($query[0])) {
				throw new InvalidArgumentException('Slug is required to fetch id');
			}

			$query['fields'] = array(
				$this->alias . '.' . $this->primaryKey
			);

			if (substr($query[0], -1) == '*') {
				$query['conditions'] = array(
					$this->alias . '.slug LIKE "' . trim($query[0], '*') . '%"'
				);

				$query['order'] = array(
					$this->alias . '.pending_job_count' => 'asc'
				);
			} else {
				$query['conditions'] = array(
					$this->alias . '.slug' => $query[0]
				);
			}

			$query['limit'] = 1;

			return $query;
		}

		if(!empty($results[0][$this->alias][$this->primaryKey])) {
			return $results[0][$this->alias][$this->primaryKey];
		}

		return false;
	}

/**
 * @brief get the status of the queue
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 *
 * @throws InvalidArgumentException when there is no queue specified
 */
	protected function _findStatus($state, $query, $results = array()) {
		if($state == 'before') {
			if(empty($query[0])) {
				throw new InvalidArgumentException('You must specify the queue');
			}

			$this->virtualFields['total_job_count'] = String::insert(
				':alias.:pending + :alias.:failed + :alias.:locked + :alias.:completed',
				array(
					'alias' => $this->alias,
					'pending' => 'pending_job_count',
					'failed' => 'failed_job_count',
					'locked' => 'locked_job_count',
					'completed' => 'completed_job_count'
				)
			);

			$query['fields'] = array(
				$this->alias . '.pending_job_count',
				$this->alias . '.failed_job_count',
				$this->alias . '.locked_job_count',
				$this->alias . '.completed_job_count',
				'total_job_count'
			);

			$query['conditions'] = array(
				$this->alias . '.slug' => $query[0]
			);

			return $query;
		}

		if (empty($results)) {
			return array();
		}

		$results = $results[0];

		return array(
			'outstanding' => $results[$this->alias]['pending_job_count'],
			'locked' => $results[$this->alias]['locked_job_count'],
			'failed' => $results[$this->alias]['failed_job_count'],
			'completed' => $results[$this->alias]['completed_job_count'],
			'total'  => $results[$this->alias]['total_job_count']
		);
	}
}
