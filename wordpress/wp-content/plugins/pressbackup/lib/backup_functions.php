<?php

 /**
 * Backup Functions lib for Pressbackup.
 *
 * This Class provide the functionality to mannage backups
 * functions for creation, restoring, get and save from S3 and Ppro
 *
 * Licensed under The GPL v2 License
 * Redistributions of files must retain the above copyright notice.
 *
 * @link				http://pressbackup.com
 * @package		libs
 * @subpackage	libs.backups
 * @since			0.1
 * @license			GPL v2 License
 */

	class pressbackup_backup {

	//		Create backup functions
	//----------------------------------------------------------------------------------------

		function create ()
		{
			//set infinite time for this action
			set_time_limit (0);

			//tools and info
			global $pressbackup;
			$pressbackup->import('misc.php');
			$misc = new pressbackup_misc();

			$preferences=get_option('pressbackup_preferences');

			//clean log && tmp dir
			$misc->perpare_folder($pressbackup->Path->Dir['LOGTMP']);
			$misc->perpare_folder($pressbackup->Path->Dir['PBKTMP']);

			@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'start');

			//zip files and export db
			if(!$this->backup_files() || !$this->backup_db())
			{
				return false;
			}

			//file name of backup
			$backup_file_type = str_replace(',', '-', $preferences['pressbackup']['backup']['type']);
			$name=str_replace(' ', '-', get_bloginfo( 'name' ));
			$zip_file = $pressbackup->Path->Dir['PBKTMP'] . DS . uniqid($name.'-backup_'.$backup_file_type.'_').'.zip';
			$folder = str_replace($pressbackup->Path->Dir['SYSTMP'] . DS, '', $pressbackup->Path->Dir['PBKTMP']);
			$folder = $folder.DS;

			//zip files
			$type = 'shell';
			if( $preferences['pressbackup']['compatibility']['zip'] == 10 ) {
				$type = 'php';
			}
			$res = $misc->zip($type, array('context_dir'=>$pressbackup->Path->Dir['SYSTMP'], 'dir' => $folder, 'zip' => $zip_file, 'compression' => 0));

			//check response of zip creation
			if( !$res ) {
				$pressbackup->Session->write( 'error_msg',  "Zip file is corrupt - creation failed");
				@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'fail');
				return false;
			}

			@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'finish');
			return $zip_file;
		}

		//shortcut
		function save_on ($type, $zip_file){
			//set_time_limit (0);

			if ($type == 'Pro'){
				return $this->save_on_pro($zip_file);
			}
			else
			{
				return $this->save_on_S3($zip_file);
			}
		}


		function save_on_S3($zip_file)
		{
			@set_time_limit (0);

			//tools and info
			global $pressbackup;
			$preferences=get_option('pressbackup_preferences');
			$pressbackup->import('S3.php');
			$pressbackup->import('misc.php');
			$misc = new pressbackup_misc();

			@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'sent.log', 'start');

			//create S3 interface
			$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['s3']['credential']));
			$s3 = new PressbackupS3($credentials[0], $credentials[1]);

			//get buckets
			$buckets=$s3->listBuckets();

			//create bucket if it not exist
			$the_bucket = $pressbackup->Config->read('S3.bucketname').'-'.md5(uniqid(rand(), true));
			$create_bucket=true;
			for($i=0; $i<count($buckets);$i++) {
				if ( strpos($buckets[$i], $pressbackup->Config->read('S3.bucketname')) !== false ) {
					$the_bucket = $buckets[$i];
					$create_bucket = false; break;
				}
			}

			if ($create_bucket && !$s3->putBucket($the_bucket, PressbackupS3::ACL_PRIVATE, $preferences['pressbackup']['s3']['region']))
			{
				$pressbackup->Session->write( 'error_msg',  "cannot create bucket on S3");
				@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'sent.log', 'fail');
				return false;
			}

			$preferences['pressbackup']['s3']['bucket_name']=$the_bucket;
			update_option('pressbackup_preferences',$preferences);

			//save file
			if(!$s3->putObjectFile($zip_file, $the_bucket, baseName($zip_file), PressbackupS3::ACL_PRIVATE))
			{
				$pressbackup->Session->write( 'error_msg',  "cannot save file on S3");
				@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'sent.log', 'fail');
				return false;
			}

			//check the number of backup stored
			$bucket_files = $s3->getBucket($preferences['pressbackup']['s3']['bucket_name']);
			$bucket_files=@$misc->msort('S3', $bucket_files);
			$bucket_files= $misc->filter_files ('this_site', $bucket_files);
			if($preferences['pressbackup']['backup']['copies'] != 7 && count($bucket_files) > $preferences['pressbackup']['backup']['copies'])
			{
				$this->delete($bucket_files[count($bucket_files) -1]['name']);
			}

			//save massage
			$pressbackup->Session->write( 'general_msg', 'backup Saved!');
			@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'sent.log', 'finish');
			return true;
		}

		function save_on_pro($zip_file)
		{
			@set_time_limit (0);

			//tools and info
			global $pressbackup;
			$preferences=get_option('pressbackup_preferences');
			$pressbackup->import('Pro.php');
			$pressbackup->import('misc.php');
			$misc = new pressbackup_misc();

			@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'sent.log', 'start');

			$pressbackup->Session->delete( 'general_msg');

			//create Pro interface
			$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['pressbackuppro']['credential']));
			$pro = new pressbackup($credentials[0], $credentials[1]);

			//check site
			if(!$pro->check()){
				$pressbackup->Session->write( 'error_msg',  "This blog is not registered on your Pressbackup Pro account");
					@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'sent.log', 'fail');
				return false;
			}

			if(!$pro->putFile($zip_file)) {
				//save massage
				$pressbackup->Session->write( 'error_msg',  "Conection with Pressbackup Pro fail. Try again later");
					@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'sent.log', 'fail');
				return false;
			}

			//check the number of backup stored
			$bucket_files = $pro->getFilesList();
			$bucket_files=@$misc->msort('pressbackup', $bucket_files);
			$bucket_files=$misc->filter_files('this_site', $bucket_files);
			if($preferences['pressbackup']['backup']['copies'] != 7 && count($bucket_files) > $preferences['pressbackup']['backup']['copies'])
			{
				$this->delete($bucket_files[count($bucket_files) -1]['name']);
			}

			@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'sent.log', 'finish');
			return true;
		}

		function backup_files()
		{
			//tools and info
			global $pressbackup;
			$pressbackup->import('misc.php');
			$preferences=get_option('pressbackup_preferences');
			$misc = new pressbackup_misc();


			//read backup preferences
			$backup_file_type = explode(',', $preferences['pressbackup']['backup']['type']);

			//uploads
			if(in_array('1', $backup_file_type) && file_exists(WP_CONTENT_DIR. DS.'uploads'))
			{

				@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'Creating Uploads folder backup');

				$zip_file = $pressbackup->Path->Dir['PBKTMP'].DS.'uploads.zip';
				$folder = 'uploads'.DS;

				//zip files
				$type = 'shell';
				if( $preferences['pressbackup']['compatibility']['zip'] == 10 ) {
					$type = 'php';
				}
				$res = $misc->zip($type, array('context_dir'=>WP_CONTENT_DIR, 'dir' => $folder, 'zip' => $zip_file));


				//check response of zip creation
				if( !$res ) {
					$pressbackup->Session->write( 'error_msg',  "Can not create zip archive for uploads folder!");
					@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'fail');
					return false;
				}
			}

			//plugins
			if(in_array('3', $backup_file_type))
			{
				@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'Creating Plugins folder backup');

				$zip_file = $pressbackup->Path->Dir['PBKTMP'].DS.'plugins.zip';
				$folder = 'plugins'.DS;

				//zip files
				$type = 'shell';
				if( $preferences['pressbackup']['compatibility']['zip'] == 10 ) {
					$type = 'php';
				}
				$res = $misc->zip($type, array('context_dir'=>WP_CONTENT_DIR, 'dir' => $folder, 'zip' => $zip_file));

				//check response of zip creation
				if( !$res ) {
					$pressbackup->Session->write( 'error_msg',  "Can not create zip archive for plugins folder!");
					@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'fail');
					return false;
				}
			}

			//themes
			if(in_array('5', $backup_file_type))
			{
				@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'Creating Themes folder backup');

				$zip_file = $pressbackup->Path->Dir['PBKTMP'].DS.'themes.zip';
				$folder = 'themes'.DS;

				//zip files
				$type = 'shell';
				if( $preferences['pressbackup']['compatibility']['zip'] == 10 ) {
					$type = 'php';
				}
				$res = $misc->zip($type, array('context_dir'=>WP_CONTENT_DIR, 'dir' => $folder, 'zip' => $zip_file));

				//check response of zip creation
				if( !$res ) {
					$pressbackup->Session->write( 'error_msg',  "Can not create zip archive for plugins folder!");
					@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'fail');
					return false;
				}
			}
			return true;
		}

		function backup_db ()
		{
			//tools and info
			global $wpdb;
			global $pressbackup;
			$preferences=get_option('pressbackup_preferences');

			//maximun multimple inserts
			$insert_max = 50;

			//read backup preferences
			$backup_db_type = explode(',', $preferences['pressbackup']['backup']['type']);

			if(in_array('7', $backup_db_type))
			{

				@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'Creating Database backup');

				//save .httaccess for this server
				@copy(ABSPATH.'.htaccess', $pressbackup->Path->Dir['PBKTMP'].DS.'.htaccess');

				//save server for this SQL
				$file =$pressbackup->Path->Dir['PBKTMP'].DS.'server';
				if(!$fh=fopen( $file, 'w'))
				{
					$pressbackup->Session->write( 'error_msg',  "cannot create file to store server for this SQL dump ");
					@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'fail');
					return false;
				}
				fwrite($fh, get_bloginfo( 'wpurl' ));
				fclose($fh);

				//create .sql file
				$file = $pressbackup->Path->Dir['PBKTMP'].DS.'database.sql';
				if(!$fh=fopen( $file, 'w'))
				{
					$pressbackup->Session->write( 'error_msg',  "cannot create file for SQL dump ");
					@file_put_contents($pressbackup->Path->Dir['LOGTMP'] . DS . 'create.log', 'fail');
					return false;
				}

				//dump DB
				$file_header= '-- PressBackup SQL Dump'."\n".
				'-- version 1.0'."\n".
				'-- http://www.infinimedia.com'."\n\n".
				'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";'."\n\n";
				fwrite($fh, $file_header."\n\n");

				$DB_tables=$wpdb->get_results('SHOW TABLES');
				$method2 = 'Tables_in_'.DB_NAME;

				for($i=0; $i<count($DB_tables); $i++)
				{

					//table estructure
					$query = $wpdb->get_results('show create table '.$DB_tables[$i]->$method2);

					//"Create Table" with a space in the middle.
					$method = 'Create Table';
					$table_structure = $query[0]->$method;
					$table_structure = str_replace ('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $table_structure);
					fwrite($fh, "\n\n".$table_structure.";\n\n");

					//inserts -header
					$describe_table = $wpdb->get_results('DESCRIBE '.$DB_tables[$i]->$method2);
					$insert_header=$fields=array();
					for ($j=0; $j< count($describe_table); $j++)
					{
						$insert_header[$j] = '`'. $describe_table[$j]->Field.'`';
						$fields[$j]=$describe_table[$j]->Field;
					}
					$insert_header= 'INSERT INTO `'.$DB_tables[$i]->$method2.'` ( '. join(',', $insert_header). ') VALUES ';


					//count rows for inserts -data
					$inserts_count = $wpdb->get_results('SELECT count(*) as cant FROM  `'.$DB_tables[$i]->$method2.'`');
					$insert_pages = ceil ($inserts_count[0]->cant / $insert_max);

					//dump insert rows
					for ($l = 0; $l < $insert_pages; $l ++)
					{
						//inserts -data
						unset($inserts); $inserts = array();
						$inserts = $wpdb->get_results('SELECT * FROM  `'.$DB_tables[$i]->$method2.'` LIMIT '.($l * $insert_max).','.$insert_max);

						unset($insert_data); $insert_data=array();
						for ($j=0; $j< count($inserts); $j++)
						{
							unset($popo); $popo=array();
							for ($k=0; $k< count($fields); $k++)
							{
								$popo[$k]='\''.mysql_real_escape_string($inserts[$j]->$fields[$k]).'\'';
							}
							$insert_data[$j] = '('.join(',', $popo).')';
						}
						if($insert_data) {
							fwrite($fh, "\n".$insert_header."\n".join(",\n", $insert_data).';'."\n\n");
						}
					}
				}
				fclose($fh);
			}

			return true;
		}

	//		Restore backup functions
	//----------------------------------------------------------------------------------------

		function restore ($args)
		{
			//set infinite time for this action
			set_time_limit (0);

			//tools and info
			global $pressbackup;
			$pressbackup->import('misc.php');
			$misc = new pressbackup_misc();

			//clean log && tmp dir
			$misc->perpare_folder($pressbackup->Path->Dir['LOGTMP']);
			$misc->perpare_folder($pressbackup->Path->Dir['PBKTMP']);

			//extract backup
			$zip = new ZipArchive();
			if(!$zip->open($args['tmp_name'])===true)
			{
				$pressbackup->Session->write( 'error_msg',  "Backup seems corrupt. Process aborted");
				return false;
			}
			$zip->extractTo($pressbackup->Path->Dir['SYSTMP']);
			$zip->close();

			//restore backup
			if(!$this->restore_files() || !$this->restore_db())
			{
				return false;
			}

			$pressbackup->Session->write( 'general_msg',  "System restored!!");
			return true;
		}

		function restore_files()
		{
			//tools and info
			global $pressbackup;
			$pressbackup->import('misc.php');
			$misc = new pressbackup_misc();

			//shortcuts
			$PBKTMP = $pressbackup->Path->Dir['PBKTMP'].DS;

			$zip = new ZipArchive();

			//restore themes
			if(file_exists($PBKTMP .'themes.zip')){
				if($zip->open( $PBKTMP .'themes.zip') !== true) {
					$pressbackup->Session->write( 'error_msg',  "Can't restore themes, themes backup is corrupt. Restore process aborted");
					return false;
				}

				if(!@$zip->extractTo(WP_CONTENT_DIR))
				{
					$zip->close();
					$pressbackup->Session->write( 'error_msg',  "Can't restore themes folder, permission denied to write on <b>wp-content</b> foder. Restore process aborted");
					return false;
				}
				$zip->close();
			}

			//restore uploads
			if(file_exists($PBKTMP .'uploads.zip')){
				if($zip->open( $PBKTMP .'uploads.zip') !== true)
				{
					$pressbackup->Session->write( 'error_msg',  "Can't restore uploads folder, uploads backup is corrupt. Restore process aborted");
					return false;
				}

				if(!@$zip->extractTo(WP_CONTENT_DIR))
				{
					$zip->close();
					$pressbackup->Session->write( 'error_msg',  "Can't restore uploads folder, permission denied to write on <b>wp-content</b> foder. Restore process aborted");
					return false;
				}

				$zip->close();
			}

			//restore plugins
			if(file_exists($PBKTMP .'plugins.zip')){
				if($zip->open( $PBKTMP .'plugins.zip') !== true) {
					$pressbackup->Session->write( 'error_msg',  "Can't restore plugins folder, plugins backup is corrupt. Restore process aborted");
					return false;
				}

				if(!@$zip->extractTo(WP_CONTENT_DIR))
				{
					$zip->close();
					$pressbackup->Session->write( 'error_msg',  "Can't restore plugins folder, permission denied to write on <b>wp-content</b> foder. Restore process aborted");
					return false;
				}
				$zip->close();
			}
			return true;
		}

		function restore_db()
		{
			require_once(ABSPATH . '/wp-admin/admin.php');
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );

			//tools and info
			global $pressbackup;
			global $wpdb;
			global $wp_rewrite;

			//shortcuts
			$PBKTMP = $pressbackup->Path->Dir['PBKTMP'].DS;

			//check if need to restore DB
			if(!file_exists($PBKTMP .'database.sql')){ return true; }

			//get old server name to can restore new DB with it
			if(!$fn=fopen( $PBKTMP .'server', 'rb')) {
				$pressbackup->Session->write( 'error_msg',  "Can't restore database, missing files. Restore process aborted");
				return false;
			}
			$last_server=trim(fgets($fn)); fclose($fn);
			$new_server = get_bloginfo( 'wpurl' );

			//get SQL dump
			if(!$DBdump = @fopen($PBKTMP .'database.sql', 'rb')) {
				$pressbackup->Session->write( 'error_msg',  "Can't restore database, missing files (.sql). Restore process aborted");
				return false;
			}

			//Drop current DB tables
			$DB_tables=$wpdb->get_results('SHOW TABLES');
			$method = 'Tables_in_'.DB_NAME;
			for($i=0; $i<count($DB_tables); $i++)
			{
				$wpdb->query('DROP TABLE '.$DB_tables[$i]->$method);
			}

			//Read headers from .sql
			 $inserts='';
			while (($buffer=fgets($DBdump)) && !preg_match("/^INSERT INTO(.)*/", $buffer) && !preg_match("/^CREATE TABLE IF NOT EXISTS(.)*/", $buffer))
			{
				$inserts .= $buffer;
			}
			$inserts = str_replace($last_server, $new_server, $inserts); //echo $inserts;
			@$wpdb->query(trim($inserts));

			//Read until EOF cuting sql into create and insert stataments
			while($buffer)
			{
				if(preg_match("/^CREATE TABLE IF NOT EXISTS(.)*/", $buffer)){

					$inserts = $buffer;
					while(($buffer=fgets($DBdump)) && !preg_match("/^INSERT INTO(.)*/", $buffer) && !preg_match("/^CREATE TABLE IF NOT EXISTS(.)*/", $buffer))
					{
						$inserts .= $buffer;
					}
					$inserts = str_replace($last_server, $new_server, $inserts); //echo $inserts;
					dbDelta( trim($inserts) ); //@$wpdb->query(trim($inserts));
				}

				if(preg_match("/^INSERT INTO(.)*/", $buffer)) {

					//save insert header
					$header = $buffer;

					// start read insert values
					$i=0; $inserts='';
					while(($buffer=fgets($DBdump)) && !preg_match("/^INSERT INTO(.)*/", $buffer) && !preg_match("/^CREATE TABLE IF NOT EXISTS(.)*/", $buffer))
					{
						$inserts .= $buffer; $i++;

						//inser 50 results at once
						if($i==50) {
							$inserts= trim($inserts);
							if( substr($inserts, -1) == ',') { $inserts = substr_replace($inserts, ';', -1, 1);}
							$inserts = str_replace($last_server, $new_server, $inserts);
							@$wpdb->query(trim($header.$inserts)); //echo $header.$inserts;
							$i=0; $inserts='';
						}
					}

					//if something remaing to save
					if($i>0) {
						$inserts= trim($inserts);
						if( substr($inserts, -1) == ',') { $inserts = substr_replace($inserts, ';', -1, 1);}
						$inserts = str_replace($last_server, $new_server, $inserts);
						@$wpdb->query(trim($header.$inserts)); //echo trim($header.$inserts)."\n\n";

					}
				}

			}
			fclose($DBdump);

			//update options
			$siteopts = wp_load_alloptions();
			$this->update_site_options($siteopts, $last_server, $new_server);

			//copy backed up .htaccess
			//@copy($PBKTMP .'.htaccess', ABSPATH .'.htaccess') ;

			//remake .htaccess
			$preferences= get_option('pressbackup_preferences');
			$preferences['pressbackup']['restore']=true;
			update_option('pressbackup_preferences',$preferences);

			return true;
		}

		function update_site_options(array $options, $old_url, $new_url) {
			require_once ABSPATH .'wp-includes/functions.php';
			foreach ($options as $option_name => $option_value) {

				if (FALSE === strpos($option_value, $old_url)) {
					continue;
				}

				if (is_array($option_value)) {
					$this->update_site_options($option_value, $old_url, $new_url);
				}

				// attempt to unserialize option_value
				if(!is_serialized($option_value)) {
					$newvalue = str_replace($old_url, $new_url, $option_value);
				} else {
					$newvalue = $this->update_serialized_options(maybe_unserialize($option_value), $old_url, $new_url);
				}

				update_option($option_name, $newvalue);
			}
		}

		function update_serialized_options($data, $old_url, $new_url) {
			require_once ABSPATH .'wp-includes/functions.php';
			// ignore _site_transient_update_*
			if(is_object($data)){
				return $data;
			}

			foreach ($data as $key => $val) {
				if (is_array($val)) {
						$data[$key] = $this->update_serialized_options($val, $old_url, $new_url);
				} else {
					if (!strstr($val, $old_url)) {
						continue;
					}
					$data[$key] = str_replace($old_url, $new_url, $val);
				}
			}
			return $data;
		}

	//		Get backup functions
	//----------------------------------------------------------------------------------------

		function get ($file='')
		{
			//set infinite time for this action
			set_time_limit (0);

			//tools and info
			global $pressbackup;
			$pressbackup->import('misc.php');
			$misc = new pressbackup_misc();

			$preferences=get_option('pressbackup_preferences');

			//shortcuts
			$DS = DS;
			$SYSTMP = $pressbackup->Path->Dir['SYSTMP'].$DS;

			if(!$file){
				$pressbackup->Session->write( 'error_msg',  "Missing backup file name");
				return false;
			}

			//clear temp dir
			$misc->actionFolder($pressbackup->Path->Dir['PBKTMP'].DS,  array('function'=>'del'));
			@mkdir($pressbackup->Path->Dir['PBKTMP']);
			@chmod($pressbackup->Path->Dir['PBKTMP'], 0777);

			//where to put backup file
			$name=uniqid();
			$stored_in = $store_in = $SYSTMP . $name;

			if($preferences['pressbackup']['s3']['credential'])
			{
				$pressbackup->import('S3.php');
				$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['s3']['credential']));
				$s3 = new PressbackupS3($credentials[0], $credentials[1]);
				$transfer=$s3->getObject($preferences['pressbackup']['s3']['bucket_name'],$file, $store_in);
			}
			else
			{
				$pressbackup->import('Pro.php');
				$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['pressbackuppro']['credential']));
				$pbp = new pressbackup($credentials[0], $credentials[1]);
				$transfer=$pbp->getFile($file, $store_in);
			}

			if($transfer !== false)
			{
				return $stored_in;
			}
			else
			{
				$pressbackup->Session->write( 'error_msg',  'Failed to get file');
				return false;
			}
		}

		function get2 ($file='')
		{
			//set infinite time for this action
			set_time_limit (0);

			//tools and info
			global $pressbackup;
			$preferences=get_option('pressbackup_preferences');

			if(!$file){
				$pressbackup->Session->write( 'error_msg',  "Missing backup file name");
				return false;
			}

			$pressbackup->import('Pro.php');
			$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['pressbackuppro']['credential']));
			$pbp = new pressbackup($credentials[0], $credentials[1]);
			$transfer=$pbp->getFile2($file);

			if($transfer !== false)
			{
				return true;
			}
			else
			{
				$pressbackup->Session->write( 'error_msg',  'Failed to get file 2');
				return false;
			}
		}

	//		Delete backup functions
	//----------------------------------------------------------------------------------------

		function delete($file='')
		{
			//set infinite time for this action
			set_time_limit (0);

			//tools and info
			global $pressbackup;
			$preferences=get_option('pressbackup_preferences');


			if(!$file){
				$pressbackup->Session->write( 'error_msg',  "Missing backup file name");
				return false;
			}

			if($preferences['pressbackup']['s3']['credential'])
			{
				$pressbackup->import('S3.php');
				$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['s3']['credential']));
				$s3 = new PressbackupS3($credentials[0], $credentials[1]);
				$deleted=$s3->deleteObject($preferences['pressbackup']['s3']['bucket_name'],$file);

			}
			else
			{
				$pressbackup->import('Pro.php');
				$credentials=explode('|AllYouNeedIsLove|', base64_decode($preferences['pressbackup']['pressbackuppro']['credential']));
				$pbp = new pressbackup($credentials[0], $credentials[1]);
				$deleted=$pbp->deleteFile($file);
			}

			if($deleted)
			{
				$pressbackup->Session->write( 'general_msg',  'File deleted!');
				return true;
			}
			else
			{
				//$pressbackup->Session->write( 'error_msg',  'Failed to delete file');
				return false;
			}
		}

	//		Schedule functions
	//----------------------------------------------------------------------------------------

		function add_schedule($time=null, $task = 'pressback_backup_start_cronjob') {
			global $pressbackup;
			$preferences=get_option('pressbackup_preferences');

			if($time){
				$start_time = strtotime($time);
			}
			else{
				$start_time = time() + ($preferences['pressbackup']['backup']['time'] * (60 * 60));
			}
			$this->remove_schedule($task);

			$preferences= get_option('pressbackup_preferences');
			@wp_schedule_single_event($start_time, $task);
			return true;
		}

		function remove_schedule($task = 'pressback_backup_start_cronjob') {
			@wp_clear_scheduled_hook($task);
			return true;
		}

	}
?>
