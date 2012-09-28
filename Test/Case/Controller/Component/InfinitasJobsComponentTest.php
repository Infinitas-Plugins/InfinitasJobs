<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('InfinitasJobsComponent', 'InfinitasJobs.Controller/Component');

/**
 * InfinitasJobsComponent Test Case
 *
 */
class InfinitasJobsComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->InfinitasJobs = new InfinitasJobsComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->InfinitasJobs);

		parent::tearDown();
	}

/**
 * testLoad method
 *
 * @return void
 */
	public function testLoad() {
	}

/**
 * testEnqueue method
 *
 * @return void
 */
	public function testEnqueue() {
	}

/**
 * testStatus method
 *
 * @return void
 */
	public function testStatus() {
	}

}
