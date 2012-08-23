<?php
/**
 * @brief fixture file for InfinitasJobLog tests.
 *
 * @package InfinitasJobs.Fixture
 * @since 0.9b1
 */
class InfinitasJobLogFixture extends CakeTestFixture {
	public $name = 'InfinitasJobLog';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'error' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'infinitas_job_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'message' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => '5026ef9a-2a8c-4f56-9112-6fc46318cd70',
			'error' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'infinitas_job_id' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-08-12 00:49:46',
			'modified' => '2012-08-12 00:49:46',
			'message' => null
		),
	);
}