<?php echo $this->html->css("styles.css");?>

	<?if($error){?>
		<div class="updated fade" style="width:800px;">
			<p><b>Important!</b></p>
			<?foreach($error as $key => $value){?>
			<p>* <?echo $value;?></p>
			<?}?>
		</div><br/>
	<?}?>

	<div class=" pressbak_content pressbak_content_all">
		<form method="post" action="<?php echo $this->path->router(array('controller'=>'settings', 'function'=>'config_step1'));?>">
			<h4>Welcome to PressBackup</h4>

			<p>Thanks for installing PressBackup. You can run scheduled backups if you have an Amazon S3 server configured, or you can perform manual backups whenever you want to.</p>
			<p>Now you have to setup your settings on a quick wizard clicking the following button.</p>
			<input class="button" type="submit" value="Start wizard" <?if($disable_backup_files){?>disabled<?}?>>
		</form>
	</div><br/>

	<div class=" pressbak_content pressbak_content_all">
		<h4>Signup for PressBackup News to get updates</h4>
		<form action="http://infinimedia.createsend.com/t/y/s/otyxj/" method="post" id="subForm">
			<div>
				<input type="text" name="cm-otyxj-otyxj" id="otyxj-otyxj" value="Your email address here" onfocus="if(jQuery(this).val() == 'Your email address here'){ jQuery(this).val('') }" onblur="if(jQuery(this).val() == ''){ jQuery(this).val('Your email address here') }" style="font-size:14px; font-style:italic; color:#565656" />&nbsp;&nbsp;
				<input class="button" type="submit" value="Subscribe" />
			</div>
		</form>
	</div>