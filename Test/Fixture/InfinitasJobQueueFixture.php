<?php
	class InfinitasJobQueueFixture extends CakeTestFixture {
		public $name = 'InfinitasJobQueue';
		
			public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'pending_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'failed_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'locked_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'completed_job_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
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
			'id' => '5026efba-ebe8-46bf-b0d6-6ffe6318cd70',
			'name' => 'Lorem ipsum dolor sit amet',
			'slug' => 'Lorem ipsum dolor sit amet',
			'pending_job_count' => 1,
			'failed_job_count' => 1,
			'locked_job_count' => 1,
			'completed_job_count' => 1,
			'created' => '2012-08-12 00:50:18',
			'modified' => '2012-08-12 00:50:18'
		),
	);
		}