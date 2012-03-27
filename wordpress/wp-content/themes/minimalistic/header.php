<?php
/* WebSite Header */
GLOBAL $shortname;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php bloginfo('name'); ?><?php wp_title(' | ', true, 'left'); ?></title>
	<link rel="shortcut icon" href="http://colgancommodities.com/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link href="<?php bloginfo('template_url'); ?>/css/reset.css" type="text/css" rel="stylesheet" media="screen" />
	<link href="<?php bloginfo('template_url'); ?>/style.css" type="text/css" rel="stylesheet" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/<?php echo get_wp_options('_theme_style', 'Orange.css', true); ?>" type="text/css" media="all" />
	<link href="<?php bloginfo('template_url'); ?>/css/jqueryslidemenu.css" type="text/css" rel="stylesheet" />
	<script src="<?php bloginfo('template_url'); ?>/js/jquery.js" type="text/javascript"></script>	
	<script id="jqueryslidemenu" src="<?php bloginfo('template_url'); ?>/js/jqueryslidemenu.js?imageurl=<?php bloginfo('template_url'); ?>" type="text/javascript"></script>	
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/slider/js/swfobject/swfobject.js"></script>	
	<script src="<?php bloginfo('template_url'); ?>/js/tabs.js" type="text/javascript"></script>

	<script src="<?php bloginfo('template_directory'); ?>/js/prettyphoto/js/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/js/prettyphoto/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
	<script src="<?php bloginfo('template_directory'); ?>/js/prettyphoto/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>

	<?
	$gallery_page = get_post_meta($post->ID, '_wp_page_template', true);
	if (($gallery_page == 'template-gallery.php') or ($gallery_page == 'template-portfolio.php'))
	{
	?>
		<link href="<?php bloginfo('template_url'); ?>/css/jquery.lightbox-0.5.css" type="text/css" rel="stylesheet" />
		<script id="jqueryboxshow" src="<?php bloginfo('template_url'); ?>/js/jquery.lightbox-0.5.js?imageurl=<?php bloginfo('template_url'); ?>" type="text/javascript"></script>
		<script type="text/javascript">
		<?
			if ($gallery_page == 'template-gallery.php') {
		?>
			/*$(function() {
				$('.gallery a').lightBox();
			});*/
		<?
		} 			
		if ($gallery_page == 'template-portfolio.php') {
		?>
			/*$(function() {
					$('.portfolio a.thumb').lightBox();
			});*/
		<?
		}
		?>			
		</script>
	<?
	}
	$portfolio_page = get_post_meta($post->ID, '_wp_page_template', true);
	if ($portfolio_page == 'template-portfolio.php')
	{
	?>
		<link href="<?php bloginfo('template_url'); ?>/css/jquery.lightbox-0.5.css" type="text/css" rel="stylesheet" />
		<script src="<?php bloginfo('template_url'); ?>/js/jquery.lightbox-0.5.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function() {
				//$('.portfolio a.thumb').lightBox();
			});
		</script>
	<?
	}
	?>
</head>
<?
$blog_page = get_option($shortname.'_display_blog_content');
$news_page = get_option($shortname.'_display_news_content');
$testimonials_page = get_option($shortname.'_display_testimonials_content');
$clients_page = get_option($shortname.'_display_clients_content');

GLOBAL $blogpage;
GLOBAL $newspage;
GLOBAL $testimonialpage;
GLOBAL $clientpage;
GLOBAL $simple_page;

$simple_page = true;
if ($post->ID == $news_page) {
	$newspage = true;
	$simple_page = false;
}
if ($post->ID == $blog_page) {
	$blogpage = true;
	$simple_page = false;
}
if ($post->ID == $testimonials_page) {
	$testimonialpage = true;
	$simple_page = false;
}
if ($post->ID == $clients_page) {
	$clientpage = true;
	$simple_page = false;
}

if ($blogpage) {
	echo '<body id="blog">';
}

if ($newspage) {
	echo '<body id="news">';
}

if ($testimonialpage) {
	echo '<body id="testimonial">';
}

if ($clientpage) {
	echo '<body id="clients">';
}

if (is_home() || is_front_page()){
	$simple_page = false;
	echo '<body id="home">';
}

$gallery_page = get_post_meta($post->ID, '_wp_page_template', true);
if ($gallery_page == 'template-gallery.php') {
	echo '<body id="gallery">';
}

$portfolio_page = get_post_meta($post->ID, '_wp_page_template', true);
if ($portfolio_page == 'template-portfolio.php') {
	echo '<body id="portfolio">';
}

$portfolio_page = get_post_meta($post->ID, '_wp_page_template', true);
if ($portfolio_page == 'template-portfolio.php') {
	echo '<body id="portfolio">';
}				

$services_page = get_post_meta($post->ID, '_wp_page_template', true);
if ($services_page == 'template-services.php') {
	echo '<body id="services">';
}		

if ($contact_page == 'template-contact.php') {
	echo '<body id="contact">';
}		

if (($simple_page == true) and ($gallery_page != 'template-gallery.php') and ($portfolio_page != 'template-portfolio.php') 
		and ($services_page != 'template-services.php') and ($contact_page != 'template-contact.php')) {
	echo '<body id="about">';
}

//get style folder for images
GLOBAL $images_path;
$images_path = 'css/'.str_replace('.css','',get_wp_options('_theme_style', 'Orange', true));
?>
<!--BEGIN: page -->
<div id="page">
  <!--BEGIN: wrap -->
  <div id="wrap">
    <!--BEGIN: header -->
    <div id="header">
	<?
		//get image height and set style for height
		$get_logo_url = get_wp_options('_logo', get_bloginfo('template_url') .'/'.$images_path.'/logo.png');
		list($width, $height, $type, $attr) = getimagesize($get_logo_url);
	?>
      <h1 id="logo"><a href="<? echo bloginfo('url'); ?>" style="height:<? echo $height; ?>px;background:url(<?php $logo_url = get_wp_options('_logo', get_bloginfo('template_url') .'/'.$images_path.'/logo.png', true); 
			echo $logo_url; ?>) no-repeat;"><?php bloginfo('name'); ?></a></h1>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Header Search Form") ) : ?>				
				<!-- "Header Search Form" WIDGET-->
		<?php endif; ?>			
			<!--form role="search" method="get" id="search" action="<?php bloginfo('home'); ?>">
      	<p><input type="text" id="s" name="s" value="Search" onblur="if (this.value == ''){this.value = 'Search'; }" onfocus="if (this.value == 'Search') {this.value = ''; }"  />&nbsp;<input  id="searchsubmit" type="image" class="go" src="<?php bloginfo('template_url'); ?>/<? echo $images_path; ?>/magnify.gif" /></p>
      </form-->
	  <?	  
		//Exclude a parent and all of that parent's child Pages
		$page_exclusions = get_wp_options('_exclude_header_pages', '');
	  ?>
<div id="slideMenuWrapper">	  	  
      <div id="myslidemenu" class="jqueryslidemenu">
		<ul>
			
				<?php 
					//wp_list_cats('');
					wp_list_pages("sort_column=menu_order&exclude=$page_exclusions&title_li=");
				?>
				
		</ul>
		<div id="loginContact">
		<p><span><a target="_blank" href="https://members.admis.com/AccountLogin.aspx?ReturnUrl=%2fdefault.aspx">Login</a></span></p> | <p><span><a href="../contact-us/">Contact</a></span></p>
		</div>
        <br style="clear: left" />
      </div>
      </div><!--close slideMenuWrapper-->	  
    </div>
	<!--END: header -->
