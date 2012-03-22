<?php
 /**
 * Principal Controller for Pressbackup.
 *
 * This Class provide a interface to display and manage backups
 *
 * Licensed under The GPL v2 License
 * Redistributions of files must retain the above copyright notice.
 *
 * @link				http://pressbackup.com
 * @package		controlers
 * @subpackage	controlers.principal
 * @since			0.1
 * @license			GPL v2 License
 */

class PressbackPrincipal {

	/**
	 * Redirect the user to de correct page
	 * If the user has not configured the plugin
	 * this function redirect directly to the config init page
	 *
	 * Note: this function is automaticaly called
	 * by pressing on tool menu link
	*/
	function index(){
		global $pressbackup;

		$preferences= get_option('pressbackup_preferences');

		if(!$preferences['pressbackup']['configured'])
		{
			$redirect=array('menu_type'=>'settings', 'controller'=>'settings', 'function'=>'config_init');
		}
		else
		{
			$redirect=array('controller'=>'principal', 'function'=>'dashboard');
		}

		$pressbackup->redirect($redirect);
	}

// Pages
//------------------------------

	/**
	 * Shows dashboard page (the main page)
	 * This page has the interface to manage backups
	 *
	 * @from string: what tab of backups see
	 * @reload bool: load ajax checker
	 * @page integer: page of backup to see
	 */
	function dashboard ($from = null, $reload=false, $page = null)
	{
		global $pressbackup;
		$pressbackup->View->layout('principal');
		$preferences= get_option('pressbackup_preferences');
		$pressbackup->import('misc.php');
		$misc = new pressbackup_misc();

		//init
		$bucket_files= array();
		$ns= null;

		//check what dashboard we are seeing last time
		if(!$pressbackup->Session->check('dashboard_from')){
			if(!$from){$from='this_site';}
			$pressbackup->Session->write('dashboard_from', $from);
		}
		elseif($from && $pressbackup->Session->read('dashboard_from') != $from)
		{
			$pressbackup->Session->write('dashboard_from', $from);
			$page=1;
		}
		$from = $pressbackup->Session->read('dashboard_from');


		//set the type of credential to see what option show in this page
		$credentials = false; $credential_type = null;
		if($preferences['pressbackup']['s3']['credential']) {
			$credentials= true; $credential_type='Amazon S3';
		}
		elseif($preferences['pressbackup']['pressbackuppro']['credential']) {
			$credentials= true; $credential_type='Pressbackup Pro';
		}

		//get saved files
		if ($credentials) {

			//retrive backups from Amazon S3
			if($preferences['pressbackup']['s3']['credential'])
			{
				$pressbackup->import('S3.php');
				$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['s3']['credential']));
				$s3 = new PressbackupS3($credentials[0], $credentials[1]);
				$bucket_files = $s3->getBucket($preferences['pressbackup']['s3']['bucket_name']);
				$bucket_files= @$misc->msort('S3', $bucket_files);
				$bucket_files= $misc->filter_files ($from, $bucket_files);
			}

