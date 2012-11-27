<?php
CakeLog::config('job_errors', array(
	'engine' => 'InfinitasJobs.InfinitasJobLogger',
	'model' => 'InfinitasJobs.InfinitasJobError'
));

class InfinitasJobsEvents extends AppEvents {
/**
 * @brief admin dashboard icon
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Jobs',
			'description' => 'Job worker script for background processes',
			'icon' => '/infinitas_jobs/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array(
				'plugin' => 'infinitas_jobs',
				'controller' => 'infinitas_jobs',
				'action' => 'dashboard'
			)
		);
	}

/**
 * @brief create navigation menu items
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu = array(
			'main' => array(
				'Dashboard' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'dashboard')
			),
			'filter' => array(
				'Running' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_job_queues', 'action' => 'index', 'type' => 'running'),
				'Pending' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'index'),
				'Locked' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'index', 'type' => 'locked'),
				'Failed' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'index', 'type' => 'failed'),
				'Completed' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'index', 'type' => 'completed')
			)
		);

		return $menu;
	}

/**
 * @brief load components
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'InfinitasJobs.InfinitasJobs'
		);
	}

/**
 * @brief auto attach the jobs behavior so that it is available for adding jobs
 *
 * @param Event $Event
 *
 * @return void
 */
	public function onAttachBehaviors(Event $Event) {
		if($Event->Handler->shouldAutoAttachBehavior()) {
			$Event->Handler->Behaviors->attach('InfinitasJobs.InfinitasJobs');
		}
	}
}
