<?php
/**
 * One-time migration that re-applies the correct autoload setting to every
 * option this plugin already had sitting in wp_options before the autoload
 * pass, and deletes the handful that are no longer written or read at all.
 *
 * update_option()'s explicit third argument only takes effect on the row's
 * NEXT write -- a site that connected months ago and never re-triggers a
 * given option's write path would otherwise keep its stale autoload forever.
 * This runs once (admin_init, gated by a flag) so existing installs get the
 * benefit immediately on their next admin page load after updating, not just
 * new installs going forward.
 *
 * @package makewebbetter-hubspot-for-woocommerce
 */

if ( ! class_exists( 'Hubwoo_Autoload_Migration' ) ) {

	class Hubwoo_Autoload_Migration {

		const DONE_FLAG = 'hubwoo_autoload_migration_done';

		/**
		 * Options this plugin reads unconditionally on every request, from
		 * inside class-hubwoo.php's hook-registration bootstrap.
		 */
		private static function autoload_yes_keys() {
			return array(
				'hubwoo_pro_settings_enable',
				'hubwoo_pro_setup_completed',
				'hubwoo_ecomm_deal_enable',
				'hubwoo_checkout_form_created',
				'hubwoo_checkout_optin_enable',
				'hubwoo_registeration_optin_enable',
				'hubwoo_subs_settings_enable',
				'hubwoo_abncart_enable_addon',
				'hubwoo_abncart_guest_cart',
			);
		}

		/**
		 * Everything else this plugin writes -- read only from a specific
		 * admin screen, AJAX handler, or cron/Action-Scheduler callback.
		 */
		private static function autoload_no_keys() {
			return array(
				'hubwoo_pro_access_token',
				'hubwoo_pro_refresh_token',
				'hubwoo_pro_token_expiry',
				'hubwoo_pro_valid_client_ids_stored',
				'hubwoo_pro_oauth_success',
				'hubwoo_pro_hubspot_id',
				'hubwoo_pro_account_scopes',
				'hubwoo_connection_complete',
				'hubwoo_connection_issue',
				'hubwoo_connection_setup_established',
				'hubwoo_static_redirect',
				'hubwoo_clear_previous_options',
				'hubwoo_pro_version',
				'hubwoo_ecomm_store_id',
				'hubwoo_fields_setup_completed',
				'hubwoo_pipeline_setup_completed',
				'hubwoo_pro_lists_setup_completed',
				'hubwoo_subs_setup_completed',
				'hubwoo_pro_get_started',
				'hubwoo_greeting_displayed_setup',
				'hubwoo_onboard_user',
				'hubwoo_pro_send_suggestions',
				'hubwoo_product_property_created',
				'hubwoo_product_scope_needed',
				'hubwoo_deal_property_created',
				'hubwoo_contact_new_property_created',
				'hubwoo_newsletter_property_update',
				'hubwoo_abandoned_property_update',
				'mwb_hubwoo_guest_user_cart',
				'hubwoo_potal_pipelines',
				'hubwoo_fetched_deal_stages',
				'hubwoo-properties-created',
				'hubwoo-groups-created',
				'hubwoo-lists-created',
				'hubwoo-workflows-created',
				'hubwoo_ecomm_pipeline_created',
				'hubwoo_ecomm_pipeline_id',
				'hubwoo_ecomm_pipeline_fallback',
				'hubwoo_ecomm_deal_stage_ids',
				'hubwoo_ecomm_won_stages',
				'hubwoo_ecomm_final_mapping',
				'hubwoo_deal_multi_currency_enable',
				'hubwoo_assoc_deal_cmpy_enable',
				'hubwoo_ecomm_closedate_days',
				'hubwoo_ecomm_order_date_allow',
				'hubwoo_deals_sync_running',
				'hubwoo_deals_current_sync_count',
				'hubwoo_deals_current_sync_total',
				'hubwoo_background_process_running',
				'hubwoo_ocs_contacts_synced',
				'hubwoo_ocs_data_synced',
				'hubwoo_total_ocs_contact_need_sync',
				'hubwoo_contact_vid_update',
				'hubwoo_customers_manual_sync',
				'hubwoo_customers_role_settings',
				'hubwoo-selected-user-roles',
				'hubwoo-selected-order-status',
				'hubwoo_users_from_date',
				'hubwoo_users_upto_date',
				'hubwoo_ecomm_order_ocs_from_date',
				'hubwoo_ecomm_order_ocs_upto_date',
				'hubwoo_ecomm_order_ocs_status',
				'hubwoo_last_sync_date',
				'hubwoo_last_time_order_sync',
				'hubwoo_no_status',
				'hubwoo_abncart_timing',
				'hubwoo_abncart_delete_after',
				'hubwoo_abncart_delete_old_data',
				'hubwoo_abncart_added',
				'hubwoo_checkout_optin_label',
				'hubwoo_registeration_optin_label',
				'hubwoo_checkout_form_id',
				'hubwoo_logs_delete_after',
				'hubwoo_woo_action_schedulers_logs_delete_after',
				'hubwoo_enable_worker_scheduler',
				'hubwoo-error-api-calls',
				'hubwoo-success-api-calls',
				'hubwoo_pro_invalid_emails',
				'hubwoo_access_workflow',
				'hubwoo_hide_festive_notice',
				'hubwoo_hide_hpos_notice',
				'hubwoo_hide_rev_notice',
				'hubwoo_hubwoo_enable_log',
				'hubwoo_rfm_5',
				'hubwoo_from_rfm_4',
				'hubwoo_to_rfm_4',
				'hubwoo_from_rfm_3',
				'hubwoo_to_rfm_3',
				'hubwoo_from_rfm_2',
				'hubwoo_to_rfm_2',
				'hubwoo_rfm_1',
				'hubwoo_plugin_activated_time',
			);
		}

		/**
		 * Options that are no longer written or read anywhere -- deleted
		 * outright rather than kept around at a "correct" autoload value.
		 */
		private static function dead_keys() {
			return array(
				'hubwoo_deals_sync_total',
				'hubwoo_total_ocs_need_sync',
				'hubwoo_pro_alert_param_set',
				'hubwoo_pro_api_validation_error_message',
				'hubwoo-cron-notice-dismiss',
			);
		}

		/**
		 * Rewrites an existing option's row via delete+add rather than
		 * update_option()'s optional autoload argument, since WordPress only
		 * guarantees an unchanged value's autoload gets updated on 6.6+ --
		 * delete+add forces it on any supported version.
		 */
		private static function force_autoload( $key, $autoload ) {
			$sentinel = '__hubwoo_autoload_migration_missing__';
			$value    = get_option( $key, $sentinel );
			if ( $sentinel === $value ) {
				return; // Option doesn't exist on this site -- nothing to migrate.
			}
			delete_option( $key );
			add_option( $key, $value, '', $autoload );
		}

		public static function maybe_run() {
			if ( 'yes' === get_option( self::DONE_FLAG, 'no' ) ) {
				return;
			}

			foreach ( self::dead_keys() as $key ) {
				delete_option( $key );
			}
			foreach ( self::autoload_yes_keys() as $key ) {
				self::force_autoload( $key, true );
			}
			foreach ( self::autoload_no_keys() as $key ) {
				self::force_autoload( $key, false );
			}

			update_option( self::DONE_FLAG, 'yes', false );
		}
	}
}
