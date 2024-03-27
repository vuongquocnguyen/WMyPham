<?php
/**
* Plugin Name: Bargain
* Plugin URI: https://www.quantumcloud.com/products/plugins/
* Description: Bargain Bot for WooCommerce
* Version: 1.5.6
* Author: QunatumCloud
* Author URI: https://www.quantumcloud.com/
* Requires at least: 4.6
* Tested up to: 6.4
* Text Domain: woo-minimum-acceptable-price
* License: GPL2
*/

defined('ABSPATH') or die("No direct script access!");

if ( ! defined( 'QCLD_MAP_PLUGIN_URL' ) ) {
	define('QCLD_MAP_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if ( ! defined( 'QCLD_MAP_EXTENDED_IMG_URL' ) ) {
	define('QCLD_MAP_EXTENDED_IMG_URL', QCLD_MAP_PLUGIN_URL . "images/");
}


require_once('wpbot-minimum-acceptable-price-function.php');
require_once('wpbot-minimum-acceptable-price-assets.php');
require_once('wpbot-minimum-acceptable-price-variation.php');


require_once( 'qc-support-promo-page/class-qc-support-promo-page.php' );

include_once('class-qcld-free-plugin-upgrade-notice.php');

require_once('class-plugin-deactivate-feedback.php');
$wpbot_feedback = new Wp_Usage_bargain_Feedback( __FILE__, 'plugins@quantumcloud.com', false, true );

/*
* Post Type settings area
*/
class Qcld_Bargain_Admin_Area_Controller {
	
	function __construct(){
		add_action( 'admin_menu', array($this,'qcld_bargain_admin_menu') );
		add_action( 'admin_init', array($this, 'qcld_bargain_register_plugin_settings') );
		add_action( 'activated_plugin', array( $this, 'qcld_bargain_activation_redirect') );
	}

	public function qcld_bargain_admin_menu(){

		add_menu_page( 
			esc_html__('Bargain Bot','woo-minimum-acceptable-price'), 
			esc_html__('Bargain Bot','woo-minimum-acceptable-price'), 
			'manage_options', 
			'wbpt-minimum-acceptable-price-page', 
			array( $this, 'qcld_bargain_settings_page' ), 
			'dashicons-cart', 
			'9' 
		);

		add_submenu_page(
	        "wbpt-minimum-acceptable-price-page",
	        esc_html__('WoowBot'),
	        esc_html__('WoowBot'),
	        'manage_options',
	        "wbpt-map-woowbot-supports",
	        array( $this, 'qcld_bargain_woowbot_settings_page' )
	    );
			
		
    }
	
	public function qcld_bargain_register_plugin_settings(){


		$args = array(
			'type' => 'string', 
			'sanitize_callback' => 'sanitize_text_field',
			'default' => NULL,
		);	

		$args_email = array(
			'type' => 'string', 
			'sanitize_callback' => 'sanitize_email',
			'default' => NULL,
		);	

		
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_enable', $args );

		if ( is_plugin_active( 'wpbot-pro/qcld-wpwbot.php' ) || is_plugin_active( 'woowbot-woocommerce-chatbot-pro/qcld-woowbot.php' )) {
			register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_product_lightbox_modal', $args );
		}

		register_setting( 'qc-wppt-map-plugin-settings-group', 'qlcd_map_chatbot_admin_email', $args_email );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qlcd_map_chatbot_admin_email_subject', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_button_text', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_heading_text', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_heading_text_again', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_low_alert_text', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_low_alert_text_two', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_too_low_alert_text', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_acceptable_price', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_acceptable_copoun_code', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_more_than_offer_price', $args );

		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_talk_to_boss', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_get_email_address', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_thanks_test', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_negotiating_test', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_congratulations_text', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_modal_submit_button_text', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_congratulations_added_to_cart_msg', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_congratulations_added_to_cart', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_modal_checkout_now_button_text', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_product_lightbox_modal', $args );

		

		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_modal_yes_button_text', $args );

		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_modal_no_button_text', $args );
		
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_modal_or_button_text', $args );
		register_setting( 'qc-wppt-map-plugin-settings-group', 'qcld_minimum_accept_price_enable_variable_validation', $args );

		// exit intent


		
	}
	
	public function qcld_bargain_activation_redirect( $plugin ) {
		if( $plugin == plugin_basename( __FILE__ ) ) {
			exit( wp_redirect( admin_url( 'admin.php?page=wbpt-minimum-acceptable-price-page') ) );
		}
	}
	
	public function qcld_bargain_settings_page(){
		
	?>
	<div class="wrap swpm-admin-menu-wrap" id="tabs">
		<h1><?php esc_html_e('Bargaining Chatbot Settings','woo-minimum-acceptable-price') ?></h1>
	
		<ul class="nav-tab-wrapper bargain_nav_container">
			<li style="margin-bottom:0px"><a class="nav-tab bargain_click_handle nav-tab-active" href="#tab-1"><?php esc_html_e('Settings','woo-minimum-acceptable-price') ?></a></li>
			<li style="margin-bottom:0px"><a class="nav-tab bargain_click_handle" href="#tab-2"><?php esc_html_e('Exit Intent','woo-minimum-acceptable-price') ?></a></li>
			<li style="margin-bottom:0px"><a class="nav-tab bargain_click_handle" href="#tab-3"><?php esc_html_e('Email Settings','woo-minimum-acceptable-price') ?></a></li>
			<li style="margin-bottom:0px"><a class="nav-tab bargain_click_handle" href="#tab-4"><?php esc_html_e('Help','woo-minimum-acceptable-price') ?></a></li>
		</ul>
		
		<form method="post" action="options.php">

			<?php settings_fields( 'qc-wppt-map-plugin-settings-group' ); ?>
			<?php do_settings_sections( 'qc-wppt-map-plugin-settings-group' ); ?>
			<div id="tab-1">
				<table class="form-table">
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Enable Bargaining Chatbot','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="checkbox" name="qcld_minimum_accept_price_enable" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_enable')!=''? esc_attr( get_option('qcld_minimum_accept_price_enable')) : '1' ); ?>" <?php echo (get_option('qcld_minimum_accept_price_enable') == '' ? esc_attr( get_option('qcld_minimum_accept_price_enable')): esc_attr( 'checked="checked"' )); ?>  />	
							<i><?php esc_html_e('If you enable this option a new "Minimum Acceptable Price" field will be added along with all product which will be your last bargain price. You have to set the minimum price with the product to get the "Make Your Offer Now" button visible.','woo-minimum-acceptable-price') ?></i>							
						</td>
					</tr>
					<?php 
					 //if ( is_plugin_active( 'wpbot/qcld-wpwbot.php' ) || is_plugin_active( 'woowbot-woocommerce-chatbot/qcld-woowbot.php' )) {

					if ( is_plugin_active( 'wpbot-pro/qcld-wpwbot.php' ) || is_plugin_active( 'woowbot-woocommerce-chatbot-pro/qcld-woowbot.php' ) || is_plugin_active( 'wpbot/qcld-wpwbot.php' ) || is_plugin_active( 'woowbot-woocommerce-chatbot/qcld-woowbot.php' )){
					?>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Lightbox Modal','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="checkbox" name="qcld_minimum_accept_product_lightbox_modal" size="100" value="<?php echo (get_option('qcld_minimum_accept_product_lightbox_modal')!=''? esc_attr( get_option('qcld_minimum_accept_product_lightbox_modal')) : '1' ); ?>" <?php echo (get_option('qcld_minimum_accept_product_lightbox_modal') == '' ? esc_attr( get_option('qcld_minimum_accept_product_lightbox_modal')): esc_attr( 'checked="checked"' )); ?> />	
							<i><?php esc_html_e('Use Lightbox Modal instead of WoowBot for bargaining','woo-minimum-acceptable-price') ?> </i>							
						</td>
					</tr>

				<?php } ?>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Exclude sale items','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="checkbox" name="qcld_minimum_accept_product_exclude" size="100" value="<?php echo (get_option('qcld_minimum_accept_product_exclude')!=''? esc_attr( get_option('qcld_minimum_accept_product_exclude')) : '1' ); ?>" <?php echo (get_option('qcld_minimum_accept_product_exclude') == '' ? esc_attr( get_option('qcld_minimum_accept_product_exclude')): esc_attr( 'checked="checked"' )); ?>   disabled/>	
							<i><?php esc_html_e('If enabled Bargaining button will not display for products that are already on sale.','woo-minimum-acceptable-price') ?> ( <strong style="color:red">Pro Feature </strong> ) </i>							
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Enable global maximum acceptable discount for all products','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="checkbox" name="qcld_minimum_accept_product_discount_for_all_enable" size="100" value="<?php echo (get_option('qcld_minimum_accept_product_discount_for_all_enable')!=''? esc_attr( get_option('qcld_minimum_accept_product_discount_for_all_enable')) : '1' ); ?>" <?php echo (get_option('qcld_minimum_accept_product_discount_for_all_enable') == '' ? esc_attr( get_option('qcld_minimum_accept_product_discount_for_all_enable')): esc_attr( 'checked="checked"' )); ?>  disabled />	
							<i><?php esc_html_e('You can also define Minimum Acceptable Price under individual product to Override the global value.','woo-minimum-acceptable-price') ?> ( <strong style="color:red">Pro Feature </strong> ) </i>							
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Global Product Discount','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="number" name="qcld_minimum_accept_product_discount_price" style="width:100px" size="100" value="<?php echo (get_option('qcld_minimum_accept_product_discount_price')!=''?esc_attr( get_option('qcld_minimum_accept_product_discount_price')):''); ?>" disabled /> <b>( % )</b> ( <strong style="color:red">Pro Feature </strong> ) 
							<br><br><i><?php esc_html_e('You can also define Minimum Acceptable Price under individual product without enabling Global discount.','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Make Your Offer Now','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_button_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_button_text')!=''?esc_attr( get_option('qcld_minimum_accept_price_button_text')):'Make Your Offer Now'); ?>"  />
							<br><br><i><?php esc_html_e('You can change the button text as your needs.','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Make me an offer that I cannot refuse!','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_heading_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_heading_text')!=''?esc_attr( get_option('qcld_minimum_accept_price_heading_text')):'Make me an offer that I cannot refuse!'); ?>"  />
							<br><br><i><?php esc_html_e('You can change the heading text as your needs.','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('It seems like you have not provided any offer amount. Please give me a number!','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_heading_text_again" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_heading_text_again')!=''?esc_attr( get_option('qcld_minimum_accept_price_heading_text_again')):'It seems like you have not provided any offer amount. Please give me a number!'); ?>"  />
							<br><br><i><?php esc_html_e('You can change the text as your needs.','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Your offered price {offer price} is too low for us. Can you do better?','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_low_alert_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_low_alert_text')!=''?esc_attr( get_option('qcld_minimum_accept_price_low_alert_text')):"Your offered price {offer price} is too low for us. Can you do better?"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Low price alert text as your needs. variable:- {offer price}','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Your offered price {offer price} is too low for us.','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_low_alert_text_two" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_low_alert_text_two')!=''?esc_attr( get_option('qcld_minimum_accept_price_low_alert_text_two')):"Your offered price {offer price} is too low for us."); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Low price alert text as your needs. variable:- {offer price}','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('The best we can do for you is {minimum amount}. Do you accept?','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_too_low_alert_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_too_low_alert_text')!=''?esc_attr( get_option('qcld_minimum_accept_price_too_low_alert_text')):"The best we can do for you is {minimum amount}. Do you accept?"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Low price alert text as your needs. variable:- {offer price}, {minimum amount}','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Your offered price {offer price} is acceptable.','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_acceptable_price" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_acceptable_price')!=''?esc_attr( get_option('qcld_minimum_accept_price_acceptable_price')):"Your offered price {offer price} is acceptable."); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs. variable:- {offer price}','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Should we finalize the deal and add the product to you cart so you can check out?','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_acceptable_copoun_code" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_acceptable_copoun_code')!=''?esc_attr( get_option('qcld_minimum_accept_price_acceptable_copoun_code')):"Should we finalize the deal and add the product to you cart so you can check out?"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Your offer {offer price} is more than I could take. Please purchase at our original cost.','woo-minimum-acceptable-price') ?> </th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_more_than_offer_price" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_more_than_offer_price')!=''?esc_attr( get_option('qcld_minimum_accept_price_more_than_offer_price')):"Your offer {offer price} is more than I could take. Please purchase at our original cost."); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.  variable:- {offer price}','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Please tell me your final price. I will talk to my boss.','woo-minimum-acceptable-price') ?> </th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_talk_to_boss" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_talk_to_boss')!=''?esc_attr( get_option('qcld_minimum_accept_price_talk_to_boss')):"Please tell me your final price. I will talk to my boss."); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?></i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Please tell me your email address so I can get back to you.','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_get_email_address" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_get_email_address')!=''?esc_attr( get_option('qcld_minimum_accept_price_get_email_address')):"Please tell me your email address so I can get back to you."); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Thank you. We will get back to you ASAP!','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_thanks_test" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_thanks_test')!=''?esc_attr( get_option('qcld_minimum_accept_price_thanks_test')):"Thank you. We will get back to you ASAP!"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Negotiating your price...','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_negotiating_test" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_negotiating_test')!=''?esc_attr( get_option('qcld_minimum_accept_price_negotiating_test')):"Negotiating your price..."); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('CONGRATULATIONS!','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_congratulations_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_congratulations_text')!=''?esc_attr( get_option('qcld_minimum_accept_congratulations_text')):"CONGRATULATIONS!"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Added to cart','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_congratulations_added_to_cart_msg" size="100" value="<?php echo (get_option('qcld_minimum_accept_congratulations_added_to_cart_msg')!=''?esc_attr( get_option('qcld_minimum_accept_congratulations_added_to_cart_msg')):"Adding to cart"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Your product successfully added to cart','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_congratulations_added_to_cart" size="100" value="<?php echo (get_option('qcld_minimum_accept_congratulations_added_to_cart')!=''?esc_attr( get_option('qcld_minimum_accept_congratulations_added_to_cart')):"Your product has been added to the cart."); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Modal Submit button text','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_modal_submit_button_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_modal_submit_button_text')!=''?esc_attr( get_option('qcld_minimum_accept_modal_submit_button_text')):"Submit"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Modal Checkout Now button text','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_modal_checkout_now_button_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_modal_checkout_now_button_text')!=''?esc_attr( get_option('qcld_minimum_accept_modal_checkout_now_button_text')):"Checkout Now"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Modal Yes Button Text','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_modal_yes_button_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_modal_yes_button_text')!=''?esc_attr( get_option('qcld_minimum_accept_modal_yes_button_text')):"Yes"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Modal No Button Text','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_modal_no_button_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_modal_no_button_text')!=''?esc_attr( get_option('qcld_minimum_accept_modal_no_button_text')):"No"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Modal OR button text','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_modal_or_button_text" size="100" value="<?php echo (get_option('qcld_minimum_accept_modal_or_button_text')!=''?esc_attr( get_option('qcld_minimum_accept_modal_or_button_text')):"OR"); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Response text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Do Not Validate Variation Products', 'woo-minimum-acceptable-price' ) ?></th>
						<td>
							<input type="checkbox" name="qcld_minimum_accept_price_enable_variable_validation" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_enable_variable_validation')!=''? esc_attr( get_option('qcld_minimum_accept_price_enable_variable_validation')) : '1' ); ?>" <?php echo (get_option('qcld_minimum_accept_price_enable_variable_validation') == '' ? esc_attr( get_option('qcld_minimum_accept_price_enable_variable_validation')): esc_attr( 'checked="checked"' )); ?>  />	
							<i><?php esc_html_e( 'Do Not Validate Variation Products for Add to Cart (Enable this if products are not added to cart after Bargaining)', 'woo-minimum-acceptable-price' ) ?></i>						
						</td>
					</tr>
					
				</table>
			</div>
			<div id="tab-2">
				<table class="form-table">
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Exit Intent for product single page','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="checkbox" name="qcld_minimum_accept_product_exit_intent_single_page" size="100" value="" disabled  />	
							<i><?php esc_html_e('Trigger bargain bot on Exit Intent for product single pages','woo-minimum-acceptable-price') ?> ( <strong style="color:red">Pro Feature </strong> ) </i>	
											
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Show only once per visit.','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="checkbox" name="qcld_minimum_accept_product_exit_intent_one_per_visit" size="100" value="" disabled />	
							<i><?php esc_html_e('Exit Intent Show only once per visit.','woo-minimum-acceptable-price') ?> ( <strong style="color:red">Pro Feature </strong> ) </i>							
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Individual Exit Intent', 'woo-minimum-acceptable-price' ) ?></th>
						<td>
							<input type="checkbox" name="qcld_minimum_accept_product_exit_intent_individual_work" size="100" value="" disabled />	
							<i><?php esc_html_e('Individually work with Exit Intent if Disable Bargaining Chatbot','woo-minimum-acceptable-price') ?> ( <strong style="color:red">Pro Feature </strong> )</i>							
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Don't like the price?",'woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_exit_intent_lang_price" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_exit_intent_lang_price')!=''?esc_attr( get_option('qcld_minimum_accept_price_exit_intent_lang_price')):"Don't like the price?"); ?>" disabled />
							<br><br><i><?php esc_html_e('You can change the button text as your needs.','woo-minimum-acceptable-price') ?> ( <strong style="color:red">Pro Feature </strong> ) </i>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e('If you have something on your mind, let us know your best offer. Maybe we can work something out together.','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_exit_intent_lang_msg" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_exit_intent_lang_msg')!=''?esc_attr( get_option('qcld_minimum_accept_price_exit_intent_lang_msg')):'If you have something on your mind, let us know your best offer. Maybe we can work something out together.'); ?>" disabled />
							<br><br><i><?php esc_html_e('You can change the button text as your needs.','woo-minimum-acceptable-price') ?> ( <strong style="color:red">Pro Feature </strong> )</i>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Let's Make a Deal",'woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qcld_minimum_accept_price_exit_intent_lang_button" size="100" value="<?php echo (get_option('qcld_minimum_accept_price_exit_intent_lang_button')!=''?esc_attr( get_option('qcld_minimum_accept_price_exit_intent_lang_button')):"Let's Make a Deal"); ?>" disabled />
							<br><br><i><?php esc_html_e('You can change the button text as your needs.','woo-minimum-acceptable-price') ?> ( <strong style="color:red">Pro Feature </strong> )</i>
						</td>
					</tr>

				</table>
			</div>
			<div id="tab-3">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Emails will be Sent to','woo-minimum-acceptable-price') ?></th>
						<td>
							<?php $admin_email = get_option('admin_email'); ?>
							<input type="text" name="qlcd_map_chatbot_admin_email" size="100" value="<?php echo (get_option('qlcd_map_chatbot_admin_email')!=''?esc_attr( get_option('qlcd_map_chatbot_admin_email')): $admin_email ); ?>"  />
							<br><br><i><?php esc_html_e('You can change the Email Address as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Email Subject','woo-minimum-acceptable-price') ?></th>
						<td>
							<input type="text" name="qlcd_map_chatbot_admin_email_subject" size="100" value="<?php echo (get_option('qlcd_map_chatbot_admin_email_subject')!=''?esc_attr( get_option('qlcd_map_chatbot_admin_email_subject')): 'Query details from WPWBot by Client' ); ?>"  />
							<br><br><i><?php esc_html_e('You can change the heading text as your needs.','woo-minimum-acceptable-price') ?> </i>
						</td>
					</tr>

				</table>
			</div>
			<div id="tab-4">
				<h3><b><?php esc_html_e('Help Section','woo-minimum-acceptable-price') ?></b></h3>
				<p><b><?php esc_html_e('Getting started with bargain bot. ','woo-minimum-acceptable-price') ?></b></p>

				<!-- <p><?php esc_html_e('If you enable Bargaining Chatbot option a new "Minimum Acceptable Price" field will be added along with all product which will be your last bargain price. You can set the minimum price with the product to get the "Make Your Offer Now" button visible.','woo-minimum-acceptable-price') ?></p> -->

				<ol style="list-style: circle; width: 60%;">
					<li><p><?php esc_html_e('When you enable Bargain Bot a new field called "Minimum Acceptable Price" will be added under "Sale price" field in General tab of every Product Edit Page. You have to add your minimum acceptable price for each product on which you want to display the Make your offer now button.','woo-minimum-acceptable-price') ?></p>
						<img src="<?php echo esc_url(QCLD_MAP_EXTENDED_IMG_URL); ?>Screenshot_1.jpg" width="100%" alt="">
					</li>
					<li><p><?php esc_html_e('For variable product','woo-minimum-acceptable-price') ?></p>
						<img src="<?php echo esc_url(QCLD_MAP_EXTENDED_IMG_URL); ?>Screenshot_399.jpg" width="100%" alt="">
					</li>
					<li><p><?php esc_html_e('Product details page you will see a button called "Make Your Offer Now"','woo-minimum-acceptable-price') ?></p>
						<img src="<?php echo esc_url(QCLD_MAP_EXTENDED_IMG_URL); ?>Screenshot_2.jpg" width="100%" alt="">
					</li>
					<li><p><?php esc_html_e('When user clicks on the "Make Your Offer Now" button the Lightbox Modal will open and show the product image, title, regular price & sale price. Then user can submit their offer price.','woo-minimum-acceptable-price') ?></p>
						<img src="<?php echo esc_url(QCLD_MAP_EXTENDED_IMG_URL); ?>bargain-modal.jpg" width="100%" alt="">
					</li>
				</ol>
				<h4><b><?php esc_html_e('Language Change','woo-minimum-acceptable-price') ?></b></h4>
				<p><?php esc_html_e('You can customize language as your need. The default languages are fine for stores using the English language. But you can change the bargain bot responses literally into any language!','woo-minimum-acceptable-price') ?></p>
				<p></p>
				<br><br><br>
				<br><br><br>
			</div>
			
			<?php submit_button(); ?>

		</form>
		
	</div>

	<?php
	}
	
	public function qcld_bargain_woowbot_settings_page(){
		
	?>
	<div class="wrap swpm-admin-menu-wrap">
		<h3><?php esc_html_e('Install WoowBot - works with Bargain Bot','woo-minimum-acceptable-price') ?></h3>
	
		 <div class="row justify-content-center">
            <div class="col-6">
                <div class="woowbot-bot-wrapper">
                    <img src="<?php echo QCLD_MAP_EXTENDED_IMG_URL; ?>/woowbot.png" alt="ChatBot for Woocommerce">
                <h3><?php esc_html_e('WoowBot WooCommerce ChatBot', 'woochatbot'); ?></h3>
                <p><?php esc_html_e('WooWBot is a simple, plug n\' play ChatBot for WooCommerce with zero configuration or bot training required. This WooCommerce based Shop Bot that can help Increase your store Sales perceptibly.', 'woochatbot'); ?></p>
                <p><a href="<?php echo esc_url('https://wordpress.org/plugins/woowbot-woocommerce-chatbot/'); ?>" target="_blank"> <b> <?php esc_html_e('Download WoowBot Free', 'woochatbot'); ?></b></a> | <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"> <b> <?php esc_html_e('Download WoowBot Pro', 'woochatbot'); ?></b></a></p>
                
                </div>

            </div>
        </div>
	
		<div class="row justify-content-center">
            <div class="col-6">

                <h4 class="qcld_woowbot_title"><?php esc_html_e('Install WoowBot Free Now! It works with Bargain Bot', 'woochatbot'); ?></h4>

        		<?php  include_once 'qcld-recommendbot-plugin.php'; ?>

            </div>
        </div>
       
		
	</div>

	<?php
	}
	

	
}
new Qcld_Bargain_Admin_Area_Controller();



