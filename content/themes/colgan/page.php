<?php get_header(); ?>

<article role="main">
	<h1><?php the_title(); ?></h1>
	<?php the_post(); the_content(); ?>
</article>

<?php get_footer(); ?>