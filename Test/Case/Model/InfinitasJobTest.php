<?php
App::uses('InfintasJob', 'InfintasJobs.Model');
App::uses('InfinitasJobs_TestJob', 'InfinitasJobs.Lib/Job');

class TestInfinitasJob extends CakeTestCase {

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

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->InfinitasJob = ClassRegistry::init('InfinitasJobs.InfinitasJob');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->InfinitasJob);

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
 * @brief test host name
 */
	public function testHostname() {
		$result = $this->InfinitasJob->hostname();
		$this->assertTrue(!empty($result));
	}

/**
 * @brief test the pid is valid
 */
	public function testPid() {
		$result = $this->InfinitasJob->pid();
		$this->assertTrue(is_int($result));
		$this->assertTrue($result > 0);
	}

/**
 * @brief test for locking with invalid job
 *
 * @expectedException InvalidArgumentException
 */
	public function testLockJobInvalid() {
		$this->InfinitasJob->lockJob('fake-id');
	}

/**
 * @brief test locking jobs
 */
	public function testLockJob() {
		$result = $this->InfinitasJob->lockJob('job-1a');
		$this->assertTrue($result);

		$result = $this->InfinitasJob->lockJob('job-2a');
		$this->assertTrue($result);

		$fields = array(
			'InfinitasJob.id',
			'InfinitasJob.locked',
			'InfinitasJob.pid'
		);
		$expected = array(
			array(
				'InfinitasJob' => array(
					'id' => 'job-1a',
					'locked' => true,
					'pid' => $this->InfinitasJob->pid()
				)
			),
			array(
				'InfinitasJob' => array(
					'id' => 'job-1b',
					'locked' => null,
					'pid' => null
				)
			),
			array(
				'InfinitasJob' => array(
					'id' => 'job-2a',
					'locked' => true,
					'pid' => $this->InfinitasJob->pid()
				)
			)
		);
		$result = $this->InfinitasJob->find('all', array('fields' => $fields));
		foreach($result as $k => $v) {
			$this->assertEquals($expected[$k]['InfinitasJob']['id'], $v['InfinitasJob']['id']);
			$this->assertEquals($expected[$k]['InfinitasJob']['locked'], (bool)$v['InfinitasJob']['locked']);
			$this->assertEquals($expected[$k]['InfinitasJob']['pid'], $v['InfinitasJob']['pid']);
		}
	}

/**
 * @brief test locking a job that has failed
 *
 * @expectedException CakeException
 */
	public function testLockFailedJob() {
		for($i = 0; $i < 6; $i++) {
			$this->InfinitasJob->lockJob('job-2a');
		}
	}

/**
 * @brief test unlocking by the job id
 */
	public function testUnlockJobById() {
		$result = $this->InfinitasJob->lockJob('job-1a');
		$this->assertTrue($result);

		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read();
		$this->assertTrue((bool)$result['InfinitasJob']['locked']);

		$this->assertTrue($this->InfinitasJob->unlock('job-1a'));
		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read();
		$this->assertFalse((bool)$result['InfinitasJob']['locked']);
	}

/**
 * @brief test unlocking by the pid
 */
	public function testUnlockJobByPid() {
		$result = $this->InfinitasJob->lockJob('job-1a');
		$this->assertTrue($result);

		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read();
		$this->assertTrue((bool)$result['InfinitasJob']['locked']);

		$this->assertTrue($this->InfinitasJob->unlock());
		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read();
		$this->assertFalse((bool)$result['InfinitasJob']['locked']);
	}

/**
 * @brief test finishing a job off
 */
	public function testFinishJob() {
		$result = $this->InfinitasJob->lockJob('job-1a');
		$this->assertTrue($result);

		$this->assertTrue($this->InfinitasJob->finishJob('job-1a'));

		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read();
		$this->assertTrue((bool)$result['InfinitasJob']['completed']);
		$this->assertFalse((bool)$result['InfinitasJob']['locked']);
		$this->assertEquals(1, $result['InfinitasJob']['attempts']);
	}

/**
 * @brief test finishing a job when there is an error
 */
	public function testFinishJobWithError() {
		$result = $this->InfinitasJob->lockJob('job-1a');
		$this->assertTrue($result);

		$this->assertTrue($this->InfinitasJob->finishJob('job-1a', 'Job failed'));

		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read();
		$this->assertFalse((bool)$result['InfinitasJob']['completed']);
		$this->assertFalse((bool)$result['InfinitasJob']['locked']);
		$this->assertEquals(1, $result['InfinitasJob']['attempts']);

		for($i = 0; $i < 10; $i++) {
			$this->assertTrue($this->InfinitasJob->lockJob('job-1a'));
			$this->assertTrue($this->InfinitasJob->finishJob('job-1a', 'Job failed'));
		}

		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read();

		$this->assertEquals(11, $result['InfinitasJob']['attempts']);
		$this->assertTrue((bool)$result['InfinitasJob']['failed']);
		$this->assertFalse((bool)$result['InfinitasJob']['completed']);
	}

	public function testRetryJob() {
		$fields = array('id', 'attempts', 'run_at');
		$this->assertTrue($this->InfinitasJob->lockJob('job-1a'));

		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read($fields);
		$expected = array(
			'id' => 'job-1a',
			'attempts' => 0,
			'run_at' => '2012-08-13 11:57:22'
		);
		$this->assertEquals($expected, $result['InfinitasJob']);

		$this->assertTrue($this->InfinitasJob->retryJob('job-1a', 7200));

		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read($fields);
		$expected = array(
			'id' => 'job-1a',
			'attempts' => '1',
			'run_at' => '2012-08-13 11:57:22'
		);
		$this->assertEquals($expected['attempts'], $result['InfinitasJob']['attempts']);
		$this->assertTrue($result['InfinitasJob']['run_at'] > date('Y-m-d H:i:s', time() + 7195));

		$this->assertTrue($this->InfinitasJob->retryJob('job-1a', 5000));

		$this->InfinitasJob->id = 'job-1a';
		$result = $this->InfinitasJob->read($fields);
		$expected = array(
			'id' => 'job-1a',
			'attempts' => '2',
			'run_at' => '2012-08-13 11:57:22'
		);
		$this->assertEquals($expected['attempts'], $result['InfinitasJob']['attempts']);
		$this->assertTrue($result['InfinitasJob']['run_at'] > date('Y-m-d H:i:s', time() + 4995));
	}

