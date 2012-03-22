<?php echo $this->html->css("styles.css");?>

	<div class="tab " ><?echo $this->html->link('Dashboard', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'));?></div>
	<div class="tab tabactive" ><?echo $this->html->link('Credentials', array('controller'=>'settings', 'function'=>'config_credentials'));?></div>
	<div class="tab " ><?echo $this->html->link('Settings', array('controller'=>'settings', 'function'=>'config_settings'));?></div>
	<div class="tab " ><?echo $this->html->link('Compatibility', array('controller'=>'settings', 'function'=>'config_compatibility'));?></div>

	<div class="tabclear" ><? echo '&nbsp;'; ?></div>
	<div  id="pressbak_content">

		<form method="post" action="<?php echo $this->path->router(array('controller'=>'settings', 'function'=>'config_credentials_save'));?>" style="width:800px;">
			<h3>Change Credentials</h3>

			<?echo $this->msg->show('error');?>

			<p>Here you can configure your Amazon S3 server or your Pressbackup Pro server, to automatically performs backups.<br/>
			You can select "No", and download manual backups whenever you want to.</p>
			<h4>Do you have an Amazon S3 or PressBackup Pro account?</h4>

			<div style="float: left; width: 350px;">
				<p><input type="radio" id="credentialNoRadio" name="data[credentials]" value="no" onclick="jQuery('#s3yes').hide(); jQuery('#proyes').hide();" /> <label for="credentialNoRadio">No</label></p>
				<p><input type="radio" id="credentialS3Radio" name="data[credentials]" value="s3" onclick="jQuery('#proyes').hide(); jQuery('#s3yes').slideToggle('fast');" /> <label for="credentialS3Radio">Amazon S3</label></p>
				<p><input type="radio" id="credentialProRadio" name="data[credentials]" value="pro" onclick="jQuery('#s3yes').hide(); jQuery('#proyes').slideToggle('fast');" /> <label for="credentialProRadio">Pressbackup Pro</label> &raquo; <a href="http://pressbackup.com/pro/pricing" target="_blank">Signup for an account!</a></p>
			</div>

			<div style="float: left; width: 400px; padding-top:12px;">
				<div class="doyouhaves3" id="s3yes">
					<label class="label_for_input">S3 accessKey</label><br/><input id="s3yesa" type="text" class="longinput" name="data[preferences][S3][accessKey]"><br/>
					<label class="label_for_input">S3 secretKey</label><br/><input id="s3yesb" type="text" class="longinput" name="data[preferences][S3][secretKey]"><br/>
					<?echo $this->html->link('Advanced options', '#', array('id'=>'ao_link'));?>
					<div id="S3advanced"style="display:none">
						<p>
						<input type="radio" id="s3_reg_us" name="data[preferences][S3][region]" value="US" checked> <label for="s3_reg_us">US S3</label>&nbsp;&nbsp;
						<input type="radio" id="s3_reg_eu" name="data[preferences][S3][region]" value="EU"> <label for="s3_reg_eu">European S3</label>
						</p>
					</div>
				</div>

				<div class="doyouhaves3" id="proyes">
					<label class="label_for_input">Pressbackup Pro User</label><br/><input id="proyesa" type="text" class="longinput" name="data[preferences][pressbackuppro][user]"><br/>
					<label class="label_for_input">Pressbackup Pro AuthKey</label><br/><input id="proyesb" type="text" class="longinput" name="data[preferences][pressbackuppro][key]"><br/>
				</div>
			</div>
			<div style="clear:both"></div><br/>

			<?echo $this->html->link('Back to dashboard', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'), array('class'=>'button'));?>
			<input class="button" type="submit" value="save">
		</form>

	</div>

<script type='text/javascript'>
	var credentials_type = '<?echo $credentials_type; ?>';
	var credentials = [ '<?echo $credentials[0]; ?>', '<?echo $credentials[1]; ?>'];

	jQuery('#ao_link').click(function(){
		jQuery('#S3advanced').slideToggle();
		return false;
	});

	if (credentials_type == 's3')
	{
		jQuery('#credentialS3Radio').attr('checked', true);
		jQuery('#s3yes').show();
		jQuery('#s3yesa').val(credentials[0]);
		jQuery('#s3yesb').val(credentials[1]);
	}
	else if (credentials_type == 'pro')
	{
		jQuery('#credentialProRadio').attr('checked', true);
		jQuery('#proyes').show();
		jQuery('#proyesa').val(credentials[0]);
		jQuery('#proyesb').val(credentials[1]);
	}
	else{
		jQuery('#credentialNoRadio').attr('checked', true);
	}

</script>


