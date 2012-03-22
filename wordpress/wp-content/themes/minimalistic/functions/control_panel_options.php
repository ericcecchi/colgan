<?
$themename = "Minimalistic";
$shortname = "mini";

/* Get CSS files into a array list */
$css_styles = array();
if(is_dir(TEMPLATEPATH . "/css/")) 
{
	if($open_dirs = opendir(TEMPLATEPATH . "/css/")) 
	{
		while(($style = readdir($open_dirs)) !== false) 
		{
			if(stristr($style, "style") !== false) 
			{
				if(stristr($style, ".css") !== false) 
				{
					$css_styles[] = $style;
				}
			}
		}
	}
}
$css_styles_list = $css_styles;

/* Get Pages into a drop-down list */
$pages = get_pages();
$pagetomenu = array();
foreach($pages as $apag) 
{
	$pagetomenu[$page->ID] = $page->post_title;
}

/* Tween Types array for slider */
$TweenTypes = array("easeInOutBack","easeInSine","easeOutSine", "easeInOutSine", "easeInCubic", "easeOutCubic", "easeInOutCubic", "easeOutInCubic", "easeInQuint", "easeOutQuint", "easeInOutQuint", "easeOutInQuint", "easeInCirc", "easeOutCirc", "easeInOutCirc", "easeOutInCirc", "easeInBack", "easeOutBack", "easeInOutBack", "easeOutInBack", "easeInQuad", "easeOutQuad", "easeInOutQuad", "easeOutInQuad", "easeInQuart", "easeOutQuart", "easeInOutQuart", "easeOutInQuart", "easeInExpo", "easeOutExpo", "easeInOutExpo", "easeOutInExpo", "easeInElastic", "easeOutElastic", "easeInOutElastic", "easeOutInElastic", "easeInBounce", "easeOutBounce", "easeInOutBounce", "easeOutInBounce","linear");

