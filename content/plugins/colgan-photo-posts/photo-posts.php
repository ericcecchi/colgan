<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   Photo_Posts
 * @author    Eric Cecchi <me@ericcecchi.com>
 * @license   GPL-2.0+
 * @link      http://ericcecchi.com
 * @copyright 2013 Eric Cecchi
 *
 * @wordpress-plugin
 * Plugin Name: Colgan Photos
 * Plugin URI:  http://colgancommodities.com
 * Description: Photos @Colgan
 * Version:     1.0.0
 * Author:      Eric Cecchi
 * Author URI:  http://ericcecchi.com
 * Text Domain: photo-posts-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO: replace `class-photo-posts.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'class-photo-posts.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// TODO: replace Photo_Posts with the name of the plugin defined in `class-photo-posts.php`
register_activation_hook( __FILE__, array( 'Photo_Posts', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Photo_Posts', 'deactivate' ) );

// TODO: replace Photo_Posts with the name of the plugin defined in `class-photo-posts.php`
Photo_Posts::get_instance();