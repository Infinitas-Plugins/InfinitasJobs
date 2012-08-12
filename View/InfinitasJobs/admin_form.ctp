<?php
	/**
	 * @brief Add some documentation for this admin_edit form.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link		  http://infinitas-cms.org/InfinitasJobs
	 * @package	   InfinitasJobs.views.admin_edit
	 * @license	   http://infinitas-cms.org/mit-license The MIT License
	 * @since 0.9b1
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	echo $this->Form->create('InfinitasJob');
		echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Infinitas jobs'); ?></h1><?php
				echo $this->Form->input('id');
				echo $this->Form->input('attempts');
				echo $this->Form->input('run_at');
				echo $this->Form->input('locked_at');
				echo $this->Form->input('failed_at');
				echo $this->Form->input('created_at');
				echo $this->Infinitas->wysiwyg('InfinitasJob.handler');
				echo $this->Infinitas->wysiwyg('InfinitasJob.error');
			?>
		</fieldset>

		<fieldset>
			<h1><?php echo __('Configuration'); ?></h1><?php
		?>
		</fieldset><?php
	echo $this->Form->end();
