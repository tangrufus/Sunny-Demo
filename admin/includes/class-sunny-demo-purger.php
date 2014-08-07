<?php
// sunny-demo/admin/includes/class-sunny-demo-purger.php

/**
 * @package 	Sunny
 * @subpackage 	Sunny_Purger
 * @author 		Tang Rufus <tangrufus@gmail.com>
 * @license  	GPL-2.0+
 * @link  		http://tangrufus.com
 * @copyright 	2014 Tang Rufus
 * @link      	http://tangrufus.com/wordpress-plugin-boilerplate-tutorial-hooks-and-http-api
 * @see 		https://github.com/vexxhost/CloudFlare-API
 * @see 		https://www.cloudflare.com/docs/client-api.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	 die;
}

Class Sunny_Demo_Purger {

	 // The URL of the API
	private static $CLOUDFLARE_API_ENDPOINT = 'https://www.cloudflare.com/api_json.html';

	/**
	 * Purge single file in CloudFlare's cache by making `zone_file_purge` calls
	 *
	 * @since    1.0.0
	 *
	 * @param    array    $url    Url to be purged
	 */
	public static function purge_cloudflare_cache_by_url( $url ) {

		$temp_domain = explode( '.', parse_url( esc_url_raw( $url ), PHP_URL_HOST ) );
		$domain = $temp_domain[count( $temp_domain )-2] . '.' . $temp_domain[count( $temp_domain )-1];


		$data = array(
					'email' => get_option( 'sunny_demo_cloudflare_email' ),
					'tkn'   => get_option( 'sunny_demo_cloudflare_api_key' ),
					'a'     => 'zone_file_purge',
					'z'     => $domain,
					'url'   => $url
					);

		 $response = wp_remote_post(
									self::$CLOUDFLARE_API_ENDPOINT,
									array(
										'body' => $data
										)
									);

		Sunny_Demo_API_Logger::write_report( $response, $url );

		return $response;

	} // end purge_cloudflare_cache_by_url

} // end Sunny_Demo_Purger