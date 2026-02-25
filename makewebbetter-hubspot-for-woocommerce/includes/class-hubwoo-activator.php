<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
 */

if ( ! class_exists( 'Hubwoo_Activator' ) ) {

	/**
	 * Fired during plugin activation.
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 *
	 * @since      1.0.0
	 * @package    makewebbetter-hubspot-for-woocommerce
	 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
	 */
	class Hubwoo_Activator {

		/**
		 * Schedule the realtime sync for HubSpot WooCommerce Integration
		 *
		 * Create a log file in the WooCommerce defined log directory
		 * and use the same for the logging purpose of our plugin.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {

			update_option( 'hubwoo_plugin_activated_time', time() );

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			global $wp_filesystem;
			if ( ! is_object( $wp_filesystem ) ) {
				WP_Filesystem();
			}
			$log_file = WC_LOG_DIR . 'hubspot-for-woocommerce-logs.log';
			if ( ! $wp_filesystem->exists( $log_file ) ) {
				$wp_filesystem->put_contents( $log_file, '', FS_CHMOD_FILE );
			}

			HubWoo_Schedulers::get_instance()->hubwoo_initate_schedulers();

			// Create log table in database.
			Hubwoo::hubwoo_create_log_table( Hubwoo::get_current_crm_name( 'slug' ) );
		}

	}
}