			//retrive backups from Pro
			elseif($preferences['pressbackup']['pressbackuppro']['credential'])
			{
				$pressbackup->import('Pro.php');
				$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['pressbackuppro']['credential']));
				$pbp = new pressbackup($credentials[0], $credentials[1]);
				$bucket_files = $pbp->getFilesList();
				$bucket_files= @$misc->msort('pressbackup', $bucket_files);
				$bucket_files= $misc->filter_files ($from, $bucket_files);
			}

			//inicializacion del paginador
			if ($bucket_files) {
				$pressbackup->import('paginator.php');
				$pag = new PressbackPaginator();
				$pagination_size = 5;

				$page_saved = $pressbackup->Session->read('dash.page');
				if(!$page && !$page_saved){
					$page = 1;
				}
				elseif(!$page && $page_saved){
					$page = $page_saved;
				}
				if(!array_slice($bucket_files, (($page -1 )*$pagination_size), $pagination_size, true) && $page > 1){
					$page--;
				}
				$pressbackup->Session->write('dash.page', $page);

				$paginator['page'] = $page ;
				$paginator['total']  = count($bucket_files);
				$paginator['pages'] = ceil ($paginator['total'] /$pagination_size);
				$paginator['ini']  = (($page -1 )*$pagination_size);
				$paginator['fin']  = (($page*$pagination_size) -1);
				$paginator['func_path']  = array('controller'=>'principal', 'function'=>'dashboard', $from, null);
				$paginator['pagination'] = $pagination_size;
				$pressbackup->View->set('paginator', $pag->get_html($paginator));
				$pressbackup->View->set('bucket_files', array_slice($bucket_files, $paginator['ini'], $pagination_size, true));
			}
			else{
				$pressbackup->View->set('bucket_files', array());
			}


			//check for activated schedule
			if (!$ns=wp_next_scheduled('pressback_backup_start_cronjob')) {
				$pressbackup->import('backup_functions.php');
				$pb = new pressbackup_backup();
				$pb->add_schedule('+1 minutes');
				$ns=wp_next_scheduled('pressback_backup_start_cronjob');
			}
		}

		//set messages
		if($pressbackup->Session->check('general_msg') || $pressbackup->Session->check('general_msg', true)){
			$pressbackup->Msg->general($pressbackup->Session->read('general_msg'));
			$pressbackup->Msg->general($pressbackup->Session->read('general_msg', true));
			$pressbackup->Session->delete('general_msg');
			$pressbackup->Session->delete('general_msg', true);
		}

		//set errors
		if($pressbackup->Session->check('error_msg') || $pressbackup->Session->check('error_msg', true)){
			$pressbackup->Msg->error($pressbackup->Session->read('error_msg'));
			$pressbackup->Msg->error($pressbackup->Session->read('error_msg', true));
			$pressbackup->Session->delete('error_msg');
			$pressbackup->Session->delete('error_msg', true);
		}

		//set errors de permisos
		if($pressbackup->Session->check('error_msg_perm')){
			$pressbackup->Msg->error($pressbackup->Session->read('error_msg_perm'));
			$pressbackup->Session->delete('error_msg_perm');
		}

		$pressbackup->View->set('from', $from);
		$pressbackup->View->set('settings', $preferences);
		$pressbackup->View->set('credentials', $credentials);
		$pressbackup->View->set('credential_type', $credential_type);
		$pressbackup->View->set('ns', $ns);
		$pressbackup->View->set('reload', $reload);
	}

	/**
	 * Shows upload form for restore a PC soter backup
	 */
	function backup_upload_page ()
	{
		//tools & info
		global $pressbackup;
		$pressbackup->import('misc.php');
		$pressbackup->View->layout('principal');
		$preferences= get_option('pressbackup_preferences');
		$misc = new pressbackup_misc();

		//set error from previus upload
		if($pressbackup->Session->check('error_msg')){
			$pressbackup->Msg->error($pressbackup->Session->read('error_msg'));
			$pressbackup->Session->delete('error_msg');
		}

		//check for folder permissions
		$permissions = array();
		$disable = false;
		$msg = false;

		$dir = WP_CONTENT_DIR . DS;
		if( substr(sprintf('%o', @fileperms( $dir . 'themes')), -3) < '777'){ $permissions[] ='themes'; }
		if( file_exists($dir . 'uploads') && substr(sprintf('%o', @fileperms( $dir . 'uploads')), -3) < '777'){ $permissions[] ='uploads'; }
		if( substr(sprintf('%o', @fileperms( $dir . 'plugins')), -3) < '777'){ $permissions[] ='plugins'; }

		if($permissions) {
			$msg = "Permissions denied to write on <b>".join('</b>, <b>', $permissions)."</b> folder/s";
			$pressbackup->Msg->error($pressbackup->Msg->error($msg));
			$disable = true;
		}

		$pressbackup->View->set('upload_size', $misc->get_size_in('m', $misc->upload_max_filesize()));
		$pressbackup->View->set('settings', $preferences);
		$pressbackup->View->set('disable_ulpoad', $disable);
	}

	/**
	 * Shows host information (for report an error)
	 */
	function host_info(){
		global $pressbackup;

		$wp['version'] = get_bloginfo ('version');
		$wp['wpurl'] = get_bloginfo ('wpurl');
		$pressbackup->View->set('modules',  get_loaded_extensions());
		$pressbackup->View->set('server',  $_SERVER);
		$pressbackup->View->set('server_sapi',  php_sapi_name());
		$pressbackup->View->set('wp',  $wp);
		$pressbackup->View->set('p',  get_plugin_data($pressbackup->main_file));
		$pressbackup->View->set('psystmp', $pressbackup->Path->Dir['SYSTMP']);
		$pressbackup->View->set('pwpftmp', $pressbackup->Path->Dir['SYSTMP']);
	}