/* Control Panel options */
$options = array (
		
	/* General Theme Options */
array( "name" => "General Theme Options",
"type" => "toggle"),
	
	array(	"name" => "General Theme Options",
			"type" => "title-"),
			

array( "type" => "open"),
	
	array(	"name" => "Theme Stylesheet",
			"desc" => "Please choose one of the MINIMALISTIC skins here.",
			"id" => $shortname."_theme_style",
			"type" => "select",
			"std" => "grey.css",
			"options" => $css_styles),
			
	array(	"name" => "Logo",
			"desc" => "Paste the full URL path to your logo e.g. 'http://www.yourdomain.com/images/logo.jpg'.<br>
					If the input field is left blank then the themes default logo gets applied. Logo Dimension: 517 x 36 px.",
            "id" => $shortname."_logo",
            "type" => "text"),
		
	array(	"name" => "Google Analytics",
			"desc" => "Enter Google Analytics account ID. Ex: UA-XXXXXXX-X",
			"id" => $shortname."_analytics_code",
			"type" => "text"),

	array(	"name" => "Footer (Copyright)",
			"desc" => "Enter in the company that is copyrighting site content. This will show up in the footer. Is possible to insert html tags.",
			"id" => $shortname."_footer_copyright",
			"type" => "textarea",
			"std" => 'Copyright 2009-2010. All Rights Reserved.'),
			
	array(    "type" => "close"),
	

	/* Homepage Options */
array( "name" => "Homepage Options",
"type" => "toggle"),
	
	
	array(	"name" => "Homepage Options",
			"type" => "title-"),

	array(	"type" => "open"),		
			
	array(	"name" => "3 Homepage Columns",
			"desc" => "Populate 3 columns at the front page. Choose from Categories, Posts or Pages.<br/> If you leave HTML option then you need to edit file index.php",
			"type" => "text2"),	
		
	array(  "name" => "Select Column",
			"desc" => "Select Category, Page or Post. Leave it as HTML (need to edit index.php)",
            "id" => $shortname."_homepage_columns",
            "type" => "homepagecolumns"),
			
	array(	"type" => "close"),
	
	/* General Contact Form Info */
array( "name" => "Contact Form Options",
"type" => "toggle"),
	
	
	array(	"name" => "Contact Form Options",
			"type" => "title-"),
			
	array(	"type" => "open"),
	
	array(	"name" => "Your email",
			"desc" => "(required) Enter your email for contact form.",
			"id" => $shortname."_contact_admin_email",
			"type" => "text"),

	array(	"name" => "Contact info image",
			"desc" => "(optional) Enter URL for your contact info image. Max width 250px.",
			"id" => $shortname."_contact_info_image",
			"type" => "text"),
	
	array(	"name" => "Your address",
			"desc" => "(optional) Enter your address.",
			"id" => $shortname."_contact_address_info",
			"type" => "text"),			

	array(	"name" => "Your telephone number",
			"desc" => "(optional) Enter your telephone number.",
			"id" => $shortname."_contact_telephone_number",
			"type" => "text"),			

	array(	"name" => "Your fax number",
			"desc" => "(optional) Enter your fax number.",
			"id" => $shortname."_contact_fax_number",
			"type" => "text"),			
	
	array(	"name" => "Your email",
			"desc" => "(optional) Enter your email, may be different than the contact form.",
			"id" => $shortname."_contact_email_info",
			"type" => "text"),				
			
	array(	"name" => "Your Location",
			"desc" => "(optional) Enter Your Location. Is possible to insert html tags.",
			"id" => $shortname."_contact_location_info",
			"type" => "textarea",
			"std" => ''),
			
	array(    "type" => "close"),
	

	/* Categories Settings / Pages Templates */
	array( "name" => "Categories Settings / Pages Templates",
"type" => "toggle"),
	

	array(	"name" => "Categories Settings / Pages Templates",
			"type" => "title-"),
			
	array(	"type" => "open"),

	array(	"name" => "Exclude Pages",
			"id" => $shortname."_exclude_header_pages",
			"std" => "",
			"desc" => "Exclude pages from header menu.",
            "type" => "exclude_header_pages",
			"options" => $pagetomenu),

	array(	"name" => "Exclude Blog Categories",
			"id" => $shortname."_exclude_categories",
			"std" => "",
			"desc" => "Exclude categories from Blog. Selected Categories will not be show in Blog categories and in Blog posts.",
            "type" => "exclude_categories",
			"options" => $cats_menu),
			
	array(  "name" => "Select Page for Blog",
			"desc" => "Blog Template. Choose page that will display the Blog content.",
            "id" => $shortname."_display_blog_content",
            "type" => "displayblogcontent"),			

	array(  "name" => "Select Page for News",
			"desc" => "News Template. Choose page that will display the News content.",
            "id" => $shortname."_display_news_content",
            "type" => "displaynewscontent"),			

	array(  "name" => "Select Testimonial Category",
			"desc" => "Testimonials Template. Choose category that will display the testimonials posts.",
            "id" => $shortname."_display_testimonials_content",
            "type" => "displaytestimonials"),			

	array(  "name" => "Select Clients Category",
			"desc" => "Clients Template. Choose category that will display the clients posts.",
            "id" => $shortname."_display_clients_content",
            "type" => "displayclients"),			
			
	array(  "name" => "Select Category for Gallery",
			"desc" => "Gallery Template. Choose category that will display the Gallery images.",
            "id" => $shortname."_display_gallery_content",
            "type" => "displaygallerycontent"),			
			
	array(  "name" => "Select Category for Porftolio",
			"desc" => "Portfolio Template. Choose category that will display the Portfolio images.",
            "id" => $shortname."_display_portfolio_content",
            "type" => "displayportfoliocontent"),			

	array(  "name" => "Select Category for Services",
			"desc" => "Services Template. Choose category that will display the Services pages.",
            "id" => $shortname."_display_services_content",
            "type" => "displayservicescontent"),			

	array(  "name" => "Select Page for Nav",
			"desc" => "Nav Menu in sidebar. Choose page that will display subPages in Nav menu in sidebar.",
            "id" => $shortname."_display_nav_content",
            "type" => "displaynavmenu"),
			
	array(    "type" => "close"),

	
	/* Follow Us Options */	
array( "name" => "Follow Us Options",
"type" => "toggle"),
	
	
	array(	"name" => "Follow Us Options",
			"type" => "title-"),

	array(	"type" => "open"),

	array(	"name" => "Display Facebook?",
			"desc" => "Yes",
			"desc2" => "No",
			"value" => "Yes",
			"value2" => "No",
			"id" => $shortname."_show_facebook_account",
			"type" => "radio",
			'selector' => true),
			
	array(	"name" => "Facebook URL",
			"desc" => "Enter url of your Facebook account.",
			"id" => $shortname."_facebook_account",
			"type" => "text"),	
			
	array(	"name" => "Display Twitter?",
			"desc" => "Yes",
			"desc2" => "No",
			"value" => "Yes",
			"value2" => "No",
			"id" => $shortname."_show_twitter_page",
			"type" => "radio",
			'selector' => true),
			
	array(	"name" => "Twitter URL",
			"desc" => "Enter url of your twitter page.",
			"id" => $shortname."_twitter_page",
			"type" => "text"),

	array(	"name" => "Display Linkedin?",
			"desc" => "Yes",
			"desc2" => "No",
			"value" => "Yes",
			"value2" => "No",
			"id" => $shortname."_show_linkedin_account",
			"type" => "radio",
			'selector' => true),
			
	array(	"name" => "Linkedin URL",
			"desc" => "Enter url of your Linkedin account.",
			"id" => $shortname."_linkedin_account",
			"type" => "text"),		

	array(	"name" => "Display Delicious?",
			"desc" => "Yes",
			"desc2" => "No",
			"value" => "Yes",
			"value2" => "No",
			"id" => $shortname."_show_delicious_account",
			"type" => "radio",
			'selector' => true),
			
	array(	"name" => "Delicious URL",
			"desc" => "Enter url of your Delicious account.",
			"id" => $shortname."_delicious_account",
			"type" => "text"),		
			
	array(	"name" => "Display StumbleUpon?",
			"desc" => "Yes",
			"desc2" => "No",
			"value" => "Yes",
			"value2" => "No",
			"id" => $shortname."_show_stumbleupon_account",
			"type" => "radio",
			'selector' => true),
			
	array(	"name" => "StumbleUpon URL",
			"desc" => "Enter url of your StumbleUpon account.",
			"id" => $shortname."_stumbleupon_account",
			"type" => "text"),	

	array(	"name" => "Display Vimeo?",
			"desc" => "Yes",
			"desc2" => "No",
			"value" => "Yes",
			"value2" => "No",
			"id" => $shortname."_show_vimeo_account",
			"type" => "radio",
			'selector' => true),
			
	array(	"name" => "Vimeo URL",
			"desc" => "Enter url of your Vimeo account.",
			"id" => $shortname."_vimeo_account",
			"type" => "text"),	


	array(	"name" => "Display DeviantArt?",
			"desc" => "Yes",
			"desc2" => "No",
			"value" => "Yes",
			"value2" => "No",
			"id" => $shortname."_show_deviantart_account",
			"type" => "radio",
			'selector' => true),
			
	array(	"name" => "DeviantArt URL",
			"desc" => "Enter url of your DeviantArt account.",
			"id" => $shortname."_deviantart_account",
			"type" => "text"),	
			
	array(	"type" => "close"),
	
	
	/* Slider Options */
array( "name" => "Slider Options",
"type" => "toggle"),
	
	
	array(	"name" => "Slider Options",
			"type" => "title-"),		
	
	array(	"type" => "open"),		

	array(	"name" => "Add, Edit, Remove Images",
			"desc" => "Control images from slider. Add, Edit, Remove and reorder images.",	
			"type" => "slider_control_panel"),	
			
	array(	"name" => "Add, Edit, Remove Images",
			"desc" => "<!--u>Add</u> - click button Add to add new image.<br>
					   <u>Remove</u> - click button Remove to remove images from last to first (one by one).<br>
					   <u>Save</u> - click button Save to save all changes.<br>
					   <strong>Note:</strong> you can edit Image Name, left Image Name empty to delete it, use Up and Down buttons to reorder images.-->
					   To add images, simply drop the images into your '/wp-content/minimalistic/slider/images' folder located in your themes main directory. Make sure to upload these images to your server. And enter only image name, for example: image1.jpg
					   ",	
			"id" => $shortname."_slider_cp",
			"type" => "slider_cp"),	
			
	array(  "name" => "Image Width",
			"desc" => "Image width in pixels.",
			"type" => "dottedline"),
	
	array(  "name" => "Image Width",
			"desc" => "The width of the images to be loaded.",
			"id" => $shortname."_imageWidth",
			"type" => "text",
			"std" => "830"),
			
	array(  "name" => "Image Height",
			"desc" => "The height of the images to be loaded.",
			"id" => $shortname."_imageHeight",
			"type" => "text",
			"std" => "360"),			

	array(  "name" => "Segments",
			"desc" => "Number of segments in which the image will be sliced.",
			"id" => $shortname."_segments",
			"type" => "text",
			"std" => "7"),		

	array(  "name" => "Tween Time",
			"desc" => "Number of seconds for each element to be turned.",
			"id" => $shortname."_tweenTime",
			"type" => "text",
			"std" => "1.4"),

	array(  "name" => "Tween Delay",
			"desc" => "Number of seconds from one element starting to turn to the next element starting.",
			"id" => $shortname."_tweenDelay",
			"type" => "text",
			"std" => "0.1"),

	array( "name" => "Tween Type",
			"desc" => "Type of transition from Caurina's Tweener class.",
			"id" => $shortname."_tweenType",
			"type" => "select_tweenType",
			"options" => $TweenTypes,
			"std" => "Choose a category"),

	array(  "name" => "Z Distance",
			"desc" => "To which extend are the cubes moved on z axis when being tweened. Negative values bring the cube closer to the camera, positive values take it further away. A good range is roughly between -200 and 700.",
			"id" => $shortname."_zDistance",
			"type" => "text",
			"std" => "0"),

	array(  "name" => "Expand",
			"desc" => "To which extend are the cubes moved away from each other when tweening.",
			"id" => $shortname."_expand",
			"type" => "text",
			"std" => "26"),

	array(  "name" => "Inner Color",
			"desc" => "Color of the sides of the elements in hex values (e.g. 0x000000 for black).",
			"id" => $shortname."_innerColor",
			"type" => "text",
			"std" => "0x111111"),

	array(  "name" => "Text Background Color",
			"desc" => "Color of the description text background in hex values (e.g. 0xFF0000 for red).",
			"id" => $shortname."_textBackground",
			"type" => "text",
			"std" => "0x30302E"),

	array(  "name" => "Shadow Darkness",
			"desc" => "To which extend are the sides shadowed, when the elements are tweening and the sided move towards the background. 100 is black, 0 is no darkening.",
			"id" => $shortname."_shadowDarkness",
			"type" => "text",
			"std" => "100"),

	array(  "name" => "Text Distance",
			"desc" => "Distance of the info text to the borders of its background.",
			"id" => $shortname."_textDistance",
			"type" => "text",
			"std" => "25"),

	array(  "name" => "Auto Play",
			"desc" => "Number of seconds to the next image when autoplay is on. Set 0, if you don't want autoplay.",
			"id" => $shortname."_autoplay",
			"type" => "text",
			"std" => "4"),
			
	array(	"type" => "close")	
);	
	
