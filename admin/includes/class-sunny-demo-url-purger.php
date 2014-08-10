<?php
// sunny-demo/admin/includes/class-sunny-demo-url-purger.php

/**
 * @package     Sunny_Demo
 * @subpackage  Sunny_Demo_Admin
 * @author      Tang Rufus <tangrufus@gmail.com>
 * @license     GPL-2.0+
 * @link        http://tangrufus.com/wordpress-plugin-boilerplate-tutorial-options-page/
 * @copyright   2014 Tang Rufus
 * @author      Tang Rufus <tangrufus@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Sunny_Demo_URL_Purger {
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by registrating settings
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Get $plugin_slug from public plugin class.
		$plugin = Sunny_Demo::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add Settings
		$this->add_settings();
		
		add_action( 'wp_ajax_sunny_demo_purge_url', array( $this, 'process_ajax' ) );
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
	 * Register the CloudFlare account section, CloudFlare email field
	 * and CloudFlare api key field
	 *
	 * @since     1.0.0
	 */
	private function add_settings() {
		/**
		 * First, we register a section. This is necessary since all future settings must belong to one.
		 */
		add_settings_section(
			// ID used to identify this section and with which to register options
			'sunny_demo_url_purger_section',
			// Title to be displayed on the administration page
			'URL Purger',
			// Callback used to render the description of the section
			array( $this, 'sunny_demo_display_url_purger' ),
			// Page on which to add this section of options
			'sunny_demo_url_purger_section'
			);

		/**
		 * Next, we will introduce the fields for CloudFlare Account info.
		 */
		add_settings_field(
			// ID used to identify the field throughout the theme
			'sunny_demo_purge_url',
			// The label to the left of the option interface element
			'URL',
			// The name of the function responsible for rendering the option interface
			array( $this, 'sunny_demo_render_purge_url_input_html' ),
			// The page on which this option will be displayed
			'sunny_demo_url_purger_section',
			// The name of the section to which this field belongs
			'sunny_demo_url_purger_section'
			);


	} // end of register_settings


	/**
	 * This function provides a simple description for the Sunny Demo Options page.
	 * This function is being passed as a parameter in the add_settings_section function.
	 *
	 * @since 1.0.0
	 */
	public function sunny_demo_display_url_purger() {
		echo '<p>Clear cache of this URL from CloudFlare.</p>';
	} // end of sunny_demo_display_url_purger

	/**
	 * This function generate the HTML input element for
	 * sunny_demo_purge_url and shown its value.
	 *
	 * @since 1.0.0
	 */
	public function sunny_demo_render_purge_url_input_html() {

		// Render the output
		echo '<input type="url" id="sunny_demo_purge_url" name="sunny_demo_purge_url" size="40" />';

	} // end sunny_demo_render_purge_url_input_html

	/**
	 * @since     1.2.0
	 */
	public function process_ajax() {

		header('Content-Type: application/json');

		// Check that user has proper secuity level  && Check the nonce field
		if ( ! current_user_can( 'manage_options') || ! check_ajax_referer( 'sunny_demo_url_purger', 'nonce', false ) ) {

			$return_args = array(
				"result" => "Error",
				"message" => "403 Forbidden",
				);
			$response = json_encode( $return_args );
			echo $response;

			die;

		}

		// It's safe to carry on
		// Prepare return message
		$message = '';
		$links = array();

		$post_url = esc_url_raw( $_REQUEST['post_url'], array( 'http', 'https' ) );

		if ( '' == $post_url || $_REQUEST['post_url'] != $post_url ) {

			$message = 'Error: Invalid URL.';

		} elseif ( '' != $post_url  ) {

			$_response = Sunny_Demo_Purger::purge_cloudflare_cache_by_url( $post_url );

			if ( is_wp_error( $_response ) ) {

				$message .= 'WP Error: ' . $_response->get_error_message();

				} // end wp error
				else {

					// API made
					$_response_array = json_decode( $_response['body'], true );

					if ( 'error' == $_response_array['result'] ) {

						$message .= 'API Error: ';
						$message .= $_response_array['msg'] . ' -- ';

					} // end api returns error
					elseif ( 'success' == $_response_array['result'] ) {

						$message .= 'Success: ';

					} // end api success //end elseif

				} // end else

		} // end elseif

		$message .= esc_url( $post_url );

		$return_args = array(
			'message' => $message,
			);
		$response = json_encode( $return_args );
		echo $response;

		die;

	} // end process_ajax




} //end  Sunny_Demo_URL_Purger
