<div class="updated fade" style="width:500px;" >
	<p>
		* files container :<br/>
		<? for($i=0; $i < count($tmp_dir); $i++){ if(in_array($tmp_dir[$i], array('.', '..'))){continue;} echo '&nbsp;&nbsp;'.$tmp_dir[$i]; echo "<br/>\n";}?>
		<br/>
		* log container :<br/>
		<? for($i=0; $i < count($log_dir); $i++){ if(in_array($log_dir[$i], array('.', '..'))){continue;} echo '&nbsp;&nbsp;'.$log_dir[$i]; echo "<br/>\n";}?>
	</p>

	<p>
		* Log :<br/>
		<?echo $error_log;?>
	</p>

	<p>
		now: <?echo  date('< d M y - H:i:s >'); ?><br/>
		cron: <?echo  date('< d M y - H:i:s >', wp_next_scheduled('pressback_backup_start_cronjob') ); ?><br/>
		down: <?echo  date('< d M y - H:i:s >', wp_next_scheduled('pressback_backupnow_download') ); ?><br/>
		downA: <?echo  date('< d M y - H:i:s >', wp_next_scheduled('pressback_backupnow_download_ajax') ); ?><br/>
		save: <?echo  date('< d M y - H:i:s >', wp_next_scheduled('pressback_backupnow_save') ); ?><br/>
		saveA: <?echo  date('< d M y - H:i:s >', wp_next_scheduled('pressback_backupnow_save_ajax') ); ?><br/>
		<?		@wp_clear_scheduled_hook('pressback_backup_start_cronjob'); ?>
	</p>
</div>
<?echo $this->html->link('Clear TMP folders', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'clean'), array('class'=>'button'));?>
<?echo $this->html->link('Back to dashboard', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'), array('class'=>'button'));?>
