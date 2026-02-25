<?php

/**
 * All api GET/POST functionalities.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
 */

/**
 * Handles all hubspot api reqests/response related functionalities of the plugin.
 *
 * Provide a list of functions to manage all the requests
 * that needs in our integration to get/fetch data
 * from/to hubspot.
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/includes
 */
class HubwooObjectProperties
{

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @var HubwooObjectProperties  The single instance of the HubwooObjectProperties
	 */
	protected static $instance = null;
	/**
	 * Main HubwooObjectProperties Instance.
	 *
	 * Ensures only one instance of HubwooObjectProperties is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return HubwooObjectProperties - Main instance.
	 */
	public static function get_instance()
	{

		if (is_null(self::$instance)) {

			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Create/update contact and associate with a deal.
	 *
	 * @since 1.0.0
	 * @param int $user_id - User Id of the contact.
	 * @static
	 * @return  void.
	 */
	public static function hubwoo_ecomm_contacts_with_id($user_id)
	{
		$object_type           = 'CONTACT';
		$contact               = array();

		$hubwoo_customer = new HubWooCustomer($user_id);
		$properties      = $hubwoo_customer->get_contact_properties();
		$user_properties = $hubwoo_customer->get_user_data_properties($properties);
		foreach ($user_properties as $key => $property) {
			$contact[$property['property']] = $property['value'];
		}
		$contact = apply_filters('hubwoo_map_ecomm_' . $object_type . '_properties', $contact, $user_id);

		$user_info = json_decode(json_encode(get_userdata($user_id)), true);
		$user_email = $user_info['data']['user_email'];
		$contact['email'] = $user_email;

		self::hubwoo_create_update_single_contact($contact, $user_email, 'reg', $user_id);
	}

	/**
	 * Create/update a guest user and associate with a deal.
	 *
	 * @since 1.0.0
	 * @param int $order_id - order id of the contact.
	 * @static
	 * @return  void.
	 */
	public static function hubwoo_ecomm_guest_user($order_id)
	{

		global $hubwoo;

		$order = wc_get_order($order_id);
		$guest_email = $order->get_billing_email();
		if (! empty($guest_email)) {
			$contact                                    = array();

			$object_type                                = 'CONTACT';
			$guest_user_info                            = array();
			$guest_order_callback                       = new HubwooGuestOrdersManager($order_id);
			$guest_user_properties                      = $guest_order_callback->get_order_related_properties($order_id, $guest_email);
			foreach ($guest_user_properties as $key => $value) {
				$guest_user_info[$value['property']] = $value['value'];
			}

			$guest_user_info['email']                   = $guest_email;
			$guest_user_info['firstname']               = $order->get_billing_first_name();
			$guest_user_info['lastname']                = $order->get_billing_last_name();
			$guest_user_info['phone']                   = $order->get_billing_phone();
			$guest_user_info['billing_address_line_1']  = $order->get_billing_address_1();
			$guest_user_info['billing_address_line_2']  = $order->get_billing_address_2();
			$guest_user_info['billing_city']            = $order->get_billing_city();
			$billing_state           					= $order->get_billing_state();
			$billing_country         					= $order->get_billing_country();
			$guest_user_info['billing_state']           = Hubwoo::map_state_by_abbr($billing_state, $billing_country);
			$guest_user_info['billing_country']         = Hubwoo::map_country_by_abbr($billing_country);
			$guest_user_info['billing_postal_code']     = $order->get_billing_postcode();
			$guest_user_info['lifecyclestage']          = 'customer';
			$guest_user_info['customer_source_store']   = get_bloginfo('name');
			$guest_user_info['hs_language']             = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_preferred_language', true);
			$guest_contact_properties                   = apply_filters('hubwoo_map_ecomm_guest_' . $object_type . '_properties', $guest_user_info, $order_id);

			self::hubwoo_create_update_single_contact($guest_contact_properties, $guest_email, 'guest', '', $order);
		}
	}

	/**
	 * Create/update an ecommerce deal.
	 *
	 * @since 1.0.0
	 * @param int $order_id - order id.
	 * @param int $source - register or guest.
	 * @param int $customer_id - user id.
	 * @static
	 * @return  array sync response from HubSpot.
	 */
	public static function hubwoo_ecomm_sync_deal($order_id, $source, $customer_id)
	{
		$object_type                 = 'DEAL';
		$deal_properties                = array();
		$order 						 = wc_get_order($order_id);
		$response                    = array('status_code' => 206);

		$assc_deal_cmpy              = get_option('hubwoo_assoc_deal_cmpy_enable', 'yes');
		$pipeline_id                 = get_option('hubwoo_ecomm_pipeline_id', false);
		$hubwoo_ecomm_deal           = new HubwooEcommObject($order_id, $object_type);
		$deal_properties             = $hubwoo_ecomm_deal->get_object_properties();

		if ('yes' == get_option('hubwoo_deal_multi_currency_enable', 'no')) {
			$currency = $order->get_currency();
			if (! empty($currency)) {
				$deal_properties['deal_currency_code'] = $currency;
			}
		}
		if (empty($pipeline_id)) {
			Hubwoo::get_all_deal_stages();
			$pipeline_id = get_option('hubwoo_ecomm_pipeline_id', false);
		}
		$deal_properties['pipeline'] = $pipeline_id;

		$deal_properties = apply_filters('hubwoo_map_ecomm_' . $object_type . '_properties', $deal_properties, $order_id);

		if ('user' == $source) {
			$user_info  = json_decode(wp_json_encode(get_userdata($customer_id)), true);
			$user_email = $user_info['data']['user_email'];
			$contact    = $user_email;
			$contact_vid = get_user_meta($customer_id, 'hubwoo_user_vid', true);
			$invalid_contact = get_user_meta($customer_id, 'hubwoo_invalid_contact', true);
		} else {
			$contact_vid = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_user_vid', true);
			$contact = $order->get_billing_email();
			$invalid_contact = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_invalid_contact', true);
		}

		if (count($deal_properties)) {
			$deal_properties = array('properties' => $deal_properties);

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
				$deal_name  = '#' . $order->get_order_number();
				$user_detail['first_name'] = $order->get_billing_first_name();
				$user_detail['last_name']  = $order->get_billing_last_name();
				foreach ($user_detail as $value) {
					if (! empty($value)) {
						$deal_name .= ' ' . $value;
					}
				}

				$filtergps = array(
					'filterGroups' => array(
						array(
							'filters' => array(
								array(
									'value' => $deal_name,
									'propertyName' => 'dealname',
									'operator' => 'EQ',
								),
								array(
									'value' => $order->get_order_number(),
									'propertyName' => 'order_number',
									'operator' => 'EQ',
								),
							),
						),
					),
					'properties' => array('hs_num_of_associated_line_items')
				);
				$filtergps = apply_filters('hubwoo_deal_search_filter', $filtergps, $order_id);

				$response = HubWooConnectionMananager::get_instance()->search_object_record('deals', $filtergps);
				if (200 == $response['status_code']) {
					$responce_body = json_decode($response['body']);
					$result = $responce_body->results;
					if (! empty($result)) {
						foreach ($result as $key => $value) {
							Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_id', $value->id);
							$num_of_assoc_line_items = $value->properties->hs_num_of_associated_line_items;
							if ($num_of_assoc_line_items != 0) {
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_order_line_item_created', 'yes');
							} else if (empty($num_of_assoc_line_items) || $num_of_assoc_line_items == 0) {
								Hubwoo::hubwoo_hpos_delete_meta_data($order, 'hubwoo_order_line_item_created');
							}
						}
					}

					$hubwoo_ecomm_deal_id = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_ecomm_deal_id', true);

					if (empty($hubwoo_ecomm_deal_id)) {
						$response = HubWooConnectionMananager::get_instance()->create_object_record('deals', $deal_properties);
						if (201 == $response['status_code']) {
							$response_body = json_decode($response['body']);
							$hubwoo_ecomm_deal_id = $response_body->id;
							Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_id', $hubwoo_ecomm_deal_id);
							Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_created', 'yes');
						} else {
							Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_invalid_deal', 'yes');
							Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_created', 'yes');
						}
					} else {
						$response = HubWooConnectionMananager::get_instance()->update_object_record('deals', $hubwoo_ecomm_deal_id, $deal_properties);
						Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_created', 'yes');
						if (200 != $response['status_code']) {
							Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_invalid_deal', 'yes');
							Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_created', 'yes');
						}
					}
					$hubwoo_order_sync_hash = self::get_instance()->hubwoo_get_order_sync_hash($order);
					Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_order_sync_hash', $hubwoo_order_sync_hash);

					if (!empty($hubwoo_ecomm_deal_id) && !empty($contact_vid)) {
						HubWooConnectionMananager::get_instance()->associate_object('deal', $hubwoo_ecomm_deal_id, 'contact', $contact_vid, 3);
					}

					do_action('hubwoo_ecomm_deal_created', $order_id);

					if ('yes' == $assc_deal_cmpy) {
						if (!empty($contact) && empty($invalid_contact) && !empty($hubwoo_ecomm_deal_id)) {
							Hubwoo::hubwoo_associate_deal_company($contact, $hubwoo_ecomm_deal_id, $contact_vid);
						}
					}

					Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_ecomm_deal_upsert', 'no');
					Hubwoo::hubwoo_hpos_delete_meta_data($order, 'hubwoo_ecomm_deal_upsert');

					if (!empty($hubwoo_ecomm_deal_id)) {
						$response = self::hubwoo_ecomm_sync_line_items($order_id);
					}
				}

				return $response;
			}
		}
	}

	/**
	 * Create and Associate Line Items for an order.
	 *
	 * @since 1.0.0
	 * @param int $order_id - order id.
	 * @static
	 * @return  array sync response from HubSpot.
	 */
	public static function hubwoo_ecomm_sync_line_items($order_id)
	{
		if (! empty($order_id)) {

			$order             = wc_get_order($order_id);
			$line_updates      = array();
			$order_items       = $order->get_items();
			$hs_product_ids    = array();
			$invalid_hs_product_ids = array();
			$response          = array('status_code' => 206);

			$deal_id = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_ecomm_deal_id', true);

			if ('yes' != Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_order_line_item_created', 'no')) {
				if (is_array($order_items) && count($order_items)) {
					foreach ($order_items as $item_key => $single_item) :
						$product_id = $single_item->get_variation_id();
						if (0 === $product_id) {
							$product_id = $single_item->get_product_id();
							if (0 === $product_id) {
								continue;
							}
						}

						if (get_post_status($product_id) == 'trash' || get_post_status($product_id) == false) {
							continue;
						}

						$item_sku = get_post_meta($product_id, '_sku', true);
						$quantity        = ! empty($single_item->get_quantity()) ? $single_item->get_quantity() : 0;
						$item_total      = ! empty($single_item->get_total()) ? $single_item->get_total() : 0;
						$item_sub_total  = ! empty($single_item->get_subtotal()) ? $single_item->get_subtotal() : 0;
						$total_tax       = ! empty($single_item->get_total_tax()) ? $single_item->get_total_tax() : 0;
						$item_sku        = ! empty($item_sku) ? $item_sku : '';
						$product         = $single_item->get_product();
						$name            = self::hubwoo_ecomm_product_name($product);
						$discount_amount = abs($item_total - $item_sub_total);
						$discount_amount = $discount_amount / $quantity;
						$item_sub_total  = $item_sub_total / $quantity;
						$object_ids[]    = $item_key;

						$properties = array(
							'quantity'        => $quantity,
							'price'           => $item_sub_total,
							'total_cost'      => $item_total,
							'name'            => $name,
							'discount'        => $discount_amount,
							'sku'             => $item_sku,
							'tax_amount'      => $total_tax,
						);

						if ('yes' != get_option('hubwoo_product_scope_needed', 'no')) {
							$hs_product_id   = get_post_meta($product_id, 'hubwoo_ecomm_pro_id', true);
							if(!empty($hs_product_id)){
								$hs_product_ids[] = array('id' => $hs_product_id);
								$properties['hs_product_id'] = $hs_product_id;
							}
						}

						$properties = apply_filters('hubwoo_line_item_properties', $properties, $product_id, $order_id);

						$line_updates[] = array(
							'properties'       => $properties,
						);
					endforeach;
				}
			}

			$line_updates = apply_filters('hubwoo_custom_line_item', $line_updates, $order_id);
			if (count($line_updates)) {
				if (count($hs_product_ids)) {
					$hs_product_ids = array('inputs' => $hs_product_ids);
					$response = HubWooConnectionMananager::get_instance()->get_batch_object_record('products', $hs_product_ids);
					if ($response['status_code'] == 207) {
						$response_body = json_decode($response['body'], true);
						$invalid_hs_product_ids = $response_body['errors'][0]['context']['ids'] ?? array();
					}
				}

				$modified_line_updates = array();
				foreach ($line_updates as $line_update) {
					if (isset($line_update['properties']['hs_product_id']) && count($invalid_hs_product_ids)) {
						if (in_array($line_update['properties']['hs_product_id'], $invalid_hs_product_ids)) {
							unset($line_update['properties']['hs_product_id']);
						}
					}
					$line_update['associations'] = array(
						array(
							'types' => array(
								array(
									'associationCategory' => "HUBSPOT_DEFINED",
									'associationTypeId' => 20
								)
							),
							'to' => array(
								'id' => $deal_id
							)
						)
					);
					$modified_line_updates[] = $line_update;
				}
				$line_updates = array(
					'inputs' => $modified_line_updates,
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
					$response = HubWooConnectionMananager::get_instance()->create_batch_object_record('line_items', $line_updates);
				}
			}

			if (201 == $response['status_code'] || 206 == $response['status_code'] || empty($object_ids)) {
				if (1 == get_option('hubwoo_deals_sync_running', 0)) {
					$current_count = get_option('hubwoo_deals_current_sync_count', 0);
					update_option('hubwoo_deals_current_sync_count', ++$current_count);
				}
			}
			if (201 == $response['status_code']) {
				Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_order_line_item_created', 'yes');
			}

			return $response;
		}
	}


	/**
	 * Start syncing an ecommerce deal
	 *
	 * @since 1.0.0
	 * @param int $order_id - order id.
	 * @return  array sync response from HubSpot.
	 */
	public function hubwoo_ecomm_deals_sync($order_id, $sync_type = '')
	{

		if (! empty($order_id)) {
			$hubwoo_ecomm_order = wc_get_order($order_id);
			if ($hubwoo_ecomm_order instanceof WC_Order) {

				// Get selected user roles for syncing
				$real_user_roles = get_option('hubwoo-selected-user-roles', array());
				if (empty($real_user_roles)) {
					$real_user_roles = array_keys(Hubwoo_Admin::get_all_user_roles());
					update_option('hubwoo-selected-user-roles', $real_user_roles);
				}
				$historical_user_roles = get_option('hubwoo_customers_role_settings', array());
				if (empty($historical_user_roles)) {
					$historical_user_roles = array_keys(Hubwoo_Admin::get_all_user_roles());
					update_option('hubwoo_customers_role_settings', $historical_user_roles);
				}

				$customer_id = $hubwoo_ecomm_order->get_customer_id();

				if (! empty($customer_id)) {
					$source = 'user';
					$customer = get_userdata($customer_id);
					if ($customer) {
						$customer_user_roles = $customer->roles;
					}
					if (! empty($customer_user_roles)) {
						$roles_to_check = $sync_type == 'real' ? $real_user_roles : $historical_user_roles;
						if (!empty(array_intersect($customer_user_roles, $roles_to_check))) {
							self::hubwoo_ecomm_contacts_with_id($customer_id);
						}
					}
				} else {
					$source = 'guest';
					$roles_to_check = $sync_type == 'real' ? $real_user_roles : $historical_user_roles;
					if (in_array('guest_user', $roles_to_check)) {
						self::hubwoo_ecomm_guest_user($order_id);
					}
				}

				// Check renewal subscription order
				if (class_exists('WC_Subscriptions')) {
					self::hubwoo_check_wcs_renewal_order($order_id);
				}
				Hubwoo::hubwoo_hpos_delete_meta_data($hubwoo_ecomm_order, 'hubwoo_invalid_deal');
				$response = self::hubwoo_ecomm_sync_deal($order_id, $source, $customer_id);
				update_option('hubwoo_last_sync_date', time());
				return $response;
			}
		}
	}

	public static function hubwoo_create_update_single_contact($contact_properties, $user_email, $user_type, $user_id = '', $order = NULL)
	{
		if (count($contact_properties)) {
			if (!(($user_type == 'reg' && !empty($user_id)) || ($user_type == 'guest' && ($order instanceof WC_Order)))) {
				return;
			}

			$contact_properties = array('properties' => $contact_properties);

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
				$filtergps = array(
					'filterGroups' => array(
						array(
							'filters' => array(
								array(
									'value' => $user_email,
									'propertyName' => 'email',
									'operator' => 'EQ',
								),
							),
						),
					),
				);

				$response = HubWooConnectionMananager::get_instance()->search_object_record('contacts', $filtergps);
				if (200 == $response['status_code']) {
					$responce_body = json_decode($response['body']);
					$result = $responce_body->results;
					if (! empty($result)) {
						foreach ($result as $key => $value) {
							if ($user_type == 'reg') {
								update_user_meta($user_id, 'hubwoo_user_vid', $value->id);
							} else if ($user_type == 'guest') {
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_user_vid', $value->id);
							}
						}
					} else {
						if ($user_type == 'reg') {
							delete_user_meta($user_id, 'hubwoo_user_vid');
						} else if ($user_type == 'guest') {
							$order->delete_meta_data('hubwoo_user_vid');
							$order->save();
						}
					}

					if ($user_type == 'reg') {
						$hubwoo_user_vid = get_user_meta($user_id, 'hubwoo_user_vid', true);
					} else if ($user_type == 'guest') {
						$hubwoo_user_vid = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_user_vid', true);
					}
					if (! empty($hubwoo_user_vid)) {
						$response = HubWooConnectionMananager::get_instance()->update_object_record('contacts', $hubwoo_user_vid, $contact_properties);
						if ($user_type == 'reg') {
							update_user_meta($user_id, 'hubwoo_pro_user_data_change', 'synced');
						} else if ($user_type == 'guest') {
							Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_pro_guest_order', 'synced');
						}
					} else {
						$response = HubWooConnectionMananager::get_instance()->create_object_record('contacts', $contact_properties);
						if (201 == $response['status_code']) {
							$contact_vid = json_decode($response['body']);
							if ($user_type == 'reg') {
								update_user_meta($user_id, 'hubwoo_user_vid', $contact_vid->id);
								update_user_meta($user_id, 'hubwoo_pro_user_data_change', 'synced');
							} else if ($user_type == 'guest') {
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_user_vid', $contact_vid->id);
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_pro_guest_order', 'synced');
							}
						} else if (409 == $response['status_code']) {
							$contact_vid = json_decode($response['body']);
							$hs_id = explode('ID: ', $contact_vid->message);
							$response = HubWooConnectionMananager::get_instance()->update_object_record('contacts', $hs_id[1], $contact_properties);
							if ($user_type == 'reg') {
								update_user_meta($user_id, 'hubwoo_user_vid', $hs_id[1]);
								update_user_meta($user_id, 'hubwoo_pro_user_data_change', 'synced');
							} else if ($user_type == 'guest') {
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_user_vid', $hs_id[1]);
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_pro_guest_order', 'synced');
							}
						} else if (400 == $response['status_code']) {
							if ($user_type == 'reg') {
								update_user_meta($user_id, 'hubwoo_invalid_contact', 'yes');
								update_user_meta($user_id, 'hubwoo_pro_user_data_change', 'synced');
							} else if ($user_type == 'guest') {
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_invalid_contact', 'yes');
								Hubwoo::hubwoo_hpos_update_meta_data($order, 'hubwoo_pro_guest_order', 'synced');
							}
						}
					}
				}
			}
		}
	}

