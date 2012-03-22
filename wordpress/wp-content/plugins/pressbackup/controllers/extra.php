<?
 /**
 * Extra Controller for Pressbackup.
 *
 * This Class provide misc function that are called automaticaly at some point
 *
 * Licensed under The GPL v2 License
 * Redistributions of files must retain the above copyright notice.
 *
 * @link				http://pressbackup.com
 * @package		controlers
 * @subpackage	controlers.extra
 * @since			0.5
 * @license			GPL v2 License
 */

class PressbackExtra {

	/**
	 * Check if the scheluded taks is active and active otherwise
	 *
	 * Note: this function its called at the init of the plugin
	*/
	function pressbackup_check_schedule () {
		//tools
		global $pressbackup;
		$preferences= get_option('pressbackup_preferences');

		//set the type of credential to see what option show in this page
		$credentials = false;
		if($preferences['pressbackup']['s3']['credential'] || $preferences['pressbackup']['pressbackuppro']['credential']) {
			$credentials= true;
		}

		//fix to missing rew schedule
		if ($credentials && !$ns=wp_next_scheduled('pressback_backup_start_cronjob')){
			$pressbackup->import('backup_functions.php');
			$pb = new pressbackup_backup();
			$pb->add_schedule();
		}
	}

	/**
	 * Restore the permalinks
	 *
	 * called after a restore is done
	 * Note: this function its called at the admin init
	 */
	function pressbackup_restore_htaccess () {
		//tools
		global $pressbackup;
		global $wp_rewrite;
		$preferences= get_option('pressbackup_preferences');

		if(isset($preferences['pressbackup']['restore']))
		{
			$wp_rewrite->flush_rules();
			unset($preferences['pressbackup']['restore']);
			update_option('pressbackup_preferences',$preferences);
		}
		return true;
	}

}
?>