// Backup functions: create
//------------------------------

	/**
	 * Start a manual backup
	 * create a schedule action (background proccess)
	 *
	 * @reload_type string: specify what type of manual backup we are doing
	 */
	function backup_start ($reload_type="backup_download") {
		//tools
		global $pressbackup;
		$preferences = get_option('pressbackup_preferences');

		$pressbackup->import('backup_functions.php');
		$pb = new pressbackup_backup();

		$pressbackup->import('curl.php');
		$curl = new PressbackupCurl();

		$pressbackup->import('misc.php');
		$misc = new pressbackup_misc();

		if ($reload_type == "backup_download") {
			switch($preferences['pressbackup']['compatibility']['background']){
				case 10:
					//soft
					$pb->add_schedule('+4 seconds', 'pressback_backupnow_download');
				break;
				case 20:
					//midium
					$args = array(
						'url' => get_bloginfo('wpurl').'/wp-admin/admin-ajax.php?action=pressback_backupnow_download_ajax',
						'cookie'=>$_COOKIE,
						'timeout'=>4,
					);
					$curl->call($args);
				break;
				case 30:
					$cookie = array(); foreach($_COOKIE as $key => $value) { $cookie[]=$key.'='.$value;}

					$info = array();
					$info[0] = get_bloginfo('wpurl').'/wp-admin/admin-ajax.php?action=pressback_backupnow_download_ajax';
					$info[1]= join (';', $cookie);

					$args = array(
						'file' => $pressbackup->Path->Dir['LIB'].DS.'background.php',
						'split'=>'ALLYOUNEEDISLOVE',
						'args'=>base64_encode(join('|ALLYOUNEEDISLOVE|', $info)),
					);
					$misc->php($args);
				break;
			}

		}elseif ($reload_type == "dashboard") {

			switch($preferences['pressbackup']['compatibility']['background']){
				case 10:
					$pb->add_schedule('+4 seconds', 'pressback_backupnow_save');
				break;
				case 20:
					$args = array(
						'url' => get_bloginfo('wpurl').'/wp-admin/admin-ajax.php?action=pressback_backupnow_save_ajax',
						'cookie'=>$_COOKIE,
						'timeout'=>4,
					);
					$curl->call($args);
				break;
				case 30:
					$cookie = array(); foreach($_COOKIE as $key => $value) { $cookie[]=$key.'='.$value;}

					$info = array();
					$info[0] = get_bloginfo('wpurl').'/wp-admin/admin-ajax.php?action=pressback_backupnow_save_ajax';
					$info[1]= join (';', $cookie);

					$args = array(
						'file' => $pressbackup->Path->Dir['LIB'].DS.'background.php',
						'split'=>'ALLYOUNEEDISLOVE',
						'args'=>base64_encode(join('|ALLYOUNEEDISLOVE|', $info)),
					);
					$misc->php($args);
				break;
			}

		}

		$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard', null, $reload_type));
		exit();
	}

	/**
	 * Create and send a backup to S3 or PPro
	 * this is the background proccess called from
	 * backup start or via scheduled task (cron job)
	 */
	function backup_create_and_send() {
		@ignore_user_abort(true);
		@set_time_limit(0);
		@ob_end_clean();
		@ob_start();
		header("Status: 204", true);
		header("Content-type: text/html", true);
		header("Content-Length: 0", true);
		header("Connection: close", true);
		@ob_end_flush();
		@ob_flush();
		@flush();
		@fclose(STDIN);
		@fclose(STDOUT);
		@fclose(STDERR);

		//tools
		global $pressbackup;
		$preferences= get_option('pressbackup_preferences');

		$pressbackup->import('backup_functions.php');
		$pb = new pressbackup_backup();


		//check for activated schedule
		//scheduled job will not exist if this function is called by cron
		if (!$ns=wp_next_scheduled('pressback_backup_start_cronjob')) {
			$pb->add_schedule();
		}

		//get the type of credential
		$credential = 'Pro';
		if($preferences['pressbackup']['s3']['credential']) { $credential='S3'; }

		@wp_clear_scheduled_hook('pressback_backupnow_save');

		set_error_handler(array($this, 'creation_eh'));
		if(!$file = $pb->create()){ exit(); }
		$pb->save_on($credential, $file);
		restore_error_handler();

		@wp_clear_scheduled_hook('pressback_backupnow_save');

		exit;
	}

	/**
	 * Create a backup to downloading it latter
	 * this is the background proccess called from
	 * backup start. The ajax checker tell de browser when
	 * this procces finish and redirect the user to donwload backup
	 */
	function backup_create_then_download () {
		@set_time_limit(0);

		//tools
		global $pressbackup;
		$pressbackup->import('backup_functions.php');

		@wp_clear_scheduled_hook('pressback_backupnow_download');

		set_error_handler(array($this, 'creation_eh'));
		$pb = new pressbackup_backup();
		if(!$zip_file_created = $pb->create()){ exit; }
		restore_error_handler();

		@wp_clear_scheduled_hook('pressback_backupnow_download');

		@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.return', $zip_file_created);
		exit;
	}

