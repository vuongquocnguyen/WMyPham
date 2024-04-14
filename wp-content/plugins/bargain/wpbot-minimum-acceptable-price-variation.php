<?php 

defined('ABSPATH') or die("No direct script access!");


if(get_option('qcld_minimum_accept_price_enable') == '1'){

if (!function_exists('qcld_woo_map_bargin_add_custom_field_to_variations')){
	 
	function qcld_woo_map_bargin_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
		woocommerce_wp_text_input( array(
			'id' => 'qcld_minimum_acceptable_price_variation_[' . $loop . ']',
			'class' => 'qcld_minimum_acceptable_price_variations',
			'label' => __( 'Bargain Bot Minimum Acceptable Price:', 'woo-minimum-acceptable-price' ),
			'value' => get_post_meta( $variation->ID, 'qcld_minimum_acceptable_price_variation', true )
			)
		);


		woocommerce_wp_select( 
            array( 
                'id'          => 'qcld_minimum_acceptable_multi_currency_variable_[' . $loop . ']', 
                'label'       => __( 'Type', 'woo-minimum-acceptable-price' ), 
                'description' => __( ' ( If you are using a multi currency plugin, please use Percentage from the drop down for multi currency support. ) ', 'woo-minimum-acceptable-price' ),
                'value'       => get_post_meta( $variation->ID, 'qcld_minimum_acceptable_multi_currency_variable', true ),
                'options'     => array(
                        'simple'   => __( 'Fixed Amount', 'woocommerce' ),
                        'multiple' => __( 'Percentage', 'woocommerce' )
                    )
                )
            );


	}
	add_action( 'woocommerce_variation_options_pricing', 'qcld_woo_map_bargin_add_custom_field_to_variations', 10, 3 );
}
 
// -----------------------------------------
// 2. Save custom field on product variation save
 
if (!function_exists('qcld_woo_map_bargin_save_custom_field_variations')){
	function qcld_woo_map_bargin_save_custom_field_variations( $variation_id, $i ) {
		$multi_currency_variable = $_POST['qcld_minimum_acceptable_multi_currency_variable_'][$i];
		if ( isset( $multi_currency_variable ) ) update_post_meta( $variation_id, 'qcld_minimum_acceptable_multi_currency_variable', esc_attr( $multi_currency_variable ) );
		$custom_field = sanitize_text_field($_POST['qcld_minimum_acceptable_price_variation_'][$i]);
		if ( isset( $custom_field ) ) update_post_meta( $variation_id, 'qcld_minimum_acceptable_price_variation', esc_attr( $custom_field ) );
	}
	add_action( 'woocommerce_save_product_variation', 'qcld_woo_map_bargin_save_custom_field_variations', 10, 2 );
}
// -----------------------------------------
// 3. Store custom field value into variation data
 
if (!function_exists('qcld_woo_map_bargin_add_custom_field_variation_data')){
	function qcld_woo_map_bargin_add_custom_field_variation_data( $variations ) {
		$variations['qcld_minimum_acceptable_price_variation'] = '<div class="woocommerce_custom_field">Minimum Acceptable Price: <span>' . get_post_meta( $variations[ 'variation_id' ], 'qcld_minimum_acceptable_price_variation', true ) . '</span></div>';
		return $variations;
	}
	add_filter( 'woocommerce_available_variation', 'qcld_woo_map_bargin_add_custom_field_variation_data' );
}

}



