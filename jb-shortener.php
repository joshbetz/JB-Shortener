<?php
/*
Plugin Name: JB Shortener
Plugin URI: http://joshbetz.com/2011/11/jb-shortener/
Description: Changes the WordPress shorturl and Twitter Tools URL based on a base-36 encode of the post ID. Also includes materials to setup custom shorturl domain.
Version: 1.1.1
Author: Josh Betz
Author URI: http://joshbetz.com
*/

class JB_Shortlinks {
	
	const BASE = 36;

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'init', array( $this, 'redirect' ), 1 );
		add_action( 'admin_init', array( $this, 'settings' ) );
	}

	function init() {
		// Replace core shortlinks with JB shortlinks
		add_filter( 'get_shortlink', array( $this, 'shortlink' ), 10, 4 );

		// Get the short URL for Twitter Tools
		add_filter( 'tweet_blog_post_url', array( $this, 'shortener' ) );
	}

	/**
	 * Redirect to the correct page based on the short url
	 */
	function redirect() {
		if ( ! self::get_short_domain() )
			return;

		$token = trim( esc_url( $_SERVER[ 'REQUEST_URI' ] ), '/' );

		if ( ! empty( $token ) ) {
			$permalink = get_permalink( self::get_id( $token ) );

			// Redirect to the permalink
			if( $permalink ) {
				wp_safe_redirect( $permalink, 301 );
				exit;
			}
		}
	}

	function shortlink( $shortlink, $id, $context, $allowslugs ) {
		return esc_url( self::get_short_domain() ) . '/' . self::get_shorturl( $id );
	}

	function shortener( $url ) {
		$slug = end( explode( '/', $url ) );

		$args = array(
			'name' => $slug,
			'post_status' => 'publish',
			'numberposts' => 1
		);

		$post = get_posts( $args );
		$id = self::get_shorturl( $post[0]->ID );
		$shorturl = self::get_short_domain();

		return esc_url( "$shorturl/$id" );
	}

	/**
	 * Add our short domain setting to the General settings page
	 */
	function settings() {
		add_settings_field( 'jb_shorturl', __( 'Short URL' ), array( $this, 'display_setting' ), 'general', 'default', array( 'label_for' => 'jb_shorturl' ) );
		register_setting( 'general', 'jb_shorturl', array( __CLASS__, 'sanitize_url' ) );
	}

	/**
	 * Output a field to define the short domain
	 */
	function display_setting() {
		echo '<input name="jb_shorturl" id="jb_shorturl" type="text" value="' . self::get_short_domain() . '" class="code regular-text"><p class="description">The custom short url for your site</p>';
	}
	
	static function get_short_domain() {
		return get_option( 'jb_shorturl' );
	}
	
	static function sanitize_url( $url ) {
		return esc_url_raw( trim( $url, '/' ) );
	}

	/**
	 * Converts a base 10 number to base36
	 */
	static function get_shorturl( $number ) {
		return base_convert( $number, 10, self::BASE );
	}
	
	static function get_id( $number ) {
		return intval( $number, self::BASE );
	}

}

new JB_Shortlinks();
