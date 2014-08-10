<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Sunny_Demo
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form action="options.php" method="POST">
		<?php $plugin = Sunny_Demo::get_instance(); ?>
		<?php $plugin_slug = $plugin->get_plugin_slug(); ?>
		<?php settings_fields( 'sunny_demo_cloudflare_account_section' ); ?>
		<?php do_settings_sections( $plugin_slug ); ?>
		<?php submit_button( __('Save', $plugin_slug ) ); ?>
	</form>

	<div id="sunny_demo_url_purger" class="wrap">
		<form id="sunny_demo_url_purger_form" method="POST">
			<?php wp_nonce_field( 'sunny_demo_url_purger', 'sunny_demo_url_purger_nonce'); ?>
			<?php do_settings_sections( 'sunny_demo_url_purger_section' ); ?>
			<?php submit_button( __('Purge', $plugin_slug ), 'primary', 'sunny_demo_url_purger_button' ); ?>
		</form>
		<br class="clear">
		<div id="sunny_demo_url_purger_result" style="display: none">
			<h3 id="sunny_demo_url_purger_result_heading">URL Purger Result</h3>
			<img id="sunny_demo_url_purger_form_spinner" style="display: none" src="<?php echo admin_url(); ?>images/spinner-2x.gif">
		</div>
	</div>
</div>
