<?php
App::uses('InfinitasJobLog', 'InfinitasJobs.Model');

/**
 * InfinitasJobLog Test Case
 *
 */
class InfinitasJobLogTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.infinitas_jobs.infinitas_job_log',
		'plugin.infinitas_jobs.infinitas_job',
		'plugin.infinitas_jobs.infinitas_job_queue'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->InfinitasJobLog = ClassRegistry::init('InfinitasJobs.InfinitasJobLog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->InfinitasJobLog);

		parent::tearDown();
	}

/**
 * testGetViewData method
 *
 * @return void
 */
	public function testGetViewData() {
	}

}
