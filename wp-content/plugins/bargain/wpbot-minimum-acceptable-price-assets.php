<?php

defined('ABSPATH') or die("No direct script access!");

//Loading frontend style & scripts based on the above condition.
if (!function_exists('qcld_bargain_load_front_scripts')){
function qcld_bargain_load_front_scripts(){

    //Styles
    wp_enqueue_style( 'qcld_bargain_animate_css', QCLD_MAP_PLUGIN_URL . 'css/wpbot-map-animate.css');

    if ( is_product() ){
        wp_enqueue_style( 'qcld_bargain_magnific_popup_css', QCLD_MAP_PLUGIN_URL . 'css/wpbot-map-magnific-popup.css');
        wp_enqueue_script( 'qcld_bargain_magnific_popup_js', QCLD_MAP_PLUGIN_URL . 'js/wpbot-jquery.magnific-popup.min.js', array('jquery'));
    }

    wp_enqueue_style( 'qcld_bargain_front_custom_css', QCLD_MAP_PLUGIN_URL . 'css/wpbot-minimum-acceptable-price-custom-front.css');

    //Scripts
    wp_enqueue_script( 'jquery', 'jquery');
    wp_enqueue_script( 'qcld_bargain_cookie_js', QCLD_MAP_PLUGIN_URL  . 'js/wpbot-jquery.cookie.js', array('jquery'));
    wp_enqueue_script( 'qcld_bargain_custom_js', QCLD_MAP_PLUGIN_URL . 'js/wpbot-minimum-acceptable-price-custom.js', array('jquery'));


    wp_add_inline_script( 'qcld_bargain_custom_js', 
        'var qcld_map_ajaxurl = "' . admin_url('admin-ajax.php') . '"; 
         var qcld_map_ajax_nonce = "'.wp_create_nonce( 'woo-minimum-acceptable-price' ).'";  
         var qcld_map_currency_symbol = "'.get_woocommerce_currency_symbol().'";  
         var qcld_map_your_offer_price = "'.esc_html(get_option('qcld_minimum_accept_price_heading_text')).'";  
         var qcld_map_your_low_price_alert = "'.esc_html(get_option('qcld_minimum_accept_price_low_alert_text_two')).'";  
         var qcld_map_your_too_low_price_alert = "'.esc_html(get_option('qcld_minimum_accept_price_too_low_alert_text')).'";  
         var qcld_map_talk_to_boss = "'.esc_html(get_option('qcld_minimum_accept_price_talk_to_boss')).'";  
         var qcld_map_get_email_address = "'.esc_html(get_option('qcld_minimum_accept_price_get_email_address')).'";  
         var qcld_map_thanks_test = "'.esc_html(get_option('qcld_minimum_accept_price_thanks_test')).'";  
         var map_acceptable_price = "'.esc_html(get_option('qcld_minimum_accept_price_acceptable_price')).'";  
         var qcld_map_price_negotiating_test = "'.esc_html(get_option('qcld_minimum_accept_price_negotiating_test')).'";  
         var qcld_map_modal_submit_button = "'.esc_html(get_option('qcld_minimum_accept_modal_submit_button_text')).'";  
         var qcld_map_pro_added_to_cart_msg = "'.esc_html(get_option('qcld_minimum_accept_congratulations_added_to_cart_msg')).'";  
         var qcld_map_pro_checkout_now_button_text = "'.esc_html(get_option('qcld_minimum_accept_modal_checkout_now_button_text')).'";  
         var qcld_map_pro_get_checkout_url = "'.wc_get_checkout_url().'";  
         var qcld_map_pro_get_is_product = "'.is_product().'";  
         var qcld_map_pro_get_ajax_nonce = "'.wp_create_nonce( 'woo-minimum-acceptable-price').'";  
         var qcld_minimum_accept_modal_yes_button_text = "'.esc_html(get_option('qcld_minimum_accept_modal_yes_button_text')).'";  
         var qcld_minimum_accept_modal_no_button_text = "'.esc_html(get_option('qcld_minimum_accept_modal_no_button_text')).'";  
         var qcld_minimum_accept_modal_or_button_text = "'.esc_html(get_option('qcld_minimum_accept_modal_or_button_text')).'"; 
         ', 'before' );



}
}
add_action('wp_enqueue_scripts', 'qcld_bargain_load_front_scripts');


add_action('admin_enqueue_scripts','qcld_bargain_load_admin_scripts');
if (!function_exists('qcld_bargain_load_admin_scripts')){
    function qcld_bargain_load_admin_scripts() {

        $screen = get_current_screen();

        // var_dump( $screen->base ); bargain-bot_page_wbpt-map-woowbot-supports
        // wp_die();

        if ( ( isset( $screen->base ) && $screen->base == 'toplevel_page_wbpt-minimum-acceptable-price-page' ) ||  
            ( isset( $screen->base ) && $screen->base == 'bargain-bot_page_wbpt-map-woowbot-supports' )  ) {

            wp_enqueue_script('qcld-wp-bargainator-custom-js', QCLD_MAP_PLUGIN_URL . '/js/wpbot-admin-custom.js', array('jquery', 'jquery-ui-tabs') );
            wp_enqueue_style('qcld-wp-bargainator-admin-css', QCLD_MAP_PLUGIN_URL . '/css/wpbot-map-admin.css');

        
        }

        if( isset( $screen->base ) && $screen->base == 'bargain-bot_page_wbpt-map-woowbot-supports' ){
            wp_enqueue_script('qcld-wp-bargainator-bootstrap-js', QCLD_MAP_PLUGIN_URL . '/js/bootstrap.js', array('jquery') );
            wp_enqueue_style('qcld-woo-chatbot-bootstrap-css', QCLD_MAP_PLUGIN_URL . '/css/bootstrap.min.css');
        }

    }

}