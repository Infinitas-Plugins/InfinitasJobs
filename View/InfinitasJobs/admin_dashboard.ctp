<?php
	$links = array();
	$links['main'] = array(
		array(
			'name' => __d('infinitas_jobs', 'Job Queues'),
			'description' => __d('aqua_companies', 'View and manage various job queues'),
			'icon' => '/infinitas_jobs/img/queues.png',
			'dashboard' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_job_queues', 'action' => 'index')
		),
		array(
			'name' => __d('infinitas_jobs', 'Jobs'),
			'description' => __d('infinitas_jobs', 'View and manage jobs in the system'),
			'icon' => '/infinitas_jobs/img/icon.png',
			'dashboard' => array('plugin' => 'infinitas_jobs', 'controller' => 'infinitas_jobs', 'action' => 'index')
		),
	);
?>
<div class="dashboard grid_16">
	<h1><?php echo __d('infinitas_jobs', 'Jobs'); ?></h1>
	<?php echo $this->Design->arrayToList(current($this->Menu->builDashboardLinks($links['main'], 'infinitas_jobs_dashboard')), 'icons'); ?>
</div>