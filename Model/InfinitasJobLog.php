<?php
/**
 * InfinitasJobError model
 *
 * @brief Add some documentation for InfinitasJobError model.
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

class InfinitasJobLog extends InfinitasJobsAppModel {

/**
 * belongsTo relations for this model
 *
 * @var array
 */
	public $belongsTo = array(
		'InfinitasJob' => array(
			'className' => 'InfinitasJobs.InfinitasJob',
			'foreignKey' => 'infinitas_job_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
		)
	);

/**
 * overload the construct method so that you can use translated validation
 * messages.
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
			'error' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'infinitas_job_id' => array(
				'uuid' => array(
					'rule' => array('uuid'),
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
}
