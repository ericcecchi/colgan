<?php
 /**
 * Settings Controller for Pressbackup.
 *
 * This Class provide a interface to display and manage Plugin settings
 * Also Manage the configurattion wizard
 *
 * Licensed under The GPL v2 License
 * Redistributions of files must retain the above copyright notice.
 *
 * @link				http://pressbackup.com
 * @package		controlers
 * @subpackage	controlers.settings
 * @since			0.1
 * @license			GPL v2 License
 */


class PressbackSettings {

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
		if( $preferences['pressbackup']['configured'] ){
			$pressbackup->redirect(array('controller'=>'settings', 'function'=>'config_credentials'));
		}
		$pressbackup->redirect(array('controller'=>'settings', 'function'=>'config_init'));
	}


// Config Pages ( wizard )
//------------------------------

	/**
	 * Start The configuration wizard
	 * Check all that all nedded libs are intalled
	 * Also check for perms on tmp folders
	*/
	function config_init() {

		global $pressbackup;
		$pressbackup->View->layout('principal');
		$preferences= get_option('pressbackup_preferences');

		//misc
		$pressbackup->import('misc.php');
		$misc = new pressbackup_misc();

		$error=array();
		$disable_backup_files= false;

		//check for
		if(strpos($_SERVER['SERVER_SOFTWARE'], 'iis') !== false){
			$error['iss'] = 'Sorry your web Server (Windows IIS) is not complatible with Pressbackup. You are welcome to use Pressbackup, but we cannot provide any support or guarantees for your system';
		}

		//check for
		if(ini_get('safe_mode'))
		{
			$error['safe_mode'] = 'Safe mode is enabled. You would need to contact your hosting provider and ask them if it\'s  necessary for you to have safe_mode enabled. Most hosts should be able  to disable it for you, or give you a more specific answer';
			$disable_backup_files=true;
		}

		//check for Zip Creation
		$bin = $misc->checkShell('zip');
		if(!$bin) {
			$preferences['pressbackup']['compatibility']['zip'] = 10;
			update_option('pressbackup_preferences',$preferences);
		}
		if(!$bin && !class_exists('ZipArchive'))
		{
			$error['zip'] = 'You probably don\'t have the php-zip extension installed. You would need to contact your hosting provider and ask them to install it.';
			$disable_backup_files=true;
		}

		// Check for CURL
		if (!extension_loaded('curl'))
		{
			$error['curl'] = 'You probably don\'t have the php-curl extension installed.  You would need to contact your hosting provider and ask them to install it.';
			$disable_backup_files=true;
		}

		//check for
		if(!class_exists('SimpleXMLElement'))
		{
			$error['xml'] = 'You probably don\'t have the SimpleXML extension installed.  You would need to contact your hosting provider and ask them to install it.';
			$disable_backup_files=true;
		}

		//tmp dir
		if (!file_exists($pressbackup->Path->Dir['PBKTMP']) && !mkdir($pressbackup->Path->Dir['PBKTMP']))
		{
			$error['tmpdir'] = 'Could not create the FileStore directory "'.$pressbackup->Path->Dir['PBKTMP'].'". Please check the effective permissions.';
			$disable_backup_files=true;
		}
		else
		{
			@chmod($pressbackup->Path->Dir['PBKTMP'], 0777);
		}

		//log dir
		if (!file_exists($pressbackup->Path->Dir['LOGTMP']) && !mkdir($pressbackup->Path->Dir['LOGTMP']))
		{
			$error['logdir'] = 'Could not create the FileStore directory "'.$pressbackup->Path->Dir['LOGTMP'].'". Please check the effective permissions.';
			$disable_backup_files=true;
		}
		else
		{
			@chmod($pressbackup->Path->Dir['LOGTMP'], 0777);
		}

		$pressbackup->View->set('error', $error);
		$pressbackup->View->set('disable_backup_files', $disable_backup_files);
	}

	/**
	 * Show the form to enter credential
	 * for S3 or PressbackupPro
	*/
	function  config_step1() {

		global $pressbackup;
		$pressbackup->View->layout('principal');

		$credentials_type = 'no';
		if($pressbackup->Session->check('error'))
		{
			//save selected credentials && set error msg
			$credentials_type = $pressbackup->Session->read('credentials');
			$pressbackup->Msg->error($pressbackup->Session->read('error_msg'));

			$pressbackup->Session->delete('error');
			$pressbackup->Session->delete('credentials');
			$pressbackup->Session->delete('error_msg');
		}

		$pressbackup->View->set('credentials_type', $credentials_type);
		$pressbackup->View->set('settings', get_option('pressbackup_preferences'));
	}

	/**
	 * Show the form to enter prefered settings
	 * as backup type, schedule time, etc
	*/
	function config_step2() {

		global $pressbackup;
		$pressbackup->View->layout('principal');
		$preferences= get_option('pressbackup_preferences');

		if($pressbackup->Session->check('error'))
		{
			//set error msg
			$pressbackup->Msg->error($pressbackup->Session->read('error_msg'));

			$pressbackup->Session->delete('error');
			$pressbackup->Session->delete('error_msg');
		}

		//set the type of credential to see what option show in this step
		$credentials = false;
		$credential_type = null;
		if($preferences['pressbackup']['s3']['credential']) {
			$credentials= true; $credential_type='Amazon S3';
		}
		elseif($preferences['pressbackup']['pressbackuppro']['credential']) {
			$credentials= true; $credential_type='Pressbackup Pro';
		}

		$pressbackup->View->set('settings', get_option('pressbackup_preferences'));
		$pressbackup->View->set('credentials', $credentials);
		$pressbackup->View->set('credential_type', $credential_type);
	}

