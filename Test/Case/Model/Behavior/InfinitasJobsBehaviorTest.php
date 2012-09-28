<?php
App::uses('InfinitasJobsBehavior', 'InfinitasJobs.Model/Behavior');

/**
 * InfinitasJobsBehavior Test Case
 *
 */
class InfinitasJobsBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->InfinitasJobs = new InfinitasJobsBehavior();
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
