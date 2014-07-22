<?php
// sunny-demo/admin/includes/class-sunny-demo-option.php

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

class Sunny_Demo_Option {
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

        // Register Settings
        $this->register_settings();
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
    private function register_settings() {
        /**
         * First, we register a section. This is necessary since all future settings must belong to one.
         */
        add_settings_section(
            // ID used to identify this section and with which to register options
            'sunny_demo_cloudflare_account_section',
            // Title to be displayed on the administration page
            'CloudFlare Account',
            // Callback used to render the description of the section
            array( $this, 'sunny_demo_display_cloudflare_account' ),
            // Page on which to add this section of options
            $this->plugin_slug
            );

        /**
         * Next, we will introduce the fields for CloudFlare Account info.
         */
        add_settings_field(
            // ID used to identify the field throughout the theme
            'sunny_demo_cloudflare_email',
            // The label to the left of the option interface element
            'Email',
            // The name of the function responsible for rendering the option interface
            array( $this, 'sunny_demo_render_cloudflare_email_input_html' ),
            // The page on which this option will be displayed
            $this->plugin_slug,
            // The name of the section to which this field belongs
            'sunny_demo_cloudflare_account_section'
            );

        add_settings_field(
            'sunny_demo_cloudflare_api_key',
            'API Key',
            array( $this, 'sunny_demo_render_cloudflare_api_key_input_html' ),
            $this->plugin_slug,
            'sunny_demo_cloudflare_account_section'
            );

        // Finally, we register the fields with WordPress
        register_setting(
            // The settings group name. Must exist prior to the register_setting call.
            'sunny_demo_cloudflare_account_section',
            // The name of an option to sanitize and save.
            'sunny_demo_cloudflare_email',
            // The callback function for sanitization and validation
            array( $this, 'sunny_demo_validate_input_cloudflare_email' )
            );

        register_setting(
            'sunny_demo_cloudflare_account_section',
            'sunny_demo_cloudflare_api_key',
            array( $this, 'sunny_demo_validate_input_cloudflare_api_key' )
            );

    } // end of register_settings


    /**
     * This function provides a simple description for the Sunny Demo Options page.
     * This function is being passed as a parameter in the add_settings_section function.
     *
     * @since 1.0.0
     */
    public function sunny_demo_display_cloudflare_account() {
        echo '<p>Sunny Demo purges CloudFlare cache when post updated.</p>';
    } // end of sunny_demo_display_cloudflare_account

    /**
     * This function generate the HTML input element for
     * sunny_demo_input_cloudflare_email and shown its value.
     *
     * @since 1.0.0
     */
    public function sunny_demo_render_cloudflare_email_input_html() {
        // First, we read the option from db
        $sunny_demo_cloudflare_email = get_option( 'sunny_demo_cloudflare_email', '' );

        // Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
        // Render the output
        echo '<input type="text" id="sunny_demo_input_cloudflare_email" name="sunny_demo_cloudflare_email" size="40" value="' . $sunny_demo_cloudflare_email . '" />';

    } // end sunny_demo_render_cloudflare_email_input_html


    /**
     * This function generate the HTML input element
     * for sunny_demo_input_cloudflare_api_key and shown its value.
     *
     * @since 1.0.0
     */
    public function sunny_demo_render_cloudflare_api_key_input_html() {
        $sunny_demo_cloudflare_api_key = get_option( 'sunny_demo_cloudflare_api_key', '' );

        // Render the output
        echo '<input type="text" id="sunny_demo_input_cloudflare_api_key" name="sunny_demo_cloudflare_api_key" size="40" value="' . $sunny_demo_cloudflare_api_key . '" />';

    } // end sunny_demo_render_cloudflare_api_key_input_html

    /**
     * Sanitization callback for the email option.
     * Use is_email for Sanitization
     *
     * @param  $input  The email user inputed
     *
     * @return         The sanitized email.
     *
     * @since 1.0.0
     */
    public function sunny_demo_validate_input_cloudflare_email ( $input ) {
      // Get old value from DB
      $sunny_cloudflare_email = get_option( 'sunny_demo_cloudflare_email' );

      // Don't trust users
      $input = sanitize_email( $input );

      if ( is_email( $input ) || !empty( $input ) ) {
          $output = $input;
      }
      else
        add_settings_error( 'sunny_demo_cloudflare_account_section', 'invalid-email', __( 'You have entered an invalid email.', $this->plugin_slug ) );

    return $output;

      } //end sunny_demo_validate_input_cloudflare_email


    /**
     * Sanitization callback for the email option.
     * Use is_email for Sanitization
     *
     * @param  $input  The api key user inputed
     *
     * @return         The sanitized api key.
     *
     * @since 1.0.0
     */
    public function sunny_demo_validate_input_cloudflare_api_key( $input ) {
      // Get old value
      $output = get_option( 'sunny_demo_cloudflare_api_key' );

      // Don't trust users
      // Strip all HTML and PHP tags and properly handle quoted strings
      // Leave a-z, A-Z, 0-9 only
      $input = preg_replace('/[^a-zA-Z0-9]/', '' , strip_tags( stripslashes( $input ) ) );
      if( !empty( $input ) ) {
        $output = $input;
    }
    else
        add_settings_error( 'sunny_demo_cloudflare_account_section', 'invalid-api-key', __( 'You have entered an invalid API key.', $this->plugin_slug ) );

    return $output;
    } // end sunny_demo_validate_input_cloudflare_api_key


} //end of Sunny_Demo_Option Class