//Get bargin products
add_action('wp_ajax_qcld_woo_bargin_product_variation', 'qcld_woo_map_bargin_product_variation');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product_variation', 'qcld_woo_map_bargin_product_variation');
if (!function_exists('qcld_woo_map_bargin_product_variation')){
function qcld_woo_map_bargin_product_variation(){

    check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

    $product_id     = trim(sanitize_text_field($_POST['qcld_woo_map_product_id']));
    $variation_id   = trim(sanitize_text_field($_POST['qcld_woo_map_variation_id']));
    $product        = wc_get_product( $variation_id ); // product_id
    

    $qcld_minimum_accept_price_button_text =  get_option('qcld_minimum_accept_price_button_text');
    $qcld_minimum_accept_price_heading_text =  get_option('qcld_minimum_accept_price_heading_text');
    $qcld_minimum_accept_modal_submit_button_text =  get_option('qcld_minimum_accept_modal_submit_button_text');

    $html = '';
    
    if(!empty($product_id)){
    $html .= '<div id="wpbot-map-product-modal" class="mfp-hide white-popup-block animated flipInX"><div id="wpbot-map-product">';

    $html .= '<div class="woo-chatbot-products-bargain-modal">';

        $html .= '<h5>'.esc_html($qcld_minimum_accept_price_button_text).'</h5>';

        
        $image = get_the_post_thumbnail($product_id, 'shop_catalog');
        if(empty($image)){
            $image = woocommerce_placeholder_img( 'shop_catalog' );
        }
        // product details
        $html .= '<div class="woo-chatbot-product-modal">';
            $html .= '<div class="woo-chatbot-product-modal-img">';
            $html .= $image . '</div>
            <div class="woo-chatbot-product-bargain-modal">
            <h3 class="woo-chatbot-product-title-modal">' . $product->get_formatted_name() . '</h3></div>
            <div class="woo-chatbot-product-price-modal">' . $product->get_price_html() . '</div>';
            
        $html .= '</div>';
        // bargin section
        $html .= '<div class="woo-chatbot-products-bargain-wrap">';
        $html .= '<div class="woo-chatbot-products-bargain-content">';
        $html .= '<h4>'.esc_html($qcld_minimum_accept_price_heading_text).'</h4>';
        $html .= '</div>';
        $html .= '<div  class="woo-chatbot-products-bargain-info">';
        $html .= '<input type="number" name="woo-chatbot-product-bargain-price" class="woo-chatbot-product-bargain-price" placeholder="'.esc_attr(get_woocommerce_currency_symbol()).'">';
        $html .= '<div class="woo-chatbot-product-bargain-msg"></div>';
        $html .= '<button type="button" class="woo-chatbot-product-bargain-variation-submit" product-id="'.esc_attr($product_id).'" variation-id="'.esc_attr($variation_id).'">'.esc_html($qcld_minimum_accept_modal_submit_button_text).' </button>';
        $html .= '</div>';
        $html .= '</div>';
    
    $html .= '</div>';
    $html .= '</div></div>';

    }else{

        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p>' . esc_html_e('You have no products', 'woo-minimum-acceptable-price') . ' !';
        $html .= '</div>';
    }
   
    $response = array('html' => $html);
    echo wp_send_json($response);
    //return $html;
    wp_die();

}
}



//Get bargin products
add_action('wp_ajax_qcld_woo_bargin_product_modal_variation_price', 'qcld_woo_bargin_product_modal_variation_price');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product_modal_variation_price', 'qcld_woo_bargin_product_modal_variation_price');
if (!function_exists('qcld_woo_bargin_product_modal_variation_price')){
function qcld_woo_bargin_product_modal_variation_price(){

    check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

    $product_id     = trim(sanitize_text_field($_POST['qcld_woo_map_product_id']));
    $variation_id     = trim(sanitize_text_field($_POST['qcld_woo_map_variation_id']));
    $customer_price = trim(sanitize_text_field($_POST['price']));
    $product        = wc_get_product( $variation_id ); // product_id
    $actual_price   = $product->get_price();

    $html ='';


	$minimum_price  = get_post_meta( $variation_id, 'qcld_minimum_acceptable_price_variation', true );
	$multi_currency  = get_post_meta( $variation_id, 'qcld_minimum_acceptable_multi_currency_variable', true );
    if(empty(!$minimum_price) && ($multi_currency == 'simple')){

    	$minimum_price = $minimum_price;
        
    }elseif(!empty($minimum_price) && ($multi_currency == 'multiple')){

        $discount_percent_price = $minimum_price;
        $minimum_price = ( $actual_price * $discount_percent_price /100 );

    }else{

        $discount_percent_price =  get_option('qcld_minimum_accept_product_discount_price');
        $minimum_price = ( $actual_price * $discount_percent_price /100 );
    }


    $amount         = $actual_price - $minimum_price; // Amount

    $qcld_map_currency_symbol = get_woocommerce_currency_symbol(); 

    $checking_min_price = ($actual_price * 2/3);

    $confirm = '';

    $sale_price = $minimum_price;
 
    if(($customer_price < $minimum_price)){

        $price_low_text =  get_option('qcld_minimum_accept_price_low_alert_text');

        $price_low_text_msg = str_replace("{offer price}", $customer_price . $qcld_map_currency_symbol , $price_low_text);

        $html .= '<p>' . esc_html($price_low_text_msg) .'</p>';
        
        $confirm = 'success';
        $sale_price = $minimum_price;

    }else if($customer_price >= $actual_price){

        $more_offer_price_text =  get_option('qcld_minimum_accept_price_more_than_offer_price');

        $more_offer_price_text_msg = str_replace("{offer price}", $customer_price. $qcld_map_currency_symbol , $more_offer_price_text);

        $html .= '<p>' . esc_html($more_offer_price_text_msg) . ' !';

        $confirm = 'default';
        $sale_price = $actual_price; 

    }else if(($customer_price >= $minimum_price) && ($customer_price <  $actual_price)){

        $acceptable_price_text   =   get_option('qcld_minimum_accept_price_acceptable_price');
        $acceptable_price_msg    =   str_replace("{offer price}", $customer_price. $qcld_map_currency_symbol , $acceptable_price_text);


        $coupon_code_text   =   get_option('qcld_minimum_accept_price_acceptable_copoun_code');
        $coupon_code_msg    =   $coupon_code_text;

        $qcld_minimum_accept_congratulations_text = get_option('qcld_minimum_accept_congratulations_text');


        $html .= '<div class="woo-chatbot-product-bargain-msg-success">';
        $html .= '<h4>'.esc_html($qcld_minimum_accept_congratulations_text).'</h4>';
        $html .= '<p>' . esc_html($acceptable_price_msg) .'</p>';
        $html .= '<p>' . esc_html($coupon_code_msg) .'</p>';
        $html .= '</div>';

        $confirm = 'agree';
        $sale_price = $customer_price;

    }
   
    $response       = array('html' => $html, 'confirm' => $confirm, 'sale_price' => $sale_price );

    echo wp_send_json($response);
    wp_die();

}
}


