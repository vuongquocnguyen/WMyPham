<?php 

defined('ABSPATH') or die("No direct script access!");


if(get_option('qcld_minimum_accept_price_enable') == '1'){

/******************************************************
 * The minimum acceptable price when product price insert or update
 ******************************************************/
if (!function_exists('qcld_minimum_accept_price_create_custom_field')) {

	function qcld_minimum_accept_price_create_custom_field() {

	 	$args = array(
	 		'id' 			=> 'qcld_minimum_acceptable_price',
	 		'label' 		=> __( 'Bargain Bot Minimum Acceptable Price', 'woo-minimum-acceptable-price' ),
	 		'class' 		=> 'woo-minimum-acceptable-price-custom-field wc_input_price',
	 		'desc_tip' 		=> true,
	 		'description' 	=> __( ' ( Enter the minimum acceptable price to make your offer now ) ', 'woo-minimum-acceptable-price' ),
	 	);
	 	woocommerce_wp_text_input( $args );

	}
	add_action( 'woocommerce_product_options_general_product_data', 'qcld_minimum_accept_price_create_custom_field' );

}

if (!function_exists('qcld_minimum_accept_price_create_custom_fields')) {

    function qcld_minimum_accept_price_create_custom_fields() {

         global $post, $thepostid, $product;
         $thepostid = $post->ID;
         $product = wc_get_product($thepostid);

         $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_multi_currency' ) ? $product->get_meta( 'qcld_minimum_acceptable_multi_currency' ) : '';

        woocommerce_wp_select( 
            array( 
                'id'          => 'qcld_minimum_acceptable_multi_currency', 
                'label'       => __( 'Type', 'woocommerce' ), 
                'description' => __( ' ( If you are using a multi currency plugin, please use Percentage from the drop down for multi currency support. ) ', 'woo-minimum-acceptable-price' ),
                'value'       => $minimum_price,
                'options'     => array(
                        'simple'   => __( 'Fixed Amount', 'woocommerce' ),
                        'multiple' => __( 'Percentage', 'woocommerce' )
                    )
                )
            );

    }
    add_action( 'woocommerce_product_options_general_product_data', 'qcld_minimum_accept_price_create_custom_fields' );

}


/******************************************************
 * save the minimum acceptable price
 ******************************************************/
if (!function_exists('qcld_minimum_accept_price_save_custom_field')) {

	function qcld_minimum_accept_price_save_custom_field( $post_id ) {

	 	$product 	= wc_get_product( $post_id );
	 	$title 		= isset( $_POST['qcld_minimum_acceptable_price'] ) ? sanitize_text_field($_POST['qcld_minimum_acceptable_price']) : '';
	 	$product->update_meta_data( 'qcld_minimum_acceptable_price', sanitize_text_field( $title ) );
        $multi_currency = isset( $_POST['qcld_minimum_acceptable_multi_currency'] ) ? $_POST['qcld_minimum_acceptable_multi_currency'] : '';
        $product->update_meta_data( 'qcld_minimum_acceptable_multi_currency', sanitize_text_field( $multi_currency ) );
	 	$product->save();

	}
	add_action( 'woocommerce_process_product_meta', 'qcld_minimum_accept_price_save_custom_field' );

}



/*********************************************
 * Display make your offer now button  on the front end
 *********************************************/
if (!function_exists('qcld_minimum_accept_price_display_button_field')){

	function qcld_minimum_accept_price_display_button_field(){
	 	global $post;

	 	// Check for the minimum price value
	 	$product 		= wc_get_product( $post->ID );


	 	$price 			= $product->get_meta( 'qcld_minimum_acceptable_price' );
        $button_text    = get_option('qcld_minimum_accept_price_button_text');
        $product_id     = $post->ID;

        $html = '';
	

            if ( !is_plugin_active( 'wpbot-pro/qcld-wpwbot.php' ) && !is_plugin_active( 'woowbot-woocommerce-chatbot-pro/qcld-woowbot.php' ) && !is_plugin_active( 'wpbot/qcld-wpwbot.php' ) && !is_plugin_active( 'woowbot-woocommerce-chatbot/qcld-woowbot.php' )) {

                if( $product->is_type( 'variable' ) && (qcld_map_is_check_variations_minimu_price($product_id) == true ) ){

                   

                    $html =  '<div class="woo_minimum_accept_price-field-wrapper"><button type="button" id="woo_minimum_accept_price-field" class="woo_minimum_accept_price-bargin-varialbe-modal" product_id="'. esc_attr( $product_id ).'">'.esc_html( $button_text ).'</button></div>';

                    
                }else{

                    if( $price ) {

                        $html =  '<div class="woo_minimum_accept_price-field-wrapper"><button type="button" id="woo_minimum_accept_price-field" class="woo_minimum_accept_price-bargin-modal" product_id="'. esc_attr( $product_id ).'">'.esc_html( $button_text ).'</button></div>';

                        $html .= qcld_woo_bargin_product_modal($product_id);

                    }
                }

                echo $html;


            }else{

                $qcld_minimum_accept_product_lightbox_modal    = get_option('qcld_minimum_accept_product_lightbox_modal');

                if(!empty($qcld_minimum_accept_product_lightbox_modal)){

                    if( $product->is_type( 'variable' ) && (qcld_map_is_check_variations_minimu_price($product_id) == true ) ){

                        $html =  '<div class="woo_minimum_accept_price-field-wrapper"><button type="button" id="woo_minimum_accept_price-field" class="woo_minimum_accept_price-bargin-varialbe-modal" product_id="'. esc_attr( $product_id ).'">'.esc_html( $button_text ).'</button></div>';
                        if (function_exists('qcld_minimum_accept_price_exit_intent_function')){
                            $html .= qcld_minimum_accept_price_exit_intent_function($product_id);
                        }

                        
                    }else{

                        if( $price ) {

                            $html =  '<div class="woo_minimum_accept_price-field-wrapper"><button type="button" id="woo_minimum_accept_price-field" class="woo_minimum_accept_price-bargin-modal" product_id="'. esc_attr( $product_id ).'">'.esc_html( $button_text ).'</button></div>';
                            $html .= qcld_woo_bargin_product_modal($product_id);

                        }
                    }

                    echo $html;

                }else{

                    if( $price ) {

                        printf('<div class="woo_minimum_accept_price-field-wrapper"><button type="button" id="woo_minimum_accept_price-field" class="woo_minimum_accept_price-bargin" product_id="%s">%s</button></div>', esc_attr( $product_id ), esc_html( $button_text )
                        );

                    }elseif(  $product->is_type( 'variable' ) && ((qcld_map_is_check_variations_minimu_price($product_id) == true )   ) ){

                        $html =  '<div class="woo_minimum_accept_price-field-wrapper"><button type="button" id="woo_minimum_accept_price-field" class="woo_minimum_accept_price-bargin" product_id="'. esc_attr( $product_id ).'">'.esc_html( $button_text ).'</button></div>';

                        echo $html;
                        
                    }

                }

            }




	}
	add_action( 'woocommerce_after_add_to_cart_button', 'qcld_minimum_accept_price_display_button_field' );

}

// check variable minimum acceptable price ....
if (!function_exists('qcld_map_is_check_variations_minimu_price')){
    function qcld_map_is_check_variations_minimu_price($product_id){

        $product   = wc_get_product( $product_id );

        foreach ($product->get_available_variations() as $variation) {

            $minimum_price_check  = get_post_meta( $variation['variation_id'], 'qcld_minimum_acceptable_price_variation', true );

            if(!empty($minimum_price_check)){
                return true;
            }

            return false;

        }


    }
}


/****************************************************
 * bargin product ajax
 *****************************************************/

//Get bargin products
add_action('wp_ajax_qcld_woo_bargin_product', 'qcld_woo_map_bargin_product');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product', 'qcld_woo_map_bargin_product');
if (!function_exists('qcld_woo_map_bargin_product')){
function qcld_woo_map_bargin_product(){

    check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

    $product_id     = trim(sanitize_text_field($_POST['qcld_woo_map_product_id']));
    $variation_id   = trim(sanitize_text_field($_POST['qcld_woo_map_variation_id']));

    if($variation_id){
        $product  = wc_get_product( $variation_id ); // variation_id
    }else{
        $product  = wc_get_product( $product_id ); // product_id get_formatted_name
    }

    $html = '';
    
    if(!empty($product_id)){
    $html .= '<div class="woo-chatbot-products-bargain">';
        $html .= '<ul class="woo-chatbot-products">';
      
            $html .= '<li class="woo-chatbot-products">';
            $html .= '<a class="woo-chatbot-product-url" woo-chatbot-pid= "' . esc_attr($product_id) . '" target="_blank" href="' . get_permalink($product_id) . '" title="' . esc_attr($product->get_title() ? $product->get_title() : $product_id) . '">';

            
            $image = get_the_post_thumbnail($product_id, 'shop_catalog');
            if(empty($image)){
                $image = woocommerce_placeholder_img( 'shop_catalog' );
            }

            $html .= $image . '
            <div class="woo-chatbot-product-bargain">
            <div class="woo-chatbot-product-table">
            <div class="woo-chatbot-product-table-cell">';

            if($variation_id){
                $html .= '<h3 class="woo-chatbot-product-title">' . $product->get_formatted_name() . '</h3>';
            }else{
                $html .= '<h3 class="woo-chatbot-product-title">' . esc_html($product->get_title()) . '</h3>';
            }

            $html .= '<div class="price">' . $product->get_price_html() . '</div>';
            $html .= ' </div>
            </div>
            </div></a>
            </li>';
            
        wp_reset_postdata();
        $html .= '</ul>';
    
    $html .= '</div>';

    }else{

        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p>' . esc_html_e('You have no products', 'woo-minimum-acceptable-price') . ' !';
        $html .= '</div>';
    }
   
    $response = array('html' => $html);
    echo wp_send_json($response);
    wp_die();

}
}



//Get bargin products
add_action('wp_ajax_qcld_woo_bargin_product_price', 'qcld_woo_bargin_product_price');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product_price', 'qcld_woo_bargin_product_price');
if (!function_exists('qcld_woo_bargin_product_price')){
function qcld_woo_bargin_product_price(){

    check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

    $product_id     = trim(sanitize_text_field($_POST['qcld_woo_map_product_id']));
    $variation_id   = trim(sanitize_text_field($_POST['qcld_woo_map_variation_id']));
    $customer_price = trim(sanitize_text_field($_POST['price']));

    if(!empty($variation_id)){

        $product        = wc_get_product( $variation_id ); // product_id
        $actual_price   = $product->get_price();

        $minimum_price  = get_post_meta( $variation_id, 'qcld_minimum_acceptable_price_variation', true );
        $multi_currency  = get_post_meta( $variation_id, 'qcld_minimum_acceptable_multi_currency_variable', true );
        if(empty(!$minimum_price) && ($multi_currency == 'simple')){

            $minimum_price = $minimum_price;
            
        }elseif(!empty($minimum_price) && ($multi_currency == 'multiple')){

            $discount_percent_price = $minimum_price;
            $minimum_price = ( $actual_price * $discount_percent_price /100 );

        }else{

            $minimum_price = $minimum_price;
        }



    }else{

        $product        = wc_get_product( $product_id ); // product_id
        $actual_price   = $product->get_price();
        $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
    
        $minimum_price_check  = $product->get_meta( 'qcld_minimum_acceptable_price' );
        $multi_currency  = $product->get_meta( 'qcld_minimum_acceptable_multi_currency' );
        if(!empty($minimum_price_check) && ($multi_currency == 'simple')){
            $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
        }elseif(!empty($minimum_price_check) && ($multi_currency == 'multiple')){
            $discount_percent_price =  $minimum_price_check;
            $minimum_price = ( $actual_price * $discount_percent_price /100 );

        }else{
           $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
        }


    }



    $amount         = $actual_price - $minimum_price; // Amount

    $qcld_map_currency_symbol = get_woocommerce_currency_symbol(); 

    $checking_min_price = ($actual_price * 2/3);

    $confirm = '';
    $html = '';

    $sale_price = $minimum_price;
 
    if(($customer_price < $minimum_price)){

        $price_low_text =  get_option('qcld_minimum_accept_price_low_alert_text');

       	$price_low_text_msg = str_replace("{offer price}", $customer_price . $qcld_map_currency_symbol , $price_low_text);

        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p>' . esc_html($price_low_text_msg) .'</p>';
        $html .= '</div>';
        $confirm = 'success';
        $sale_price = $minimum_price;

    }else if($customer_price >= $actual_price){

       	$more_offer_price_text =  get_option('qcld_minimum_accept_price_more_than_offer_price');

       	$more_offer_price_text_msg = str_replace("{offer price}", $customer_price. $qcld_map_currency_symbol , $more_offer_price_text);

        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p>' . esc_html($more_offer_price_text_msg) . ' !';
        $html .= '</div>';
        $confirm = 'default';
        $sale_price = $actual_price; 

    }else if(($customer_price >= $minimum_price) && ($customer_price <  $actual_price)){

       $acceptable_price_text =  get_option('qcld_minimum_accept_price_acceptable_price');

       $acceptable_price_msg = str_replace("{offer price}", $customer_price. $qcld_map_currency_symbol , $acceptable_price_text);

        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p>' . esc_html($acceptable_price_msg) .'</p>';
        $html .= '</div>';
        $confirm = 'agree';
        $sale_price = $customer_price;

    }
   
    $response   = array('html' => $html, 'confirm' => $confirm, 'sale_price' => $sale_price );

    echo wp_send_json($response);
    wp_die();

}
}
//Get bargin products
add_action('wp_ajax_qcld_woo_bargin_product_confirm', 'qcld_woo_bargin_product_confirm');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product_confirm', 'qcld_woo_bargin_product_confirm');
if (!function_exists('qcld_woo_bargin_product_confirm')){
function qcld_woo_bargin_product_confirm(){

    check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

    $product_id     = trim(sanitize_text_field($_POST['qcld_woo_map_product_id']));
    $customer_price = trim(sanitize_text_field($_POST['price']));

    $product        = wc_get_product( $product_id );
    $actual_price   = $product->get_price();
    $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
    
    $minimum_price_check  = $product->get_meta( 'qcld_minimum_acceptable_price' );
    $multi_currency  = $product->get_meta( 'qcld_minimum_acceptable_multi_currency' );
    if(!empty($minimum_price_check) && ($multi_currency == 'simple')){
        $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
    }elseif(!empty($minimum_price_check) && ($multi_currency == 'multiple')){
        $discount_percent_price =  $minimum_price_check;
        $minimum_price = ( $actual_price * $discount_percent_price /100 );

    }else{
       $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
    }


    // $amount         = $actual_price - $minimum_price; // Amount
    $coupon_amount  = $actual_price - $customer_price; //  discount price from sale price

    $confirm = '';
    $html = '';


       	$coupon_code_text =  get_option('qcld_minimum_accept_price_acceptable_copoun_code');

       	$coupon_code_msg = $coupon_code_text;

        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p>' .  esc_html($coupon_code_msg) .'</p>';
        $html .= '</div>';
   
    	$response   = array('html' => $html, 'confirm' => $confirm );

    	echo wp_send_json($response);
    	wp_die();

}
}

add_action('wp_ajax_qcld_woo_bargin_send_query', 'qcld_woo_bargin_send_query');
add_action('wp_ajax_nopriv_qcld_woo_bargin_send_query', 'qcld_woo_bargin_send_query');
if (!function_exists('qcld_woo_bargin_send_query')){
function qcld_woo_bargin_send_query() {

    check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

    $email          = sanitize_email($_POST['email']);
    $product_id     = trim(sanitize_text_field($_POST['qcld_woo_map_product_id']));
    $customer_price = trim(sanitize_text_field($_POST['price']));

   $subject = get_option('qlcd_map_chatbot_admin_email_subject');
   //Extract Domain
   $url = get_site_url();
   $url = parse_url($url);
   $domain = $url['host'];

   $admin_email = get_option('admin_email');
   $toEmail = get_option('qlcd_map_chatbot_admin_email') != '' ? get_option('qlcd_map_chatbot_admin_email') : $admin_email;
   $fromEmail = "wordpress@" . $domain;


    //build email body
    $bodyContent = "";
    $bodyContent .= '<p><strong>' . esc_html('Query Details', 'woo-minimum-acceptable-price') . ':</strong></p><hr>';
    
    $bodyContent .= '<p>' . esc_html('Email', 'woo-minimum-acceptable-price') . ' : ' . esc_html($email) . '</p>';

    $bodyContent .= '<p>' . esc_html('Offer Price', 'woo-minimum-acceptable-price') . ' : ' . esc_html($customer_price) . '</p>';

    $product      = wc_get_product( $product_id ); 
    
    if(!empty($product_id)){
    
        $bodyContent .= '<p><a target="_blank" href="'. esc_url(get_permalink($product_id)) .'" title="'. esc_attr($product->get_title()) .'">'. esc_html($product->get_title()) .'</a></p>';
    }
        
    $bodyContent .= '<p>' . esc_html('Mail Generated on', 'woo-minimum-acceptable-price') . ': ' . date('F j, Y, g:i a') . '</p>';
    $to = $toEmail;
    $body = $bodyContent;

    $name = esc_html('Bargaining Chatbot', 'woo-minimum-acceptable-price');

    $headers   = array();
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . esc_html($name) . ' <' . esc_html($fromEmail) . '>';
    $headers[] = 'Reply-To: <' . esc_html($email) . '>';

    $result = wp_mail($to, $subject, $body, $headers);
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = esc_html(str_replace('\\', '',get_option('qlcd_wp_chatbot_email_sent')));
    }

   ob_clean();
   echo json_encode($bodyContent);
   die();

}


}


}


