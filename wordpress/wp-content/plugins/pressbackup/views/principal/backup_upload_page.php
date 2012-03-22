<?php echo $this->html->css("styles.css");?>

<div class=" pressbak_content pressbak_content_all">
	<form method="post" action="<?php echo $this->path->router(array('controller'=>'principal', 'function'=>'backup_upload', 'restore'));?>" enctype="multipart/form-data">
		<h3>Restore your site's backup</h3>

		<?echo $this->msg->show('error');?>

		<p>If you previously have downloaded a backup from PressBackup, you can restore it from here.<p/>
		<br/><br/>

		<p><input type="file" name="backup" /></p>
		<p>Max upload size: <?echo ($upload_size);?> Mb</p>
		<p><strong>Be careful!</strong> Your site's data will be replaced and this step <strong>can not</strong> be undo.</p>

		<br/><br/>

		<?echo $this->html->link('Back to dashboard', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'), array('class'=>'button'));?>
		<input class="button" type="submit" value="Upload" <?if ($disable_ulpoad){?>disabled<?}?>>

	</form>
</div>