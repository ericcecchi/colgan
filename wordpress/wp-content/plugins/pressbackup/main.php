<?php
/*
Plugin Name: PressBackup
Plugin URI: http://pressbackup.com
Description: Pressbackup is the easiest plugin available for backing up your wordpress site automatically.Using Amazon cloud technology, pressbackup allows your wordpress blog administrator to schedule backups of your entire site, restore backups, and migrate your site in the event of your server failure or moving. Pressbackup is free to use with your own AWS S3 credentials or you can purchase a pro subscription and we handle the backups for you. Without S3, pressbackup will allow you to manually download and upload backup files from your site.
Author: Infinimedia Inc.
Version: 1.2
Author URI: http://infinimedia.com/
*/

	//init framework
	require_once('.core/w2pf_init.php');
	global $FramePress;
	global $pressbackup;
	$pressbackup = new $FramePress(__FILE__);

	$wp_pages =array (

		'tools' =>array(
			array(
				'page_title' => 'PressBackup',
				'menu_title' => 'PressBackup',
				'capability' => 'administrator',
				'menu_slug' => 'principal',
			),
		),
		'settings' =>array(
			array(
				'page_title' => 'PressBackup',
				'menu_title' => 'PressBackup Settings',
				'capability' => 'administrator',
				'menu_slug' => 'settings',
			),
		),
	);

	$wp_actions = array (
		array(
			'tag' => 'init',
			'handler' => 'extra',
			'function' => 'pressbackup_check_schedule',
		),
		array(
			'tag' => 'admin_init',
			'handler' => 'extra',
			'function' => 'pressbackup_restore_htaccess',
		),

		//schedule job
		array(
			'tag' => 'pressback_backup_start_cronjob',
			'handler' => 'principal',
			'function' => 'backup_create_and_send',
		),

		// save and donload
		array(
			'tag' => 'pressback_backupnow_save',
			'handler' => 'principal',
			'function' => 'backup_create_and_send',
		),
		array(
			'tag' => 'pressback_backupnow_download',
			'handler' => 'principal',
			'function' => 'backup_create_then_download',
		),

		// save and donload ajax
		array(
			'tag' => 'pressback_backupnow_download_ajax',
			'handler' => 'principal',
			'function' => 'backup_create_then_download',
			'is_ajax' => true
		),
		array(
			'tag' => 'pressback_backupnow_save_ajax',
			'handler' => 'principal',
			'function' => 'backup_create_and_send',
			'is_ajax' => true
		),

		// check status ajax
		array(
			'tag' => 'pressbackup_check_backupnow_status',
			'handler' => 'principal',
			'function' => 'check_backupnow_status',
			'is_ajax' => true,
		),
	);


	$pressbackup->Page->add($wp_pages);
	$pressbackup->Action->add($wp_actions);

?>