add_action('wp_ajax_qcld_woo_bargin_product_variation_add_to_cart', 'qcld_woo_bargin_product_variation_add_to_cart');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product_variation_add_to_cart', 'qcld_woo_bargin_product_variation_add_to_cart');
if (!function_exists('qcld_woo_bargin_product_variation_add_to_cart')){
    function qcld_woo_bargin_product_variation_add_to_cart(){

        check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

        ob_start();

        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( sanitize_text_field($_POST['product_id']) ) );
        $quantity           = isset( $_POST['product_qty'] ) ? sanitize_text_field( $_POST['product_qty'] ) : '';
        $quantity           = apply_filters( 'woocommerce_stock_amount', $quantity )  ? apply_filters( 'woocommerce_stock_amount', $quantity ) : 1;
        $variation_id       = isset( $_POST['variation_id'] ) ? trim(sanitize_text_field($_POST['variation_id'])) : '';
        $variation          = wc_get_product_variation_attributes($variation_id);

        //$customer_price =  (float) $_POST['price'];
        $customer_price     = isset( $_POST['price'] ) ? round(trim(sanitize_text_field($_POST['price'])), 2) : '';

        $product            = wc_get_product( $variation_id );
        

        $minimum_price  = get_post_meta( $variation_id, 'qcld_minimum_acceptable_price_variation', true );
        $multi_currency  = get_post_meta( $variation_id, 'qcld_minimum_acceptable_multi_currency_variable', true );

        $qcld_random_discount_enable = get_option('qcld_minimum_accept_random_discount_for_all_enable');
        $qcld_random_min_discount_price = get_option('qcld_minimum_accept_random_min_discount_price');
        $qcld_random_max_discount_price = get_option('qcld_minimum_accept_random_max_discount_price');

        if(empty(!$minimum_price) && ($multi_currency == 'simple')){

            $actual_price   = $product->get_price();
            $customer_price = round(trim(sanitize_text_field($_POST['price'])), 2);
            
        }elseif(!empty($minimum_price) && ( $customer_price > $minimum_price ) && ($multi_currency == 'multiple')){


            $actual_price   = $product->get_price();
            $customer_price = round(trim(sanitize_text_field($_POST['price'])), 2);
            

        }elseif(!empty($minimum_price) && ($multi_currency == 'multiple')){

            $actual_price   = (float) get_post_meta( $variation_id, '_sale_price', true) ? get_post_meta( $variation_id, '_sale_price', true) : get_post_meta( $variation_id, '_regular_price', true);

            $discount_percent_price = $minimum_price;
            $customer_price = ( $actual_price * $discount_percent_price /100 );

        }elseif(!empty($qcld_random_discount_enable) && !empty($qcld_random_min_discount_price) && !empty($qcld_random_max_discount_price) ){

            $discount_percent_price = rand($qcld_random_min_discount_price, $qcld_random_max_discount_price );

            $actual_price   = $product->get_price();
            $customer_price = ( $actual_price * $discount_percent_price / 100 );

        }else{
            
            $actual_price   = $product->get_price();
            $discount_percent_price =  get_option('qcld_minimum_accept_product_discount_price');
            $customer_price = ( $actual_price * $discount_percent_price /100 );
        }

        $product_cookie = isset( $_POST['product_cookie'] ) ? sanitize_text_field( $_POST['product_cookie'] ) : '';
        if($product_cookie == 'yes'){
            $customer_price =  (float) $_POST['price']; 
        }

        $coupon_amount  = (float) $actual_price - (float) $customer_price;

        WC()->session->set( 'qcld_map_coupon_name', $coupon_amount );
        WC()->session->set( 'qcld_map_coupon_product_id', $product_id );
        WC()->session->set( 'qcld_map_coupon_variation_id', $variation_id );

        $enable_variable_validation = get_option('qcld_minimum_accept_price_enable_variable_validation');

        $passed_validation  = ($enable_variable_validation == 1 ) ? true : apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

        if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation  ) ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
                wc_add_to_cart_message( $product_id );
            }

        } else {
            
            $html = '';
            $html .= '<p>'.esc_html('Please check product quantity and other details.').'</p>';

            $response = array('html' => $html );

            echo wp_send_json($response);

            wp_die();
        }

        $qcld_minimum_accept_congratulations_text = get_option('qcld_minimum_accept_congratulations_text');
        $qcld_minimum_accept_congratulations_added_to_cart = get_option('qcld_minimum_accept_congratulations_added_to_cart');
        $qcld_minimum_accept_modal_checkout_now_button_text = get_option('qcld_minimum_accept_modal_checkout_now_button_text');
      
        $html = '<h4>'.esc_html($qcld_minimum_accept_congratulations_text).'</h4>';
        $html .= '<p>'.esc_html($qcld_minimum_accept_congratulations_added_to_cart).'</p>';
   
        $response = array('html' => $html );

        echo wp_send_json($response);

        wp_die();


    }

}



