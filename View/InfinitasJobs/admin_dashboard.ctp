<?php
$links = array();
$links['main'] = array(
	array(
		'name' => __d('infinitas_jobs', 'Queues'),
		'description' => __d('aqua_companies', 'View and manage various job queues'),
		'icon' => '/infinitas_jobs/img/queues.png',
		'dashboard' => array('controller' => 'infinitas_job_queues', 'action' => 'index')
	),
	array(
		'name' => __d('infinitas_jobs', 'Jobs'),
		'description' => __d('infinitas_jobs', 'View and manage jobs in the system'),
		'icon' => '/infinitas_jobs/img/icon.png',
		'dashboard' => array('controller' => 'infinitas_jobs', 'action' => 'index')
	),
);

$links['main'] = $this->Design->arrayToList(current($this->Menu->builDashboardLinks($links['main'], 'infinitas_jobs_dashboard')), array(
	'ul' => 'icons'
));
echo $this->Design->dashboard($links['main'], __d('infinitas_jobs', 'Jobs'), array(
	'class' => 'dashboard span6'
));