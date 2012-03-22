<?php
/*
Plugin Name: Snapshot Backup
Plugin URI: http://wpguru.co.uk/2011/02/snapshot-backup/
Description: Backs up your ENTIRE Wordpress site and sends it to an FTP archive. Excellent!
Author: Jay Versluis
Version: 1.5
Author URI: http://wpguru.co.uk
License: GPLv2 or later

Copyright 2011 by Jay Versluis (email : versluis2000@yahoo.com)

This is Version 1.5.1 as of 08/04/2011

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

// Hook for adding admin menu
add_action('admin_menu', 'snapshot_admin');

// action function for above hook
function snapshot_admin() {

// Add a new submenu under DASHBOARD
add_dashboard_page('Snapshot Backup', 'Snapshot Backup', 'administrator', 'snapshot-admin', 'snapshot');
}

// displays the page content for the admin submenu
function snapshot() {

//must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $opt_name = 'snapshot_ftp_host';
    $opt_name2 = 'snapshot_ftp_user';
    $opt_name3 = 'snapshot_ftp_pass';
    $opt_name4 = 'snapshot_ftp_subdir';
	$opt_name5 = 'snapshot_ftp_prefix';
	$opt_name6 = 'snapshot_add_dir1';
    $hidden_field_name = 'snapshot_ftp_hidden';
    $hidden_field_name2 = 'snapshot_backup_hidden';
    $hidden_field_name3 = 'snapshot_check_repo';
    $data_field_name = 'snapshot_ftp_host';
    $data_field_name2 = 'snapshot_ftp_user';
    $data_field_name3 = 'snapshot_ftp_pass';
    $data_field_name4 = 'snapshot_ftp_subdir';
	$data_field_name5 = 'snapshot_ftp_prefix';
	$data_field_name6 = 'snapshot_add_dir1';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
    $opt_val2 = get_option ($opt_name2 );
    $opt_val3 = get_option ($opt_name3 );
    $opt_val4 = get_option ($opt_name4 );
	$opt_val5 = get_option ($opt_name5 );
	$opt_val6 = get_option ($opt_name6 );


    // reset working directory to WP root
    // chdir('../');
	chdir (ABSPATH);
	
/* 
 * @since 1.5
 * ADDITIONAL BACKUP SETTINGS
 */
 
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name3 ]) && $_POST[ $hidden_field_name3 ] == 'Y' ) {
    // Read their posted value
    $opt_val6 = trim($_POST[ $data_field_name6 ]);
	// Save the posted value in the database
    update_option( $opt_name6, $opt_val6 );
	// Put a "settings updated" message on the screen
?>
<div class="updated"><p><strong><?php echo 'Your additional directory has been saved.'; ?></strong></p></div>
<?php
    }
/*
 * @since 1.0
 * FTP FORM SETTINGS
 */
	
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
    $opt_val = trim($_POST[ $data_field_name ]);
    $opt_val2 = trim($_POST[ $data_field_name2 ]);
	$opt_val3 = trim($_POST[ $data_field_name3 ]);
    $opt_val4 = trim($_POST[ $data_field_name4 ]);
	$opt_val5 = trim($_POST[ $data_field_name5 ]);
        
	// Save the posted value in the database
    update_option( $opt_name, $opt_val );
    update_option( $opt_name2, $opt_val2 );
	update_option( $opt_name3, $opt_val3 );
	update_option( $opt_name4, $opt_val4 );
	update_option( $opt_name5, $opt_val5 );

        // Put a "settings updated" message on the screen
?>
<div class="updated"><p><strong><?php _e('Your FTP details have been saved.', 'snapshot-menu' ); ?></strong></p></div>
<?php
    }

/****************************************************
/ SNAPSHOT ADMIN AREA
/****************************************************/
// Header
?>
<div class="wrap">
<h2><?php echo '<img src="'. plugins_url('WP-Guru-Logo.png', __FILE__) .'">'; ?>&nbsp;Welcome to Snapshot Backup!</h2>
<table class="snapshot-backup" width=600 cellspacing=10 bgcolor=red>
<tr><td>
<p><strong>With this plugin you can create an up-to-the-minute archive of your entire website and save it to an offsite location via FTP.</strong></p>
<p>Things couldn't be easier: </p>
<ul><li>&nbsp;&nbsp;&bull; enter your FTP details at the bottom</li>
<li>&nbsp;&nbsp;&bull; click on CREATE NEW SNAPSHOT</li>
<li>&nbsp;&nbsp;&bull; rest assured you've backed your database AND contents with just one single click</li></ul>
<p>If you don't have an FTP account you can <a href="http://wpguru.co.uk/hosting/ftp/" target="_blank">sign up for one here</a> or download your snapshot from this server once it's done.</p>
</td></tr>
</table>
<p>Any questions? Check out the included <?php echo '<a href="' . plugins_url('readme.txt', __FILE__) .'"'; ?>" target="_blank">readme file</a> or visit the <a href="http://wpguru.co.uk/2011/02/snapshot-backup/" target="_blank">Snapshot Backup Website</a>. Have fun!</p>

<?php

 if( isset($_POST[ $hidden_field_name2 ]) && $_POST[ $hidden_field_name2 ] == 'Y' ) {
/*
 * @since 1.0
 * MAIN SNAPSHOT BUTTON
 */
// this could take a while...
// set the number of seconds you'd like to wait for the script here
// default is 300
set_time_limit(300);

// create global file name
$filetime = date('Ymd-Gi');
// echo "<br>The Filetime is $filetime <br>";
// readout the Database
include plugin_dir_path(__FILE__).'database.php';
// create ZIP package
include plugin_dir_path(__FILE__).'zipshot.php';
// send package to FTP
include plugin_dir_path(__FILE__).'sendaway.php';
// we're done
echo '<div class="updated"><h2>All done - thank you!</h2>';
?>
</div>
<?php
}

?>

<form name="form2" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name2; ?>" value="Y">
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Create New Snapshot') ?>" />
</p>
</form>
<hr />

<?php
// call Recent Download option
if (get_option('snapshot_latest')){
include plugin_dir_path(__FILE__).'download-recent.php';
}
// call FTP Details form
include plugin_dir_path(__FILE__).'ftp-form.php';

// call Backup Settings
include plugin_dir_path(__FILE__).'settings.php';

// footer
?>

<p><strong>Coming soon: automated backups, repository browser, snapshot restore option. Watch this space! </strong></p>
<p>This plugin was brought to you by<br />
  <a href="http://wpguru.co.uk" target="_blank"><?php echo '<img src="'. plugins_url('guru-header.jpg', __FILE__) .'">'; ?></a>
</p>
<p>Snapshot Backup Version 1.5 | <a href="http://wpguru.co.uk/2011/02/snapshot-backup/" target="_blank">Plugin Home Page</a>  | <a href="http://plugins.trac.wordpress.org/log/snapshot-backup/" target="_Blank">Changelog</a> | <a href="http://wpguru.co.uk/hosting/ftp/" target="_blank">Get an FTP Account</a> | <a href="http://wpguru.co.uk/say-thanks/" target="_blank">Buy me a Coffee</a></p>

<?php
}
