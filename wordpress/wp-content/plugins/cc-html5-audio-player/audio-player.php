<?php
/*
Plugin Name: CodeCanyon HTML5 Audio Player
Plugin URI: none yet :/
Description: Plugin to embed the CodeCayon HTML5 audio player.
Version: 1.0
Author: Eric Cecchi
Author URI: http://ericcecchi.com
License: GPL2
*/

add_action( 'wp_print_styles', 'cc_enqueue_styles' );

/*
Color options: blue,gray,green,orange,pink
*/
function cc_enqueue_styles() {
  wp_register_style( 'cc_style', plugins_url('orange/style.css', __FILE__) );
	wp_enqueue_style( 'cc_style' );
}

add_action( 'wp_print_scripts', 'cc_enqueue_scripts' );

function cc_enqueue_scripts() {
  wp_register_script( 'jquery-ui-widget', plugins_url('jquery.ui.widget.min.js', __FILE__), array('jquery') );
  wp_register_script( 'cc_player_script', plugins_url('AudioPlayerV1.js', __FILE__), array('jquery','jquery-ui-widget') );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'cc_player_script' );
}

/*
Call
--------------------------------------------------------------------------------
	<?php audio_player("http://example.com/myaudio.mp3") ?>
--------------------------------------------------------------------------------
to generate an audio player.
*/
function audio_player($url = '') {
	echo "<audio class=\"AudioPlayerV1\" preload=\"none\" data-fallback=\"".plugins_url('AudioPlayerV1.swf', __FILE__)."\">";
  echo "	<source type=\"audio/mpeg\" src=\"{$url}\" />";
  echo "</audio>";
}

/*
Use Wordpress shortcode
--------------------------------------------------------------------------------
	[mp3 src="http://example.com/myaudio.mp3"] 
--------------------------------------------------------------------------------
to generate an audio player.

Optional paramerters (defaults listed first):
	autoplay="false|autoplay"
	preload="none|metadata|auto"
	loop="false|loop"
*/
function html5_audio($atts, $content = null) { 

	extract(shortcode_atts(array( "src" => '', "autoplay" => '', "preload"=> 'none', "loop" => '', "controls"=> 'true' ), $atts)); 
	return "<audio class=\"AudioPlayerV1\" preload=\"{$preload}\" {$loop} {$autoplay} data-fallback=\"".plugins_url('AudioPlayerV1.swf', __FILE__)."\">\n\t<source type=\"audio/mpeg\" src=\"{$src}\" />\n</audio>";
}

add_shortcode('mp3', 'html5_audio');