// Backup functions: Download
//------------------------------

	/**
	 * Download a backup
	 * the backup was presviously stored by backup_create_then_download
	 */
	function backup_download ($file =null){
		global $pressbackup;
		@ob_end_clean();
		$pressbackup->View->layout('blank');
		$pressbackup->import('download.php');
		$pressbackup->View->set('file', base64_decode($file));
	}

// Backup functions: Upload
//------------------------------

	/**
	 * Call to restore if uploaded backups its valid
	 * or save the errors and display upload form again
	 */
	function backup_upload ($then= 'restore')
	{
		//tools & info
		global $pressbackup;
		$preferences= get_option('pressbackup_preferences');
		$pressbackup->import('backup_functions.php');

		//return if exists upload errors
		if(!$this->check_backup_upload() || !$this->check_backup_integrity($_FILES['backup']['tmp_name']))
		{
			$pressbackup->redirect(array('controller'=>'principal', 'function'=>'backup_upload_page'));
		}

		//create backup file
		$pb = new pressbackup_backup();
		$pb->restore(array('tmp_name'=>$_FILES['backup']['tmp_name']));
		$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
	}

// Backup functions: Restore
//------------------------------

	/**
	 * Restore a backup previously stored by backup_upload
	 * or  S3/PPro get function ( from backup function lib)
	 */
	function backup_restore ($name= null)
	{
		//tools & info
		global $pressbackup;
		$preferences= get_option('pressbackup_preferences');
		$pressbackup->import('backup_functions.php');

		//check for folder permissions
		$permissions_problem = array();
		$dir = WP_CONTENT_DIR . DS;
		if( substr(sprintf('%o', @fileperms( $dir . 'themes')), -3) < '777'){ $permissions_problem[] ='themes'; }
		if( file_exists($dir . 'uploads') && substr(sprintf('%o', @fileperms( $dir . 'uploads')), -3) < '777'){ $permissions_problem[] ='uploads'; }
		if( substr(sprintf('%o', @fileperms( $dir . 'plugins')), -3) < '777'){ $permissions_problem[] ='plugins'; }

		if($permissions_problem) {
			$msg = "Warning: permissions denied to write on <b>".join('</b>, <b>', $permissions_problem)."</b> folder/s, change the permissions if you want to be able to restore a backup";
			$pressbackup->Session->write( 'error_msg_perm', $msg);
			$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
		}

		$pb = new pressbackup_backup();
		if(!$tmp_name=$pb->get($name))
		{
			$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
		}
		elseif(!$this->check_backup_integrity($tmp_name))
		{
			$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
		}

		$pb->restore(array('tmp_name'=>$tmp_name));
		$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
	}

