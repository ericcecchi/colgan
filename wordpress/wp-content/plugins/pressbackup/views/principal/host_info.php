<div class="updated fade" style="width:500px;" ><p>

==WP Info==<br/>
* url: <?echo $wp['wpurl']."<br/>\n";?>
* version: <?echo $wp['version']."<br/>\n";?>

==Host Info==<br/>

* Web Server : <?echo $server['SERVER_SOFTWARE']."<br/>\n";?>
* Port : <?echo $server['SERVER_PORT']."<br/>\n";?>
* SAPI : <?echo $server_sapi."<br/>\n";?>

=Host modules=<br/>

	<?for ($i=0; $i< count($modules); $i++) { echo '&nbsp;&nbsp;  * '.$modules[$i]; if ($i % 4 == 0) {echo "<br/>\n";} } echo "<br/>\n";?>

==Browser==<br/>

* version: <?echo $server['HTTP_USER_AGENT']."<br/>\n";?>

==Plugin info==<br/>
* version: <? echo $p['Version']; echo "<br/>\n";?>
* sys tmp: <? echo $psystmp; echo "<br/>\n";?>
* wpf tmp: <? echo $pwpftmp; echo "<br/>\n";?>
<br/>

</p></div>
<?echo $this->html->link('Back to dashboard', array('menu_type'=>'tools', 'controller'=>'principal', 'function'=>'dashboard'), array('class'=>'button'));?>
