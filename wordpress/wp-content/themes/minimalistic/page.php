<?php
get_header();

if ($blogpage) {
	include(TEMPLATEPATH.'/template-blog.php');
}

if ($newspage) {
	include(TEMPLATEPATH.'/template-news.php');
}

if ($testimonialpage) {
	include(TEMPLATEPATH.'/template-testimonial.php');
}

if ($clientpage) {
	include(TEMPLATEPATH.'/template-clients.php');
}


if ($simple_page == true)
{
?>
<!--BEGIN: main_content -->
   <div id="main_content_singlepage">
    	<div id="content">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if ( is_front_page() ) { ?>
						<h2 class="shadow"><?php the_title(); ?></h2>
					<?php } else { ?>
						<h1 class="shadow"><?php the_title(); ?></h1>
					<?php } ?>

						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
					<!-- .entry-content -->
				</div><!-- #post-## -->

<?php endwhile; ?>
				</div>
	<?php get_sidebar(); ?>
    </div>
    <!--END: main_content -->
    
<?
}
?>

<?php get_footer(); ?>