// Backup functions: Get
//------------------------------

	/**
	 * Download a backup From S3
	 *
	 * Download a backup previously stored by
	 * S3 get function ( from backup function lib)
	 */
	function backup_get ($name=null)
	{
		//tools & info
		global $pressbackup;
		$preferences= get_option('pressbackup_preferences');
		$pressbackup->import('backup_functions.php');

		$pb = new pressbackup_backup();
		if(!$tmp_name=$pb->get($name))
		{
			$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
		}
		elseif(!$this->check_backup_integrity($tmp_name))
		{
			$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
		}

		ob_end_clean();
		$pressbackup->import('download.php');
		$pressbackup->View->set('file', $tmp_name);
	}

	/**
	 * Download a backup From PPro
	 *
	 * Prepare the backup for download on PPro server
	 *  then redirect the user to there to download the backup
	 */
	function backup_get2 ($name=null)
	{
		//tools & info
		global $pressbackup;
		$preferences= get_option('pressbackup_preferences');
		$pressbackup->import('backup_functions.php');

		$pb = new pressbackup_backup();
		if(!$tmp_name=$pb->get2($name))
		{
			$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
		}

		$pressbackup->redirect('http://pressbackup.com/pro/api/download/'.base64_encode($name));
	}

// Backup functions: Delete
//------------------------------

	/**
	 * Remove a backup From PPro/S3
	 *
	 * @name string: name of the backup to remove
	 */
	function backup_delete ($name=null)
	{
		//tools & info
		global $pressbackup;
		$preferences= get_option('pressbackup_preferences');
		$pressbackup->import('backup_functions.php');

		$pb = new pressbackup_backup();
		$pb->delete($name);
		$pressbackup->redirect(array('controller'=>'principal', 'function'=>'dashboard'));
	}


// Functions helpers
//------------------------------

	/**
	 * Check backup upload
	 *
	 * Check if the upload of a backup was made correctly
	 * ej: see if no errors, or if uploaded file is a zip file etc
	 */
	function check_backup_upload()
	{
		//tools
		global $pressbackup;
		$pressbackup->import('misc.php');

		if (!isset($_FILES['backup']) || $_FILES['backup']['error']==4)
		{
			$pressbackup->Session->write( 'error_msg', 'You sent an empty file or it is bigger than '.$misc->get_size_in('m', $misc->upload_max_filesize()).' Mb. Please try it again');
			return false;
		}
		if ($_FILES['backup']['error']!=0)
		{
			$pressbackup->Session->write( 'error_msg', 'There was a problem, maybe the file is bigger than '.$misc->get_size_in('m', $misc->upload_max_filesize()).' Mb. Please try it again');
			return false;
		}
		if ( !in_array($_FILES['backup']['type'], array('application/zip', 'application/x-zip-compressed')))
		{
			$pressbackup->Session->write( 'error_msg', 'Wrong file type: '.$_FILES['backup']['type']);
			return false;
		}
		if (!is_uploaded_file($_FILES['backup']['tmp_name']))
		{
			$pressbackup->Session->write( 'error_msg', 'The file could not be uploaded correctly');
			return false;
		}
		return true;
	}

	/**
	 * Check a backup integrity
	 *
	 * Check if the uploaded or geted backup
	 * can be opened without errors
	 */
	function check_backup_integrity($zip_file)
	{
		//tools
		global $pressbackup;

		if(!file_exists($zip_file)){
			$pressbackup->Session->write( 'error_msg', 'file not found');
			return false;
		}

		$zip = new ZipArchive();
		if ($zip->open($zip_file) !== TRUE)
		{
			$pressbackup->Session->write( 'error_msg', 'Sorry, but the file is corrupt');
			return false;
		}
		$zip->close();
		return true;
	}

	/**
	 * Check the status of the backup now process
	 *
	 * This function its called via Ajax and inform what
	 * is the process doing. And most important when
	 * the process finish.
	 */
	function check_backupnow_status()
	{
		//tools
		global $pressbackup;

		//for what task we are checking, download or save?
		$task = $_POST['task'];

		//posibilly background procees its not running
		$response = '{"action": "wait", "status": "fail"}';

		//its creating the backup
		if( ($logfilec = $this->check_backupnow_log('create.log')) && !$this->check_backupnow_log('sent.log') ) {

			$get = file_get_contents($logfilec);
			switch($get){
				case 'fail':
					@unlink($logfilec);
					$response = '{"action": "finish", "status": "fail"}';
				break;
				case 'finish':
					$rget='';if($returnfile = $this->check_backupnow_log('create.return') ) { $rget= file_get_contents( $returnfile ); }
					@unlink($logfilec); @unlink($returnfile);
					if($task == 'download') {
						$response = '{"action": "finish", "status": "ok", "response": { "file":"'.base64_encode($rget).'"}}';
					} else {
						$response = '{"action": "wait", "status": "ok", "task_now": "Start Sending Backup"}';
					}
				break;
				default: //creating
					$response = '{"action": "wait", "status": "ok", "task_now": "'.$get.'"}';
				break;
			}
		}

		//its sending the backup
		elseif( $logfiles = $this->check_backupnow_log('sent.log') ) {

			@unlink($logfilec);
			$get = file_get_contents($logfiles);
			switch($get){
				case 'fail':
					@unlink($logfiles); $pressbackup->Session->delete( 'sent.percent', true);
					$response = '{"action": "finish", "status": "fail"}';
				break;
				case 'finish':
					$rget=''; if( $returnfile = $this->check_backupnow_log('sent.return') ) { $rget= file_get_contents( $returnfile ); }
					@unlink($logfiles); @unlink($returnfile); $pressbackup->Session->delete( 'sent.percent', true);
					$response = '{"action": "finish", "status": "ok", "response": { "file":"'.base64_encode($rget).'"}}';
				break;
				default: //sending
					$current_taskpercent = $pressbackup->Session->read( 'sent.percent', true);
					$percent= explode('|', $current_taskpercent);
					if(isset($percent[1])) {
						$task_now = "Sending Backup"; if(($percent[1] -$percent[0]) == 1){$task_now = "Checking Integrity";}
						$response = '{"action": "wait", "status": "percent", "task_now": "'.$task_now.'", "response":{ "total": "'.$percent[1].'", "current":"'.$percent[0].'"} }';
					}
				break;
			}
		}

		exit( $response);
	}

	/**
	 * Check if a log file exists
	 *
	 * This is a helper function for check_backupnow_status
	 */
	function check_backupnow_log($log_file){
		//tools
		global $pressbackup;

		if ( file_exists( $log_file_path = $pressbackup->Path->Dir['LOGTMP']. DS. $log_file) ){
			return $log_file_path;
		}
		return false;
	}

	/**
	 * Background process error handler
	 *
	 * This function register the errors ocurred on the background process
	 */
	function creation_eh($level, $message, $file, $line, $context) {
		global $pressbackup;
		@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.fail', $message, FILE_APPEND);
		//@unlink($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log');
		return false;
	}

