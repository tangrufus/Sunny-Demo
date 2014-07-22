<?php
// sunny-demo/uninstall.php
 
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Sunny_Demo
 * @author    Tang Rufus <tangrufus@gmail.com>
 * @license   GPL-2.0+
 * @link      http://tangrufus.com/wordpress-plugin-boilerplate-tutorial-options-page/
 * @copyright 2014 Tang Rufus
 */
 
// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
 
if ( get_option( 'sunny_demo_cloudflare_email' ) != false )
	delete_option('sunny_demo_cloudflare_email');
 
if ( get_option( 'sunny_demo_cloudflare_api_key' ) != false )
	delete_option('sunny_demo_cloudflare_api_key');