function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {
				
                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) 
				{
                    if( isset( $_REQUEST[ $value['id'] ] ) ) 
					{ 
						update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); 
					} 
					else 
					{ 
						delete_option( $value['id'] ); 
					} 
				}

				/* Updates homepage slider settings */
				$imagesCount = 0;
				foreach ($_POST as $key => $value) 
				{
					if ( preg_match("/slider_cp_url_/", $key) ) 
					{
						if ($value != '') $imagesCount = $imagesCount +1;
					}
				}		
				foreach ($_POST as $key => $value) 
				{
					if ( preg_match("/slider_cp_url_/", $key) ) 
					{	
						if ($value != '') $options_slider_custom[$key] = $value;
					}	
					
					$options_slider_custom['imagesCount'] = $imagesCount;
					
					delete_option( $shortname.'_slider_cp');
					update_option( $shortname.'_slider_cp', $options_slider_custom);					
				}

				/* Updates 3 homepage columns */
				foreach ($_POST as $key => $value) 
				{
					if ( preg_match("/homepage_columns_/", $key) ) 
					{	
						if ($value != '') $homepage_categories_custom[$key] = $value;
					}	
					
					delete_option( $shortname.'_homepage_columns');
					update_option( $shortname.'_homepage_columns', $homepage_categories_custom);					
				}
				
				/* Updates News to category */
				foreach ($_POST as $key => $value) 
				{
					if ( preg_match("/news_content_to_cat/", $key) ) 
					{	
						if ($value != '') $news_content_to_cat = $value;
					}	
					
					delete_option( $shortname.'_display_news_content_to_cat');
					update_option( $shortname.'_display_news_content_to_cat', $news_content_to_cat);					
				}

				/* Updates Testimonials to category */
				foreach ($_POST as $key => $value)
				{
					if ( preg_match("/testimonials_content_to_cat/", $key) ) 
					{	
						if ($value != '') $testimonials_content_to_cat = $value;
					}			
					delete_option( $shortname.'_display_testimonials_content_to_cat');
					update_option( $shortname.'_display_testimonials_content_to_cat', $testimonials_content_to_cat);
				}

				/* Updates Clients to category */
				foreach ($_POST as $key => $value)
				{
					if ( preg_match("/clients_content_to_cat/", $key) ) 
					{	
						if ($value != '') $clients_content_to_cat = $value;
					}			
					delete_option( $shortname.'_display_clients_content_to_cat');
					update_option( $shortname.'_display_clients_content_to_cat', $clients_content_to_cat);
				}

				/* Updates Portfolio to category */
				foreach ($_POST as $key => $value)
				{
					if ( preg_match("/portfolio_content_to_cat/", $key) ) 
					{	
						if ($value != '') $portfolio_content_to_cat = $value;
					}			
					delete_option( $shortname.'_display_portfolio_content_to_cat');
					update_option( $shortname.'_display_portfolio_content_to_cat', $portfolio_content_to_cat);
				}
				
				/* Updates Gallery to category */
				foreach ($_POST as $key => $value)
				{
					if ( preg_match("/gallery_content_to_cat/", $key) ) 
					{	
						if ($value != '') $gallery_content_to_cat = $value;
					}			
					delete_option( $shortname.'_display_gallery_content_to_cat');
					update_option( $shortname.'_display_gallery_content_to_cat', $gallery_content_to_cat);
				}				

				/* Updates Services to category */
				foreach ($_POST as $key => $value)
				{
					if ( preg_match("/services_content_to_cat/", $key) ) 
					{	
						if ($value != '') $services_content_to_cat = $value;
					}			
					delete_option( $shortname.'_display_services_content_to_cat');
					update_option( $shortname.'_display_services_content_to_cat', $services_content_to_cat);
				}
				
                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            header("Location: themes.php?page=functions.php&reset=true");
            die;
        }
    }
    add_menu_page($themename." Options", "".$themename." Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');
}

