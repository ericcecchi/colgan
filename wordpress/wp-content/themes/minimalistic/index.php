<?php
/* Home Page */
get_header(); 
?>
   
	<!--BEGIN: featured -->
	<div id="indexWheatBar">
		<img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/indexWheat.jpg" align="left"/>
		<h1>Use futures and options<br/>to hedge your grain!</h1>
		<p><span>As a farmer, the current business environment is full of risk. Do you really know the status of your local grain elevator, ethanol plant, feed plant or feedlot? By hedging risk through <a href="http://www.colgancommodities.com/commodities-trading/">commodities trading</a>, you'll know exactly where you stand at the end of each day and lower your overall exposure.
		</span></p>
		<a href="http://www.colgancommodities.com/commodities-trading/"><img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/learnBtn.png" align="left" /></a>
	</div>
	
	
	<!--<div id="sliderfeatures"  align="center">
		<p>You need to upgrade your Flash Player to version 10 or newer by clicking <a href="http://www.adobe.com/products/flashplayer/" target="_blank">this link</a>.</p>
	</div>
	<script type="text/javascript">
			var paths = {};
			paths.imageSource = "<?php bloginfo('template_url'); ?>/slider/images";
			paths.cssSource = "<?php bloginfo('template_url'); ?>/slider/piecemaker.css";		
			paths.xmlSource = "<?php bloginfo('template_url'); ?>/slider/piecemakerXML.php";
			var vars = {};
			vars.wmode = "transparent";
			swfobject.embedSWF("<?php bloginfo('template_url'); ?>/slider/piecemaker.swf", "sliderfeatures", "1200", "550", "10", "<?php bloginfo('template_url'); ?>/slider/js/swfobject/expressInstall.swf", paths, vars);
	</script> -->
	<!--END: featured -->

	<!--BEGIN: main_content -->
    <div id="main_content_homepage">
    	<div id="content">
        	<div class="column3 subcontent">
			<?
				$get_custom_options = get_option($shortname.'_homepage_columns');				
				for($column_number = 1;$column_number < 4; $column_number++) {
					if ($get_custom_options[$shortname.'_homepage_columns_'.$column_number]) {			
						$selected_cat[$column_number] = $get_custom_options[$shortname.'_homepage_columns_'.$column_number];
						if(strpos($selected_cat[$column_number],'_Categories')) { 
							$selected_cat_name[$column_number] = 'Categories';
							$selected_cat[$column_number] = str_replace('_Categories','',$selected_cat[$column_number]);		
						}
						if(strpos($selected_cat[$column_number],'_Pages')) { 
							$selected_cat_name[$column_number] = 'Pages';
							$selected_cat[$column_number] = str_replace('_Pages','',$selected_cat[$column_number]);		
						}
						if(strpos($selected_cat[$column_number],'_Posts')) { 
							$selected_cat_name[$column_number] = 'Posts';
							$selected_cat[$column_number] = str_replace('_Posts','',$selected_cat[$column_number]);		
						}
						
					}			
				}				

				$m = 0;
				$n = 0;
				for($column_number = 1;$column_number < 4; $column_number++) {
					if ($selected_cat_name[$column_number] == 'Categories') {
						query_posts('cat='.$selected_cat[$column_number].'&showposts=3');
						if (have_posts()) : while (have_posts()) : the_post();
							$m++;
							$get_post_id[$m] = $post->ID;
						endwhile; endif;
						
						$n++;
						$selected_cat[$column_number] = $get_post_id[$n];
					}
				}
				
				// maximum content length
				$max_content_char = 120;
			?>

			
				<div class="first">
					<?php 
						if ($selected_cat[1] != 'html') {
							get_columns_data($selected_cat[1],$selected_cat_name[1],$max_content_char,1);
						}
						else {
						// first html column
					?>
						<h3>Colgan Commodities</h3>
						<img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/manPhone.jpg" alt="Man on phone" align="left"/>
						
						<p>Colgan Commodities is a futures and commodities brokerage firm specializing in providing farmers with the tools to market your products using the experience, knowledge and research we bring to every partnership.</p>
						<a href="http://www.colgancommodities.com/about/">&#0062; About Colgan Commodities</a></p>
					
					<?
						}
					?> 
                </div>
                <div>
					<?php 
						if ($selected_cat[2] != 'html') {
							get_columns_data($selected_cat[2],$selected_cat_name[2],$max_content_char,2);
						}
						else {
						// second html column
					?>
						<h3>Commodity Trading</h3>
						<img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/cornPic.jpg" align="left" />
						<p>For any producer, commodities trading is an essential element in hedging risk. Colgan Commodities will help you develop the commodities trading portion of your ag risk management plan.</p>
						<p><a href="http://www.colgancommodities.com/commodities-trading/">&#0062; Commodity Trading Services</a>
						</p>
					<?
						}
					?> 			
                </div>
                <div>
					<?php 
						if ($selected_cat[3] != 'html') {
							// third html column						
							get_columns_data($selected_cat[3],$selected_cat_name[3],$max_content_char,3);
						}
						else {
						?>
						
						<h3>Ag Market Report</h3>
						<p>All clients receive the Colgan Audio Cast, an audio broadcast sent to your voicemail throughout the day providing you with timely market updates. Listen to a past report and sign-up for a FREE 1-week trial!</p>
						<p><a href="http://colgancommodities.com/commodities-trading/colgan-audio-cast/"><img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/audioIcon.gif" align="left" /></a><span class="indexIcon">Previous Report</span></p>
						<p><a href="http://colgancommodities.com/commodities-trading/colgan-audio-cast/"><img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/calIcon.gif" align="left" /></a><span class="indexIcon">Free 1-Week Trial</span></p>
						<p><a href="http://colgancommodities.com/commodities-trading/colgan-audio-cast/"><img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/eIcon.png" align="left" /></a><span class="indexIcon">Subscribe to Reports</span></p>
						<a href="http://colgancommodities.com/commodities-trading/colgan-audio-cast/">&#0062; Listen to Past Reports</a>
						
					<?
						}
					?> 
					
				</div>
            </div>
        </div>
    </div>
    <!--END: main_content -->

<?php get_footer(); ?>

