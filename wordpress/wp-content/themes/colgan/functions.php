<?php

add_action( 'wp_print_styles', 'my_styles' );

function my_styles() {
  wp_deregister_style( 'wp-members' );
}

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

function colgan_logout_redirect($logouturl, $redir)
	{
		return $logouturl . '&amp;redirect_to=http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
add_filter('logout_url', 'colgan_logout_redirect', 10, 2);

// show admin bar only for admins
if (!current_user_can('manage_options')) {
	add_filter('show_admin_bar', '__return_false');
}
// show admin bar only for admins and editors
if (!current_user_can('edit_posts')) {
	add_filter('show_admin_bar', '__return_false');
}
