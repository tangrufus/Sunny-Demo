<?php
// sunny-demo/admin/includes/class-sunny-demo-post-purger.php

/**
 * @package 	Sunny_Demo
 * @subpackage 	Sunny_Demo_Admin
 * @author		Tang Rufus <rufus@tangrufus.com>
 * @license   	GPL-2.0+
 * @link 		http://tangrufus.com/wordpress-plugin-boilerplate-tutorial-hooks-and-http-api
 * @copyright 	2014 Tang Rufus
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	 die;
}

/**
 * This class takes care the purge process fired from the admin dashboard.
 */
class Sunny_Demo_Post_Purger {
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the class and purge after post saved
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		add_action( 'save_post', array( $this, 'purge_post' ), 20 );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Purge the updated post only if it is published.
	 * Hooked into 'save_post'
	 *
	 * @param    integer    $post_id    The current post being saved
	 *
	 * @since 	 1.0.0
	 */
	public function purge_post( $post_id ) {

		if ( $this->should_purge( $post_id ) ) {

				$url = get_permalink( $post_id );
				Sunny_Demo_Purger::purge_cloudflare_cache_by_url( $url );

		}

		return $post_id;

	} // end purge_post

	/**
	 * Verifies that the user who is currently logged in has permission to save the post
	 * and the post is published.
	 *
	 * @since 	 1.0.0
	 *
	 * @param    integer    $post_id    The current post being saved.
	 *
	 * @return   boolean                True if the user can save the information
	 */
	private function should_purge( $post_id ) {

		$post = get_post( $post_id );

		if ( false == is_object( $post )  ) {

			return false;

		} // end if

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_published = ( 'publish' == get_post_status( $post_id ) );

		return ! ( $is_autosave || $is_revision ) && $is_published;

	} //end should_purge

}