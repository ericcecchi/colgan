<?php
get_header();
?>

	<!--BEGIN: main_content -->
    <div id="main_content_singlepage">
    	<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<h2 class="shadow"><?php the_title(); ?></h2>
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
<?php
foreach((get_the_category()) as $category) { 
    $category_id = $category->cat_ID; 
} 
?>
				
			</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.!!!!</p>

	<?php endif; ?>
	
	</div>
	
	<?php get_sidebar(); ?>
        
    </div>
    <!--END: main_content -->    

<?php get_footer(); ?>
