<?php
/**
 * InfinitasJobQueues controller
 *
 * @brief Add some documentation for InfinitasJobQueues controller.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/InfinitasJobs
 * @package	   InfinitasJobs.Controller
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

class InfinitasJobQueuesController extends InfinitasJobsAppController {
/**
 * The helpers linked to this controller
 *
 * @access public
 * @var array
 */
	public $helpers = array(
		//'InfinitasJobs.InfinitasJobs', // uncoment this for a custom plugin controller
		//'Libs.Gravatar',
	);

/**
 * @brief the index method
 *
 * Show a paginated list of InfinitasJobQueue records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
			)
		);

		$infinitasJobQueues = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
		);

		$this->set(compact('infinitasJobQueues', 'filterOptions'));
	}

/**
 * @brief view method for a single row
 *
 * Show detailed information on a single InfinitasJobQueue
 *
 * @todo update the documentation 
 * @param mixed $id int or string uuid or the row to find
 *
 * @return void
 */
	public function admin_view($id = null) {
		if(!$id) {
			$this->Infinitas->noticeInvalidRecord();
		}

		$infinitasJobQueue = $this->InfinitasJobQueue->getViewData(
			array($this->InfinitasJobQueue->alias . '.' . $this->InfinitasJobQueue->primaryKey => $id)
		);

		$this->set(compact('infinitasJobQueue'));
	}

/**
 * @brief admin create action
 *
 * Adding new InfinitasJobQueue records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

	}

/**
 * @brief admin edit action
 *
 * Edit old InfinitasJobQueue records.
 *
 * @todo update the documentation
 * @param mixed $id int or string uuid or the row to edit
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit($id);

	}
}