/* Add homepage columns function */
function add_homepage_columns($column_number,$values_id,$shortname)
{
	$htmlselected = '';
	$get_custom_options = get_option($shortname.'_homepage_columns');

	$m = 0;
	if ($get_custom_options[$shortname.'_homepage_columns_'.$column_number]) {
		$m = $m + 1;
		if ( $column_number == 1 ) { $selected_cat = $get_custom_options[$shortname.'_homepage_columns_'.$column_number]; }
		if ( $column_number == 2 ) { $selected_cat = $get_custom_options[$shortname.'_homepage_columns_'.$column_number]; }
		if ( $column_number == 3 ) { $selected_cat = $get_custom_options[$shortname.'_homepage_columns_'.$column_number]; }

		//check if is category, page or post
		$selected_cat_name = '';
		$pos = strpos($selected_cat,'_Categories');
		if($pos === false) { 
			// string not found
		}
		else {
			// string found
			$selected_cat_name = 'Categories';
		}

		$pos = strpos($selected_cat,'_Pages');
		if($pos === false) {
			// string not found
		}
		else {
			// string found
			$selected_cat_name = 'Pages';
		}		
		
		$pos = strpos($selected_cat,'_Posts');
		if($pos === false) {
			// string not found
		}
		else {
			// string found
			$selected_cat_name = 'Posts';
		}		

		$selected_cat = str_replace('_Categories','',$selected_cat);		
		$selected_cat = str_replace('_Pages','',$selected_cat);
		$selected_cat = str_replace('_Posts','',$selected_cat);		
	}

	// if was not selected category, post or page then will show default items from index.php
	if ( $m == 0 ) {
		$htmlselected = 'selected';
	}
	
	echo '<select name="'.$values_id.'_'.$column_number.'" class="postform selector">';
	echo '<option '.$htmlselected.' value="html">&nbsp;&nbsp;&nbsp;HTML (edit index.php) </option>';
	
	echo '<OPTGROUP LABEL="Categories">';
	$categories = get_categories();			
	foreach ($categories as $cat) 
	{
		$selected_option = $cat->cat_ID;
		if ($selected_cat_name == 'Categories') 
		{
			if ($selected_cat == $selected_option) { 
			?>
				<option selected value='<? echo $cat->cat_ID.'_Categories'; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>			
			<?
			}
			else{
			?>
				<option value='<? echo $cat->cat_ID.'_Categories'; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>
			<?php 
			}	
		}
		else {
		?>
				<option value='<? echo $cat->cat_ID.'_Categories'; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>		
		<?
		}
	};			
	echo '</OPTGROUP>';		
	
	echo '<OPTGROUP LABEL="Pages">';
	global $post;
	$myposts = get_pages();
	foreach($myposts as $post) : setup_postdata($post);
		$selected_option = $post->ID;		
		if ($selected_cat_name == 'Pages') 
		{
			if ( $selected_cat == $selected_option ) { 
			?>
				<option selected value='<?php echo $post->ID.'_Pages'; ?>'>&nbsp;&nbsp;&nbsp;<?php the_title(); ?></option>";
			<?	
			}
			if ( $selected_cat != $selected_option ) { 
			?>
				<option value='<?php  echo $post->ID.'_Pages'; ?>'>&nbsp;&nbsp;&nbsp;<?php the_title(); ?></option>";
			<?php 
			}
		}
		else {
			?>
				<option value='<?php  echo $post->ID.'_Pages'; ?>'>&nbsp;&nbsp;&nbsp;<?php the_title(); ?></option>";
			<?php 		
		}
		
	endforeach;
	echo '</OPTGROUP>';	
	
	echo '<OPTGROUP LABEL="Posts">';
	global $post;
	$myposts = get_posts('numberposts=10000');
	foreach($myposts as $post) : setup_postdata($post);
		$selected_option = $post->ID;		
		
		foreach((get_the_category()) as $category) { 
			$category_name = $category->cat_name;
		} 

		
		if ($selected_cat_name == 'Posts') {
		if ( $selected_cat == $selected_option ) { 
			?>
				<option selected value='<?php  echo $post->ID.'_Posts'; ?>'>&nbsp;&nbsp;&nbsp;<?php  echo $post->post_title.' [Cat &rarr; '.$category_name.']'; ?></option>";
			<?
			}
			else{
			?>
				<option value='<?php  echo $post->ID.'_Posts'; ?>'>&nbsp;&nbsp;&nbsp;<?php  echo $post->post_title.' [Cat &rarr; '.$category_name.']'; ?></option>";
			<?php 
			}
		}
		else {
			?>
				<option value='<?php  echo $post->ID.'_Posts'; ?>'>&nbsp;&nbsp;&nbsp;<?php  echo $post->post_title.' [Cat &rarr; '.$category_name.']'; ?></option>";
			<?php 		
		}
	endforeach;
	echo '</OPTGROUP>';
	
	echo '</select>';
}

