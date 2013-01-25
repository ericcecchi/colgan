<?php 
/*
* Template Name: Snap Shot Tour
*/
get_header(); ?>

<div class="row-fluid">
	<article class="span8" role="main">
		<h1><?php the_title(); ?></h1>
		<?php if ( is_user_logged_in() ) { ?>
		<?php
			$cat_args = array(
			'orderby' => 'slug',
			'order' => 'ASC',
			'child_of' => 0
			);

			$post_args = array(
				'posts_per_page' => 5,
				'category_name' => 'snap-shot-tour',
				'post_type' => 'audio_post',
				'paged' => $paged
			);
	
			$loop = new WP_Query($post_args);
		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Date</th>
					<th>Contributor</th>
					<th>Stream</th>
					<th>MP3</th>
				</tr>
			</thead>
			<tbody>
		<?php
			while ( $loop->have_posts() ) : $loop->the_post(); 
				if ( get_post_meta($post->ID, 'ap_url', true) ) :
					$url = get_post_meta($post->ID, "ap_url", true);
				?>
				<tr>
					<td><?php the_date(); ?></td>
					<td><?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?></td>
					<td>
						<?php echo do_shortcode("[audio src=\"{$url}\" volume=\"false\" width=\"200\"]"); ?>
					</td>
					<td><a href="<?php echo $url; ?>">Download</a></td>
				</tr>
				<?php endif; ?>
			<?php endwhile; //each post ?>
				</tbody>
		</table>
		<?php if (function_exists("pagination")) {
			pagination($loop->max_num_pages,2); 
		} ?>
		<?php } // if logged in 
		else { ?>
		<p>The <strong>Colgan AudioCast</strong> is a daily, periodic voicemail sent to your phone with brief market updates, insight, and commentary. Produced and delivered by Marty Colgan, this broadcast contacts everyone simultaneously. No matter if your name starts with A or Z, everyone is called at the same time. Both large and small clients get the same information immediately. Each call lasts a maximum of 90 seconds making it short, concise and to the point. Since it is a recording, you can listen at your convenience.</p>

		<p>This exclusive market commentary is <strong>free for all clients</strong>, or available for $240/year.</p>
		<h3>Morning sample</h3>
		<p>
		<?php echo do_shortcode("[audio src=\"/wordpress/wp-content/uploads/2012-03-30-Morning-Sample.mp3\" volume=\"false\"]"); ?>
		</p>
		<h3>Afternoon sample</h3>
		<p>
		<?php echo do_shortcode("[audio src=\"/wordpress/wp-content/uploads/2012-03-30-Morning-Sample.mp3\" volume=\"false\"]"); ?>
		</p>
		<?php } // if not logged in ?>
		<?php the_post(); the_content(); ?>
	</article>
	
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>