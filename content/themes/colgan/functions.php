<?php
/**
 * colgan functions and definitions
 *
 * @package colgan
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'colgan_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function colgan_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on colgan, use a find and replace
	 * to change 'colgan' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'colgan', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'nav' => __('Navigation')
	));

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'colgan_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	/**
	 * Post thumbnails support.
	 */
	add_theme_support( 'post-thumbnails' );
}
endif; // colgan_setup
add_action( 'after_setup_theme', 'colgan_setup' );


add_action( 'wp_print_styles', 'my_styles' );

function my_styles() {
  wp_deregister_style( 'wp-members' );
}

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
} add_action('wp_enqueue_scripts', 'load_scripts');

// Add slug to body class.
function slug_body_class($classes) {
	global $post;
	if (isset($post)) $classes[] = $post->post_name;
	return $classes;
} add_filter('body_class', 'slug_body_class');

function colgan_logout_redirect($logouturl, $redir)
	{
		return $logouturl . '&amp;redirect_to='.urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
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

function colgan_logout_url($redirect = '') {
  $args = array( 'action' => 'logout' );
  if ( !empty($redirect) ) {
    $args['redirect_to'] = urlencode( $redirect );
  } else {
    $args['redirect_to'] = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
  }

  $logout_url = add_query_arg($args, site_url('wp-login.php', 'login'));
  $logout_url = wp_nonce_url( $logout_url, 'log-out' );

  return $logout_url;
}

/**
 * Allow password reset with email only (WP-Members).
 */

add_filter( 'wpmem_login_form', 'my_resetpwd_inputs' );
function my_resetpwd_inputs( $form ) {

	// we need the action variable
	global $wpmem_a;

	// if the action is pwdreset
	if( $wpmem_a == 'pwdreset' ) {
		// html for the username field
		$username_field = '<label for="user">Username</label><div class="div_text"><input name="user" type="text" id="user" value="" class="username" /></div>';

		// the "search" strings (remove breaks, tabs, and the username)
		$old = array( "\n", "\r", "\t", $username_field );

		// the "replace strings (essentially, everything is empty "")
		$new = array( '', '', '', '' );

		// do the search/replace
		$form = str_replace( $old, $new, $form );

	}

	// return the $form html string
	return $form;
}

add_filter( 'wpmem_pwdreset_args', 'my_pwd_reset_args' );
function my_pwd_reset_args( $args ) {

	// get the user data object by the user's email
	if( isset( $_POST['email'] ) ) {
		$user = get_user_by( 'email', trim( $_POST['email'] ) );
	} else {
		$user = false;
	}

	// if you have a valid user, populate the reset args
	if( $user ) {
		return array('user'=>$user->user_login,'email'=>$_POST['email']);
	} else {
		return array( 'user' => '', 'email' => '' );
	}

	// return the reset arguments
	return $args;
}
