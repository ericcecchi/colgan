<h2>FTP Details</h2>
<?php

        ?>

<p>Enter your FTP details for your offsite backup repository. Leave these blank for local backups.</p>		
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<table width="700" border="0" cellspacing="10">
  <tr>
    <td>FTP Host:</td>
    <td><input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="25"></td>
    <td><em>e.g. ftp.yoursite.com </em></td>
  </tr>
  <tr>
    <td>FTP User:</td>
    <td><input type="text" name="<?php echo $data_field_name2; ?>" value="<?php echo $opt_val2; ?>" size="25"></td>
    <td>&nbsp;      </td>
  </tr>
  <tr>
    <td>FTP Password:</td>
    <td><input type="password" name="<?php echo $data_field_name3; ?>" value="<?php echo $opt_val3; ?>" size="25"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Subdirectory:</td>
    <td><input type="text" name="<?php echo $data_field_name4; ?>" value="<?php echo $opt_val4; ?>" size="25"></td>
    <td><em>e.g. /httpdocs/backups or leave blank</em> </td>
  </tr>

  <tr>
    <td>Snapshot Prefix: </td>
    <td><input type="text" name="<?php echo $data_field_name5; ?>" value="<?php echo $opt_val5; ?>" size="25"></td>
    <td><em>filename is prefix-snapshot-date-time.tar</em></td>
  </tr>
</table>
<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
  <br />
</p>
</form>
<hr />