// Calculate and save as custom cart item data the discounted price
add_filter('woocommerce_add_cart_item_data', 'qcld_woo_bargin_product_add_custom_cart_item_data', 20, 3);

if (!function_exists('qcld_woo_bargin_product_add_custom_cart_item_data')){

    function qcld_woo_bargin_product_add_custom_cart_item_data($cart_item_data, $product_id, $variation_id) {

     
        if( !WC()->session->get('qcld_map_coupon_name') ) return $cart_item_data;

        $coupon_amount                  = (float) WC()->session->get( 'qcld_map_coupon_name' );
        $qcld_map_coupon_product_id     = WC()->session->get('qcld_map_coupon_product_id');
        $qcld_map_coupon_variation_id   = WC()->session->get('qcld_map_coupon_variation_id');
        $qcld_map_coupon_quantity       = WC()->session->get('qcld_map_coupon_quantity') ? WC()->session->get('qcld_map_coupon_quantity') : 1;

        if($qcld_map_coupon_variation_id){

            $_product_id = $qcld_map_coupon_variation_id;

            $product = wc_get_product($_product_id);
            //$base_price = (float) $product->get_price();

            $minimum_price  = get_post_meta( $_product_id, 'qcld_minimum_acceptable_price_variation', true );
            $multi_currency  = get_post_meta( $_product_id, 'qcld_minimum_acceptable_multi_currency_variable', true );
            if(!empty($minimum_price) && ($multi_currency == 'multiple')){

                $base_price = (float) get_post_meta( $_product_id, '_sale_price', true) ? get_post_meta( $_product_id, '_sale_price', true) : get_post_meta( $_product_id, '_regular_price', true);

            }else{
                $base_price = (float) $product->get_price();
            }



            // Save the calculated discounted price as custom cart item data
            if( !empty($coupon_amount) && ( $product_id == $qcld_map_coupon_product_id ) && ( $variation_id == $qcld_map_coupon_variation_id )  ) { 
                $cart_item_data['discounted_price'] = $base_price - $coupon_amount;
                WC()->session->set( 'qcld_map_coupon_name', '' );
                WC()->session->set( 'qcld_map_coupon_product_id', '' );
                WC()->session->set( 'qcld_map_coupon_variation_id', '' );
            }

        }else{

            $_product_id = $qcld_map_coupon_product_id;

            $product = wc_get_product($_product_id);

            $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
            $multi_currency  = $product->get_meta( 'qcld_minimum_acceptable_multi_currency' );
            if(!empty($minimum_price) && ($multi_currency == 'multiple')){
                $base_price = (float) get_post_meta( $_product_id, '_sale_price', true) ? get_post_meta( $_product_id, '_sale_price', true) : get_post_meta( $_product_id, '_regular_price', true);

            }else{
                $base_price = (float) $product->get_price();
            }



            // Save the calculated discounted price as custom cart item data
            if( !empty($coupon_amount) && ( $product_id == $qcld_map_coupon_product_id ) ) { 

                $cart_item_data['discounted_price'] = $base_price - $coupon_amount;
                WC()->session->set( 'qcld_map_coupon_name', '' );
                WC()->session->set( 'qcld_map_coupon_product_id', '' );
            }

        }

        return $cart_item_data;
    }
}

// Set the discounted price 
add_action('woocommerce_before_calculate_totals', 'qcld_woo_bargin_product_add_discount', 20, 1);
if (!function_exists('qcld_woo_bargin_product_add_discount')){
	function qcld_woo_bargin_product_add_discount($cart) {
	    if ( is_admin() && !defined('DOING_AJAX') ) return;

	    // Loop through cart items
	    foreach($cart->get_cart() as $cart_item) {

	        if( isset($cart_item['discounted_price']) ) {  
	        	$cart_item['data']->set_price($cart_item['discounted_price']);
	        }

	    }
	}
}