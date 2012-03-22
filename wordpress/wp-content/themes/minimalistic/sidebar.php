
<?php
		/* Sidebar */
		GLOBAL $shortname;
		GLOBAL $images_path;

		/* Get exclusions for categories */
		$cats_exclusions = get_wp_options('_exclude_categories', '', true);				
		$cats_exclusions_popular_posts = $cats_exclusions;
		$cats_exclusions_sidebar = $cats_exclusions;
		
		$cats_exclusions_sidebar = substr($cats_exclusions_sidebar, 0, -1);
		$cats_exclusions_sidebar = substr($cats_exclusions_sidebar, 1);
		$cats_exclusions_sidebar = str_replace(',,',',',$cats_exclusions_sidebar);
		
		$cats_exclusions = str_replace(',,','|-',$cats_exclusions);
		$cats_exclusions = str_replace(',','-',$cats_exclusions);
		$cats_exclusions = substr($cats_exclusions, 0, -1);
		$cats_exclusions = str_replace('|',',',$cats_exclusions);
		

		/* Get Follow Us Options */
		$show_facebook_account = get_option($shortname.'_show_facebook_account');
		$facebook_account = get_option($shortname.'_facebook_account');

		$show_twitter_account = get_option($shortname.'_show_twitter_page');
		$twitter_account = get_option($shortname.'_twitter_page');

		$show_linkedin_account = get_option($shortname.'_show_linkedin_account');
		$linkedin_account = get_option($shortname.'_linkedin_account');

		$show_delicious_account = get_option($shortname.'_show_delicious_account');
		$delicious_account = get_option($shortname.'_delicious_account');

		$show_stumbleupon_account = get_option($shortname.'_show_stumbleupon_account');
		$stumbleupon_account = get_option($shortname.'_stumbleupon_account');

		$show_vimeo_account = get_option($shortname.'_show_vimeo_account');
		$vimeo_account = get_option($shortname.'_vimeo_account');

		$show_deviantart_account = get_option($shortname.'_show_deviantart_account');
		$deviantart_account = get_option($shortname.'_deviantart_account');

		if ( ($show_facebook_account == 'Yes') or ($show_twitter_account == 'Yes') or ($show_linkedin_account == 'Yes') or 
			($show_delicious_account == 'Yes') or ($show_stumbleupon_account == 'Yes') or ($show_vimeo_account == 'Yes') or ($show_deviantart_account == 'Yes') ) {
			$show_follow_us_section = True;
		} else {
			$show_follow_us_section = False;
		}
		/* END: Get Follow Us Options */
?>
        <!--BEGIN: sidebar -->
        <div id="sidebar">
		<img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/wheat.jpg" />
		<a href="http://colgancommodities.com/commodities-trading/colgan-audio-cast/"><img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/audio.gif" /></a>
		<a href="http://colgancommodities.com/contact-us/"><img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/questions.gif" /></a>

        </div>
	

        <!--END: sidebar --> 