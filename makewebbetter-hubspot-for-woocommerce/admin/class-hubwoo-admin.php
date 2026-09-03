<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/admin
 */
class Hubwoo_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// let's modularize our codebase, all the admin actions in one function.
		$this->admin_actions();
	}

	/**
	 * All admin actions.
	 *
	 * @since 1.0.0
	 */
	public function admin_actions()
	{

		// add submenu hubspot in woocommerce top menu.
		add_action('admin_menu', array(&$this, 'add_hubwoo_submenu'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		$screen = get_current_screen();

		if (isset($screen->id) && 'woocommerce_page_hubwoo' === $screen->id) {
			wp_enqueue_style('selectWoo');
		}

		if (isset($screen->id) && 'woocommerce_page_hubwoo' === $screen->id || 'edit-shop_order' === $screen->id || 'woocommerce_page_wc-orders' === $screen->id) {

			wp_enqueue_style('hubwoo-admin-style', plugin_dir_url(__FILE__) . 'css/hubwoo-admin.css', array(), $this->version, 'all');
			wp_register_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION);
			wp_enqueue_style('woocommerce_admin_menu_styles');
			wp_enqueue_style('woocommerce_admin_styles');
			wp_enqueue_style('hubwoo_jquery_ui', plugin_dir_url(__FILE__) . 'css/jquery-ui.css', array(), $this->version);
		}
		// deactivation screen
		wp_enqueue_style('hubwoo-admin-global-style', plugin_dir_url(__FILE__) . 'css/hubwoo-admin-global.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		$screen = get_current_screen();

		if (isset($screen->id) && 'woocommerce_page_hubwoo' === $screen->id) {
			wp_enqueue_script('selectWoo');
		}

		if (isset($screen->id) && 'woocommerce_page_hubwoo' === $screen->id || 'edit-shop_order' === $screen->id || 'woocommerce_page_wc-orders' === $screen->id) {

			wp_register_script('woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array('jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'wc-enhanced-select'), WC_VERSION, true);
			wp_register_script('jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.js', array('jquery'), WC_VERSION, true);

			$locale            = localeconv();
			$decimal           = isset($locale['decimal_point']) ? $locale['decimal_point'] : '.';
			$decimal_seperator = wc_get_price_decimal_separator();
			$params            = array(
				/* translators: %s: decimal */
				'i18n_decimal_error'               => sprintf(esc_html__('Please enter in decimal (%s) format without thousand separators.', 'makewebbetter-hubspot-for-woocommerce'), $decimal),
				/* translators: %s: decimal_separator */
				'i18n_mon_decimal_error'           => sprintf(esc_html__('Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'makewebbetter-hubspot-for-woocommerce'), $decimal_seperator),
				'i18n_country_iso_error'           => esc_html__('Please enter in country code with two capital letters.', 'makewebbetter-hubspot-for-woocommerce'),
				'i18_sale_less_than_regular_error' => esc_html__('Please enter in a value less than the regular price.', 'makewebbetter-hubspot-for-woocommerce'),
				'decimal_point'                    => $decimal,
				'mon_decimal_point'                => $decimal_seperator,
				'strings'                          => array(
					'import_products' => esc_html__('Import', 'makewebbetter-hubspot-for-woocommerce'),
					'export_products' => esc_html__('Export', 'makewebbetter-hubspot-for-woocommerce'),
				),
				'urls'                             => array(
					'import_products' => esc_url_raw(admin_url('edit.php?post_type=product&page=product_importer')),
					'export_products' => esc_url_raw(admin_url('edit.php?post_type=product&page=product_exporter')),
				),
			);
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('hubwoo-datatables', plugin_dir_url(__FILE__) . 'js/datatable.js', array('jquery'), $this->version, false);
			wp_localize_script('woocommerce_admin', 'woocommerce_admin', $params);
			wp_enqueue_script('woocommerce_admin');
			wp_register_script('hubwoo_admin_script', plugin_dir_url(__FILE__) . 'js/hubwoo-admin.js', array('jquery', 'hubwoo-datatables'), $this->version, true);
			wp_localize_script(
				'hubwoo_admin_script',
				'hubwooi18n',
				array(
					'ajaxUrl'               => admin_url('admin-ajax.php'),
					'hubwooSecurity'        => wp_create_nonce('hubwoo_security'),
					'hubwooWentWrong'       => esc_html__('Something went wrong, please try again later!', 'makewebbetter-hubspot-for-woocommerce'),
					'hubwooSuccess'         => esc_html__('Setup is completed successfully!', 'makewebbetter-hubspot-for-woocommerce'),
					'hubwooMailFailure'     => esc_html__('Mail not sent', 'makewebbetter-hubspot-for-woocommerce'),
					'hubwooMailSuccess'     => esc_html__('Mail Sent Successfully. We will get back to you soon.', 'makewebbetter-hubspot-for-woocommerce'),
					'hubwooAccountSwitch'   => esc_html__('Want to continue to switch to new HubSpot account? This cannot be reverted and will require running the whole setup again.', 'makewebbetter-hubspot-for-woocommerce'),
					'hubwooRollback'        => esc_html__('Doing rollback will require running the whole setup again. Continue?', 'makewebbetter-hubspot-for-woocommerce'),
					'hubwooOverviewTab'     => admin_url() . 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-overview',
					'hubwooNoListsSelected' => esc_html__('Please select a list to proceed', 'makewebbetter-hubspot-for-woocommerce'),
					'hubwooOcsSuccess'      => esc_html__('Congratulations !! Your data has been synced successfully.', 'makewebbetter-hubspot-for-woocommerce'),
					'hubwooOcsError'        => esc_html__('Something went wrong, Please check the error log and try re-sync your data.', 'makewebbetter-hubspot-for-woocommerce'),
				)
			);
			wp_enqueue_script('hubwoo_admin_script');
		}
		// deactivation screen.
		wp_enqueue_script('crm-connect-hubspot-sdk', '//js.hsforms.net/forms/shell.js', array(), $this->version, false);
		wp_register_script('hubwoo_admin_global_script', plugin_dir_url(__FILE__) . 'js/hubwoo-admin-global.js', array('jquery'), $this->version, true);
		wp_localize_script(
			'hubwoo_admin_global_script',
			'hubwooi18n',
			array(
				'ajaxUrl'               => admin_url('admin-ajax.php'),
				'hubwooSecurity'        => wp_create_nonce('hubwoo_security'),
			)
		);
		wp_enqueue_script('hubwoo_admin_global_script');
	}

	/**
	 * Add hubspot submenu in woocommerce menu..
	 *
	 * @since 1.0.0
	 */
	public function add_hubwoo_submenu()
	{

		add_submenu_page('woocommerce', esc_html__('HubSpot', 'makewebbetter-hubspot-for-woocommerce'), esc_html__('HubSpot', 'makewebbetter-hubspot-for-woocommerce'), 'manage_woocommerce', 'hubwoo', array(&$this, 'hubwoo_configurations'));
	}

	/**
	 * All the configuration related fields and settings.
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_configurations()
	{

		include_once HUBWOO_ABSPATH . 'admin/templates/hubwoo-main-template.php';
	}

	/**
	 * Generating access token.
	 *
	 * @since    1.0.0
	 */
	public function hubwoo_redirect_from_hubspot()
	{

		if (isset($_GET['code'])) {

			// TEMPORARY DIAGNOSTIC -- no effect on behavior below, safe to
			// remove once we've seen what a real connection attempt actually
			// sends. This OAuth callback is only ever reached via a redirect
			// from the external auth.makewebbetter.com proxy (not directly
			// from HubSpot), and it's not currently known whether that proxy
			// forwards 'state' as a literal parameter, or forwards the
			// 'mwb_nonce' this plugin actually embeds in the value it sent as
			// 'state' to HubSpot (that value is never verified anywhere in
			// this codebase otherwise) -- fixing the nonce-check gap flagged
			// in the security audit needs to be grounded in what really
			// arrives here, not a guess, since a wrong guess risks breaking
			// the connection flow entirely. Logged via the plugin's own
			// existing Sync Logs mechanism so it shows up in a screen already
			// in place, already properly access-controlled.
			$hubwoo_oauth_diagnostic_params = array();
			foreach ($_GET as $hubwoo_oauth_diag_key => $hubwoo_oauth_diag_value) {
				$hubwoo_oauth_diagnostic_params[sanitize_key($hubwoo_oauth_diag_key)] = is_scalar($hubwoo_oauth_diag_value)
					? sanitize_text_field(wp_unslash($hubwoo_oauth_diag_value))
					: '(non-scalar value)';
			}
			HubWooConnectionMananager::get_instance()->create_log(
				'OAuth callback diagnostic -- parameters actually received (temporary, safe to remove)',
				'hubwoo_redirect_from_hubspot',
				array(
					'status_code' => 0,
					'response'    => 'diagnostic',
					'body'        => wp_json_encode($hubwoo_oauth_diagnostic_params),
				),
				'oauth_diagnostic'
			);

			if (isset($_GET['mwb_source']) && $_GET['mwb_source'] == 'hubspot') {
				if (! isset($_GET['state']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['state'])), 'hubwoo_security')) {
					wp_die('The state is not correct from HubSpot Server. Try again.');
				}
			}

			$hapikey = HUBWOO_CLIENT_ID;
			$hseckey = HUBWOO_SECRET_ID;

			if ($hapikey && $hseckey) {

				if (HubWooConnectionMananager::get_instance()->hubwoo_fetch_access_token_from_code($hapikey, $hseckey) == true) {
					$hubwoo_connection_complete = 'yes';
					update_option('hubwoo_static_redirect', 'yes', false);
				} else {
					$hubwoo_connection_complete = 'no';
				}

				global $hubwoo;
				$hubwoo->hubwoo_owners_email_info();
				if ('yes' == $hubwoo_connection_complete) {
					update_option('hubwoo_connection_complete', 'yes', false);
					delete_option('hubwoo_connection_issue');
					wp_safe_redirect(admin_url() . 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-overview&hubwoo_key=grp-pr-setup');
					exit;
				} else {
					update_option('hubwoo_connection_complete', 'no', false);
					update_option('hubwoo_connection_issue', 'yes', false);
					wp_safe_redirect(admin_url() . 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-overview&hubwoo_key=connection-setup');
					exit;
				}
			}
		} elseif (! empty($_GET['hubwoo_download'])) {
			// admin_init fires on every wp-admin page load for any logged-in
			// user, regardless of role -- this branch streams the full sync
			// log (customer/order/deal API payloads, store-wide), so it needs
			// its own explicit checks rather than relying on which admin page
			// happens to link to it.
			if (! current_user_can('manage_woocommerce')) {
				wp_die();
			}
			if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'hubwoo_download_log')) {
				wp_die();
			}

			// Download log file dynamically without timeouts.
			if ( function_exists( 'set_time_limit' ) ) {
				set_time_limit( 0 );
			}

			$crm_name = Hubwoo::get_current_crm_name('slug');
			$date_suffix = gmdate('Y-m-d_H-i');
			$filename = $crm_name . '-sync-log-' . $date_suffix . '.log';
			
			header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
			
			// Flush headers so download starts immediately in the browser
			if ( ob_get_level() ) { 
				ob_end_clean(); 
			}
			flush();

			$chunk_size = 500;
			$offset = 0;
			$search_value = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : false;

			do {
				$log_data = Hubwoo::hubwoo_get_log_data( $search_value, $chunk_size, $offset );

				foreach ( $log_data as $key => $value ) {
					$value[ $crm_name . '_id' ] = ! empty( $value[ $crm_name . '_id' ] ) ? $value[ $crm_name . '_id' ] : '-';
					$log                        = 'Feed : ' . $value['event'] . PHP_EOL;
					$log                       .= ucwords( $crm_name ) . ' Object : ' . $value[ $crm_name . '_object' ] . PHP_EOL;
					$log                       .= 'Time : ' . gmdate( 'd-m-Y h:i A', esc_html( $value['time'] ) ) . PHP_EOL;
					$log                       .= 'Request : ' . $value['request'] . PHP_EOL; // phpcs:ignore
					$log                       .= 'Response : ' . wp_json_encode( maybe_unserialize( $value['response'] ) ) . PHP_EOL;  // phpcs:ignore
					$log                       .= '-----------------------------------------------------------------------' . PHP_EOL;
					
					echo $log;
				}
				
				flush(); // Push the chunk to the browser to keep connection alive
				$offset += $chunk_size;
			} while ( count( $log_data ) === $chunk_size );

			exit;
		}
	}



	/**
	 * WooCommerce privacy policy
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_pro_add_privacy_message()
	{

		if (function_exists('wp_add_privacy_policy_content')) {

			$content = '<p>' . esc_html__('We use your email to send your Orders related data over HubSpot.', 'makewebbetter-hubspot-for-woocommerce') . '</p>';

			$content .= '<p>' . esc_html__('HubSpot is an inbound marketing and sales platform that helps companies attract visitors, convert leads, and close customers.', 'makewebbetter-hubspot-for-woocommerce') . '</p>';

			$content .= '<p>' . esc_html__('Please see the ', 'makewebbetter-hubspot-for-woocommerce') . '<a href="https://www.hubspot.com/data-privacy/gdpr" target="_blank" >' . esc_html__('HubSpot Data Privacy', 'makewebbetter-hubspot-for-woocommerce') . '</a>' . esc_html__(' for more details.', 'makewebbetter-hubspot-for-woocommerce') . '</p>';

			if ($content) {

				wp_add_privacy_policy_content(esc_html__('MWB HubSpot for WooCommerce', 'makewebbetter-hubspot-for-woocommerce'), $content);
			}
		}
	}

	/**
	 * General setting tab fields for hubwoo old customers sync
	 *
	 * @return array  woocommerce_admin_fields acceptable fields in array.
	 * @since 1.0.0
	 */
	public static function hubwoo_customers_sync_settings()
	{

		$settings = array();

		if (! function_exists('get_editable_roles')) {

			require_once ABSPATH . 'wp-admin/includes/user.php';
		}

		$existing_user_roles = self::get_all_user_roles();

		$settings[] = array(
			'title' => esc_html__('Export your users and customers to HubSpot', 'makewebbetter-hubspot-for-woocommerce'),
			'id'    => 'hubwoo_customers_settings_title',
			'type'  => 'title',
		);

		$settings[] = array(
			'title'             => esc_html__('Select user role', 'makewebbetter-hubspot-for-woocommerce'),
			'id'                => 'hubwoo_customers_role_settings',
			'type'              => 'multiselect',
			'desc'              => esc_html__('Select a user role from the dropdown. Default will be all user roles.', 'makewebbetter-hubspot-for-woocommerce'),
			'options'           => $existing_user_roles,
			'desc_tip'          => true,
			'class'             => 'hubwoo-ocs-input-change',
			'custom_attributes' => array(
				'data-keytype' => esc_html__('user-role', 'makewebbetter-hubspot-for-woocommerce'),
			),
		);

		$settings[] = array(
			'title' => esc_html__('Select a time period', 'makewebbetter-hubspot-for-woocommerce'),
			'id'    => 'hubwoo_customers_manual_sync',
			'class' => 'hubwoo-ocs-input-change',
			'type'  => 'checkbox',
			'desc'  => esc_html__('Date range for user / order sync', 'makewebbetter-hubspot-for-woocommerce'),
		);

		$settings[] = array(
			'title'             => esc_html__('Users registered from date', 'makewebbetter-hubspot-for-woocommerce'),
			'id'                => 'hubwoo_users_from_date',
			'type'              => 'text',
			'placeholder'       => 'dd-mm-yyyy',
			'default'           => gmdate('d-m-Y'),
			'desc'              => esc_html__('From which date you want to sync the users, select that', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip'          => true,
			'class'             => 'date-picker hubwoo-date-range hubwoo-ocs-input-change',
			'custom_attributes' => array(
				'data-keytype' => esc_html__('from-date', 'makewebbetter-hubspot-for-woocommerce'),
			),
		);
		$settings[] = array(
			'title'             => esc_html__('Users registered upto date', 'makewebbetter-hubspot-for-woocommerce'),
			'id'                => 'hubwoo_users_upto_date',
			'type'              => 'text',
			'default'           => gmdate('d-m-Y'),
			'placeholder'       => esc_html__('dd-mm-yyyy', 'makewebbetter-hubspot-for-woocommerce'),
			'desc'              => esc_html__('Upto which date you want to sync the users, select that date', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip'          => true,
			'class'             => 'date-picker hubwoo-date-range hubwoo-ocs-input-change',
			'custom_attributes' => array(
				'data-keytype' => esc_html__('upto-date', 'makewebbetter-hubspot-for-woocommerce'),
			),
		);
		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'hubwoo_customers_settings_end',
		);
		return $settings;
	}

	/**
	 * Get all WordPress user roles in formatted way
	 *
	 * @return array  $existing_user_roles user roles in an array.
	 * @since 1.0.0
	 */
	public static function get_all_user_roles()
	{

		$existing_user_roles = array();

		global $wp_roles;

		$user_roles = ! empty($wp_roles->role_names) ? $wp_roles->role_names : array();

		if (is_array($user_roles) && count($user_roles)) {

			foreach ($user_roles as $role => $role_info) {

				$role_label = ! empty($role_info) ? $role_info : $role;

				$existing_user_roles[$role] = $role_label;
			}
			$existing_user_roles['guest_user'] = 'Guest User';
		}

		return $existing_user_roles;
	}

	/**
	 * Check if the user has cart as abandoned.
	 *
	 * @param array $properties array of contact properties.
	 * @return bool  $flag true/false.
	 * @since 1.0.0
	 */
	public static function hubwoo_check_for_cart($properties)
	{

		$flag = false;

		if (! empty($properties) && is_array($properties)) {
			$key = array_search('current_abandoned_cart', array_column($properties, 'property'));
			if (false !== $key) {
				$value = $properties[$key]['value'];
				$flag  = 'yes' === $value ? true : false;
			}
		}

		return $flag;
	}


	/**
	 * Check if the key in properties contains specific values
	 *
	 * @since 1.0.0
	 * @param string $key key name to compare.
	 * @param string $value value of the property to check.
	 * @param array  $properties array of contact properties.
	 * @return bool  $flag true/false.
	 */
	public static function hubwoo_check_for_properties($key, $value, $properties)
	{
		$flag = false;

		if (is_array($properties)) {

			$prop_index = array_search($key, array_column($properties, 'property'));

			if (array_key_exists($prop_index, $properties)) {
				$property_value = $properties[$prop_index]['value'];
				$flag           = $property_value == $value ? true : false;
			}
		}

		return $flag;
	}

	/**
	 * Unset the workflow/ROI properties to avoid update on data sync.
	 *
	 * @since    1.0.0
	 * @param    array $properties      contact properties data.
	 */
	public function hubwoo_reset_workflow_properties($properties)
	{

		$workflow_properties = HubWooContactProperties::get_instance()->_get('properties', 'roi_tracking');
		if (is_array($workflow_properties) && count($workflow_properties)) {
			foreach ($workflow_properties as $single_property) {
				$group_name = isset($single_property['name']) ? $single_property['name'] : '';
				if (! empty($group_name)) {
					if ('customer_new_order' !== $group_name) {
						$properties = self::hubwoo_unset_property($properties, $group_name);
					}
				}
			}
		}
		return $properties;
	}

	/**
	 * Unset the key from array of properties.
	 *
	 * @since    1.0.0
	 * @param    array $properties      contact properties data.
	 * @param    array $key             key to unset.
	 */
	public static function hubwoo_unset_property($properties, $key)
	{

		if (! empty($properties) && ! empty($key)) {
			if (array_key_exists($key, $properties)) {
				unset($properties[$key]);
			}
		}
		return $properties;
	}

	/**
	 * Getting next execution for realtime cron, priorities task for old users.
	 *
	 * @since 1.2.9
	 */
	public function hubwoo_review_notice()
	{

		if ('yes' != get_option('hubwoo_hide_rev_notice', 'no') && 'yes' == get_option('hubwoo_onboard_user', 'no')) {
?>
			<style type="text/css">
				.hubwoo-btn--notific {
					display: inline-block;
					text-decoration: none;
					background: #ff7a59;
					color: #fff !important;
					font-size: 11px;
					font-weight: 700;
					letter-spacing: 1px;
					border-radius: 3px;
					box-shadow: none !important;
					border: 2px solid #ff7a59;
					padding: 5px;
					margin-right: 5px;
					cursor: pointer;
				}

				.hubwoo-pl-notice {
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 5px 0px 5px 10px;
				}

				.hubwoo-wrapper-notice {
					justify-content: space-between;
				}

				.hubwoo-close-size {
					position: static;
					float: right;
					top: 0;
					right: 0;
					padding: 0px 15px 7px 28px;
					margin-top: -10px;
					font-size: 13px;
					line-height: 1.23076923;
					text-decoration: none;
					background: 0 0;
					color: #787c82;
					cursor: pointer;
				}

				.hubwoo-close-size::before {
					position: relative;
					top: 18px;
					left: -20px;
					transition: all .1s ease-in-out;
					background: 0 0;
					color: #787c82;
					content: "\f153";
					display: block;
					font: normal 16px/20px dashicons;
					height: 20px;
					text-align: center;
					width: 20px;
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;

				}
			</style>

		<?php
		}
		if ('yes' != get_option('hubwoo_connection_complete', 'no')) {
		?>
			<style>
				.hubwoo-deactive-notice-wrapper button {
					margin: 0.5em 0;
				}
			</style>
			<div class="notice notice-warning is-dismissible hubwoo-deactive-notice-wrapper">
				<div class="hubwoo-ocs-options hubwoo-pl-notice">
					<p>
						<strong><?php echo esc_html__('The HubSpot for WooCommerce plugin isn’t connected right now. To start sync store data to HubSpot ', 'makewebbetter-hubspot-for-woocommerce'); ?></strong>
						<a href="admin.php?page=hubwoo"><?php echo esc_html__('connect the plugin now.', 'makewebbetter-hubspot-for-woocommerce'); ?></a>
					</p>
				</div>
			</div>
		<?php
		}
	}

	/**
	 * Getting next execution for realtime cron, priorities task for old users.
	 *
	 * @since 1.2.9
	 */
	public function hubwoo_festive_notice()
	{

		if ('yes' === get_option('hubwoo_hide_festive_notice', 'no')) {
			return;
		}
		?>
		<style>
			.mwb-hswoo-festive-notice {
				padding: 0;
			}

			.mwb-hswoo-notice-inner {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 16px;
				padding: 10px 16px;
				flex-wrap: wrap;
			}

			.mwb-hswoo-notice-content {
				margin: 0;
				font-size: 14px;
				line-height: 1.4;
				color: #1d2327;
			}

			.mwb-hswoo-notice-actions {
				display: flex;
				align-items: center;
				gap: 12px;
				white-space: nowrap;
			}

			.mwb-hswoo-cta-btn {
				display: inline-flex;
				align-items: center;
				gap: 6px;
				padding: 8px 14px;
				background-color: #ffeb3b;
				color: #ff9800;
				font-size: 13px;
				font-weight: 600;
				text-decoration: none;
				border-radius: 4px;
				border: 1px solid #f0d000;
				transition: all 0.2s ease;
			}

			.mwb-hswoo-cta-btn img {
				width: 14px;
				height: 14px;
				filter: brightness(0) saturate(100%) invert(56%) sepia(96%) saturate(749%) hue-rotate(360deg);
			}

			.mwb-hswoo-cta-btn:hover,
			.mwb-hswoo-cta-btn:focus {
				background-color: #ff9800;
				color: #ffeb3b;
				border-color: #ff9800;
			}

			.mwb-hswoo-cta-btn:hover img,
			.mwb-hswoo-cta-btn:focus img {
				filter: brightness(0) saturate(100%) invert(91%) sepia(88%) saturate(1000%) hue-rotate(2deg);
			}

			.mwb-hubwoo-close-size {
				background: none;
				border: none;
				cursor: pointer;
				padding: 6px;
				color: #787c82;
			}

			.mwb-hubwoo-close-size::before {
				content: "\f153";
				font-family: dashicons;
				font-size: 18px;
				line-height: 1;
			}

			.mwb-hubwoo-close-size:hover,
			.mwb-hubwoo-close-size:focus {
				color: #d63638;
			}

			@media (max-width: 782px) {
				.mwb-hswoo-notice-inner {
					flex-direction: column;
					align-items: flex-start;
				}
			}
		</style>

		<div class="notice notice-warning mwb-hswoo-festive-notice">
			<div class="mwb-hswoo-notice-inner">

				<p class="mwb-hswoo-notice-content">
					<?php esc_html_e(
						'Make it count! Turn festive momentum into New Year readiness with a ',
						'makewebbetter-hubspot-for-woocommerce'
					); ?>
					<strong>
						<?php esc_html_e(
							'FREE HubSpot Audit and expert CRM setup.',
							'makewebbetter-hubspot-for-woocommerce'
						); ?>
					</strong>
				</p>

				<div class="mwb-hswoo-notice-actions">
					<a
						href="https://makewebbetter.com/festive-offer/?utm_source=hswoo-org&utm_medium=plugin-backend&utm_campaign=festive-offer2025"
						target="_blank"
						rel="noopener noreferrer"
						class="mwb-hswoo-cta-btn">
						<?php esc_html_e('Claim Now', 'makewebbetter-hubspot-for-woocommerce'); ?>
						<img
							src="<?php echo esc_url(HUBWOO_URL . 'admin/images/festive-congratulations.png'); ?>"
							alt="" />
					</a>

					<button
						type="button"
						class="mwb-hubwoo-close-size hubwoo-hide-festive-notice"
						aria-label="<?php esc_attr_e('Dismiss notice', 'makewebbetter-hubspot-for-woocommerce'); ?>">
					</button>

				</div>

			</div>
		</div>
	<?php
	}








	/**
	 * Notice for the HPOS addon.
	 *
	 * @since 1.5.3
	 */
	public function hubwoo_hpos_notice()
	{

		$activated_plugins    = get_option('active_plugins', array());
		if ((in_array('hubspot-woocommerce-hpos-compatibility/hubspot-woocommerce-hpos-compatibility.php', $activated_plugins, true) || true == get_option('hubwoo_hpos_license_check', 0)) || 'yes' == get_option('hubwoo_hide_hpos_notice', 'no')) {
			return;
		}

	?>
		<style type="text/css">
			.hubwoo-btn--notific {
				display: inline-block;
				padding: 13px 30px;
				text-decoration: none;
				background: #ff7a59;
				color: #fff !important;
				font-size: 11px;
				font-weight: 700;
				letter-spacing: 1px;
				border-radius: 3px;
				box-shadow: none !important;
				border: 2px solid #ff7a59;
				padding: 5px;
			}

			.hubwoo-pl-notice {
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 5px 0px 5px 10px;
			}

			.hubwoo-wrapper-notice {
				justify-content: space-between;
			}

			.hubwoo-close-size {
				position: static;
				float: right;
				top: 0;
				right: 0;
				padding: 0px 15px 7px 28px;
				margin-top: -10px;
				font-size: 13px;
				line-height: 1.23076923;
				text-decoration: none;
				background: 0 0;
				color: #787c82;
				cursor: pointer;
			}

			.hubwoo-close-size::before {
				position: relative;
				top: 18px;
				left: -20px;
				transition: all .1s ease-in-out;
				background: 0 0;
				color: #787c82;
				content: "\f153";
				display: block;
				font: normal 16px/20px dashicons;
				height: 20px;
				text-align: center;
				width: 20px;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;

			}
		</style>
		<?php
		if ('yes' == get_option('woocommerce_custom_orders_table_enabled', 'no')) {
		?>
			<div class="notice notice-warning hubwoo-hpos-notice-wrapper">
				<div class="hubwoo-ocs-options hubwoo-pl-notice">
					<p>
						<strong><?php echo esc_html__("We noticed you're using WooCommerce HPOS. To ensure compatibility with the HubSpot WooCommerce plugin, please install the HPOS Addon.", 'makewebbetter-hubspot-for-woocommerce'); ?></strong>
					</p>
					<div class="hubwoo-wrapper-notice">
						<a target="_blank" href="https://makewebbetter.com/product/hubspot-woocommerce-hpos-compatibility/?utm_source=MWB-HubspotFree-backend&utm_medium=MWB-backend&utm_campaign=backend" class="hubwoo-btn--notific"><?php esc_html_e('Buy Now', 'makewebbetter-hubspot-for-woocommerce'); ?></a>
						<a class="hubwoo-close-size hubwoo-hide-hpos-notice fa fa-times"></a>
					</div>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Updating users/orders list to be updated on hubspot on order status transition.
	 *
	 * @since 1.0.0
	 * @param int $order_id order id.
	 */
	public function hubwoo_update_order_changes($order_id)
	{
		if (! empty($order_id)) {
			$order = wc_get_order($order_id);

			if ( $order instanceof WC_Order && 'checkout-draft' === $order->get_status() && 'store-api' === $order->get_created_via() ) {
				return;
			}

			$user_id = (int) $order->get_customer_id();

			if (0 !== $user_id && 0 < $user_id) {
				update_user_meta($user_id, 'hubwoo_pro_user_data_change', 'yes');
			} else {
				Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_pro_guest_order', 'yes');
			}

			$upsert_meta = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_ecomm_deal_upsert', 'no');
			if ($upsert_meta == 'yes') {
				return;
			}

			if ('shop_subscription' == $order->get_type()) {
				return;
			}

			// A previous version bailed out here whenever HPOS was primary AND
			// "Enable compatibility mode" was on -- that combination is WooCommerce's
			// own recommended post-migration configuration, so this silently stopped
			// every order status change from ever flagging the order for HubSpot
			// deal sync, for any store in that state (licensed or not). Removed: the
			// upsert_meta re-entrancy check a few lines above already prevents this
			// from firing twice for the same pending order, and
			// hubwoo_hpos_update_meta_data() below already writes to whichever store
			// is actually primary (Hubwoo::hubwoo_is_hpos_enabled()) regardless of
			// compatibility mode -- there was nothing left for this check to protect
			// against.
			Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_upsert', 'yes');

			HubWoo_Schedulers::get_instance()->hubwoo_trigger_heartbeat();
		}
	}

	/**
	 * Updating users list to be updated on hubspot when admin changes the role forcefully.
	 *
	 * @since 1.0.0
	 * @param int $user_id user id.
	 */
	public function hubwoo_add_user_toupdate($user_id)
	{

		if (! empty($user_id)) {
			update_user_meta($user_id, 'hubwoo_pro_user_data_change', 'yes');
		}
	}

	/**
	 * New active groups for subscriptions.
	 *
	 * @since 1.0.0
	 * @param array $values list of pre-defined groups.
	 */
	public function hubwoo_subs_groups($values)
	{

		$values[] = array(
			'name'        => 'subscriptions_details',
			'label' => __('Subscriptions Details', 'makewebbetter-hubspot-for-woocommerce'),
		);

		return $values;
	}

	/**
	 * New active groups for subscriptions
	 *
	 * @since 1.0.0
	 * @param array $active_groups list of active groups.
	 */
	public function hubwoo_active_subs_groups($active_groups)
	{

		$active_groups[] = 'subscriptions_details';

		return $active_groups;
	}

	/**
	 * Cheap peek to see if there is any unsynced guest abandoned-cart entry
	 * waiting to be sent, without doing the full batch sync work.
	 *
	 * @return bool
	 */
	private static function hubwoo_guest_abncart_pending_exists()
	{
		$hubwoo_guest_cart_data = get_option('mwb_hubwoo_guest_user_cart', array());
		if (empty($hubwoo_guest_cart_data)) {
			return false;
		}

		foreach ($hubwoo_guest_cart_data as $entry) {
			if (isset($entry['sent']) && 'no' === $entry['sent']) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Cheap peek to see if any contact (registered user or guest order) or
	 * abandoned cart entry is waiting to be synced, without doing the full
	 * sync work.
	 *
	 * @return bool
	 */
	private static function hubwoo_contacts_pending_exists()
	{
		if ('yes' != get_option('hubwoo_greeting_displayed_setup', 'no')) {
			return false;
		}
		if (! function_exists('wc_get_order')) {
			return false;
		}

		$args = array(
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'hubwoo_pro_user_data_change',
					'value'   => 'yes',
					'compare' => '==',
				),
				array(
					'key'     => 'hubwoo_invalid_contact',
					'compare' => 'NOT EXISTS',
				),
			),
			'role__in' => get_option('hubwoo-selected-user-roles', array()),
			'number'   => 1,
			'fields'   => 'ID',
		);

		if (! empty(get_users($args))) {
			return true;
		}

		// HPOS is primary but the add-on isn't licensed -- everything below
		// this point is the guest-order half of the check, which needs a real
		// order query; the registered-user check above already ran and isn't
		// order-related, so it's unaffected.
		if (Hubwoo::hubwoo_hpos_orders_blocked()) {
			return false;
		}

		$guest_contact_sync_statuses = array_keys(Hubwoo::hubwoo_get_valid_order_statuses());

		if (Hubwoo::hubwoo_check_hpos_active()) {
			$query = new WC_Order_Query(array(
				'posts_per_page'      => 1,
				'post_status'         => $guest_contact_sync_statuses,
				'orderby'             => 'date',
				'order'               => 'desc',
				'return'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'post_parent'         => 0,
				'customer_id'         => 0,
				'meta_query'          => array(
					'relation' => 'AND',
					array(
						'key'     => 'hubwoo_pro_guest_order',
						'compare' => '==',
						'value'   => 'yes',
					),
					array(
						'key'     => 'hubwoo_invalid_contact',
						'compare' => 'NOT EXISTS',
					),
				),
			));

			$hubwoo_orders = $query->get_orders();
		} else {
			$query = new WP_Query();

			$hubwoo_orders = $query->query(
				array(
					'post_type'           => 'shop_order',
					'posts_per_page'      => 1,
					'post_status'         => 'any',
					'orderby'             => 'date',
					'order'               => 'desc',
					'fields'              => 'ids',
					'no_found_rows'       => true,
					'ignore_sticky_posts' => true,
					'meta_query'          => array(
						'relation' => 'AND',
						array(
							'key'     => 'hubwoo_pro_guest_order',
							'compare' => '==',
							'value'   => 'yes',
						),
						array(
							'key'     => 'hubwoo_invalid_contact',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => '_customer_user',
							'value'   => 0,
							'compare' => '==',
						),
					),
				)
			);
		}

		$hubwoo_orders = apply_filters('hubwoo_guest_orders', $hubwoo_orders);

		if (! empty($hubwoo_orders)) {
			return true;
		}

		return self::hubwoo_guest_abncart_pending_exists();
	}

	/**
	 * Whether a hubwoo_contacts_batch_sync job of this exact sync_type/user_type
	 * is still pending. as_has_scheduled_action() can only do an exact match on
	 * the full args array, and the real jobs always carry a third 'batch_id' key
	 * (a fresh random value every time) alongside sync_type/user_type -- so a
	 * 2-key lookup against it can never match a real 3-key job. This inspects
	 * each pending job's actual args directly instead, ignoring batch_id on
	 * purpose since any pending batch of the same sync_type/user_type is
	 * equivalent for throttling purposes, regardless of which specific batch it is.
	 *
	 * @param string $sync_type 'real' or 'historical'.
	 * @param string $user_type 'reg' or 'guest'.
	 * @return bool
	 */
	private function hubwoo_contacts_batch_pending($sync_type, $user_type)
	{
		$pending_actions = as_get_scheduled_actions(
			array(
				'hook'     => 'hubwoo_contacts_batch_sync',
				'status'   => 'pending',
				'per_page' => -1,
			)
		);

		foreach ($pending_actions as $pending_action) {
			$action_args = $pending_action->get_args();
			if (
				isset($action_args['sync_type'], $action_args['user_type'])
				&& $sync_type === $action_args['sync_type']
				&& $user_type === $action_args['user_type']
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Realtime sync for HubSpot CRM.
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_cron_schedule()
	{
		if ('yes' != get_option('hubwoo_greeting_displayed_setup', 'no')) {
			return;
		}
		if (! function_exists('wc_get_order')) {
			return;
		}
		if (! $this->hubwoo_contacts_batch_pending('real', 'reg')) {
			$args = array(
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'hubwoo_pro_user_data_change',
						'value'   => 'yes',
						'compare' => '==',
					),
					array(
						'key'     => 'hubwoo_invalid_contact',
						'compare' => 'NOT EXISTS',
					),
				),
				'role__in' => get_option('hubwoo-selected-user-roles', array()),
				'number' => 5,
				'fields' => 'ID',
			);
			$hubwoo_updated_user = get_users($args);
			$hubwoo_unique_users = apply_filters('hubwoo_users', $hubwoo_updated_user);
			if (! empty($hubwoo_unique_users)) {
				$hubwoo_unique_users = array_unique($hubwoo_unique_users);
				$hubwoo_unique_users = array_slice($hubwoo_unique_users, -10);
				$batch_id = bin2hex(random_bytes(16));
				update_option('hubwoo_batch_users_to_sync_' . $batch_id, $hubwoo_unique_users, false);
				as_enqueue_async_action('hubwoo_contacts_batch_sync', array('sync_type' => 'real', 'user_type' => 'reg',  'batch_id' => $batch_id));
			}
		}

		//hpos changes
		// Guest orders are only worth selecting at all if guest-user syncing is
		// actually enabled -- historical sync already gates this the same way
		// (see HubwooDataSync::hubwoo_get_all_unique_user()). Without this check,
		// hubwoo_pro_guest_order='yes' orders would get selected here regardless
		// of the setting, then silently dropped inside get_guest_sync_data_v3()
		// (which does respect it), and since nothing marks them as handled when
		// they're dropped, they'd get re-selected on every tick forever.
		// The registered-user block above is user-meta based, not order-based,
		// so it isn't gated -- only this guest-order half needs a real order
		// query, and HPOS-primary-but-not-licensed means no order query runs
		// at all.
		if (in_array('guest_user', get_option('hubwoo-selected-user-roles', array())) && ! Hubwoo::hubwoo_hpos_orders_blocked() && ! $this->hubwoo_contacts_batch_pending('real', 'guest')) {
			$guest_contact_sync_statuses = array_keys( Hubwoo::hubwoo_get_valid_order_statuses() );
			if (Hubwoo::hubwoo_check_hpos_active()) {
				$query = new WC_Order_Query(array(
					'posts_per_page'      => 5,
					'post_status'         => $guest_contact_sync_statuses,
					'orderby'             => 'date',
					'order'               => 'desc',
					'return'              => 'ids',
					'no_found_rows'       => true,
					'ignore_sticky_posts' => true,
					'post_parent'         => 0,
					'customer_id'  		  => 0,
					'meta_query'          => array(
						'relation' => 'AND',
						array(
							'key'     => 'hubwoo_pro_guest_order',
							'compare' => '==',
							'value'   => 'yes',
						),
						array(
							'key'     => 'hubwoo_invalid_contact',
							'compare' => 'NOT EXISTS',
						),
					)
				));

				$hubwoo_orders = $query->get_orders();
			} else {
				$query = new WP_Query();

				$hubwoo_orders = $query->query(
					array(
						'post_type'           => 'shop_order',
						'posts_per_page'      => 5,
						'post_status'         => 'any',
						'orderby'             => 'date',
						'order'               => 'desc',
						'fields'              => 'ids',
						'no_found_rows'       => true,
						'ignore_sticky_posts' => true,
						'meta_query'          => array(
							'relation' => 'AND',
							array(
								'key'     => 'hubwoo_pro_guest_order',
								'compare' => '==',
								'value'   => 'yes',
							),
							array(
								'key'     => 'hubwoo_invalid_contact',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_customer_user',
								'value'   => 0,
								'compare' => '==',
							),
						),
					)
				);
			}
			$hubwoo_orders = apply_filters('hubwoo_guest_orders', $hubwoo_orders);

			if (! empty($hubwoo_orders)) {
				$batch_id = bin2hex(random_bytes(16));
				update_option('hubwoo_batch_users_to_sync_' . $batch_id, $hubwoo_orders, false);
				as_enqueue_async_action('hubwoo_contacts_batch_sync', array('sync_type' => 'real', 'user_type' => 'guest',  'batch_id' => $batch_id));
			}
		}

		if (self::hubwoo_guest_abncart_pending_exists() && ! as_has_scheduled_action('hubwoo_contacts_batch_sync', array('sync_type' => 'real', 'user_type' => 'guest_abncart'))) {
			as_enqueue_async_action('hubwoo_contacts_batch_sync', array('sync_type' => 'real', 'user_type' => 'guest_abncart'));
		}
	}

	public function hubwoo_contacts_batch_sync($sync_type = '', $user_type = '', $batch_id = '')
	{
		$batch_contacts_data = array();
		$order_email_id_map = array();
		if ($user_type == 'reg') {

			$users_to_sync = get_option('hubwoo_batch_users_to_sync_' . $batch_id, array());
			if (count($users_to_sync)) {
				try {
					$batch_contacts_data = HubwooDataSync::get_sync_data_v3($users_to_sync, $sync_type);
				} catch (Throwable $e) {
					foreach ($users_to_sync as $user_id) {
						HubwooObjectProperties::hubwoo_ecomm_contacts_with_id($user_id);
					}
				}
			}
		} else if ($user_type == 'guest') {

			$users_to_sync = get_option('hubwoo_batch_users_to_sync_' . $batch_id, array());
			if (count($users_to_sync)) {
				try {
					$batch_contacts_data = HubwooDataSync::get_guest_sync_data_v3($users_to_sync, $sync_type, false);
					if (!empty($batch_contacts_data)) {
						foreach ($users_to_sync as $order_id) {
							$order = wc_get_order($order_id);
							if (!($order instanceof WC_Order)) {
								continue;
							}
							if ($order->get_status() === 'checkout-draft' && $order->get_created_via() === 'store-api') {
								continue;
							}
							$billing_email = $order->get_billing_email();
							// HubSpot matches/dedupes contacts by email case-insensitively --
							// if this email was already synced under a different case (e.g.
							// an earlier guest order typed it differently), HubSpot's response
							// echoes back its existing stored case, not this order's. Normalize
							// the key so the lookup in hubwoo_marked_users_sync() still finds
							// it regardless of case.
							$order_email_id_map[strtolower($billing_email)] = $order_id;
						}
					}
				} catch (Throwable $e) {
					foreach ($users_to_sync as $order_id) {
						HubwooObjectProperties::hubwoo_ecomm_guest_user($order_id);
					}
				}
			}
		} else if ($user_type == 'guest_abncart') {

			$hubwoo_guest_cart_data = get_option('mwb_hubwoo_guest_user_cart', array());
			if (count($hubwoo_guest_cart_data)) {
				$unsynced_guest_cart_data = array_filter($hubwoo_guest_cart_data, function ($entry) {
					return isset($entry['sent']) && $entry['sent'] === 'no';
				});

				$last_10_unsynced_cart_data = array_slice($unsynced_guest_cart_data, -10);

				if (count($last_10_unsynced_cart_data)) {
					foreach ($last_10_unsynced_cart_data as $unsynced_cart) {
						if (! empty($unsynced_cart['email'])) {
							$contact = array();
							$unsynced_cart_email = $unsynced_cart['email'];
							$unsynced_cart_key = '';
							$unsynced_cart_key = array_search($unsynced_cart_email, array_column($hubwoo_guest_cart_data, 'email'));

							// This cart was captured client-side purely by email, so it
							// carries no WP role information -- it may belong to a
							// registered user (their account email pre-fills the billing
							// field) just as easily as a true guest. Resolve that here,
							// before this entry is synced, so real-time role selection is
							// honored the same way it already is for the reg/guest paths.
							$roles_to_check_for_cart = get_option('hubwoo-selected-user-roles', array());
							$existing_user_for_cart  = get_user_by('email', $unsynced_cart_email);
							if ($existing_user_for_cart instanceof WP_User) {
								if (empty(array_intersect($existing_user_for_cart->roles, $roles_to_check_for_cart))) {
									$hubwoo_guest_cart_data[$unsynced_cart_key]['sent'] = 'yes';
									continue;
								}
							} elseif (! in_array('guest_user', $roles_to_check_for_cart)) {
								$hubwoo_guest_cart_data[$unsynced_cart_key]['sent'] = 'yes';
								continue;
							}

							// Deliberately NOT bailing out here when the cart is empty --
							// an empty cart at this point (sent='no' but no items) means
							// a real order was just placed and the cart was cleared (see
							// hubwoo_abncart_woocommerce_new_orders() /
							// hubwoo_block_checkout_order_placed()), not "nothing to
							// sync". hubwoo_abncart_process_guest_data() below correctly
							// computes a "no abandoned cart" property set (zeroed
							// counters/totals) from its own independent lookup of this
							// same email regardless of what $unsynced_cart looks like --
							// skipping it here was preventing that cleared state from
							// ever reaching HubSpot, leaving stale abandoned-cart data on
							// the contact even after they'd already ordered.
							$guest_user_properties = apply_filters('hubwoo_pro_track_guest_cart', $unsynced_cart_email, array());

							if (Hubwoo_Admin::hubwoo_check_for_cart($guest_user_properties)) {
								if (!empty($unsynced_cart_key) || $unsynced_cart_key === 0) {
									$hubwoo_guest_cart_data[$unsynced_cart_key]['sent'] = 'yes';
								}
							} elseif (! Hubwoo_Admin::hubwoo_check_for_cart($guest_user_properties) && Hubwoo_Admin::hubwoo_check_for_cart_contents($guest_user_properties)) {
								if (!empty($unsynced_cart_key) || $unsynced_cart_key === 0) {
									$hubwoo_guest_cart_data[$unsynced_cart_key]['sent'] = 'yes';
								}
							}

							$guest_user_properties = apply_filters('hubwoo_map_new_abncart_properties', $guest_user_properties, $unsynced_cart_email);

							foreach ($guest_user_properties as $key => $value) {
								$contact[$value['property']] = $value['value'];
							}
							if (! empty($guest_user_properties)) {
								$batch_contacts_data[] = array(
									'id' => $unsynced_cart_email,
									'idProperty' => 'email',
									'properties' => $contact
								);
							}
						}
					}
				}

				update_option("mwb_hubwoo_guest_user_cart", $hubwoo_guest_cart_data, false);
			}
		}

		if (!empty($batch_contacts_data)) {
			$batch_contacts_data = array('inputs' => $batch_contacts_data);

			$flag = true;
			if (Hubwoo::is_access_token_expired()) {
				$hapikey = HUBWOO_CLIENT_ID;
				$hseckey = HUBWOO_SECRET_ID;
				$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);
				if (! $status) {
					$flag = false;
				}
			}
			if ($flag) {
				$response = HubWooConnectionMananager::get_instance()->create_or_update_contacts_v3($batch_contacts_data);
				if ($response['status_code'] == 200) {
					$response_body = json_decode($response['body'], true);
					$results = $response_body['results'];
					self::hubwoo_marked_users_sync($results, $user_type, $order_email_id_map);

					// Historical background sync drives the Contacts-tab / dashboard-wide
					// progress bar off this counter (see hubwoo_sync_status_tracker()).
					// Real-time batches never set hubwoo_background_process_running, so
					// this is a no-op for them.
					if ('historical' === $sync_type && get_option('hubwoo_background_process_running', false)) {
						$hsocssynced = get_option('hubwoo_ocs_contacts_synced', 0);
						$hsocssynced += count($results);
						update_option('hubwoo_ocs_contacts_synced', $hsocssynced, false);
					}
				} else {
					self::hubwoo_handle_batch_response($response, $batch_contacts_data, $user_type, $order_email_id_map);
				}
			}
		}

		if (!empty($batch_id)) {
			delete_option('hubwoo_batch_users_to_sync_' . $batch_id);
		}
		update_option('hubwoo_last_sync_date', time(), false);
	}

	public function hubwoo_marked_users_sync($response_data, $user_type, $order_email_id_map = array())
	{
		if (count($response_data)) {
			// REGISTERED USER MAPPING
			if ($user_type == 'reg') {
				foreach ($response_data as $synced_contact) {
					if (isset($synced_contact['properties']['email']) && !empty($synced_contact['properties']['email'])) {
						$synced_user = get_user_by('email', $synced_contact['properties']['email']);
						$synced_user_id = $synced_user->ID;
						if (!empty($synced_user_id)) {
							update_user_meta($synced_user_id, 'hubwoo_user_vid', $synced_contact['id']);
							update_user_meta($synced_user_id, 'hubwoo_pro_user_data_change', 'synced');
						}
					}
				}
			}
			// GUEST USER MAPPING
			else if ($user_type == 'guest') {
				foreach ($response_data as $synced_contact) {
					if (isset($synced_contact['properties']['email']) && !empty($synced_contact['properties']['email'])) {
						// $order_email_id_map is keyed by lowercased email (see where it's
						// built in hubwoo_contacts_batch_sync()) -- HubSpot's response can
						// come back in a different case than this order's own billing email
						// if the same address was already synced under a different case.
						$synced_contact_email = strtolower($synced_contact['properties']['email']);
						if (isset($order_email_id_map[$synced_contact_email])) {
							$order_id = $order_email_id_map[$synced_contact_email];
							$order = wc_get_order($order_id);
							if ($order instanceof WC_Order) {
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_user_vid', $synced_contact['id']);
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_pro_guest_order', 'synced');
							}
						}

					}
				}
			}
		}
	}

	public function hubwoo_handle_batch_response($response, $batch_contacts_data, $user_type, $order_email_id_map = array())
	{
		if (is_array($response) && !empty($response)) {
			$status_code = isset($response['status_code']) ? (int) $response['status_code'] : 0;
			if ($status_code === 429 || $status_code >= 500) {
				return;
			}

			$batch_contacts_data = $batch_contacts_data['inputs'];
			$retry_count = 0;
			$max_retries = 3;

			foreach ($batch_contacts_data as $unsynced_contact_data) {
				if ($retry_count >= $max_retries) {
					break;
				}
				$unsynced_user_id = '';
				$unsynced_guest_user_order = NULL;
				$unsynced_user_email = $unsynced_contact_data['id'];
				$unsynced_contact_properties = $unsynced_contact_data['properties'];
				if ($user_type == 'reg') {
					$unsynced_user = get_user_by('email', $unsynced_user_email);
					if (!$unsynced_user) {
						continue;
					}
					$unsynced_user_id = $unsynced_user->ID;
				} else if ($user_type == 'guest') {
					// $order_email_id_map is keyed by lowercased email -- normalize here
					// too, defensively, for the same reason as hubwoo_marked_users_sync().
					$unsynced_user_email_key = strtolower($unsynced_user_email);
					if (!isset($order_email_id_map[$unsynced_user_email_key])) {
						continue;
					}
					$unsynced_guest_user_order_id = $order_email_id_map[$unsynced_user_email_key];
					if (!empty($unsynced_guest_user_order_id)) {
						$unsynced_guest_user_order = wc_get_order($unsynced_guest_user_order_id);
					}
				}

				if (count($unsynced_contact_properties) && (($user_type == 'reg' && !empty($unsynced_user_id)) || ($user_type == 'guest' && ($unsynced_guest_user_order instanceof WC_Order)) || $user_type == 'guest_abncart')) {
					HubwooObjectProperties::hubwoo_create_update_single_contact($unsynced_contact_properties, $unsynced_user_email, $user_type, $unsynced_user_id, $unsynced_guest_user_order);
					$retry_count++;
				}
			}
		}
	}

	/**
	 * Check if the user has empty cart
	 *
	 * @since    1.0.0
	 * @param    array $properties List of properties.
	 */
	public static function hubwoo_check_for_cart_contents($properties)
	{

		$flag = false;

		if (! empty($properties)) {

			foreach ($properties as $single_record) {

				if (! empty($single_record['property'])) {

					if ('abandoned_cart_products' == $single_record['property']) {

						if (empty($single_record['value'])) {

							$flag = true;
							break;
						}
					}
				}
			}
		}

		return $flag;
	}

	/**
	 * Populating Orders column that has been synced as deal.
	 *
	 * @since    1.0.0
	 * @param    string          $column             Column being rendered.
	 * @param    int|WC_Order    $post_id_or_order    Legacy screen passes a post ID; HPOS Orders screen passes a WC_Order object.
	 */
	public function hubwoo_order_cols_value($column, $post_id_or_order)
	{

		// Registered against both the legacy shop_order list table
		// (manage_shop_order_posts_custom_column, passes a plain post ID) and the
		// HPOS Orders screen (manage_woocommerce_page_wc-orders_custom_column,
		// passes a WC_Order object -- confirmed against WooCommerce core) --
		// normalize to a WC_Order either way so every meta read below can go
		// through the HPOS-aware helper instead of raw get_post_meta(), which
		// only ever reads wp_postmeta and silently misses anything actually
		// stored in the HPOS orders-meta table.
		$order = ( $post_id_or_order instanceof WC_Order ) ? $post_id_or_order : wc_get_order( $post_id_or_order );

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$deal_id   = Hubwoo::hubwoo_hpos_get_meta_data( $order, 'hubwoo_ecomm_deal_id', true );
		$portal_id = get_option('hubwoo_pro_hubspot_id', '');
		$user_id   = (int) $order->get_customer_id();
		$user_vid  = $user_id > 0 ? get_user_meta($user_id, 'hubwoo_user_vid', true) : Hubwoo::hubwoo_hpos_get_meta_data( $order, 'hubwoo_user_vid', true );

		if (get_option('hubwoo_background_process_running', false) || get_option('hubwoo_contact_vid_update', 0)) {
			$contact_tip    = 'Contacts are being synced';
			$contact_status = 'yes';
		} else {
			$contact_tip    = 'Sync Contacts';
			$contact_status = 'no';
		}

		if (1 == get_option('hubwoo_deals_sync_running', 0)) {
			$deal_tip    = 'Deals are being synced';
			$deal_status = 'yes';
		} else {
			$deal_tip    = 'Sync Deals';
			$deal_status = 'no';
		}

		switch ($column) {

			case 'hubwoo-deal-sync':
			?>
				<p style="text-align:center;">
					<?php
					if (! empty($user_vid)) {
					?>
						<a class="hubwoo-action-icon" data-tip="View Contact in HubSpot" data-status='yes' data-type='contact' target="_blank" href="<?php echo esc_url('https://app.hubspot.com/contacts/' . $portal_id . '/contact/' . $user_vid . '/'); ?>"><img src="<?php echo esc_url(HUBWOO_URL . 'admin/images/contact.png'); ?>"></a>
					<?php
					} else {
					?>
						<a style="opacity: 0.4;" data-sync-status='<?php echo esc_attr($contact_status); ?>' data-tip="<?php echo esc_attr($contact_tip); ?>" class="hubwoo-action-icon" data-status='no' data-type='contact' href="javascript:void"><img src="<?php echo esc_url(HUBWOO_URL . 'admin/images/contact.png'); ?>"></a>
					<?php
					}

					if (! empty($deal_id)) {
					?>
						<a class="hubwoo-action-icon" data-tip="View Deal in HubSpot" target="_blank" data-status='yes' data-type='deal' href="<?php echo esc_url('https://app.hubspot.com/contacts/' . $portal_id . '/deal/' . $deal_id . '/'); ?>"><img src="<?php echo esc_url(HUBWOO_URL . 'admin/images/deal.png'); ?>"></a>
					<?php
					} else {
					?>
						<a style="opacity: 0.4;" data-sync-status='<?php echo esc_attr($deal_status); ?>' data-tip="<?php echo esc_attr($deal_tip); ?>" class="hubwoo-action-icon" data-status='no' data-type='deal' href="javascript:void"><img src="<?php echo esc_url(HUBWOO_URL . 'admin/images/deal.png'); ?>"></a>
					<?php
					}
					?>
				</p>
<?php
				break;
		}
	}

	/**
	 * Adding custom column in orders table at backend.
	 *
	 * @since    1.0.0
	 * @param    array $columns    array of columns on orders table.
	 * @return   array    $columns    array of columns on orders table alongwith deal sync column.
	 */
	public function hubwoo_order_cols($columns)
	{

		$portal_id                   = get_option('hubwoo_pro_hubspot_id', '');
		$columns['hubwoo-deal-sync'] = __('HubSpot Actions', 'makewebbetter-hubspot-for-woocommerce');
		return $columns;
	}


	/**
	 * General setting for Abandoned Carts.
	 *
	 * @return array  woocommerce_admin_fields acceptable fields in array.
	 * @since 1.0.0
	 */
	public static function hubwoo_abncart_general_settings()
	{

		$settings = array();

		$settings[] = array(
			'title' => esc_html__('Abandoned Cart Settings', 'makewebbetter-hubspot-for-woocommerce'),
			'id'    => 'hubwoo_abncart_settings_title',
			'type'  => 'title',
			'class' => 'hubwoo-abncart-settings-title',
		);
		$settings[] = array(
			'title'   => esc_html__('Enable/Disable', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_abncart_enable_addon',
			'desc'    => esc_html__('Track Abandoned Carts', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'checkbox',
			'default' => 'yes',
		);

		$settings[] = array(
			'title'   => esc_html__('Guest Users ', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_abncart_guest_cart',
			'desc'    => esc_html__('Track Guest Abandoned Carts', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'checkbox',
			'default' => 'yes',
		);

		$settings[] = array(
			'title'   => esc_html__('Delete Old Data ', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_abncart_delete_old_data',
			'desc'    => esc_html__('Delete Abandoned Carts Data', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'checkbox',
			'default' => 'yes',
		);

		$settings[] = array(
			'title'             => esc_html__('Delete Data Timer( Days )', 'makewebbetter-hubspot-for-woocommerce'),
			'id'                => 'hubwoo_abncart_delete_after',
			'type'              => 'number',
			'desc'              => esc_html__('Specify how many days abandoned cart data should be stored. Data older than this period will be automatically deleted (min. 7days).', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip'          => true,
			'custom_attributes' => array('min' => '7'),
			'default'           => '30',
		);

		$settings[] = array(
			'title'             => esc_html__('Cart Timer( Minutes )', 'makewebbetter-hubspot-for-woocommerce'),
			'id'                => 'hubwoo_abncart_timing',
			'type'              => 'number',
			'desc'              => esc_html__('Set the timer for abandoned cart. Customers abandoned cart data will be updated over HubSpot after the specified timer. Minimum value is 5 minutes.', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip'          => true,
			'custom_attributes' => array('min' => '5'),
			'default'           => '5',
		);
		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'hubwoo_abncart_settings_end',
		);

		return apply_filters('hubwoo_abn_cart_settings', $settings);
	}

	/**
	 * Updating customer properties for abandoned cart on HubSpot.
	 *
	 * @since 1.0.0
	 * @param array $properties list of contact properties.
	 * @param int   $contact_id user ID.
	 */
	public function hubwoo_abncart_contact_properties($properties, $contact_id)
	{

		$cart_product_skus                                  = array();
		$cart_categories                                    = array();
		$cart_products                                      = array();
		$in_cart_products                                   = array();
		$abncart_properties                                 = array();
		$abncart_properties['current_abandoned_cart']       = 'no';
		$abncart_properties['abandoned_cart_date']          = '';
		$abncart_properties['abandoned_cart_counter']       = 0;
		$abncart_properties['abandoned_cart_url']           = '';
		$abncart_properties['abandoned_cart_products_skus'] = '';
		$abncart_properties['abandoned_cart_products_categories'] = '';
		$abncart_properties['abandoned_cart_products']            = '';
		$abncart_properties['abandoned_cart_tax_value']           = 0;
		$abncart_properties['abandoned_cart_subtotal']            = 0;
		$abncart_properties['abandoned_cart_total_value']         = 0;
		$abncart_properties['abandoned_cart_products_html']       = '';

		$hubwoo_abncart_timer = get_option('hubwoo_abncart_timing', 5);
		$customer_cart        = get_user_meta($contact_id, '_woocommerce_persistent_cart_' . get_current_blog_id(), true);
		$last_time            = get_user_meta($contact_id, 'hubwoo_pro_last_addtocart', true);

		if (isset($customer_cart['cart']) && ! empty($last_time)) {

			if (count($customer_cart['cart'])) {

				$current_time = time();

				$time_diff = round(abs($current_time - $last_time) / 60, 2);

				$hubwoo_abncart_timer = (int) $hubwoo_abncart_timer;

				if ($time_diff <= $hubwoo_abncart_timer) {
					return $properties;
				}
				$last_date                                 = (int) $last_time;
				$last_date                                 = HubwooGuestOrdersManager::hubwoo_set_utc_midnight($last_date);
				$abncart_properties['abandoned_cart_date'] = $last_date;
				$locale                                    = get_user_meta($contact_id, 'hubwoo_pro_cart_locale', true);
				$locale                                    = ! empty($locale) ? $locale : get_locale();
				$cart_url                                  = apply_filters('wpml_permalink', wc_get_cart_url(), $locale, true);

				$abncart_properties['current_abandoned_cart'] = 'yes';

				$cart_products = self::hubwoo_return_abncart_values($customer_cart['cart']);

				if (count($cart_products)) {

					$abncart_properties['abandoned_cart_products_html'] = self::hubwoo_abncart_product_html($cart_products);
				}
				$cart_url .= '?hubwoo-abncart-retrieve=';
				$cart_prod = array();
				foreach ($customer_cart['cart'] as $single_cart_item) {

					$item_id         = $single_cart_item['product_id'];
					$parent_item_sku = get_post_meta($item_id, '_sku', true);
					if (! empty($single_cart_item['variation_id'])) {
						$item_id = $single_cart_item['variation_id'];
					}

					$qty         = $single_cart_item['quantity'] ? $single_cart_item['quantity'] : 1;
					$cart_prod[] = $item_id . ':' . $qty;
					if (get_post_status($item_id) == 'trash' || get_post_status($item_id) == false) {

						continue;
					}

					$cart_item_sku = get_post_meta($item_id, '_sku', true);

					if (empty($cart_item_sku)) {
						$cart_item_sku = $parent_item_sku;
					}

					if (empty($cart_item_sku)) {
						$cart_item_sku = $item_id;
					}

					$cart_product_skus[] = $cart_item_sku;

					$product_cats_ids = wc_get_product_term_ids($item_id, 'product_cat');

					if (is_array($product_cats_ids) && count($product_cats_ids)) {

						foreach ($product_cats_ids as $cat_id) {

							$term              = get_term_by('id', $cat_id, 'product_cat');
							$cart_categories[] = $term->slug;
						}
					}

					$post               = get_post($item_id);
					$post_name          = isset($post->post_name) ? $post->post_name : '';
					$product_name       = $post_name . '-' . $item_id;
					$in_cart_products[] = $product_name;
					// Count actual quantity, not one per line item, so this
					// matches the guest-path definition of this same property
					// (a single product added with qty 2 must report 2, not 1).
					$abncart_properties['abandoned_cart_counter'] += $qty;
					if (array_key_exists('line_total', $single_cart_item)) {
						$abncart_properties['abandoned_cart_subtotal'] += $single_cart_item['line_total'];
						$abncart_properties['abandoned_cart_tax_value'] += isset($single_cart_item['line_tax']) ? $single_cart_item['line_tax'] : 0;
					} else {
						// A just-added item's persistent-cart snapshot is saved
						// before WC_Cart::calculate_totals() runs
						// (persistent_cart_update() is hooked to
						// woocommerce_add_to_cart at the default priority 10,
						// while calculate_totals() is hooked to the same
						// action at priority 20), so line_total/line_tax
						// aren't populated yet. Compute both the same way WC
						// itself would once totals do run, using the
						// product's own tax-inclusive/exclusive price
						// helpers, instead of silently treating tax as 0.
						$product_obj                                    = wc_get_product($item_id);
						$item_qty                                       = (int) $qty;
						$abncart_properties['abandoned_cart_subtotal'] += (float) $product_obj->get_price() * $item_qty;
						if ($product_obj instanceof WC_Product && wc_tax_enabled()) {
							$price_incl_tax = wc_get_price_including_tax($product_obj, array('qty' => $item_qty));
							$price_excl_tax = wc_get_price_excluding_tax($product_obj, array('qty' => $item_qty));
							$abncart_properties['abandoned_cart_tax_value'] += max(0, $price_incl_tax - $price_excl_tax);
						}
					}
				}

				$cart_url .= implode(',', $cart_prod);

				$abncart_properties['abandoned_cart_url']                 = $cart_url;
				$abncart_properties['abandoned_cart_products_skus']       = HubwooGuestOrdersManager::hubwoo_format_array($cart_product_skus);
				$abncart_properties['abandoned_cart_products_categories'] = HubwooGuestOrdersManager::hubwoo_format_array($cart_categories);
				$abncart_properties['abandoned_cart_products']            = HubwooGuestOrdersManager::hubwoo_format_array($in_cart_products);

				if (! empty($abncart_properties['abandoned_cart_subtotal']) || ! empty($abncart_properties['abandoned_cart_tax_value'])) {

					$abncart_properties['abandoned_cart_total_value'] = floatval($abncart_properties['abandoned_cart_subtotal'] + $abncart_properties['abandoned_cart_tax_value']);
				}
			} else {

				delete_user_meta($contact_id, 'hubwoo_pro_user_left_cart');
				delete_user_meta($contact_id, 'hubwoo_pro_last_addtocart');
				delete_user_meta($contact_id, 'hubwoo_pro_cart_locale');
				delete_user_meta($contact_id, 'hubwoo_pro_user_cart_sent');
			}
		} else {

			delete_user_meta($contact_id, 'hubwoo_pro_user_left_cart');
			delete_user_meta($contact_id, 'hubwoo_pro_last_addtocart');
			delete_user_meta($contact_id, 'hubwoo_pro_cart_locale');
			delete_user_meta($contact_id, 'hubwoo_pro_user_cart_sent');
		}

		$abandoned_property_updated = get_option('hubwoo_abandoned_property_update', 'no');

		if (! empty($abandoned_property_updated) && 'yes' == $abandoned_property_updated) {
			if ('yes' == $abncart_properties['current_abandoned_cart']) {
				$abncart_properties['current_abandoned_cart'] = true;
			} else {
				$abncart_properties['current_abandoned_cart'] = false;
			}
		}

		foreach ($abncart_properties as $property_name => $property_value) {
			if (isset($property_value)) {
				$properties[] = array(
					'property' => $property_name,
					'value'    => $property_value,
				);
			}
		}
		return $properties;
	}

	/**
	 * Preparing few parameters value for abandoned cart details.
	 *
	 * @since 1.0.0
	 * @param array $customer_cart cart contents.
	 */
	public static function hubwoo_return_abncart_values($customer_cart)
	{

		$key = 0;

		$cart_products = array();

		$cart_total   = 0;
		$cart_counter = 0;

		if (! empty($customer_cart)) {

			foreach ($customer_cart as $single_cart_item) {

				$item_id         = $single_cart_item['product_id'];
				$parent_item_img = wp_get_attachment_image_src(get_post_thumbnail_id($item_id), 'single-post-thumbnail');
				$item_img        = '';
				if (! empty($single_cart_item['variation_id'])) {
					$item_id  = $single_cart_item['variation_id'];
					$item_img = wp_get_attachment_image_src(get_post_thumbnail_id($item_id), 'single-post-thumbnail');
				}
				if (get_post_status($item_id) == 'trash' || get_post_status($item_id) == false) {

					continue;
				}
				if (empty($item_img)) {
					$item_img = $parent_item_img;
				}
				$product                          = wc_get_product($item_id);
				if ($product instanceof WC_Product) {
					$cart_products[$key]['image']   = $item_img;
					$cart_products[$key]['name']    = $product->get_name();
					$cart_products[$key]['url']     = get_permalink($item_id);
					$cart_products[$key]['price']   = $product->get_price();
					$cart_products[$key]['qty']     = $single_cart_item['quantity'];
					$cart_products[$key]['item_id'] = $item_id;
					if (array_key_exists('line_total', $single_cart_item)) {
						$cart_products[$key]['total'] = floatval($single_cart_item['line_total'] + $single_cart_item['line_tax']);
					} else {
						$product_obj                    = wc_get_product($item_id);
						$cart_products[$key]['total'] = (float) $product_obj->get_price() * (int) $single_cart_item['quantity'];
					}
					$key++;
				}
			}
		}

		return $cart_products;
	}

	/**
	 * Preparing cart HTML with products data.
	 *
	 * @since 1.0.0
	 * @param array $cart_products values for products to be used in html.
	 */
	public static function hubwoo_abncart_product_html($cart_products)
	{

		$products_html = '<div><hr></div><!--[if mso]><center><table width="100%" style="width:600px;"><![endif]--><table style="font-size: 14px; font-family: Arial, sans-serif; line-height: 20px; text-align: left; table-layout: fixed;" width="100%"><thead><tr><th style="text-align: center;word-wrap: unset;">' . __('Image', 'makewebbetter-hubspot-for-woocommerce') . '</th><th style="text-align: center;word-wrap: unset;">' . __('Item', 'makewebbetter-hubspot-for-woocommerce') . '</th><th style="text-align: center;word-wrap: unset;">' . __('Qty', 'makewebbetter-hubspot-for-woocommerce') . '</th><th style="text-align: center;word-wrap: unset;">' . __('Cost', 'makewebbetter-hubspot-for-woocommerce') . '</th><th style="text-align: center;word-wrap: unset;">' . __('Total', 'makewebbetter-hubspot-for-woocommerce') . '</th></tr></thead><tbody>';
		foreach ($cart_products as $single_product) {
			$products_html .= '<tr><td width="20" style="max-width: 100%; text-align: center;"><img height="50" width="50" src="' . $single_product['image'][0] . '"></td><td width="50" style="max-width: 100%; text-align: center; font-weight: normal;font-size: 10px;word-wrap: unset;"><a style="display: inline-block;" target="_blank" href="' . $single_product['url'] . '">' . $single_product['name'] . '</a></td><td width="10" style="max-width: 100%;text-align: center;">' . $single_product['qty'] . '</td><td width="10" style="max-width: 100%;text-align: center; font-size: 10px;">' . wc_price($single_product['price'], array('currency' => get_option('woocommerce_currency'))) . '</td><td width="10" style="max-width: 100%;text-align: center; font-size: 10px;">' . wc_price($single_product['total'], array('currency' => get_option('woocommerce_currency'))) . '</td></tr>';
		}
		$products_html .= '</tbody></table><!--[if mso]></table></center><![endif]--><div><hr></div>';

		return apply_filters('hubwoo_abandoned_cart_html', $products_html, $cart_products);
	}

	/**
	 * Preparing guest user data for HubSpot.
	 *
	 * @since 1.0.0
	 * @param string $email user email.
	 * @param array  $properties list of properties.
	 */
	public function hubwoo_abncart_process_guest_data($email, $properties = array())
	{

		if (! empty($email)) {
			$cart_product_skus    = array();
			$cart_product_qty     = 0;
			$cart_subtotal        = 0;
			$cart_total           = 0;
			$cart_tax             = 0;
			$last_date            = '';
			$cart_url             = '';
			$cart_status          = 'no';
			$cart_categories      = array();
			$cart_products        = array();
			$hubwoo_abncart_timer = get_option('hubwoo_abncart_timing', 5);
			$products_html        = '';
			$in_cart_products     = array();
			$flag                 = false;

			$existing_guest_users = get_option('mwb_hubwoo_guest_user_cart', array());

			if (! empty($existing_guest_users)) {

				foreach ($existing_guest_users as $key => &$single_cart_data) {

					$flag = false;

					if (isset($single_cart_data['email']) && $email == $single_cart_data['email']) {

						$last_time            = ! empty($single_cart_data['timeStamp']) ? $single_cart_data['timeStamp'] : '';
						$current_time         = time();
						$time_diff            = round(abs($current_time - $last_time) / 60, 2);
						$hubwoo_abncart_timer = (int) $hubwoo_abncart_timer;
						$flag                 = true;

						$cart_data = ! empty($single_cart_data['cartData']['cart']) ? $single_cart_data['cartData']['cart'] : array();

						if (count($cart_data)) {

							$cart_products = self::hubwoo_return_abncart_values($cart_data);
						}

						if (count($cart_products)) {

							$products_html = self::hubwoo_abncart_product_html($cart_products);
						}
						if (! empty($cart_data)) {
							$cart_status = 'yes';

							$last_date = $last_time;

							$locale   = ! empty($single_cart_data['locale']) ? $single_cart_data['locale'] : get_locale();
							$cart_url = apply_filters('wpml_permalink', wc_get_cart_url(), $locale, true);

							$cart_url .= '?hubwoo-abncart-retrieve=';
							$cart_prod = array();

							foreach ($cart_data as $single_cart_item) {
								$item_id         = $single_cart_item['product_id'];
								$parent_item_sku = get_post_meta($item_id, '_sku', true);
								if (! empty($single_cart_item['variation_id'])) {
									$item_id = $single_cart_item['variation_id'];
								}

								$qty         = $single_cart_item['quantity'] ? $single_cart_item['quantity'] : 1;
								$cart_prod[] = $item_id . ':' . $qty;
								if (get_post_status($item_id) == 'trash' || get_post_status($item_id) == false) {

									continue;
								}

								$cart_item_sku = get_post_meta($item_id, '_sku', true);

								if (empty($cart_item_sku)) {

									$cart_item_sku = $parent_item_sku;
								}

								if (empty($cart_item_sku)) {

									$cart_item_sku = $item_id;
								}

								$cart_product_skus[] = $cart_item_sku;

								$product_cats_ids = wc_get_product_term_ids($item_id, 'product_cat');

								if (is_array($product_cats_ids) && count($product_cats_ids)) {

									foreach ($product_cats_ids as $cat_id) {

										$term              = get_term_by('id', $cat_id, 'product_cat');
										$cart_categories[] = $term->slug;
									}
								}

								$post               = get_post($item_id);
								$post_name          = isset($post->post_name) ? $post->post_name : '';
								$product_name       = $post_name . '-' . $item_id;
								$in_cart_products[] = $product_name;
								$cart_product_qty  += $single_cart_item['quantity'];
								$cart_subtotal     += $single_cart_item['line_total'];
								$cart_tax          += $single_cart_item['line_tax'];
							}
							$cart_url .= implode(',', $cart_prod);
						} else {

							$this->hubwoo_abncart_clear_data($email);
						}

						if ($time_diff <= $hubwoo_abncart_timer) {
							if (count($cart_data)) {
								$flag = false;
								break;
							} else {
								$flag = true;
								break;
							}
						}
						break;
					}
				}
			}

			$cart_product_skus = HubwooGuestOrdersManager::hubwoo_format_array($cart_product_skus);
			$in_cart_products  = HubwooGuestOrdersManager::hubwoo_format_array($in_cart_products);
			$cart_categories   = HubwooGuestOrdersManager::hubwoo_format_array($cart_categories);
			$cart_total        = floatval($cart_tax + $cart_subtotal);

			if ($flag) {
				if (! empty($last_date)) {

					$last_date    = (int) $last_date;
					$properties[] = array(
						'property' => 'abandoned_cart_date',
						'value'    => HubwooGuestOrdersManager::hubwoo_set_utc_midnight($last_date),
					);
				} else {
					$properties[] = array(
						'property' => 'abandoned_cart_date',
						'value'    => '',
					);
				}

				$abandoned_property_updated = get_option('hubwoo_abandoned_property_update', 'no');

				if (! empty($abandoned_property_updated) && 'yes' == $abandoned_property_updated) {
					if ('yes' == $cart_status) {
						$cart_status = true;
					} else {
						$cart_status = false;
					}
				}

				$properties[] = array(
					'property' => 'current_abandoned_cart',
					'value'    => $cart_status,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_counter',
					'value'    => $cart_product_qty,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_url',
					'value'    => $cart_url,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_products_skus',
					'value'    => $cart_product_skus,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_products_categories',
					'value'    => $cart_categories,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_products',
					'value'    => $in_cart_products,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_tax_value',
					'value'    => $cart_tax,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_subtotal',
					'value'    => $cart_subtotal,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_total_value',
					'value'    => $cart_total,
				);
				$properties[] = array(
					'property' => 'abandoned_cart_products_html',
					'value'    => $products_html,
				);
			}
			return $properties;
		}
	}

	/**
	 * Clear data for email whose cart has been found empty.
	 *
	 * @since 1.0.0
	 * @param string $email contact email.
	 */
	public function hubwoo_abncart_clear_data($email)
	{

		$existing_guest_users = get_option('mwb_hubwoo_guest_user_cart', array());

		if (! empty($existing_guest_users)) {

			foreach ($existing_guest_users as $key => &$single_cart_data) {

				if (isset($single_cart_data['email']) && $email == $single_cart_data['email']) {

					unset($existing_guest_users[$key]);
					break;
				}
			}
		}

		$existing_guest_users = array_values($existing_guest_users);
		update_option('mwb_hubwoo_guest_user_cart', $existing_guest_users, false);
	}

	/**
	 * Clear those abandoned carts who have elapsed the saved timer.
	 *
	 * @since    1.0.0
	 */
	public function hubwoo_abncart_clear_old_cart()
	{

		if (get_option('hubwoo_abncart_delete_old_data', 'yes') == 'yes') {
			$saved_carts = get_option('mwb_hubwoo_guest_user_cart', array());

			$hubwoo_abncart_delete_after = (int) get_option('hubwoo_abncart_delete_after', '30');

			$hubwoo_abncart_delete_after = $hubwoo_abncart_delete_after * (24 * 60 * 60);

			// process the guest cart data.
			if (! empty($hubwoo_abncart_delete_after)) {

				if (! empty($saved_carts)) {

					foreach ($saved_carts as $key => &$single_cart) {

						$cart_time = ! empty($single_cart['timeStamp']) ? $single_cart['timeStamp'] : '';

						if (! empty($cart_time)) {

							$time = time();

							if ($time > $cart_time && ($time - $cart_time) >= $hubwoo_abncart_delete_after) {

								unset($saved_carts[$key]);
							}
						}
					}
				}
			}

			update_option('mwb_hubwoo_guest_user_cart', $saved_carts, false);

			// process the clearing of meta for registered users but don't clear their cart.
			$args['meta_query'] = array(

				array(
					'key'     => 'hubwoo_pro_user_left_cart',
					'value'   => 'yes',
					'compare' => '==',
				),
			);

			$users = wp_list_pluck(get_users($args), 'ID');

			if (! empty($users)) {
				foreach ($users as $user_id) {
					$cart_time = get_user_meta($user_id, 'hubwoo_pro_last_addtocart', true);
					if (! empty($cart_time)) {
						$time = time();
						if ($time > $cart_time && ($time - $cart_time) >= $hubwoo_abncart_delete_after) {
							delete_user_meta($user_id, 'hubwoo_pro_last_addtocart');
							delete_user_meta($user_id, 'hubwoo_pro_user_left_cart');
							delete_user_meta($user_id, 'hubwoo_pro_cart_locale');
						}
					}
				}
			}
		}
	}

	/**
	 * Fetching the customers who have abandoned cart.
	 *
	 * @since 1.0.0
	 * @param array $hubwoo_users list of users ready to be synced on hubspot.
	 */
	public function hubwoo_abncart_users($hubwoo_users)
	{

		$args['meta_query']          = array(
			'relation' => 'AND',
			array(
				'key'     => 'hubwoo_pro_user_left_cart',
				'value'   => 'yes',
				'compare' => '==',
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'hubwoo_pro_user_cart_sent',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'hubwoo_pro_user_cart_sent',
					'value'   => 'no',
					'compare' => '==',
				),
			),
		);
		// hubwoo_pro_user_left_cart is set for ANY logged-in user regardless of
		// role (see hubwoo_abncart_woocommerce_add_to_cart()) -- without this,
		// this merge silently reintroduces every role the caller's query
		// already filtered out, syncing a Contact for roles the admin
		// explicitly excluded from real-time sync.
		$args['role__in']            = get_option('hubwoo-selected-user-roles', array());
		$args['number']              = 10;
		$args['fields']              = 'ID';
		$hubwoo_abandoned_cart_users = get_users($args);
		$hubwoo_new_users            = array();

		if (count($hubwoo_abandoned_cart_users)) {

			$hubwoo_new_users = array_merge($hubwoo_users, $hubwoo_abandoned_cart_users);
		} else {

			$hubwoo_new_users = $hubwoo_users;
		}

		return $hubwoo_new_users;
	}
	/**
	 * Get user actions for marketing.
	 *
	 * @return array abandoned cart current status.
	 * @since 1.0.0
	 */
	public static function get_abandoned_cart_status()
	{

		$cart_status = array();

		$cart_status[] = array(
			'label' => __('Yes', 'makewebbetter-hubspot-for-woocommerce'),
			'value' => 'yes',
		);
		$cart_status[] = array(
			'label' => __('No', 'makewebbetter-hubspot-for-woocommerce'),
			'value' => 'no',
		);

		$cart_status = apply_filters('hubwoo_customer_cart_statuses', $cart_status);

		return $cart_status;
	}

	/**
	 * Prepare params for generating plugin settings.
	 *
	 * @since    1.0.0
	 * @return array $basic_settings array of html settings.
	 */
	public static function hubwoo_get_plugin_settings()
	{

		global $hubwoo;

		$existing_user_roles = self::get_all_user_roles();

		$basic_settings = array();

		$basic_settings[] = array(
			'title' => esc_html__('Plugin Settings', 'makewebbetter-hubspot-for-woocommerce'),
			'id'    => 'hubwoo_checkout_optin_title',
			'type'  => 'title',
		);

		$basic_settings[] = array(
			'title'    => esc_html__('Sync with User Role', 'makewebbetter-hubspot-for-woocommerce'),
			'id'       => 'hubwoo-selected-user-roles',
			'class'    => 'hubwoo-general-settings-fields',
			'type'     => 'multiselect',
			'desc'     => esc_html__('The users with selected roles will be synced on HubSpot. Default will be all user roles.', 'makewebbetter-hubspot-for-woocommerce'),
			'options'  => $existing_user_roles,
			'desc_tip' => true,

		);

		if (Hubwoo::hubwoo_subs_active()) {
			$basic_settings[] = array(
				'title'   => esc_html__('WooCommerce Subscription', 'makewebbetter-hubspot-for-woocommerce'),
				'id'      => 'hubwoo_subs_settings_enable',
				'class'   => 'hubwoo-general-settings-fields',
				'desc'    => esc_html__('Enable subscriptions data sync', 'makewebbetter-hubspot-for-woocommerce'),
				'type'    => 'checkbox',
				'default' => 'yes',
			);
		}

		$basic_settings[] = array(
			'title'   => esc_html__('Show Checkbox on Checkout Page', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_checkout_optin_enable',
			'class'   => 'hubwoo-general-settings-fields',
			'desc'    => esc_html__('Show Opt-In checkbox on Checkout Page', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'checkbox',
			'default' => 'no',
		);

		$basic_settings[] = array(
			'title'   => esc_html__('Checkbox Label on Checkout Page', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_checkout_optin_label',
			'class'   => 'hubwoo-general-settings-fields',
			'desc'    => esc_html__('Label to show for the checkbox', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'text',
			'default' => esc_html__('Subscribe', 'makewebbetter-hubspot-for-woocommerce'),
		);

		$basic_settings[] = array(
			'title'   => esc_html__('Show Checkbox on My Account Page', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_registeration_optin_enable',
			'class'   => 'hubwoo-general-settings-fields',
			'desc'    => esc_html__('Show Opt-In checkbox on My Account Page (Registration form)', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'checkbox',
			'default' => 'no',
		);

		$basic_settings[] = array(
			'title'   => esc_html__('Checkbox Label on My Account Page', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_registeration_optin_label',
			'class'   => 'hubwoo-general-settings-fields',
			'desc'    => esc_html__('Label to show for the checkbox', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'text',
			'default' => esc_html__('Subscribe', 'makewebbetter-hubspot-for-woocommerce'),
		);

		$basic_settings[] = array(
			'title'    => esc_html__('Calculate ROI for the Selected Status', 'makewebbetter-hubspot-for-woocommerce'),
			'id'       => 'hubwoo_no_status',
			'class'    => 'hubwoo-general-settings-fields',
			'type'     => 'select',
			'desc'     => esc_html__('Select an order status from the dropdown for which the new order property will be set/changed on each sync. Default Order Status is Completed', 'makewebbetter-hubspot-for-woocommerce'),
			'options'  => Hubwoo::hubwoo_get_valid_order_statuses(),
			'desc_tip' => true,
			'default'  => 'wc-completed',
		);

		$basic_settings[] = array(
			'title'             => esc_html__('Action Scheduler Log Retention (Days)', 'makewebbetter-hubspot-for-woocommerce'),
			'id'                => 'hubwoo_woo_action_schedulers_logs_delete_after',
			'class'    			=> 'hubwoo-general-settings-fields',
			'type'              => 'number',
			'desc'              => esc_html__('Define how many days completed Action Scheduler logs should be retained. Logs older than this period will be automatically deleted (min. 10 days).', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip'          => true,
			'custom_attributes' => array('min' => '10'),
			'default'           => '30',
		);

		$basic_settings = apply_filters('hubwoo_general_settings_options', $basic_settings);

		$basic_settings[] = array(
			'type' => 'sectionend',
			'id'   => 'hubwoo_pro_settings_end',
		);

		return $basic_settings;
	}

	/**
	 * Get all users count.
	 *
	 * @since 1.0.0
	 * @param string $constraint ( default = "NOT EXISTS" ).
	 * @return int $users count of all users
	 */
	public static function hubwoo_get_all_users_count($constraint = 'NOT EXISTS')
	{

		global $hubwoo;

		$roles = get_option('hubwoo_customers_role_settings', array());

		if (empty($roles)) {
			$roles = array_keys($hubwoo->hubwoo_get_user_roles());
			$key   = array_search('guest_user', $roles);
			if (false !== $key) {
				unset($roles[$key]);
			}
		}

		$args['role__in'] = $roles;

		$args['meta_query'] = array(
			array(
				'key'     => 'hubwoo_pro_user_data_change',
				'compare' => $constraint,
			),
		);

		$args['fields'] = 'ids';

		return count(get_users($args));
	}

	/**
	 * Cheap peek to see if any existing deal's order has changed recently
	 * and needs its deal updated, without doing the full sync work.
	 *
	 * @return bool
	 */
	private static function hubwoo_deal_update_pending_exists()
	{
		if ('yes' === get_option('hubwoo_connection_issue', 'no')) {
			return false;
		}

		if (Hubwoo::hubwoo_hpos_orders_blocked()) {
			return false;
		}

		$is_hpos_enabled = Hubwoo::hubwoo_check_hpos_active();

		if ($is_hpos_enabled) {
			$args = array(
				'limit'               => 1,
				'post_status'         => array_keys(Hubwoo::hubwoo_get_valid_order_statuses()),
				'orderby'             => 'date',
				'order'               => 'desc',
				'return'              => 'ids',
				'meta_key'            => 'hubwoo_ecomm_deal_created',
				'meta_compare'        => 'EXISTS',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'post_parent'         => 0,
			);
		} else {
			$args = array(
				'post_type'           => 'shop_order',
				'posts_per_page'      => 1,
				'post_status'         => array_keys(Hubwoo::hubwoo_get_valid_order_statuses()),
				'orderby'             => 'date',
				'order'               => 'desc',
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'     => 'hubwoo_ecomm_deal_created',
						'compare' => 'EXISTS',
					),
				),
			);
		}

		$hubwoo_last_time_order_sync = get_option('hubwoo_last_time_order_sync', current_time('mysql', true));
		if (! empty($hubwoo_last_time_order_sync)) {
			$date = new DateTime($hubwoo_last_time_order_sync, new DateTimeZone('UTC'));
			$date->modify('-3 minutes');
			$after_date = $date->format('Y-m-d H:i:s');

			$args['date_query'] = array(
				array(
					'column'    => $is_hpos_enabled ? 'date_updated_gmt' : 'post_modified_gmt',
					'after'     => $after_date,
					'inclusive' => true,
				),
			);
		}

		$args = apply_filters('hubwoo_deals_update_filter', $args);

		if ($is_hpos_enabled) {
			$query                = new WC_Order_Query($args);
			$orders_needs_syncing = $query->get_orders();
		} else {
			$query                = new WP_Query();
			$orders_needs_syncing = $query->query($args);
		}

		return ! empty($orders_needs_syncing);
	}

	/**
	 * Background sync for Deals.
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_ecomm_deal_update()
	{

		if ('yes' === get_option('hubwoo_connection_issue', 'no')) {
			return;
		}

		if (Hubwoo::hubwoo_hpos_orders_blocked()) {
			return;
		}

		$is_hpos_enabled = Hubwoo::hubwoo_check_hpos_active();
		//hpos changes
		if ($is_hpos_enabled) {
			// HPOS is enabled.
			$args = array(
				'limit'               => 20,
				'post_status'         => array_keys(Hubwoo::hubwoo_get_valid_order_statuses()),
				'orderby'             => 'date',
				'order'               => 'desc',
				'return'              => 'ids',
				'meta_key'     		  => 'hubwoo_ecomm_deal_created',
				'meta_compare' 		  => 'EXISTS',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'post_parent'         => 0,
			);
		} else {
			// CPT-based orders are in use.
			$args = array(
				'post_type'           => 'shop_order',
				'posts_per_page'      => 20,
				'post_status'         => array_keys(Hubwoo::hubwoo_get_valid_order_statuses()),
				'orderby'             => 'date',
				'order'               => 'desc',
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'     => 'hubwoo_ecomm_deal_created',
						'compare' => 'EXISTS',
					),
				),
			);
		}

		$hubwoo_last_time_order_sync = get_option('hubwoo_last_time_order_sync', current_time('mysql', true));
		if (! empty($hubwoo_last_time_order_sync)) {
			$date = new DateTime($hubwoo_last_time_order_sync, new DateTimeZone('UTC'));
			$date->modify('-3 minutes');
			$after_date = $date->format('Y-m-d H:i:s');

			$args['date_query'] = array(
				array(
					'column'    => $is_hpos_enabled ? 'date_updated_gmt' : 'post_modified_gmt',
					'after'     => $after_date,
					'inclusive' => true,
				),
			);
		}

		$args = apply_filters('hubwoo_deals_update_filter', $args);

		//hpos changes
		if ($is_hpos_enabled) {
			// HPOS is enabled.
			$query = new WC_Order_Query($args);
			$orders_needs_syncing = $query->get_orders();
		} else {
			// CPT-based orders are in use.
			$query = new WP_Query();
			$orders_needs_syncing = $query->query($args);
		}

		if (! empty($orders_needs_syncing)) {
			$orders_needs_syncing = self::hubwoo_check_orders_upsert_key($orders_needs_syncing);
			foreach ($orders_needs_syncing as $key => $order_id) {
				if (HubwooObjectProperties::get_instance()->hubwoo_needs_to_sync_order($order_id)) {
					if (! as_has_scheduled_action('hubwoo_ecomm_deal_upsert', array('order_id' => $order_id, 'sync_type' => 'real'))) {
						as_enqueue_async_action('hubwoo_ecomm_deal_upsert', array('order_id' => $order_id, 'sync_type' => 'real'));
					}
				}
			}
		}

		update_option('hubwoo_last_time_order_sync', current_time('mysql', true), false);
	}

	public function hubwoo_check_orders_upsert_key($orders)
	{
		// HPOS is primary but the add-on isn't licensed -- don't fall through to
		// the legacy branch below; that branch is for genuinely Legacy-primary
		// stores, not a stand-in for "not activated yet".
		if (Hubwoo::hubwoo_hpos_orders_blocked()) {
			return $orders;
		}

		$is_hpos_enabled    = Hubwoo::hubwoo_check_hpos_active();
		$upsert_key_statuses = array_keys( Hubwoo::hubwoo_get_valid_order_statuses() );
		//hpos changes
		if ($is_hpos_enabled) {
			// HPOS is enabled.
			$args = array(
				'posts_per_page'      => 5,
				'post_status'         => $upsert_key_statuses,
				'orderby'             => 'date',
				'order'               => 'ASC',
				'return'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'post_parent'         => 0,
				'meta_query'          => array(
					array(
						'key'     => 'hubwoo_ecomm_deal_upsert',
						'compare' => '=',
						'value'   => 'yes',
					),
				),
			);
		} else {
			// CPT-based orders are in use.
			$args = array(
				'post_type'           => 'shop_order',
				'posts_per_page'      => 5,
				'post_status'         => $upsert_key_statuses,
				'orderby'             => 'date',
				'order'               => 'ASC',
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'     => 'hubwoo_ecomm_deal_upsert',
						'compare' => '=',
						'value'   => 'yes',
					),
				),
			);
		}

		$args = apply_filters('hubwoo_deals_update_filter', $args);

		//hpos changes
		if ($is_hpos_enabled) {
			// HPOS is enabled.
			$query = new WC_Order_Query($args);
			$orders_with_upsert = $query->get_orders();
		} else {
			// CPT-based orders are in use.
			$query = new WP_Query();
			$orders_with_upsert = $query->query($args);
		}

		if (! empty($orders_with_upsert)) {
			$orders = array_unique(array_merge($orders, $orders_with_upsert));
		}

		return $orders;
	}

	/**
	 * Background sync for Deals.
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_deals_sync_background()
	{
		$orders_needs_syncing = self::hubwoo_orders_count_for_deal(5, false);
		if (is_array($orders_needs_syncing) && count($orders_needs_syncing)) {
			foreach ($orders_needs_syncing as $key => $order_id) {
				if (! as_has_scheduled_action('hubwoo_ecomm_deal_upsert', array('order_id' => $order_id, 'sync_type' => 'historical'))) {
					as_enqueue_async_action('hubwoo_ecomm_deal_upsert', array('order_id' => $order_id, 'sync_type' => 'historical'));
				}
			}
		} else {
			Hubwoo::hubwoo_stop_sync('stop-deal');
		}
	}

	/**
	 * Single schedule event for syncing deals.
	 *
	 * @since 1.6.3
	 */
	public function hubwoo_ecomm_deal_upsert($order_id, $sync_type = '')
	{
		if (!empty($order_id)) {
			HubwooObjectProperties::get_instance()->hubwoo_ecomm_deals_sync($order_id, $sync_type);
		}
	}

	/**
	 * Keep our own HubSpot sync-state meta from being copied onto a duplicated
	 * product. Without this, a clone inherits the original product's
	 * hubwoo_ecomm_pro_id and is then treated as already synced -- it never gets
	 * pushed to HubSpot on its own, and if it's later edited, hubwoo_ecomm_update_product()
	 * would use that inherited ID to push the clone's data as an update to the
	 * *original* product's HubSpot record.
	 *
	 * @since 1.6.8
	 * @param array $exclude_meta Meta keys WooCommerce already excludes from duplication.
	 * @return array
	 */
	public function hubwoo_exclude_product_meta_from_duplicate($exclude_meta)
	{
		$exclude_meta[] = 'hubwoo_ecomm_pro_id';
		$exclude_meta[] = 'hubwoo_product_synced';
		$exclude_meta[] = 'hubwoo_ecomm_invalid_pro';

		return $exclude_meta;
	}

	/**
	 * Updates the product whenever there is any change
	 *
	 * @since    1.0.0
	 * @param int    $post_ID post id of the product.
	 * @param object $post post object.
	 */
	public static function hubwoo_ecomm_update_product($post_ID, $post)
	{

		if ('yes' != get_option('hubwoo_greeting_displayed_setup', 'no') || 'yes' == get_option('hubwoo_product_scope_needed', 'no')) {
			return;
		}

		$post_type = $post->post_type;
		if ('product' != $post_type) {
			return;
		}
		$updates     = array();
		$hs_new      = 'true';
		$object_type = 'PRODUCT';
		if (is_ajax()) {
			return;
		}
		$post_status = get_post_status($post_ID);
		$acceptable_post_status = apply_filters('hubwoo_accept_product_status', array('publish'));
		if (in_array($post_status, $acceptable_post_status)) {
			if (! empty($post_ID)) {
				$product      = wc_get_product($post_ID);
				$product_type = $product->get_type();
				if (! empty($product_type) && ('variable' == $product_type || 'variable-subscription' == $product_type)) {
					$variation_args    = array(
						'post_parent' => $post_ID,
						'post_type'   => 'product_variation',
						'numberposts' => -1,
					);
					$wc_products_array = get_posts($variation_args);
					if (is_array($wc_products_array) && count($wc_products_array)) {
						foreach ($wc_products_array as $single_var_product) {
							$hubwoo_ecomm_product           = new HubwooEcommObject($single_var_product->ID, $object_type);
							$properties                     = $hubwoo_ecomm_product->get_object_properties();
							$properties                     = apply_filters('hubwoo_map_ecomm_' . $object_type . '_properties', $properties, $single_var_product->ID);
							$pro_hs_id                      = get_post_meta($single_var_product->ID, 'hubwoo_ecomm_pro_id', true);
							$properties['description']      = isset($properties['pr_description']) ? $properties['pr_description'] : '';

							unset($properties['pr_description']);
							if (! empty($pro_hs_id)) {
								$updates[]            = array(
									'id'               => $pro_hs_id,
									'properties'       => $properties,
								);
								$hs_new = 'false';
							} else {
								$updates[]            = array(
									'properties'       => $properties,
								);
							}
						}
					}
				} else {
					$hubwoo_ecomm_product           = new HubwooEcommObject($post_ID, $object_type);
					$properties                     = $hubwoo_ecomm_product->get_object_properties();
					$properties                     = apply_filters('hubwoo_map_ecomm_' . $object_type . '_properties', $properties, $post_ID);
					$pro_hs_id                      = get_post_meta($post_ID, 'hubwoo_ecomm_pro_id', true);
					$properties['description']      = isset($properties['pr_description']) ? $properties['pr_description'] : '';

					unset($properties['pr_description']);
					if (! empty($pro_hs_id)) {
						$updates[]            = array(
							'id'               => $pro_hs_id,
							'properties'       => $properties,
						);
						$hs_new = 'false';
					} else {
						$updates[]            = array(
							'properties'       => $properties,
						);
					}
				}
			}
		}
		if (count($updates)) {
			$updates = array(
				'inputs' => $updates,
			);

			$flag = true;

			if (Hubwoo::is_access_token_expired()) {

				$hapikey = HUBWOO_CLIENT_ID;
				$hseckey = HUBWOO_SECRET_ID;
				$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);

				if (! $status) {

					$flag = false;
				}
			}

			if ($flag) {
				if ('true' == $hs_new) {
					$response = HubWooConnectionMananager::get_instance()->create_batch_object_record('products', $updates);

					if (201 == $response['status_code']) {
						$response = json_decode($response['body']);
						$result = $response->results;
						foreach ($result as $key => $value) {
							update_post_meta($value->properties->store_product_id, 'hubwoo_ecomm_pro_id', $value->id);
							delete_post_meta($value->properties->store_product_id, 'hubwoo_product_synced');
							do_action('hubwoo_update_product_property', $value->properties->store_product_id);
						}
					}
				} else {
					$response = HubWooConnectionMananager::get_instance()->update_batch_object_record('products', $updates);
					if (200 == $response['status_code']) {
						$response = json_decode($response['body']);
						$result = $response->results;
						foreach ($result as $key => $value) {
							delete_post_meta($value->properties->store_product_id, 'hubwoo_product_synced');
							do_action('hubwoo_update_product_property', $value->properties->store_product_id);
						}
					}
				}
			}
		}
	}

	/**
	 * Fetching total order available and returning count. Also excluding orders with deal id.
	 *
	 * @since 1.0.0
	 * @param int  $number_of_posts posts to fetch in one call.
	 * @param bool $count true/false.
	 * @return array $old_orders orders posts.
	 */
	public static function hubwoo_orders_count_for_deal($number_of_posts = -1, $count = true)
	{

		$sync_data['since_date']            = get_option('hubwoo_ecomm_order_ocs_from_date', gmdate('d-m-Y'));
		$sync_data['upto_date']             = get_option('hubwoo_ecomm_order_ocs_upto_date', gmdate('d-m-Y'));
		$sync_data['selected_order_status'] = array_diff(
			get_option( 'hubwoo_ecomm_order_ocs_status', array_keys( Hubwoo::hubwoo_get_valid_order_statuses() ) ),
			array( 'wc-checkout-draft' )
		);

		// wc_get_orders() resolves HPOS vs legacy storage internally (see
		// hubwoo_setup_overview()), so a single call covers both -- no more
		// branching on which query function to call.
		$args = array(
			'limit'        => $number_of_posts,
			'orderby'      => 'date',
			'post_status'  => $sync_data['selected_order_status'],
			'order'        => 'DESC',
			'return'       => 'ids',
			'meta_key'     => 'hubwoo_ecomm_deal_created',
			'meta_compare' => 'NOT EXISTS',
			'post_parent'  => 0,
		);

		if ('yes' == get_option('hubwoo_ecomm_order_date_allow', 'no')) {
			$args['date_query'] = array(
				array(
					'after'     => gmdate('d-m-Y', strtotime($sync_data['since_date'])),
					'before'    => gmdate('d-m-Y', strtotime($sync_data['upto_date'] . ' +1 day')),
					'inclusive' => true,
				),
			);
		}

		// 'meta_compare' => 'NOT EXISTS' above only checks whichever table is
		// CURRENTLY the primary store. A store that has ever switched HPOS on/off
		// can have hubwoo_ecomm_deal_created sitting in the *other* table from an
		// earlier period, which would otherwise make an already-synced order look
		// unsynced and get re-selected for historical deal creation. Exclude
		// anything flagged in the other table directly.
		global $wpdb;
		$hpos_enabled          = Hubwoo::hubwoo_is_hpos_enabled();
		$other_table           = $hpos_enabled ? $wpdb->postmeta : "{$wpdb->prefix}wc_orders_meta";
		$other_table_id_column = $hpos_enabled ? 'post_id' : 'order_id';
		$synced_elsewhere      = $wpdb->get_col(
			"SELECT DISTINCT {$other_table_id_column} FROM {$other_table} WHERE meta_key = 'hubwoo_ecomm_deal_created'"
		);
		if (! empty($synced_elsewhere)) {
			$args['exclude'] = array_map('intval', $synced_elsewhere);
		}

		$args = apply_filters('hubwoo_deals_historical_sync_filter', $args);

		if ($count) {
			// wc_get_orders() has no native 'count' return mode; 'paginate' gives
			// us the total without loading every order into memory.
			$count_args             = $args;
			$count_args['return']   = 'ids';
			$count_args['paginate'] = true;
			$count_args['limit']    = -1;
			$results                = wc_get_orders($count_args);
			return isset($results->total) ? (int) $results->total : 0;
		}

		return wc_get_orders($args);
	}

	/**
	 * General setting tab fields.
	 *
	 * @return array  woocommerce_admin_fields acceptable fields in array.
	 * @since 1.0.0
	 */
	public static function hubwoo_ecomm_general_settings()
	{

		$settings = array();

		$settings[] = array(
			'title' => __('Apply your settings for Deals and its stages', 'makewebbetter-hubspot-for-woocommerce'),
			'id'    => 'hubwoo_ecomm_deals_settings_title',
			'type'  => 'title',
			'class' => 'hubwoo-ecomm-settings-title',
		);

		$settings[] = array(
			'title'   => __('Enable/Disable', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_ecomm_deal_enable',
			'desc'    => __('Allow to sync new deals', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'checkbox',
			'class'   => 'hubwoo-ecomm-settings-checkbox hubwoo_real_time_changes',
			'default' => 'yes',
		);

		$settings[] = array(
			'title'             => __('Days required to close a deal', 'makewebbetter-hubspot-for-woocommerce'),
			'id'                => 'hubwoo_ecomm_closedate_days',
			'type'              => 'number',
			'desc'              => __('set the minimum number of days in which the pending/open deals can be closed/won', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip'          => true,
			'custom_attributes' => array('min' => '5'),
			'default'           => '5',
			'class'             => 'hubwoo-ecomm-settings-text hubwoo_real_time_changes',
		);

		$settings[] = array(
			'title'    => __('Winning Deal Stages', 'makewebbetter-hubspot-for-woocommerce'),
			'id'       => 'hubwoo_ecomm_won_stages',
			'type'     => 'multiselect',
			'class'    => 'hubwoo-ecomm-settings-multiselect hubwoo_real_time_changes',
			'desc'     => __('select the deal stages of ecommerce pipeline which are won according to your business needs. "Processing" and "Completed" are default winning stages for extension as well as HubSpot', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip' => true,
			'options'  => self::hubwoo_ecomm_get_stages(),
		);

		$settings[] = array(
			'title'   => __('Enable/Disable', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_assoc_deal_cmpy_enable',
			'desc'    => __('Allow to associate deal and company', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'checkbox',
			'class'   => 'hubwoo-ecomm-settings-checkbox hubwoo_real_time_changes',
			'default' => 'yes',
		);

		$settings[] = array(
			'title'   => __('Enable Multi Currency	Sync', 'makewebbetter-hubspot-for-woocommerce'),
			'id'      => 'hubwoo_deal_multi_currency_enable',
			'desc'    => __('Enable to sync currency if you are using multi currency on your site.', 'makewebbetter-hubspot-for-woocommerce'),
			'type'    => 'checkbox',
			'class'   => 'hubwoo-ecomm-settings-checkbox hubwoo_real_time_changes',
		);

		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'hubwoo_ecomm_deal_settings_end',
		);

		return apply_filters('hubwoo_ecomm_deals_settings', $settings);
	}


	/**
	 * Updates the product whenever there is any change
	 *
	 * @since    1.0.0
	 * @return   array $settings ecomm ocs settings
	 */
	public static function hubwoo_ecomm_order_ocs_settings()
	{

		$settings = array();

		$settings[] = array(
			'title' => __('Export your old orders as Deals on HubSpot', 'makewebbetter-hubspot-for-woocommerce'),
			'id'    => 'hubwoo_ecomm_order_ocs_title',
			'type'  => 'title',
			'class' => 'hubwoo-ecomm-settings-title',
		);

		$settings[] = array(
			'title'    => __('Select order status', 'makewebbetter-hubspot-for-woocommerce'),
			'id'       => 'hubwoo_ecomm_order_ocs_status',
			'type'     => 'multiselect',
			'desc'     => __('Select a order status from the dropdown and all orders for the selected status will be synced as deals', 'makewebbetter-hubspot-for-woocommerce'),
			'options'  => Hubwoo::hubwoo_get_valid_order_statuses(),
			'desc_tip' => true,
			'class'    => 'hubwoo-ecomm-settings-select',
		);

		$settings[] = array(
			'title' => __('Select a time period', 'makewebbetter-hubspot-for-woocommerce'),
			'id'    => 'hubwoo_ecomm_order_date_allow',
			'desc'  => __(' Date range for orders', 'makewebbetter-hubspot-for-woocommerce'),
			'type'  => 'checkbox',
			'class' => 'hubwoo-ecomm-settings-checkbox hubwoo-ecomm-settings-select',
		);

		$settings[] = array(
			'title'       => __('Orders from date', 'makewebbetter-hubspot-for-woocommerce'),
			'id'          => 'hubwoo_ecomm_order_ocs_from_date',
			'type'        => 'text',
			'placeholder' => 'dd-mm-yyyy',
			'default'     => gmdate('d-m-Y'),
			'desc'        => __('From which date you want to sync the orders, select that date', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip'    => true,
			'class'       => 'hubwoo-date-picker hubwoo-date-d-range hubwoo-ecomm-settings-select',
		);

		$settings[] = array(
			'title'       => __('Orders up to date', 'makewebbetter-hubspot-for-woocommerce'),
			'id'          => 'hubwoo_ecomm_order_ocs_upto_date',
			'type'        => 'text',
			'default'     => gmdate('d-m-Y'),
			'placeholder' => __('dd-mm-yyyy', 'makewebbetter-hubspot-for-woocommerce'),
			'desc'        => __('Up to which date you want to sync the orders, select that date', 'makewebbetter-hubspot-for-woocommerce'),
			'desc_tip'    => true,
			'class'       => 'hubwoo-date-picker hubwoo-date-d-range hubwoo-ecomm-settings-select',
		);

		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'hubwoo_ecomm_order_ocs_end',
		);

		return apply_filters('hubwoo_ecomm_order_sync_settings', $settings);
	}

	/**
	 * Updates the product whenever there is any change
	 *
	 * @since    1.0.0
	 * @return array $mapped_array mapped deal stage and order statuses.
	 */
	public static function hubwoo_ecomm_get_stages()
	{

		$mapped_array  = array();
		$stages        = get_option('hubwoo_fetched_deal_stages', '');
		$deal_stage_id = 'stageId';

		if ('yes' == get_option('hubwoo_ecomm_pipeline_created', 'no')) {
			$deal_stage_id = 'id';
		}
		if (! empty($stages)) {
			$mapped_array = array_combine(array_column($stages, $deal_stage_id), array_column($stages, 'label'));
		}
		return $mapped_array;
	}

	/**
	 * Updates the product whenever there is any change
	 *
	 * @since    1.0.0
	 * @param bool $redirect redirect to contact page ( default = false).
	 */
	public static function hubwoo_schedule_sync_listener($redirect = false)
	{

		$hubwoodatasync = new HubwooDataSync();

		$hubwoodatasync->hubwoo_start_schedule();

		if ($redirect) {
			// Without exit, the calling template keeps rendering its entire
			// page body after this header is queued -- same failure mode
			// already fixed in hubwoo_redirect_from_hubspot(): other code
			// later in the same request can still run and interfere before
			// the browser actually acts on the redirect.
			wp_safe_redirect(admin_url('admin.php?page=hubwoo&hubwoo_tab=hubwoo-sync-contacts'));
			exit;
		}
	}

	/**
	 * Download the log file of the plugin.
	 *
	 * @return void
	 */

	public function hubwoo_get_plugin_log()
	{

		if (isset($_GET['action']) && 'download-log' === $_GET['action']) {
			// admin_init fires on every wp-admin page load for any logged-in
			// user, regardless of role -- this streams the plugin's raw log
			// file (can contain HubSpot API request/response bodies with
			// customer PII), so it needs its own explicit check.
			if (! current_user_can('manage_woocommerce')) {
				wp_die();
			}

			$filename = WC_LOG_DIR . 'hubspot-for-woocommerce-logs.log';

			global $wp_filesystem;
			if (! function_exists('WP_Filesystem')) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			WP_Filesystem();

			if ($wp_filesystem->exists($filename)) {

				$file_contents = $wp_filesystem->get_contents($filename);

				if ($file_contents === false) {
					wp_die(esc_html__('Log file could not be read.', 'makewebbetter-hubspot-for-woocommerce'));
				}

				if (ob_get_length()) {
					ob_end_clean();
				}

				header('Content-Type: text/plain');
				header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
				header('Content-Length: ' . strlen($file_contents)); // Ensure correct file size
				header('Cache-Control: no-cache, no-store, must-revalidate');
				header('Pragma: no-cache');
				header('Expires: 0');

				echo esc_html($file_contents);
				flush();
				exit();
			} else {
				wp_die(esc_html__('Log file not found or inaccessible.', 'makewebbetter-hubspot-for-woocommerce'));
			}
		} elseif (isset($_GET['action']) && 'dismiss-hubwoo-notice' === $_GET['action']) {
			wp_safe_redirect(admin_url('admin.php') . '?page=hubwoo');
			exit();
		}
	}



	/**
	 * Cheap peek to see if any order is waiting to be synced as a deal,
	 * without doing the full sync work.
	 *
	 * @return bool
	 */
	private static function hubwoo_deals_pending_exists()
	{
		if ('yes' === get_option('hubwoo_connection_issue', 'no')) {
			return false;
		}

		if (Hubwoo::hubwoo_hpos_orders_blocked()) {
			return false;
		}

		$is_hpos_enabled    = Hubwoo::hubwoo_check_hpos_active();
		$deal_sync_statuses = array_keys(Hubwoo::hubwoo_get_valid_order_statuses());

		if ($is_hpos_enabled) {
			$args = array(
				'limit'        => 1,
				'orderby'      => 'date',
				'post_status'  => $deal_sync_statuses,
				'order'        => 'DESC',
				'return'       => 'ids',
				'meta_key'     => 'hubwoo_ecomm_deal_created',
				'meta_compare' => 'NOT EXISTS',
				'post_parent'  => 0,
			);
		} else {
			$args = array(
				'post_type'           => 'shop_order',
				'posts_per_page'      => 1,
				'post_status'         => $deal_sync_statuses,
				'orderby'             => 'date',
				'order'               => 'desc',
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'     => 'hubwoo_ecomm_deal_created',
						'compare' => 'NOT EXISTS',
					),
				),
			);
		}

		$activated_time = get_option('hubwoo_plugin_activated_time', '');
		if (! empty($activated_time)) {
			$args['date_query'] = array(
				array(
					'after'     => gmdate('d-m-Y', strtotime('-1 day', $activated_time)),
					'inclusive' => true,
				),
			);
		}

		$args = apply_filters('hubwoo_deals_sync_filter', $args);

		if ($is_hpos_enabled) {
			$orders_needs_syncing = wc_get_orders($args);
		} else {
			$query                = new WP_Query();
			$orders_needs_syncing = $query->query($args);
		}

		return ! empty($orders_needs_syncing);
	}

	/**
	 * Re-sync Orders as deals.
	 *
	 * @since    1.0.0
	 */
	public function hubwoo_deals_sync_check()
	{

		if ('yes' === get_option('hubwoo_connection_issue', 'no')) {
			return;
		}

		if (Hubwoo::hubwoo_hpos_orders_blocked()) {
			return;
		}

		$is_hpos_enabled = Hubwoo::hubwoo_check_hpos_active();
		$deal_sync_statuses = array_keys( Hubwoo::hubwoo_get_valid_order_statuses() );

		//hpos changes
		if ($is_hpos_enabled) {
			// HPOS is enabled.
			$args = array(
				'limit'        => 3, // Query all orders
				'orderby'      => 'date',
				'post_status'  => $deal_sync_statuses,
				'order'        => 'DESC',
				'return'       => 'ids',
				'meta_key'     => 'hubwoo_ecomm_deal_created', // The postmeta key field
				'meta_compare' => 'NOT EXISTS', // The comparison argument
				'post_parent'  => 0,
			);
		} else {
			// CPT-based orders are in use.
			$query = new WP_Query();

			$args = array(
				'post_type'           => 'shop_order',
				'posts_per_page'      => 3,
				'post_status'         => $deal_sync_statuses,
				'orderby'             => 'date',
				'order'               => 'desc',
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'     => 'hubwoo_ecomm_deal_created',
						'compare' => 'NOT EXISTS',
					),
				),
			);
		}

		$activated_time = get_option('hubwoo_plugin_activated_time', '');
		if (! empty($activated_time)) {
			$args['date_query'] = array(
				array(
					'after'     => gmdate('d-m-Y', strtotime('-1 day', $activated_time)),
					'inclusive' => true,
				),
			);
		}

		$args = apply_filters('hubwoo_deals_sync_filter', $args);

		//hpos changes
		if ($is_hpos_enabled) {
			$orders_needs_syncing = wc_get_orders($args);
		} else {
			$orders_needs_syncing = $query->query($args);
		}

		if (! empty($orders_needs_syncing)) {
			foreach ($orders_needs_syncing as $key => $order_id) {
				if (! as_has_scheduled_action('hubwoo_ecomm_deal_upsert', array('order_id' => $order_id, 'sync_type' => 'real'))) {
					as_enqueue_async_action('hubwoo_ecomm_deal_upsert', array('order_id' => $order_id, 'sync_type' => 'real'));
				}
			}
		}
	}

	/**
	 * Cheap peek to see if any product is waiting to be synced,
	 * without building properties or calling the HubSpot API.
	 *
	 * @return bool
	 */
	private static function hubwoo_products_pending_exists()
	{
		if ('yes' != get_option('hubwoo_greeting_displayed_setup', 'no') || 'yes' == get_option('hubwoo_product_scope_needed', 'no')) {
			return false;
		}

		$constraints = array(
			array(
				'key'     => 'hubwoo_ecomm_pro_id',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'hubwoo_ecomm_invalid_pro',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'hubwoo_product_synced',
				'compare' => 'NOT EXISTS',
			),
			'relation' => 'AND',
		);

		$products = Hubwoo::hubwoo_ecomm_get_products(1, $constraints);

		return ! empty($products);
	}

	/**
	 * Re-sync Orders as deals.
	 *
	 * @since    1.0.0
	 */
	public function hubwoo_products_sync_check()
	{

		if ('yes' != get_option('hubwoo_greeting_displayed_setup', 'no') || 'yes' == get_option('hubwoo_product_scope_needed', 'no')) {
			return;
		}

		$product_data       = Hubwoo::hubwoo_get_product_data(10);

		if (! empty($product_data) && is_array($product_data)) {
			foreach ($product_data as $pro_id => $value) {

				$filtergps = array();
				$product_data = array(
					'properties' => $value['properties'],
				);

				$flag = true;
				if (Hubwoo::is_access_token_expired()) {

					$hapikey = HUBWOO_CLIENT_ID;
					$hseckey = HUBWOO_SECRET_ID;
					$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);

					if (! $status) {

						$flag = false;
					}
				}

				if ($flag) {

					$product = wc_get_product($pro_id);

					$filtergps = array(
						'filterGroups' => array(
							array(
								'filters' => array(
									array(
										'value' => $pro_id,
										'propertyName' => 'store_product_id',
										'operator' => 'EQ',
									),
								),
							),
						),
					);

					if (!empty($product->get_sku())) {
						$filtergps['filterGroups'][] = array(
							'filters' => array(
								array(
									'value' => $product->get_sku(),
									'propertyName' => 'hs_sku',
									'operator' => 'EQ',
								),
							),
						);
					}

					$response = HubWooConnectionMananager::get_instance()->search_object_record('products', $filtergps);

					if (200 == $response['status_code']) {
						$responce_body = json_decode($response['body']);
						$result = $responce_body->results;
						if (! empty($result)) {
							foreach ($result as $key => $value) {
								update_post_meta($pro_id, 'hubwoo_ecomm_pro_id', $value->id);
							}
						}
					}

					$pro_hs_id = get_post_meta($pro_id, 'hubwoo_ecomm_pro_id', true);

					if (! empty($pro_hs_id)) {
						$response = HubWooConnectionMananager::get_instance()->update_object_record('products', $pro_hs_id, $product_data);
						if (200 == $response['status_code']) {
							delete_post_meta($pro_id, 'hubwoo_product_synced');
						}
					} else {
						$response = HubWooConnectionMananager::get_instance()->create_object_record('products', $product_data);
						if (201 == $response['status_code']) {
							$response_body = json_decode($response['body']);
							update_post_meta($pro_id, 'hubwoo_ecomm_pro_id', $response_body->id);
							delete_post_meta($pro_id, 'hubwoo_product_synced');
						}
					}
					do_action('hubwoo_update_product_property', $pro_id);
				}
			}
		}
	}

	/**
	 * Contact sync in background.
	 *
	 * @since    1.0.4
	 */
	public function hubwoo_contacts_sync_background()
	{
		$hubwoo_datasync = new HubwooDataSync();

		$reg_batch_pending = $this->hubwoo_contacts_batch_pending('historical', 'reg');

		if (! $reg_batch_pending) {
			// Registered users needing historical sync (hubwoo_pro_user_data_change NOT EXISTS).
			$users_need_syncing = $hubwoo_datasync->hubwoo_get_all_unique_user(false, 'customer', 5);

			if (! empty($users_need_syncing)) {
				$batch_id = bin2hex(random_bytes(16));
				update_option('hubwoo_batch_users_to_sync_' . $batch_id, $users_need_syncing, false);
				as_enqueue_async_action('hubwoo_contacts_batch_sync', array('sync_type' => 'historical', 'user_type' => 'reg', 'batch_id' => $batch_id));
				return;
			}
		}

		$guest_batch_pending = $this->hubwoo_contacts_batch_pending('historical', 'guest');

		if (! $guest_batch_pending) {
			// No registered users left — fall back to guest orders needing historical sync
			// (hubwoo_pro_guest_order NOT EXISTS). The guestOrder branch always returns the
			// full matching set, so trim it to the same batch size here.
			$orders_need_syncing = $hubwoo_datasync->hubwoo_get_all_unique_user(false, 'guestOrder');
			$orders_need_syncing = array_slice((array) $orders_need_syncing, 0, 5);

			if (! empty($orders_need_syncing)) {
				$batch_id = bin2hex(random_bytes(16));
				update_option('hubwoo_batch_users_to_sync_' . $batch_id, $orders_need_syncing, false);
				as_enqueue_async_action('hubwoo_contacts_batch_sync', array('sync_type' => 'historical', 'user_type' => 'guest', 'batch_id' => $batch_id));
				return;
			}
		}

		// Only declare the sync fully finished if nothing is already pending AND
		// nothing new was found -- a pending batch means work is still in flight
		// even though we skipped re-querying for it this tick, so stopping here
		// would unschedule this recurring job while a sync is still genuinely
		// running.
		if (! $reg_batch_pending && ! $guest_batch_pending) {
			Hubwoo::hubwoo_stop_sync('stop-contact');
		}
	}

	/**
	 * Re-sync Contacts to fetch Vid.
	 *
	 * @since    1.0.0
	 */
	public function hubwoo_update_contacts_vid()
	{

		$contact_data = array();

		$mark = array();

		$args['meta_query'] = array(

			array(
				'key'     => 'hubwoo_user_vid',
				'compare' => 'NOT EXISTS',
			),
		);
		$args['role__in']   = get_option('hubwoo-selected-user-roles', array());
		$args['number']     = 20;
		$args['fields']     = 'ID';

		$users = get_users($args);

		if (! empty($users)) {
			$mark['type'] = 'user';
			$mark['ids']  = $users;

			foreach ($users as $user_id) {
				$user_data                   = array();
				$user                        = get_user_by('id', $user_id);
				$user_data['email']          = $user->data->user_email;
				$user_data['customer_group'] = ! empty($user->data->roles) ? HubwooGuestOrdersManager::hubwoo_format_array($user->data->roles) : 'customer';
				$user_data['firstname']      = get_user_meta($user_id, 'first_name', true);
				$user_data['lastname']       = get_user_meta($user_id, 'last_name', true);
				$contacts[]                  = $user_data;
			}
		} else {

			// HPOS is primary but the add-on isn't licensed -- skip the
			// guest-order lookup entirely rather than falling through to the
			// legacy branch below, which isn't meant to run against an
			// HPOS-primary store.
			$customer_orders = Hubwoo::hubwoo_hpos_orders_blocked() ? array() : null;

			if ( null === $customer_orders ) {
				//hpos changes
				$contact_batch_statuses = array_keys( Hubwoo::hubwoo_get_valid_order_statuses() );
				if (Hubwoo::hubwoo_check_hpos_active()) {
					// HPOS is enabled.
					$query = new WC_Order_Query(array(
						'limit'        => 20, // Query all orders
						'orderby'      => 'date',
						'post_status'  => $contact_batch_statuses,
						'order'        => 'DESC',
						'return'       => 'ids',
						'meta_key'     => 'hubwoo_user_vid', // The postmeta key field
						'meta_compare' => 'NOT EXISTS', // The comparison argument
						'post_parent'  => 0,
						'customer_id'  => 0,
					));
					$customer_orders = $query->get_orders();
				} else {
					// CPT-based orders are in use.
					$query = new WP_Query();

					$customer_orders = $query->query(
						array(
							'post_type'           => 'shop_order',
							'posts_per_page'      => 20,
							'post_status'         => $contact_batch_statuses,
							'orderby'             => 'date',
							'order'               => 'desc',
							'fields'              => 'ids',
							'no_found_rows'       => true,
							'ignore_sticky_posts' => true,
							'meta_query'          => array(
								'relation' => 'AND',
								array(
									'key'   => '_customer_user',
									'value' => 0,
								),
								array(
									'key'     => 'hubwoo_user_vid',
									'compare' => 'NOT EXISTS',
								),
							),
						)
					);
				}
			}

			if (! empty($customer_orders)) {

				$mark['type'] = 'order';
				$mark['ids']  = $customer_orders;

				foreach ($customer_orders as $order_id) {
					$order 						 = wc_get_order($order_id);
					$user_data                   = array();
					$user_data['email']          = $order->get_billing_email();
					$user_data['customer_group'] = 'guest';
					$user_data['firstname']      = $order->get_billing_first_name();
					$user_data['lastname']       = $order->get_billing_last_name();
					$contacts[]                  = $user_data;
				}
			}
		}

		if (! empty($contacts)) {

			$prepared_data = array();

			array_walk(
				$contacts,
				function ($user_data) use (&$prepared_data) {
					if (! empty($user_data)) {
						$temp_data = array();

						if (isset($user_data['email'])) {
							$temp_data['email'] = $user_data['email'];
							unset($user_data['email']);
							foreach ($user_data as $name => $value) {
								if (! empty($value)) {
									$temp_data['properties'][] = array(
										'property' => $name,
										'value'    => $value,
									);
								}
							}
							$prepared_data[] = $temp_data;
						}
					}
				}
			);

			HubWooConnectionMananager::get_instance()->create_or_update_contacts($prepared_data, $mark);
		} else {
			delete_option('hubwoo_contact_vid_update');
			as_unschedule_action('hubwoo_update_contacts_vid');
		}
	}

	/**
	 * Source tracking from Checkout form.
	 *
	 * @since    1.0.4
	 */
	public function hubwoo_submit_checkout_form()
	{

		if (! empty($_REQUEST['woocommerce-process-checkout-nonce'])) {

			$request = sanitize_text_field(wp_unslash($_REQUEST['woocommerce-process-checkout-nonce']));
			$hub_cookie = isset($_COOKIE['hubspotutk']) ? sanitize_text_field(wp_unslash($_COOKIE['hubspotutk'])) : '';
			$nonce_value = wc_get_var($request);

			if (! empty($nonce_value) && wp_verify_nonce($nonce_value, 'woocommerce-process_checkout')) {
				$data       = array();
				$form_id    = get_option('hubwoo_checkout_form_id', '');
				$portal_id  = get_option('hubwoo_pro_hubspot_id', '');
				$form_data  = ! empty($_POST) ? map_deep(wp_unslash($_POST), 'sanitize_text_field') : '';
				$required_data = array(
					'billing_email'      => 'email',
					'billing_first_name' => 'firstname',
					'billing_last_name'  => 'lastname',
				);

				foreach ($required_data as $key => $name) {
					if (array_key_exists($key, $form_data)) {
						$value = $form_data[$key];
						if (empty($value)) {
							continue;
						}
						$data[] = array(
							'name'  => $name,
							'value' => $value,
						);
					}
				}
				if (! empty($data)) {

					$data = array('fields' => $data);
					$context = self::hubwoo_page_source($hub_cookie);

					if (! empty($context['context'])) {
						$data = array_merge($data, $context);
					}
				}
				$response = HubWooConnectionMananager::get_instance()->submit_form_data($data, $portal_id, $form_id);
				if (200 != $response['status_code']) {
					$all_errors = json_decode($response['body']);
					if (! empty($all_errors)) {
						$get_errors = $all_errors->errors;
					}
					if (! empty($get_errors)) {
						$get_error_type = $get_errors[0]->errorType;
					}
					if (! empty($get_error_type) && 'INVALID_HUTK' === $get_error_type) {
						$context_data = self::hubwoo_page_source();
						if (! empty($context_data['context'])) {
							$data = array_merge($data, $context_data);
							HubWooConnectionMananager::get_instance()->submit_form_data($data, $portal_id, $form_id);
						}
					}
				}
			}
		}
	}

	/**
	 * Fetching current page context.
	 *
	 * @since    1.0.4
	 * @param string $hub_cookie hubspot cookies.
	 * @return array $context.
	 */
	public static function hubwoo_page_source($hub_cookie = '')
	{

		$referrer = wp_get_referer();
		$obj_id = 0;
		$ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';

		if ($referrer) {
			$obj_id = url_to_postid($referrer);
		}

		$current_url = get_permalink($obj_id);
		$page_name = get_the_title($obj_id);
		$context = array();

		if (! empty($current_url)) {
			$context['pageUri'] = $current_url;
		}
		if (! empty($page_name)) {
			$context['pageName'] = $page_name;
		}
		if (! empty($hub_cookie)) {
			$context['hutk'] = $hub_cookie;
		}
		if (! empty($ip)) {
			if (filter_var($ip, FILTER_VALIDATE_IP)) {
				$context['ipAddress'] = $ip;
			}
		}
		$context = array(
			'context' => $context,
		);

		return $context;
	}

	/**
	 * Fetching property value.
	 *
	 * @since    1.2.6
	 */
	public function hubwoo_check_property_value()
	{

		if (1 == get_option('hubwoo_pro_lists_setup_completed', 0)) {

			$property_updated = get_option('hubwoo_newsletter_property_update');
			$abandoned_property_updated = get_option('hubwoo_abandoned_property_update');

			if (empty($property_updated)) {

				$flag = true;
				$object_type = 'contacts';
				$property_name = 'newsletter_subscription';
				$property_changed = 'no';

				if (Hubwoo::is_access_token_expired()) {

					$hapikey = HUBWOO_CLIENT_ID;
					$hseckey = HUBWOO_SECRET_ID;
					$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);

					if (! $status) {

						$flag = false;
					}
				}

				if ($flag) {
					$response = HubWooConnectionMananager::get_instance()->hubwoo_read_object_property($object_type, $property_name);

					if (200 == $response['status_code']) {
						$results = json_decode($response['body']);

						if (isset($results->options)) {
							$options = $results->options;

							if ('no' == $options[0]->value || 'yes' == $options[0]->value) {
								$property_changed = 'no';
							} else {
								$property_changed = 'yes';
							}
						}
					}
				}

				update_option('hubwoo_newsletter_property_update', $property_changed, false);
			}

			if (empty($abandoned_property_updated)) {

				$flag = true;
				$object_type = 'contacts';
				$property_name = 'current_abandoned_cart';
				$abandoned_property_changed = 'no';

				if (Hubwoo::is_access_token_expired()) {

					$hapikey = HUBWOO_CLIENT_ID;
					$hseckey = HUBWOO_SECRET_ID;
					$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);

					if (! $status) {

						$flag = false;
					}
				}

				if ($flag) {
					$response = HubWooConnectionMananager::get_instance()->hubwoo_read_object_property($object_type, $property_name);

					if (200 == $response['status_code']) {
						$results = json_decode($response['body']);

						if (isset($results->options)) {
							$options = $results->options;

							if ('no' == $options[0]->value || 'yes' == $options[0]->value) {
								$abandoned_property_changed = 'no';
							} else {
								$abandoned_property_changed = 'yes';
							}
						}
					}
				}

				update_option('hubwoo_abandoned_property_update', $abandoned_property_changed, false);
			}
		}
	}

	/**
	 * Check and create new properties.
	 *
	 * @since 1.4.0
	 */
	public function hubwoo_check_update_changes()
	{
		if (1 == get_option('hubwoo_pro_lists_setup_completed', 0)) {

			$flag = true;
			if (Hubwoo::is_access_token_expired()) {

				$hapikey = HUBWOO_CLIENT_ID;
				$hseckey = HUBWOO_SECRET_ID;
				$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);

				if (! $status) {

					$flag = false;
				}
			}

			if ($flag) {

				if ('yes' != get_option('hubwoo_product_property_created', 'no') && 'yes' != get_option('hubwoo_product_scope_needed', 'no')) {
					$product_properties  = Hubwoo::hubwoo_get_product_properties();

					$response            = HubWooConnectionMananager::get_instance()->create_batch_properties($product_properties, 'products');
					if (201 == $response['status_code'] || 207 == $response['status_code']) {
						update_option('hubwoo_product_property_created', 'yes', false);
					} else if (403 == $response['status_code']) {
						update_option('hubwoo_product_scope_needed', 'yes', false);
					}
				}

				if ('yes' != get_option('hubwoo_deal_property_created', 'no')) {
					$deal_properties  = Hubwoo::hubwoo_get_deal_properties();

					$response         = HubWooConnectionMananager::get_instance()->create_batch_properties($deal_properties, 'deals');
					if (201 == $response['status_code'] || 207 == $response['status_code']) {
						update_option('hubwoo_deal_property_created', 'yes', false);
					}
				}

				if ('yes' != get_option('hubwoo_contact_new_property_created', 'no')) {
					$group_properties[] = array(
						'name'      => 'customer_source_store',
						'label'     => __('Customer Source Store', 'makewebbetter-hubspot-for-woocommerce'),
						'type'      => 'string',
						'fieldType' => 'textarea',
						'formField' => false,
						'groupName' => 'customer_group',
					);

					$response         = HubWooConnectionMananager::get_instance()->create_batch_properties($group_properties, 'contacts');
					if (201 == $response['status_code'] || 207 == $response['status_code']) {
						update_option('hubwoo_contact_new_property_created', 'yes', false);
					}
				}

				if (as_next_scheduled_action('hubwoo_deal_update_schedule')) {
					as_unschedule_action('hubwoo_deal_update_schedule');
				}
			}
		}
		if (1 != get_option('hubwoo_enable_worker_scheduler', 0)) {
			// Deactivate old schedulers
			if (as_next_scheduled_action('hubwoo_cron_schedule')) {
				as_unschedule_action('hubwoo_cron_schedule');
			}
			if (as_next_scheduled_action('hubwoo_contacts_batch_sync')) {
				as_unschedule_all_actions('hubwoo_contacts_batch_sync');
			}
			// The jobs just cancelled above are the only code that ever deletes a
			// hubwoo_batch_users_to_sync_{batch_id} option -- any that were still
			// pending get orphaned the moment those jobs are cancelled, since
			// nothing else in the plugin ever looks for this option again.
			global $wpdb;
			$hubwoo_orphaned_batches = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
					$wpdb->esc_like('hubwoo_batch_users_to_sync_') . '%'
				)
			);
			foreach ($hubwoo_orphaned_batches as $hubwoo_orphaned_batch) {
				delete_option($hubwoo_orphaned_batch);
			}
			if (as_next_scheduled_action('hubwoo_deals_sync_check')) {
				as_unschedule_action('hubwoo_deals_sync_check');
			}
			if (as_next_scheduled_action('hubwoo_ecomm_deal_update')) {
				as_unschedule_action('hubwoo_ecomm_deal_update');
			}
			if (as_next_scheduled_action('hubwoo_ecomm_deal_upsert')) {
				as_unschedule_all_actions('hubwoo_ecomm_deal_upsert');
			}
			if (as_next_scheduled_action('hubwoo_products_sync_check')) {
				as_unschedule_action('hubwoo_products_sync_check');
			}
			if (as_next_scheduled_action('hubwoo_deal_update_schedule')) {
				as_unschedule_action('hubwoo_deal_update_schedule');
			}
			if (as_next_scheduled_action('huwoo_abncart_clear_old_cart')) {
				as_unschedule_action('huwoo_abncart_clear_old_cart');
			}
			if (as_next_scheduled_action('hubwoo_check_scheduler_status')) {
				as_unschedule_action('hubwoo_check_scheduler_status');
			}
			// Activate new schedulers
			HubWoo_Schedulers::get_instance()->hubwoo_initate_schedulers();
			update_option('hubwoo_enable_worker_scheduler', 1, false);
		}

		HubWoo_Schedulers::get_instance()->hubwoo_trigger_heartbeat();
	}

	/**
	 * Update a ecomm deal.
	 *
	 * @since 1.0.0
	 * @param int $order_id order id to be updated.
	 */
	public function hubwoo_ecomm_deal_update_order($order_id)
	{
		if (! empty($order_id)) {
			$order = wc_get_order($order_id);

			$upsert_meta = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_ecomm_deal_upsert', 'no');
			if ($upsert_meta == 'yes') {
				return;
			}

			if ('shop_subscription' == $order->get_type()) {
				return;
			}

			// A previous version bailed out here whenever HPOS was primary AND
			// "Enable compatibility mode" was on -- that combination is WooCommerce's
			// own recommended post-migration configuration, so this silently stopped
			// every order status change from ever flagging the order for HubSpot
			// deal sync, for any store in that state (licensed or not). Removed: the
			// upsert_meta re-entrancy check a few lines above already prevents this
			// from firing twice for the same pending order, and
			// hubwoo_hpos_update_meta_data() below already writes to whichever store
			// is actually primary (Hubwoo::hubwoo_is_hpos_enabled()) regardless of
			// compatibility mode -- there was nothing left for this check to protect
			// against.
			Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_upsert', 'yes');

			HubWoo_Schedulers::get_instance()->hubwoo_trigger_heartbeat();
		}
	}

	/**
	 * Initiate deactivation screen.
	 */
	public static function init_deactivation()
	{
		$params = array();
		try {
			$result = wc_get_template(
				'/templates/deactivation-screen.php',
				$params,
				'',
				plugin_dir_path(__FILE__)
			);
		} catch (\Throwable $th) {
			echo (esc_html($th->getMessage()));
			die;
		}
	}

	/**
	 * Check and delete logs.
	 */
	public function hubwoo_check_logs()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'hubwoo_log';
		$hubwoo_logs_delete_after = (int) get_option('hubwoo_logs_delete_after', '30');
		$delete_timestamp = time() - ($hubwoo_logs_delete_after * DAY_IN_SECONDS);
		$daily_limit = 1500;

		$sql = $wpdb->prepare("DELETE FROM {$table_name} WHERE time < %d ORDER BY time ASC LIMIT %d", $delete_timestamp, $daily_limit);
		$wpdb->query($sql);
	}

	/**
	 * Check and delete woo action schedulers logs.
	 */
	public function hubwoo_check_action_schedulers_logs() {

    	global $wpdb;

    	$actions_table = $wpdb->prefix . 'actionscheduler_actions';
    	$logs_table    = $wpdb->prefix . 'actionscheduler_logs';

		$hubwoo_woo_action_schedulers_logs_delete_after = (int) get_option('hubwoo_woo_action_schedulers_logs_delete_after', '30');
    	$retention_time = time() - ($hubwoo_woo_action_schedulers_logs_delete_after * DAY_IN_SECONDS);
    	$daily_limit    = 1500;

    	$action_ids = $wpdb->get_col(
        	$wpdb->prepare("SELECT action_id FROM {$actions_table} WHERE status = %s AND scheduled_date_gmt < FROM_UNIXTIME(%d) AND ( hook LIKE %s OR hook LIKE %s ) ORDER BY scheduled_date_gmt ASC LIMIT %d",
            	'complete', $retention_time, 'hubwoo\_%', 'huwoo\_%', $daily_limit
        	)
    	);

    	if ( empty( $action_ids ) ) {
        	return;
    	}

    	$ids_placeholder = implode( ',', array_map( 'intval', $action_ids ) );

    	$wpdb->query("DELETE FROM {$logs_table} WHERE action_id IN ({$ids_placeholder})");

    	$wpdb->query("DELETE FROM {$actions_table} WHERE action_id IN ({$ids_placeholder})");
	}

	public function hubwoo_real_time_sync()
	{
		$tasks = array('deals_sync', 'products_sync', 'deals_update', 'contacts_sync');
		foreach ($tasks as $task) {
			if (HubWoo_Schedulers::get_instance()->is_task_running($task)) {
				continue;
			}
			if (as_has_scheduled_action('hubwoo_real_time_task', array('task' => $task))) {
				continue;
			}
			if (! HubWoo_Schedulers::get_instance()->should_schedule_task($task)) {
				continue;
			}
			if (! self::hubwoo_task_has_pending_work($task)) {
				continue;
			}
			// Scheduled (not async-dispatched) so it rides the normal Action Scheduler
			// queue instead of forcing an immediate loopback request to admin-ajax.php.
			as_schedule_single_action(time(), 'hubwoo_real_time_task', array('task' => $task));
		}
	}

	/**
	 * Cheap existence check to avoid queuing a sync task (and the background
	 * action/request that comes with it) when there is nothing for it to do.
	 *
	 * @param string $task task key.
	 * @return bool
	 */
	private static function hubwoo_task_has_pending_work($task)
	{
		switch ($task) {
			case 'deals_sync':
				return self::hubwoo_deals_pending_exists();
			case 'products_sync':
				return self::hubwoo_products_pending_exists();
			case 'deals_update':
				return self::hubwoo_deal_update_pending_exists();
			case 'contacts_sync':
				return self::hubwoo_contacts_pending_exists();
		}

		return false;
	}

	public function hubwoo_real_time_task($task)
	{
		if (!empty($task)) {
			HubWoo_Schedulers::get_instance()->acquire_lock($task);

			try {
				@set_time_limit(300);
				switch ($task) {
					case 'deals_sync':
						self::hubwoo_deals_sync_check();
						break;
					case 'products_sync':
						self::hubwoo_products_sync_check();
						break;
					case 'deals_update':
						self::hubwoo_ecomm_deal_update();
						break;
					case 'contacts_sync':
						self::hubwoo_cron_schedule();
						break;
				}
			} catch (Exception $e) {
				error_log("[HubWoo] FAILED {$task}: " . $e->getMessage());
				throw $e;
			} finally {
				HubWoo_Schedulers::get_instance()->release_lock($task);
			}
		}
	}
}
