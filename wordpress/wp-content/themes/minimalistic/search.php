<?php
get_header(); ?>
<div id="container">
		<div id="main_content">
			<div id="content">

				<?php if (have_posts()) : ?>

				<h3 class="pagetitle">Search Results</h3>

				<?php while (have_posts()) : the_post(); ?>

					<div <?php post_class() ?>>
						<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
						<small><?php the_time('l, F jS, Y') ?></small>

						<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
					</div>

				<?php endwhile; ?>

													
				<div class="navigation">
					<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(''); } ?>   
				</div>

				<?php else : ?>

					<h2 class="center">No posts found. Try a different search?</h2>
					<form role="search" method="get" id="search" action="<?php bloginfo('home'); ?>">
					<p><input type="text" id="s" name="s" value="Search" onblur="if (this.value == ''){this.value = 'Search'; }" onfocus="if (this.value == 'Search') {this.value = ''; }"  />&nbsp;<input  id="searchsubmit" type="image" class="go" src="<?php bloginfo('template_url'); ?>/<? echo $images_path; ?>/magnify.gif" /></p>
					</form>
					
				<?php endif; ?>
			</div>

	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
