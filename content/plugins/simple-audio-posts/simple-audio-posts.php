<?php
/*
Plugin Name: Simple Audio Posts
Plugin URI: none yet :/
Description: Custom post type that allows posting an audio file.
Version: 1.0
Author: Eric Cecchi
Author URI: http://ericcecchi.com
License: GPL2
*/

/* Register the custom post type */
add_action( 'init', 'create_post_type' );

function create_post_type() {
	register_post_type('audio_post', array(
		'labels' => array(
			'name' => __('Audio Posts'),
			'singular_name' => __('Audio Post'),
			'add_new' => __('Add New', 'audio post'),
			'add_new_item' => __('Add New Audio Post'),
			'edit_item' => __('Edit Audio Post'),
			'new_item' => __('New Audio Post'),
			'view_item' => __('View Audio Post'),
			'search_items' => __('Search Audio Posts'),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash'),
			'parent_item_colon' => ''
		),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => "/audio/", // Permalinks format
		'query_var' => false,
		'supports' => array('title','author','cats'/*,'editor'*/),
    'taxonomies' => array('category')
	));
}

/* Add columns to custom posts screen */
add_filter("manage_edit-audio_post_columns", "set_audio_post_columns");
add_action( "manage_audio_post_posts_custom_column", "audio_post_column", 10, 2 );

function set_audio_post_columns($columns) {
	return array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __('Title'),
		"date" => __('Date'),
		"category" => __('Category'),
/* 		"the_content" => __('Description'), */
		"ap_url" => __('MP3'),
	);
}

function audio_post_column( $column, $post_id ) {
  switch ( $column ) {
    case "title":
      echo the_title();
      break;
    case "date":
      echo the_date();
      break;
    case "category":
      echo the_category();
      break;
    case "ap_url":
    	$url = get_post_meta( $post_id, 'ap_url', true);
      echo '<a href="' . $url . '">Download</a>';
      break;
    case "ap_description":
      echo the_content();
      break;
  }
}

/* Add a box to the main column on the edit screen */
add_action( 'add_meta_boxes', 'audio_post_add_custom_box' );

function audio_post_add_custom_box() {
    add_meta_box(
        'ap_file_meta',
        __( 'MP3 File', 'ap_file' ),
        'audio_post_inner_custom_box',
        'audio_post'
    );
}

/* Allow file upload in post metaboxes */
add_action( 'post_edit_form_tag' , 'post_edit_form_tag' );

/* This needs to be added to the Wordpress post form to allow file upload */
function post_edit_form_tag( ) {
    echo ' enctype="multipart/form-data"';
}

/* Print the box content */
function audio_post_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'audio_post_noncename' );

  // The actual fields for data entry
  $url = get_post_meta( $post->ID, 'ap_url', true);
  if ( $url ) {
	  echo '<label for="url">Current MP3: </label><a type="text" name="url" href="' . $url . '">' . $url . '</a><br/>' ;
	}
  echo '<label for="ap_file">Upload MP3: </label><input type="file" id="ap_file" name="ap_file" />';
}

add_action( 'save_post', 'audio_post_save_postdata' );

/* When the post is saved, save our custom data */
function audio_post_save_postdata( $post_id ) {
  global $post;

  if(strtolower($_POST['post_type']) === 'page') {
      if(!current_user_can('edit_page', $post_id)) {
          return $post_id;
      }
  }
  else {
      if(!current_user_can('edit_post', $post_id)) {
          return $post_id;
      }
  }

	if ( !empty($_FILES['ap_file']['name'] ) ) {
    $file   = $_FILES['ap_file'];

		/* Hijack the Wordpress file uploader */
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$override['action'] = 'editpost';
		$uploaded_file = wp_handle_upload($file, $override);

		$wp_filetype = wp_check_filetype($file['name'], null );
		$attachment = array(
					     'guid' => $uploaded_file['url'],
							 'post_mime_type' => $wp_filetype['type'],
							 'post_title' => $file['name'],
							 'post_content' => '',
							 'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $file['file'], $post_id );
	  // you must first include the image.php file
	  // for the function wp_generate_attachment_metadata() to work
	  require_once(ABSPATH . 'wp-admin/includes/image.php');
	  $attach_data = wp_generate_attachment_metadata( $attach_id, $file['file'] );
	  wp_update_attachment_metadata( $attach_id, $attach_data );
	  update_post_meta($post_id, "ap_url", $uploaded_file['url']);
  }
}
