<?
/*
Template Name: Gallery Template
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
				$use_gallery_link = get_option($shortname.'_use_gallery_link'); 
			?>
        	<h2><?php echo $page->post_title; ?></h2>
           
		    <? if ($use_gallery_link) {?>
            <ul class="gallery_link">
			<? } else { ?>
			<ul class="gallery">
			<?
			}
				//$gallery_page_to_cat = get_option($shortname.'_display_gallery_content_to_cat');
				//if ($gallery_page_to_cat>0) 
				{
					$thePostID = $post->ID;
					
					$get_custom_options = get_option($shortname.'_gallery_page_id'); 
					$posts_per_page = get_option($shortname.'_gallery_posts'); 
					
					$cat_inclusion = $get_custom_options['gallery_id_'.$thePostID];
					if ($cat_inclusion == 0) { $cat_inclusion = ''; }
					

					
					$column_number = 1;

					query_posts('posts_per_page='.$posts_per_page.'&paged='.$paged.'&cat='.$cat_inclusion);
					if(have_posts()) : while(have_posts()) : the_post();
						$gallery_img = get_post_meta($post->ID, $shortname.'_post_thumb_160x130',true);
						$gallery_full_img = get_post_meta($post->ID, $shortname.'_big_image_500x500',true);
						if(($gallery_img) and ($gallery_full_img)){
							if ($column_number == 1) { 
								$class_name = ' class="row"';
							} else { $class_name = '';}

							if ($use_gallery_link) { echo '<li'.$class_name.'><a href="'.get_permalink().'"><img src="'.$gallery_img.'" /></a></li>';
							} else {  echo '<li'.$class_name.'><a rel="prettyPhoto[gallery]" href="'.$gallery_full_img.'"><img src="'.$gallery_img.'" /></a></li>'; }
							$column_number++;
							if ($column_number == 6) {$column_number = 1;}
						}
					endwhile; 
					endif;
				}
			?>			
			
            </ul>
				<div class="navigation">
					<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(''); } ?>   				
                </div>			
        </div>
    </div>
    <!--END: main_content -->
    
<?php get_footer(); ?>
