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
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'dashboard'),
		);

		$check = (!empty($Event->Handler->request->params['plugin']) && $Event->Handler->request->params['plugin'] == 'infinitas_jobs') &&
				(!empty($Event->Handler->request->params['controller']) && $Event->Handler->request->params['controller'] == 'infinitas_jobs');

		if($check) {
			$menu['main']['Pending'] = array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'index');
			$menu['main']['Locked'] = array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'locked');
			$menu['main']['Failed'] = array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'failed');
			$menu['main']['Completed'] = array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'completed');
		}

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
