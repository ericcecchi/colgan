<?php get_header(); ?>

			<article role="main">
<?php the_post(); the_content(); ?>
			</article>

<?php get_sidebar(); ?>

<?php get_footer(); ?>