/*****************************************************
 * Plugin default data set when activation.
 *****************************************************/
register_activation_hook(__FILE__, 'qcld_bargain_minimum_accept_price_options');
if (!function_exists('qcld_bargain_minimum_accept_price_options')) {
	function qcld_bargain_minimum_accept_price_options(){
		
		if(!get_option('qcld_minimum_accept_price_enable')) {
	        update_option('qcld_minimum_accept_price_enable', 1);
	    }
		
		if(!get_option('qcld_minimum_accept_price_button_text')) {
	        update_option('qcld_minimum_accept_price_button_text', 'Make Your Offer Now');
	    }
		
		if(!get_option('qcld_minimum_accept_price_heading_text')) {
	        update_option('qcld_minimum_accept_price_heading_text', 'Make me an offer that I cannot refuse!');
	    }
		if(!get_option('qcld_minimum_accept_price_heading_text_again')) {
	        update_option('qcld_minimum_accept_price_heading_text_again', 'It seems like you have not provided any offer amount. Please give me a number!');
	    }
		
		if(!get_option('qcld_minimum_accept_price_low_alert_text')) {
	        update_option('qcld_minimum_accept_price_low_alert_text', "Your offered price {offer price} is too low for us. Can you do better?");
	    }
		
		if(!get_option('qcld_minimum_accept_price_low_alert_text_two')) {
	        update_option('qcld_minimum_accept_price_low_alert_text_two', "Your offered price {offer price} is too low for us.");
	    }
		
		if(!get_option('qcld_minimum_accept_price_too_low_alert_text')) {
	        update_option('qcld_minimum_accept_price_too_low_alert_text', "The best we can do for you is {minimum amount}. Do you accept?");
	    }
		
		if(!get_option('qcld_minimum_accept_price_acceptable_price')) {
	        update_option('qcld_minimum_accept_price_acceptable_price', "Your offered price {offer price} is acceptable.");
	    }
		if(!get_option('qcld_minimum_accept_price_acceptable_copoun_code')) {
	        update_option('qcld_minimum_accept_price_acceptable_copoun_code', "Should we finalize the deal and add the product to you cart so you can check out?");
	    }
		
		if(!get_option('qcld_minimum_accept_price_more_than_offer_price')) {
	        update_option('qcld_minimum_accept_price_more_than_offer_price', "Your offer {offer price} is more than I could take. Please purchase at our original cost.");
	    }
		
		
		if(!get_option('qcld_minimum_accept_price_talk_to_boss')) {
	        update_option('qcld_minimum_accept_price_talk_to_boss', "Please tell me your final price. I will talk to my boss.");
	    }
		
		if(!get_option('qcld_minimum_accept_price_get_email_address')) {
	        update_option('qcld_minimum_accept_price_get_email_address', "Please tell me your email address so i can get back to you.");
	    }

		if(!get_option('qcld_minimum_accept_price_thanks_test')) {
	        update_option('qcld_minimum_accept_price_thanks_test', "Thank you. We will get back to you ASAP!");
	    }

		if(!get_option('qcld_minimum_accept_price_negotiating_test')) {
	        update_option('qcld_minimum_accept_price_negotiating_test', "Negotiating your price...");
	    }

		if(!get_option('qcld_minimum_accept_congratulations_text')) {
	        update_option('qcld_minimum_accept_congratulations_text', "CONGRATULATIONS!");
	    }

		if(!get_option('qcld_minimum_accept_congratulations_added_to_cart_msg')) {
	        update_option('qcld_minimum_accept_congratulations_added_to_cart_msg', "Adding to cart");
	    }

		if(!get_option('qcld_minimum_accept_congratulations_added_to_cart')) {
	        update_option('qcld_minimum_accept_congratulations_added_to_cart', "Your product has been added to the cart.");
	    }

		if(!get_option('qcld_minimum_accept_modal_submit_button_text')) {
	        update_option('qcld_minimum_accept_modal_submit_button_text', "Submit");
	    }

		if(!get_option('qcld_minimum_accept_modal_checkout_now_button_text')) {
	        update_option('qcld_minimum_accept_modal_checkout_now_button_text', "Checkout Now");
	    }

		if(!get_option('qcld_minimum_accept_modal_yes_button_text')) {
	        update_option('qcld_minimum_accept_modal_yes_button_text', "Yes");
	    }

		if(!get_option('qcld_minimum_accept_modal_no_button_text')) {
	        update_option('qcld_minimum_accept_modal_no_button_text', "No");
	    }

		if(!get_option('qcld_minimum_accept_modal_or_button_text')) {
	        update_option('qcld_minimum_accept_modal_or_button_text', "OR");
	    }

		if(!get_option('qlcd_map_chatbot_admin_email')) {
			$admin_email = get_option('admin_email');
	        update_option('qlcd_map_chatbot_admin_email', $admin_email);
	    }

		if(!get_option('qlcd_map_chatbot_admin_email_subject')) {
	        update_option('qlcd_map_chatbot_admin_email_subject', 'Query details from WPWBot by Client');
	    }

	  
		
	}

}


if (!function_exists('qcld_minimum_accept_pro_notice')) {
//add_action( 'admin_notices', 'qcld_minimum_accept_pro_notice', 100 );
function qcld_minimum_accept_pro_notice(){

    global $pagenow, $typenow;
    $screen = get_current_screen();

    if(isset($screen->base) && ($screen->base == 'toplevel_page_wbpt-minimum-acceptable-price-page' || $screen->base == 'bargain-bot_page_wbpt-map-woowbot-supports' || $screen->base == 'bargain-bot_page_wbpt-minimum-acceptable-price-supports' )){
    ?>
    <div id="message-bargain" class="notice notice-info is-dismissible" style="padding:4px 0px 0px 4px;background:#C13825;">
        <?php
            printf(
                __('%s  %s  %s','woo-minimum-acceptable-price'),
                '<a href="'.esc_url('https://www.quantumcloud.com/products/bargain-bot/').'" target="_blank">',
                '<img src="'.esc_url(QCLD_MAP_EXTENDED_IMG_URL).'new-year-23.gif" >',
                '</a>'
            );
        ?>
    </div>
<?php
	}

}
}