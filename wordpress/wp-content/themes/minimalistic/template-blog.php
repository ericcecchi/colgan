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
					//get exclusions for categories
					$cats_exclusions = get_wp_options('_exclude_categories', '', true);				
					$cats_exclusions = str_replace(',,','|-',$cats_exclusions);
					$cats_exclusions = str_replace(',','-',$cats_exclusions);
					$cats_exclusions = substr($cats_exclusions, 0, -1);
					$cats_exclusions = str_replace('|',',',$cats_exclusions);
					
					query_posts('posts_per_page=5&paged='.$paged.'&cat='.$cats_exclusions);
					if(have_posts()) : while(have_posts()) : the_post();
				?>

            	<!--BEGIN: entry-->
            	<div class="entry" <?php post_class() ?> id="post-<?php $postid = the_ID(); ?>">
                    <div class="entry_excerpt">
                    	<h3><a href="<?php echo get_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
						<?php if ( get_post_meta($post->ID, $shortname.'_post_thumb_160x130', true) ) : ?>
							<img src="<? echo get_post_meta($post->ID, $shortname.'_post_thumb_160x130', true); ?>" alt="<?php the_title(); ?>" class="border alignright" />
						<?php endif; ?>
                        
                        <?php echo excerpt_content(get_the_content(''), 70, FALSE);?>
                        <p class="button"><a href="<?php echo get_permalink() ?>"><span>Continue Reading ></span></a></p>
                    </div>
                   <div class="entry_meta">
						<span class="date"><?php the_time('M. j, Y') ?></span>
                        <span class="categories"><?php if ( count(($categories=get_the_category())) >= 1  ) : ?>
						 <?php the_category(', ') ?>
					<?php endif; ?></span>
						<span class="comments"><a href="<?php echo get_permalink() ?>"><?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?></a></span>
					</div>
				</div><!--END: entry-->
				             
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