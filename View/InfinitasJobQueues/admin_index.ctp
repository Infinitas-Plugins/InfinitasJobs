<?php
/**
 * @brief Add some documentation for this admin_index form.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/InfinitasJobs
 * @package	   InfinitasJobs.View.admin_index
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */
echo $this->Form->create('InfinitasJobQueue', array('action' => 'mass'));

$massActions = $this->Infinitas->massActionButtons(
	array(
		'add',
		'edit',
		'toggle',
		'copy',
		'delete',

		// other methods available
		// 'unlock',
	)
);

echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
?>
<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
		<?php
			echo $this->Infinitas->adminTableHeader(
				array(
					$this->Form->checkbox('all') => array(
						'class' => 'first',
						'style' => 'width:25px;'
					),
					$this->Paginator->sort('name'),
					$this->Paginator->sort('pending_job_count', __d('infinitas_jobs', 'Pending')) => array(
						'style' => 'width:50px;'
					),
					$this->Paginator->sort('failed_job_count', __d('infinitas_jobs', 'Failed')) => array(
						'style' => 'width:50px;'
					),
					$this->Paginator->sort('locked_job_count', __d('infinitas_jobs', 'Locked')) => array(
						'style' => 'width:50px;'
					),
					$this->Paginator->sort('completed_job_count', __d('infinitas_jobs', 'Completed')) => array(
						'style' => 'width:50px;'
					),
					$this->Paginator->sort('modified') => array(
						'style' => 'width:75px;'
					),
					__d('infinitas_jobs', 'Running') => array(
						'style' => 'width:75px;'
					)
				)
			);

			foreach ($infinitasJobQueues as $infinitasJobQueue) { ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
					<td><?php echo $this->Infinitas->massActionCheckBox($infinitasJobQueue); ?>&nbsp;</td>
					<td title="<?php echo $infinitasJobQueue['InfinitasJobQueue']['slug']; ?>"><?php echo $this->Html->adminQuickLink($infinitasJobQueue['InfinitasJobQueue']); ?>&nbsp;</td>
					<td><?php echo $infinitasJobQueue['InfinitasJobQueue']['pending_job_count']; ?>&nbsp;</td>
					<td><?php echo $infinitasJobQueue['InfinitasJobQueue']['failed_job_count']; ?>&nbsp;</td>
					<td><?php echo $infinitasJobQueue['InfinitasJobQueue']['locked_job_count']; ?>&nbsp;</td>
					<td><?php echo $infinitasJobQueue['InfinitasJobQueue']['completed_job_count']; ?>&nbsp;</td>
					<td><?php echo CakeTime::niceShort($infinitasJobQueue['InfinitasJobQueue']['modified']); ?>&nbsp;</td>
					<td>
						<?php
							$running = false;
							$message = array(
								'title_no' => __d('infinitas_jobs', 'Status :: This job queue is not active')
							);
							if($infinitasJobQueue['InfinitasJobQueue']['pid'] && posix_getsid($infinitasJobQueue['InfinitasJobQueue']['pid'])) {
								$message['title_yes'] = __d('infinitas_jobs', 'Status :: This job queue is currently running (pid: %s)', $infinitasJobQueue['InfinitasJobQueue']['pid']);
								$running = true;
							}

							echo $this->Infinitas->status($running, $message);
						?>&nbsp;
					</td>
				</tr><?php
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>