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
echo $this->Form->create('InfinitasJob', array('action' => 'mass'));

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
					$this->Paginator->sort('queue'),
					$this->Paginator->sort('attempts'),
					$this->Paginator->sort('created_at'),
					$this->Paginator->sort('failed_at', __d('infinitas_jobs', 'Last Fail')),
				)
			);

			foreach ($infinitasJobs as $infinitasJob) { ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?> no-bottom-border">
					<td><?php echo $this->Infinitas->massActionCheckBox($infinitasJob); ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link(
								$infinitasJob['InfinitasJobQueue']['name'],
								array(
									'controller' => 'infinitas_job_queues',
									'action' => 'edit',
									$infinitasJob['InfinitasJobQueue']['id']
								)
							);
						?>&nbsp;
					</td>
					<td><?php echo $infinitasJob['InfinitasJob']['attempts']; ?>&nbsp;</td>
					<td><?php echo CakeTime::niceShort($infinitasJob['InfinitasJob']['created']); ?>&nbsp;</td>
					<td><?php echo CakeTime::niceShort($infinitasJob['InfinitasJob']['failed']); ?>&nbsp;</td>
				</tr><?php
				$i = 0;
				$errorCount = count($infinitasJob['InfinitasJobError']);
				foreach($infinitasJob['InfinitasJobError'] as $error) {
					$errorClass = 'no-bottom-border';
					if($i >= $errorCount) {
						$errorClass = '';
					} ?>
					<tr class="<?php echo $this->Infinitas->rowClass() . ' ' . $errorClass; ?>">
						<td>&nbsp;</td>
						<td colspan="3"><?php echo h($error['error']); ?>&nbsp;</td>
						<td><?php echo CakeTime::niceShort($error['created']); ?>&nbsp;</td>
					</tr> <?php
					$i++;
				}
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>