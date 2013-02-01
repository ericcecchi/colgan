<?php 
/*
* Template Name: Snapshot Tour
*/
get_header(); ?>

<div class="row-fluid">
	<article class="span8" role="main">
		<h1><?php the_title(); ?></h1>

		<p>The <strong>Colgan Audiocast Cornbelt Snapshot Tour</strong> is broadcast 5 days a week from April through October. Jay Calhoun interviews 10 grain professionals distributed across the entire corn belt on key market drivers including crop progress and conditions, cash markets, and farmer marketing.</p>

		<img src="<?php bloginfo('template_url'); ?>/images/Midwest-Contributors.png">

		<?php if ( is_user_logged_in() ) { ?>
		<h2 id="snapshot-tour-locations-in-order-of-appearance">Snapshot Tour Locations in Order of Appearance</h2>
		<ol>
			<li>Maumee, Ohio</li>
			<li>Henderson, Kentucky</li>
			<li>Greenville, Ohio</li>
			<li>Clymers,  Indiana</li>
			<li>Champaign, Illinois</li>
			<li>Bird Island, Minnesota</li>
			<li>Kearney, Nebraska</li>
			<li>Sheldon, Iowa</li>
		</ol>
		<?php
			$cat_args = array(
			'orderby' => 'slug',
			'order' => 'ASC',
			'child_of' => 0
			);

			$post_args = array(
				'posts_per_page' => 5,
				'category_name' => 'snapshot-tour',
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
					<h2><?php the_date(); ?></h2>
					<p class="post-content">
						<?php echo do_shortcode("[audio src=\"{$url}\" volume=\"false\" width=\"240\"]"); ?>
						<a href="<?php echo $url; ?>">Download MP3</a>
					</p>
				</section>
				<?php endif; ?>
				<?php endwhile; //each post ?>
				</tbody>
		</table>
		<?php if (function_exists("pagination")) {
			pagination($loop->max_num_pages,2); 
		} ?>
		<?php } // if logged in ?>
		<?php if (!is_user_logged_in()) { ?>
		<h2>Sample</h2>
		<p>The following is a sample of the Colgan Audiocast Cornbelt Snapshot Tour.</p>
		<p>
			<?php echo do_shortcode("[audio src=\"/wordpress/wp-content/uploads/2012-03-30-Morning-Sample.mp3\" width=\"200\" volume=\"false\"]"); ?>
			<a href="<?php echo $url; ?>">Download MP3</a>
		</p>
		<?php } // if not logged in ?>
		<?php the_post(); the_content(); ?>
	</article>
	
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>