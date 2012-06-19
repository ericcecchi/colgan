<?php 
/*
* Template Name: Audio
*/
get_header(); ?>

<div class="row-fluid">
	<article class="span8" role="main">
		<h1><?php the_title(); ?></h1>
		<?php the_post(); the_content(); ?>
		<?php
			$cat_args = array(
		  'orderby' => 'name',
		  'order' => 'ASC',
		  'child_of' => 0
		);
		
		$categories =   get_categories($cat_args); 
		
		foreach($categories as $category) {
				if ($category->name == 'Uncategorized' or $category->name == 'Audio') {continue;}
		    echo '<h2>' . $category->name . '</h2>';
		
		     $post_args = array(
		      'posts_per_page' => 5,
		      'cat' => $category->term_id,
		      'post_type' => 'audio_post',
		      'paged' => $paged
		    );
		
		    $loop = new WP_Query($post_args);
		?>
		<table class="table table-striped">
		  <thead>
		    <tr>
		      <th>Date</th>
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
					<td><?php echo get_the_date(); ?></td>
					<td>
						<?php if (function_exists("audio_player")) {
							audio_player($url); 
						} ?>
					</td>
					<td><a href="<?php echo $url; ?>">Download</a></td>
				</tr>
				<?php endif; ?>
			<?php endwhile; //each post ?>
			  </tbody>
		</table>
		<?php } //each category ?> 
		<?php if (function_exists("pagination")) {
			pagination($loop->max_num_pages); 
		} ?>
	</article>
	
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>