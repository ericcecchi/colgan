<?
get_header();
?>
    <!--BEGIN: main_content -->
    <div id="main_content">
    	<div id="content">
			<?
				$page = get_post($pageID);			
			?>		
        	<h2><?php echo $page->post_title; ?></h2>

			<?
				$testimonials_page_to_cat = get_option($shortname.'_display_testimonials_content_to_cat');
				query_posts('posts_per_page=10&paged='.$paged.'&cat='.$testimonials_page_to_cat);
				if(have_posts()) : while(have_posts()) : the_post();
			?>

			<!--BEGIN: entry-->
			<div class="testimonial_entry">
				 <blockquote>
					<p class="q1">
					<? 
						echo excerpt_content(get_the_content(''), 80, FALSE);
					?>
					</p>
					<p class="q"></p>
				  </blockquote>
			</div>	
			<!--END: entry-->		
			
			<?php 
				endwhile; 
				endif;
			?>
										
			<div class="navigation">
				<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(''); } ?>   
			</div>
        </div>
        
	<?php get_sidebar(); ?>
        
    </div>
    <!--END: main_content -->
    
<?php get_footer(); ?>