<?php
class InfinitasJobQueueFixture extends CakeTestFixture {
	public $name = 'InfinitasJobQueue';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'max_attempts' => array('type' => 'integer', 'null' => false, 'default' => '5', 'length' => 5),
		'sleep_between' => array('type' => 'integer', 'null' => false, 'default' => '5', 'length' => 5),
		'pending_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'failed_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'locked_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'completed_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'pid' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 5),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'name' => array('column' => 'name', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 'queue-1',
			'name' => 'Queue1',
			'slug' => 'queue1',
			'max_attempts' => 10,
			'sleep_between' => 10,
			'pending_job_count' => 2,
			'failed_job_count' => 0,
			'locked_job_count' => 0,
			'completed_job_count' => 0,
			'pid' => null,
			'created' => '2012-08-13 13:43:56',
			'modified' => '2012-08-13 13:43:56'
		),
		array(
			'id' => 'queue-2',
			'name' => 'Queue2',
			'slug' => 'queue2',
			'max_attempts' => 5,
			'sleep_between' => 5,
			'pending_job_count' => 1,
			'failed_job_count' => 0,
			'locked_job_count' => 0,
			'completed_job_count' => 0,
			'pid' => null,
			'created' => '2012-08-13 13:43:56',
			'modified' => '2012-08-13 13:43:56'
		)
	);
}