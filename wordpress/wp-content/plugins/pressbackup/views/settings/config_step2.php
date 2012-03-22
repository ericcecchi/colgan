<?php echo $this->html->css("styles.css");?>
	<div class=" pressbak_content pressbak_content_all">
<form method="post" action="<?php echo $this->path->router(array('controller'=>'settings', 'function'=>'config_step2_save'));?>">
	<h3>Configuration wizard (step 2 of 2)</h3>

	<?echo $this->msg->show('error');?>
	<?echo $this->msg->show('general');?>

	<div style="float: left; width: 400px;">
	<?if($credentials){?>
		<h4>How often do you want to backup?</h4>
		<ul class="pressbackup-ul">
			<li><input type="radio" name="data[preferences][time]" value ="12" <?if ($settings['pressbackup']['backup']['time']==12) {?>checked<?}?>> Every 12 hours</li>
			<li><input type="radio" name="data[preferences][time]" value ="24" <?if ($settings['pressbackup']['backup']['time']==24){?>checked<?}?>> Daily</li>
			<li><input type="radio" name="data[preferences][time]" value ="168" <?if ($settings['pressbackup']['backup']['time']==168){?>checked<?}?>> Weekly</li>
			<li><input type="radio" name="data[preferences][time]" value ="720" <?if ($settings['pressbackup']['backup']['time']==720){?>checked<?}?>> Monthly</li>
		</ul>

		<h4>How many copies do you want to store?</h4>
		<ul class="pressbackup-ul">
			<li><input type="radio" name="data[preferences][copies]" value ="7" <?if ($settings['pressbackup']['backup']['copies']==7) {?>checked<?}?>> All (may get expensive)</li>
			<li><input type="radio" name="data[preferences][copies]" value ="5" <?if ($settings['pressbackup']['backup']['copies']==5) {?>checked<?}?>> Last 5</li>
			<li><input type="radio" name="data[preferences][copies]" value ="3" <?if ($settings['pressbackup']['backup']['copies']==3) {?>checked<?}?>> Last 3</li>
		</ul>
	<?}?>
	</div>
	<div style="float: left; width: 400px;">
		<h4>What do you want to backup?</h4>
		<ul class="pressbackup-ul">
			<li><input type="checkbox" name="data[preferences][type][]" value ="7" <?if (in_array('7', explode(',',$settings['pressbackup']['backup']['type']))) {?>checked<?}?>> Database</li>
			<li><input type="checkbox" name="data[preferences][type][]" value ="5" <?if (in_array('5', explode(',',$settings['pressbackup']['backup']['type']))) {?>checked<?}?>> Themes</li>
			<li><input type="checkbox" name="data[preferences][type][]" value ="3" <?if (in_array('3', explode(',',$settings['pressbackup']['backup']['type']))) {?>checked<?}?>> Plugins</li>
			<li><input type="checkbox" name="data[preferences][type][]" value ="1" <?if (in_array('1', explode(',',$settings['pressbackup']['backup']['type']))) {?>checked<?}?>> Uploads</li>
		</ul>
	</div>
	<div style="clear:both"></div>
	<br/>

	<?echo $this->html->link('Back', array('controller'=>'settings', 'function'=>'config_step1'), array('class'=>'button'));?>
	<input class="button" type="submit" value="Finish">
</form>
</div>
