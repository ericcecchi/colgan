<?php get_header(); ?>

<div class="row-fluid">
	<article class="span8" role="main">
		<h1><?php the_title(); ?></h1>
		<?php the_post(); the_content(); ?>
	</article>
	
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>