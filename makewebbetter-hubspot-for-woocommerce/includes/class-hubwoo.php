<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
 */

if (! class_exists('Hubwoo')) {

	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    makewebbetter-hubspot-for-woocommerce
	 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
	 */
	class Hubwoo
	{

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @var      Hubwoo_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;


		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct()
		{

			if (defined('HUBWOO_VERSION')) {

				$this->version = HUBWOO_VERSION;
			} else {

				$this->version = '1.6.8';
			}

			$this->plugin_name = 'makewebbetter-hubspot-for-woocommerce';
			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Hubwoo_Loader. Orchestrates the hooks of the plugin.
		 * - Hubwoo_I18n. Defines internationalization functionality.
		 * - Hubwoo_Admin. Defines all hooks for the admin area.
		 * - Hubwoo_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 */
		private function load_dependencies()
		{

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoo-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoo-i18n.php';

			/**
			 * The class responsible for handling background data sync from WooCommerce to
			 * HubSpot.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoodatasync.php';

			/**
			 * The class for Managing Enums.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-hubwooenum.php';

			/**
			 * The class for Error Handling.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwooerrorhandling.php';

			/**
			 * The class responsible for plugin constants.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwooconst.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-hubwoo-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public area.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-hubwoo-public.php';

			$this->loader = new Hubwoo_Loader();

			/**
			 * The class responsible for all api actions with hubspot.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwooconnectionmananager.php';

			/**
			 * The class contains all the information related to customer groups and properties.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoocontactproperties.php';

			/**
			 * The class contains are readymade contact details to send it to
			 * hubspot.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoocustomer.php';

			/**
			 * The class responsible for property values.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoopropertycallbacks.php';

			/**
			 * The class responsible for handling ajax requests.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoo-ajax-handler.php';

			/**
			 * The class responsible for rfm configuration settings.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoo-rfm-configuration.php';

			/**
			 * The class responsible for manging guest orders.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwooguestordersmanager.php';

			/**
			 * The class responsible for defining all upsert settings and ecomm mappings for deals and line items
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwooecommproperties.php';

			/**
			 * The class responsible for defining functions related to get values/date for ecomm objects
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwooecommobject.php';

			/**
			 * The class responsible for defining functions related to get values/date for ecomm objects
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwooobjectproperties.php';

			/**
			 * The class responsible for defining functions to return values as per ecomm settings upserted
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwooecommpropertycallbacks.php';

			/**
			 * The class responsible for defining functions to handle schedulers
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoo-schedulers.php';

			/**
			 * One-time migration that fixes autoload on options already stored
			 * from before this plugin started setting it explicitly.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hubwoo-autoload-migration.php';
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Hubwoo_I18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 */
		private function set_locale()
		{

			$plugin_i18n = new Hubwoo_I18n();

			$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 */
		private function define_admin_hooks()
		{

			$plugin_admin = new Hubwoo_Admin($this->get_plugin_name(), $this->get_version());
			$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
			$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
			$this->loader->add_action('admin_init', 'Hubwoo_Autoload_Migration', 'maybe_run');
			$this->loader->add_action('admin_init', $plugin_admin, 'hubwoo_redirect_from_hubspot');
			$this->loader->add_action('admin_init', $plugin_admin, 'hubwoo_pro_add_privacy_message');
			$this->loader->add_action('admin_init', $plugin_admin, 'hubwoo_get_plugin_log');
			$this->loader->add_action('admin_init', $plugin_admin, 'hubwoo_check_property_value');
			$this->loader->add_action('admin_init', $plugin_admin, 'hubwoo_check_update_changes');
			$this->loader->add_action('admin_notices', $plugin_admin, 'hubwoo_review_notice', 99);
			// $this->loader->add_action('admin_notices', $plugin_admin, 'hubwoo_festive_notice', 99);

			//hpos changes
			$this->loader->add_action('admin_notices', $plugin_admin, 'hubwoo_hpos_notice', 99);
			// hubspot deal hooks.
			if ('yes' == get_option('hubwoo_ecomm_deal_enable', 'yes')) {
				$this->loader->add_filter('manage_edit-shop_order_columns', $plugin_admin, 'hubwoo_order_cols', 11);
				$this->loader->add_action('manage_shop_order_posts_custom_column', $plugin_admin, 'hubwoo_order_cols_value', 10, 2);
				// HPOS Orders screen (admin.php?page=wc-orders) uses a separate hook
				// pair from the legacy shop_order list table above -- without these,
				// the HubSpot Actions column never appears there at all, regardless of
				// HPOS/compatibility-mode state, since that screen never fires the
				// legacy hooks. Same callbacks as the legacy pair: hubwoo_order_cols()
				// is a pure array filter (works unchanged either way), and
				// hubwoo_order_cols_value() now normalizes its second argument since
				// this hook passes a WC_Order object rather than a post ID.
				$this->loader->add_filter('manage_woocommerce_page_wc-orders_columns', $plugin_admin, 'hubwoo_order_cols', 11);
				$this->loader->add_action('manage_woocommerce_page_wc-orders_custom_column', $plugin_admin, 'hubwoo_order_cols_value', 10, 2);
			}

			// new schedulers start
			$this->loader->add_action('hubwoo_real_time_sync', $plugin_admin, 'hubwoo_real_time_sync');
			$this->loader->add_action('hubwoo_real_time_task', $plugin_admin, 'hubwoo_real_time_task');
			// new schedulers end

			// deactivation screen.
			$this->loader->add_action('admin_footer', $plugin_admin, 'init_deactivation');

			// hubspot abandon carts.
			$this->loader->add_filter('hubwoo_users', $plugin_admin, 'hubwoo_abncart_users');
			$this->loader->add_filter('hubwoo_contact_modified_fields', $plugin_admin, 'hubwoo_abncart_contact_properties', 10, 2);
			$this->loader->add_filter('hubwoo_pro_track_guest_cart', $plugin_admin, 'hubwoo_abncart_process_guest_data', 10, 2);

			$this->loader->add_action('hubwoo_abncart_clear_old_cart', $plugin_admin, 'hubwoo_abncart_clear_old_cart');

			if (get_option('hubwoo_checkout_form_created', 'no') == 'yes') {
				$this->loader->add_action('woocommerce_checkout_process', $plugin_admin, 'hubwoo_submit_checkout_form');
			}

			if ($this->is_plugin_enable() == 'yes') {

				if ($this->is_setup_completed()) {
					$this->loader->add_action('hubwoo_contacts_batch_sync', $plugin_admin, 'hubwoo_contacts_batch_sync', 10, 3);
					$this->loader->add_filter('hubwoo_unset_workflow_properties', $plugin_admin, 'hubwoo_reset_workflow_properties');
					$this->loader->add_action('woocommerce_order_status_changed', $plugin_admin, 'hubwoo_update_order_changes');
					$this->loader->add_action('set_user_role', $plugin_admin, 'hubwoo_add_user_toupdate', 10);
				}

				if ($this->hubwoo_subs_active()) {
					$this->loader->add_filter('hubwoo_contact_groups', $plugin_admin, 'hubwoo_subs_groups');
					$this->loader->add_filter('hubwoo_active_groups', $plugin_admin, 'hubwoo_active_subs_groups');
				}

				$this->loader->add_action('save_post', $plugin_admin, 'hubwoo_ecomm_update_product', 10, 2);
				$this->loader->add_filter('woocommerce_duplicate_product_exclude_meta', $plugin_admin, 'hubwoo_exclude_product_meta_from_duplicate');

				// HubSpot Deals.
				if ('yes' == get_option('hubwoo_ecomm_deal_enable', 'yes')) {
					$this->loader->add_action('hubwoo_ecomm_deal_upsert', $plugin_admin, 'hubwoo_ecomm_deal_upsert', 10, 2);
				}
				$this->loader->add_action('hubwoo_deals_sync_background', $plugin_admin, 'hubwoo_deals_sync_background', 10, 2);

				$this->loader->add_action('hubwoo_check_logs', $plugin_admin, 'hubwoo_check_logs');
				$this->loader->add_action('hubwoo_check_action_schedulers_logs', $plugin_admin, 'hubwoo_check_action_schedulers_logs');

				// HubSpot deals hooks.
				if ('yes' != get_option('woocommerce_custom_orders_table_enabled', 'no')) {
					$this->loader->add_action('save_post_shop_order', $plugin_admin, 'hubwoo_ecomm_deal_update_order');
				}

				$this->loader->add_action('hubwoo_contacts_sync_background', $plugin_admin, 'hubwoo_contacts_sync_background');
				$this->loader->add_action('hubwoo_update_contacts_vid', $plugin_admin, 'hubwoo_update_contacts_vid');
			}
		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 */
		private function define_public_hooks()
		{

			$plugin_public = new Hubwoo_Public($this->get_plugin_name(), $this->get_version());

			if ($this->is_plugin_enable() == 'yes') {

				$this->loader->add_action('profile_update', $plugin_public, 'hubwoo_woocommerce_save_account_details');
				$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'hubwoo_add_hs_script');
				$this->loader->add_action('user_register', $plugin_public, 'hubwoo_woocommerce_save_account_details');
				$this->loader->add_action('woocommerce_customer_save_address', $plugin_public, 'hubwoo_woocommerce_save_account_details');
				$this->loader->add_action('woocommerce_checkout_update_user_meta', $plugin_public, 'hubwoo_woocommerce_save_account_details');
				$this->loader->add_action('woocommerce_update_order', $plugin_public, 'hubwoo_pro_woocommerce_guest_orders');
				if ('yes' == get_option('hubwoo_checkout_optin_enable', 'no')) {
					$this->loader->add_action('woocommerce_after_checkout_billing_form', $plugin_public, 'hubwoo_pro_checkout_field');
					$this->loader->add_action('woocommerce_checkout_order_processed', $plugin_public, 'hubwoo_pro_process_checkout_optin');
				}
				if ('yes' == get_option('hubwoo_registeration_optin_enable', 'no')) {
					$this->loader->add_action('woocommerce_register_form', $plugin_public, 'hubwoo_pro_register_field');
					$this->loader->add_action('woocommerce_created_customer', $plugin_public, 'hubwoo_save_register_optin');
				}
				$this->loader->add_action('wp_loaded', $plugin_public, 'hubwoo_add_abncart_products', 10);

				$subs_enable = get_option('hubwoo_subs_settings_enable', 'yes');

				if ('yes' == $subs_enable && $this->hubwoo_subs_active()) {

					$this->loader->add_action('woocommerce_renewal_order_payment_complete', $plugin_public, 'hubwoo_pro_save_renewal_orders');
					$this->loader->add_action('woocommerce_scheduled_subscription_payment', $plugin_public, 'hubwoo_pro_save_renewal_orders');
					$this->loader->add_action('woocommerce_subscription_renewal_payment_complete', $plugin_public, 'hubwoo_pro_update_subs_changes');
					$this->loader->add_action('woocommerce_subscription_payment_failed', $plugin_public, 'hubwoo_pro_update_subs_changes');
					$this->loader->add_action('woocommerce_subscription_renewal_payment_failed', $plugin_public, 'hubwoo_pro_update_subs_changes');
					$this->loader->add_action('woocommerce_subscription_payment_complete', $plugin_public, 'hubwoo_pro_update_subs_changes');
					$this->loader->add_action('woocommerce_subscription_status_updated', $plugin_public, 'hubwoo_pro_update_subs_changes');
					$this->loader->add_action('woocommerce_customer_changed_subscription_to_cancelled', $plugin_public, 'hubwoo_save_changes_in_subs');
					$this->loader->add_action('woocommerce_customer_changed_subscription_to_active', $plugin_public, 'hubwoo_save_changes_in_subs');
					$this->loader->add_action('woocommerce_customer_changed_subscription_to_on-hold', $plugin_public, 'hubwoo_save_changes_in_subs');
					$this->loader->add_action('init', $plugin_public, 'hubwoo_subscription_switch');
				}

				// HubSpot Abandon Carts.
				if (get_option('hubwoo_abncart_enable_addon', 'yes') == 'yes') {

					if (get_option('hubwoo_abncart_guest_cart', 'yes') == 'yes') {

						$this->loader->add_action('init', $plugin_public, 'hubwoo_abncart_start_session', 10);
						$this->loader->add_action('template_redirect', $plugin_public, 'hubwoo_track_cart_for_formuser');
						$this->loader->add_action('wp_ajax_nopriv_hubwoo_save_guest_user_cart', $plugin_public, 'hubwoo_save_guest_user_cart');
						$this->loader->add_action('wp_ajax_nopriv_get_order_detail', $plugin_public, 'get_order_detail');
						$this->loader->add_action('woocommerce_after_checkout_billing_form', $plugin_public, 'hubwoo_track_email_for_guest_users', 10);
						$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'hubwoo_enqueue_block_checkout_scripts');
						$this->loader->add_action('wp_ajax_nopriv_hubwoo_block_checkout_save_cart', $plugin_public, 'hubwoo_block_checkout_ajax_handler');
						$this->loader->add_action('wp_ajax_hubwoo_block_checkout_save_cart', $plugin_public, 'hubwoo_block_checkout_ajax_handler');
						$this->loader->add_action('woocommerce_store_api_checkout_order_processed', $plugin_public, 'hubwoo_block_checkout_order_placed');
						$this->loader->add_action('woocommerce_after_checkout_billing_form', $plugin_public, 'get_email_checkout_page');
						$this->loader->add_action('woocommerce_new_order', $plugin_public, 'hubwoo_abncart_woocommerce_new_orders');
						$this->loader->add_action('woocommerce_cart_updated', $plugin_public, 'hubwoo_abncart_track_guest_cart', 99, 0);
						// woocommerce_cart_updated isn't reliably fired by WooCommerce
						// Blocks/Store API cart mutations (add item, change quantity,
						// etc. on the new checkout) -- woocommerce_after_calculate_totals
						// is, since the Store API's own CartController explicitly calls
						// WC_Cart::calculate_totals() on every mutation. Hooking both
						// keeps the classic-checkout behavior unchanged while covering
						// the block-checkout case too.
						$this->loader->add_action('woocommerce_after_calculate_totals', $plugin_public, 'hubwoo_abncart_track_guest_cart', 99, 0);
						$this->loader->add_action('user_register', $plugin_public, 'hubwoo_abncart_user_registeration');
						$this->loader->add_action('wp_logout', $plugin_public, 'hubwoo_clear_session');
					}
					$this->loader->add_filter('woocommerce_update_cart_action_cart_updated', $plugin_public, 'hubwoo_guest_cart_updated');
					$this->loader->add_action('woocommerce_add_to_cart', $plugin_public, 'hubwoo_abncart_woocommerce_add_to_cart', 20, 0);
				}

				$active_plugins = get_option('active_plugins');
				if (in_array('sitepress-multilingual-cms/sitepress.php', $active_plugins)) {
					$this->loader->add_action('woocommerce_thankyou', $plugin_public, 'hubwoo_update_user_prefered_lang');
				}

				$this->loader->add_filter('woocommerce_order_item_get_formatted_meta_data', $plugin_public, 'hubwoo_hide_line_item_meta', 20, 2);
			}
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run()
		{

			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name()
		{

			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Hubwoo_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader()
		{

			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version()
		{

			return $this->version;
		}

		/**
		 * Predefined default hubwoo tabs.
		 *
		 * @since     1.0.0
		 */
		public function hubwoo_default_tabs()
		{

			$default_tabs = array();

			$common_dependency = array('is_oauth_success', 'is_valid_client_ids_stored', 'is_field_setup_completed');

			$default_tabs['hubwoo-overview'] = array(
				'name'       => __('Dashboard', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => array(),
				'title'      => esc_html__('Integrate your WooCommerce store with HubSpot', 'makewebbetter-hubspot-for-woocommerce'),
			);

			$default_tabs['hubwoo-sync-contacts'] = array(
				'name'       => __('Contacts', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => $common_dependency,
				'title'      => esc_html__('Sync all your woocommerce data to HubSpot', 'makewebbetter-hubspot-for-woocommerce'),
			);

			$default_tabs['hubwoo-deals'] = array(
				'name'       => __('Deals', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => $common_dependency,
				'title'      => esc_html__('Sync All of your woocommerce orders as HubSpot Deals', 'makewebbetter-hubspot-for-woocommerce'),
			);

			$default_tabs['hubwoo-abncart'] = array(
				'name'       => __('Abandoned Carts', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => $common_dependency,
				'title'      => esc_html__('Sync all of the cart abandoners on your website', 'makewebbetter-hubspot-for-woocommerce'),
			);

			$default_tabs['hubwoo-automation'] = array(
				'name'       => __('Automation', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => $common_dependency,
				'title'      => esc_html__('Create Workflows to Track ROI and Retrieve Abandoned Carts', 'makewebbetter-hubspot-for-woocommerce'),
			);

			$default_tabs['hubwoo-add-ons']          = array(
				'name'       => __('Add Ons', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => '',
				'title'      => esc_html__('Add-ons for the HubSpot Integrations', 'makewebbetter-hubspot-for-woocommerce'),
			);
			$default_tabs['hubwoo-general-settings'] = array(
				'name'       => __('Settings', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => array('is_oauth_success', 'is_valid_client_ids_stored'),
				'title'      => esc_html__('General And Advanced Settings', 'makewebbetter-hubspot-for-woocommerce'),
			);
			$default_tabs['hubwoo-logs'] = array(
				'name'       => __('Logs', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => array('is_oauth_success', 'is_valid_client_ids_stored'),
				'title'      => esc_html__('HubSpot Logs', 'makewebbetter-hubspot-for-woocommerce'),
			);

			$default_tabs = apply_filters('hubwoo_navigation_tabs', $default_tabs);

			$default_tabs['hubwoo-support'] = array(
				'name'       => __('Support', 'makewebbetter-hubspot-for-woocommerce'),
				'dependency' => array('is_oauth_success', 'is_valid_client_ids_stored'),
				'title'      => esc_html__('Support', 'makewebbetter-hubspot-for-woocommerce'),
			);

			return $default_tabs;
		}

		/**
		 * Checking dependencies for tabs.
		 *
		 * @since     1.0.0
		 * @param array $dependency list of dependencies of function.
		 */
		public function check_dependencies($dependency = array())
		{

			$flag = true;

			global $hubwoo;

			if (count($dependency)) {

				foreach ($dependency as $single_dependency) {

					if (! empty($hubwoo->$single_dependency())) {
						$flag = $flag & $hubwoo->$single_dependency();
					}
				}
			}

			return $flag;
		}

		/**
		 * Get started with setup.
		 *
		 * @since     1.0.0
		 */
		public static function hubwoo_pro_get_started()
		{

			$last_version = self::hubwoo_pro_last_version();

			if (HUBWOO_VERSION != $last_version) {

				return true;
			} elseif (HUBWOO_VERSION != $last_version && ! get_option('hubwoo_pro_get_started', false)) {

				return false;
			} elseif (HUBWOO_VERSION == $last_version && get_option('hubwoo_pro_get_started', false)) {

				return true;
			} else {

				return get_option('hubwoo_pro_get_started', false);
			}
		}

		/**
		 * Fetching the last version from user database
		 *
		 * @since 1.0.0
		 */
		public static function hubwoo_pro_last_version()
		{

			if (self::is_setup_completed()) {

				return get_option('hubwoo_pro_version', '1.6.8');
			} else {

				return HUBWOO_VERSION;
			}
		}

		/**
		 * Verify if the hubspot setup is completed.
		 *
		 * @since 1.0.0
		 * @return boolean true/false
		 */
		public static function is_setup_completed()
		{

			return get_option('hubwoo_pro_setup_completed', false);
		}

		/**
		 * Check if hubspot oauth has been successful.
		 *
		 * @since  1.0.0
		 * @return boolean true/false
		 */
		public function is_oauth_success()
		{

			return get_option('hubwoo_pro_oauth_success', false);
		}

		/**
		 * Check if plugin feature is enbled or not.
		 *
		 * @since  1.0.0
		 * @return boolean true/false
		 */
		public function is_plugin_enable()
		{

			return get_option('hubwoo_pro_settings_enable', 'yes');
		}

		/**
		 * Check if valid hubspot client Ids is stored.
		 *
		 * @since  1.0.0
		 * @return boolean true/false
		 */
		public static function is_valid_client_ids_stored()
		{

			$hapikey = HUBWOO_CLIENT_ID;
			$hseckey = HUBWOO_SECRET_ID;

			if ($hapikey && $hseckey) {

				return get_option('hubwoo_pro_valid_client_ids_stored', false);
			}

			return false;
		}

		/**
		 * Checking the properties setup status.
		 *
		 * @since 1.0.0
		 */
		public function is_field_setup_completed()
		{

			$last_version = self::hubwoo_pro_last_version();

			if (HUBWOO_VERSION != $last_version) {

				return true;
			} else {

				return get_option('hubwoo_fields_setup_completed', false);
			}
		}

		/**
		 * Locate and load appropriate tempate.
		 *
		 * @since   1.0.0
		 * @param string $path path of the file.
		 * @param string $tab tab name.
		 */
		public function load_template_view($path, $tab = '')
		{

			$file_path = HUBWOO_ABSPATH . $path;

			if (file_exists($file_path)) {

				include $file_path;
			} else {

				$file_path = apply_filters('hubwoo_load_template_path', $tab);

				if (file_exists($file_path)) {

					include $file_path;
				} else {

					/* translators: %s: file path */
					$notice = sprintf(esc_html__('Unable to locate file path at location %s some features may not work properly in HubSpot Integration, please contact us!', 'makewebbetter-hubspot-for-woocommerce'), $file_path);
					$this->hubwoo_notice($notice, 'error');
				}
			}
		}

		/**
		 * Show admin notices.
		 *
		 * @param  string $message    Message to display.
		 * @param  string $type       notice type, accepted values - error/update/update-nag.
		 * @since  1.0.0
		 */
		public static function hubwoo_notice($message, $type = 'error')
		{

			$classes = 'notice ';

			switch ($type) {

				case 'update':
					$classes .= 'updated';
					break;

				case 'update-nag':
					$classes .= 'update-nag';
					break;

				case 'success':
					$classes .= 'notice-success is-dismissible';
					break;
				case 'hubwoo-notice':
					$classes .= 'hubwoo-notice';
					break;
				default:
					$classes .= 'error';
			}

			$notice  = '<div class="' . $classes . '">';
			$notice .= '<p>' . $message . '</p>';
			$notice .= '</div>';

			echo wp_kses_post($notice);
		}

		/**
		 * Fetch owner email info from HubSpot.
		 *
		 * @since 1.0.0
		 * @return boolean true/false
		 */
		public function hubwoo_owners_email_info()
		{

			$owner_email = get_option('hubwoo_pro_hubspot_id', '');

			if (empty($owner_email)) {

				if (self::is_valid_client_ids_stored()) {

					$flag = true;

					if (self::is_access_token_expired()) {

						$hapikey = HUBWOO_CLIENT_ID;
						$hseckey = HUBWOO_SECRET_ID;
						$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);

						if (! $status) {

							$flag = false;
						}
					}

					if ($flag) {

						$owner_email = HubWooConnectionMananager::get_instance()->hubwoo_get_owners_info();

						if (! empty($owner_email)) {

							update_option('hubwoo_pro_hubspot_id', $owner_email, false);
						}
					}
				}
			}

			return $owner_email;
		}

		/**
		 * Check if access token is expired.
		 *
		 * @since     1.0.0
		 * @return boolean true/false
		 */
		public static function is_access_token_expired()
		{

			$get_expiry = get_option('hubwoo_pro_token_expiry', false);

			if ($get_expiry) {

				$current_time = time();

				if (($get_expiry > $current_time) && ($get_expiry - $current_time) <= 50) {

					return true;
				} elseif (($current_time > $get_expiry)) {

					return true;
				}
			}

			return false;
		}

		/**
		 * Reset saved options for setup.
		 *
		 * @param bool $redirect whether to redirect.
		 * @param bool $delete_meta whether to delete meta.
		 * @since     1.0.0
		 */
		public function hubwoo_switch_account($redirect = true, $delete_meta = false)
		{

			global $wpdb;
			$wpdb->query("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE '%hubwoo%'");
			as_unschedule_action('hubwoo_contacts_sync_background');
			as_unschedule_action('hubwoo_deals_sync_background');
			as_unschedule_action('hubwoo_update_contacts_vid');

			if ($delete_meta) {
				delete_option('WooCommerce: set Order Recency 1 Ratings');
				delete_option('WooCommerce: set Order Recency 2 Ratings');
				delete_option('WooCommerce: set Order Recency 3 Ratings');
				delete_option('WooCommerce: set Order Recency 4 Ratings');
				delete_option('WooCommerce: set Order Recency 5 Ratings');
				delete_option('WooCommerce: MQL to Customer lifecycle stage Conversion');
				delete_option('WooCommerce: Welcome New Customer & Get a 2nd Order');
				delete_option('WooCommerce: 2nd Order Thank You & Get a 3rd Order');
				delete_option('WooCommerce: 3rd Order Thank You');
				delete_option('WooCommerce: ROI Calculation');
				delete_option('WooCommerce: Order Workflow');
				delete_option('WooCommerce: Update Historical Order Recency Rating');
				delete_option('WooCommerce: After order Workflow');
				delete_option('WooCommerce: Enroll Customers for Recency Settings');
				delete_metadata('user', 0, 'hubwoo_pro_user_data_change', '', true);
				delete_metadata('user', 0, 'hubwoo_user_vid', '', true);
				delete_metadata('post', 0, 'hubwoo_pro_user_data_change', '', true);
				delete_metadata('post', 0, 'hubwoo_user_vid', '', true);
				delete_metadata('post', 0, 'hubwoo_pro_guest_order', '', true);
				delete_metadata('post', 0, 'hubwoo_ecomm_deal_id', '', true);
				delete_metadata('post', 0, 'hubwoo_ecomm_deal_created', '', true);
				delete_metadata('post', 0, 'hubwoo_order_line_item_created', '', true);
				delete_metadata('post', 0, 'hubwoo_invalid_deal', '', true);
				delete_metadata('post', 0, 'hubwoo_ecomm_pro_id', '', true);
				delete_metadata('post', 0, 'hubwoo_product_synced', '', true);
				delete_metadata('post', 0, 'hubwoo_ecomm_invalid_pro', '', true);
				delete_metadata('post', 0, 'hubwoo_invalid_contact', '', true);
				delete_metadata('post', 0, 'hubwoo_order_sync_hash', '', true);
				delete_metadata('post', 0, 'hubwoo_ecomm_deal_upsert', '', true);

				// Clear the HPOS orders-meta table unconditionally, the same way
				// hubwoo_make_db_query()'s sync counts read both tables unconditionally --
				// gating this on the *current* HPOS toggle left behind orphaned meta from
				// a previously-connected account whenever HPOS had been on at some point
				// but was off at the moment of switching accounts. That leftover meta was
				// then picked up by hubwoo_setup_overview()'s dual-table counts, making a
				// freshly-connected portal look like it already had synced data.
				$wpdb->query("DELETE FROM `{$wpdb->prefix}wc_orders_meta` WHERE `meta_key` IN ('hubwoo_pro_guest_order', 'hubwoo_ecomm_deal_id', 'hubwoo_ecomm_deal_created', 'hubwoo_user_vid', 'hubwoo_pro_user_data_change', 'hubwoo_order_line_item_created', 'hubwoo_invalid_deal', 'hubwoo_order_sync_hash', 'hubwoo_invalid_contact', 'hubwoo_ecomm_deal_upsert')");
			}

			if ($redirect) {
				wp_safe_redirect(admin_url('admin.php?page=hubwoo'));
			} else {
				update_option('hubwoo_clear_previous_options', 'yes', false);
			}
			exit();
		}

		/**
		 * Getting the final groups after the setup.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_get_final_groups()
		{

			$final_groups = array();

			$hubwoo_groups = HubWooContactProperties::get_instance()->_get('groups');

			$last_version = self::hubwoo_pro_last_version();

			if (HUBWOO_VERSION != $last_version && '2.0.0' > $last_version) {

				if (is_array($hubwoo_groups) && count($hubwoo_groups)) {

					foreach ($hubwoo_groups as $single_group) {

						if ('subscriptions_details' == $single_group['name'] && ! self::is_subs_group_setup_completed()) {

							$final_groups[] = array(
								'detail' => $single_group,
								'status' => 'false',
							);
						} else {

							$final_groups[] = array(
								'detail' => $single_group,
								'status' => 'created',
							);
						}
					}
				}
			} else {

				$added_groups = get_option('hubwoo-groups-created', array());

				if (get_option('hubwoo_abncart_added', 0) == 1) {
					$added_groups = apply_filters('hubwoo_active_groups', $added_groups);
				}

				if (is_array($hubwoo_groups) && count($hubwoo_groups)) {

					foreach ($hubwoo_groups as $single_group) {

						if (in_array($single_group['name'], $added_groups)) {

							$final_groups[] = array(
								'detail' => $single_group,
								'status' => 'created',
							);
						} else {

							$final_groups[] = array(
								'detail' => $single_group,
								'status' => 'false',
							);
						}
					}
				}
			}

			return $final_groups;
		}

		/**
		 * Verify if the hubspot subscription group setup is completed.
		 *
		 * @since 1.0.0
		 * @return boolean true/false
		 */
		public static function is_subs_group_setup_completed()
		{

			$last_version = self::hubwoo_pro_last_version();

			if (HUBWOO_VERSION != $last_version) {

				if (get_option('hubwoo_subs_setup_completed', false)) {

					return true;
				} else {

					if (in_array('subscriptions_details', get_option('hubwoo-groups-created', array()))) {

						return true;
					} else {

						return false;
					}
				}
			}
		}

		/**
		 * Required groups for lists and workflows.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_workflows_and_list_groups()
		{

			return array('rfm_fields', 'roi_tracking', 'customer_group', 'order', 'abandoned_cart');
		}

		/**
		 * Required properties for lists abd workflows.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_workflows_and_list_properties()
		{

			$required_fields = array();

			$roi_tracking_properties = HubWooContactProperties::get_instance()->_get('properties', 'roi_tracking');

			if (! empty($roi_tracking_properties)) {

				foreach ($roi_tracking_properties as $single_property) {

					if (isset($single_property['name'])) {

						$required_fields[] = $single_property['name'];
					}
				}
			}

			$required_fields[] = 'newsletter_subscription';
			$required_fields[] = 'total_number_of_orders';
			$required_fields[] = 'last_order_date';
			$required_fields[] = 'last_order_value';
			$required_fields[] = 'average_days_between_orders';
			$required_fields[] = 'monetary_rating';
			$required_fields[] = 'order_frequency_rating';
			$required_fields[] = 'order_recency_rating';

			return $required_fields;
		}

		/**
		 * Get final lists to be created on HubSpot.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_get_final_lists()
		{

			$final_lists = array();

			$final_lists = HubWooContactProperties::get_instance()->_get('lists');
			// if ( is_array( $hubwoo_lists ) && count( $hubwoo_lists ) ) {

			// 	foreach ( $hubwoo_lists as $single_list ) {
			// 		$list_filter_created = self::is_list_filter_created( $single_list['filters'] );
			// 		if ( $list_filter_created ) {

			// 			$final_lists[] = $single_list;
			// 		}
			// 	}
			// }

			if (count($final_lists)) {

				$add_lists = get_option('hubwoo-lists-created', array());

				$filtered_final_lists = array();

				foreach ($final_lists as $single_list) {

					if (in_array($single_list['name'], $add_lists)) {

						$filtered_final_lists[] = array(
							'detail' => $single_list,
							'status' => 'created',
						);
					} else {

						$filtered_final_lists[] = array(
							'detail' => $single_list,
							'status' => 'false',
						);
					}
				}
			}

			return $filtered_final_lists;
		}

		/**
		 * Checking the list filter to be created.
		 *
		 * @since 1.0.0
		 * @param array $filters list of filters in a list.
		 */
		public function is_list_filter_created($filters)
		{

			$status = true;

			if (is_array($filters) && count($filters)) {

				foreach ($filters as $key => $single_filter) {

					foreach ($single_filter as $single_filter_detail) {

						if (isset($single_filter_detail['property'])) {

							$status &= self::check_field_existence($single_filter_detail['property']);
						}
					}
				}
			}

			return $status;
		}

		/**
		 * Checking field existense for the lists.
		 *
		 * @since 1.0.0
		 * @param string $field name of the field.
		 */
		public static function check_field_existence($field = '')
		{

			$status = false;

			if ('lifecyclestage' == $field) {

				return true;
			}

			global $hubwoo;

			$hubwoo_fields = get_option('hubwoo-properties-created', array());

			$status = in_array($field, $hubwoo_fields) ? true : false;

			return $status;
		}

		/**
		 * Checking the lists setup status.
		 *
		 * @since 1.0.0
		 */
		public function is_list_setup_completed()
		{

			return get_option('hubwoo_pro_lists_setup_completed', false);
		}

		/**
		 * Workflow description.
		 *
		 * @since 1.0.0
		 */
		public function get_workflow_description()
		{
			return array(
				'WooCommerce: MQL to Customer lifecycle stage Conversion' => 'It is designed to get a qualified lead to make the first purchase.',
				'WooCommerce: Welcome New Customer & Get a 2nd Order' => 'This workflow triggers shortly after a first purchase, and are designed to push the customer towards a 2nd order.',
				'WooCommerce: 2nd Order Thank You & Get a 3rd Order' => 'This workflow triggers shortly after the 2nd Purchase and is designed to thank customers to become repeat buyers.',
				'WooCommerce: 3rd Order Thank You'         => 'This workflow triggers for those customers who have placed their order for at least 3 times.',
				'WooCommerce: ROI Calculation'             => 'This workflow triggers to track conversions in your marketing system by knowing Return-On-Investment.',
				'WooCommerce: After order Workflow'        => 'This workflow triggers when any new order gets placed.',
				'WooCommerce: Order Workflow'              => 'This workflow triggers to track purchase if a prospect gets convert into a customer.',
				'WooCommerce: set Order Recency 1 Ratings' => 'This workflow triggers for those customers with Order Recency Rating - 1.',
				'WooCommerce: set Order Recency 2 Ratings' => 'This workflow triggers for those customers with Order Recency Rating - 2',
				'WooCommerce: set Order Recency 3 Ratings' => 'This workflow triggers for those customers with Order Recency Rating - 3',
				'WooCommerce: set Order Recency 4 Ratings' => 'This workflow triggers for those customers with Order Recency Rating - 4',
				'WooCommerce: set Order Recency 5 Ratings' => 'This workflow triggers for those customers with Order Recency Rating - 5',
				'WooCommerce: Update Historical Order Recency Rating' => 'This workflow triggers when any historical order recency rating gets updated.',
				'WooCommerce: Enroll Customers for Recency Settings' => 'This workflow triggers when any customer has made its first purchase and enrolled for Order Recency.',
				'WooCommerce: Abandoned Cart Recovery'     => 'This workflow triggers when any user abandons their cart on your store.',
			);
		}

		/**
		 * Checking the lists setup status.
		 *
		 * @since 1.0.0
		 */
		public function get_lists_description()
		{

			return array(
				'Repeat Buyers'                 => 'Repeat Buyers is the smart list of HubSpot which helps to segment customers those who shop on your store regularly and their HubSpot property Average days between orders is also under the count of 30 days.',
				'DisEngaged Customers'          => 'Disengaged Customers is the smart list where you can see the list of customers that didn’t reach you from more than 60-180 days. It is the most useful list where you can target your those customers who are disengaged for a long period of time.',
				'Abandoned Cart'                => 'Send Reminders, Capture emails and Recover Lost Sales in real-time with an Automated Cart Recovery Solution for your WooCommerce store.',
				'Engaged Customers'             => 'Engaged Customers is the smart list of HubSpot that will list all your contacts whose last brought item is less than 60days. It will show the list of your loyal and regular customers.',
				'Customers'                     => 'This list will enroll customers according to their customer’s lifecycle stage. Whenever any customer’s lifecycle changes it would filter all those customers.',
				'Marketing Qualified Leads'     => 'It will enlist all those marketing qualified lead (MQL) who has been deemed more likely to become a customer compared to other leads. This qualification is based on what web pages a person has visited, what they’ve downloaded, and similar engagement with the business’s content.',
				'Leads'                         => 'It will list all those leads who have indicated interest in your company’s product or service in some way, shape, or form.',
				'Bought four or more times'     => 'It will list all those customers who have purchased 4 times from your store. You can provide special benefits to those customers.',
				'Three time purchase customers' => 'It will list all those customers who have purchased only three times from your store. You can encourage them to buy more frequently.',
				'Two time purchase customers'   => 'It will list all those customers who have brought only 2 times from your store. You can pay special attention to those customers as they are interested but you have to educate them about your product and service.',
				'One time purchase customers'   => 'It will list all those customers whose total number of order is 1. As the list shows, the total number of order is 1, so you have to work hard on these customers and start nurturing them and educate them about your product and services.',
				'Newsletter Subscriber'         => 'It will list all those newsletter subscribers who have subscribed for a printed report containing news (information) of the activities of a business or an organization (institutions, societies, associations) that is sent by mail regularly to all its members, customers, employees or people, who are interested.',
				'Low Spenders'                  => 'This list shows the contact property whose Monetary rating is equal to 1. It notifies that your customer is not spending much on your store.',
				'Mid Spenders'                  => 'This list shows the contact property whose Monetary rating is equal to 3. It means that he not frequently buying from your store.',
				'Big Spenders'                  => 'This list shows the contact property whose Monetary rating is equal to 5. These are the customers who are spending lavishly and purchasing more often from your store.',
				'About to Sleep'                => 'It is the list in which customer whose Recency Frequency and Monetary (RFM) value lie between 1 & 2 and they are about to sleep. It means that their engagement with your website is getting less on each successive day.',
				'Customers needing attention'   => 'In this list, Monetary and Frequency of the customer are 3 but Recency lies between 1 & 2. This list shows that customer has spent time and money both on your website but his last order was long-ago.',
				'New Customers'                 => 'This list shows new contact whose Frequency and Recency is 1. They are the new customer they are not yet engaged with your website.',
				'Low Value Lost Customers'      => 'It is the list of those customers whose Recency, Frequency & Monetary is 1. These are the customer who is on the verge of getting lost as their engagement with the website is very low.',
				'Churning Customers'            => 'It is the list of those customers whose Monetary and Order frequency is 5 but Recency is 1. The churning rate, also known as the rate of attrition, it is the percentage of subscribers to a service who discontinue their subscriptions to the service within a given time period.',
				'Loyal Customers'               => 'It is the list of those customers whose Frequency and Recency of order is 5. It is the list which exhibits customer loyalty when they consistently purchase a certain product or brand over an extended period of time and describes the loyalty which is established between a customer and companies.',
				'Best Customers'                => 'It is the list of those customers whose RFM (Recency, Frequency & Monetary) rating is perfect 5. It is the list of your loyal customers that are consistently positive & emotional, physical attribute-based satisfaction and perceived value of an experience, which includes the product or services.',
			);
		}

		/**
		 * Required lists to create.
		 *
		 * @since 1.0.0
		 * @param string $list_name name of the list.
		 */
		public function required_lists_to_create($list_name)
		{

			$required_lists = array('Customers', 'Leads', 'Abandoned Cart');

			return in_array($list_name, $required_lists) ? "checked='checked'" : '';
		}

		/**
		 * Getting the final groups after the setup.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_get_final_workflows()
		{

			$final_workflows = array();

			$hubwoo_workflows = HubWooContactProperties::get_instance()->_get('workflows');

			$add_workflows = get_option('hubwoo-workflows-created', array());

			if (is_array($hubwoo_workflows) && count($hubwoo_workflows)) {

				foreach ($hubwoo_workflows as $single_workflow) {

					if (in_array($single_workflow['name'], $add_workflows)) {

						$final_workflows[] = array(
							'detail' => $single_workflow,
							'status' => 'created',
						);
					} else {

						$final_workflows[] = array(
							'detail' => $single_workflow,
							'status' => 'false',
						);
					}
				}
			}

			return $final_workflows;
		}

		/**
		 * All dependencies for workflows.
		 *
		 * @since 1.0.0
		 */
		public static function hubwoo_workflows_dependency()
		{

			$workflows = array();

			$workflows[] = array(
				'workflow'     => 'WooCommerce: set Order Recency 1 Ratings',
				'dependencies' => array('WooCommerce: Order Workflow'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: set Order Recency 2 Ratings',
				'dependencies' => array('WooCommerce: set Order Recency 1 Ratings'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: set Order Recency 3 Ratings',
				'dependencies' => array('WooCommerce: set Order Recency 2 Ratings'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: set Order Recency 4 Ratings',
				'dependencies' => array('WooCommerce: set Order Recency 3 Ratings'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: set Order Recency 5 Ratings',
				'dependencies' => array('WooCommerce: set Order Recency 4 Ratings', 'WooCommerce: Order Workflow'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: Enroll Customers for Recency Settings',
				'dependencies' => array('WooCommerce: Update Historical Order Recency Rating'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: Update Historical Order Recency Rating',
				'dependencies' => array('WooCommerce: set Order Recency 5 Ratings'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: Order Workflow',
				'dependencies' => array('WooCommerce: ROI Calculation', 'WooCommerce: After order Workflow'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: After order Workflow',
				'dependencies' => array('WooCommerce: 3rd Order Thank You', 'WooCommerce: 2nd Order Thank You & Get a 3rd Order', 'WooCommerce: Welcome New Customer & Get a 2nd Order'),
			);

			$workflows[] = array(
				'workflow'     => 'WooCommerce: Abandoned Cart Recovery',
				'dependencies' => array('WooCommerce: Order Workflow'),
			);

			return $workflows;
		}

		/**
		 * Checking workflow dependency.
		 *
		 * @since 1.0.0
		 * @param string $workflow name of workflow.
		 */
		public function is_workflow_dependent($workflow = '')
		{

			if (! empty($workflow)) {

				$workflow_dependencies = self::hubwoo_workflows_dependency();

				$dependencies = array();

				$status = true;

				if (! empty($workflow_dependencies)) {

					foreach ($workflow_dependencies as $single_workflow) {

						if (isset($single_workflow['workflow']) && $workflow == $single_workflow['workflow']) {

							$dependencies = $single_workflow['dependencies'];
							break;
						}
					}
				}

				if (! empty($dependencies)) {

					foreach ($dependencies as $single_dependency) {

						$status &= self::is_hubwoo_workflow_exists($single_dependency);
					}
				}

				return $status;
			}
		}

		/**
		 * Check for workflow existence.
		 *
		 * @since 1.0.0
		 * @param string $workflow workflow name.
		 */
		public static function is_hubwoo_workflow_exists($workflow = '')
		{

			$status = false;

			if (! empty($workflow)) {

				return in_array($workflow, get_option('hubwoo-workflows-created', array()));
			}

			return $status;
		}

		/**
		 * Checking for workflows scope.
		 *
		 * @since     1.0.0
		 */
		public function is_automation_enabled()
		{

			$scopes = get_option('hubwoo_pro_account_scopes', array());

			HubWooConnectionMananager::get_instance()->get_workflows();

			if (empty($scopes)) {

				HubWooConnectionMananager::get_instance()->hubwoo_pro_get_access_token_info();
			}

			if (in_array('automation', $scopes)) {

				return true;
			}
		}


		/**
		 * Get array of all user roles of WordPress.
		 *
		 * @since 1.0.0
		 */
		public static function hubwoo_get_user_roles()
		{

			global $wp_roles;

			$exiting_user_roles = array();

			$user_roles = ! empty($wp_roles->role_names) ? $wp_roles->role_names : array();

			if (is_array($user_roles) && count($user_roles)) {

				foreach ($user_roles as $role => $role_info) {

					$role_label = ! empty($role_info) ? $role_info : $role;

					$exiting_user_roles[$role] = $role_label;
				}

				$exiting_user_roles['guest_user'] = 'Guest User';
			}

			return $exiting_user_roles;
		}

		/**
		 * Check whether subscriptions are active or not.
		 *
		 * @since 1.0.0
		 * @return boolean true/false
		 */
		public static function hubwoo_subs_active()
		{

			$flag = false;

			if (in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters('active_plugins', get_option('active_plugins')))) {

				$flag = true;
			}

			return $flag;
		}

		/**
		 * Get full country name.
		 *
		 * @since 1.0.0
		 * @param string $value country abbreviation.
		 */
		public static function map_country_by_abbr($value)
		{

			if (! empty($value)) {
				if (class_exists('WC_Countries')) {
					$wc_countries = new WC_Countries();
					$countries    = $wc_countries->__get('countries');
				}
				if (! empty($countries)) {
					foreach ($countries as $abbr => $country_name) {
						if ($value == $abbr) {
							$value = $country_name;
							break;
						}
					}
				}
			}
			return $value;
		}

		/**
		 * Get full state name.
		 *
		 * @since 1.0.0
		 * @param string $value abbrevarion for state.
		 * @param string $country name of country.
		 */
		public static function map_state_by_abbr($value, $country)
		{

			if (! empty($value) && ! empty($country)) {
				if (class_exists('WC_Countries')) {
					$wc_countries = new WC_Countries();
					$states       = $wc_countries->__get('states');
				}
				if (! empty($states)) {
					foreach ($states as $country_abbr => $country_states) {
						if ($country == $country_abbr) {
							foreach ($country_states as $state_abbr => $state_name) {
								if ($value == $state_abbr) {
									break;
								}
							}
							break;
						}
					}
				}
			}
			return $value;
		}

		/**
		 * Filter contact properties with the help of created properties.
		 *
		 * @since 1.0.0
		 * @param array $properties list of contact properties.
		 */
		public function hubwoo_filter_contact_properties($properties = array())
		{

			$filtered_properties = array();

			$created_properties = array_map(
				function ($property) {
					return str_replace("'", '', $property);
				},
				get_option('hubwoo-properties-created', array())
			);

			if (! empty($properties) && count($properties)) {

				foreach ($properties as $single_property) {

					if (! empty($single_property['property'])) {

						if (in_array($single_property['property'], $created_properties)) {

							$filtered_properties[] = $single_property;
						}
					}
				}
			}

			return $filtered_properties;
		}

		/**
		 * Returning saved access token.
		 *
		 * @since 1.0.0
		 */
		public static function hubwoo_get_access_token()
		{

			if (self::is_valid_client_ids_stored()) {

				if (self::is_access_token_expired()) {
					$hapikey = HUBWOO_CLIENT_ID;
					$hseckey = HUBWOO_SECRET_ID;
					$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);
				}
			}

			return get_option('hubwoo_pro_access_token', false);
		}

		/**
		 * Get auth url.
		 *
		 * @since 1.0.0
		 */
		public static function hubwoo_get_auth_url()
		{

			$url = 'https://app.hubspot.com/oauth/authorize';

			$hapikey = HUBWOO_CLIENT_ID;

			$hubspot_url = add_query_arg(
				array(
					'response_type'  => 'code',
					'state'          => urlencode(self::get_oauth_state()),
					'client_id'      => $hapikey,
					'optional_scope' => 'automation%20files%20forms%20e-commerce%20crm.objects.custom.read%20crm.objects.custom.write',
					'scope'          => 'oauth%20crm.objects.owners.read%20crm.objects.contacts.write%20crm.objects.companies.write%20crm.lists.write%20crm.objects.companies.read%20crm.lists.read%20crm.objects.deals.read%20crm.objects.deals.write%20crm.objects.contacts.read%20crm.schemas.companies.write%20crm.schemas.contacts.write%20crm.schemas.deals.read%20crm.schemas.deals.write%20crm.schemas.contacts.read%20crm.schemas.companies.read',
					'redirect_uri'   => 'https://auth.makewebbetter.com/integration/hubspot-auth/',
				),
				$url
			);

			return $hubspot_url;
		}

		/**
		 * Get oauth state with current instance redirect url.
		 *
		 * @since 1.4.4
		 * @return string State.
		 */
		public static function get_oauth_state()
		{

			$nonce = wp_create_nonce('hubwoo_security');

			$admin_redirect_url = admin_url();
			$args               = array(
				'mwb_nonce'  => $nonce,
				'mwb_source' => 'hubspot',
			);
			$admin_redirect_url = add_query_arg($args, $admin_redirect_url);
			return $admin_redirect_url;
		}

		/**
		 * Get selected deal stage by order key.
		 *
		 * @since 1.0.0
		 * @param string $order_key order key.
		 */
		public static function get_selected_deal_stage($order_key)
		{

			$deal_stage = array();
			if (! empty($order_key)) {
				$saved_mapping = get_option('hubwoo_ecomm_final_mapping', array());
				if (! empty($saved_mapping)) {
					foreach ($saved_mapping as $single_mapping) {
						if ($order_key == $single_mapping['status']) {
							$deal_stage = $single_mapping['deal_stage'];
							break;
						}
					}
				}
			}
			return $deal_stage;
		}

		/**
		 * Get contact sync status.
		 *
		 * @since 1.0.0
		 */
		public static function get_sync_status()
		{

			// Cast defensively: a corrupted/legacy option value here must not crash the divide below.
			$sync_status['current'] = (int) get_option('hubwoo_deals_current_sync_count', 0);
			$sync_status['total']   = (int) get_option('hubwoo_deals_current_sync_total', 0);
			$sync_status['eta_deals_sync'] = '';

			if ($sync_status['total']) {
				$perc                          = round(($sync_status['current'] / $sync_status['total']) * 100);
				$sync_status['deals_progress'] = $perc > 100 ? 99 : $perc;
				$sync_status['eta_deals_sync'] = self::hubwoo_create_sync_eta($sync_status['current'], $sync_status['total'], 5, 5);
			}

			if (($sync_status['current'] == $sync_status['total']) || ($sync_status['current'] > $sync_status['total'])) {
				self::hubwoo_stop_sync('stop-deal');
			}
			return $sync_status;
		}

		/**
		 * Pull the single aggregate value out of a $wpdb->get_results() call that
		 * returns exactly one row with one column (a COUNT(...) query), regardless
		 * of what that column happens to be aliased as. Replaces the old array_walk
		 * pass that juggled a mix of stdClass-row and plain-array shapes to get to
		 * the same value.
		 *
		 * @param array $db_result Result of $wpdb->get_results().
		 * @return int
		 */
		private static function hubwoo_extract_scalar_count($db_result)
		{
			if (empty($db_result)) {
				return 0;
			}

			$row = (array) $db_result[0];

			return (int) array_pop($row);
		}

		/**
		 * Get contact sync status.
		 *
		 * @since 1.0.0
		 * @param string $query_action which query to run.
		 * @return array|string query result.
		 */
		public static function hubwoo_make_db_query($query_action)
		{

			global $wpdb;

			switch ($query_action) {
				case 'total_products_to_sync':
					$acceptable_post_status = apply_filters('hubwoo_accept_product_status', array('publish'));
					$quoted_statuses = array_map(function ($status) use ($wpdb) {
						return "'" . esc_sql($status) . "'";
					}, $acceptable_post_status);
					$status_sql = implode(',', $quoted_statuses);
					return $wpdb->get_results("SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type IN ( 'product', 'product_variation' ) AND ID NOT IN (SELECT post_parent FROM {$wpdb->posts} WHERE post_type IN ( 'product', 'product_variation' ) ) AND post_status  IN ($status_sql)");
				case 'total_products_waiting':
					// Eligible products that haven't reached ANY terminal state yet --
					// not synced (hubwoo_ecomm_pro_id), not permanently excluded
					// (hubwoo_ecomm_invalid_pro, e.g. variable products, which are
					// never synced this way), and not otherwise marked handled
					// (hubwoo_product_synced). A plain total-minus-synced subtraction
					// would count invalid/excluded products as "waiting" forever,
					// since they never get hubwoo_ecomm_pro_id either.
					$acceptable_post_status = apply_filters('hubwoo_accept_product_status', array('publish'));
					$quoted_statuses = array_map(function ($status) use ($wpdb) {
						return "'" . esc_sql($status) . "'";
					}, $acceptable_post_status);
					$status_sql = implode(',', $quoted_statuses);
					return $wpdb->get_results(
						"SELECT COUNT(p.ID) FROM {$wpdb->posts} p
						WHERE p.post_type IN ( 'product', 'product_variation' )
						AND p.ID NOT IN (SELECT post_parent FROM {$wpdb->posts} WHERE post_type IN ( 'product', 'product_variation' ) )
						AND p.post_status IN ($status_sql)
						AND NOT EXISTS (
							SELECT 1 FROM {$wpdb->postmeta} pm
							WHERE pm.post_id = p.ID
							AND pm.meta_key IN ('hubwoo_ecomm_pro_id', 'hubwoo_ecomm_invalid_pro', 'hubwoo_product_synced')
						)"
					);
				case 'total_synced_products':
					return $wpdb->get_results("SELECT COUNT(post_id) FROM {$wpdb->postmeta} WHERE meta_key = 'hubwoo_ecomm_pro_id'");
				case 'total_synced_deals':
					// Counted by hubwoo_ecomm_deal_id (the real HubSpot deal ID),
					// not hubwoo_ecomm_deal_created -- that flag gets set to 'yes'
					// even when the deal create/update call FAILED (see
					// hubwoo_ecomm_sync_deal(), which sets hubwoo_invalid_deal
					// alongside it in that case), so counting by it silently
					// includes permanently-failed deals as "synced". Still checks
					// both storage tables, same reasoning as before.
					return $wpdb->get_results(
						"SELECT COUNT(DISTINCT order_id) AS synced_count FROM (
							SELECT post_id AS order_id FROM {$wpdb->postmeta} WHERE meta_key = 'hubwoo_ecomm_deal_id' AND meta_value != ''
							UNION
							SELECT order_id FROM {$wpdb->prefix}wc_orders_meta WHERE meta_key = 'hubwoo_ecomm_deal_id' AND meta_value != ''
						) combined"
					);
				case 'total_synced_contacts':
					// Counted by hubwoo_user_vid (the real HubSpot contact ID), not
					// hubwoo_pro_user_data_change -- that flag gets set to 'synced'
					// even when HubSpot rejected the contact (see
					// HubwooObjectProperties::hubwoo_create_update_single_contact(),
					// which sets hubwoo_invalid_contact alongside it in that case),
					// so counting by it silently includes permanently-invalid
					// contacts as "synced".
					return $wpdb->get_results("SELECT COUNT(DISTINCT user_id) FROM {$wpdb->usermeta} WHERE meta_key = 'hubwoo_user_vid' AND meta_value != ''");
				case 'total_synced_guest_contacts':
					// Counted by unique billing email, not by order -- a guest who
					// placed multiple orders only ever becomes ONE HubSpot Contact,
					// so counting their orders here would inflate this figure
					// relative to what actually shows up as Contacts in HubSpot, and
					// relative to how total_synced_contacts above counts registered
					// users (one row per person). Counted by hubwoo_user_vid (the
					// real HubSpot contact ID), not hubwoo_pro_guest_order -- same
					// "set to 'synced' even on failure" reasoning as
					// total_synced_contacts above. Still checks both storage tables.
					return $wpdb->get_results(
						"SELECT COUNT(DISTINCT combined.email) AS synced_count FROM (
							SELECT LOWER(pm_email.meta_value) AS email
							FROM {$wpdb->postmeta} pm_flag
							INNER JOIN {$wpdb->postmeta} pm_email ON pm_email.post_id = pm_flag.post_id AND pm_email.meta_key = '_billing_email'
							WHERE pm_flag.meta_key = 'hubwoo_user_vid' AND pm_flag.meta_value != ''
							UNION
							SELECT LOWER(o.billing_email) AS email
							FROM {$wpdb->prefix}wc_orders_meta om
							INNER JOIN {$wpdb->prefix}wc_orders o ON om.order_id = o.id
							WHERE om.meta_key = 'hubwoo_user_vid' AND om.meta_value != ''
						) combined WHERE combined.email != ''"
					);
				default:
					return '';
			}
		}

		/**
		 * Get contact sync status.
		 *
		 * @since 1.0.0
		 */
		public static function get_deals_presenter()
		{

			$display_data = array();

			// Products, contacts, and deals now sync independently of each other
			// (each gated only by its own real-time heartbeat task), so the Deals
			// tab no longer has a "setup is still running" phase to gate behind --
			// it always shows its full content.
			$display_data['view_all']     = 'block';
			$display_data['view_button']  = 'inline-block';
			$display_data['view_mapping'] = 'block';

			// The Ecommerce Pipeline couldn't be created (e.g. the portal's HubSpot
			// plan only allows one deal pipeline) and the plugin fell back to an
			// existing pipeline instead. Surface a button to retry pipeline creation
			// once the portal can support it (e.g. after a plan upgrade).
			$display_data['show_pipeline_retry'] = ('yes' === get_option('hubwoo_ecomm_pipeline_fallback', 'no')) ? 'inline-block' : 'none';

			// HPOS is active on the store but the HPOS Compatibility add-on isn't
			// (installed+active+licensed) -- deal sync doesn't work correctly under
			// HPOS without it, so lock the tab behind a prompt instead of letting
			// the user configure something that silently won't sync.
			$hpos_enabled = self::hubwoo_is_hpos_enabled();
			$display_data['show_hpos_lock'] = ($hpos_enabled && ! self::hubwoo_check_hpos_active()) ? 'true' : 'false';

			// Distinguish "the add-on isn't installed at all" (needs to buy and
			// install it) from "it's installed and active, just never licensed"
			// (needs to go enter the license key) -- the lock modal shows
			// different content/CTA for each instead of always pointing at a
			// purchase page the user may not even need.
			$hpos_addon_active_plugin = in_array(
				'hubspot-woocommerce-hpos-compatibility/hubspot-woocommerce-hpos-compatibility.php',
				get_option('active_plugins', array()),
				true
			);
			$display_data['hpos_lock_needs_activation'] = ($hpos_enabled && $hpos_addon_active_plugin && 'true' === $display_data['show_hpos_lock']) ? 'true' : 'false';

			$display_data['is_dsync'] = 'no';
			if (1 == get_option('hubwoo_deals_sync_running', 0)) {
				$display_data['is_dsync'] = 'yes';
				$display_data['message']  = 'block';
				$display_data['button']   = 'none';
				$display_data['btn_data'] = 'stop-deal';
				$display_data['btn_text'] = 'Stop Sync';
			} else {
				$display_data['btn_text'] = 'Start Sync';
				$display_data['btn_data'] = 'start-deal';
				$display_data['message']  = 'none';
				$display_data['button']   = 'inline-block';
			}

			$scopes = get_option('hubwoo_pro_account_scopes', array());

			$display_data['scope_notice'] = 'none';

			return $display_data;
		}

		/**
		 * Get all deal stages
		 *
		 * @since 1.0.0
		 * @param bool $update true/false.
		 */
		public static function get_all_deal_stages($update = false)
		{

			$deal_stages = get_option('hubwoo_fetched_deal_stages', '');
			$pipeline_id = get_option('hubwoo_ecomm_pipeline_id', '');
			if (empty($deal_stages) || $update || empty($pipeline_id)) {

				$deal_stages = self::fetch_deal_stages_from_pipeline();
				if (! empty($deal_stages)) {
					update_option('hubwoo_fetched_deal_stages', $deal_stages, false);
				}
			}
			return $deal_stages;
		}

		/**
		 * Get deal stages from sales pipeline.
		 *
		 * @since 1.0.0
		 * @param string $pipeline_label name of pipeline ( default Ecommerce Pipline).
		 * @param bool   $only_stages return only stages (default true).
		 */
		/**
		 * Pick a pipeline to fall back to when the plugin's own "Ecommerce Pipeline"
		 * can't be created or found -- most commonly because the portal's HubSpot
		 * plan doesn't allow more than one deal pipeline. Prefers HubSpot's own
		 * default pipeline (id 'default') if present, otherwise just uses whichever
		 * pipeline the portal returned first: any existing pipeline is better than
		 * leaving onboarding with nothing to show.
		 *
		 * @param array $all_deal_pipelines Result of HubWooConnectionMananager::fetch_all_deal_pipelines().
		 * @return array
		 */
		private static function hubwoo_get_fallback_pipeline($all_deal_pipelines)
		{
			if (empty($all_deal_pipelines['results'])) {
				return array();
			}

			foreach ($all_deal_pipelines['results'] as $single_pipeline) {
				if (isset($single_pipeline['id']) && 'default' === $single_pipeline['id']) {
					return $single_pipeline;
				}
			}

			return $all_deal_pipelines['results'][0];
		}

		public static function fetch_deal_stages_from_pipeline($pipeline_label = 'Ecommerce Pipeline', $only_stages = true)
		{

			$all_deal_pipelines = HubWooConnectionMananager::get_instance()->fetch_all_deal_pipelines();
			$fetched_pipeline   = array();
			if (! empty($all_deal_pipelines['results'])) {
				update_option('hubwoo_potal_pipelines', $all_deal_pipelines['results'], false);
				array_map(
					function ($single_pipeline) use ($pipeline_label, &$fetched_pipeline, $only_stages) {

						if ($single_pipeline['label'] == $pipeline_label) {

							$fetched_pipeline = $only_stages ? $single_pipeline['stages'] : $single_pipeline;

							$pipeline_id = $single_pipeline['id'];
							update_option('hubwoo_ecomm_pipeline_id', $pipeline_id, false);
							update_option('hubwoo_ecomm_pipeline_fallback', 'no', false);

							self::update_deal_stages_mapping($fetched_pipeline);
						}
					},
					$all_deal_pipelines['results']
				);
			}

			if (empty($fetched_pipeline)) {

				$create_pipeline = array(
					'label' => 'Ecommerce Pipeline',
					'displayOrder' => 0,
					'stages' => self::get_ecomm_deal_stages(),
				);

				$flag = true;
				if (self::is_access_token_expired()) {

					$hapikey = HUBWOO_CLIENT_ID;
					$hseckey = HUBWOO_SECRET_ID;
					$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);

					if (! $status) {

						$flag = false;
					}
				}

				if ($flag) {

					$response = HubWooConnectionMananager::get_instance()->create_deal_pipeline($create_pipeline);
					if (201 == $response['status_code']) {
						$all_deal_pipelines = HubWooConnectionMananager::get_instance()->fetch_all_deal_pipelines();
						array_map(
							function ($single_pipeline) use ($pipeline_label, &$fetched_pipeline, $only_stages) {

								if ($single_pipeline['label'] == $pipeline_label) {

									$fetched_pipeline = $only_stages ? $single_pipeline['stages'] : $single_pipeline;
									$pipeline_id = $single_pipeline['id'];
									update_option('hubwoo_ecomm_pipeline_id', $pipeline_id, false);
									update_option('hubwoo_ecomm_pipeline_fallback', 'no', false);
								}
							},
							$all_deal_pipelines['results']
						);

						self::update_deal_stages_mapping($fetched_pipeline);
					}
				}

				if (empty($fetched_pipeline)) {
					// Pipeline creation either didn't run (token refresh failed) or
					// HubSpot rejected it -- most commonly because the portal's plan
					// doesn't allow another deal pipeline. Fall back to a pipeline the
					// portal already has instead of leaving onboarding with nothing to
					// show and Step 4's deal-stage dropdowns empty.
					$fallback_pipeline = self::hubwoo_get_fallback_pipeline($all_deal_pipelines);

					if (! empty($fallback_pipeline)) {
						$fetched_pipeline = $only_stages ? $fallback_pipeline['stages'] : $fallback_pipeline;
						update_option('hubwoo_ecomm_pipeline_id', $fallback_pipeline['id'], false);
						update_option('hubwoo_ecomm_pipeline_fallback', 'yes', false);
						self::update_deal_stages_mapping($fetched_pipeline);
					}
				}
			}

			return $fetched_pipeline;
		}

		/**
		 * Get deal stages from sales pipeline.
		 *
		 * @since 1.4.0
		 * @param string $fetched_pipeline array of deal stages.
		 */
		public static function update_deal_stages_mapping($fetched_pipeline = array())
		{

			if (empty($fetched_pipeline)) {
				return;
			}

			$mapping_with_new_stages = array();

			foreach ($fetched_pipeline as $single_pipeline) {
				$label = isset($single_pipeline['label']) ? $single_pipeline['label'] : '';
				switch ($label) {
					case 'Checkout Abandoned':
						$mapping_with_new_stages['checkout_abandoned'] = $single_pipeline['id'];
						break;
					case 'Payment Pending/Failed':
						$mapping_with_new_stages['checkout_pending'] = $single_pipeline['id'];
						break;
					case 'On hold':
						$mapping_with_new_stages['checkout_completed'] = $single_pipeline['id'];
						break;
					case 'Processing':
						$mapping_with_new_stages['processed'] = $single_pipeline['id'];
						break;
					case 'Completed':
						$mapping_with_new_stages['shipped'] = $single_pipeline['id'];
						break;
					case 'Refunded/Cancelled':
						$mapping_with_new_stages['cancelled'] = $single_pipeline['id'];
						break;
				}
			}
			update_option('hubwoo_ecomm_pipeline_created', 'yes', false);
			update_option('hubwoo_ecomm_deal_stage_ids', $mapping_with_new_stages, false);
			update_option('hubwoo_ecomm_final_mapping', self::hubwoo_deals_mapping(), false);
		}

		/**
		 * Fetch Ecomm pipeline deal stages.
		 *
		 * @since 1.4.0
		 * @return array formatted array with get request.
		 */
		public static function get_ecomm_deal_stages()
		{
			return array(
				array(
					'label' => 'Checkout Abandoned',
					'displayOrder' => 0,
					'metadata' => array(
						'isClosed' => false,
						'probability' => 0.1,
					),
				),
				array(
					'label' => 'Payment Pending/Failed',
					'displayOrder' => 1,
					'metadata' => array(
						'isClosed' => false,
						'probability' => 0.2,
					),
				),
				array(
					'label' => 'On hold',
					'displayOrder' => 2,
					'metadata' => array(
						'isClosed' => false,
						'probability' => 0.6,
					),
				),
				array(
					'label' => 'Processing',
					'displayOrder' => 3,
					'metadata' => array(
						'isClosed' => true,
						'probability' => 1.0,
					),
				),
				array(
					'label' => 'Completed',
					'displayOrder' => 4,
					'metadata' => array(
						'isClosed' => true,
						'probability' => 1.0,
					),
				),
				array(
					'label' => 'Refunded/Cancelled',
					'displayOrder' => 5,
					'metadata' => array(
						'isClosed' => true,
						'probability' => 0.0,
					),
				),

			);
		}

		/**
		 * Stop deals sync.
		 *
		 * @since 1.0.0
		 * @param string $type stop a specific task.
		 * @return void
		 */
		public static function hubwoo_stop_sync($type)
		{

			if ('stop-contact' == $type) {

				update_option('hubwoo_ocs_data_synced', true, false);
				delete_option('hubwoo_background_process_running');
				delete_option('hubwoo_total_ocs_contact_need_sync');
				delete_option('hubwoo_ocs_contacts_synced');
				as_unschedule_action('hubwoo_contacts_sync_background');
			} elseif ('stop-deal' == $type) {

				delete_option('hubwoo_deals_sync_running');
				delete_option('hubwoo_deals_current_sync_count');
				delete_option('hubwoo_deals_current_sync_total');
				as_unschedule_action('hubwoo_deals_sync_background');
			}
		}

		/**
		 * Get the eCommerce Store Data.
		 *
		 * @since 1.0.0
		 * @return array store data .
		 */
		public static function get_store_data()
		{

			$blog_name = get_bloginfo('name');
			$blog_id   = preg_replace('/[^a-zA-Z0-9]/', '', $blog_name);
			$store     = array(
				'id'       => $blog_id . '-' . get_current_blog_id(),
				'label'    => $blog_name,
				'adminUri' => get_admin_url(),
			);
			return $store;
		}

		/**
		 * Stop deals sync.
		 *
		 * @since 1.0.0
		 * @param int $number_of_products number of products to prepare.
		 * @return array products info as eCommerce products.
		 */
		public static function hubwoo_get_product_data($number_of_products = 5)
		{

			$contraints = array(
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

			$products = self::hubwoo_ecomm_get_products($number_of_products, $contraints);

			$products_info = array();

			if (is_array($products) && count($products)) {

				$object_type = 'PRODUCT';

				foreach ($products as $product_id) {

					if (! empty($product_id)) {

						$product      = wc_get_product($product_id);
						$product_type = $product->get_type();

						if ('variable' == $product_type && (! empty($product_type)) || 'variable-subscription' == $product_type || null == $product_type) {
							update_post_meta($product_id, 'hubwoo_ecomm_invalid_pro', 'yes');
							continue;
						} else {

							$hubwoo_ecomm_product           = new HubwooEcommObject($product_id, $object_type);
							$properties                     = $hubwoo_ecomm_product->get_object_properties();
							$properties                     = apply_filters('hubwoo_map_ecomm_' . $object_type . '_properties', $properties, $product_id);
							$properties['description']      = isset($properties['pr_description']) ? $properties['pr_description'] : '';

							unset($properties['pr_description']);
							$products_info[$product_id]     = array(
								'properties'       => $properties,
							);
						}
					}
				}
			}
			return $products_info;
		}

		/**
		 * Retrieve all of the products.
		 *
		 * @since 1.0.0
		 * @param int   $post_per_page number of products to prepare.
		 * @param array $constraints meta query constraints.
		 * @return array products ids.
		 */
		public static function hubwoo_ecomm_get_products($post_per_page = 10, $constraints = array())
		{

			$response = array(
				'status_code' => 400,
				'reponse'     => 'No Products Found',
			);
			$query    = new WP_Query();
			$request  = array(
				'post_type'           => array('product', 'product_variation'),
				'posts_per_page'      => $post_per_page,
				'post_status'         => apply_filters('hubwoo_accept_product_status', array('publish')),
				'orderby'             => 'date',
				'order'               => 'desc',
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			);

			if (! empty($constraints)) {
				if (isset($constraints['relation'])) {
					$request['meta_query']['relation'] = array_pop($constraints);
				}
				$request['meta_query'] = array_merge($constraints);
			}

			$response = $query->query($request);
			return $response;
		}

		/**
		 * Update the eCommerce pipeline deal stages
		 * with WooCommerce Deal stages and probability.
		 *
		 * @since 1.0.0
		 * @return array deal stage data.
		 */
		public static function hubwoo_deal_stage_model()
		{
			return array(
				'checkout_abandoned' => array(
					'label' => esc_html__('Checkout Abandoned', 'makewebbetter-hubspot-for-woocommerce'),
					'metadata' => array(
						'isClosed' => false,
						'probability' => 0.1,
					),
				),
				'checkout_pending'   => array(
					'label'    => esc_html__('Payment Pending/Failed', 'makewebbetter-hubspot-for-woocommerce'),
					'metadata' => array(
						'isClosed'    => false,
						'probability' => 0.2,
					),
				),
				'checkout_completed' => array(
					'label'    => esc_html__('On hold', 'makewebbetter-hubspot-for-woocommerce'),
					'metadata' => array(
						'isClosed'    => false,
						'probability' => 0.6,
					),
				),
				'processed'          => array(
					'label'    => esc_html__('Processing', 'makewebbetter-hubspot-for-woocommerce'),
					'metadata' => array(
						'isClosed'    => true,
						'probability' => 1,
					),
				),
				'shipped'            => array(
					'label'    => esc_html__('Completed', 'makewebbetter-hubspot-for-woocommerce'),
					'metadata' => array(
						'isClosed'    => true,
						'probability' => 1,
					),
				),
				'cancelled'          => array(
					'label'    => esc_html__('Refunded/Cancelled', 'makewebbetter-hubspot-for-woocommerce'),
					'metadata' => array(
						'isClosed'    => true,
						'probability' => 0,
					),
				),
			);
		}

		/**
		 * Get the default model of order status and deal stage.
		 *
		 * @since 1.0.0
		 * @return array mapped deal stage and order status.
		 */
		public static function hubwoo_deals_mapping()
		{

			$mapping = array();

			$default_dealstages = array(
				'wc-pending'    => 'checkout_pending',
				'wc-processing' => 'processed',
				'wc-on-hold'    => 'checkout_completed',
				'wc-completed'  => 'shipped',
				'wc-cancelled'  => 'cancelled',
				'wc-refunded'   => 'cancelled',
				'wc-failed'     => 'checkout_pending',
			);

			if ('yes' == get_option('hubwoo_ecomm_pipeline_created', 'no')) {
				$new_stages = get_option('hubwoo_ecomm_deal_stage_ids', true);
				foreach ($default_dealstages as $key => $value) {
					$new_stage_value = isset($new_stages[$value]) ? $new_stages[$value] : '';
					$default_dealstages[$key] = $new_stage_value;
				}
			}

			$mapping = array_map(
				function ($order_status) use ($default_dealstages) {
					$mapped_data['status'] = $order_status;
					if (array_key_exists($order_status, $default_dealstages)) {
						$mapped_data['deal_stage'] = $default_dealstages[$order_status];
					} else {
						$mapped_data['deal_stage'] = 'checkout_completed';
					}
					return $mapped_data;
				},
				array_keys(self::hubwoo_get_valid_order_statuses())
			);
			return $mapping;
		}

		/**
		 * Get the default model of order status and deal stage.
		 *
		 * @since 1.0.0
		 * @return array mapped deal stage and order status.
		 */
		public static function hubwoo_sales_deals_mapping()
		{
			$default_dealstages = array(
				'wc-pending'    => 'appointmentscheduled',
				'wc-processing' => 'contractsent',
				'wc-on-hold'    => 'presentationscheduled',
				'wc-completed'  => 'closedwon',
				'wc-cancelled'  => 'closedlost',
				'wc-refunded'   => 'closedlost',
				'wc-failed'     => 'appointmentscheduled',
			);

			update_option('hubwoo_ecomm_pipeline_created', 'yes', false);
			$mapping = array_map(
				function ($order_status) use ($default_dealstages) {
					$mapped_data['status'] = $order_status;
					if (array_key_exists($order_status, $default_dealstages)) {
						$mapped_data['deal_stage'] = $default_dealstages[$order_status];
					} else {
						$mapped_data['deal_stage'] = 'presentationscheduled';
					}
					return $mapped_data;
				},
				array_keys(self::hubwoo_get_valid_order_statuses())
			);

			return $mapping;
		}

		/**
		 * Setup the overview section of the dashboard.
		 *
		 * @since 1.0.0
		 * @param bool $install_plugin default ( false ).
		 * @return array|void $display_data  display data for the HS plugin.
		 */
		public function hubwoo_setup_overview($install_plugin = false)
		{

			if ('no' == get_option('hubwoo_checkout_form_created', 'no')) {
				$form_data = self::form_data_model(HubwooConst::CHECKOUTFORM);
				$flag = true;
				if (self::is_access_token_expired()) {

					$hapikey = HUBWOO_CLIENT_ID;
					$hseckey = HUBWOO_SECRET_ID;
					$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token($hapikey, $hseckey);

					if (! $status) {

						$flag = false;
					}
				}

				if ($flag) {
					$res       = HubWooConnectionMananager::get_instance()->create_form_data($form_data);
					if (200 == $res['status_code']) {
						update_option('hubwoo_checkout_form_created', 'yes', true);
						$res = json_decode($res['body'], true);
						if (isset($res['guid'])) {
							update_option('hubwoo_checkout_form_id', $res['guid'], false);
						}
					} else {
						HubwooErrorHandling::get_instance()->hubwoo_handle_response($res, HubwooConst::CHECKOUTFORM);
					}
				}
			}

			if ($install_plugin) {

				WC_Install::background_installer(
					'leadin',
					array(
						'name'      => esc_html__('HubSpot All-In-One Marketing - Forms, Popups, Live Chat', 'makewebbetter-hubspot-for-woocommerce'),
						'repo-slug' => 'leadin',
					)
				);
?>
				<script type="text/javascript">
					window.open("<?php echo esc_url(admin_url('admin.php?page=leadin')); ?>")
					window.location.href = "<?php echo esc_url(admin_url('admin.php?page=hubwoo')); ?>"
				</script>
<?php
				return;
			}

			$display_data = array();
			if (! in_array('leadin/leadin.php', get_option('active_plugins'), true)) {
				$display_data['plugin-install']['label'] = 'Install and Activate';
				$display_data['plugin-install']['href']  = wp_nonce_url('?page=hubwoo&task=install-plugin', 'hubwoo_install_plugin');
			} else {
				$display_data['plugin-install']['label'] = 'Activated';
				$display_data['plugin-install']['href']  = 'javascript:void(0)';
			}

			$last_sync = get_option('hubwoo_last_sync_date', '');

			$display_data['last_sync'] = 'Last Sync: Waiting to sync';

			if (! empty($last_sync)) {
				$date = new DateTime();
				$date->setTimestamp($last_sync);
				$display_data['last_sync'] = 'Last Sync: ' . date_format($date, 'jS F Y \a\t g:ia ');
			}
			// wc_get_orders() with paginate lets WooCommerce compute the total via its
			// own COUNT query (resolving HPOS vs legacy storage internally), instead
			// of pulling every matching order ID into PHP just to count them -- with
			// tens of thousands of orders, fetching the full list (and then diffing
			// it in PHP against the synced-ids list) is the expensive part, not the
			// query itself.
			$overview_deal_statuses = array_keys( self::hubwoo_get_valid_order_statuses() );

			$eligible_orders_query = wc_get_orders(array(
				'return'      => 'ids',
				'post_status' => $overview_deal_statuses,
				'paginate'    => true,
				'limit'       => 1,
			));
			$total_eligible_orders = (int) $eligible_orders_query->total;

			// Checks both the legacy postmeta table and the HPOS orders-meta table
			// directly (a COUNT, not a fetched ID list), rather than picking one based
			// on the current HPOS toggle -- see hubwoo_make_db_query() for why: a
			// store that switched storage mode can have this meta sitting in whichever
			// table was active when it was written, regardless of which one is active
			// now.
			$synced_deals_count = self::hubwoo_extract_scalar_count(self::hubwoo_make_db_query('total_synced_deals'));

			$deals_left_count = max(0, $total_eligible_orders - $synced_deals_count);

			$display_data['deals_left'] = $deals_left_count > 0 ? $deals_left_count . ' waiting to sync' : 'Sync completed';
			$display_data['deal']       = $synced_deals_count;

			// Same reasoning as hubwoo_get_total_contact_need_sync() below -- this
			// covers both registered users and guest orders, checking both storage
			// tables for the guest-order half, so it's accurate regardless of HPOS
			// state, licensing, or a mid-migration split between the two tables.
			$total_contacts_left = self::hubwoo_get_total_contact_need_sync();

			$display_data['contacts_left'] = empty($total_contacts_left) ? 'Sync completed' : $total_contacts_left . ' waiting to sync';

			$display_data['reg_users']      = self::hubwoo_extract_scalar_count(self::hubwoo_make_db_query('total_synced_contacts'));
			$display_data['guest_users']    = self::hubwoo_extract_scalar_count(self::hubwoo_make_db_query('total_synced_guest_contacts'));
			$display_data['product']        = self::hubwoo_extract_scalar_count(self::hubwoo_make_db_query('total_synced_products'));
			$display_data['total_products'] = self::hubwoo_extract_scalar_count(self::hubwoo_make_db_query('total_products_to_sync'));

			// Not total_products - product: that subtraction counts a permanently
			// excluded/invalid product (e.g. a variable product, which never gets
			// hubwoo_ecomm_pro_id either) as "waiting to sync" forever, even though
			// it will never actually sync. total_products_waiting excludes those.
			$products_waiting_count        = self::hubwoo_extract_scalar_count(self::hubwoo_make_db_query('total_products_waiting'));
			$display_data['products_left'] = $products_waiting_count > 0 ? $products_waiting_count . ' waiting to sync' : 'Sync completed';
			return $display_data;
		}

		/**
		 * Create an ETA for the current running sync.
		 *
		 * @since 1.0.0
		 * @param int $current current sync count.
		 * @param int $total total sync count.
		 * @param int $timer scheduled timer.
		 * @param int $limiter limiter of the sync.
		 * @return string $eta_string  returns the calculated eta string.
		 */
		public static function hubwoo_create_sync_eta($current, $total, $timer, $limiter)
		{
			$eta_string       = '';
			$left_items_timer = round(($total - $current) / $limiter) * $timer;

			if ($left_items_timer > 90) {
				$float_timer = number_format(($left_items_timer / 60), 2);
				$hours       = intval($float_timer);
				$minutes     = round(($float_timer - $hours) * 0.6);
				$eta_string  = "{$hours} hours and {$minutes} minutes ";
			} elseif (0 == $left_items_timer) {
				$eta_string = 'less than a minute';
			} else {
				$eta_string = "{$left_items_timer} minutes";
			}
			return $eta_string;
		}

		/**
		 * Handle the Contact sync for failed cases.
		 *
		 * @since 1.0.0
		 * @param array  $ids object ids to be marked.
		 * @param string $type type of object id.
		 * @return void.
		 */
		public static function hubwoo_marked_sync($ids, $type)
		{

			if (! empty($ids)) {

				$emails    = '';
				$user_data = array();

				$method_calls['user']  = array(
					'get'        => 'get_user_meta',
					'update'     => 'update_user_meta',
					'get_key'    => 'billing_email',
					'update_key' => 'hubwoo_pro_user_data_change',
				);
				if ( Hubwoo::hubwoo_is_hpos_enabled() ) {
					$method_calls['order'] = array(
						'get'        => function( $id, $key, $single ) {
							$order = wc_get_order( $id );
							if ( ! $order ) return '';
							if ( '_billing_email' === $key ) {
								return $order->get_billing_email();
							}
							return $order->get_meta( $key, $single );
						},
						'update'     => function( $id, $key, $value ) {
							$order = wc_get_order( $id );
							if ( $order ) {
								$order->update_meta_data( $key, $value );
								$order->save();
							}
						},
						'get_key'    => '_billing_email',
						'update_key' => 'hubwoo_pro_guest_order',
					);
				} else {
					$method_calls['order'] = array(
						'get'        => 'get_post_meta',
						'update'     => 'update_post_meta',
						'get_key'    => '_billing_email',
						'update_key' => 'hubwoo_pro_guest_order',
					);
				}
				$unsynced_ids          = array_filter(
					$ids,
					function ($id) use (&$method_calls, &$type) {
						return empty($method_calls[$type]['get']($id, 'hubwoo_user_vid', true));
					}
				);

				if (empty($unsynced_ids) && 'user' == $type) {
					foreach ($ids as $id) {
						$method_calls[$type]['update']($id, $method_calls[$type]['update_key'], 'synced');
					}
					return;
				}

				switch ($type) {
					case 'user':
						foreach ($unsynced_ids as $id) {
							$user      = get_user_by('id', $id);
							$usr_email = $user->data->user_email;
							if (! empty($usr_email)) {
								$usr_email               = strtolower($usr_email);
								$user_data[$usr_email] = $id;
								$emails                 .= 'email=' . $usr_email . '&';
							}
						}
						break;
					case 'order':
						foreach ($unsynced_ids as $id) {
							$usr_email = $method_calls[$type]['get']($id, $method_calls[$type]['get_key'], true);
							if (! empty($usr_email)) {
								$usr_email               = strtolower($usr_email);
								$user_data[$usr_email] = $id;
								$emails                 .= 'email=' . $usr_email . '&';
							}
						}
						break;
					default:
						return;
				}

				$response = HubWooConnectionMananager::get_instance()->hubwoo_get_batch_vids($emails);

				if (200 == $response['status_code'] && ! empty($response['body'])) {
					$users = json_decode($response['body'], true);
					if (0 == count($users)) {
						return;
					}

					foreach ($users as $vid => $data) {
						if (! empty($data['properties']['email'])) {
							if (array_key_exists($data['properties']['email']['value'], $user_data)) {
								$method_calls[$type]['update']($user_data[$data['properties']['email']['value']], 'hubwoo_user_vid', $vid);
								$method_calls[$type]['update']($user_data[$data['properties']['email']['value']], $method_calls[$type]['update_key'], 'synced');
							}
						}
					}
				}
			}
		}

		/**
		 * Onboarding questionaire Model.
		 *
		 * @since 1.0.0
		 * @return array.
		 */
		public static function hubwoo_onboarding_questionaire()
		{

			return array(
				'mwb_hs_familarity'    => array(
					'allow'   => '',
					'label'   => 'Which of these sounds most like your HubSpot ability?',
					'options' => array(
						'',
						'I have never used a CRM before',
						'I\'m new to HubSpot, but I have used a CRM before',
						'I know my way around HubSpot pretty well',
					),
				),
				'mwb_woo_familarity'   => array(
					'allow'   => '',
					'label'   => 'Which of these sounds most like your WooCommerce ability?',
					'options' => array(
						'',
						'I have never used an e-Commerce platform before',
						'I\'m new to WooCommerce, but I have used an e-Commerce platform before',
						'I know my way around WooCommerce pretty well',
					),
				),
				'which_hubspot_packages_do_you_currently_use_' => array(
					'allow'   => 'multiple',
					'label'   => 'Which HubSpot plan you are using?',
					'options' => array(
						'I don’t currently use HubSpot',
						'HubSpot Free',
						'Marketing Starter',
						'Marketing Pro',
						'Marketing Enterprise',
						'Sales Starter',
						'Sales Pro',
						'Sales Enterprise',
						'Service Starter',
						'Service Pro',
						'Service Enterprise',
						'CMS Hub',
						'Other',
					),
				),
			);
		}

		/**
		 * Form Model Data.
		 *
		 * @since 1.0.0
		 * @param stgring $type type of form model.
		 * @return array.
		 */
		public static function form_data_model($type)
		{
			switch ($type) {
				case HubwooConst::CHECKOUTFORM:
					return
						array(
							'name'            => HubwooConst::CHECKOUTFORMNAME,
							'submitText'      => 'Submit',
							'formFieldGroups' => array(
								array(
									'fields' => array(
										array(
											'name'     => 'firstname',
											'label'    => 'First Name',
											'required' => false,
										),
										array(
											'name'     => 'lastname',
											'label'    => 'Last Name',
											'required' => false,
										),
										array(
											'name'     => 'email',
											'label'    => 'Email',
											'required' => false,
										),
									),
								),
							),
						);
				default:
					return array();
			}
		}

		/**
		 * Get contact sync status.
		 *
		 * Counts only contacts actually queued for historical sync (registered
		 * users/guest orders never touched yet, filtered by the same role/date
		 * settings the historical sync itself uses) rather than every user and
		 * guest order on the site — that broader count made the progress bar
		 * denominator wildly inaccurate.
		 *
		 * The guest-order half checks both the legacy postmeta table and the HPOS
		 * orders-meta table directly (rather than picking one based on the current
		 * HPOS toggle, the way HubwooDataSync::hubwoo_get_all_unique_user() does for
		 * the actual sync-candidate queries), so this stays accurate on a store that
		 * has switched storage mode and left some historical meta behind in the
		 * table that isn't active anymore.
		 *
		 * The registered half works from a COUNT total (WP_User_Query::get_total())
		 * rather than fetching every matching ID into PHP -- a WP user only ever
		 * has one row for the sync-status meta key, so counting rows already means
		 * counting people. The guest half can't take that shortcut: an order count
		 * isn't a person count when the same guest can place several orders, so it
		 * fetches the matching order IDs and counts DISTINCT billing emails among
		 * the ones not yet handled, instead of just subtracting two totals.
		 *
		 * @since 1.2.7
		 * @return int number of contacts still needing historical sync.
		 */
		public static function hubwoo_get_total_contact_need_sync()
		{
			global $wpdb;

			$roles = get_option('hubwoo_customers_role_settings', array());
			if (empty($roles)) {
				global $hubwoo;
				$roles = array_keys($hubwoo->hubwoo_get_user_roles());
			}

			$date_range = false;
			if ('yes' == get_option('hubwoo_customers_manual_sync', 'no')) {
				$date_range = true;
				$from_date  = get_option('hubwoo_users_from_date', gmdate('d-m-Y'));
				$upto_date  = get_option('hubwoo_users_upto_date', gmdate('d-m-Y'));
			}

			// Registered users aren't affected by HPOS at all (this is usermeta, not
			// order data). WP_User_Query's own total (a separate COUNT(*) query) is
			// used instead of fetching every matching user ID just to count them.
			$registered_args = array(
				// Waiting = never actually synced (no hubwoo_user_vid, the real
				// HubSpot contact ID) AND not permanently invalid (no
				// hubwoo_invalid_contact) -- matches how total_synced_contacts
				// now counts "synced" in hubwoo_make_db_query(), so a contact
				// that failed validation is no longer double-counted as both
				// "synced" (it never was) and doesn't sit in "waiting" forever
				// either (it's permanently excluded, same as an invalid product).
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'hubwoo_user_vid',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'hubwoo_invalid_contact',
						'compare' => 'NOT EXISTS',
					),
				),
				'role__in'    => array_diff($roles, array('guest_user')),
				'number'      => 1,
				'fields'      => 'ID',
				'count_total' => true,
			);

			if ($date_range) {
				$registered_args['date_query'] = array(
					array(
						'after'     => gmdate('d-m-Y', strtotime($from_date)),
						'before'    => gmdate('d-m-Y', strtotime($upto_date . ' +1 day')),
						'inclusive' => true,
					),
				);
			}

			$registered_query = new WP_User_Query($registered_args);
			$unique_users     = (int) $registered_query->get_total();

			if (in_array('guest_user', $roles)) {

				$order_statuses = get_option('hubwoo-selected-order-status', array());
				if (empty($order_statuses) || ! is_array($order_statuses)) {
					$order_statuses = array_keys(self::hubwoo_get_valid_order_statuses());
				}

				$order_args = array(
					'post_status' => $order_statuses,
					'return'      => 'ids',
					'post_parent' => 0,
					'customer_id' => 0,
					'limit'       => -1,
				);

				if ($date_range) {
					$order_args['date_query'] = array(
						array(
							'after'     => gmdate('d-m-Y', strtotime($from_date)),
							'before'    => gmdate('d-m-Y', strtotime($upto_date . ' +1 day')),
							'inclusive' => true,
						),
					);
				}

				// wc_get_orders() resolves HPOS vs legacy storage and status
				// matching internally -- fetching actual IDs here (instead of the
				// previous paginate-for-a-cheap-count trick) is required now, since
				// turning an order count into a person count means seeing which
				// orders share the same billing email.
				$guest_order_ids = array_map('intval', wc_get_orders($order_args));

				if (! empty($guest_order_ids)) {
					$id_list = implode(',', $guest_order_ids);

					// Which of these are already handled -- genuinely synced
					// (hubwoo_user_vid, the real HubSpot contact ID) or
					// permanently invalid (hubwoo_invalid_contact)? Not
					// hubwoo_pro_guest_order: that flag gets set to 'synced' even
					// when HubSpot rejected the contact (see
					// total_synced_guest_contacts above), so using it here would
					// wrongly treat a permanently-failed order as "handled" only
					// by coincidence of that flag, rather than by the actual
					// outcome. Checks both storage tables directly, same as
					// hubwoo_make_db_query().
					$handled_ids = $wpdb->get_col(
						"SELECT DISTINCT order_id FROM (
							SELECT post_id AS order_id FROM {$wpdb->postmeta} WHERE post_id IN ({$id_list}) AND ((meta_key = 'hubwoo_user_vid' AND meta_value != '') OR meta_key = 'hubwoo_invalid_contact')
							UNION
							SELECT order_id FROM {$wpdb->prefix}wc_orders_meta WHERE order_id IN ({$id_list}) AND ((meta_key = 'hubwoo_user_vid' AND meta_value != '') OR meta_key = 'hubwoo_invalid_contact')
						) combined"
					);

					$unhandled_ids = array_diff($guest_order_ids, array_map('intval', $handled_ids));

					if (! empty($unhandled_ids)) {
						$unhandled_id_list = implode(',', $unhandled_ids);

						// Billing email lives in postmeta (_billing_email) for legacy
						// orders and directly on the wc_orders row for HPOS -- check
						// both, same dual-table reasoning as everywhere else here, so
						// this is correct regardless of which store is actually
						// current. Counted by unique email for the same reason as
						// total_synced_guest_contacts above: one guest with several
						// still-pending orders is still only one Contact to sync.
						$emails = $wpdb->get_col(
							"SELECT DISTINCT LOWER(email) FROM (
								SELECT meta_value AS email FROM {$wpdb->postmeta} WHERE meta_key = '_billing_email' AND post_id IN ({$unhandled_id_list})
								UNION
								SELECT billing_email AS email FROM {$wpdb->prefix}wc_orders WHERE id IN ({$unhandled_id_list})
							) combined WHERE email != ''"
						);

						$unique_users += count($emails);
					}
				}
			}

			return $unique_users;
		}

		/**
		 * Currently CRM name.
		 *
		 * @param string $get The slug for crm we want to integrate with.
		 */
		public static function get_current_crm_name($get = '')
		{
			$slug = 'hubwoo';
			if ('slug' === $get) {
				return esc_html(($slug));
			} else {
				return esc_html(ucwords($slug));
			}
		}

		/**
		 * Check if log is enable.
		 *
		 * @return boolean
		 */
		public static function is_log_enable()
		{
			$enable = get_option('hubwoo_' . self::get_current_crm_name('slug') . '_enable_log', 'yes');
			$enable = ('yes' === $enable);
			return $enable;
		}

		/**
		 * Check if table exists.
		 *
		 * @param  string $table_name Table name to be checked.
		 */
		public static function hubwoo_log_table_exists($table_name)
		{
			global $wpdb;

			if ($wpdb->get_var($wpdb->prepare('show tables like %s', $wpdb->esc_like($table_name))) === $table_name) {
				return 'exists';
			} else {
				return false;
			}
		}

		/**
		 * Create log table in database.
		 *
		 * @param string $slug crm slug.
		 */
		public static function hubwoo_create_log_table($slug = '')
		{

			global $wpdb;
			$crm_log_table = $wpdb->prefix . 'hubwoo_log';

			// If exists true.
			if ('exists' === self::hubwoo_log_table_exists($crm_log_table)) {
				return;
			}

			$crm_object = $slug . '_object';

			// Table/column names can't go through $wpdb->prepare()'s %s/%d
			// placeholders -- prepare() always wraps a substituted value in
			// SQL string quotes, which is invalid syntax where an identifier
			// is expected. Both $crm_log_table and $crm_object are safe to
			// interpolate directly here: neither is ever derived from request
			// input, only from the plugin's own hardcoded 'hubwoo' CRM slug
			// (see get_current_crm_name()).
			$wpdb->get_results(
				"CREATE TABLE IF NOT EXISTS `{$crm_log_table}` (
	            `id` int(11) NOT NULL AUTO_INCREMENT,
	            `{$crm_object}` varchar(255) NOT NULL,
	            `event` varchar(255) NOT NULL,
	            `request` text NOT NULL,
	            `response` text NOT NULL,
	            `time` int(11) NOT NULL,
	            PRIMARY KEY (`id`)
	          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
			);
		}

		/**
		 * Get CRM log data from database.
		 *
		 * @param  string|boolean $search_value Search value.
		 * @param  integer        $limit        Max limit of data.
		 * @param  integer        $offset       Offest to start.
		 * @param  boolean        $all          Return all results.
		 * @return array                        Array of data.
		 */
		public static function hubwoo_get_log_data($search_value = false, $limit = 25, $offset = 0, $all = false)
		{

			global $wpdb;
			$table_name = $wpdb->prefix . 'hubwoo_log';
			$log_data   = array();

			if ($all) {

				$log_data = $wpdb->get_results("SELECT * FROM `{$table_name}` ORDER BY `id` DESC", ARRAY_A); // @codingStandardsIgnoreLine.
				return $log_data;
			}

			if (! $search_value) {

				$log_data    = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$table_name}` ORDER BY `id` DESC LIMIT %d OFFSET %d", $limit, $offset), ARRAY_A); // @codingStandardsIgnoreLine.
				return $log_data;
			}

			$like_search = '%' . $wpdb->esc_like( $search_value ) . '%';
			
			// A single prepare() call, not one nested inside another -- the
			// WHERE clause's placeholders and the LIMIT/OFFSET placeholders
			// are all resolved together, so nothing here depends on how
			// $wpdb->prepare() happens to handle a literal '%' surviving
			// inside an already-substituted fragment.
			if ( is_numeric( $search_value ) && strlen( $search_value ) === 3 ) {
				$status_int = '%"status_code";i:' . intval( $search_value ) . ';%';
				$status_str = '%"status_code";s:%:"' . intval( $search_value ) . '";%';
				$status_json = '%"status_code":' . intval( $search_value ) . '%';
				$status_json_str = '%"status_code":"' . intval( $search_value ) . '"%';

				$log_data = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM `{$table_name}` WHERE (`hubwoo_object` LIKE %s OR `event` LIKE %s OR `request` LIKE %s OR `response` LIKE %s OR `response` LIKE %s OR `response` LIKE %s OR `response` LIKE %s) ORDER BY `id` DESC LIMIT %d OFFSET %d",
						$like_search, $like_search, $like_search, $status_int, $status_str, $status_json, $status_json_str, $limit, $offset
					), ARRAY_A
				); // @codingStandardsIgnoreLine.
			} else {
				$log_data = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM `{$table_name}` WHERE (`hubwoo_object` LIKE %s OR `event` LIKE %s OR `request` LIKE %s OR `response` LIKE %s) ORDER BY `id` DESC LIMIT %d OFFSET %d",
						$like_search, $like_search, $like_search, $like_search, $limit, $offset
					), ARRAY_A
				); // @codingStandardsIgnoreLine.
			}

			return $log_data;
		}

		/**
		 * Get total count from log table.
		 *
		 * @param  string|boolean $search_value Optional. Filter count by hubwoo_object value.
		 * @return array                        Array whose first element is the count.
		 */
		public static function hubwoo_get_total_log_count($search_value = false)
		{
			global $wpdb;
			$table_name = $wpdb->prefix . 'hubwoo_log';

			if ($search_value) {
				$like_search = '%' . $wpdb->esc_like( $search_value ) . '%';
				
				if ( is_numeric( $search_value ) && strlen( $search_value ) === 3 ) {
					$status_int = '%"status_code";i:' . intval( $search_value ) . ';%';
					$status_str = '%"status_code";s:%:"' . intval( $search_value ) . '";%';
					$status_json = '%"status_code":' . intval( $search_value ) . '%';
					$status_json_str = '%"status_code":"' . intval( $search_value ) . '"%';

					$where = $wpdb->prepare(
						"(`hubwoo_object` LIKE %s OR `event` LIKE %s OR `request` LIKE %s OR `response` LIKE %s OR `response` LIKE %s OR `response` LIKE %s OR `response` LIKE %s)",
						$like_search, $like_search, $like_search, $status_int, $status_str, $status_json, $status_json_str
					);
				} else {
					$where = $wpdb->prepare(
						"(`hubwoo_object` LIKE %s OR `event` LIKE %s OR `request` LIKE %s OR `response` LIKE %s)",
						$like_search, $like_search, $like_search, $like_search
					);
				}

				$count = $wpdb->get_results( "SELECT COUNT(*) as `total_count` FROM `{$table_name}` WHERE {$where}" ); // @codingStandardsIgnoreLine.
			} else {
				$count = $wpdb->get_results("SELECT COUNT(*) as `total_count` FROM `{$table_name}`"); // @codingStandardsIgnoreLine.
			}
			$count[0] = $count[0]->total_count;
			return $count;
		}

		/**
		 * Get deal group properties.
		 *
		 * @return array array of properties.
		 */
		public static function hubwoo_get_deal_properties()
		{
			$update_properties = array(
				array(
					'name'      => 'discount_amount',
					'label'     => __('Discount savings', 'makewebbetter-hubspot-for-woocommerce'),
					'type'      => 'number',
					'fieldType' => 'number',
					'formField' => false,
					'groupName' => 'dealinformation',
				),
				array(
					'name'      => 'order_number',
					'label'     => __('Order number', 'makewebbetter-hubspot-for-woocommerce'),
					'type'      => 'string',
					'fieldType' => 'textarea',
					'formField' => false,
					'groupName' => 'dealinformation',
				),
				array(
					'name'      => 'shipment_ids',
					'label'     => __('Shipment IDs', 'makewebbetter-hubspot-for-woocommerce'),
					'type'      => 'string',
					'fieldType' => 'textarea',
					'formField' => false,
					'groupName' => 'dealinformation',
				),
				array(
					'name'      => 'tax_amount',
					'label'     => __('Tax amount', 'makewebbetter-hubspot-for-woocommerce'),
					'type'      => 'number',
					'fieldType' => 'number',
					'formField' => false,
					'groupName' => 'dealinformation',
				),
			);

			return $update_properties;
		}

		/**
		 * Get product group properties.
		 *
		 * @return array array of properties.
		 */
		public static function hubwoo_get_product_properties()
		{
			return array(
				array(
					'name'      => 'store_product_id',
					'label'     => __('Store Product Id', 'makewebbetter-hubspot-for-woocommerce'),
					'type'      => 'number',
					'fieldType' => 'number',
					'formField' => false,
					'groupName' => 'productinformation',
				),
				array(
					'name'      => 'product_source_store',
					'label'     => __('Product Source Store', 'makewebbetter-hubspot-for-woocommerce'),
					'type'      => 'string',
					'fieldType' => 'textarea',
					'formField' => false,
					'groupName' => 'productinformation',
				),
			);
		}

		/**
		 * Associate deal with company.
		 *
		 * @param  string $contact Contact email address.
		 * @param  integer $deal_id Deal hubspot id.
		 * @param  integer $contact_id Contact hubspot id.
		 */
		public static function hubwoo_associate_deal_company($contact = '', $deal_id = '', $contact_id = '')
		{
			if ((! empty($contact) || ! empty($contact_id)) && ! empty($deal_id)) {

				if (!empty($contact) && empty($contact_id)) {
					$contact_response = HubWooConnectionMananager::get_instance()->get_contact_by_email($contact);
					if (200 == $contact_response['status_code']) {
						$contact_response['body'] = json_decode($contact_response['body'], true);
						if (! empty($contact_response['body']) && isset($contact_response['body']['id'])) {
							$contact_id = $contact_response['body']['id'];
						}
					}
				}

				if (! empty($contact_id)) {
					$additional_params = array(
						'associations' => array('companies'),
					);
					$associations = HubWooConnectionMananager::get_instance()->get_object_record_additional_information('contact', $contact_id, $additional_params);
					if (200 == $associations['status_code']) {
						$decoded_response = json_decode($associations['body'], true);
						$associated_companies = $decoded_response['associations']['companies']['results'] ?? [];
						$company_ids = [];
						if (!empty($associated_companies)) {
							foreach ($associated_companies as $associated_company) {
								$company_ids[] = $associated_company['id'];
							}
						}

						// Remove duplicates
						$company_ids = array_unique($company_ids);
						if (!empty($company_ids)) {
							foreach ($company_ids as $company_id) {
								HubWooConnectionMananager::get_instance()->associate_object('deal', $deal_id, 'company', $company_id, 5);
							}
						}
					}
				}
			}
		}

		public static function hubwoo_is_hpos_enabled()
		{
			return 'yes' == get_option('woocommerce_custom_orders_table_enabled', 'no');
		}

		public static function hubwoo_check_hpos_active()
		{
			return self::hubwoo_is_hpos_enabled() && true == get_option('hubwoo_hpos_license_check', 0);
		}

		/**
		 * True when HPOS is the store's primary order data store but the HPOS
		 * Compatibility add-on isn't licensed/activated -- the state in which no
		 * order-related HubSpot sync should run at all, deliberately, rather than
		 * falling through to a legacy post-meta query that either finds nothing
		 * (compatibility mode off) or leaks real results through the mirrored
		 * data (compatibility mode on). Every order-sync function checks this
		 * once, up front, instead of branching into a "legacy" query path that
		 * was never meant to run against an HPOS-primary store in the first place.
		 *
		 * @since 1.10.0
		 * @return bool
		 */
		public static function hubwoo_hpos_orders_blocked()
		{
			return self::hubwoo_is_hpos_enabled() && ! self::hubwoo_check_hpos_active();
		}

		/**
		 * All order statuses WooCommerce considers real orders -- i.e. every
		 * status wc_get_order_statuses() returns, minus 'wc-checkout-draft'.
		 *
		 * wc_get_order_statuses() itself always includes checkout-draft on any
		 * current WooCommerce install: WooCommerce Blocks unconditionally hooks
		 * the wc_order_statuses filter to add it (see
		 * Automattic\WooCommerce\Blocks\Domain\Services\DraftOrders::register_draft_order_status()),
		 * since it's the internal status used for an in-progress Store API
		 * checkout that hasn't been placed yet. That's not a real order, so
		 * nothing in this plugin should ever query, count, or map it as one --
		 * use this everywhere wc_get_order_statuses() would otherwise be
		 * called, rather than repeating an array_diff() at every call site.
		 *
		 * @since 1.10.0
		 * @return array status_key => label, same shape as wc_get_order_statuses().
		 */
		public static function hubwoo_get_valid_order_statuses()
		{
			$statuses = wc_get_order_statuses();
			unset($statuses['wc-checkout-draft']);
			return $statuses;
		}

		/**
		 * The single, shared definition of "which orders belong to this
		 * contact" -- used by both the registered-contact and guest-contact
		 * property computations, so the same real person's stats come out
		 * identical regardless of which sync path triggered them.
		 *
		 * An order belongs to this contact if EITHER:
		 *   - it has this WP user as its attached customer (they placed it,
		 *     regardless of whose email was billed -- the Customer field is
		 *     what defines ownership, not the billing email), or
		 *   - it has NO customer attached at all, and its billing email
		 *     matches this contact's email (their own guest activity, before
		 *     or without an account).
		 * An order billed to this email but placed by a DIFFERENT logged-in
		 * customer (e.g. someone ordering a gift) deliberately never matches
		 * either condition -- it belongs to that other customer, not here.
		 *
		 * @param int    $user_id WP user ID, 0 if this contact has no account.
		 * @param string $email   Contact's email, empty if not known.
		 * @return array Order IDs, most recent first.
		 */
		public static function hubwoo_resolve_contact_orders($user_id = 0, $email = '')
		{
			if (self::hubwoo_hpos_orders_blocked()) {
				return array();
			}

			$order_statuses = get_option('hubwoo-selected-order-status', array());
			if (empty($order_statuses)) {
				$order_statuses = array_keys(self::hubwoo_get_valid_order_statuses());
			}

			$order_ids = array();

			if (! empty($user_id)) {
				if (self::hubwoo_check_hpos_active()) {
					$query = new WC_Order_Query(array(
						'posts_per_page'      => -1,
						'post_status'         => $order_statuses,
						'orderby'             => 'date',
						'order'               => 'desc',
						'return'              => 'ids',
						'no_found_rows'       => true,
						'ignore_sticky_posts' => true,
						'customer_id'         => $user_id,
					));
					$order_ids = $query->get_orders();
				} else {
					$query     = new WP_Query();
					$order_ids = $query->query(array(
						'post_type'           => 'shop_order',
						'posts_per_page'      => -1,
						'post_status'         => $order_statuses,
						'orderby'             => 'date',
						'order'               => 'desc',
						'fields'              => 'ids',
						'no_found_rows'       => true,
						'ignore_sticky_posts' => true,
						'meta_query'          => array(
							array('key' => '_customer_user', 'value' => $user_id),
						),
					));
				}
			}

			if (! empty($email)) {
				if (self::hubwoo_check_hpos_active()) {
					$query = new WC_Order_Query(array(
						'posts_per_page'      => -1,
						'post_status'         => $order_statuses,
						'orderby'             => 'date',
						'order'               => 'desc',
						'return'              => 'ids',
						'no_found_rows'       => true,
						'ignore_sticky_posts' => true,
						'customer'            => $email,
					));
					$email_matched_ids = $query->get_orders();
				} else {
					$query = new WP_Query();
					$email_matched_ids = $query->query(array(
						'post_type'           => 'shop_order',
						'posts_per_page'      => -1,
						'post_status'         => $order_statuses,
						'orderby'             => 'date',
						'order'               => 'desc',
						'fields'              => 'ids',
						'no_found_rows'       => true,
						'ignore_sticky_posts' => true,
						'meta_query'          => array(
							array('key' => '_billing_email', 'value' => $email),
						),
					));
				}

				// The email match above catches ANY order with this billing
				// email, guest or registered -- filter down to genuinely
				// customer-less ones here, since WC_Order_Query's 'customer'
				// argument can't express "this email AND no customer" as a
				// single condition.
				foreach ((array) $email_matched_ids as $email_matched_id) {
					$matched_order = wc_get_order($email_matched_id);
					if ($matched_order instanceof WC_Order && 0 === (int) $matched_order->get_customer_id()) {
						$order_ids[] = $email_matched_id;
					}
				}
			}

			$order_ids = array_values(array_unique($order_ids));

			// Merging two independently-ordered result sets can interleave
			// them -- re-sort so callers can still trust $order_ids[0] as the
			// genuinely most recent order. get_date_created() is used rather
			// than a post-table lookup since it's correct for both HPOS and
			// legacy storage.
			usort($order_ids, function ($a, $b) {
				$order_a = wc_get_order($a);
				$order_b = wc_get_order($b);
				$time_a  = ($order_a instanceof WC_Order && $order_a->get_date_created()) ? $order_a->get_date_created()->getTimestamp() : 0;
				$time_b  = ($order_b instanceof WC_Order && $order_b->get_date_created()) ? $order_b->get_date_created()->getTimestamp() : 0;
				return $time_b <=> $time_a;
			});

			return $order_ids;
		}

		public static function hubwoo_hpos_get_meta_data($order, $meta_key, $bool)
		{
			if (!($order instanceof WC_Order)) {
				return;
			}
			if (Hubwoo::hubwoo_is_hpos_enabled()) {
				$meta_value = $order->get_meta(sanitize_key($meta_key), $bool);
			} else {
				$meta_value = get_post_meta($order->get_id(), sanitize_key($meta_key), $bool);
			}
			return $meta_value;
		}

		public static function hubwoo_hpos_update_meta_data($order, $meta_key, $meta_value)
		{
			if (!($order instanceof WC_Order)) {
				return;
			}
			if (Hubwoo::hubwoo_is_hpos_enabled()) {
				$order->update_meta_data(sanitize_key($meta_key), sanitize_text_field($meta_value));
				$order->save();
			} else {
				update_post_meta($order->get_id(), sanitize_key($meta_key), sanitize_text_field($meta_value));
			}
		}

		public static function hubwoo_hpos_delete_meta_data($order, $meta_key)
		{
			if (!($order instanceof WC_Order)) {
				return;
			}
			if (Hubwoo::hubwoo_is_hpos_enabled()) {
				$order->delete_meta_data(sanitize_key($meta_key));
				$order->save();
			} else {
				delete_post_meta($order->get_id(), sanitize_key($meta_key));
			}
		}
	}
}
