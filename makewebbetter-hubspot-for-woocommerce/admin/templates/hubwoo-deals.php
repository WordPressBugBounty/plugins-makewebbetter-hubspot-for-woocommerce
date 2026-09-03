<?php
/**
 * Manage eCommerce Pipeline and Deals creation.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    makewebbetter-hubspot-for-woocommerce
 * @subpackage makewebbetter-hubspot-for-woocommerce/admin/templates/
 */

global $hubwoo;
$deal_stages       = Hubwoo::get_all_deal_stages();
$sync_data         = Hubwoo::get_sync_status();
$display_data      = Hubwoo::get_deals_presenter();
$fetch_pipeline    = get_option( 'hubwoo_potal_pipelines', true );
$selected_pipeline = get_option( 'hubwoo_ecomm_pipeline_id', true );
$deal_stage_id     = 'stageId';

if ( 'yes' == get_option( 'hubwoo_ecomm_pipeline_created', 'no' ) ) {
	$deal_stage_id = 'id';
}

?>
<div class="hubwoo-form-wizard-wrapper">
	<input type="hidden" id="hubwoo_show_hpos_lock" value="<?php echo esc_attr( $display_data['show_hpos_lock'] ); ?>">

	<?php $hpos_lock_needs_activation = ( 'true' === $display_data['hpos_lock_needs_activation'] ); ?>
	<div class="hubwoo_pop_up_wrap hubwoo-hpos-lock-popup hubwoo-pop-up-scoped" style="display: none">
		<div class="pop_up_sub_wrap<?php echo $hpos_lock_needs_activation ? ' hubwoo-hpos-lock-single' : ''; ?>">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-overview' ) ); ?>" class="hubwoo-pop-up-close" aria-label="<?php esc_attr_e( 'Close', 'makewebbetter-hubspot-for-woocommerce' ); ?>"></a>
			<div class="hubwoo_pop_up_wrap--content">
				<div class="hubwoo_pop_up_wrap--inner-content">
					<h2>
						<?php esc_html_e( 'HPOS Compatibility Required', 'makewebbetter-hubspot-for-woocommerce' ); ?>
					</h2>
					<p style="text-align: center;font-size: 17px;">
						<?php esc_html_e( 'High-Performance Order Storage is active on your store, but the HPOS Compatibility add-on isn\'t. Deals won\'t sync correctly until it\'s installed and activated.', 'makewebbetter-hubspot-for-woocommerce' ); ?>
					</p>
					<div class="button_wrap">
						<?php if ( $hpos_lock_needs_activation ) : ?>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-hpos-template' ) ); ?>" class="upgrade_hubspot_plan"><?php esc_html_e( 'Activate HPOS add-on', 'makewebbetter-hubspot-for-woocommerce' ); ?></a>
						<?php else : ?>
							<a href="https://makewebbetter.com/product/hubspot-woocommerce-hpos-compatibility/?utm_source=MWB-HubspotFree-backend&utm_medium=MWB-backend&utm_campaign=backend" target="_blank" class="upgrade_hubspot_plan"><?php esc_html_e( 'Buy Now', 'makewebbetter-hubspot-for-woocommerce' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php if ( ! $hpos_lock_needs_activation ) : ?>
			<div class="hubwoo_pop_up_wrap--image">
				<div class="hubwoo_pop_up_wrap--image--inner-content">
					<h2>
						<?php esc_html_e( 'Connect with MakeWebBetter to learn more', 'makewebbetter-hubspot-for-woocommerce' ); ?>
					</h2>
					<p>
						<?php esc_html_e( 'MakeWebBetter is a HubSpot Elite Solutions Partner. Schedule a meeting with our experts to learn more.', 'makewebbetter-hubspot-for-woocommerce' ); ?>
					</p>
					<a target="_blank" href="https://meetings.hubspot.com/makewebbetter/free-hubspot-consultation?utm_source=MWB-HubspotFree-backend&utm_medium=MWB-backend&utm_campaign=backend"><?php esc_html_e( 'Schedule meeting', 'makewebbetter-hubspot-for-woocommerce' ); ?></a>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="hubwoo-form-wizard-content-wrapper<?php echo ( 'true' === $display_data['show_hpos_lock'] ) ? ' hubwoo-hpos-locked' : ''; ?>">

		<div class="hubwoo-group-wrap__deal_notice deals-par" data-type='scope' style="display: <?php echo esc_attr( $display_data['scope_notice'] ); ?>">
			<p class="hubwoo_deals_message">
				<?php
					esc_html_e(
						'eCommerce scopes are missing, please Re-authorize with a Super Admin account from the dashboard to start eCommerce pipeline setup.',
						'makewebbetter-hubspot-for-woocommerce'
					);
					?>
			</p>
		</div>

		<!--- Order and Deal Stage Mappping  -->

		<div class="hubwoo-form-wizard-content hubwoo-deal-wrap-con" data-tab-content="map-deal-stage" style="display: <?php echo esc_attr( $display_data['view_all'] ); ?>">
			<div class="hubwoo-deal-wrap-con-flex">
				<div class="hubwoo-deal-wrap-con__h-con">
					<div class="hubwoo-fields-header hubwoo-common-header">
						<h2 class=""><?php esc_html_e( 'Map Deal Stages with eCommerce pipeline', 'makewebbetter-hubspot-for-woocommerce' ); ?></h2>
					</div>
					<div class="hubwoo-deal-wrap-con__intro">
						<?php esc_html_e( 'Sync order statuses with deal stages so you can manage your eCommerce pipeline in HubSpot.', 'makewebbetter-hubspot-for-woocommerce' ); ?>
					</div>
				</div>
				<div class="hubwoo-deal-wrap-con__h-btn">
					<a class="hubwoo__btn" style="display: <?php echo esc_attr( $display_data['view_button'] ); ?>">
						<?php esc_html_e( 'View', 'makewebbetter-hubspot-for-woocommerce' ); ?>
					</a>
				</div>
			</div>

			<div class="hubwoo-general-settings hubwoo-group-wrap__map_deal_stage hubwoo-settings-container hubwoo-deal-wrap-con__store" style="display: <?php echo esc_attr( $display_data['view_mapping'] ); ?>">
				<div>
					<table class="hubwoo-pipeline-stages-conf-table form-table">
						<tr>
							<th class="hubwoo-pipeline-wrap-con__thead"><?php esc_html_e( 'Select Pipeline', 'makewebbetter-hubspot-for-woocommerce' ); ?></th>
							<td>
								<select class="hubwoo_selected_pipeline" name="hubwoo_selected_pipeline">
									<?php
									if ( ! empty( $fetch_pipeline ) ) {
										foreach ( $fetch_pipeline as $single_pipeline ) {

											if ( $single_pipeline['id'] === $selected_pipeline ) {
												?>
													<option value="<?php echo esc_attr( $single_pipeline['id'] ); ?>" selected=""><?php echo esc_html( $single_pipeline['label'] ); ?></option>
													<?php
											} else {
												?>
													<option value="<?php echo esc_attr( $single_pipeline['id'] ); ?>"><?php echo esc_html( $single_pipeline['label'] ); ?></option>
													<?php
											}
										}
									}
									?>
								</select>
								<a class="hubwoo_update_pipelines"><i class="fa fa-refresh" style="font-size:24px;"></i></a>
							</td>
						</tr>
					</table>
				</div>
				<form action="#" method="post" class="hubwoo_save_ecomm_mapping">
					<table class="hubwoo-deals-stages-conf-table form-table">
						<thead>
							<tr>
								<th class="hubwoo-deal-wrap-con__thead"><?php esc_html_e( 'WooCommerce Order Status', 'makewebbetter-hubspot-for-woocommerce' ); ?></th>
								<th><?php esc_html_e( 'Deal Stage', 'makewebbetter-hubspot-for-woocommerce' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $all_order_statuses = Hubwoo::hubwoo_get_valid_order_statuses(); ?>
							<?php
							foreach ( $all_order_statuses as $order_key => $order_label ) {
								$stage = Hubwoo::get_selected_deal_stage( $order_key );
								?>
									<tr>
										<th class="hubwoo-deal-wrap-con__thead">
											<?php echo esc_html( $order_label ); ?>
											<input type="hidden" name="hubwoo_woo_order_statuses[]" value="<?php echo esc_html( $order_key ); ?>">
										</th>
										<td>
											<select class="hubwoo_ecomm_mapping" name="hubwoo_deal_stages[]">
												<?php
												if ( ! empty( $deal_stages ) ) {
													foreach ( $deal_stages as $single_deal_stage ) {

														if ( $single_deal_stage[ $deal_stage_id ] === $stage ) {
															?>
																<option value="<?php echo esc_attr( $single_deal_stage[ $deal_stage_id ] ); ?>" selected=""><?php echo esc_html( $single_deal_stage['label'] ); ?></option>
																<?php
														} else {
															?>
																<option value="<?php echo esc_attr( $single_deal_stage[ $deal_stage_id ] ); ?>"><?php echo esc_html( $single_deal_stage['label'] ); ?></option>
																<?php
														}
													}
												}
												?>
											</select>
										</td>
									</tr>
								<?php
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td>
									<button id="reset-deal-stages" class="hubwoo__btn hubwoo-btn--primary hubwoo-btn--dashboard" style="display: <?php echo esc_attr( $display_data['view_all'] ); ?>"><?php esc_html_e( ' Reset to Default Mapping', 'makewebbetter-hubspot-for-woocommerce' ); ?>
									</button>
									<button id="recreate-ecomm-pipeline" class="hubwoo__btn hubwoo-btn--primary hubwoo-btn--dashboard" style="display: <?php echo esc_attr( $display_data['show_pipeline_retry'] ); ?>"><?php esc_html_e( ' Create Ecommerce Pipeline', 'makewebbetter-hubspot-for-woocommerce' ); ?>
									</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</form>				
			</div>									
		</div>

		<div class="hubwoo-form-wizard-content hubwoo-deal-wrap-con" data-tab-content="deal-settings" style="display: <?php echo esc_attr( $display_data['view_all'] ); ?>">
			<div class="hubwoo-group-wrap__deal_settings hubwoo-deal-wrap-con">
				<div class="hubwoo-deal-wrap-con-flex">
				<div class="hubwoo-deal-wrap-con__h-con">
					<div class="hubwoo-fields-header hubwoo-common-header">
						<h2 class=""><?php esc_html_e( 'Create Deals for New Orders', 'makewebbetter-hubspot-for-woocommerce' ); ?></h2>
					</div>
					<div class="hubwoo-deal-wrap-con__intro">
						<?php esc_html_e( 'Create Deals in real time for the new orders that are mapped with winning deal stages.', 'makewebbetter-hubspot-for-woocommerce' ); ?>
					</div>
				</div>
				<div class="hubwoo-deal-wrap-con__h-btn">
					<a href="javascript:;" class="hubwoo__btn">
						<?php esc_html_e( 'View', 'makewebbetter-hubspot-for-woocommerce' ); ?>
					</a>
				</div>
			</div>			
				<div class="hubwoo-settings-container hubwoo-deal-wrap-con__store hubwoo-general-settings">
					<form method="POST" id="hubwoo_real_time_deal_settings" class="hubwoo_form_submitted">					
						<?php
						if ( empty( get_option( 'hubwoo_ecomm_won_stages', '' ) ) ) {
							$stages = array_map(
								function( $stage ) {
									return strval( $stage );
								},
								array_keys( Hubwoo_Admin::hubwoo_ecomm_get_stages() )
							);
							update_option( 'hubwoo_ecomm_won_stages', $stages, false );
						}
							woocommerce_admin_fields( Hubwoo_Admin::hubwoo_ecomm_general_settings() );
						?>

					</form>
				</div>			
			</div>		
		</div>

		<div class="hubwoo-form-wizard-content hubwoo-deal-wrap-con" data-tab-content="deal-ocs" style="display: <?php echo esc_attr( $display_data['view_all'] ); ?>">
			<div class="hubwoo-group-wrap__deal_ocs hubwoo-deal-wrap-con">
				<div class="hubwoo-deal-wrap-con-flex">
					<div class="hubwoo-deal-wrap-con__h-con">
						<div class="hubwoo-fields-header hubwoo-common-header">
							<h2 class=""><?php esc_html_e( 'Sync Historical Orders as Deals', 'makewebbetter-hubspot-for-woocommerce' ); ?></h2>
						</div>
						<div class="hubwoo-deal-wrap-con__intro">
							<?php esc_html_e( 'Select Order status and the time frame and start syncing all of those orders as deals in HubSpot.', 'makewebbetter-hubspot-for-woocommerce' ); ?>
						</div>
					</div>
					<div class="hubwoo-deal-wrap-con__h-btn">
						<a href="javascript:;" class="hubwoo__btn" style="display: <?php echo esc_attr( $display_data['button'] ); ?>" >
							<?php esc_html_e( 'View', 'makewebbetter-hubspot-for-woocommerce' ); ?>						
						</a>
					</div>
				</div>						
				<div data-txn="ocs-form" class="hubwoo-group-wrap__deal_ocs hubwoo-deal-wrap-con__store hubwoo-general-settings" style="display:<?php echo esc_attr( $display_data['message'] ); ?>">
					<div class="hubwoo-group-wrap__deal_notice deals-par" data-type='pBar' style="display: <?php echo esc_attr( $display_data['message'] ); ?>">
						<p class="hubwoo_deals_message sync-desc" data-sync-type = "order" data-sync-eta = "<?php echo ( isset( $sync_data['eta_deals_sync'] ) && ! empty( $sync_data['eta_deals_sync'] ) ) ? esc_attr( $sync_data['eta_deals_sync'] ) : ''; ?>">
						<?php
								echo esc_textarea(
									'Your orders are syncing as deals in the background so you can safely leave this page. It should take ',
									'makewebbetter-hubspot-for-woocommerce'
								);
								?>
							<?php
								echo esc_attr( $sync_data['eta_deals_sync'] );
							?>
							<?php 
								echo esc_textarea( ' to complete.', 'makewebbetter-hubspot-for-woocommerce' ); 
							?>

						</p>
						<div class="manage-ocs-bar" >						
							<div class="hubwoo-progress-wrap progress-cover deal-sync_progress" style="display: <?php echo esc_attr( $display_data['message'] ); ?>">
								<div class="hubwoo-progress">
									<div class="hubwoo-progress-bar" data-percentage= "<?php echo isset( $sync_data['deals_progress'] ) ? esc_attr( $sync_data['deals_progress'] ) : 0; ?>"  data-sync-type = "order" data-sync-status = "<?php echo esc_attr( $display_data['is_dsync'] ); ?>" role="progressbar" style="width: <?php echo isset( $sync_data['deals_progress'] ) ? esc_attr( $sync_data['deals_progress'] ) : 0; ?>%">
										<?php echo isset( $sync_data['deals_progress'] ) ? esc_textarea( $sync_data['deals_progress'] ) : 0; ?>%
									</div>
								</div> 
							</div>						
							<button class="hubwoo__btn manage_deals_ocs" data-action = "<?php echo esc_attr( $display_data['btn_data'] ); ?>"><?php echo esc_textarea( $display_data['btn_text'], 'makewebbetter-hubspot-for-woocommerce' ); ?></button>
						</div>
					</div>				
					<form method="POST" id="hubwoo_deals_ocs_form" class="hubwoo_form_submitted">					
						<?php
						if ( empty( get_option( 'hubwoo_ecomm_order_ocs_status', '' ) ) ) {
							update_option( 'hubwoo_ecomm_order_ocs_status', array_keys( Hubwoo::hubwoo_get_valid_order_statuses() ), false );
						}
							woocommerce_admin_fields( Hubwoo_Admin::hubwoo_ecomm_order_ocs_settings() );
						?>
					</form>				
				</div>		
			</div>			
		</div>
	</div>
</div>
