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
					$news_page_to_cat = get_option($shortname.'_display_news_content_to_cat');
					query_posts('posts_per_page=5&paged='.$paged.'&cat='.$news_page_to_cat);
					if(have_posts()) : while(have_posts()) : the_post();
				?>

				
            	<!--BEGIN: entry-->			
				<div class="news_entry" id="post-<?php $postid = the_ID(); ?>">
					<h3><a href="<?php echo get_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
					<div class="news_meta">
						<span class="date">Posted on <?php the_time('M. j, Y') ?> <em>by</em> <?php the_author_posts_link(); ?></span>
					</div>
						<?php if ( get_post_meta($post->ID, $shortname.'_post_thumb_160x130', true) ) : ?>
							<img src="<? echo get_post_meta($post->ID, $shortname.'_post_thumb_160x130', true); ?>" alt="<?php the_title(); ?>" class="border alignleft" />
						<?php endif; ?>
						<?php echo excerpt_content(get_the_content(''), 60, TRUE);?> <a href="<?php echo get_permalink() ?>">Read More &rarr;</a></p>
				</div>
				             
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