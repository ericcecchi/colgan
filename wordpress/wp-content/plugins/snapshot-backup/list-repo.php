<h2>List FTP Repository</h2>
<p>You can list the contents of your FTP repository here.</p>

<?php
// check if this button has been pressed
 if( isset($_POST[ $hidden_field_name3 ]) && $_POST[ $hidden_field_name3 ] == 'Y' ) {

// set up variables - change these to suit application
$host = get_option('snapshot_ftp_host');
$user = get_option('snapshot_ftp_user');
$pass = get_option('snapshot_ftp_pass');
$subdir = get_option('snapshot_ftp_subdir');
$remotefile = 'httpdocs/wp-content/uploads/test.sql';
$localfile = '/var/www/vhosts/versluis.com/subdomains/dev/httpdocs/wp-content/uploads/test.sql';

// connect to host
$conn = ftp_connect($host);
if (!$conn)
{
  echo 'Error: Could not connect to ftp server. Please check your FTP details.<br />';
  exit;
}
echo "Connected to $host.<br />";

// log in to host
$result = @ftp_login($conn, $user, $pass);
if (!$result)
{
  echo "Error: Could not log on as $user<br />. Please check your FTP details.";
    ftp_quit($conn);
  exit;
}
echo "Logged in as $user<br />";

// list direcory contents
echo 'Here comes the Directory Listing:<br>';
$listing = ftp_nlist($conn, $subdir);
if (!$listing) {
echo "The path or directory <strong>$subdir</strong> does not exist on the server. Please check your FTP details.<br />";
}
foreach ($listing as $file) {
echo '<strong>&nbsp;&nbsp;&bull;&nbsp;'.basename($file).'<br></strong>';

}
fclose($fp);

// close connection to host
ftp_quit($conn);
echo "<br>Done!<br>";
}
?>

<p>
<form name="form3" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name3; ?>" value="Y">
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('List existing backups') ?>" />
</p>
</form>
<hr />