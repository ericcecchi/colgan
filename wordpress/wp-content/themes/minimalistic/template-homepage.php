<?php
/*
Template Name: Front Page
*/
get_header();
?>
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
					<!-- .entry-content -->
				</div><!-- #post-## -->

<?php endwhile; ?>
            
    <!--END: main_content -->    
	
<?php get_footer(); ?>