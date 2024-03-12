<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


abstract class WooCheckout_Abstract extends WC_Payment_Gateway {

	/**
	 * Configure $method_title and $method_description
	 */
	abstract public function configure_payment();

    abstract public function get_checkout_payment_link($link);
	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = strtolower( get_called_class() );
		$this->has_fields         = false;
		$this->configure_payment();
		$this->supports           = array(
			'products',
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
        $this->title             = $this->get_option( 'title' );
		$this->description       = $this->get_option( 'description' );
		$this->order_button_text = $this->get_option( 'order_button_text' );
		// Process the admin options
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'handle_checkout_return_url' ) );
	}
    /**
	 * Process the payment
	 *
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		return array(
			'result'   => 'success',
			'redirect' => $this->get_pay_url( $order )
		);
	}
	/**
	 * Get the checkout URL for an order
	 *
	 * @param  WC_Order $order
	 *
	 * @return string
	 */
	public function get_pay_url( $order ) {
        $id = $order->get_id();
        $total = $order->get_total();
        $first_name = $order->get_billing_first_name();
        $last_name = $order->get_billing_last_name();
        $full_name = $first_name.' '.$last_name;
        $email = $order->get_billing_email();
        $phone = $order->get_billing_phone();

        $product = $order->get_items();

        
        $apiKey = get_option( 'salekit_checkout_api' );
        $token = get_option( 'salekit_checkout_token' );

        $orderProduct = array();

        foreach ( $order->get_items() as $item_id => $item ) {
            array_push($orderProduct, [
                "item_name" => $item->get_name(),
                "item_quantity" => $item->get_quantity(),
                "item_price" => $item->get_total()
            ]);
        }

        $value = [
            "token" => $token,
            "currency" => "vnd",
            "order_items" => $orderProduct,
            "pay_type" => 1,
            "pay_money" => $total,
            "contact_name" => $full_name,
            "contact_mobile" => $phone,
            "contact_email" => $email,
            "order_id" => $id, 
            "ship_fee" => 0,
            "url_thanks" => $this->get_return_url( $order ),
            "url_cancel" => $order->get_cancel_order_url_raw()
        ]; 
        
        $link = $this->getLinkCheckout($token, $apiKey, $value,$website=false);
        
        return $this->get_checkout_payment_link($link);
    }

    function getLinkCheckout($token, $apiKey, $value,$website = false) {
        $serialize = true;
        $iv = random_bytes(16);
        $cipher = 'AES-256-CBC';
        $value = \openssl_encrypt(
            $serialize ? serialize($value) : $value,
            $cipher, $apiKey, 0, $iv
        );
    
        if ($value === false) {
            return 'Could not encrypt the data';
        }
    
        $mac = $this->hashMake($iv = base64_encode($iv), $value, $apiKey);
        $json = json_encode(compact('iv', 'value', 'mac'));
        if (! is_string($json)) {
            return 'Could not encrypt the data';
        }
        if($website ) {
            if($website == 'checkout')
                $link =  "https://".$website.".vn/checkout?token=$token&datas=". base64_encode($json);
            else
                $link = "https://".$website.".checkout.vn/checkout?token=$token&datas=". base64_encode($json);
        }
        else  {
            $link = "https://checkout.vn/checkout?token=$token&datas=". base64_encode($json);
        }
        return $link;
    }
            
    function hashMake($iv, $value, $key)
    {
        return hash_hmac('sha256', $iv.$value, $key);
    }

    /**
	 * Handle the return URL - GET request from checkout.vn
	 */
	public function handle_checkout_return_url() {

		if ( isset( $_GET['order_id'] ) ) {

			$order = wc_get_order( $_GET['order_id'] );
            $order->payment_complete();
            
		}
	}

}
