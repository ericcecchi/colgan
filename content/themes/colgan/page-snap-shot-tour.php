<?php
/*
* Template Name: Snapshot Tour
*/
get_header(); ?>

<article role="main">
	<h1><?php the_title(); ?></h1>

	<p>The <strong>Colgan Audiocast Cornbelt Snapshot Tour</strong> is broadcast 5 days a week from April through October. Jay Calhoun interviews 10 grain professionals distributed across the entire corn belt on key market drivers including crop progress and conditions, cash markets, and farmer marketing.</p>

	<p><a href="#disclaimerModal" data-toggle="modal">Read the disclaimer.</a></p>

	<div class="row">
		<div class="col-sm-6">
			<img src="<?php bloginfo('template_url'); ?>/images/Midwest-Contributors.png">
		</div>

		<div class="col-sm-6">
			<h2 style="margin-top: 0;">
				Snapshot Tour locations<br>
				<small>In order of appearance</small>
			</h2>
			<ol>
				<li>Maumee, Ohio</li>
				<li>Trenton, Tennessee</li>
				<li>Greenville, Ohio</li>
				<li>Logansport,  Indiana</li>
				<li>Champaign, Illinois</li>
				<li>Camp Grove, Illinois</li>
				<li>Bird Island, Minnesota</li>
				<li>Kearney, Nebraska</li>
				<li>Sheldon, Iowa</li>
			</ol>
		</div>
	</div>

	<?php  // if ( is_user_logged_in() ) { ?>

	<?php if (current_user_can('publish_posts')) : ?>
		<a href="<?php echo bloginfo('url').'/wordpress/wp-admin/post-new.php?post_type=audio_post'; ?>" class="btn btn-primary">New audio post</a>
	<?php endif; ?>
	<div id="disclaimerModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Disclamier</h4>
				</div>
				<div class="modal-body">
					<p>These comments are those of the contributor and not necessarily of Colgan Commodities LLC. Trading futures involves risk and is not suitable for everyone. You could lose more than your initial investment. Please call us and discuss any trade recommendations prior to taking action.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

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
			<div class="audio-post">
				<h3><?php the_date(); ?></h3>
				<p class="post-content">
					<audio id="<?php echo $post->ID; ?>" src="<?php echo $url; ?>" type="audio/mp3" controls="controls" preload="none" data-category="Snapshot Tour"></audio>
					<a href="<?php echo $url; ?>">Download MP3</a>
				</p>
			</div>
			<?php endif; ?>
			<?php endwhile; //each post ?>
			</tbody>
	</table>
	<?php if (function_exists("pagination")) {
		pagination($loop->max_num_pages,2);
	} ?>
	<?php // } // if logged in ?>
<!-- 		<?php if (!is_user_logged_in()) { ?>
	<h2>Sample</h2>
	<p>The following is a sample of the Colgan Audiocast Cornbelt Snapshot Tour.</p>
	<p>
		<?php echo do_shortcode("[audio src=\"/wordpress/wp-content/uploads/2012/10/snapshot101612.mp3\" width=\"200\" volume=\"false\"]"); ?>
		<a href="/wordpress/wp-content/uploads/2012/10/snapshot101612.mp3">Download MP3</a>
	</p>
	<?php } // if not logged in ?> -->
	<?php the_post(); the_content(); ?>
</article>

<?php get_footer(); ?>
