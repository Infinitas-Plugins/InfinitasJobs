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
					$this->Paginator->sort('locked_at', __d('infintias_jobs', 'Started')),
					$this->Paginator->sort('failed_at', __d('infinitas_jobs', 'Last Fail')),
					$this->Paginator->sort('created_at'),
				)
			);

			foreach ($infinitasJobs as $infinitasJob) { ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
					<td><?php echo $this->Infinitas->massActionCheckBox($infinitasJob); ?>&nbsp;</td>
					<td><?php echo $infinitasJob['InfinitasJob']['queue']; ?>&nbsp;</td>
					<td><?php echo $infinitasJob['InfinitasJob']['attempts']; ?>&nbsp;</td>
					<td><?php echo CakeTime::niceShort($infinitasJob['InfinitasJob']['locked']); ?>&nbsp;</td>
					<td><?php echo CakeTime::niceShort($infinitasJob['InfinitasJob']['failed']); ?>&nbsp;</td>
					<td><?php echo CakeTime::niceShort($infinitasJob['InfinitasJob']['created']); ?>&nbsp;</td>
				</tr><?php
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>