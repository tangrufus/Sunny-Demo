<?php
// sunny-demo/admin/includes/class-sunny-demo-api-logger.php

/**
 * @package     Sunny
 * @subpackage  Sunny_API_Logger
 * @author      Tang Rufus <tangrufus@gmail.com>
 * @license     GPL-2.0+
 * @link      http://tangrufus.com/wordpress-plugin-boilerplate-tutorial-hooks-and-http-api
 * @copyright   2014 Tang Rufus
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	 die;
}

/**
 * Helper class. Log API responses.
 */
class Sunny_Demo_API_Logger {
	/**
	 * Log debug messages in php error log.
	 *
	 * @since     1.0.0
	 *
	 * @param     $response The response after api call, could be WP Error object or HTTP return object
	 * @param     $url      The Url that API calls
	 *
	 * @return    void      No return
	 */
	public static function write_report( $response, $url ) {

		if ( is_wp_error( $response ) ) {

			error_log( 'Sunny Demo ' . 'WP Error ' . $response->get_error_message() . $url );

		}// end WP Error

		else {

			// API made
			$response_array = json_decode( $response['body'], true );

			if ( 'error' == $response_array['result'] ) {

				error_log( 'Sunny Demo ' . 'API Error ' . $response_array['msg'] .'--' . $url );
				error_log( 'Sunny Demo ' . 'API Error ' . $response_array['msg'] .'--' . $url );


			} else {

				error_log( 'Sunny Demo' . 'API Success ' . $url );

			} // end else

		} // end API made

	} // end  write_report( $response, $url )

} // end Sunny_Demo_API_Logger 