/****************************************************
 * bargin modal product ajax
 *****************************************************/


//Get bargin products
if (!function_exists('qcld_woo_bargin_product_modal')){
function qcld_woo_bargin_product_modal($product_id){

    $product_id     = $product_id;
    $product        = wc_get_product( $product_id ); // product_id

    $qcld_minimum_accept_price_button_text =  get_option('qcld_minimum_accept_price_button_text');
    $qcld_minimum_accept_price_heading_text =  get_option('qcld_minimum_accept_price_heading_text');
    $qcld_minimum_accept_modal_submit_button_text =  get_option('qcld_minimum_accept_modal_submit_button_text');

    $image = get_the_post_thumbnail($product_id, 'shop_catalog');
    if(empty($image)){
        $image = woocommerce_placeholder_img( 'shop_catalog' );
    }

    $html = '';
    
    if(!empty($product_id)){
    $html .= '<div id="wpbot-map-product-modal" class="mfp-hide white-popup-block animated flipInX"><div id="wpbot-map-product">';

    $html .= '<div class="woo-chatbot-products-bargain-modal">';

        $html .= '<h5>'.esc_html($qcld_minimum_accept_price_button_text).'</h5>';
        // product details
        $html .= '<div class="woo-chatbot-product-modal">';
            $html .= '<div class="woo-chatbot-product-modal-img">';
            $html .= $image . '</div>
            <div class="woo-chatbot-product-bargain-modal">
            <h3 class="woo-chatbot-product-title-modal">' . esc_html($product->get_title()) . '</h3></div>
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
        $html .= '<button type="button" class="woo-chatbot-product-bargain-submit" product-id="'.esc_attr($product_id).'">'.esc_html($qcld_minimum_accept_modal_submit_button_text).' </button>';
        $html .= '</div>';
        $html .= '</div>';
    
    $html .= '</div>';
    $html .= '</div></div>';

    }else{

        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p>' . esc_html_e('You have no products', 'woo-minimum-acceptable-price') . ' !';
        $html .= '</div>';
    }
   
    return $html;

}
}


