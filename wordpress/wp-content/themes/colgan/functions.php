<?php

// Enable custom menus.
function enable_menus() {
	register_nav_menus( array(
		'nav' => __('Navigation')
	));
} add_action('init', 'enable_menus');

function custom_menus($args = '') {
	$args['container'] = '';
	return $args;
} add_filter('wp_nav_menu_args', 'custom_menus');

// Enable dynamic sidebars.
register_sidebar();

// Load Javascript.
function load_scripts() {
	if (!is_admin()) {
		wp_deregister_script( 'jquery' );
		wp_register_script('jquery', get_template_directory_uri().'/js/jquery.js', 0, '1.7.1', true);
		wp_enqueue_script('jquery');
		wp_register_script('colgan', get_template_directory_uri().'/js/colgan.js', 'jquery', '1.0', true);
		wp_enqueue_script('colgan');
/*
		wp_register_script('analytics', get_template_directory_uri().'/js/analytics.js', 0, '1.0', true);
		wp_enqueue_script('analytics');
*/
	}
} add_action('init', 'load_scripts');

// Add slug to body class.
function slug_body_class($classes) {
	global $post;
	if (isset($post)) $classes[] = $post->post_name;
	return $classes;
} add_filter('body_class', 'slug_body_class');
