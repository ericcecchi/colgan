<?php
/*
Template Name: About Page
*/
get_header();
?>
	<!--BEGIN: main_content -->
    <div id="main_content_singlepage">
    	<div id="content">
			<?
				$page = get_post($pageID);			
			?>
        	<h2 class="shadow"><?php echo $page->post_title; ?></h2>
			<!--p><?php echo str_replace('<img','<img class="border"',$page->post_content); ?></p-->
			<p><?php echo $page->post_content; ?></p>
					
            <div class="column2 subcontent">
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
						<h3>Who Are We?</h3>
						<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam. 
						<a href="#">More About Us &rarr;</a></p><img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/img1.gif" alt="Sample Image" />
					
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
						<h3>Why Choose Us?</h3>
						<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam. <a href="#">Meet The Team &rarr;</a></p>
						<img src="<?php bloginfo('template_url'); ?>/<? echo $images_path;?>/img2.gif" alt="Sample Image" />
					<?
						}
					?> 			
                </div>				
            </div>
                   
        </div>
	
	<?php get_sidebar(); ?>
        
    </div>
    <!--END: main_content -->    
	
<?php get_footer(); ?>