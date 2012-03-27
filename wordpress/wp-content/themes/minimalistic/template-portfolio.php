<?
/*
Template Name: Portfolio Template
*/
get_header();
?>
    <!--BEGIN: main_content -->
    <div id="main_content">
    	<div id="content">

        	<!--ul class="navi">
            	<li><a href="#">Subpage 01</a></li>
                <li><a href="#">Subpage 02</a></li>
                <li class="last"><a href="#">Subpage 03</a></li>
            </ul-->
			<?
				$page = get_post($pageID);			
			?>		
        	<h2><?php echo $page->post_title; ?></h2>

			<?
				$page = get_post($pageID);
				echo '<p>'.$page->post_content.'</p>';
				echo '<div class="column3 portfolio subcontent">';
				$portfolio_page_to_cat = get_option($shortname.'_display_portfolio_content_to_cat');
				if ($portfolio_page_to_cat>0) {
					$column_number = 1;
					$row_number = 1;
					query_posts('posts_per_page=6&paged='.$paged.'&cat='.$portfolio_page_to_cat);
					if(have_posts()) : while(have_posts()) : the_post();
					
						if ($column_number == 1)  {
							echo '
							<!--begin: row'.$row_number.' -->
							<div class="first">
							';
						} else { echo '<div>'; }
			?>
                	<h3><? the_title(); ?></h3>
			<?
						$project_thumb = get_post_meta($post->ID, $shortname.'_small_image_257x57',true);
						$large_preview = get_post_meta($post->ID, $shortname.'_big_image_500x500',true);			
							
						if(($project_thumb) and ($large_preview)){
							echo '<a rel="prettyPhoto[gallery]"href="'.$large_preview.'" class="thumb"><img src="'.$project_thumb.'" /></a>';
						}
						$column_number++;
						if ($column_number == 4) {$column_number = 1; $row_number++; }						
						echo '<p>'.excerpt_content(get_the_content(''), 18, true).'<br><a href="'.get_permalink().'">Learn more about this project &rarr;</a></p>';
							
			?>
				        </div>
			<?
					endwhile; 
					endif;
				}
			?>	
                <br class="clear" />               
            </div>
				<div class="navigation">
					<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(''); } ?>   
                </div>
			
        </div>
    </div>
    <!--END: main_content -->
    
<?php get_footer(); ?>
