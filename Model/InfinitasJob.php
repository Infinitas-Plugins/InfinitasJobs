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
 * @brief get the hostname of the machine running the current request
 *
 * @return string
 */
	public function hostname() {
		return trim(`hostname`);
	}

/**
 * @brief get the process id of self
 *
 * @return int
 */
	public function pid() {
		return getmypid();
	}

/**
 * @brief log something related to a job
 *
 * @param string|null $jobId the job id or null to use Model::id
 * @param string|exception $message
 * @param type $error
 *
 * @throws InvalidArgumentException
 */
	public function writeLog($jobId = null, $message = null, $error = true) {
		if(empty($jobId)) {
			$jobId = $this->id;
		}

		if($message instanceof Exception) {
			$e = $message;
			$message = $e->getMessage();
			if($e instanceof InfinitasJobsException && empty($jobId)) {
				$jobId = $e->getJobId();
			}
		}

		if(empty($jobId)) {
			throw new InvalidArgumentException('Missing job id for loggin');
		}

		$error = array(
			'id' => $jobId,
			'message' => $message,
			'error' => (bool)$error
		);
		CakeLog::write('job_errors', $error);

		if(!empty($e)) {
			throw $e;
		}
	}

/**
 * @brief lock a job while it is running
 *
 * @param string $id the id of the job to lock
 */
	public function lockJob($id) {
		$this->updateAll(
			array(
				$this->alias . '.locked' => date('Y-m-d H:i:s'),
				$this->alias . '.pid' => $this->pid()
			),
			array(
				$this->alias . '.' . $this->primaryKey => $id,
				$this->alias . '.failed' => null,
				array(
					'or' => array(
						$this->alias . '.locked' => null,
						$this->alias . '.pid' => $this->pid()
					)
				)
			)
		);

		if($this->getAffectedRows() > 0) {
			return true;
		}

		$this->writeLog($id, 'Failed to acquire lock');
		return false;
	}

/**
 * @brief unlock jobs
 *
 * @param string $id pass nothing to unlock by process, or the id of the job to unlock
 *
 * @return see Model::updateAll
 */
	public function unlock($id = null) {
		$fields = array(
			$this->alias . '.locked' => null,
			$this->alias . '.pid' => null
		);

		$updated = false;
		if(empty($id)) {
			$id = $this->find(
				'list',
				array(
					'fields' => array(
						$this->alias . '.' . $this->primaryKey,
						$this->alias . '.' . $this->primaryKey,
					),
					'conditions' => array(
						$this->alias . '.pid' => $this->pid()
					)
				)
			);
			$updated = $this->updateAll(
				$fields,
				array(
					$this->alias . '.pid' => $this->pid()
				)
			);
		} else {
			$updated = $this->updateAll(
				$fields,
				array(
					$this->alias . '.' . $this->primaryKey => $id
				)
			);
		}

		if($updated) {
			return $this->writeLog($id, 'Unlocking the job', false);
		}

		$this->writeLog($id, 'Failed to unlock the job');
		return false;
	}

/**
 * @brief close a job off once it is done
 *
 * @param string $id the id of the job being closed
 */
	public function finishJob($id, $error = null) {
		if($error) {
			$this->writeLog($id, $error);

			$attempts = $this->find('attempts', $id);
			$fields = array(
				$this->alias . '.attempts' => $attempts + 1
			);

			if($attempts >= $this->find('max_attempts', $id)) {
				$fields[$this->alias . '.failed'] = date('Y-m-d H:i:s');
				$this->writeLog($id, sprintf('Stopping trying after %d attempts', $attempts));
			} else {
				$fields[$this->alias . '.failed'] = null;
				$this->writeLog($id, 'Failure in running the job');
			}

			$this->updateAll(
				$fields,
				array(
					$this->alias . '.' . $this->primaryKey => $id
				)
			);
			return $this->unlock($id);
		}

		$update = $this->updateAll(
			array(
				$this->alias . '.completed' => date('Y-m-d H:i:s')
			),
			array(
				$this->alias . '.' . $this->primaryKey => $id
			)
		);

		if($update) {
			$this->writeLog($id, 'Completed job', false);
			return true;
		}

		$this->writeLog($id, 'Failed to complete the job');
	}

