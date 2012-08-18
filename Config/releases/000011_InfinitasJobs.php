<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R50301f090fb040c0913c549c6318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for InfinitasJobs version 0.1.1';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'InfinitasJobs';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'create_table' => array(
				'infinitas_job_logs' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'message' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'infinitas_job_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'message'),
					'error' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'after' => 'infinitas_job_id'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'error'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'infinitas_job_queues' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_bin', 'charset' => 'utf8'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_bin', 'charset' => 'utf8', 'after' => 'id'),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_bin', 'charset' => 'utf8', 'after' => 'name'),
					'max_attempts' => array('type' => 'integer', 'null' => false, 'default' => '5', 'length' => 5, 'after' => 'slug'),
					'sleep_between' => array('type' => 'integer', 'null' => false, 'default' => '5', 'length' => 5, 'after' => 'max_attempts'),
					'retry_delay' => array('type' => 'integer', 'null' => false, 'default' => '7200', 'length' => 8, 'after' => 'sleep_between'),
					'pending_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'after' => 'retry_delay'),
					'failed_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'after' => 'pending_job_count'),
					'locked_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'after' => 'failed_job_count'),
					'completed_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'after' => 'locked_job_count'),
					'pid' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5, 'after' => 'completed_job_count'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'pid'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'name' => array('column' => 'name', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'InnoDB'),
				),
			),
			'create_field' => array(
				'infinitas_jobs' => array(
					'infinitas_job_queue_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'host' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'infinitas_job_queue_id'),
					'pid' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'host'),
					'completed' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'run_at'),
					'locked' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'completed'),
					'failed' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'locked'),
					'infinitas_job_error_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'after' => 'failed'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'infinitas_job_error_count'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
				),
			),
			'drop_field' => array(
				'infinitas_jobs' => array('queue', 'locked_at', 'locked_by', 'failed_at', 'error', 'created_at',),
			),
			'alter_field' => array(
				'infinitas_jobs' => array(
					'attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'infinitas_job_logs', 'infinitas_job_queues'
			),
			'drop_field' => array(
				'infinitas_jobs' => array('infinitas_job_queue_id', 'host', 'pid', 'completed', 'locked', 'failed', 'infinitas_job_error_count', 'created', 'modified',),
			),
			'create_field' => array(
				'infinitas_jobs' => array(
					'queue' => array('type' => 'string', 'null' => false, 'default' => 'default', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'locked_at' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked_by' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'failed_at' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'error' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'created_at' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
				),
			),
			'alter_field' => array(
				'infinitas_jobs' => array(
					'attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
				),
			),
		),
		);

	
	/**
	* Before migration callback
	*
	* @param string $direction, up or down direction of migration process
	* @return boolean Should process continue
	* @access public
	*/
		public function before($direction) {
			return true;
		}

	/**
	* After migration callback
	*
	* @param string $direction, up or down direction of migration process
	* @return boolean Should process continue
	* @access public
	*/
		public function after($direction) {
			return true;
		}
	}