// Config Pages ( tab settings )
//------------------------------

	/**
	 * Show the form to modify credentials
	 * for S3 or PressbackupPro
	*/
	function config_credentials(){

		global $pressbackup;
		$pressbackup->View->layout('principal');
		$preferences= get_option('pressbackup_preferences');

		$credentials_type = 'no';
		$credentials = array('','');
		if($preferences['pressbackup']['s3']['credential']) {
			$credentials_type='s3';
			$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['s3']['credential']));
		}
		elseif($preferences['pressbackup']['pressbackuppro']['credential']) {
			$credentials_type='pro';
			$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['pressbackuppro']['credential']));
		}

		if($pressbackup->Session->check('error'))
		{
			//save selected credentials && set error msg
			$credentials_type = $pressbackup->Session->read('credentials');
			$pressbackup->Msg->error($pressbackup->Session->read('error_msg'));

			$pressbackup->Session->delete('error');
			$pressbackup->Session->delete('credentials');
			$pressbackup->Session->delete('error_msg');
		}

		$pressbackup->View->set('credentials_type', $credentials_type);
		$pressbackup->View->set('credentials', $credentials);
		$pressbackup->View->set('settings', get_option('pressbackup_preferences'));
	}

	/**
	 * Show the form to modify prefered settings
	 * as backup type, schedule time, etc
	*/
	function config_settings(){

		global $pressbackup;
		$pressbackup->View->layout('principal');
		$preferences= get_option('pressbackup_preferences');

		if($pressbackup->Session->check('error'))
		{
			//set error msg
			$pressbackup->Msg->error($pressbackup->Session->read('error_msg'));

			$pressbackup->Session->delete('error');
			$pressbackup->Session->delete('error_msg');
		}

		//set the type of credential to see what option show in this step
		$credentials = false;
		$credential_type = null;
		if($preferences['pressbackup']['s3']['credential']) {
			$credentials= true; $credential_type='Amazon S3';
		}
		elseif($preferences['pressbackup']['pressbackuppro']['credential']) {
			$credentials= true; $credential_type='Pressbackup Pro';
		}

		//check for activated schedule
		/*
		$cgi=false;
		if (strpos(php_sapi_name(), 'cgi') !== false) {
			$cgi=true;
		}

		$pressbackup->View->set('cgi', $cgi);
		*/
		$pressbackup->View->set('settings', get_option('pressbackup_preferences'));
		$pressbackup->View->set('credentials', $credentials);
		$pressbackup->View->set('credential_type', $credential_type);
	}

	/**
	 * Show the form to modify prefered conpatibility settings
	*/
	function config_compatibility(){

		global $pressbackup;
		$pressbackup->View->layout('principal');
		$preferences= get_option('pressbackup_preferences');

		$pressbackup->import('misc.php');
		$misc = new pressbackup_misc();

		$binaries = false;
		if ($misc->checkShell('php') ){
			$binaries = true;
		}

		$pressbackup->View->set('binaries', $binaries);
		$pressbackup->View->set('settings', $preferences);
	}

