<?php
class InfinitasJobsEvents extends AppEvents {
	public function onRequireComponentsToLoad(Event $event = null) {
		return array(
			'InfinitasJobs.InfinitasJobs'
		);
	}

/**
 * @brief auto attach the jobs behavior so that it is available for adding jobs
 *
 * @param Event $event
 */
	public function onAttachBehaviors(Event $event = null) {
		if($event->Handler->shouldAutoAttachBehavior()) {
			$event->Handler->Behaviors->attach('InfinitasJobs.InfinitasJobs');
		}
	}
}
