<?php
App::uses('AppShell', 'Console/Command');
App::uses('ConnectionManager', 'Model');
App::uses('CakeJob', 'InfinitasJobs.Job');

App::uses('DJJob', 'InfinitasJobs.Lib/DJJob');
App::uses('DJWorker', 'InfinitasJobs.Lib/DJJob');

/**
 * Convenience method to unserialize InfinitasJobs classes properly
 *
 * Uses _ (underscore) to denote plugins, since php classnames
 * cannot use periods
 *
 * @package default
 */
function unserialize_jobs($className) {
	$pluginDot = null;
	if ($plugin = strstr($className, '_', true)) {
		$pluginDot = $plugin . '.';
	}

	App::uses($className, $pluginDot . 'Job');
}

/**
 * Worker runs jobs created by the DJJob system.
 *
 * @package job_workers
 */
class WorkerShell extends AppShell {

	public $tasks = array(
		'InfinitasJobs.Cleanup',
		'InfinitasJobs.Run',
		'InfinitasJobs.Status'
	);

/**
 * Override main() for help message hook
 *
 * @return void
 */
	public function main() {
		$this->out(__d('cake_djjob', '<info>InfinitasJobs Worker Shell</info>'));
		$this->hr();
		$this->out(__d('cake_djjob', '[R]un jobs in the system'));
		$this->out(__d('cake_djjob', '[S]tatus of system'));
		$this->out(__d('cake_djjob', '[C]leans a job queue'));
		$this->out(__d('cake_djjob', '[Q]uit'));

		$choice = strtolower($this->in(__d('cake_djjob', 'What would you like to do?'), array('R', 'S', 'C', 'Q')));
		switch ($choice) {
			case 'r':
				$this->Run->execute();
			break;
			case 's':
				$this->Status->execute();
			break;
			case 'c':
				$this->Cleanup->execute();
			break;
			case 'q':
				exit(0);
			break;
			default:
				$this->out(__d('cake_djjob', 'You have made an invalid selection. Please choose a command to execute by entering R, S, C, or Q.'));
		}
		$this->hr();
		$this->main();
	}

/**
 * Get and configure the Option parser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(
			__d('cake_djjob', 'The Worker Shell runs jobs created by the DJJob system.')
		)->addOptions(array(
			'connection' => array(
				'help' => __('Set db config'),
				'default' => 'default',
			),
			'type' => array(
				'help' => __('PDO name for connection <type>'),
				'default' => 'mysql',
			),
			'debug' => array(
				'help' => __('Set debug level dynamically for running jobs'),
				'default' => 0,
				'choices' => array(0, 1, 2)
			),
			'queue' => array(
				'help' => __('Queue <name> to pul jobs from'),
				'default' => 'default',
			),
		))->addSubcommand('run', array(
			'help' => __d('cake_djjob', 'runs jobs in the system'),
			'parser' => $this->Run->getOptionParser()
		))->addSubcommand('status', array(
			'help' => __d('cake_djjob', 'returns the status of a job queue'),
			'parser' => $this->Status->getOptionParser()
		))->addSubcommand('cleanup', array(
			'help' => __d('cake_djjob', 'cleans a job queue'),
			'parser' => $this->Cleanup->getOptionParser()
		));
	}

}