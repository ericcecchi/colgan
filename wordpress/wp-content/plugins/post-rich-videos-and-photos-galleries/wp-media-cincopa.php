<?php
/*
Plugin Name: Post video players slideshow and photo galleries
Plugin URI: http://www.cincopa.com/wpplugin/wordpress-plugin.aspx
Description: Post rich videos and photos galleries from your cincopa account
Author: Cincopa 
Version: 1.78
*/


function cincopa_plugin_ver()
{
	return 'wp1.78';
}

function cincopa_afc()
{
	$cincopa_afc = get_site_option('CincopaAFC');
	
	if ($cincopa_afc == '')
		return '';
	
	return '&afc='.$cincopa_afc;
}

function cincopa_url()
{
	return 'http://www.cincopa.com';
}

if (strpos($_SERVER['REQUEST_URI'], 'media-upload.php') && strpos($_SERVER['REQUEST_URI'], '&type=cincopa') && !strpos($_SERVER['REQUEST_URI'], '&wrt='))
{
	header('Location: '.cincopa_url().'/wpplugin/start.aspx?ver='.cincopa_plugin_ver().cincopa_afc().'&rdt='.urlencode(cincopa_selfURL()));
	exit;
}

function cincopa_selfURL()
{
	$s = empty ( $_SERVER ["HTTPS"] ) ? '' : ($_SERVER ["HTTPS"] == "on") ? "s" : "";

	$protocol =  strtolower ( $_SERVER ["SERVER_PROTOCOL"] );
	$protocol =  substr($protocol, 0, strpos($protocol, "/"));
	$protocol .= $s;

	$port = ($_SERVER ["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER ["SERVER_PORT"]);
	$ret = $protocol . "://" . $_SERVER ['SERVER_NAME'] . $port . $_SERVER ['REQUEST_URI'];

	return $ret;
}

function cincopa_pluginURI()
{
	return get_option('siteurl').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
}

function WpMediaCincopa_init() // constructor
{
//		load_plugin_textdomain('wp-media-cincopa', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)));

	add_action('media_buttons', 'cincopa_addMediaButton', 20);

	add_action('media_upload_cincopa', 'media_upload_cincopa');
	// No longer needed in WP 2.6
	if ( !function_exists('wp_enqueue_style') )
	{
		add_action('admin_head_media_upload_type_cincopa', 'media_admin_css');
	}
      
	// check auth enabled
	//if(!function_exists('curl_init') && !ini_get('allow_url_fopen')) {}
}

function cincopa_addMediaButton($admin = true)
{
	global $post_ID, $temp_ID;
	$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);

	$media_upload_iframe_src = get_option('siteurl').'/wp-admin/media-upload.php?post_id=$uploading_iframe_ID';

	$media_cincopa_iframe_src = apply_filters('media_cincopa_iframe_src', "$media_upload_iframe_src&amp;type=cincopa&amp;tab=cincopa");
	$media_cincopa_title = __('Add Cincopa photo', 'wp-media-cincopa');

	echo "<a class=\"thickbox\" href=\"{$media_cincopa_iframe_src}&amp;TB_iframe=true&amp;height=500&amp;width=640\" title=\"$media_cincopa_title\"><img src=\"".cincopa_pluginURI()."/media-cincopa.gif\" alt=\"$media_cincopa_title\" /></a>";
}

function cincopa_modifyMediaTab($tabs)
{
	return array(
		'cincopa' =>  __('Cincopa photo', 'wp-media-cincopa'),
	);
}

function media_upload_cincopa()
{
	wp_iframe('media_upload_type_cincopa');
}


function media_upload_type_cincopa()
{
	global $wpdb, $wp_query, $wp_locale, $type, $tab, $post_mime_types;
	add_filter('media_upload_tabs', 'cincopa_modifyMediaTab');
?>

<br />
<br />
<h2>&nbsp;&nbsp;Please Wait...</h2>

<script>

	function cincopa_stub()
	{
	var i = location.href.indexOf("&wrt=");

	if (i > -1)
	{
	top.send_to_editor(unescape(location.href.substring(i+5)));
	}

	top.tb_remove();
	}

	window.onload = cincopa_stub;

</script>

<?php
}

WpMediaCincopa_init();

define("CINCOPA_REGEXP", "/\[cincopa ([[:print:]]+?)\]/");

function cincopa_plugin_callback($match)
{
	$uni = uniqid('');
	$ret = '
<!-- Powered by Cincopa WordPress plugin '.cincopa_plugin_ver().': http://www.cincopa.com/wpplugin/wordpress-plugin.aspx -->
<div id="_cp_widget_'.$uni.'"><img src="http://www.cincopa.com/wpplugin/runtime/loading.gif" style="border:0;" alt="Powered by Cincopa WordPress plugin" /></div>
<script src="http://www.cincopa.com/wpplugin/runtime/libasync.js" type="text/javascript"></script>
<script type="text/javascript">
// PLEASE CHANGE DEFAULT EXCERPT HANDLING TO CLEAN OR FULL (go to your Wordpress Dashboard/Settings/Cincopa Options ...
cp_load_widget("'.urlencode($match[0]).'", "_cp_widget_'.$uni.'");
</script>
';

	$ret .= '<noscript>Click <a href="http://www.cincopa.com/wpplugin/view.aspx?fid='.urlencode($match[0]).'">here</a> to open the gallery.<br>';
	$ret .= 'Powered by Cincopa <a href="http://www.cincopa.com/wpplugin/wordpress-plugin.aspx">wp content plugins</a> solution for your website and Cincopa MediaSend for <a href="http://www.cincopa.com/mediasend/start.aspx">file transfer</a>.';
	$ret .= '</noscript>';

	return $ret;
}

function cincopa_async_plugin_callback($match)
{
	$uni = uniqid('');
	$ret = '
<!-- Powered by Cincopa WordPress plugin '.cincopa_plugin_ver().': http://www.cincopa.com/wpplugin/wordpress-plugin.aspx -->

<div id="_cp_widget_'.$uni.'"><img src="http://www.cincopa.com/wpplugin/runtime/loading.gif" style="border:0;" alt="Powered by Cincopa WordPress plugin" /></div>

<script type="text/javascript">
// PLEASE CHANGE DEFAULT EXCERPT HANDLING TO CLEAN OR FULL (go to your Wordpress Dashboard/Settings/Cincopa Options ...

var cpo = [];
cpo["_object"] ="_cp_widget_'.$uni.'";
cpo["_fid"] = "'.urlencode($match[0]).'";

var _cpmp = _cpmp || [];
_cpmp.push(cpo);

(function() {
	var cp = document.createElement("script"); cp.type = "text/javascript";
	cp.async = true; cp.src = "http://www.cincopa.com/wpplugin/runtime/libasync.js";
	var c = document.getElementsByTagName("script")[0];
	c.parentNode.insertBefore(cp, c);
})();

</script>

';

	$ret .= '<noscript>Click <a href="http://www.cincopa.com/wpplugin/view.aspx?fid='.urlencode($match[0]).'">here</a> to open the gallery.<br>';
	$ret .= 'Powered by Cincopa <a href="http://www.cincopa.com/wpplugin/wordpress-plugin.aspx">wp content plugins</a> solution for your website and Cincopa MediaSend for <a href="http://www.cincopa.com/mediasend/start.aspx">file transfer</a>.';
	$ret .= '</noscript>';

	return $ret;
}

function cincopa_plugin($content)
{
	return (preg_replace_callback(CINCOPA_REGEXP, 'cincopa_plugin_callback', $content));
}

function cincopa_plugin_rss($content)
{
//	return (preg_replace_callback(CINCOPA_REGEXP, 'cincopa_plugin_callback', $content));
	return (preg_replace(CINCOPA_REGEXP, '', $content));
}

//add_shortcode('cincopa', 'cincopa_plugin_shortcode');
add_filter('the_content', 'cincopa_plugin');
add_filter('the_content_rss', 'cincopa_plugin_rss');
add_filter('the_excerpt_rss', 'cincopa_plugin_rss');
add_filter('comment_text', 'cincopa_plugin'); 

/////////////////////////////////
// dashboard widget
//////////////////////////////////
function cincopa_dashboard()
{
	if(function_exists('wp_add_dashboard_widget'))
		wp_add_dashboard_widget('cincopa', 'Cincopa', 'cincopa_dashboard_content');
}

function cincopa_dashboard_content()
{

	echo "<iframe src='http://www.cincopa.com/wpplugin/wordpress-dashboard-content.aspx?ver=".cincopa_plugin_ver().cincopa_afc()."&src=".urlencode(cincopa_selfURL())."' width='100%' height='370px' scrolling='no'></iframe>";

}


add_action('wp_dashboard_setup', 'cincopa_dashboard'); 





// Hook for adding admin menus
// http://codex.wordpress.org/Adding_Administration_Menus
add_action('admin_menu', 'mt_cincopa_add_pages');

// action function for above hook
function mt_cincopa_add_pages() {
	// Add a new submenu under Options:
	
	add_options_page('Cincopa Options', 'Cincopa Options', 8, 'cincopaoptions', 'mt_cincopa_options_page');

    // Add a new submenu under Manage:
//	add_management_page('Test Manage', 'Test Manage', 8, 'testmanage', 'mt_manage_page');

	if(function_exists('add_menu_page'))
	{
		// Add a new top-level menu (ill-advised):
		add_menu_page('Cincopa', 'Cincopa', 8, __FILE__, 'mt_cincopa_toplevel_page');

		// kill the first menu item that is usually the the identical to the menu itself
		add_submenu_page(__FILE__, '', '', 8, __FILE__);

		add_submenu_page(__FILE__, 'Monitor Galleries', 'Monitor Galleries', 8, 'sub-page', 'mt_cincopa_sublevel_monitor');

		add_submenu_page(__FILE__, 'Create Gallery', 'Create Gallery', 8, 'sub-page2', 'mt_cincopa_sublevel_create');

		add_submenu_page(__FILE__, 'My Account', 'My Account', 8, 'sub-page3', 'mt_cincopa_sublevel_myaccount');

		add_submenu_page(__FILE__, 'Support Forum', 'Support Forum', 8, 'sub-page4', 'mt_cincopa_sublevel_forum');
	}
}

function cp_isAdmin()
{
	return !function_exists('is_site_admin') || is_site_admin() == true;
}

function mt_cincopa_options_page() {

//	if( is_site_admin() == false ) {
//		wp_die( __('You do not have permission to access this page.') );
//	}

	if (strpos($_SERVER['QUERY_STRING'], 'hide_note=welcome_notice'))
	{
		update_site_option('cincopa_welcome_notice', cincopa_plugin_ver());
		echo "<script type=\"text/javascript\">	document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
		exit;
	}

	if (strpos($_SERVER['QUERY_STRING'], 'hide_note=premiumpress_notice'))
	{
		update_site_option('premiumpress_notice', cincopa_plugin_ver());
		echo "<script type=\"text/javascript\">	document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
		exit;
	}

	$cincopa_afc = get_site_option('CincopaAFC');
	$cincopa_excerpt = get_site_option('CincopaExcerpt');
	$cincopa_footer = get_site_option('CincopaFooter');

	if ( isset($_POST['submit']) )
	{
		if (cp_isAdmin())
		{
			if (isset($_POST['cincopaafc']))
			{
				$cincopa_afc = $_POST['cincopaafc'];
				update_site_option('CincopaAFC', $cincopa_afc);
			}

			if (isset($_POST['footerRel']))
			{
				$cincopa_footer = $_POST['footerRel'];
				update_site_option('CincopaFooter', $cincopa_footer);			
			}
		}

		if (isset($_POST['embedRel']))
		{
			$cincopa_excerpt = $_POST['embedRel'];
			update_site_option('cincopaexcerpt', $cincopa_excerpt);			
		}
		
		echo "<div id=\"updatemessage\" class=\"updated fade\"><p>Cincopa settings updated.</p></div>\n";
		echo "<script type=\"text/javascript\">setTimeout(function(){jQuery('#updatemessage').hide('slow');}, 3000);</script>";	
	}

	$disp_excerpt2 = $cincopa_excerpt == 'clean' ? 'checked="checked"' : '';
	$disp_excerpt3 = $cincopa_excerpt == 'full' ? 'checked="checked"' : '';
	$disp_excerpt1 = $cincopa_excerpt == '' || $cincopa_excerpt == 'nothing' ? 'checked="checked"' : '';

	$disp_footer2 = $cincopa_footer == 'hide' ? 'checked="checked"' : '';
	$disp_footer1 = $cincopa_footer == '' || $cincopa_footer == 'show' ? 'checked="checked"' : '';


?>
	<div class="wrap">
		<h2>Cincopa Configuration</h2>
		<div class="postbox-container">
			<div class="metabox-holder">
				<div class="meta-box-sortables">
					<form action="" method="post" id="cincopa-conf">
						<div id="cincopa_settings" class="postbox">
							<div class="handlediv" title="Click to toggle">
								<br />
							</div>
							<h3 class="hndle">
								<span>Cincopa Settings</span>
							</h3>
							<div class="inside" style="width:600px;">
								<table class="form-table">

									<tr style="width:100%;">
										<th valign="top" scrope="row">
											<label for="cincopaafc">
												Excerpt Handling(<a target="_blank" href="http://support.cincopa.com/index.php?title=Cincopa_Multimedia_Platform/Excerpt_Handling">what?</a>):
											</label>
										</th>
										<td valign="top">

											<input type="radio" <?php echo $disp_excerpt1; ?> id="embedCustomization0" name="embedRel" value="nothing"/>
											<label for="embedCustomization0">Do nothing (default Wordpress behavior)</label>
											<br/>
											<input type="radio" <?php echo $disp_excerpt2; ?> id="embedCustomization1" name="embedRel" value="clean"/>
											<label for="embedCustomization1">Clean excerpt (do not show gallery)</label>
											<br/>
											<input type="radio" <?php echo $disp_excerpt3; ?> id="embedCustomization2" name="embedRel" value="full"/>
											<label for="embedCustomization2">Full excerpt (show gallery)</label>
											<br/>

										</td>
									</tr>


									<?php

if (cp_isAdmin())
{
?>

									<tr style="width:100%;">
										<th valign="top" scrope="row">
											<label for="cincopaafc">
												Support Footer:
											</label>
										</th>
										<td valign="top">

											<input type="radio" <?php echo $disp_footer1; ?> id="footerCustomization0" name="footerRel" value="show"/>
											<label for="footerCustomization0">Show</label>
											<br/>
											<input type="radio" <?php echo $disp_footer2; ?> id="footerCustomization1" name="footerRel" value="hide"/>
												<label for="footerCustomization1">Hide</label>
											<br/>

										</td>
									</tr>

									<tr style="width:100%;">
										<th valign="top" scrope="row">
											<label for="cincopaafc">
												Cincopa AFC(<a target="_blank" href="http://support.cincopa.com/index.php?title=Cincopa_Multimedia_Platform/Wordpress_AFC">what?</a>):
											</label>
										</th>
										<td valign="top">
											<input id="cincopaafc" name="cincopaafc" type="text" size="20" value=""<?php echo $cincopa_afc; ?>"/>
										</td>
									</tr>


									<?php
}

?>

								</table>
							</div>
						</div>
						<div class="submit">
							<input type="submit" class="button-primary" name="submit" value="Update &raquo;" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php
    
    
}
/*
// mt_manage_page() displays the page content for the Test Manage submenu
function mt_manage_page() {
    echo "<h2>Test Manage</h2>";
}
*/

function mt_cincopa_toplevel_page() {
    echo "<iframe src='http://www.cincopa.com/wpplugin/start.aspx?ver=".cincopa_plugin_ver().cincopa_afc()."&src=".urlencode(cincopa_selfURL())."' width='98%' height='2000px'></iframe>";
}

function mt_cincopa_sublevel_create() {
    echo "<iframe src='http://www.cincopa.com/wpplugin/wizard_name.aspx?ver=".cincopa_plugin_ver().cincopa_afc()."&src=".urlencode(cincopa_selfURL())."' width='98%' height='2000px'></iframe>";
}

function mt_cincopa_sublevel_monitor() {
    echo "<iframe src='http://www.cincopa.com/wpplugin/wizard_edit.aspx?ver=".cincopa_plugin_ver().cincopa_afc()."&src=".urlencode(cincopa_selfURL())."' width='98%' height='2000px'></iframe>";
}

function mt_cincopa_sublevel_myaccount() {
    echo "<iframe src='http://www.cincopa.com/cincopaManager/ManageAccount.aspx?ver=".cincopa_plugin_ver().cincopa_afc()."&src=".urlencode(cincopa_selfURL())."' width='98%' height='2000px'></iframe>";
}

function mt_cincopa_sublevel_forum() {
    echo "<iframe src='http://support.cincopa.com/index.php?title=Cincopa_Multimedia_Platform' width='98%' height='2000px'></iframe>";
}




/**
 * CincopaWidget Class
 */
class CincopaWidget extends WP_Widget {
    /** constructor */
    function CincopaWidget() {
        parent::WP_Widget(false, $name = 'Cincopa Gallery Widget');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );

				if (strpos($instance['galleryid'], 'cincopa'))
					$gallery = cincopa_plugin($instance['galleryid']);
				else
					$gallery = cincopa_plugin('[cincopa '.$instance['galleryid'].']');

        echo $gallery;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $galleryid = esc_attr($instance['galleryid']);
        ?>
<p>
	<label for=""
		<?php echo $this->get_field_id('galleryid'); ?>"><?php _e('Gallery ID:'); ?> <a target="_blank" href="http://support.cincopa.com/index.php?title=Cincopa_Multimedia_Platform/How_To#How_do_I_add_a_gallery_to_my_Wordpress_sidebar_.3F">what?</a> <input class="widefat" id=""<?php echo $this->get_field_id('galleryid'); ?>" name="<?php echo $this->get_field_name('galleryid'); ?>" type="text" value="<?php echo $galleryid; ?>" />
	</label>
</p>
<?php 
    }

} // class CincopaWidget