function display_blog_content($values_id,$shortname,$blog_or_news)
{
	$get_blog_name = get_option($shortname.'_display_'.$blog_or_news.'_content');
	
	echo 'Page <select name="'.$values_id.'" class="postform selector">';	
	echo '<OPTGROUP LABEL="Pages">';
	echo '<option value="0">Select Page</option>';		
	global $post;
	$myposts = get_pages();
	foreach($myposts as $post) : setup_postdata($post);
		$selected_option = $post->ID;		
		if ( $get_blog_name == $selected_option ) { 
		?>
			<option selected value='<?php echo $post->ID; ?>'>&nbsp;&nbsp;&nbsp;<?php the_title(); ?></option>";
		<?	
		}
		if ( $get_blog_name != $selected_option ) { 
		?>
			<option value='<?php  echo $post->ID; ?>'>&nbsp;&nbsp;&nbsp;<?php the_title(); ?></option>";
		<?php 
		}
	endforeach;
	echo '</OPTGROUP>';	
	echo '</select>';
	
	if ($blog_or_news == 'news'){
		$get_news_name = get_option($shortname.'_display_'.$blog_or_news.'_content_to_cat');
		echo '&nbsp;&nbsp;&nbsp;go to category&nbsp;&nbsp;&nbsp;<select name="'.$values_id.'_to_cat" class="postform selector">';	
		echo '<OPTGROUP LABEL="Categories">';
		echo '<option value="0">Select category</option>';			
		$categories = get_categories();			
		foreach ($categories as $cat) 
		{
			$selected_option = $cat->cat_ID;
			if ($get_news_name == $selected_option) { 
			?>
			
				<option selected value='<? echo $cat->cat_ID; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>			
			<?
			}
			else{
			?>
			
				<option value='<? echo $cat->cat_ID; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>
			<?php 
			}	
		};			
		echo '</OPTGROUP>';	
		echo '</select>';	
	}			

	if ($blog_or_news == 'testimonials'){
		$get_news_name = get_option($shortname.'_display_'.$blog_or_news.'_content_to_cat');
		echo '&nbsp;&nbsp;&nbsp;go to category&nbsp;&nbsp;&nbsp;<select name="'.$values_id.'_to_cat" class="postform selector">';	
		echo '<OPTGROUP LABEL="Categories">';
		echo '<option value="0">Select category</option>';	
		$categories = get_categories();			
		foreach ($categories as $cat) 
		{
			$selected_option = $cat->cat_ID;
			if ($get_news_name == $selected_option) { 
			?>
			
				<option selected value='<? echo $cat->cat_ID; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>			
			<?
			}
			else{
			?>
			
				<option value='<? echo $cat->cat_ID; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>
			<?php 
			}	
		};					
		echo '</OPTGROUP>';	
		echo '</select>';	
	}	

	if ($blog_or_news == 'clients'){
		$get_news_name = get_option($shortname.'_display_'.$blog_or_news.'_content_to_cat');
		echo '&nbsp;&nbsp;&nbsp;go to category&nbsp;&nbsp;&nbsp;<select name="'.$values_id.'_to_cat" class="postform selector">';	
		echo '<OPTGROUP LABEL="Categories">';
		echo '<option value="0">Select category</option>';			
		$categories = get_categories();			
		foreach ($categories as $cat) 
		{
			$selected_option = $cat->cat_ID;
			if ($get_news_name == $selected_option) { 
			?>
			
				<option selected value='<? echo $cat->cat_ID; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>			
			<?
			}
			else{
			?>
			
				<option value='<? echo $cat->cat_ID; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>
			<?php 
			}	
		};					
		echo '</OPTGROUP>';	
		echo '</select>';	
	}	
}