	public function hubwoo_get_order_sync_hash($order)
	{
		if (! $order instanceof WC_Order) {
			return false;
		}

		$data = array(
			'status'        		=> $order->get_status(),
			'total'         		=> $order->get_total(),
			'subtotal'      		=> $order->get_subtotal(),
			'currency'      		=> $order->get_currency(),
			'customer_id'   		=> $order->get_customer_id(),
			'date_created'  		=> $order->get_date_created(),
			'billing_email' 		=> $order->get_billing_email(),
			'billing_first_name' 	=> $order->get_billing_first_name(),
			'billing_last_name' 	=> $order->get_billing_last_name(),
			'billing_company' 		=> $order->get_billing_company(),
			'billing_address_1' 	=> $order->get_billing_address_1(),
			'billing_address_2' 	=> $order->get_billing_address_2(),
			'billing_city' 			=> $order->get_billing_city(),
			'billing_state' 		=> $order->get_billing_state(),
			'billing_country' 		=> $order->get_billing_country(),
			'billing_postcode' 		=> $order->get_billing_postcode(),
			'billing_phone' 		=> $order->get_billing_phone(),
			'shipping_first_name' 	=> $order->get_shipping_first_name(),
			'shipping_last_name' 	=> $order->get_shipping_last_name(),
			'shipping_company' 		=> $order->get_shipping_company(),
			'shipping_address_1' 	=> $order->get_shipping_address_1(),
			'shipping_address_2' 	=> $order->get_shipping_address_2(),
			'shipping_city' 		=> $order->get_shipping_city(),
			'shipping_state' 		=> $order->get_shipping_state(),
			'shipping_country' 		=> $order->get_shipping_country(),
			'shipping_postcode' 	=> $order->get_shipping_postcode(),
			'shipping_phone' 		=> $order->get_shipping_phone(),
			'order_items'    		=> array(),
		);
		foreach ($order->get_items() as $item_id => $item) {
			$data['order_items'][] = array(
				'product_id' => $item->get_product_id(),
				'variation_id' => $item->get_variation_id(),
				'quantity'   => $item->get_quantity(),
				'subtotal'   => $item->get_subtotal(),
				'total'      => $item->get_total(),
			);
		}
		sort($data['order_items']);

		return md5(wp_json_encode($data));
	}

