<?php get_header(); ?>

<div class="row-fluid">
	<div class="span8">
		<h1><?php the_title(); ?></h1>
		<?php the_post(); the_content(); ?>
	</div>
	
	<div class="span4">
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>