//Get bargin products
add_action('wp_ajax_qcld_woo_bargin_product_modal_price', 'qcld_woo_bargin_product_modal_price');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product_modal_price', 'qcld_woo_bargin_product_modal_price');
if (!function_exists('qcld_woo_bargin_product_modal_price')){
function qcld_woo_bargin_product_modal_price(){

    check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

    $product_id     = trim(sanitize_text_field($_POST['qcld_woo_map_product_id']));
    $customer_price = trim(sanitize_text_field($_POST['price']));
    $product        = wc_get_product( $product_id ); // product_id
    $actual_price   = $product->get_price();

    $minimum_price_check  = $product->get_meta( 'qcld_minimum_acceptable_price' );
    $multi_currency  = $product->get_meta( 'qcld_minimum_acceptable_multi_currency' );
    if(!empty($minimum_price_check) && ($multi_currency == 'simple')){
        $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
    }elseif(!empty($minimum_price_check) && ($multi_currency == 'multiple')){
        $discount_percent_price =  $minimum_price_check;
        $minimum_price = ( $actual_price * $discount_percent_price /100 );

    }else{
        $discount_percent_price =  get_option('qcld_minimum_accept_product_discount_price');
        $minimum_price = ( $actual_price * $discount_percent_price / 100 );
    }


    $amount         = $actual_price - $minimum_price; // Amount

    $qcld_map_currency_symbol = get_woocommerce_currency_symbol(); 

    $checking_min_price = ($actual_price * 2/3);

    $confirm = '';
    $html = '';

    $sale_price = $minimum_price;
 
    if(($customer_price < $minimum_price)){

        $price_low_text =  get_option('qcld_minimum_accept_price_low_alert_text');

        $price_low_text_msg = str_replace("{offer price}", $customer_price . $qcld_map_currency_symbol , $price_low_text);

        $html .= '<p>'. esc_html($price_low_text_msg) .'</p>';
        
        $confirm = 'success';
        $sale_price = $minimum_price;

    }else if($customer_price >= $actual_price){

        $more_offer_price_text =  get_option('qcld_minimum_accept_price_more_than_offer_price');

        $more_offer_price_text_msg = str_replace("{offer price}", $customer_price. $qcld_map_currency_symbol , $more_offer_price_text);

        $html .= '<p>'. esc_html($more_offer_price_text_msg) . ' ! </p>';

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
        $html .= '<p>' .esc_html($acceptable_price_msg) .'</p>';
        $html .= '<p>' .esc_html($coupon_code_msg) .'</p>';
        $html .= '</div>';

        $confirm = 'agree';
        $sale_price = $customer_price;

    }
   
    $response = array('html' => $html, 'confirm' => $confirm, 'sale_price' => $sale_price );

    echo wp_send_json($response);
    wp_die();

}
}

