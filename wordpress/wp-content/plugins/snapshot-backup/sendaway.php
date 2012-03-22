<?php
echo "<h2>Send package to FTP site</h2>";

// set up variables
$host = get_option('snapshot_ftp_host');
$user = get_option('snapshot_ftp_user');
$pass = get_option('snapshot_ftp_pass');
$subdir = get_option('snapshot_ftp_subdir');
$remotefile = $subdir.'/'.$filename;
$localfile = WP_CONTENT_DIR .'/uploads/' . $filename;

// connect to host
$conn = ftp_connect($host);
if (!$conn)
{
  echo 'Error: Could not connect to ftp server. This will be local backup.<br />';
  // exit;
}
echo "Connected to $host.<br />";

// log in to host
$result = @ftp_login($conn, $user, $pass);
if (!$result)
{
  echo "Error: Could not log on as $user. This will be local backup.<br />";
    ftp_quit($conn);
  // exit;
}
echo "Logged in as $user<br />";

// upload file
// http://uk2.php.net/manual/en/function.ftp-put.php
echo 'Uploading package to FTP repository...<br />';
// echo "Local file is $localfile - Remote File is $remotefile<br>";
// $fp = fopen ($localfile, 'r');
if (!$success = ftp_put($conn, $remotefile, $localfile, FTP_BINARY))
{
  echo 'Error: Could not upload file. This will be local backup.<br />';
  ftp_quit($conn);
  // exit;
}

// before we go, let's list the directory

// list direcory contents
// echo '<br>Here comes the Directory Listing:<br>';
// $listing = ftp_nlist($conn, "/httpdocs/");
// foreach ($listing as $filename)
// echo "$filename <br>";

fclose($fp);
echo 'File was uploaded successfully <br />';

// close connection to host
ftp_quit($conn);
// echo "... Done!";
?>