// register CincopaWidget widget
add_action('widgets_init', create_function('', 'return register_widget("CincopaWidget");'));




// http://www.aaronrussell.co.uk/blog/improving-wordpress-the_excerpt/

function cp_improved_trim_excerpt($text) {
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');

		$cincopa_excerpt_rt = get_site_option('CincopaExcerpt');
//		echo "<!-- cincopa_excerpt_rt = '".$cincopa_excerpt_rt."' -->";

		if ($cincopa_excerpt_rt == 'clean')
			$text = preg_replace(CINCOPA_REGEXP, '', $text);

		$text = apply_filters('the_content', $text);

		if ($cincopa_excerpt_rt == 'full')
			return $text;

		$text = str_replace(']]>', ']]&gt;', $text);
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);

		$text = strip_tags($text, '<p>');
		$excerpt_length = 80;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

$cincopa_excerpt_rt = get_site_option('CincopaExcerpt');
if ($cincopa_excerpt_rt == 'full' || $cincopa_excerpt_rt == 'clean')
{
	remove_filter('get_the_excerpt', 'wp_trim_excerpt');
//	remove_all_filters('get_the_excerpt');
	add_filter('get_the_excerpt', 'cp_improved_trim_excerpt');
}

//add_action ( 'wp_footer', 'the_cincopa_footer' );

