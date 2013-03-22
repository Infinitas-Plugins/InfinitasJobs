<?php	
class TestInfinitasJobQueue extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.infinitas_jobs.infinitas_job',
		'plugin.infinitas_jobs.infinitas_job_queue',
		'plugin.infinitas_jobs.infinitas_job_log',
	);

	public function setUp() {
		parent::setUp();
		$this->InfinitasJobQueue = ClassRegistry::init('InfinitasJobs.InfinitasJobQueue');
	}

	public function tearDown() {
		unset($this->InfinitasJobQueue);
		parent::tearDown();
	}


	/**
	 * @brief Tests Validation
	 *
	 * @test Enter description here
	 */
	public function testValidation() {
		
	}

/**
 * test find id from slug exception
 * 
 * @expectedException InvalidArgumentException
 */
	public function testFindIdFromSlugException() {
		$this->InfinitasJobQueue->find('idFromSlug');
	}

/**
 * test find id from slug
 */
	public function testFindIdFromSlug() {
		$result = $this->InfinitasJobQueue->find('idFromSlug', 'queue1');
		$this->assertEquals('queue-1', $result);

		$result = $this->InfinitasJobQueue->find('idFromSlug', 'queue2');
		$this->assertEquals('queue-2', $result);

		$result = $this->InfinitasJobQueue->find('idFromSlug', 'queue*');
		$this->assertEquals('queue-2', $result);

		$this->assertTrue($this->InfinitasJobQueue->updateAll(
			array($this->InfinitasJobQueue->alias . '.pending_job_count' => 20),
			array($this->InfinitasJobQueue->alias . '.id' => 'queue-2')
		));

		$result = $this->InfinitasJobQueue->find('idFromSlug', 'queue*');
		$this->assertEquals('queue-1', $result);

		$result = $this->InfinitasJobQueue->find('idFromSlug', 'foobar');
		$this->assertFalse($result);
	}

/**
 * test find status
 * 
 * @dataProvider findStatusDataProvider
 */
	public function testFindStatus($data, $expected) {
		$result = $this->InfinitasJobQueue->find('status', $data);
		$this->assertEquals($expected, $result);
	}

/**
 * find status data provider
 * 
 * @return array
 */
	public function findStatusDataProvider() {
		return array(
			'nothing' => array(
				'foo-bar',
				array()
			),
			'queue 1' => array(
				'queue1',
				array(
					'outstanding' => 2,
					'locked' => 0,
					'failed' => 0,
					'completed' => 0,
					'total'  => 2
				)
			),
			'queue 2' => array(
				'queue2',
				array(
					'outstanding' => 1,
					'locked' => 0,
					'failed' => 0,
					'completed' => 0,
					'total'  => 1
				)
			)
		);
	}
}