//Get bargin products
add_action('wp_ajax_qcld_woo_bargin_product_modal_disagree', 'qcld_woo_bargin_product_modal_disagree');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product_modal_disagree', 'qcld_woo_bargin_product_modal_disagree');
if (!function_exists('qcld_woo_bargin_product_modal_disagree')){
function qcld_woo_bargin_product_modal_disagree(){

    check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

    $product_id     = isset( $_POST['qcld_woo_map_product_id'] )    ? trim(sanitize_text_field($_POST['qcld_woo_map_product_id'])) : '';
    $customer_price = isset( $_POST['offer_price'] )                ? trim(sanitize_text_field($_POST['offer_price'])) : '';
    $email          = isset( $_POST['email'] )                      ? sanitize_email($_POST['email']) : '';

   // Extract Domain
   $url = get_site_url();
   $url = parse_url($url);
   $domain = $url['host'];

   $admin_email = get_option('admin_email');
   $toEmail = get_option('qlcd_map_chatbot_admin_email') != '' ? get_option('qlcd_map_chatbot_admin_email') : $admin_email;
   $fromEmail = "wordpress@" . $domain;

    //build email body
    $bodyContent = "";
    $bodyContent .= '<p><strong>' . esc_html('Query Details', 'woo-minimum-acceptable-price') . ':</strong></p><hr>';
    
    $bodyContent .= '<p>' . esc_html('Email', 'woo-minimum-acceptable-price') . ' : ' . esc_html($email) . '</p>';
    $bodyContent .= '<p>' . esc_html('Offer Price', 'woo-minimum-acceptable-price') . ' : ' . esc_html($customer_price) . '</p>';

    $product      = wc_get_product( $product_id ); // product_id

    $subject = get_option('qlcd_map_chatbot_admin_email_subject');
    
    if(!empty($product_id)){
    
        $bodyContent .= '<p><a target="_blank" href="'. esc_url(get_permalink($product_id)) .'" title="'. esc_attr($product->get_title()) .'">'. esc_html($product->get_title()) .'</a></p>';
    
    }
        
    $bodyContent .= '<p>' . esc_html('Mail Generated on', 'woo-minimum-acceptable-price') . ': ' . date('F j, Y, g:i a') . '</p>';
    $to = $toEmail;
    $body = $bodyContent;

    $name = esc_html('Bargaining Chatbot', 'woo-minimum-acceptable-price');

    $headers   = array();
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . esc_html($name) . ' <' . esc_html($fromEmail) . '>';
    $headers[] = 'Reply-To: <' . esc_html($email) . '>';

    $result = wp_mail($to, $subject, $body, $headers);
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = esc_html(str_replace('\\', '',get_option('qlcd_wp_chatbot_email_sent')));
    }

   ob_clean();
   echo json_encode($bodyContent);
   die();

}
}



