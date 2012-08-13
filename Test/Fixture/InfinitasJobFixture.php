<?php
	class InfinitasJobFixture extends CakeTestFixture {
		public $name = 'InfinitasJob';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'infinitas_job_queue_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'host' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'pid' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'handler' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
		'run_at' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'completed' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'locked' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'failed' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'infinitas_job_error_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array();

	public function __construct() {
		App::uses('InfinitasJobs_TestJob', 'InfinitasJobs.Lib/Job');

		$this->records = array(
			array(
				'id' => 'job-1a',
				'infinitas_job_queue_id' => 'queue-1',
				'host' => null,
				'pid' => null,
				'handler' => serialize(new InfinitasJobs_TestJob()),
				'attempts' => '0',
				'run_at' => '2012-08-13 11:57:22',
				'completed' => null,
				'locked' => null,
				'failed' => null,
				'infinitas_job_error_count' => '0',
				'created' => '2012-08-13 11:57:22',
				'modified' => '2012-08-13 11:57:22'
			),
			array(
				'id' => 'job-1b',
				'infinitas_job_queue_id' => 'queue-1',
				'host' => null,
				'pid' => null,
				'handler' => serialize(new InfinitasJobs_TestJob()),
				'attempts' => '0',
				'run_at' => '2012-08-13 11:57:22',
				'completed' => null,
				'locked' => null,
				'failed' => null,
				'infinitas_job_error_count' => '0',
				'created' => '2012-08-13 11:57:22',
				'modified' => '2012-08-13 11:57:22'
			),
			array(
				'id' => 'job-2a',
				'infinitas_job_queue_id' => 'queue-2',
				'host' => null,
				'pid' => null,
				'handler' => serialize(new InfinitasJobs_TestJob()),
				'attempts' => '0',
				'run_at' => '2012-08-13 12:22:50',
				'completed' => null,
				'locked' => null,
				'failed' => null,
				'infinitas_job_error_count' => '0',
				'created' => '2012-08-13 12:22:50',
				'modified' => '2012-08-13 12:22:50'
			)
		);

		parent::__construct();
	}
}