// Config save functions
//------------------------------

	/**
	 * Store the credentials if they are correct
	 * or save the error and return to form page
	*/
	function config_step1_save() {

		global $pressbackup;

		if(!isset($_POST['data']) || ($credentials= $this->check_fields_step1($_POST['data'])) === false)
		{
			$pressbackup->Session->write('error', true);
			$pressbackup->Session->write('credentials', $_POST['data']['credentials']);
			$pressbackup->redirect(array('controller'=>'settings', 'function'=>'config_step1'));
		}

		$preferences= get_option('pressbackup_preferences');

		if(!$credentials)
		{
			$preferences['pressbackup']['s3']['credential']='';
			$preferences['pressbackup']['pressbackuppro']['credential']='';
		}
		elseif($_POST['data']['credentials']==='s3')
		{
			$preferences['pressbackup']['s3']['credential']=$credentials;
			$preferences['pressbackup']['pressbackuppro']['credential']='';
		}
		elseif($_POST['data']['credentials']==='pro')
		{
			$preferences['pressbackup']['s3']['credential']='';
			$preferences['pressbackup']['pressbackuppro']['credential']=$credentials;
		}

		update_option('pressbackup_preferences',$preferences);
		$pressbackup->redirect(array('controller'=>'settings', 'function'=>'config_step2'));
	}

	/**
	 * Store the settings if they are correct
	 * or save the error and return to form page
	*/
	function config_step2_save() {

		global $pressbackup;
		$pressbackup->import('backup_functions.php');

		if(!isset($_POST['data']) || !$this->check_fields_step2($_POST['data']['preferences']))
		{
			$pressbackup->Session->write('error', true);
			$pressbackup->redirect(array('controller'=>'settings', 'function'=>'config_step2'));
		}

		$preferences = get_option('pressbackup_preferences');

		if(isset($_POST['data']['preferences']['time']))
		{
			$preferences['pressbackup']['backup']['time']=$_POST['data']['preferences']['time'];
		}
		if(isset($_POST['data']['preferences']['copies']))
		{
			$preferences['pressbackup']['backup']['copies']=$_POST['data']['preferences']['copies'];
		}
		$preferences['pressbackup']['backup']['type']=join(',', $_POST['data']['preferences']['type']);

		$pb = new pressbackup_backup();
		$preferences['pressbackup']['configured']=1;
		if($preferences['pressbackup']['s3']['credential'] || $preferences['pressbackup']['pressbackuppro']['credential'])
		{
			$preferences['pressbackup']['enabled']=1;
			$pb->add_schedule();
		}
		else
		{
			$pb->remove_schedule();
		}

		update_option('pressbackup_preferences',$preferences);
		$pressbackup->redirect(array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'));
	}

	/**
	 * Store the modified credentials if they are correct
	 * or save the error and return to form page
	*/
	function config_credentials_save(){

		global $pressbackup;
		$pressbackup->import('backup_functions.php');

		if(!isset($_POST['data']) || ($credentials= $this->check_fields_step1($_POST['data'])) === false)
		{
			$pressbackup->Session->write('error', true);
			$pressbackup->Session->write('credentials', $_POST['data']['credentials']);
			$pressbackup->redirect(array('controller'=>'settings', 'function'=>'config_credentials'));
		}

		$preferences= get_option('pressbackup_preferences');

		if(!$credentials)
		{
			$preferences['pressbackup']['s3']['credential']='';
			$preferences['pressbackup']['pressbackuppro']['credential']='';
		}
		elseif($_POST['data']['credentials']==='s3')
		{
			$preferences['pressbackup']['s3']['credential']=$credentials;
			$preferences['pressbackup']['pressbackuppro']['credential']='';
		}
		elseif($_POST['data']['credentials']==='pro')
		{
			$preferences['pressbackup']['s3']['credential']='';
			$preferences['pressbackup']['pressbackuppro']['credential']=$credentials;
		}
		update_option('pressbackup_preferences',$preferences);

		$pb = new pressbackup_backup();
		if($preferences['pressbackup']['s3']['credential'] || $preferences['pressbackup']['pressbackuppro']['credential'])
		{
			$pb->add_schedule();
		}
		else
		{
			$pb->remove_schedule();
		}

		$pressbackup->Session->write('general_msg', 'Credentials Saved!');
		$pressbackup->redirect(array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'));
	}

	/**
	 * Store the modified settings if they are correct
	 * or save the error and return to form page
	*/
	function config_settings_save (){

		global $pressbackup;

		if(!isset($_POST['data']) || !$this->check_fields_step2($_POST['data']['preferences']))
		{
			$pressbackup->Session->write('error', true);
			$pressbackup->redirect(array('controller'=>'settings', 'function'=>'config_step2'));
		}

		$preferences = get_option('pressbackup_preferences');

		if(isset($_POST['data']['preferences']['time']))
		{
			$preferences['pressbackup']['backup']['time']=$_POST['data']['preferences']['time'];
		}
		if(isset($_POST['data']['preferences']['copies']))
		{
			$preferences['pressbackup']['backup']['copies']=$_POST['data']['preferences']['copies'];
		}
		$preferences['pressbackup']['backup']['type']=join(',', $_POST['data']['preferences']['type']);

		update_option('pressbackup_preferences',$preferences);

		$pressbackup->Session->write('general_msg', 'Preferences Saved!');
		$pressbackup->redirect(array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'));
	}

	/**
	 * Store the modified settings if they are correct
	 * or save the error and return to form page
	*/
	function config_compatibility_save (){

		global $pressbackup;

		$preferences = get_option('pressbackup_preferences');

		if(isset($_POST['data']['compatibility']['background']))
		{
			$preferences['pressbackup']['compatibility']['background']=$_POST['data']['compatibility']['background'];
		}
		if(isset($_POST['data']['compatibility']['zip']))
		{
			$preferences['pressbackup']['compatibility']['zip']=$_POST['data']['compatibility']['zip'];
		}

		update_option('pressbackup_preferences',$preferences);

		$pressbackup->Session->write('general_msg', 'Preferences Saved!');
		$pressbackup->redirect(array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'));
	}

// Config check functions
//------------------------------

	/**
	 * Check if credentials are valid
	 * for S3 or Pressbackup PRO account
	 *
	 * @args array: credentials sent via post
	 */
	function check_fields_step1($args) {

		global $pressbackup;

		if($args['credentials']==='s3')
		{
			$pressbackup->import('S3.php');

			if(!isset($args['preferences']['S3']['accessKey']) || empty($args['preferences']['S3']['accessKey']) || !isset($args['preferences']['S3']['secretKey']) || empty($args['preferences']['S3']['secretKey']))
			{
				$pressbackup->Session->write('error_msg', 'Empty Fields, please ty it again');
				return false;
			}

			$s3 = new PressbackupS3($args['preferences']['S3']['accessKey'], $args['preferences']['S3']['secretKey']);
			if(($buckets = @$s3->listBuckets())===false)
			{
				$pressbackup->Session->write('error_msg', 'Incorrect S3 access or secret Keys');
				return false;
			}

			//create bucket if it not exist
			$the_bucket = $pressbackup->Config->read('S3.bucketname').'-'.md5(uniqid(rand(), true));
			$create_bucket=true;
			for($i=0; $i<count($buckets);$i++) {
				if ( strpos($buckets[$i], $pressbackup->Config->read('S3.bucketname')) !== false ) {
					$the_bucket = $buckets[$i];
					$create_bucket = false; break;
				}
			}

			$region = ($args['preferences']['S3']['region']=='EU')?'EU':false;

			if ($create_bucket && !$s3->putBucket($the_bucket, PressbackupS3::ACL_PRIVATE, $region)) {
				$pressbackup->Session->write( 'error_msg',  "Unable to create a bucket on S3: Service temporarily unavailable. Please try it again later.");
				return false;
			}

			$preferences= get_option('pressbackup_preferences');
			$preferences['pressbackup']['s3']['bucket_name']=$the_bucket;
			$preferences['pressbackup']['s3']['region']=$region;
			update_option('pressbackup_preferences',$preferences);

			return base64_encode($args['preferences']['S3']['accessKey'].'|AllYouNeedIsLove|'.$args['preferences']['S3']['secretKey']);
		}
		elseif ($args['credentials']==='pro')
		{
			$pressbackup->import('Pro.php');

			if(!isset($args['preferences']['pressbackuppro']['key']) || empty($args['preferences']['pressbackuppro']['key']) || !isset($args['preferences']['pressbackuppro']['user']) || empty($args['preferences']['pressbackuppro']['user']))
			{
				$pressbackup->Session->write('error_msg', 'Empty Fields, please ty it again');
				return false;
			}

			$pro = new pressbackup ($args['preferences']['pressbackuppro']['user'], $args['preferences']['pressbackuppro']['key']);
			if($pro->auth() === false || $pro->response_code == 401)
			{
				$pressbackup->Session->write('error_msg', 'Incorrect PressBackup Pro User/AuthKey');
				return false;
			}
			elseif (!$pro->check()){
				$pressbackup->Session->write( 'error_msg',  "This blog is not registered on your Pressbackup Pro account! Register it and try again");
				return false;
			}


			return base64_encode($args['preferences']['pressbackuppro']['user'].'|AllYouNeedIsLove|'.$args['preferences']['pressbackuppro']['key']);
		}
		else
		{
			return '';
		}
	}

	/**
	 * Check if fields for Settings are valid.
	 * The files are type of backup, schedule time, etc
	 *
	 * @args array: all fields sent via post
	 */
	function check_fields_step2($args) {

		global $pressbackup;
		$pass= true;

		if(isset($args['time']) && !preg_match("/^[[:digit:]]+$/", $args['time']))
		{
			$pass= false;
		}

		if(isset($args['copies']) && !preg_match("/^[[:digit:]]+$/", $args['copies']))
		{
			$pass= false;
		}

		if(isset($args['type']))
		{
			for($i=0; $i < count($args['type']); $i++)
			{
				if(!preg_match("/^[[:digit:]]+$/", $args['type'][$i]))
				{
					$pass= false;
				}
			}
		}

		if(!$pass)
		{
			$pressbackup->Session->write('error_msg', 'Empty/wrong Fields, please ty it again');
		}
		return $pass;
	}
}
?>
