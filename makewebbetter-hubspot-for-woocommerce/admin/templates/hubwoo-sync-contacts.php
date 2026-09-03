<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/admin/templates/
 */

?>
<?php
if ( isset( $_GET['action'] ) && 'hubwoo-osc-schedule-sync' == $_GET['action'] ) {
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'hubwoo_osc_schedule_sync' ) ) {
		wp_die();
	}
	Hubwoo_Admin::hubwoo_schedule_sync_listener( true );
}
?>
<div class="hubwoo-m-wrap-c">

	<form action="" method="post" id="hubwoo-ocs-form">
		<?php
		if ( empty( get_option( 'hubwoo_customers_role_settings', array() ) ) ) {
			update_option( 'hubwoo_customers_role_settings', array_keys( Hubwoo_Admin::get_all_user_roles() ), false );
		}
			woocommerce_admin_fields( Hubwoo_Admin::hubwoo_customers_sync_settings() );
		?>
		<div>
			<div class="hubwoo-user-notice" style="margin-bottom: 20px;">
				<span class="hubwoo-ocs-btn-notice"><?php esc_html_e( 'Fetching all of the recently updated and un-synced users / orders', 'makewebbetter-hubspot-for-woocommerce' ); ?></span> <span id='hubwoo-usr-spin' class="fa fa-spin fa-spinner"></span>
			</div>
			<a href="<?php echo esc_url( wp_nonce_url( '?page=hubwoo&hubwoo_tab=hubwoo-sync-contacts&action=hubwoo-osc-schedule-sync', 'hubwoo_osc_schedule_sync' ) ); ?>" id = "hubwoo-osc-schedule-sync" style="display: none;" class="hubwoo-osc-schedule-sync hubwoo__btn"><?php esc_html_e( 'Schedule Sync', 'makewebbetter-hubspot-for-woocommerce' ); ?></a>
		</div>
	</form>
</div>