	public function hubwoo_needs_to_sync_order($order_id)
	{
		$order = wc_get_order($order_id);
		if (! $order) {
			return false;
		}

		$current_hash = self::get_instance()->hubwoo_get_order_sync_hash($order);
		$stored_hash  = Hubwoo::hubwoo_hpos_get_meta_data($order, 'hubwoo_order_sync_hash', true);

		if (empty($stored_hash)) {
			return true;
		}

		return $current_hash !== $stored_hash;
	}
	/**
	 * Create a formatted name of the product.
	 *
	 * @since 1.0.0
	 * @param int $product product object.
	 * @return string formatted name of the product.
	 */
	public static function hubwoo_ecomm_product_name($product)
	{

		if ($product->get_sku()) {
			$identifier = $product->get_sku();
		} else {
			$identifier = '#' . $product->get_id();
		}
		return sprintf('%2$s (%1$s)', $identifier, $product->get_name());
	}


	/**
	 * Return formatted time for HubSpot
	 *
	 * @param  int $unix_timestamp current timestamp.
	 * @return string formatted time.
	 * @since 1.0.0
	 */
	public static function hubwoo_set_utc_midnight($unix_timestamp)
	{

		$string       = gmdate('Y-m-d H:i:s', $unix_timestamp);
		$date         = new DateTime($string);
		$wp_time_zone = get_option('timezone_string', '');
		if (empty($wp_time_zone)) {
			$wp_time_zone = 'UTC';
		}
		$time_zone = new DateTimeZone($wp_time_zone);
		$date->setTimezone($time_zone);
		return $date->getTimestamp() * 1000; // in miliseconds.
	}

