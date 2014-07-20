<?php
/**
 * Sunny Demo
 *
 * A demo to show how to write a plugin interacting with CloudFlare
 * Client API base on WordPress Plugin Boilerplate.
 *
 * @package   Sunny_Demo
 * @author    Tang Rufus <tangrufus@gmail.com>
 * @license   GPL-2.0+
 * @link      http://tangrufus.com/wordpress-plugin-boilerplate-tutorial-getting-started/
 * @copyright 2014 Tang Rufus
 *
 * @wordpress-plugin
 * Plugin Name:       Sunny Demo
 * Plugin URI:        http://tangrufus.com/sunny
 * Description:       A demo to show how to write a plugin interacting with CloudFlare Client API base on WordPress Plugin Boilerplate.
 * Version:           1.0.0
 * Author:            Tang Rufus
 * Author URI:        http://tangrufus.com
 * Text Domain:       sunny-demo-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/tangrufus/sunny/
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-sunny-demo.php` with the name of the plugin's class file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-sunny-demo.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace Sunny_Demo with the name of the class defined in
 *   `class-sunny-demo.php`
 */
register_activation_hook( __FILE__, array( 'Sunny_Demo', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Sunny_Demo', 'deactivate' ) );

/*
 * @TODO:
 *
 * - replace Sunny_Demo with the name of the class defined in
 *   `class-sunny-demo.php`
 */
add_action( 'plugins_loaded', array( 'Sunny_Demo', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-sunny-demo-admin.php` with the name of the plugin's admin file
 * - replace Sunny_Demo_Admin with the name of the class defined in
 *   `class-sunny-demo-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-sunny-demo-admin.php' );
	add_action( 'plugins_loaded', array( 'Sunny_Demo_Admin', 'get_instance' ) );

}