// Functions: tests
//------------------------------

	function test () {
		global $pressbackup;

		//misc
		$pressbackup->import('misc.php');
		$misc = new pressbackup_misc();

		//background errors
		$errors = '';
		if ( $logfilef = $this->check_backupnow_log('create.fail') ) {
			$errors = nl2br(file_get_contents($logfilef));
		}

		//folder contents
		$tmp_dir_path = $pressbackup->Path->Dir['PBKTMP'] . DS;
		$tmp_dir = array('No se creo carpeta');
		if(is_dir($tmp_dir_path)) {
			$tmp_dir = scandir($tmp_dir_path);
			for($i=0; $i < count($tmp_dir); $i++){
				if(in_array($tmp_dir[$i], array('.', '..'))){continue;}
				echo base64_encode($tmp_dir_path.DS.$tmp_dir[$i]).'>>';
				$tmp_dir[$i].= ' <br/>&nbsp;&nbsp;&nbsp;&nbsp;- size: '.$misc->get_size_in('m', filesize ($tmp_dir_path.DS.$tmp_dir[$i]) ).' MB';
			}
		}
		$log_dir_path = $pressbackup->Path->Dir['LOGTMP'] . DS;
		$log_dir = array('No se creo carpeta');
		if(is_dir($log_dir_path)) { $log_dir = scandir($log_dir_path); }

		$pressbackup->View->set('tmp_dir', $tmp_dir);
		$pressbackup->View->set('log_dir', $log_dir);
		$pressbackup->View->set('error_log', $errors);
	}

	function clean () {
		global $pressbackup;

		//misc
		$pressbackup->import('misc.php');
		$misc = new pressbackup_misc();

		//clean log && tmp dir
		$misc->perpare_folder($pressbackup->Path->Dir['LOGTMP']);
		$misc->perpare_folder($pressbackup->Path->Dir['PBKTMP']);

		$pressbackup->redirect(array('controller'=>'principal', 'function'=>'test'));
	}

}
?>