function the_cincopa_footer()
{
	$cincopa_footer_rt = get_site_option('CincopaFooter');
	if ($cincopa_footer_rt == 'show')
		echo "<p style='font-size:x-small;'>";
	else
		echo "<p style='display:none;'>";
		
	echo "Videos, Slideshows and Podcasts by Cincopa <a href='http://www.cincopa.com/wpplugin/wordpress-plugin.aspx'>Wordpress Plugin</a></p>";
}


add_action ( 'bp_get_activity_content_body', 'the_cincopa_bp_entry' );
add_action ( 'bp_get_the_topic_post_content', 'the_cincopa_bp_entry' );

function the_cincopa_bp_entry($content)
{
	return (preg_replace_callback(CINCOPA_REGEXP, 'cincopa_plugin_callback', $content));
}

function notifyGA()
{
	$var_utmac='UA-5185695-1'; //enter the new urchin code
	$var_utmhn='www.cincopa.com'; //enter your domain
	$var_utmn=rand(1000000000,9999999999);//random request number
	$var_cookie=rand(10000000,99999999);//random cookie number
	$var_random=rand(1000000000,2147483647); //number under 2147483647
	$var_today=time(); //today
	$var_referer=$_SERVER['REQUEST_URI']; //referer url (HTTP_REFERER)

	$var_uservar='-'; //enter your own user defined variable
	$var_utmp='/wpplugin/plugin-install-'.cincopa_plugin_ver();

	$urchinUrl='http://www.google-analytics.com/__utm.gif?utmwv=1&utmn='.$var_utmn.'&utmsr=-&utmsc=-&utmul=-&utmje=0&utmfl=-&utmdt=-&utmhn='.$var_utmhn.'&utmr='.$var_referer.'&utmp='.$var_utmp.'&utmac='.$var_utmac.'&utmcc=__utma%3D'.$var_cookie.'.'.$var_random.'.'.$var_today.'.'.$var_today.'.'.$var_today.'.2%3B%2B__utmb%3D'.$var_cookie.'%3B%2B__utmc%3D'.$var_cookie.'%3B%2B__utmz%3D'.$var_cookie.'.'.$var_today.'.2.2.utmccn%3D(direct)%7Cutmcsr%3D(direct)%7Cutmcmd%3D(none)%3B%2B__utmv%3D'.$var_cookie.'.'.$var_uservar.'%3B';

	echo "<img src='$urchinUrl' width=0 height=0 />";

//	$handle = fopen ($urchinUrl, "r");
//	$test = fgets($handle);
//	fclose($handle);
}



