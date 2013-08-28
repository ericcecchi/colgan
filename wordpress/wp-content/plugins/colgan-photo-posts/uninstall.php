<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Photo_Posts
 * @author    Eric Cecchi <me@ericcecchi.com>
 * @license   GPL-2.0+
 * @link      http://ericcecchi.com
 * @copyright 2013 Eric Cecchi
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here