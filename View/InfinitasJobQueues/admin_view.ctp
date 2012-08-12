<div class="infinitasJobQueues view">
<h2><?php echo __('Infinitas Job Queue');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Slug'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['slug']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Pending Job Count'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['pending_job_count']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Failed Job Count'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['failed_job_count']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Locked Job Count'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['locked_job_count']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Completed Job Count'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['completed_job_count']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasJobQueue['InfinitasJobQueue']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit %s'), __('Infinitas Job Queue')), array('action' => 'edit', $infinitasJobQueue['InfinitasJobQueue']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete %s'), __('Infinitas Job Queue')), array('action' => 'delete', $infinitasJobQueue['InfinitasJobQueue']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $infinitasJobQueue['InfinitasJobQueue']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s'), __('Infinitas Job Queues')), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Infinitas Job Queue')), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s'), __('Infinitas Jobs')), array('controller' => 'infinitas_jobs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Infinitas Job')), array('controller' => 'infinitas_jobs', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php printf(__('Related %s'), __('Infinitas Jobs'));?></h3>
	<?php if (!empty($infinitasJobQueue['InfinitasJob'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Handler'); ?></th>
		<th><?php echo __('Queue'); ?></th>
		<th><?php echo __('Infinitas Job Queue Id'); ?></th>
		<th><?php echo __('Infinitas Job Error Count'); ?></th>
		<th><?php echo __('Attempts'); ?></th>
		<th><?php echo __('Run'); ?></th>
		<th><?php echo __('Completed'); ?></th>
		<th><?php echo __('Locked'); ?></th>
		<th><?php echo __('Locked By'); ?></th>
		<th><?php echo __('Failed'); ?></th>
		<th><?php echo __('Error'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($infinitasJobQueue['InfinitasJob'] as $infinitasJob):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $infinitasJob['id'];?></td>
			<td><?php echo $infinitasJob['handler'];?></td>
			<td><?php echo $infinitasJob['queue'];?></td>
			<td><?php echo $infinitasJob['infinitas_job_queue_id'];?></td>
			<td><?php echo $infinitasJob['infinitas_job_error_count'];?></td>
			<td><?php echo $infinitasJob['attempts'];?></td>
			<td><?php echo $infinitasJob['run'];?></td>
			<td><?php echo $infinitasJob['completed'];?></td>
			<td><?php echo $infinitasJob['locked'];?></td>
			<td><?php echo $infinitasJob['locked_by'];?></td>
			<td><?php echo $infinitasJob['failed'];?></td>
			<td><?php echo $infinitasJob['error'];?></td>
			<td><?php echo $infinitasJob['created'];?></td>
			<td><?php echo $infinitasJob['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'infinitas_jobs', 'action' => 'view', $infinitasJob['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'infinitas_jobs', 'action' => 'edit', $infinitasJob['id'])); ?>
				<?php echo $this->Html->link(__('Delete'), array('controller' => 'infinitas_jobs', 'action' => 'delete', $infinitasJob['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $infinitasJob['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Infinitas Job')), array('controller' => 'infinitas_jobs', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
