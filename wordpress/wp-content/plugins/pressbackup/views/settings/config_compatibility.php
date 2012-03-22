<?php echo $this->html->css("styles.css");?>

	<div class="tab " ><?echo $this->html->link('Dashboard', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'));?></div>
	<div class="tab " ><?echo $this->html->link('Credentials', array('controller'=>'settings', 'function'=>'config_credentials'));?></div>
	<div class="tab " ><?echo $this->html->link('Settings', array('controller'=>'settings', 'function'=>'config_settings'));?></div>
	<div class="tab tabactive" ><?echo $this->html->link('Compatibility', array('controller'=>'settings', 'function'=>'config_compatibility'));?></div>
	<div class="tabclear"  ><? echo '&nbsp;'; ?></div>
	<div  id="pressbak_content">
		<form method="post" action="<?php echo $this->path->router(array('controller'=>'settings', 'function'=>'config_compatibility_save'));?>" style="width:800px;">
			<h3>Compatibility Settings</h3>

			<?echo $this->msg->show('error');?>
			<?echo $this->msg->show('general');?>

			<div id="bg_tip" class="msgbox warning " style="width:600px; display:none;">
				<p>The process of create and send the backup can take too much time,
				and the browser can crash after wait more that 30 seconds. because
				of thats we have to do that process in background.</p>
				<p>If you see, on the manual backup, that the progress bar go hide
				after a few seconds without the wanted result, try to change the value
				of this setting.</p>
			</div>
			<div id="zip_tip" class="msgbox warning  " style="width:600px; display:none;" >
				<p>The backup is created using zip files, this reduce the weight of them.</p>
				<p>PHP Zip librarie works in almost all hosts, but for the ones with low work
				capacity (poor RAM and/or poor CPU) and hight amount of info (a bigger blog),
				it can take too long or produce errors</p>
				<p>Shell Zip app work faster and take less resources, but it can't be available
				on some hosts</p>
			</div>

			<div style="float: left; width: 350px;">
				<h4>Background process creation <a href="#" onclick="jQuery('#bg_tip').slideToggle();"><?echo $this->html->img('help.png');?></a></h4>
				<ul class="pressbackup-ul">
					<li><input id="bg_10" type="radio" name="data[compatibility][background]" value ="10" <?if ($settings['pressbackup']['compatibility']['background']==10) {?>checked<?}?>> Soft</li>
					<li><input id="bg_20" type="radio" name="data[compatibility][background]" value ="20" <?if ($settings['pressbackup']['compatibility']['background']==20){?>checked<?}?>> Medium</li>
					<?if ($binaries){?>
					<li><input id="bg_30" type="radio" name="data[compatibility][background]" value ="30" <?if ($settings['pressbackup']['compatibility']['background']==30){?>checked<?}?>> Hard</li>
					<?}?>
				</ul>
			</div>
			<div style="float: left; width: 350px;">
				<h4>Zip Creation <a href="#" onclick="jQuery('#zip_tip').slideToggle();"><?echo $this->html->img('help.png');?></a></h4>
				<ul class="pressbackup-ul">
					<li><input id="zip_10" type="radio" name="data[compatibility][zip]" value ="10" <?if ($settings['pressbackup']['compatibility']['zip']==10) {?>checked<?}?>> PHP</li>
					<li><input id="zip_20" type="radio" name="data[compatibility][zip]" value ="20" <?if ($settings['pressbackup']['compatibility']['zip']==20) {?>checked<?}?>> Shell Zip</li>
				</ul>
			</div>
			<div style="clear:both"></div>
			<br/>

			<?echo $this->html->link('Back to dashboard', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'), array('class'=>'button'));?>
			<input class="button" type="button" value="restore" onclick="restore_def();">
			<input class="button" type="submit" value="save">
		</form>
	</div>

	<script type="text/javascript">
		function restore_def (){
			jQuery("#bg_10").click();
			jQuery("#zip_20").click();
			return false;
		}
	</script>