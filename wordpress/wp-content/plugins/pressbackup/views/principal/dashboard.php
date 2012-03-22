	<?php echo $this->html->css("styles.css");?>

	<?php if($reload) {?>
		<? echo $this->html->css('cupertino/jquery-ui.css');?>
		<? echo $this->html->js('jquery-ui-1.8.4.custom.min.js');?>
		<script type='text/javascript'>
			var task = '<? echo $reload; ?>';
			var reload_url = '<? echo str_replace('&amp;', '&', $this->path->router(array('controller'=>'principal', 'function'=>$reload))); ?>';
			var reload_url_fail = '<? echo str_replace('&amp;', '&', $this->path->router(array('controller'=>'principal', 'function'=>'dashboard'))); ?>';
		</script>
		<? echo $this->html->js("dashreload.js");?>
	<?}?>

	<? @date_default_timezone_set(get_option( 'timezone_string' ));?>

	<div class="tab tabactive" ><?echo $this->html->link('Dashboard', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'));?></div>
	<div class="tab " ><?echo $this->html->link('Credentials', array('menu_type'=>'settings', 'controller'=>'settings', 'function'=>'config_credentials'));?></div>
	<div class="tab " ><?echo $this->html->link('Settings', array('menu_type'=>'settings', 'controller'=>'settings', 'function'=>'config_settings'));?></div>
	<div class="tab " ><?echo $this->html->link('Compatibility', array('menu_type'=>'settings', 'controller'=>'settings', 'function'=>'config_compatibility'));?></div>
	<div class="tabclear"  ><? echo '&nbsp;'; ?></div>
	<div  id="pressbak_content">

	<?echo $this->msg->show('error');?>
	<?echo $this->msg->show('general', array('class'=>"msgbox success"));?>

		<h3>Current setings</h3>
		<table class="widefat" cellspacing="0">
			<tr class="alternate">
				<td class="row-title">Backup type</td>
				<td class="row-title">Scheduled backup</td>
				<td class="row-title">Scheduled time</td>
				<td class="row-title">Server</td>
			</tr>
			<tr>
				<td >
					<?
					$types=array(7=>'Database', 5=>'Themes', 3=>'Plugins', 1=>'Uploads');
					$type = explode(',', $settings['pressbackup']['backup']['type']);
					$backup_type=array();
					for($i = 0; $i<count($type); $i++){$backup_type[]=$types[$type[$i]];}
					echo join(', ',$backup_type);
					?>
				</td>
				<td >
					<strong><i><?if($credentials){echo 'Enabled';}else{echo 'Disabled';}?></i></strong>
				</td>
				<td >
					<?
					if($credentials){
						switch ($settings['pressbackup']['backup']['time'])
						{
							case '12': echo 'Every <strong><i>12 hours</i></strong>.';break;
							case '24': echo 'Every <strong><i>24 hours</i></strong>.';break;
							case '168': echo '<strong><i>Weekly</i></strong>';break;
							case '720': echo '<strong><i>Monthly</i></strong>';break;
						}
						echo ' ( Next: '.date('M d, H:i', $ns).' )';
					}
					else{echo ' -- ';}
					?>
				</td>
				<td ><?if($credentials){echo $credential_type;}else{echo ' -- ';}?></td>
			</tr>
		</table>

		<br/>
		<?echo $this->html->link( $this->html->img('package_get.gif') . ' Backup now (Download)', array('controller'=>'principal', 'function'=>'backup_start', 'backup_download'), array('class'=>'button'));?>
		<?if($credentials){?>
		<?echo $this->html->link( $this->html->img('package_send.gif') . ' Backup now ('.$credential_type.')', array('controller'=>'principal', 'function'=>'backup_start', 'dashboard'), array('class'=>'button', 'id'=>'press_send_backup'));?>
		<?}?>
		<?echo $this->html->link( $this->html->img('package_up.gif') . ' Restore backup (From computer)', array('controller'=>'principal', 'function'=>'backup_upload_page'), array('class'=>'button'));?>

		<br/>

		<?if($credentials){?>
			<br/>
			<h3>Backup list</h3>

			<?$this_site = $all_sites=''; $$from='tabactivelist';?>
			<div class="tab <?echo $this_site;?>" ><?echo $this->html->link('This site', array('controller'=>'principal', 'function'=>'dashboard', 'this_site'));?></div>
			<div class="tab <?echo $all_sites;?>" ><?echo $this->html->link('All sites', array('controller'=>'principal', 'function'=>'dashboard', 'all_sites'));?></div>
			<div class="tabclear" style="" >
				<?if (isset($paginator) && $paginator) { echo  $paginator; } else { echo '&nbsp;'; }?>
			</div>


			<table class="widefat" style="border-radius: 0 0 4px 4px; border-top: none;" cellspacing="0">
				<tr class="alternate">
					<td class="row-title">From</td>
					<td class="row-title">Type</td>
					<td class="row-title">Date</td>
					<td class="row-title">Size</td>
				</tr>
				<?if(count($bucket_files)==0){?>
					<tr>
						<td>Empty</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				<?}?>

				<?$counter=1;?>
				<?foreach($bucket_files as $key => $value){?>
					<tr class="<? if($counter%2==0){echo 'alternate';}?>">
						<?
							$file_name = explode('_', $value['name']);
							$name= str_replace('-', ' ', rawurldecode($file_name[0]));
							$time=date('M d, Y - ga', $value['time']);
							$size=round(($value['size'] / 1048576), 2);
							$types=array(7=>'Database', 5=>'Themes', 3=>'Plugins', 1=>'Uploads');
							$type = explode('-', $file_name[1]);
							$backup_type=array();
							for($i = 0; $i<count($type); $i++){$backup_type[]=$types[$type[$i]];}
						?>
						<td>
							<?echo $name?><br/>
							<div class="options">
								<?echo $this->html->link('Restore', array('controller'=>'principal', 'function'=>'backup_restore', rawurldecode($value['name'])), array('class'=>'pb_restore'));?> |
								<? $function = 'backup_get'; if ($credential_type=='Pressbackup Pro' ){ $function = 'backup_get2';}?>
								<?echo $this->html->link('Download', array('controller'=>'principal', 'function'=>$function, rawurldecode($value['name'])));?> |
								<?echo $this->html->link('Delete', array('controller'=>'principal', 'function'=>'backup_delete', rawurldecode($value['name'])), array('class'=>'pb_delete'));?>
							</div>
						</td>
						<td><?echo join(', ',$backup_type)?></td>
						<td><?echo $time?></td>
						<td><?echo $size?>MB</td>
					</tr>

					<?$counter++;?>
				<?}?>
			</table>
		<?}?>

		<div class="loading_bar" id='pressbackup_loading_img'>
			Please do not close your window, this may take a few minutes<br/>
			<div>Task: <span id="pressbackup_loading_img_status">Working...</span></div>
			<?echo $this->html->img('indicator.gif');?>
		</div>

		<div class="loading_bar" id='pressbackup_loading_bar'>
			<div>Please do not close your window, this may take a few minutes</div>
			<div>Task: <span id="pressbackup_loading_bar_status">...</span></div>
			<div id="progressbar"></div>
		</div>

		<div id="contact_info">
			<div style="font-weight: bold; font-size:10px; margin: 25px 0 5px 0;">Signup for PressBackup News to get updates</div>
			<form action="http://infinimedia.createsend.com/t/y/s/otyxj/" method="post" id="subForm">
				<div>
					<input type="text" name="cm-otyxj-otyxj" id="otyxj-otyxj" value="Your email address here" onfocus="if(jQuery(this).val() == 'Your email address here'){ jQuery(this).val('') }" onblur="if(jQuery(this).val() == ''){ jQuery(this).val('Your email address here') }" style="font-size:14px; font-style:italic; color:#565656" />&nbsp;&nbsp;
					<input class="button" type="submit" value="Subscribe" />
				</div>
			</form><br/>
			<small>Found a bug? go to <?echo $this->html->link('http://pressbackup.com/contact', 'http://pressbackup.com/contact');?> and send us <?echo $this->html->link('this info', array('controller'=>'principal', 'function'=>'host_info'));?></small>
		</div>
	</div>

	<?php echo $this->html->js("dashboard.js");?>
	<?php if($reload) {?>
		<script type='text/javascript'>
			jQuery('#progressbar').progressbar({'value': '0' });
		</script>
		<style>
			.ui-progressbar  {width:450px; height:18px;}
		</style>
	<?}?>