<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllInfinitasJobsTestsTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All InfinitasJobs test');

		$path = CakePlugin::path('InfinitasJobs') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}
