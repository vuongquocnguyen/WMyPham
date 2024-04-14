<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for Checkout.vn.
 * @since 1.0
 */
return array(
	'enabled'       => array(
		'title'   => __( 'Bật/Tắt', 'checkout' ),
		'type'    => 'checkbox',
		'label'   => __( 'Checkout.vn payment gateway', 'checkout' ),
		'default' => 'no'
	),
	
	'title'         => array(
		'title'       => __( 'Tiêu đề', 'checkout' ),
		'type'        => 'text',
		'description' => __( 'Tiêu đề thanh toán', 'checkout' ),
		'default'     => __( 'Thanh toán qua Checkout.vn', 'checkout' ),
		'desc_tip'    => true,
	),
	'description'   => array(
		'title'       => __( 'Mô tả', 'checkout' ),
		'type'        => 'textarea',
		'desc_tip'    => true,
		'description' => __( 'Mô tả hình thức thanh toán qua Checkout.vn', 'checkout' ),
		'default'     => __( 'Thanh toán qua Checkout.vn, Hộ trợ tất cả hình thức thanh toán tại Việt Nam.', 'checkout' )
	),
	'order_button_text'   => array(
		'title'       => __( 'Nút thanh toán', 'checkout' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Tên nút thanh toán trên trang checkout.', 'checkout' ),
		'default'     => __( 'Thanh toán Checkout.vn', 'checkout' )
	),
);