/* Exclude/Include pages from/in header menu */
function exclude_header_pages($values_id,$shortname)
{
	$htmlselected = '';
	$get_custom_options = get_option($shortname.'_exclude_header_pages');
	
	$page_items = explode(',',$get_custom_options);
	$count_pages = count($page_items);
	foreach($page_items as $page_item){
		$page_item_list[] = $page_item;
	}

	$n = 0;
	$n2 = 777;
	global $post;
	$arguments = array(
		'child_of' =>  $n,
		'parent' => $n
	);

	//$pages = get_pages();
	$myposts = get_pages($arguments);
	foreach($myposts as $post) : setup_postdata($post);
		$selected_option = get_permalink($post->ID);

		$checked_page = '';
		for($i=0;$i<$count_pages;$i++)
		{
			if ($page_item_list[$i] == $post->ID) { $checked_page = 'checked="yes"'; }
		}
		$n = $n + 1;
		if ($n == 10) { echo '<br>'; $n = 1;}
		echo '<p style="display:inline; padding: 0 10px 0 3px;">&raquo;<input '.$checked_page.' onClick="getSelectValue_pages('.$post->ID.');" name="'.$post->ID.'" type="checkbox" value="'.the_title().'"/></p>';
	endforeach;	
	
	echo '<p><input type="hidden" readonly="readonly" style="padding-top: 4px; width: 400px;"  name="'.$values_id.'" id="'.$values_id.'" value="'.$get_custom_options.'" type="text"></p>';
}

function display_gallery_portfolio_services($values_id,$shortname,$gallery_or_portfolio)
{
	$get_blog_name = get_option($shortname.'_display_'.$gallery_or_portfolio.'_content');

	$get_gallery_name = get_option($shortname.'_display_'.$gallery_or_portfolio.'_content_to_cat');
	echo '<select name="'.$values_id.'_to_cat" class="postform selector">';	
	echo '<OPTGROUP LABEL="Categories">';
	echo '<option value="0">&nbsp;&nbsp;&nbsp;Select category</option>';	
	$categories = get_categories();			
	foreach ($categories as $cat) 
	{
		$selected_option = $cat->cat_ID;
		if ($get_gallery_name == $selected_option) { 
		?>
		
			<option selected value='<? echo $cat->cat_ID; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>			
		<?
		}
		else{
		?>
		
			<option value='<? echo $cat->cat_ID; ?>'>&nbsp;&nbsp;&nbsp;<?= $cat->cat_name; ?></option>
		<?php 
		}	
	};			
	echo '</OPTGROUP>';	
	echo '</select>';		
}

/* Exclude categories from Blog */
function exclude_categories($values_id,$shortname)
{
	$get_custom_options = get_option($shortname.'_exclude_categories');
	
	$cat_items = explode(',',$get_custom_options);
	$count_cat = count($cat_items);
	foreach($cat_items as $cat_item){
		$cat_item_list[] = $cat_item;
	}

	$categories = get_categories('hide_empty=0');
	foreach($categories as $cat)
	{
		$checked_cat = '';
		for($i=0;$i<$count_cat;$i++)
		{
			if ($cat_item_list[$i] == $cat->cat_ID) { $checked_cat = 'checked="yes"'; }
		}
		$n = $n + 1;
		if ($n == 10) { echo '<br>'; $n = 1;}
		echo $cat->cat_name ; 
		echo '<p style="display:inline; padding: 0 10px 0 3px;">&raquo;<input '.$checked_cat.' onClick="getSelectValue_cats('.$cat->cat_ID.');" name="'.$cat->cat_ID.'" type="checkbox" value="'.$cat->cat_name.'"/></p>';
	};
	
	echo '<p><input type="hidden" readonly="readonly" style="padding-top: 4px; width: 400px;"  name="'.$values_id.'" id="'.$values_id.'" value="'.$get_custom_options.'" type="text"></p>';
}