	/**
	 * Remove meta key on renewal subscription order to avoid deal duplication.
	 *
	 * @param int $order_id - order id.
	 * @since 1.6.5
	 */
	private function hubwoo_check_wcs_renewal_order($renewal_order_id)
	{
		$renewal_order    = wc_get_order($renewal_order_id);
		$hubwoo_ecomm_renewal_deal_id = Hubwoo::hubwoo_hpos_get_meta_data($renewal_order, 'hubwoo_ecomm_deal_id', true);

		if ($hubwoo_ecomm_renewal_deal_id && function_exists('wcs_get_subscriptions_for_order')) {
			$subscriptions = wcs_get_subscriptions_for_order(
				$renewal_order,
				array('order_type' => 'renewal')
			);

			foreach ($subscriptions as $subscription) {
				$parent_order_id = $subscription->get_parent_id();
				$parent_order = wc_get_order($parent_order_id);
				$hubwoo_ecomm_parent_deal_id = Hubwoo::hubwoo_hpos_get_meta_data($parent_order, 'hubwoo_ecomm_deal_id', true);
				if (!empty($hubwoo_ecomm_parent_deal_id) && $hubwoo_ecomm_parent_deal_id == $hubwoo_ecomm_renewal_deal_id) {
					$renewal_order->delete_meta_data('hubwoo_ecomm_deal_id');
					$renewal_order->save();
				}
			}
		}
	}
}
