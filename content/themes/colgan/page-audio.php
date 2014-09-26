<?php
/*
* Template Name: Audio
*/
get_header(); ?>

<article class="" role="main">
  <h1><?php the_title(); ?></h1>
  <?php the_post(); the_content(); ?>

  <?php if (current_user_can('publish_posts')) : ?>
    <a href="<?php echo bloginfo('url').'/wordpress/wp-admin/post-new.php?post_type=audio_post'; ?>" class="btn btn-primary">New audio post</a>
  <?php endif; ?>

  <?php
  // if ( is_user_logged_in() ):

  $cat_args = array(
    'orderby' => 'slug',
    'order' => 'ASC',
    'child_of' => 0
  );

  $categories = get_categories($cat_args);
  ?>
  <div class="row">
    <?php foreach($categories as $category): ?>
    <?php
    if ($category->name == 'Uncategorized' or $category->name == 'Audio' or $category->slug == '2-meat-comments') { continue; }
    echo '<div class="col col-sm-4"><h2 class="category-name">' . $category->name . '</h2>';

    $post_args = array(
      'posts_per_page' => 5,
      'cat' => $category->term_id,
      'post_type' => 'audio_post',
      'paged' => $paged
    );

    $loop = new WP_Query($post_args);

    while ($loop->have_posts()):
      $loop->the_post();
      if ( get_post_meta($post->ID, 'ap_url', true) ):
        $url = get_post_meta($post->ID, "ap_url", true);
    ?>
      <section class="audio-post">
        <h3><?php echo get_the_date(); ?></h3>
        <audio id="<?php echo $post->ID; ?>" src="<?php echo $url; ?>" type="audio/mp3" controls="controls" preload="none" data-category="<?php echo $category->name; ?>"></audio>
        <a href="<?php echo $url; ?>">Download MP3</a>
      </section>
      <?php endif; ?>
      <?php endwhile; //each post ?>
  </div>
  <?php endforeach; //each category ?>

  <?php
    if (function_exists("pagination")) {
      pagination($loop->max_num_pages,2);
    }
  ?>

  <?php // endif; // if logged in ?>
</article>
</div>

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
</article>

<?php get_footer(); ?>
