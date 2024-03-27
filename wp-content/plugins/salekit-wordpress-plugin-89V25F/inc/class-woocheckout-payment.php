<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


require_once('abstract-payment.php');
class WooCheckout_Payment extends WooCheckout_Abstract {

	public function configure_payment() {
		$this->method_title       = __( 'Thanh toán qua Checkout.vn', 'checkout' );
		$this->method_description = __( 'Hỗ trợ nhiều hình thức thanh toán', 'checkout' );
	}

    public function get_checkout_payment_link($link) {
		return $link;
	}
    /**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = include( 'payment-settings.php' );
	}
}