function cp_activation_notice()
{ ?>
			<div id="message" class="updated fade">
				<p style="line-height: 150%">
					<strong>Welcome to Cincopa Rich Media Plugin</strong> - the most popular way to add videos, photo galleries, slideshows, Cooliris gallery, podcast and music to your site.
				</p>
				<p>
					On every post page (above the text box) you'll find this <img src="<?php echo cincopa_pluginURI() ?>/media-cincopa.gif"  /> icon, click on it to start or use sidebar Widgets (Appearance menu).
				</p>
				<p>
		
<input type="button" class="button" value="Cincopa Options Page" onclick="document.location.href = 'options-general.php?page=cincopaoptions';" />

<input type="button" class="button" value="Hide this message" onclick="document.location.href = 'options-general.php?page=cincopaoptions&amp;hide_note=welcome_notice';" />
				</p>

			</div>


			<?php

	if (get_site_option('cincopa_installed') != 'true')
	{
		update_site_option('cincopa_installed', 'true');
		notifyGA();	
	}
}


function cp_premiumpress_notice()
{ ?>
			<div id="message" class="updated fade">
				<p>
					All our products are integrated with Premium Press business themes, <a target="_blank" href="http://www.premiumpress.com/?piwik_campaign=WordpressPlugin&piwik_kwd=cincopa">click here for details</a>.
				</p>
				<p>

					<input type="button" class="button" value="View premiumpress themes" onclick="window.open('http://www.premiumpress.com/?piwik_campaign=WordpressPlugin&piwik_kwd=cincopa');" />

					<input type="button" class="button" value="Hide this message" onclick="document.location.href = 'options-general.php?page=cincopaoptions&amp;hide_note=premiumpress_notice';" />
				</p>

			</div>



			<?php

}


if (get_site_option('cincopa_welcome_notice') != cincopa_plugin_ver())
	add_action( 'admin_notices', 'cp_activation_notice' );

if (get_site_option('premiumpress_notice') != cincopa_plugin_ver())
	add_action( 'admin_notices', 'cp_premiumpress_notice' );



?>