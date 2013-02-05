<?php 
/*
* Template Name: Audio
*/
get_header(); ?>

<div class="row-fluid">
	<article class="span12" role="main">
		<h1><?php the_title(); ?></h1>
		<?php the_post(); the_content(); ?>
		<?php
			$cat_args = array(
			'orderby' => 'slug',
			'order' => 'ASC',
			'child_of' => 0
		);
		
		$categories = get_categories($cat_args); ?>
		<div class="row-fluid">
		<?php foreach($categories as $category) {
				if ($category->name == 'Uncategorized' or $category->name == 'Audio' or $category->slug == 'snapshot-tour') {continue;}
				echo '<div class="span4"><h2 class="category-name">' . $category->name . '</h2>';
		
				 $post_args = array(
					'posts_per_page' => 5,
					'cat' => $category->term_id,
					'post_type' => 'audio_post',
					'paged' => $paged
				);
		
				$loop = new WP_Query($post_args);
		?>
		<?php
			while ( $loop->have_posts() ) : $loop->the_post(); 
				if ( get_post_meta($post->ID, 'ap_url', true) ) :
					$url = get_post_meta($post->ID, "ap_url", true);
				?>
				<section class="audio-post">
					<h3><?php echo get_the_date(); ?></h3>
					<?php echo do_shortcode("[audio src=\"{$url}\" volume=\"false\" width=\"200\"]"); ?>
					<a href="<?php echo $url; ?>">Download MP3</a>
				</section>
				<?php endif; ?>
			<?php endwhile; //each post ?>
		</div>
		<?php } //each category ?> 
		<?php if (function_exists("pagination")) {
			pagination($loop->max_num_pages,2); 
		} ?>
		</div>
		
		<!-- Disclaimer Modal -->
		<div id="disclaimerModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="disclaimerModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="disclaimerModalLabel">Disclamier</h3>
			</div>
			<div class="modal-body">
				<p>These comments are those of the contributor and not necessarily of Colgan Commodities LLC. Trading futures involves risk and is not suitable for everyone. You could lose more than your initial investment. Please call us and discuss any trade recommendations prior to taking action.</p>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">OK</button>
			</div>
		</div>
		
	</article>
	
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>