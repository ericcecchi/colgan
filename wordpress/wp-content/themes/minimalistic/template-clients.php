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
				$clients_page = get_option($shortname.'_display_clients_content');
				$sidebarPageID = $page->ID;
				$clients_page_to_cat = get_option($shortname.'_display_clients_content_to_cat');
				echo '<p>'.$page->post_content.'</p>';
				
			?>

			<div class="column2 clients subcontent">			

				<?
					$column_number = 1;
					$row_number = 1;
					query_posts('order=DESC&posts_per_page=6&paged='.$paged.'&cat='.$clients_page_to_cat);
					if(have_posts()) : while(have_posts()) : the_post();
					
					if ($column_number == 1) 
					{ 
						$class_name = ' class="first"'; 
						$column_number = 0;
				?>
						<!--begin: row<?= $row_number; ?> -->
						<div class="first">
							<h3><?php the_title(); ?></h3>
							<?php if ( get_post_meta($post->ID, $shortname.'_small_image_257x57', true) ) : ?>
								<img src="<? echo get_post_meta($post->ID, $shortname.'_small_image_257x57', true); ?>" alt="<?php the_title(); ?>" />								
							<?php endif; ?>
							<p><? echo excerpt_content(get_the_content(''), 15, TRUE); ?></p>						
						</div>				
				<?
					} 
					else 
					{ 
						$class_name = ''; 
						$column_number = 1;
				?>
						<div>
							<h3><?php the_title(); ?></h3>
							<?php if ( get_post_meta($post->ID, $shortname.'_small_image_257x57', true) ) : ?>
								<img src="<? echo get_post_meta($post->ID, $shortname.'_small_image_257x57', true); ?>" alt="<?php the_title(); ?>" />								
							<?php endif; ?>
							<p><? echo excerpt_content(get_the_content(''), 15, TRUE); ?></p>					
						</div>
						<!--end: row<?= $row_number;?> -->						
				<?
					$row_number++;
					}
					endwhile; 
					endif;
				?>
			</div>											
				<div class="navigation">
					<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(''); } ?>   
				</div>

			
		</div>
	<?php get_sidebar(); ?>
        
    </div>
    <!--END: main_content -->
    
<?php get_footer(); ?>