/**
 * @brief test enqueue
 */
	public function testEnqueue() {
		$this->assertTrue($this->InfinitasJob->enqueue(new InfinitasJobs_TestJob(), 'queue1'));
		$this->assertEquals(3, $this->InfinitasJob->find('count', array('conditions' => array('infinitas_job_queue_id' => 'queue-1'))));

		$this->assertTrue($this->InfinitasJob->enqueue(new InfinitasJobs_TestJob(), 'queue1'));
		$this->assertEquals(4, $this->InfinitasJob->find('count', array('conditions' => array('infinitas_job_queue_id' => 'queue-1'))));

		$jobs = array(
			new InfinitasJobs_TestJob(),
			new InfinitasJobs_TestJob(),
			new InfinitasJobs_TestJob(),
			new InfinitasJobs_TestJob(),
			new InfinitasJobs_TestJob(),
			new InfinitasJobs_TestJob()
		);
		$this->assertTrue($this->InfinitasJob->enqueue($jobs, 'queue1'));
		$this->assertEquals(10, $this->InfinitasJob->find('count', array('conditions' => array('infinitas_job_queue_id' => 'queue-1'))));

		$this->assertTrue($this->InfinitasJob->enqueue(new InfinitasJobs_TestJob(), 'queue2'));
		$this->assertEquals(2, $this->InfinitasJob->find('count', array('conditions' => array('infinitas_job_queue_id' => 'queue-2'))));
	}

/**
 * @brief test enqueue incorrectly
 *
 * @expectedException PHPUnit_Framework_Error_Warning
 */
	public function testEnqueueFails($data) {
		$this->InfinitasJob->enqueue('', $data['queue']);
	}

	public function testFindJob() {
		$expected = array(
		'InfinitasJob' => array (
				'id' => 'job-1a',
				'handler' => 'O:21:"InfinitasJobs_TestJob":1:{s:10:"_internals";a:0:{}}',
			),
			'InfinitasJobQueue' => array (
				'name' => 'Queue1',
				'slug' => 'queue1',
				'max_attempts' => '10',
			)
		);
		$result = $this->InfinitasJob->find('job', 'queue1');
		$this->assertEquals($expected, $result);

		$expected = array(
		'InfinitasJob' => array (
				'id' => 'job-2a',
				'handler' => 'O:21:"InfinitasJobs_TestJob":1:{s:10:"_internals";a:0:{}}',
			),
			'InfinitasJobQueue' => array (
				'name' => 'Queue2',
				'slug' => 'queue2',
				'max_attempts' => '5',
			)
		);
		$result = $this->InfinitasJob->find('job', 'queue2');
		$this->assertEquals($expected, $result);

		$expected = array(
		'InfinitasJob' => array (
				'id' => 'job-1b',
				'handler' => 'O:21:"InfinitasJobs_TestJob":1:{s:10:"_internals";a:0:{}}',
			),
			'InfinitasJobQueue' => array (
				'name' => 'Queue1',
				'slug' => 'queue1',
				'max_attempts' => '10',
			)
		);
		$result = $this->InfinitasJob->find('job', 'queue1');
		$this->assertEquals($expected, $result);

		$this->assertFalse($this->InfinitasJob->find('job', 'queue1'));
		$this->assertFalse($this->InfinitasJob->find('job', 'queue2'));
	}

	public function testFindJobThatFailedBefore() {
		$this->assertTrue($this->InfinitasJob->delete('job-1b'));

		$expected = array(
		'InfinitasJob' => array (
				'id' => 'job-1a',
				'handler' => 'O:21:"InfinitasJobs_TestJob":1:{s:10:"_internals";a:0:{}}',
			),
			'InfinitasJobQueue' => array (
				'name' => 'Queue1',
				'slug' => 'queue1',
				'max_attempts' => '10',
			)
		);

		for($i = 0; $i < 10; $i++) {
			$result = $this->InfinitasJob->find('job', 'queue1');
			$this->assertEquals($expected, $result);
			$this->assertTrue($this->InfinitasJob->finishJob('job-1a', 'Some error'));
		}

		$this->assertFalse($this->InfinitasJob->find('job', 'queue1'));
	}

/**
 * @brief exception when no queue selected
 *
 * @expectedException InvalidArgumentException
 */
	public function testFindJobException() {
		$this->InfinitasJob->find('job');
	}

/**
 * @brief exception when no job selected
 *
 * @expectedException InvalidArgumentException
 */
	public function testFindHandlerException() {
		$this->InfinitasJob->find('handler');
	}

/**
 * @brief test getting a jobs handler
 */
	public function testGetHandler() {
		$result = $this->InfinitasJob->find('handler', 'job-1a');
		$this->assertTrue($result instanceof CakeJob);

		$this->assertFalse($this->InfinitasJob->find('handler', 'fake'));
	}
}