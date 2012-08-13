<?php
/**
 * InfinitasJobs controller
 *
 * @brief Add some documentation for InfinitasJobs controller.
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

class InfinitasJobsController extends InfinitasJobsAppController {
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

	public function admin_dashboard() {

	}

/**
 * @brief the index method
 *
 * Show a paginated list of InfinitasJob records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'conditions' => array(
				$this->modelClass . '.completed' => null,
				$this->modelClass . '.locked' => null,
				$this->modelClass . '.failed' => null
			)
		);

		$this->_jobsIndex();
	}

/**
 * @brief display a list of locked jobs
 *
 * @return void
 */
	public function admin_locked() {
		$this->Paginator->settings = array(
			'conditions' => array(
				$this->modelClass . '.locked IS NOT NULL'
			)
		);

		$this->_jobsIndex();
	}

/**
 * @brief display a list of failed jobs
 *
 * @return void
 */
	public function admin_failed() {
		$this->Paginator->settings = array(
			'conditions' => array(
				$this->modelClass . '.failed IS NOT NULL'
			),
			'contain' => array(
				'InfinitasJobError'
			)
		);

		$this->_jobsIndex();
	}

/**
 * @brief display a list of failed jobs
 *
 * @return void
 */
	public function admin_completed() {
		$this->Paginator->settings = array(
			'conditions' => array(
				$this->modelClass . '.completed IS NOT NULL'
			)
		);

		$this->_jobsIndex();
	}

/**
 * @brief DRY method used for the various index methods
 *
 * @see InfinitasJobsController::index()
 * @see InfinitasJobsController::locked()
 * @see InfinitasJobsController::failed()
 * @see InfinitasJobsController::completed()
 *
 * @return void
 */
	protected function _jobsIndex() {
		if(empty($this->Paginator->settings['contain'])) {
			$this->Paginator->settings['contain'] = array();
		}
		$this->Paginator->settings['contain'][] = 'InfinitasJobLog';
		$this->Paginator->settings['contain'][] = 'InfinitasJobQueue';
		$infinitasJobs = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'id',
		);

		$this->set(compact('infinitasJobs', 'filterOptions'));
	}

/**
 * @brief view method for a single row
 *
 * Show detailed information on a single InfinitasJob
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

		$infinitasJob = $this->InfinitasJob->getViewData(
			array($this->InfinitasJob->alias . '.' . $this->InfinitasJob->primaryKey => $id)
		);

		$this->set(compact('infinitasJob'));
	}

/**
 * @brief admin create action
 *
 * Adding new InfinitasJob records.
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
 * Edit old InfinitasJob records.
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