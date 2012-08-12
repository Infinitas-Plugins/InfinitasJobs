<?php
	class InfinitasJobFixture extends CakeTestFixture {
		public $name = 'InfinitasJob';
		
			public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'handler' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'queue' => array('type' => 'string', 'null' => false, 'default' => 'default', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'infinitas_job_queue_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'infinitas_job_error_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
		'attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
		'run' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'completed' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'locked' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'locked_by' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'failed' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'error' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	
			public $records = array(
		array(
			'id' => '5026f2c2-2870-4569-a864-74086318cd70',
			'handler' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'queue' => 'Lorem ipsum dolor sit amet',
			'infinitas_job_queue_id' => 'Lorem ipsum dolor sit amet',
			'infinitas_job_error_count' => 1,
			'attempts' => 1,
			'run' => '2012-08-12 01:03:14',
			'completed' => '2012-08-12 01:03:14',
			'locked' => '2012-08-12 01:03:14',
			'locked_by' => 'Lorem ipsum dolor sit amet',
			'failed' => '2012-08-12 01:03:14',
			'error' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2012-08-12 01:03:14',
			'modified' => '2012-08-12 01:03:14'
		),
	);
		}