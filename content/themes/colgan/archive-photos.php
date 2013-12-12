<?php
/**
 * Photos Archive.
 *
 * @package colgan
 */

get_header(); ?>


<h1>Photo Blog</h1>
<?php if (current_user_can('publish_posts')) : ?>
	<a href="<?php echo bloginfo('url').'/wordpress/wp-admin/post-new.php?post_type=photos'; ?>" class="btn btn-primary">New photo post</a>
<?php endif; ?>
<div class="row">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post();
			$post_id = get_the_ID();
		?>
			<article class="photo-archive list col col-lg-4">
				<header class="entry-meta">
					<h2 class="title"><?php the_title(); ?></h2>
					<time class="date"><?php echo date('F j, Y', get_post_meta($post_id, '_photo_date', true)); ?></time>
					<span class="location"> • <?php echo get_post_meta($post_id, '_photo_location', true); ?>
				</header>
				<div class="photo-container">
					<a href="#photoModal" data-toggle="modal" data-title="<?php the_title(); ?>">
						<?php echo get_the_post_thumbnail($post_id, 'original'); ?>
						<div class="caption">
							<?php the_content(); ?>
						</div><!-- .entry-content -->
					</a>
				</div>
				<?php edit_post_link('Edit photo', '<p class="edit-link">', '</p>'); ?>
			</article>
		<?php endwhile; ?>

	<?php else : ?>

		<?php get_template_part( 'no-results', 'archive' ); ?>

	<?php endif; ?>

</div>
<?php if (function_exists("pagination")) {
	pagination($loop->max_num_pages,2);
} ?>

<div id="photoModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Photo title</h4>
			</div>
			<div class="modal-body">
				<img src="http://colgan.dev/wordpress/wp-content/uploads/2013/08/IMG_3063-1024x768.jpg">
			</div>
			<div class="modal-footer">
				<p>This is one of my cars. It’s green. They say geniuses pick green. But I didn’t pick it.</p>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php get_footer(); ?>