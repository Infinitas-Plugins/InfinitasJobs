<?php
class InfinitasJobsEvents extends AppEvents {
/**
 * @brief admin dashboard icon
 *
 * @return array
 */
	public function onPluginRollCall() {
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
 * @param Event $event
 *
 * @return array
 */
	public function onAdminMenu(Event $event) {
		$menu['main'] = array(
			'Jobs' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'dashboard'),
		);

		$check = (!empty($event->Handler->request->params['plugin']) && $event->Handler->request->params['plugin'] == 'infinitas_jobs') &&
				(!empty($event->Handler->request->params['controller']) && $event->Handler->request->params['controller'] == 'infinitas_jobs');

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
 * @param Event $event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $event = null) {
		return array(
			'InfinitasJobs.InfinitasJobs'
		);
	}

/**
 * @brief auto attach the jobs behavior so that it is available for adding jobs
 *
 * @param Event $event
 *
 * @return void
 */
	public function onAttachBehaviors(Event $event = null) {
		if($event->Handler->shouldAutoAttachBehavior()) {
			$event->Handler->Behaviors->attach('InfinitasJobs.InfinitasJobs');
		}
	}
}
