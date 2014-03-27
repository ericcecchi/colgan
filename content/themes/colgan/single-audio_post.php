<?php get_header(); ?>
<div class="row-fluid">
	<article class="span8" role="main">
<article>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<section>
		<?php the_title(); ?>

		<?php endwhile; else : ?>
		<h2>Uh-oh.</h2>
		<p>It worked 'til you broke it!</p>

		<?php endif; ?>
	</section>
	</article>
	<?php // get_sidebar(); ?>
</div>
<?php get_footer(); ?>