/* mytheme_admin function */
function mytheme_admin() {
    global $themename, $shortname, $options;
    if ( $_REQUEST['saved'] ) {	echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>'; }
	if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';   
?>
<div class="wrap">

<?php screen_icon('options-general'); ?>
<h2><?php echo $themename; ?> settings</h2>

<form method="post" action="">
<?php foreach ($options as $value) { 
    
	switch ( $value['type'] ) {
	
		case "open":
		?>
        <table width="100%" border="0" style="background-color:#ffffff; padding:10px;border:1px double #f1f1f1;">  
		<?php break;
		
		case "close":
		?>	
		<tr>
			<td coslapan="2">
			<p class="submit">
				<input name="save" type="submit" value="Save changes" />    
				<input type="hidden" name="action" value="save" />
			</p>
			</form>
			</td>
		</tr>
        </table><br />     		
</div>
</div>
		<?php break;
		
		case "title":
		?>
		<table width="100%" border="0" style="background-color:#f1f1f1; padding:5px 10px;"><tr>
        	<td colspan="2"><h3 style="color:#414141"><?php echo $value['name']; ?></h3></td>
        </tr>       
		<?php break;
		
		case "title2":
		?>
		<table width="100%" border="0" style="background-color:#f1f1f1; padding:0px 10px;">
		<tr>
        	<td colspan="2"><h4 style="color:#414141"><?php echo $value['name']; ?></h4></td>
        </tr>               
		<?php break;
		
		case 'text':
		?>      
        <tr>
            <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
        </tr>

        <tr>
            <td><small><?php echo $value['desc']; ?></small></td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;
				
		case "text2":
		?>      
        <tr>
            <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><small><?php echo $value['desc']; ?></small></td>
        </tr>

        <tr>
            <td></td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:0px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>      
		<?php break;
	
		case 'textarea':
		?>      
        <tr>
            <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php 
				if ( get_settings( $value['id'] ) != "") { 
					if (($value['id'] == $shortname.'_footer_copyright') or ($value['id'] == $shortname.'_contact_location_info')) {
						echo trim(str_replace('\t','',str_replace('\\', '', get_settings( $value['id'] ))));
					} else {
						echo get_settings( $value['id'] ); 
					}
				} else { 
					echo $value['std']; 
				} 
			?></textarea></td>
         </tr>
        <tr>
            <td><small><?php echo $value['desc']; ?></small></td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;
				
		case 'select':
		?>
		<tr>
			<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
			<td width="80%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php 
			echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php 
			if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } 
			elseif (get_settings( $value['id'] ) == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select>
			</td>
		</tr>
		<tr>
			<td><small><?php echo $value['desc']; ?></small></td>
		</tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php
		break;
		
		case 'select_tweenType':
		?>
		<tr>
			<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
			<td width="80%">
				<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
				<?php foreach ($value['options'] as $option) { ?>
				<option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><small><?php echo $value['desc']; ?></small></td>
		</tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php
		break;
      
		case "checkbox":
		?>
		<tr>
			<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><? if(get_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                        <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
			</td>
        </tr>          
        <tr>
			<td><small><?php echo $value['desc']; ?></small></td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
        <?php 		
		break;
		
		case "radio":
		?>
		<tr>
			<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
			<td width="80%">
				<label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="<?php echo $value['value']; ?>" <?php echo $selector; ?> <?php if (get_settings( $value['id']) == $value['value'] || get_settings( $value['id']) == ""){echo 'checked="checked"';}?> /> <?php echo $value['desc']; ?></label><br />
				<label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>_2" type="radio" value="<?php echo $value['value2']; ?>" <?php echo $selector; ?> <?php if (get_settings( $value['id']) == $value['value2']){echo 'checked="checked"';}?> /> <?php echo $value['desc2']; ?></label>
			</td>
		</tr>
		<tr>
			<td><small><?php //echo $value['desc']; ?></small></td>
		</tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:0px dotted #000000;">&nbsp;</td>
		</tr>
		<!--/tr-->
		<?php
		break;
			
		case "radio_doted":
		?>
		<tr>
			<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
			<td width="80%">
				<label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="<?php echo $value['value']; ?>" <?php echo $selector; ?> <?php if (get_settings( $value['id']) == $value['value'] || get_settings( $value['id']) == ""){echo 'checked="checked"';}?> /> <?php echo $value['desc']; ?></label><br />
				<label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>_2" type="radio" value="<?php echo $value['value2']; ?>" <?php echo $selector; ?> <?php if (get_settings( $value['id']) == $value['value2']){echo 'checked="checked"';}?> /> <?php echo $value['desc2']; ?></label>
			</td>
		</tr>
		<tr>
			<td><small><?php //echo $value['desc']; ?></small></td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<!--/tr-->
		<?php
		break;			
 
 		case "slider_control_panel":
		?>
		<tr>
			<td colspan="2">
			<table>
				<tr>
					<td width="41%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
					<td width="59%"></td>
				</tr>
				<tr>
					<td><small><?php echo $value['desc']; ?></small></td>
				</tr>
			
			</td>
		</tr>
		<?php 
		break;

		case "slider_cp":
		?>
		<table class="slider-box" id="demo">
			<thead>
			<tr>
				<th style="text-align:left;width:4%;">Nr.</th>
				<th style="text-align:left;width:4%;">Up</th>
				<th style="text-align:left;width:6%;">Down</th>	
				<th style="text-align:left;width:86%;">Image Name</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$get_custom_options = get_option($shortname.'_slider_cp');
			$m = 0;
			for($i = 1; $i <= 100; $i++) 
			{
				if ($get_custom_options[$shortname.'_slider_cp_url_'.$i])
				{
					echo '
					<tr><td>'.($m+1).'</td><td><a class="control" rel="up" href="#up">Up</a></td>
					<td><a class="control" rel="down" href="#down">Down</a></td>
					<td><input style="width: 457px;" name="'.$value['id'].'_url_'.($m+1).'" id="'.$value['id'].'_url_'.($m+1).'" 
						type="text" value="'.$get_custom_options[$shortname.'_slider_cp_url_'.$i].'"></td></tr>
					';
					$m = $m + 1;
				}
			}
			
			if ( $m==0 )
			{
				$doc = new DOMDocument();
				$doc->load( MINIMALISTIC_ADMIN_XML );

				$images = $doc->getElementsByTagName("Image");
				$k=0;
				foreach( $images as $image )
				{
					$image_url = $images->item($k)->getAttribute('Filename'); 
					echo '
					<tr><td>'.($k+1).'</td><td><a class="control" rel="up" href="#up">Up</a></td>
					<td><a class="control" rel="down" href="#down">Down</a></td>
					<td><input style="width: 457px;" name="'.$value['id'].'_url_'.($k+1).'" id="'.$value['id'].'_url_'.($k+1).'" type="text" value="'.$image_url.'"></td></tr>
					';
					$k++;
				}
			}
			?>
			<p><?php echo $value['desc']; ?></p>
			<p>
				<input type="button" value="Add" onclick="addRowToTable();" />
				<input type="button" value="Remove" onclick="removeRowFromTable();" />
			</p>
		</tbody>
		</table>
		<?php
		break;
		
		
		case 'dottedline':
		?>
        <tr>
            <td></td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;

	
		case 'homepagecolumns':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?> 1</strong>
			</td>
			<td><? add_homepage_columns('1',$value['id'],$shortname); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
        <tr>
			<td colspan="2">
			<br>
			</td>
        </tr>
        <tr>
            <td>
				<strong><?php echo $value['name']; ?> 2</strong>
			</td>
			<td><? add_homepage_columns('2',$value['id'],$shortname); ?>			
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>		
        <tr>
			<td colspan="2">
				<br>
			</td>
        </tr>
        <tr>
            <td>
				<strong><?php echo $value['name']; ?> 3</strong>
			</td>
			<td><? add_homepage_columns('3',$value['id'],$shortname); ?>			
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>		
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;			

		case 'displayblogcontent':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?></strong>
			</td>
			<td><? display_blog_content($value['id'],$shortname,'blog'); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;	

		case 'displaynavmenu':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?></strong>
			</td>
			<td><? display_blog_content($value['id'],$shortname,'nav'); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;	
		
		case 'displaygallerycontent':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?></strong>
			</td>
			<td><? display_gallery_portfolio_services($value['id'],$shortname,'gallery'); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;			

		case 'displayportfoliocontent':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?></strong>
			</td>
			<td><? display_gallery_portfolio_services($value['id'],$shortname,'portfolio'); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;			

		case 'displayservicescontent':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?></strong>
			</td>
			<td><? display_gallery_portfolio_services($value['id'],$shortname,'services'); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;				
		
		case 'displaynewscontent':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?></strong>
			</td>
			<td><? display_blog_content($value['id'],$shortname,'news'); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;

		case 'displaytestimonials':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?></strong>
			</td>
			<td><? display_blog_content($value['id'],$shortname,'testimonials'); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;

		case 'displayclients':
		?>        
        <tr>
            <td>
				<strong><?php echo $value['name']; ?></strong>
			</td>
			<td><? display_blog_content($value['id'],$shortname,'clients'); ?>
				<br><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;
		
		case 'exclude_header_pages':
		?>
        <tr>
            <td>
				<strong><?php echo $value['name']; ?> </strong>
			</td>
			<td id="show_hide_pg">
				<? exclude_header_pages($value['id'],$shortname); ?><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;

		case 'exclude_categories':
		?>
        <tr>
            <td>
				<strong><?php echo $value['name']; ?> </strong>
			</td>
			<td id="show_hide_pg">
				<? exclude_categories($value['id'],$shortname); ?><small><?php echo $value['desc']; ?></small>
			</td>
        </tr>
		<tr>
			<td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td>
		</tr>
		<?php 
		break;
		
		case "toggle":

$i++;

?>

<div class="slideToggle" style="background-color:#f1f1f1; padding:5px 10px;">
<div><h3 style="cursor:pointer; font-size:1.1em; margin:0;	font-weight:bold; color:#264761; padding:10px">&rarr; <?php echo $value['name']; ?></h3>
</span><div class="clearfix"></div></div>
<div class="settings">

<?php break;
		
} 
}
?>

<form method="post">
	<p class="submit">
		<input name="reset" type="submit" value="Reset" />
		<input type="hidden" name="action" value="reset" />
	</p>
</form>