add_action('wp_ajax_qcld_woo_bargin_product_add_to_cart', 'qcld_woo_bargin_product_add_to_cart');
add_action('wp_ajax_nopriv_qcld_woo_bargin_product_add_to_cart', 'qcld_woo_bargin_product_add_to_cart');
if (!function_exists('qcld_woo_bargin_product_add_to_cart')){
    function qcld_woo_bargin_product_add_to_cart(){

        check_ajax_referer( 'woo-minimum-acceptable-price', 'security');

        ob_start();

        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( sanitize_text_field($_POST['product_id']) ) );
        $quantity           = $_POST['product_qty'];
        $quantity           = empty( $quantity ) ? 1 : apply_filters( 'woocommerce_stock_amount', $quantity );
        $variation          = '';

        $customer_price     =  isset( $_POST['price'] ) ? round(trim(sanitize_text_field($_POST['price'])), 2) : '';

        $product            = wc_get_product( $product_id );
        

        $minimum_price  = $product->get_meta( 'qcld_minimum_acceptable_price' );
        $multi_currency  = $product->get_meta( 'qcld_minimum_acceptable_multi_currency' );
        if(empty(!$minimum_price) && ($multi_currency == 'simple')){
            $actual_price   = $product->get_price();
            $customer_price =  (float) $_POST['price'];
            
        }elseif(!empty($minimum_price) && ( $_POST['price'] > $minimum_price )  && ($multi_currency == 'multiple')){

            $actual_price   = $product->get_price();
            $customer_price =  (float) $_POST['price'];

        }elseif(!empty($minimum_price) && ($multi_currency == 'multiple')){

            $actual_price   = (float) get_post_meta( $product_id, '_sale_price', true) ? get_post_meta( $product_id, '_sale_price', true) : get_post_meta( $product_id, '_regular_price', true);
            $discount_percent_price = $minimum_price;
            $customer_price = ( $actual_price * $discount_percent_price / 100 );

        }else{

            $actual_price   = $product->get_price();
            $discount_percent_price =  get_option('qcld_minimum_accept_product_discount_price');
            $customer_price = $actual_price - ( $actual_price * $discount_percent_price / 100 );
        }

        $coupon_amount  = $actual_price - $customer_price; //  discount price from sale price

        WC()->session->set( 'qcld_map_coupon_name', $coupon_amount );
        WC()->session->set( 'qcld_map_coupon_product_id', $product_id );

        global $woocommerce;
        
        $woocommerce->cart->add_to_cart($product_id,$quantity);

        $qcld_minimum_accept_congratulations_text = get_option('qcld_minimum_accept_congratulations_text');
        $qcld_minimum_accept_congratulations_added_to_cart = get_option('qcld_minimum_accept_congratulations_added_to_cart');
      
        $html = '<h4>'. esc_html($qcld_minimum_accept_congratulations_text) .'</h4>';
        $html .= '<p>'. esc_html($qcld_minimum_accept_congratulations_added_to_cart) .'</p>';

   
        $response = array('html' => $html );

        echo wp_send_json($response);

        wp_die();


    }

}