/**
 * @brief reset a job so that it can be attempted again after the set delay
 *
 * @param string $id the id of the job to retry
 * @param integer $delay the amount of time in seconds to wait before running again
 *
 * @return boolean
 */
	public function retryJob($id, $delay = 0) {
		$update = $this->updateAll(
			array(
				$this->alias . '.run_at' => date('Y-m-d H:i:s', time() + $delay),
				$this->alias . '.attempts' => $this->find('attempts', $id) + 1
			),
			array(
				$this->alias . '.' . $this->primaryKey => $id
			)
		);

		if($update) {
			$this->writeLog($id, 'Setting job to retry', false);
			return $this->unlock($id);
		}

		$this->writeLog($id, 'Unable to reset the job');
		return false;
	}

/**
 * enque
 *
 * @param type $handler
 * @param type $queue
 * @param type $run_at
 */
	public function enque($handler, $queue = 'default', $runAt = null) {
		if(is_array($handler)) {
			foreach($handler as $data) {
				self::enque($data, $queue, $run_at);
			}
		}

		$saved = $this->save(
			array(
				'handler' => serialize($handler),
				'infinitas_job_queue_id' => $this->InfinitasJobQueue->find('idFromSlug', $queue),
				'run_at' => $run_at == null ? date('Y-m-d H:i:s') : $runAt
			)
		);

        if (!$saved) {
            throw new CakeException('Failed to save the new job');
        }

        return true;
	}

/**
 * @brief find the next job to run for the specified queue
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return string|boolean false if nothing found, or string uuid of the selected job
 */
	protected function _findJob($state, $query = array(), $results = array()) {
		if($state == 'before') {
			if(empty($query[0])) {
				throw new InvalidArgumentException('No queue was specified');
			}

			$query['fields'] = array(
				$this->alias . '.id'
			);

			$query['conditions'] = array(
				'InfinitasJobQueue.slug' => $query[0],
				$this->alias . '.failed' => null,
				$this->alias . '.attempts < InfinitasJobQueue.max_attempts',
				array(
					'or' => array(
						$this->alias . '.run_at' => null,
						$this->alias . '.run_at >= ' => date('Y-m-d H:i:s')
					)
				),
				array(
					'or' => array(
						$this->alias . '.pid' => null,
						$this->alias . '.pid = ' => $this->pid()
					)
				)
			);

			$query['order'] = array(
				$this->alias . '.created' => 'desc'
			);

			$query['limit'] = 10;

			unset($query[0]);

			return $query;
		}

		foreach($results as $k => $v) {
			if($this->lockJob($v[$this->alias][$this->primaryKey])) {
				$this->writeLog($id, 'Job started', false);
				return $v[$this->alias][$this->primaryKey];
			}
		}

		return false;
	}

/**
 * @brief get the job handler object
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findHandler($state, $query = array(), $results = array()) {
		if($state == 'before') {
			if(empty($query[0])) {
				throw new InvalidArgumentException('Job id must be specified to fetch the handler');
			}

			$query['fields'] = array(
				$this->alias . '.handler'
			);

			$query['conditions'] = array(
				$this->alias . '.' . $this->primaryKey => $query[0]
			);
			return $query;
		}

        if(!empty($results[$this->alias]['handler'])) {
			return unserialize($results[$this->alias]['handler']);
		}

		return false;
	}

/**
 * @brief get the number of attempts
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return integer
 */
	protected function _findAttempts($state, $query = array(), $results = array()) {
		if($state == 'before') {
			if(empty($query[0])) {
				throw new InvalidArgumentException('No id specified');
			}

			$query['fields'] = array(
				$this->alias . '.attempts'
			);

			$query['conditions'] = array(
				$this->alias . '.' . $this->primaryKey => $query[0]
			);

			return $query;
		}

		if(!empty($results[0][$this->alias]['attempts'])) {
			return $results[0][$this->alias]['attempts'];
		}

		return 0;
	}
}
