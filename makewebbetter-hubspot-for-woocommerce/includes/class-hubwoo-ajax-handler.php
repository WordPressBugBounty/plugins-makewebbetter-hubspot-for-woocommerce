<?php
/**
 * Handles all admin ajax requests.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
 */

if ( ! class_exists( 'HubWooAjaxHandler' ) ) {

	/**
	 * Handles all admin ajax requests.
	 *
	 * All the functions required for handling admin ajax requests
	 * required by the plugin.
	 *
	 * @package    makewebbetter-hubspot-for-woocommerce
	 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
	 */
	class Hubwoo_Ajax_Handler {

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// check oauth access token.
			add_action( 'wp_ajax_hubwoo_check_oauth_access_token', array( &$this, 'hubwoo_check_oauth_access_token' ) );
			// create group for properties.
			add_action( 'wp_ajax_hubwoo_create_property_group', array( &$this, 'hubwoo_create_property_group' ) );
			// get group properties.
			add_action( 'wp_ajax_hubwoo_get_group_properties', array( &$this, 'hubwoo_get_group_properties' ) );
			// create property.
			add_action( 'wp_ajax_hubwoo_create_group_property', array( &$this, 'hubwoo_create_group_property' ) );
			// create deal properties.
			add_action( 'wp_ajax_hubwoo_deals_create_property', array( &$this, 'hubwoo_deals_create_property' ) );
			// get final lists to be created.
			add_action( 'wp_ajax_hubwoo_get_lists', array( &$this, 'hubwoo_get_lists_to_create' ) );
			// create bulk lists.
			add_action( 'wp_ajax_hubwoo_create_list', array( &$this, 'hubwoo_create_list' ) );
			// create single single group on admin call..
			add_action( 'wp_ajax_hubwoo_create_single_group', array( &$this, 'hubwoo_create_single_group' ) );
			// create single property on admin call.
			add_action( 'wp_ajax_hubwoo_create_single_property', array( &$this, 'hubwoo_create_single_property' ) );
			// create single list on admin call.
			add_action( 'wp_ajax_hubwoo_create_single_list', array( &$this, 'hubwoo_create_single_list' ) );
			// create workflow.
			add_action( 'wp_ajax_hubwoo_create_single_workflow', array( &$this, 'hubwoo_create_single_workflow' ) );
			// updating workflow which are dependent.
			add_action( 'wp_ajax_hubwoo_update_workflow_tab', array( &$this, 'hubwoo_update_workflow_tab' ) );
			// search for order statuses.
			add_action( 'wp_ajax_hubwoo_search_for_order_status', array( &$this, 'hubwoo_search_for_order_status' ) );
			// get user roles for batch sync.
			add_action( 'wp_ajax_hubwoo_get_for_user_roles', array( &$this, 'hubwoo_get_for_user_roles' ) );
			// emailing the errors to makewebbetter support.
			add_action( 'wp_ajax_hubwoo_email_the_error_log', array( &$this, 'hubwoo_email_the_error_log' ) );
			// disconnect the current account and delete all of the meta.
			add_action( 'wp_ajax_hubwoo_disconnect_account', array( &$this, 'hubwoo_disconnect_account' ) );
			// get all of the users for the current selected user roles.
			add_action( 'wp_ajax_hubwoo_get_user_for_current_roles', array( &$this, 'hubwoo_get_user_for_current_roles' ) );
			// get sync status of any background sync process.
			add_action( 'wp_ajax_hubwoo_get_current_sync_status', array( &$this, 'hubwoo_get_current_sync_status' ) );
			// save objects in database option table.
			add_action( 'wp_ajax_hubwoo_save_updates', array( &$this, 'hubwoo_save_updates' ) );
			// get the all of the deal stages for select2.
			add_action( 'wp_ajax_hubwoo_deals_search_for_stages', array( &$this, 'hubwoo_deals_search_for_stages' ) );
			// run processes for the ecommerce pipeline setup.
			add_action( 'wp_ajax_hubwoo_ecomm_setup', array( &$this, 'hubwoo_ecomm_setup' ) );
			// get the current ocs count for deals.
			add_action( 'wp_ajax_hubwoo_ecomm_get_ocs_count', array( &$this, 'hubwoo_ecomm_get_ocs_count' ) );
			// manage sync processes.
			add_action( 'wp_ajax_hubwoo_manage_sync', array( &$this, 'hubwoo_manage_sync' ) );
			// manage historical objects vids.
			add_action( 'wp_ajax_hubwoo_manage_vids', array( &$this, 'hubwoo_manage_vids' ) );
			// track sync statuses of background executable tasks.
			add_action( 'wp_ajax_hubwoo_sync_status_tracker', array( &$this, 'hubwoo_sync_status_tracker' ) );
			// submit the onboarding question form .
			add_action( 'wp_ajax_hubwoo_onboard_form', array( &$this, 'hubwoo_onboard_form' ) );
			// get the onboarding data questionaire.
			add_action( 'wp_ajax_hubwoo_get_onboard_form', array( &$this, 'hubwoo_get_onboard_form' ) );
			// Hide review notice.
			add_action( 'wp_ajax_hubwoo_hide_rev_notice', array( $this, 'hubwoo_hide_rev_notice' ) );
			// Hide hpos notice.
			add_action( 'wp_ajax_hubwoo_hide_hpos_notice', array( $this, 'hubwoo_hide_hpos_notice' ) );
			// Dismiss the "Sync Your Store Users" dashboard prompt.
			add_action( 'wp_ajax_hubwoo_dismiss_sync_users_prompt', array( $this, 'hubwoo_dismiss_sync_users_prompt' ) );
			// Hide festive notice.
			add_action( 'wp_ajax_hubwoo_hide_festive_notice', array( $this, 'hubwoo_hide_festive_notice' ) );
			// Get database data.
			add_action( 'wp_ajax_hubwoo_get_datatable_data', array( $this, 'hubwoo_get_datatable_data' ) );
			// Download database log.
			add_action( 'wp_ajax_hubwoo_download_sync_log', array( $this, 'hubwoo_download_sync_log' ) );
			// Clear database log.
			add_action( 'wp_ajax_hubwoo_clear_sync_log', array( $this, 'hubwoo_clear_sync_log' ) );
			// Fetch pipeline deal stages.
			add_action( 'wp_ajax_hubwoo_fetch_deal_stages', array( $this, 'hubwoo_fetch_deal_stages' ) );
			// Fetch deal pipelines.
			add_action( 'wp_ajax_hubwoo_fetch_update_pipelines', array( $this, 'hubwoo_fetch_update_pipelines' ) );
		}

		/**
		 * Checking access token validity.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_check_oauth_access_token() {

			$response = array(
				'status'  => true,
				'message' => esc_html__( 'Success', 'makewebbetter-hubspot-for-woocommerce' ),
			);

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			if ( Hubwoo::is_access_token_expired() ) {

				$hapikey = HUBWOO_CLIENT_ID;
				$hseckey = HUBWOO_SECRET_ID;
				$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token( $hapikey, $hseckey );

				if ( ! $status ) {

					$response['status']  = false;
					$response['message'] = esc_html__( 'Something went wrong. Please verify your HubSpot Connection once.', 'makewebbetter-hubspot-for-woocommerce' );
				}
			}

			echo wp_json_encode( $response );

			wp_die();
		}

		/**
		 * Create new group for contact properties.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_create_property_group() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}
			if ( ! empty( $_POST['groupName'] ) ) {
				$group_name = sanitize_key( wp_unslash( $_POST['groupName'] ) );
			}
			$object_type   = 'contacts';
			$groups        = HubWooContactProperties::get_instance()->_get( 'groups' );
			$group_details = array();
			if ( ! empty( $groups ) ) {
				foreach ( $groups as $single_group ) {
					if ( $single_group['name'] == $group_name ) {
						$group_details = $single_group;
						break;
					}
				}
			}
			$response = HubWooConnectionMananager::get_instance()->create_group( $group_details, $object_type );
			echo wp_json_encode( $response );
			wp_die();
		}

		/**
		 * Get hubwoo group properties by group name.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_get_group_properties() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			if ( isset( $_POST['groupName'] ) ) {

				$group_name = sanitize_text_field( wp_unslash( $_POST['groupName'] ) );
				$properties = HubWooContactProperties::get_instance()->_get( 'properties', $group_name );
				echo wp_json_encode( $properties );
			}

			wp_die();
		}

		/**
		 * Create an group property on ajax request.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_create_group_property() {

			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			if ( isset( $_POST['propertyDetails'] ) ) {
				$property_details = map_deep( wp_unslash( $_POST['propertyDetails'] ), 'sanitize_text_field' );
				$response         = HubWooConnectionMananager::get_instance()->create_batch_properties( $property_details, 'contact' );
				$response['body'] = json_decode( $response['body'], true );
				echo wp_json_encode( $response );
			}
			wp_die();
		}

		/**
		 * Create deal group property on ajax request.
		 *
		 * @since 1.4.0
		 */
		public function hubwoo_deals_create_property() {
			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$product_properties  = Hubwoo::hubwoo_get_product_properties();
			$object_type         = 'products';
			$response            = HubWooConnectionMananager::get_instance()->create_batch_properties( $product_properties, $object_type );
			if ( 201 == $response['status_code'] || 207 == $response['status_code'] ) {
				update_option( 'hubwoo_product_property_created', 'yes', false );
				$response['body']    = json_decode( $response['body'], true );
			} else if ( 403 == $response['status_code'] ) {
				update_option( 'hubwoo_product_scope_needed', 'yes', false );
			}

			$deal_properties  = Hubwoo::hubwoo_get_deal_properties();
			$object_type      = 'deals';
			$response         = HubWooConnectionMananager::get_instance()->create_batch_properties( $deal_properties, $object_type );
			if ( 201 == $response['status_code'] || 207 == $response['status_code'] ) {
				update_option( 'hubwoo_deal_property_created', 'yes', false );
				$response['body']    = json_decode( $response['body'], true );
			}

			echo wp_json_encode( $response );
			wp_die();
		}


		/**
		 * Get lists to be created on husbpot.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_get_lists_to_create() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$lists = HubWooContactProperties::get_instance()->_get( 'lists' );

			echo wp_json_encode( $lists );

			wp_die();
		}

		/**
		 * Create bulk lists on hubspot.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_create_list() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			if ( isset( $_POST['listDetails'] ) ) {

				$list_details = map_deep( wp_unslash( $_POST['listDetails'] ), 'sanitize_text_field' );
				$response     = HubWooConnectionMananager::get_instance()->create_list( $list_details );
				echo wp_json_encode( $response );
			}

			wp_die();
		}


		/**
		 * Create single group on HubSpot.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_create_single_group() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}
			if ( ! empty( $_POST['name'] ) ) {
				$group_name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			} else {
				$group_name = '';
			}
			$groups        = HubWooContactProperties::get_instance()->_get( 'groups' );
			$group_details = '';
			$object_type   = 'contacts';

			if ( is_array( $groups ) && count( $groups ) ) {

				foreach ( $groups as $single_group ) {

					if ( $single_group['name'] === $group_name ) {

						$group_details = $single_group;
						break;
					}
				}
			}

			if ( ! empty( $group_details ) ) {

				$response = HubWooConnectionMananager::get_instance()->create_group( $group_details, $object_type );
			}

			if ( isset( $response['status_code'] ) && ( 201 === $response['status_code'] || ! empty( $response['already_exists'] ) ) ) {

				$add_groups   = get_option( 'hubwoo-groups-created', array() );
				$add_groups[] = $group_details['name'];
				update_option( 'hubwoo-groups-created', $add_groups, false );
			}

			echo wp_json_encode( $response );
			wp_die();
		}

		/**
		 * Create single property on HubSpot.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_create_single_property() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			if ( ! empty( $_POST['group'] ) ) {
				$group_name = sanitize_text_field( wp_unslash( $_POST['group'] ) );
			} else {
				$group_name = '';
			}

			if ( ! empty( $_POST['name'] ) ) {
				$property_name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			} else {
				$property_name = '';
			}

			$properties = HubWooContactProperties::get_instance()->_get( 'properties', $group_name );

			if ( ! empty( $properties ) && count( $properties ) ) {

				foreach ( $properties as $single_property ) {

					if ( ! empty( $single_property['name'] ) && $single_property['name'] == $property_name ) {

						$property_details = $single_property;
						break;
					}
				}
			}

			if ( ! empty( $property_details ) ) {

				$property_details['groupName'] = $group_name;

				$response = HubWooConnectionMananager::get_instance()->create_property( $property_details, 'contacts' );
			}

			if ( isset( $response['status_code'] ) && ( 201 === $response['status_code'] || 409 === $response['status_code'] ) ) {

				$add_properties   = get_option( 'hubwoo-properties-created', array() );
				$add_properties[] = $property_details['name'];
				update_option( 'hubwoo-properties-created', $add_properties, false );
			}

			echo wp_json_encode( $response );

			wp_die();
		}

		/**
		 * Create single list on hubspot.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_create_single_list() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			if ( isset( $_POST['name'] ) ) {

				$list_name = sanitize_text_field( wp_unslash( $_POST['name'] ) );

				$lists = HubWooContactProperties::get_instance()->_get( 'lists' );

				if ( ! empty( $lists ) && count( $lists ) ) {

					foreach ( $lists as $single_list ) {

						if ( ! empty( $single_list['name'] ) && $single_list['name'] == $list_name ) {

							$list_details = $single_list;
							break;
						}
					}
				}

				if ( ! empty( $list_details ) ) {

					$response = HubWooConnectionMananager::get_instance()->create_list( $list_details );
				}

				if ( isset( $response['status_code'] ) ) {
					$set_list = false;
					if ($response['status_code'] == 200){
						$set_list = true;
					}elseif ($response['status_code'] == 400){
						$decoded_res = json_decode($response['body'], true);
						if(isset( $decoded_res['subCategory'] ) && $decoded_res['subCategory'] == 'ILS.DUPLICATE_LIST_NAMES'){
							$set_list = true;
						}
					}
					if($set_list){
						$add_lists   = get_option( 'hubwoo-lists-created', array() );
						$add_lists[] = $list_name;
						update_option( 'hubwoo-lists-created', $add_lists, false );
					}
				}

				echo wp_json_encode( $response );
				wp_die();
			}
		}

		/**
		 * Create single list on hubspot.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_create_single_workflow() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				echo wp_json_encode( array( 'errors' => true, 'message' => __( 'You are not allowed to perform this action.', 'makewebbetter-hubspot-for-woocommerce' ) ) );
				wp_die();
			}

			if ( ! empty( $_POST['name'] ) ) {

				$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );

				$add_workflows = get_option( 'hubwoo-workflows-created', array() );

				if ( in_array( $name, $add_workflows ) ) {
					echo wp_json_encode( array( 'errors' => true, 'message' => __( 'This workflow is already created.', 'makewebbetter-hubspot-for-woocommerce' ) ) );
					wp_die();
				}

				$workflows = HubWooContactProperties::get_instance()->_get( 'workflows' );

				if ( ! empty( $workflows ) ) {

					foreach ( $workflows as $single_workflow ) {

						if ( isset( $single_workflow['name'] ) && $single_workflow['name'] == $name ) {

							$workflow_details = $single_workflow;
							break;
						}
					}
				}

				if ( ! empty( $workflow_details ) ) {

					$response = HubWooConnectionMananager::get_instance()->create_workflow( $workflow_details );

					if ( isset( $response['status_code'] ) && ( 200 != $response['status_code'] ) ) {

						$handled_response = HubwooErrorHandling::get_instance()->hubwoo_handle_response( $response, HubwooConst::HUBWOOWORKFLOW, array( 'current_workflow' => $workflow_details ) );

						// hubwoo_handle_response() only has explicit handling for some
						// error codes (e.g. HubSpot 500 + a missing contact property);
						// for anything else it returns null. Keep the original HubSpot
						// response in that case instead of overwriting it with null,
						// so the AJAX reply always stays a well-formed, informative
						// response rather than the literal JSON value `null`.
						if ( is_array( $handled_response ) && isset( $handled_response['status_code'] ) ) {
							$response = $handled_response;
						}
					}

					if ( 200 == $response['status_code'] ) {

						$add_workflows[] = $workflow_details['name'];
						update_option( 'hubwoo-workflows-created', $add_workflows, false );

						$workflow_data = isset( $response['body'] ) ? $response['body'] : '';

						if ( ! empty( $workflow_data ) ) {

							$workflow_data = json_decode(
								$workflow_data
							);
							$id            = isset( $workflow_data->id ) ? $workflow_data->id : '';
							update_option( $workflow_details['name'], $id, false );
						}
					}

					echo wp_json_encode( $response );
					wp_die();
				}
				echo wp_json_encode( array( 'errors' => true, 'message' => __( 'Workflow definition not found.', 'makewebbetter-hubspot-for-woocommerce' ) ) );
				wp_die();
			}
		}

		/**
		 * Ajax search for order statuses.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_search_for_order_status() {

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$order_statuses = Hubwoo::hubwoo_get_valid_order_statuses();

			$modified_order_statuses = array();

			if ( ! empty( $order_statuses ) ) {

				foreach ( $order_statuses as $status_key => $single_status ) {

					$modified_order_statuses[] = array( $status_key, $single_status );
				}
			}

			echo wp_json_encode( $modified_order_statuses );

			wp_die();
		}

		/**
		 * User roles for batch sync.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_get_for_user_roles() {

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			global $hubwoo;

			$user_roles = $hubwoo->hubwoo_get_user_roles();

			$modified_order_statuses = array();

			if ( ! empty( $user_roles ) ) {

				foreach ( $user_roles as $user_key => $single_role ) {

					$modified_order_statuses[] = array( $user_key, $single_role );
				}
			}

			echo wp_json_encode( $modified_order_statuses );

			wp_die();
		}


		/**
		 * Update workflow listing window when a workflow is created.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_update_workflow_tab() {

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			global $hubwoo;

			$created_workflows = get_option( 'hubwoo-workflows-created', '' );

			$workflows_dependencies = $hubwoo->hubwoo_workflows_dependency();

			$updated_tabs = array();

			$dependencies_count = 0;

			if ( is_array( $workflows_dependencies ) && count( $workflows_dependencies ) ) {
				foreach ( $workflows_dependencies as $workflows ) {
					$dependencies_count = count( $workflows['dependencies'] );
					$counter            = 0;
					foreach ( $workflows['dependencies'] as $dependencies ) {
						if ( is_array( $created_workflows ) && count( $created_workflows ) ) {
							if ( in_array( $dependencies, $created_workflows, true ) ) {
								$counter++;
							}
						}
					}
					if ( $counter === $dependencies_count ) {
						$updated_tabs[] = $workflows['workflow'];
					}
				}
			}
			echo wp_json_encode( $updated_tabs );
			wp_die();
		}

		/**
		 * Email the hubspot API error log.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_email_the_error_log() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}
			$log_dir     = WC_LOG_DIR . 'hubspot-for-woocommerce-logs.log';
			$attachments = array( $log_dir );
			$to          = 'integrations@makewebbetter.com';
			$subject     = 'HubSpot Pro Error Logs';
			$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
			$message     = 'admin email: ' . get_option( 'admin_email', '' ) . '<br/>';
			$status      = wp_mail( $to, $subject, $message, $headers, $attachments );

			if ( 1 === $status ) {
				$status = 'success';
			} else {
				$status = 'failure';
			}
			echo wp_json_encode( $status );
			wp_die();
		}


		/**
		 * Disconnect hubspot account.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_disconnect_account() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die();
			}

			global $hubwoo;

			$delete_meta = false;

			if ( isset( $_POST['data'] ) ) {
				$data        = map_deep( wp_unslash( $_POST['data'] ), 'sanitize_text_field' );
				$delete_meta = 'yes' == $data['delete_meta'] ? true : false;
			}

			HubWooConnectionMananager::get_instance()->remove_installed_app();
			
			$hubwoo->hubwoo_switch_account( true, $delete_meta );
			echo wp_json_encode( true );
			wp_die();
		}

		/**
		 * Get wordpress/woocommerce user roles.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_get_user_for_current_roles() {
			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$hubwoo_data_sync = new HubwooDataSync();
			$unique_users     = $hubwoo_data_sync->hubwoo_get_all_unique_user( true );
			echo wp_json_encode( $unique_users );
			wp_die();
		}

		/**
		 * Get sync status for contact/deal.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_get_current_sync_status() {
			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}
			if ( ! empty( $_POST['data'] ) ) {
				$type = map_deep( wp_unslash( $_POST['data'] ), 'sanitize_text_field' );
			} else {
				$type = '';
			}
			if ( isset( $type['type'] ) ) {
				switch ( $type['type'] ) {
					case 'contact':
						$status = get_option( 'hubwoo_background_process_running', false );
						break;
					case 'deal':
						$status = get_option( 'hubwoo_deals_sync_running', 0 );
						break;
					default:
						$status = false;
						break;
				}
				echo wp_json_encode( $status );
			}
			wp_die();
		}

		/**
		 * Saving Updates to the Database.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_save_updates() {

			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( isset( $_POST['updates'] ) && ! empty( $_POST['action'] ) && current_user_can('manage_options') ) {
				
				$updates = map_deep( wp_unslash( $_POST['updates'] ), 'sanitize_text_field' );

				if ( isset( $_POST['type'] ) ) {
					$action = map_deep( wp_unslash( $_POST['type'] ), 'sanitize_text_field' );
					$status = false;
					if ( count( $updates ) ) {

						// Options this settings-save handler writes that genuinely need to be
						// read on every request (they gate hook registration in class-hubwoo.php);
						// everything else saved through this dynamic path defaults to
						// autoload = false, since a settings-tab toggle is by definition only
						// ever read from a specific admin screen, never from the hot path.
						$hubwoo_autoload_yes_keys = array(
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

						foreach ( $updates as $db_key => $value ) {

							if ( 'update' === $action ) {
								// Only allow plugin-owned option keys (all start with 'hubwoo').
								if ( 0 !== strpos( $db_key, 'hubwoo' ) ) {
									continue;
								}
								$value = 'EMPTY_ARRAY' === $value ? array() : $value;
								update_option( $db_key, $value, in_array( $db_key, $hubwoo_autoload_yes_keys, true ) );
							} elseif ( 'delete' === $action ) {
								if ( 0 !== strpos( $value, 'hubwoo' ) ) {
									continue;
								}
								delete_option( $value );
							}
						}

						$status = true;
					}
					echo wp_json_encode( $status );
					wp_die();
				}
			}
		}

		/**
		 * Saving Updates to the Database.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_manage_sync() {

			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			if ( ! empty( $_POST['process'] ) ) {
				$process = map_deep( wp_unslash( $_POST['process'] ), 'sanitize_text_field' );

				if ( ! empty( $process ) ) {

					if ( 'start-deal' === $process ) {
						$orders_needs_syncing = Hubwoo_Admin::hubwoo_orders_count_for_deal();
						if ( $orders_needs_syncing && ! as_next_scheduled_action( 'hubwoo_deals_sync_background' ) ) {

							update_option( 'hubwoo_deals_sync_running', 1, false );
							as_schedule_recurring_action( time(), 300, 'hubwoo_deals_sync_background' );
						}
					} else {
						Hubwoo::hubwoo_stop_sync( $process );
					}
					echo wp_json_encode( true );
				}
			}

			wp_die();
		}

		/**
		 * Manages Vids to Database.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_manage_vids() {

			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$status = false;

			if ( ! empty( $_POST['process'] ) ) {
				$process = map_deep( wp_unslash( $_POST['process'] ), 'sanitize_text_field' );
				if ( ! empty( $process ) ) {
					switch ( $process ) {
						case 'contact':
							update_option( 'hubwoo_contact_vid_update', 1, false );
							as_schedule_recurring_action( time(), 300, 'hubwoo_update_contacts_vid' );
							$status = true;
							break;
						case 'deal':
							delete_option( 'hubwoo_ecomm_order_date_allow' );
							$orders_needs_syncing = Hubwoo_Admin::hubwoo_orders_count_for_deal();
							if ( $orders_needs_syncing ) {
								update_option( 'hubwoo_deals_sync_running', 1, false );
								as_schedule_recurring_action( time(), 300, 'hubwoo_deals_sync_background' );
								$status = true;
							}
							break;
						default:
							break;
					}
				}
			}
			echo wp_json_encode( $status );
			wp_die();
		}

		/**
		 * Ajax call to search for deal stages.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_deals_search_for_stages() {

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$stages = get_option( 'hubwoo_fetched_deal_stages', array() );

			$existing_stages = array();

			$deal_stage_id = 'stageId';

			if ( 'yes' == get_option( 'hubwoo_ecomm_pipeline_created', 'no' ) ) {
				$deal_stage_id = 'id';
			}

			if ( is_array( $stages ) && count( $stages ) ) {

				foreach ( $stages as $stage ) {

					$existing_stages[] = array( $stage[ $deal_stage_id ], $stage['label'] );
				}
			}

			echo wp_json_encode( $existing_stages );
			wp_die();
		}

		/**
		 * Get orders count for 1 click sync.
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_ecomm_get_ocs_count() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$ocs_order_count = Hubwoo_Admin::hubwoo_orders_count_for_deal();
			if ( 1 != get_option( 'hubwoo_deals_sync_running', 0 ) ) {
				update_option( 'hubwoo_deals_current_sync_total', $ocs_order_count, false );
			}
			echo wp_json_encode( $ocs_order_count );
			wp_die();
		}

		/**
		 * Track sync percentage and eta for background processes
		 *
		 * @since 1.0.0
		 */
		public function hubwoo_sync_status_tracker() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}
			$response = array(
				'percentage' => 0,
				'is_running' => 'no',
			);

			if ( isset( $_POST['process'] ) ) {
				$process = map_deep( wp_unslash( $_POST['process'] ), 'sanitize_text_field' );
				if ( ! empty( $process ) ) {
					switch ( $process ) {
							case 'contact':
							if ( get_option( 'hubwoo_background_process_running', false ) ) {

								// Snapshot the denominator once per sync run instead of
								// recomputing it on every poll. Recomputing it live would
								// shrink it in lockstep with the synced counter (a contact
								// leaves the "not yet synced" pool the moment it's synced),
								// which made the percentage hit 100% at the halfway point.
								$users_to_sync = get_option( 'hubwoo_total_ocs_contact_need_sync', 0 );
								if ( ! $users_to_sync ) {
									$users_to_sync = Hubwoo::hubwoo_get_total_contact_need_sync();
									update_option( 'hubwoo_total_ocs_contact_need_sync', $users_to_sync, false );
								}

								$current_user_sync      = get_option( 'hubwoo_ocs_contacts_synced', 0 );
								$perc                   = $users_to_sync > 0 ? round( $current_user_sync * 100 / $users_to_sync ) : 0;
								$response['percentage'] = $perc > 100 ? 100 : $perc;
								$response['is_running'] = 'yes';

								// Deliberately does NOT clear hubwoo_background_process_running here.
								// $users_to_sync is a snapshot taken at the start of this run; if a
								// user becomes newly eligible mid-run, this percentage can reach 100
								// while hubwoo_contacts_sync_background()'s live query still finds
								// real work left. Only that background job's own completion check
								// (Hubwoo::hubwoo_stop_sync('stop-contact')) is allowed to declare the
								// sync actually finished -- clearing the flag here on a false positive
								// would hide the in-progress UI, and could incorrectly resurface the
								// "Sync Your Store Users" dashboard prompt, while the sync is still
								// quietly running in the background.
							}

							break;
							case 'order':
							if ( 1 == get_option( 'hubwoo_deals_sync_running', 0 ) ) {
								$data                   = Hubwoo::get_sync_status();
								$response['percentage'] = $data['deals_progress'];
								$response['eta']        = $data['eta_deals_sync'];
								$response['is_running'] = 'yes';
							}
							break;
					}
					echo wp_json_encode( $response );
				}
			}
			wp_die();
		}
		/**
		 * Upserting ecomm bridge settings for hubspot objects-contact,deal,product,line-item
		 *
		 * @since    1.0.0
		 */
		public function hubwoo_ecomm_setup() {

			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$response = array(
				'status_code' => 404,
				'response'    => 'E-Commerce Bridge Setup Failed.',
			);

			if ( ! empty( $_POST['process'] ) ) {
				$process = map_deep( wp_unslash( $_POST['process'] ), 'sanitize_text_field' );

				if ( ! empty( $process ) ) {
					switch ( $process ) {
						case 'get-total-products':
							$store = Hubwoo::get_store_data();
							break;
						case 'update-deal-stages':
							$deal_stages = Hubwoo::fetch_deal_stages_from_pipeline( 'Ecommerce Pipeline', false );

							if ( empty( $deal_stages ) || empty( $deal_stages['stages'] ) || ! isset( $deal_stages['id'] ) ) {
								// fetch_deal_stages_from_pipeline() already falls back to an
								// existing pipeline when ours can't be created, so this only
								// happens if the portal has no usable pipeline at all (e.g. the
								// pipeline-list API call itself failed). Bail out cleanly rather
								// than indexing into a shape that isn't there -- that's what was
								// crashing the request and freezing the onboarding progress bar.
								break;
							}

							$deal_model            = Hubwoo::hubwoo_deal_stage_model();
							$process_deal_stages   = array_map(
								function ( $deal_stage_data ) use ( $deal_model ) {
									$updates = ( isset( $deal_model[ $deal_stage_data['id'] ] ) && ! empty( $deal_model[ $deal_stage_data['id'] ] ) ) ? $deal_model[ $deal_stage_data['id'] ] : '';
									if ( ! empty( $updates ) ) {
										foreach ( $updates as $key => $value ) {
											if ( array_key_exists( $key, $deal_stage_data ) ) {
												$deal_stage_data[ $key ] = $value;
											}
										}
									}
									return $deal_stage_data;
								},
								$deal_stages['stages']
							);
							$deal_stages['stages'] = $process_deal_stages;
							$pipeline_id = $deal_stages['id'];
							unset( $deal_stages['id'] );
							$response              = HubWooConnectionMananager::get_instance()->update_deal_pipeline( $deal_stages, $pipeline_id );
							update_option( 'hubwoo_ecomm_final_mapping', Hubwoo::hubwoo_deals_mapping(), false );
							update_option( 'hubwoo_fetched_deal_stages', $deal_stages['stages'], false );
							break;
						case 'reset-mapping':
							if ( ! empty( $_POST['pipeline'] ) ) {
								$selected_pipeline = map_deep( wp_unslash( $_POST['pipeline'] ), 'sanitize_text_field' );
								if ( 'Ecommerce Pipeline' == $selected_pipeline ) {
									update_option( 'hubwoo_ecomm_final_mapping', Hubwoo::hubwoo_deals_mapping(), false );
								} else if ( 'Sales Pipeline' == $selected_pipeline ) {
									update_option( 'hubwoo_ecomm_final_mapping', Hubwoo::hubwoo_sales_deals_mapping(), false );
								}
							} else {
								update_option( 'hubwoo_ecomm_final_mapping', '', false );
							}
							break;
					}
					echo wp_json_encode( $response );
				}
			}
			wp_die();
		}

		/**
		 * Get the onboarding submission data.
		 *
		 * @since    1.0.4
		 */
		public function hubwoo_get_onboard_form() {

			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}
			if ( ! empty( $_POST['key'] ) ) {
				$key     = map_deep( wp_unslash( $_POST['key'] ), 'sanitize_text_field' );
				$key     = str_replace( '[]', '', $key );
				$options = array_map(
					function( $option ) {
						return array( $option, $option );
					},
					Hubwoo::hubwoo_onboarding_questionaire()[ $key ]['options']
				);
				echo wp_json_encode( $options );
			}
			wp_die();
		}

		/**
		 * Handle the onboarding form submision.
		 *
		 * @since    1.0.4
		 */
		public function hubwoo_onboard_form() {
			// check the nonce sercurity.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			if ( ! empty( $_POST['formData'] ) ) {
				$form_data = map_deep( wp_unslash( $_POST['formData'] ), 'sanitize_text_field' );
				if ( ! empty( $form_data ) ) {
					$form_details = array();
					array_walk(
						$form_data,
						function( $field, $name ) use ( &$form_details ) {
							if ( is_array( $field ) ) {
								$field = HubwooGuestOrdersManager::hubwoo_format_array( $field );
							}
							$form_details['fields'][] = array(
								'name'  => $name,
								'value' => $field,
							);
						}
					);
					echo wp_json_encode( HubWooConnectionMananager::get_instance()->submit_form_data( $form_details, '5373140', '0354594f-26ce-414d-adab-4e89f2104902' ) );
				}
			}
			wp_die();
		}

		/**
		 * Hide review notice.
		 */
		public function hubwoo_hide_rev_notice() {

			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			update_option( 'hubwoo_hide_rev_notice', 'yes', false );

			echo wp_json_encode(
				array(
					'status'   => true,
					'response' => 'Notice hide succesfully',
				)
			);
			wp_die();
		}

		/**
		 * Hide HPOS notice
		 */
		public function hubwoo_hide_hpos_notice(){
			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			update_option( 'hubwoo_hide_hpos_notice', 'yes', false );

			echo wp_json_encode(
				array(
					'status'   => true,
					'response' => 'Notice hide succesfully',
				)
			);
			wp_die();
		}

		/**
		 * Permanently dismiss the "Sync Your Store Users" dashboard prompt.
		 *
		 * @since 1.6.9
		 */
		public function hubwoo_dismiss_sync_users_prompt(){
			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			update_option( 'hubwoo_sync_users_prompt_dismissed', 'yes', false );

			echo wp_json_encode(
				array(
					'status'   => true,
					'response' => 'Prompt dismissed successfully',
				)
			);
			wp_die();
		}

		/**
		 * Hide Festive notice
		 */
		public function hubwoo_hide_festive_notice(){
			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			update_option( 'hubwoo_hide_festive_notice', 'yes', false );

			echo wp_json_encode(
				array(
					'status'   => true,
					'response' => 'Notice hide succesfully',
				)
			);
			wp_die();
		}

		/**
		 * Fetch logs from database.
		 */
		public function hubwoo_get_datatable_data() {
			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$offset       = isset( $_GET['start'] ) ? absint( $_GET['start'] ) : 0; // phpcs:ignore
			$limit        = isset( $_GET['length'] ) ? intval( $_GET['length'] ) : 25; // phpcs:ignore
			$draw         = isset( $_GET['draw'] ) ? absint( $_GET['draw'] ) : 0; // phpcs:ignore
			$search_value = isset( $_GET['search']['value'] ) ? sanitize_text_field( wp_unslash( $_GET['search']['value'] ) ) : ''; // phpcs:ignore

			// DataTables sends length = -1 for the "All" option; guard against an unbounded fetch.
			if ( $limit < 1 ) {
				$limit = 25;
			}
			// Clamp the upper bound so a crafted "length" value can't force a huge query.
			if ( $limit > 200 ) {
				$limit = 200;
			}

			$log_data = Hubwoo::hubwoo_get_log_data( $search_value, $limit, $offset );

			$total_count_data    = Hubwoo::hubwoo_get_total_log_count();
			$filtered_count_data = '' !== $search_value ? Hubwoo::hubwoo_get_total_log_count( $search_value ) : $total_count_data;
			$total_count         = $total_count_data[0];
			$filtered_count      = $filtered_count_data[0];
			$data        = array();
			foreach ( $log_data as $key => $value ) {
				$value[ Hubwoo::get_current_crm_name( 'slug' ) . '_object' ] = ! empty( $value[ Hubwoo::get_current_crm_name( 'slug' ) . '_object' ] ) ? $value[ Hubwoo::get_current_crm_name( 'slug' ) . '_object' ] : '-';
				$value[ Hubwoo::get_current_crm_name( 'slug' ) . '_id' ]     = ! empty( $value[ Hubwoo::get_current_crm_name( 'slug' ) . '_id' ] ) ? $value[ Hubwoo::get_current_crm_name( 'slug' ) . '_id' ] : '-';

				$current_request  = $value['request']; //phpcs:ignore
				$response = maybe_unserialize( $value['response'] ); //phpcs:ignore

				$temp = array(
					'',
					$value['event'],
					$value[ Hubwoo::get_current_crm_name( 'slug' ) . '_object' ],
					gmdate( 'd-m-Y h:i A', esc_html( $value['time'] ) ),
					$current_request,
					wp_json_encode( $response ),
				);

				$data[] = $temp;
			}

			$json_data = array(
				'draw'            => $draw,
				'recordsTotal'    => $total_count,
				'recordsFiltered' => $filtered_count,
				'data'            => $data,
			);

			echo wp_json_encode( $json_data );
			wp_die();
		}

		/**
		 * Download logs from database.
		 */
		public function hubwoo_download_sync_log() {
			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$crm_name = Hubwoo::get_current_crm_name( 'slug' );
			$log_dir  = WC_LOG_DIR . $crm_name . '-sync-log.log';

			// Fetch and write in chunks so neither the DB result set nor the
			// in-memory string ever holds the whole log table at once.
			$chunk_size  = 500;
			$offset      = 0;
			$file_handle = fopen( $log_dir, 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen

			do {
				$log_data = Hubwoo::hubwoo_get_log_data( false, $chunk_size, $offset );

				foreach ( $log_data as $key => $value ) {
					$value[ $crm_name . '_id' ] = ! empty( $value[ $crm_name . '_id' ] ) ? $value[ $crm_name . '_id' ] : '-';
					$log                        = 'Feed : ' . $value['event'] . PHP_EOL;
					$log                       .= ucwords( $crm_name ) . ' Object : ' . $value[ $crm_name . '_object' ] . PHP_EOL;
					$log                       .= 'Time : ' . gmdate( 'd-m-Y h:i A', esc_html( $value['time'] ) ) . PHP_EOL;
					$log                       .= 'Request : ' . $value['request'] . PHP_EOL; // phpcs:ignore
					$log                       .= 'Response : ' . wp_json_encode( maybe_unserialize( $value['response'] ) ) . PHP_EOL;  // phpcs:ignore
					$log                       .= '-----------------------------------------------------------------------' . PHP_EOL;

					fwrite( $file_handle, $log ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
				}

				$offset += $chunk_size;
			} while ( count( $log_data ) === $chunk_size );

			fclose( $file_handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose

			$json_data = array(
				'success'  => true,
				'redirect' => admin_url( 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-logs&hubwoo_download=1' ),
			);

			echo wp_json_encode( $json_data );
			wp_die();
		}

		/**
		 * Clear log from database
		 */
		public function hubwoo_clear_sync_log() {
			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die();
			}

			global $wpdb;
			$table_name = $wpdb->prefix . 'hubwoo_log';

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->query( "DELETE FROM `{$table_name}`" );

			$json_data = array(
				'success'  => true,
				'redirect' => admin_url( 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-logs' ),
			);

			echo wp_json_encode( $json_data );
			wp_die();
		}

		/**
		 * Fetch pipeline deal stages.
		 */
		public function hubwoo_fetch_deal_stages() {
			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$selected_pipeline = ! empty( $_POST['selected_pipeline'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_pipeline'] ) ) : '';

			$all_pipeline = get_option( 'hubwoo_potal_pipelines', true );
			update_option( 'hubwoo_ecomm_pipeline_id', $selected_pipeline, false );
			$deal_stages = '';

			foreach ( $all_pipeline as $single_pipeline ) {
				if ( $single_pipeline['id'] == $selected_pipeline ) {
					$deal_stages = $single_pipeline['stages'];
					if ( 'Ecommerce Pipeline' == $single_pipeline['label'] ) {
						Hubwoo::update_deal_stages_mapping( $deal_stages );
					} else if ( 'Sales Pipeline' == $single_pipeline['label'] ) {
						update_option( 'hubwoo_ecomm_final_mapping', Hubwoo::hubwoo_sales_deals_mapping(), false );
					}
				}
			}

			if ( ! empty( $deal_stages ) ) {

				update_option( 'hubwoo_ecomm_pipeline_created', 'yes', false );
				update_option( 'hubwoo_fetched_deal_stages', $deal_stages, false );
				update_option( 'hubwoo_ecomm_won_stages', '', false );
			}

			echo wp_json_encode(
				array(
					'success'  => true,
				)
			);
			wp_die();
		}

		/**
		 * Fetch deal pipelines.
		 */
		public function hubwoo_fetch_update_pipelines() {
			// Nonce verification.
			check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die();
			}

			$selected_pipeline = ! empty( $_POST['selected_pipeline'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_pipeline'] ) ) : '';

			$all_deal_pipelines = HubWooConnectionMananager::get_instance()->fetch_all_deal_pipelines();

			if ( ! empty( $all_deal_pipelines['results'] ) ) {
				update_option( 'hubwoo_potal_pipelines', $all_deal_pipelines['results'], false );
			}

			$all_pipeline = get_option( 'hubwoo_potal_pipelines', true );
			update_option( 'hubwoo_ecomm_pipeline_id', $selected_pipeline, false );
			$deal_stages = '';

			foreach ( $all_pipeline as $single_pipeline ) {
				if ( $single_pipeline['id'] == $selected_pipeline ) {
					$deal_stages = $single_pipeline['stages'];
				}
			}

			if ( ! empty( $deal_stages ) ) {

				update_option( 'hubwoo_ecomm_pipeline_created', 'yes', false );
				update_option( 'hubwoo_fetched_deal_stages', $deal_stages, false );
				update_option( 'hubwoo_ecomm_won_stages', '', false );
			}

			echo wp_json_encode(
				array(
					'success'  => true,
				)
			);
			wp_die();
		}
	}
}

new Hubwoo_Ajax_Handler();
