<?php

/*
	WordPress Framework, activation  v0.1
	developer: Perecedero (Ivan Lansky) perecedero@gmail.com
*/


	function on_activation ()
	{
		//options
		$preferences['pressbackup']['enabled']=0;
		$preferences['pressbackup']['configured']=0;
		$preferences['pressbackup']['backup']['time']=24;
		$preferences['pressbackup']['backup']['copies']=5;
		$preferences['pressbackup']['backup']['type']='0';
		$preferences['pressbackup']['s3']['credential']='';
		$preferences['pressbackup']['s3']['region']=false;
		$preferences['pressbackup']['pressbackuppro']['credential']='';
		$preferences['pressbackup']['compatibility']['background']=10;
		$preferences['pressbackup']['compatibility']['zip'] = 20;

		update_option('pressbackup_installed','1');
		update_option('pressbackup_preferences',$preferences);
	}

	function on_deactivation ()
	{
		global $pressbackup;
		$pressbackup->import('backup_functions.php');

		$pb = new pressbackup_backup();
		$pb->remove_schedule();

		delete_option('pressbackup_installed');
		delete_option('pressbackup_preferences');
	}


?>
