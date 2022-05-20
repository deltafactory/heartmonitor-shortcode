<?php

/*
Plugin Name: Heart Monitor Shortcode
Author: Jeff Brand, Delta Factory
Author URI: https://deltafactory.com/
Description: Embed a heart monitor onto your site.
*/

namespace DeltaFactory;

class HeartMonitor {

	static function setup() {
		add_shortcode( 'heartmonitor', array( __CLASS__, 'shortcode' ) );
	}

	static function shortcode( $atts, $content ) {

		// Unique container ID.
		static $counter = 0;

		// Flag to include script on the page only once.
		static $include = true;

		$id = !empty( $atts['id'] ) ? $atts['id'] : 'df-heartmonitor-' . $counter++;
		$options = file_get_contents( __DIR__ . '/js/default-options.json' );

		$html = sprintf( '<div id="%s" name="monitor" class="monitor no-cpu"></div>', $id );

		if ( $include ) {
			$include = false;
			$script_path = plugin_dir_url( __FILE__ );
			$html .= sprintf( '<script src="%sjs/d3monitor-beta.min.js"></script>', $script_path );
		}

		$html .= sprintf( '<script>window.addEventListener( "load", function() { (new window.d3monitor(%s, %s)).initialize(); });</script>', json_encode( $id ), $options );

		return $html;
	}
}

HeartMonitor::setup();