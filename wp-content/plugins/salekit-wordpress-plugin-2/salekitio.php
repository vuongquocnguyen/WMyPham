<?php


header("Access-Control-Allow-Origin: *");

/**

 *

 * Plugin Name: Salekit

 * Plugin URI: https://salekit.com/

 * Description: Đồng bộ Sản phẩm, Đơn hàng, Khách hàng, Lead,... giữa CRM và website Wordpress

 * Author: Salekit

 * Author URI: https://salekit.com/

 * Version: 1.8

 * License: GNU GENERAL PUBLIC LICENSE, Version 3

 * Text Domain: salekit

 * Domain Path: /languages

 */





if (!defined('ABSPATH')) {

	exit;
}



/** Add Menu Admin */

function admin_menu_salekit()
{

	$icon = plugin_dir_url(__FILE__) . 'assets/img/menu.png';

	add_menu_page(__('Salekit', 'salekit'), __('Salekit', 'salekit'), 'manage_options', 'salekit-options', 'salekit_text_admin_page', $icon, 50);

	add_submenu_page('salekit-options', 'Salekit.com', 'Salekit.com', 'manage_options', 'salekit-options');

	add_submenu_page('salekit-options', 'Salekit.io', 'Salekit.io', 'manage_options', 'order-menu', 'salekit_io_page');

	add_submenu_page('salekit-options', 'Fchat.vn', 'Fchat.vn', 'manage_options', 'contact-menu', 'salekit_fchat_page');

	add_submenu_page('salekit-options', 'Webpush.vn', 'Webpush.vn', 'manage_options', 'webpush-menu', 'salekit_webpush_page');

	add_submenu_page('salekit-options', 'Checkout.vn', 'Checkout.vn', 'manage_options', 'fchat-menu', 'salekit_checkout_page');
}

add_action('admin_menu', 'admin_menu_salekit');

function my_jquery_script($hook)
{

	$current_page = get_current_screen()->base;

	if (strpos($current_page, 'salekit') === false) {

		return;
	}

	wp_enqueue_script('jQuery', 'https://code.jquery.com/jquery-3.6.0.min.js');

	wp_enqueue_style('Salekit-Style', plugins_url('/assets/style.css', __FILE__));

	wp_enqueue_script('Salekit-Js', plugins_url('/assets/script.js', __FILE__));
}

add_action('admin_enqueue_scripts', 'my_jquery_script');



add_filter('plugin_row_meta', 'plugin_row_meta', 10, 2);



function plugin_row_meta($links, $file)
{

	if ($file == 'Salekit-Wordpress-Plugin/salekit.php') {

		$row_meta = array(

			'setting_link'    => '<a href="https://salekit.com/estore/setting?type=setting" target="_blank" ">Settings</a>',

			'docs_link'    => '<a href="https://salekit.com/help/wordpress" target="_blank" ">Docs</a>',

		);



		return array_merge($links, $row_meta);
	}



	return (array) $links;
}

if (!function_exists('get_plugin_data')) {

	require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

if (!function_exists('salemallCallAPI')) {

	function salemallCallAPI($url, $token, $data)
	{

		$curl = curl_init();

		curl_setopt_array($curl, array(

			CURLOPT_URL => $url,

			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_ENCODING => "",

			CURLOPT_MAXREDIRS => 10,

			CURLOPT_TIMEOUT => 30,

			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

			CURLOPT_CUSTOMREQUEST => $type,

			CURLOPT_POSTFIELDS => json_encode($data),

			CURLOPT_HTTPHEADER => array(

				"Token: " . $token,

				"content-type: application/json"

			),

		));



		$response = curl_exec($curl);



		$err = curl_error($curl);



		curl_close($curl);

		if ($err) {

			return "cURL Error #:" . $err;
		} else {

			return  preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
		}
	}
}

class SalekitLoader
{



	public $plugin_slug;

	public $version;

	public $cache_key;

	public $cache_allowed;



	public function __construct()
	{

		$this->setCookieSalekit();
		// var_dump('123');die;
		$plugin_data = get_plugin_data(__FILE__);

		$plugin_version = $plugin_data['Version'];

		$this->plugin_slug = plugin_basename(__DIR__);

		$this->version = $plugin_version;

		$this->cache_key = 'salekit_custom_upd';

		$this->cache_allowed = false;



		add_filter('plugins_api', array($this, 'info'), 20, 3);

		add_filter('site_transient_update_plugins', array($this, 'update'));

		add_action('upgrader_process_complete', array($this, 'purge'), 10, 2);
	}

	public function setCookieSalekit()
	{
		if (isset($_GET['ref'])) {
			setcookie('ref', $_GET['ref'], time() + 60 * 60 * 24 * 30);
			$_COOKIE["ref"] = $_GET['ref'];
		}
		if (isset($_GET['src'])) {
			setcookie('src', $_GET['src'], time() + 60 * 60 * 24 * 30);
			$_COOKIE["src"] = $_GET['src'];
		}
	}

	public function request()
	{



		$remote = get_transient($this->cache_key);



		if (false === $remote || !$this->cache_allowed) {



			$remote = wp_remote_get(

				'https://salekit.com/assets/info.json',

				array(

					'timeout' => 10,

					'headers' => array(

						'Accept' => 'application/json'

					)

				)

			);



			if (

				is_wp_error($remote)

				|| 200 !== wp_remote_retrieve_response_code($remote)

				|| empty(wp_remote_retrieve_body($remote))

			) {

				return false;
			}



			set_transient($this->cache_key, $remote, DAY_IN_SECONDS);
		}



		$remote = json_decode(wp_remote_retrieve_body($remote));



		return $remote;
	}





	function info($res, $action, $args)
	{



		if ('plugin_information' !== $action) {

			return false;
		}

		if ($this->plugin_slug !== $args->slug) {

			return false;
		}



		// get updates

		$remote = $this->request();



		if (!$remote) {

			return false;
		}



		$res = new stdClass();



		$res->name = $remote->name;

		$res->slug = $remote->slug;

		$res->version = $remote->version;

		$res->tested = $remote->tested;

		$res->requires = $remote->requires;

		$res->author = $remote->author;

		$res->author_profile = $remote->author_profile;

		$res->download_link = $remote->download_url;

		$res->trunk = $remote->download_url;

		$res->requires_php = $remote->requires_php;

		$res->last_updated = $remote->last_updated;



		$res->sections = array(

			'description' => $remote->sections->description,

			'installation' => $remote->sections->installation,

			'changelog' => $remote->sections->changelog

		);



		if (!empty($remote->banners)) {

			$res->banners = array(

				'low' => $remote->banners->low,

				'high' => $remote->banners->high

			);
		}



		return $res;
	}



	public function update($transient)
	{



		if (empty($transient->checked)) {

			return $transient;
		}



		$remote = $this->request();



		if (

			$remote

			&& version_compare($this->version, $remote->version, '<')

			&& version_compare($remote->requires, get_bloginfo('version'), '<')

			&& version_compare($remote->requires_php, PHP_VERSION, '<')

		) {

			$res = new stdClass();

			$res->slug = $this->plugin_slug;

			$res->plugin = plugin_basename(__FILE__);

			$res->new_version = $remote->version;

			$res->tested = $remote->tested;

			$res->package = $remote->download_url;



			$transient->response[$res->plugin] = $res;
		}



		return $transient;
	}



	public function purge()
	{



		if (

			$this->cache_allowed

			&& 'update' === $options['action']

			&& 'plugin' === $options['type']

		) {

			// just clean the cache when new plugin version is installed

			delete_transient($this->cache_key);
		}
	}
}



new SalekitLoader();

/** Send Woocommerce to API */

function salekitcom_send_order($order_id)
{
	$salekit_auto_order = get_option('salekit_auto_order', '');

	$salekit_domain_verify = get_option('salekit_domain_verify', '');

	if ($salekit_auto_order == 'false') return;

	if ($salekit_domain_verify == '') return;

	if (!$order_id) return;

	date_default_timezone_set('Asia/Ho_Chi_Minh');

	update_option('salekit_plugin_last_order', $order_id);

	update_option('salekit_plugin_last_time', date("d/m/Y H:i"));

	$salekit_api = get_option('salekit_plugin_variable', 'salekit_api');

	$order = new WC_Order($order_id);

	$products_order = [];

	foreach ($order->get_items() as $item_id => $item) {

		$product = wc_get_product($item->get_product_id());

		$images_id = $product->get_gallery_image_ids();

		$images = [];

		foreach ($images_id as $attachment_id) {

			$image_link = wp_get_attachment_url($attachment_id);

			array_push($images, $image_link);
		}

		$aProduct = array(

			'id' => $product->get_id(),

			'name' => $product->get_name(),

			//'create' => $product->get_date_created(),

			'description' => $product->get_description(),

			'short_description' => $product->get_short_description(),

			'sku' => $product->get_sku(),

			'price' => $product->get_regular_price(),

			'sale_price' => $product->get_sale_price(),

			'package_width' => $product->get_width(),

			'package_length' => $product->get_length(),

			'package_height' => $product->get_height(),

			'weight' => $product->get_weight(),

			'stock' => $product->get_stock_quantity(),

			'images' => $images,

			'featured' => wp_get_attachment_url($product->get_image_id()),

		);

		$product_order = array(

			'id' => $item->get_product_id(),

			'name' => $item->get_name(),

			'quantity' => $item->get_quantity(),

			'subtotal' => $item->get_subtotal(),

			'total' => $item->get_total(),

			'product' => $aProduct,

		);

		array_push($products_order, $product_order);
	}


	$aOrder = array(

		'token' => $salekit_api,

		'id' => $order->get_id(),

		'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),

		'phone' => $order->get_billing_phone(),

		'email' => $order->get_billing_email(),

		'address' => $order->get_billing_address_1() . ' ' . $order->get_billing_address_2() . '-' . $order->get_billing_city() . '' . $order->get_billing_state() . '-' . $order->get_billing_country(),

		'note' => $order->get_customer_note(),

		'pay_type' => $order->get_payment_method(),

		'status' => $order->get_status(),

		'total_price' => $order->get_total(),

		'ship_fee' => $order->get_shipping_total(),

		'total_pay' => $order->get_subtotal(),

		'discount' => $order->get_total_discount(),

		'items' => $products_order,

		'created_at' => date("U", strtotime($order->get_date_created())),

	);

	if (isset($_COOKIE["ref"]) && $_COOKIE["ref"]) {
		$aOrder['ref'] = $_COOKIE["ref"];
	}
	if (isset($_COOKIE["src"]) && $_COOKIE["src"]) {
		$aOrder['src'] = $_COOKIE["src"];
	}




	$url =   "https://salekit.com/wordpress/rsync_order";

	$data_string = $aOrder;

	$curl = curl_init($url);

	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_string));

	curl_setopt($curl, CURLOPT_HEADER, false);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

	$status_code = @curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$result = curl_exec($curl);

	curl_close($curl);

	// var_dump($data_string);die();
}



add_action('woocommerce_thankyou', 'salekitcom_send_order');

function set_to_pending($order_id)
{

	if (!$order_id) {
		return;
	}

	$order = wc_get_order($order_id);
	$order->update_status('pending-payment');
}

function salekitio_send_order($order_id)
{

	$salekitio_auto_order = get_option('salekitio_auto_order', '');

	$salekitio_status = get_option('salekitio_status', '');

	if ($salekitio_auto_order == 'false') return;

	if ($salekitio_status == 'error' || $salekitio_status == '') return;

	if (!$order_id) return;

	date_default_timezone_set('Asia/Ho_Chi_Minh');

	update_option('last_order_io', $order_id);

	update_option('last_time_oi', date("d/m/Y H:i"));

	$token = get_option('salekitio_token', '');

	$order = wc_get_order($order_id);



	$order_data = $order->get_data();

	$first_name = $order_data['billing']['first_name'];

	$last_name = $order_data['billing']['last_name'];

	$full_name = $first_name . ' ' . $last_name;

	$phone = $order_data['billing']['phone'];

	$email = $order_data['billing']['email'];

	$address_1 = $order_data['billing']['address_1'];



	$data_form_json = array();

	$data_form = array();

	foreach ($order_data['billing'] as $key => $value) {

		$data_form[] = $value;

		$data_form_json[] = array('field_title' => $key, "field_type" => "text", "field_name" => $key, "list_value" => "");
	}

	$url =   "https://salekit.io/api/v1/wordpress/create";

	$domain = $_SERVER['HTTP_ORIGIN'];

	$data = array(

		'full_name' => $full_name,

		'email' => $email,

		'phone' => $phone,

		'address' => $address_1,

		'domain' => $domain,

		'form_id' => 'order',

		'form_name' => 'Form đặt hàng',

		"tag" => "domain,order",

		"data_form" => json_encode($data_form),

		"data_form_json" => json_encode($data_form_json)

	);



	$return_data = json_decode(salemallCallAPI($url, $token, $data), true);

	setcookie('salektio_info', $return_data['cid'], time() + (86400 * 30), "/");
}

add_action('woocommerce_checkout_order_processed', 'salekitio_send_order');

/** Send CF7 to API */



function salekitio_send_mail($contact_form)
{

	$salekitio_auto_goal = get_option('salekitio_auto_goal', '');

	$salekitio_auto_contact = get_option('salekitio_auto_contact', '');

	$salekitio_status = get_option('salekitio_status', '');

	if ($salekitio_auto_contact == 'false') return;

	if ($salekitio_auto_goal == 'false') return;

	if ($salekitio_status == 'error' || $salekitio_status == '') return;

	$form_id = $contact_form->id;

	$submission = WPCF7_Submission::get_instance();



	if ($submission) {

		$posted_data = $submission->get_posted_data();

		$full_name = $posted_data['full_name'];

		$email = $posted_data['email'];

		$phone = $posted_data['phone'];

		$address = $posted_data['address'];

		$birthday = $posted_data['birthday'];

		if (!$full_name && $posted_data['your-name']) {

			$full_name = $posted_data['your-name'];
		}

		if (!$full_name && $posted_data['your-name']) {

			$full_name = $posted_data['your-name'];
		}

		if (!$email && $posted_data['your-email']) {

			$email = $posted_data['your-email'];
		}

		$data_form_json = array();

		$data_form = array();

		foreach ($posted_data as $key => $value) {

			if (is_array($value)) {

				$value = implode(',', $value);
			}

			$data_form[] = $value;

			$data_form_json[] = array('field_title' => $key, "field_type" => "text", "field_name" => $key, "list_value" => "");
		}

		$form_data = WPCF7_ContactForm::get_current();

		$form_id = $form_data->id();

		$form_name = $form_data->title();

		date_default_timezone_set('Asia/Ho_Chi_Minh');

		update_option('last_contact_io', $posted_data['email']);

		update_option('last_contact_time_oi', date("d/m/Y H:i"));

		$salekit_api = get_option('salekitio_token', '');

		$url =   "https://salekit.io/api/v1/wordpress/create";

		$domain = $_SERVER['HTTP_ORIGIN'];

		$data = array(

			'full_name' => $full_name,

			'email' => $email,

			'phone' => $phone,

			'address' => $address,

			'birthday' => $birthday,

			'form_id' => $form_id,

			'form_name' => $form_name,

			'domain' => $domain,

			"tag" => "domain,contact",

			"data_form" => json_encode($data_form),

			"data_form_json" => json_encode($data_form_json),

		);
		// if (isset($_COOKIE["ref"]) && $_COOKIE["ref"]) {
		// 	$data['ref'] = $_COOKIE["ref"];
		// }
		// if (isset($_COOKIE["src"]) && $_COOKIE["src"]) {
		// 	$data['src'] = $_COOKIE["src"];
		// }


		$return_data = json_decode(salemallCallAPI($url, $salekit_api, $data), true);

		setcookie('salektio_info', $return_data['cid'], time() + (86400 * 30), "/");
	}
}
// add_action('wpcf7_mail_sent', 'salekitio_send_mail');
add_action('wpcf7_before_send_mail', 'salekitio_send_mail');

//Rest API 

add_action('rest_api_init', function () {

	register_rest_route('api/v1', '/add', [

		'methods' => 'POST',

		'callback' => 'add_product',

		'permission_callback' => '__return_true',

	]);

	register_rest_route('api/v1', '/get', [

		'methods' => 'GET',

		'callback' => 'get_products',

		'permission_callback' => '__return_true',

	]);

	register_rest_route('api/v1', '/test', [

		'methods' => 'GET',

		'callback' => 'test',

		'permission_callback' => '__return_true',

	]);

	register_rest_route('api/v1', '/domain/verify', [

		'methods' => 'POST',

		'callback' => 'check_api',

		'permission_callback' => '__return_true',

	]);
});

function check_api($params)
{

	$salekit_api = get_option('salekit_plugin_variable', 'salekit_api');

	$data = $params->get_params();

	$token = $data['token'];

	$name = $data['name'];

	if ($salekit_api == $token) {

		update_option('salekit_domain_verify', $name);

		$result = array('error' => false, 'msg' => 'Xác minh thành công');
	} else {

		$result = array('error' => true, 'msg' => 'Sai Token vui lòng kiểm tra lại!');
	}

	return $result;
}

if (!function_exists('is_plugin_active')) {



	include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

function plugin_add_settings_link($links)
{

	$links = array_merge(array(

		'<a href="' . esc_url(admin_url('admin.php?page=salekit-options')) . '">' . __('Cấu hình', 'salekit') . '</a>'

	), $links);

	return $links;
}

$plugin = plugin_basename(__FILE__);

add_filter("plugin_action_links_$plugin", 'plugin_add_settings_link');

function get_products($params)
{
	header("Access-Control-Allow-Origin: *");

	$salekit_api = get_option('salekit_plugin_variable', 'salekit_api');

	$data = $params->get_params();

	$token = $data['key'];

	if (is_plugin_active('woocommerce/woocommerce.php')) {

		$query = new WC_Product_Query(array(

			'orderby' => 'date',

			'order' => 'DESC',

			'status' => 'publish',

			'return' => 'ids',

			'limit' => -1

		));

		$products = $query->get_products();

		$custom_product = [];

		foreach ($products as $id) {

			$product = wc_get_product($id);

			$images_id = $product->get_gallery_image_ids();

			$images = [];

			$cats = [];

			$atts = [];

			$catids = $product->get_category_ids();

			foreach ($images_id as $attachment_id) {

				$image_link = wp_get_attachment_url($attachment_id);

				array_push($images, $image_link);
			}

			if ($catids) {

				foreach ($catids as $id) {

					$catdata = get_term($id);



					array_push($cats, $catdata);
				}
			}

			$attributes = $product->get_attributes();

			if ($attributes) {



				foreach ($attributes as $key => $att) {

					$attribute_data = $att->get_data();





					array_push($atts, $attribute_data);
				}
			}

			foreach ($atts as $key => $att) {

				$atts[$key]['items'] = $product->get_attribute($att['name']);

				$atts[$key]['title'] = wc_attribute_label($att['name']);
			}

			$aproduct = array(

				'id' => $product->get_id(),

				'name' => $product->get_name(),

				'attributes' => $atts,

				'category' => $cats,

				'description' => $product->get_description(),

				'link' => $product->get_permalink(),

				'short_description' => $product->get_short_description(),

				'sku' => $product->get_sku(),

				'price' => $product->get_regular_price(),

				'sale_price' => $product->get_sale_price(),

				'package_width' => $product->get_width(),

				'package_length' => $product->get_length(),

				'package_height' => $product->get_height(),

				'weight' => $product->get_weight(),

				'stock' => $product->get_stock_quantity(),

				'images' => $images,

				'featured' => wp_get_attachment_url($product->get_image_id()),

			);

			array_push($custom_product, $aproduct);
		}

		if ($salekit_api == $token) {

			return $custom_product;
		} else {

			$result = array('error' => true, 'msg' => 'Token không đúng hoặc đã bị thay đổi, vui lòng kiểm tra lại!');

			return $result;
		}
	}
}

function test($params)
{


	$product_featureds = wc_get_products(array('orderby' => 'date', 'order' => 'DESC', 'status' => 'publish', 'return' => 'ids', 'limit' => -1));


	var_dump($product_featureds);
}

function add_product($params)
{

	require_once(ABSPATH . 'wp-admin/includes/media.php');

	require_once(ABSPATH . 'wp-admin/includes/file.php');

	require_once(ABSPATH . 'wp-admin/includes/image.php');

	$data = $params->get_params();

	$token = $data['token'];

	$data_products = $data['products'];

	$check = false;

	$salekit_api = get_option('salekit_plugin_variable', 'salekit_api');

	$return_product = [];

	if ($salekit_api == $token) {

		foreach ($data_products as $item) {

			try {

				$product = $item['product'];

				$photo = $item['photo'];
				$source_id = $item['source'];

				// check san pham da ton tai nguoc lai tao moi

				$product_c = 0;

				// $product_featureds = wc_get_products( array('orderby' => 'date','order' => 'DESC','status' => 'publish','return' => 'ids', 'limit'=>-1));

				// foreach($product_featureds as $pro){

				// 	if($pro == $product['source_id']){

				// 		$product_c = $pro;

				// 	}

				// }



				if ($source_id) {

					$check_product = wc_get_product($source_id);

					$check_product->set_name($product['name']);

					$check_product->set_sku($product['sku']);

					$check_product->set_status("publish");

					$check_product->set_description($product['content']);

					$check_product->set_short_description($product['description']);

					$check_product->set_regular_price($product['unit_price']);

					$check_product->set_sale_price($product['price_sale']);

					$check_product->set_height($product['package_height']);

					$check_product->set_length($product['package_length']);

					$check_product->set_width($product['package_width']);

					$check_product->set_weight((int)$product['weight'] / 1000);

					$check_product->set_manage_stock(true);

					$check_product->set_stock_quantity($product['inventory']);

					//att

					$get_atts_str = $product['classify'];

					$get_atts = json_decode($get_atts_str, JSON_UNESCAPED_UNICODE);

					$attributes = (array) $check_product->get_attributes();

					$attributes = array();

					foreach ($get_atts as $att) {

						$attrValue = implode(',', $att['content']);

						$attribute = new WC_Product_Attribute();

						$attribute->set_name($att['name']);

						$attribute->set_options(array($attrValue));

						//$attribute->set_position( 0 );

						$attribute->set_visible(true);

						$attribute->set_variation(true);

						$attributes[] = $attribute;
					}

					$check_product->set_attributes($attributes);

					$check_product->save();

					$idnew = $source_id;
				} else {

					$products = new WC_Product();

					$products->set_name($product['name']);

					$products->set_sku($product['sku']);

					$products->set_status("publish");

					$products->set_description($product['content']);

					$products->set_short_description($product['description']);

					$products->set_regular_price($product['unit_price']);

					$products->set_sale_price($product['price_sale']);

					$products->set_height($product['package_height']);

					$products->set_length($product['package_length']);

					$products->set_width($product['package_width']);

					$products->set_weight((int)$product['weight'] / 1000);

					$products->set_manage_stock(true);

					$products->set_stock_quantity($product['inventory']);



					$idnew = $products->save();
				}

				if ($idnew) {

					$aProduct =  array(

						'product_id' => $product['id'],

						'estore_product_id' => $idnew,
						'link' => get_permalink($idnew),

					);

					array_push($return_product, $aProduct);

					$productImagesIDs = array();

					foreach ($photo as $img) {

						$img_url = $img['photo'];

						$mediaID = media_sideload_image($img_url, $idnew, '', 'id');

						if (!$mediaID['errors']) $productImagesIDs[] = $mediaID;
					}

					$objProduct = new WC_Product($idnew);

					if ($productImagesIDs) {

						$objProduct->set_image_id($productImagesIDs[0]);

						if (count($productImagesIDs) > 1) {

							$objProduct->set_gallery_image_ids($productImagesIDs);
						}

						$objProduct->save();
					}

					//att

					$get_atts_str = $product['classify'];

					$get_atts = json_decode($get_atts_str, JSON_UNESCAPED_UNICODE);

					$attributes = array();

					if ($get_atts) {

						foreach ($get_atts as $att) {

							$attrValue = implode(',', $att['content']);

							$attribute = new WC_Product_Attribute();

							$attribute->set_name($att['name']);

							$attribute->set_options(array($attrValue));

							//$attribute->set_position( 0 );

							$attribute->set_visible(true);

							$attribute->set_variation(true);

							$attributes[] = $attribute;
						}

						$objProduct->set_attributes($attributes);

						$objProduct->save();
					}
				}
			} catch (Exception $ex) {

				$check = true;
			}
		}
	}

	if ($salekit_api == $token) {

		$post_data = array(

			'code' => 200,

			'products' => $return_product,

		);

		return $post_data;
	} else {

		$result = array('error' => true, 'msg' => 'Token không đúng hoặc đã bị thay đổi, vui lòng kiểm tra lại!');

		return $result;
	}
}



// Ajax 

add_action('wp_ajax_salekit_option', 'salekit_option_init');

add_action('wp_ajax_nopriv_salekit_option', 'salekit_option_init');

function salekit_option_init()
{



	$salekit_auto_order = (isset($_POST['salekit_auto_order'])) ? esc_attr($_POST['salekit_auto_order']) : '';

	$salekit_auto_contact = (isset($_POST['salekit_auto_contact'])) ? esc_attr($_POST['salekit_auto_contact']) : '';

	$salekitio_auto_order = (isset($_POST['salekitio_auto_order'])) ? esc_attr($_POST['salekitio_auto_order']) : '';

	$salekitio_auto_contact = (isset($_POST['salekitio_auto_contact'])) ? esc_attr($_POST['salekitio_auto_contact']) : '';

	$salekitio_auto_goal = (isset($_POST['salekitio_auto_goal'])) ? esc_attr($_POST['salekitio_auto_goal']) : '';

	if ($salekit_auto_order) {

		update_option('salekit_auto_order', $salekit_auto_order);
	}

	if ($salekit_auto_contact) {

		update_option('salekit_auto_contact', $salekit_auto_contact);
	}

	if ($salekitio_auto_order) {

		update_option('salekitio_auto_order', $salekitio_auto_order);
	}

	if ($salekitio_auto_contact) {

		update_option('salekitio_auto_contact', $salekitio_auto_contact);
	}

	if ($salekitio_auto_goal) {

		update_option('salekitio_auto_goal', $salekitio_auto_goal);
	}



	wp_send_json_success('done');

	die();
}

//Webpush

function import_sdk_webpush()
{

	$fileName = "../WebPushSDK.js";

	if (!file_exists($fileName)) {

		$script = "importScripts('https://cdn.webpush.vn/WebPushWorkerSDK.js');";

		file_put_contents($fileName, $script);
	}
}

add_action('admin_notices', 'import_sdk_webpush');

function webpush_show_embed()
{

	$salekit_webpushid = get_option('salekit_webpushid', '');

	if ($salekit_webpushid) {

		$embed_html = "<!--Start of WebPush.vn--><script id='WebPush_ID' src='https://webpush.vn/js/embed.js?id=" . $salekit_webpushid . "' type='text/javascript'></script><!--End of WebPush.vn-->";

		echo $embed_html;
	}
}

add_action('wp_footer', 'webpush_show_embed');

//Fchat

function fchat_show_embed()
{

	$salekit_fchat = get_option('salekit_fchat', '');

	if ($salekit_fchat) {

		$embed_html = "<!--Start of Fchat.vn--><script type='text/javascript' src='https://cdn.fchat.vn/assets/embed/webchat.js?id=" . $salekit_fchat . "' async='async'></script><!--End of Fchat.vn-->";

		echo $embed_html;
	}
}

add_action('wp_footer', 'fchat_show_embed');



//Fchat

function salekitio_show_embed()
{

	$salekitio_id_hash = get_option('salekitio_id_hash', '');



	if ($salekitio_id_hash && get_option('salekitio_auto_goal', '') == 'true') {

		$embed_goal_html = '<!--Start of Salekit.io--><script id="ultra_embed" type="text/javascript" src="https://salekit.io/embed/goal?id=' . $salekitio_id_hash . '" async="async"></script><!--End of Salekit.io-->';

		echo $embed_goal_html;
	}
}

add_action('wp_head', 'salekitio_show_embed');



//Checkout

$option_checkout_token = get_option('salekit_checkout_token', '');

$option_checkout_api = get_option('salekit_checkout_api', '');

if ($option_checkout_token && $option_checkout_api) {

	new WooCheckout();
}

class WooCheckout
{

	static $default_settings = array();



	/**

	 * Setup class.

	 *

	 * @since 1.0

	 */

	public function __construct()
	{

		add_action('init', array($this, 'init'));
	}



	/**

	 * Throw a notice if WooCommerce is NOT active

	 */

	public function notice_if_not_woocommerce()
	{

		$class = 'notice notice-warning';



		$message = __(
			'Salekit Checkout.vn Plugin không thể chạy vì plugin WooCommerce chưa được bật. vui lòng bật plugin WooCommerce.',

			'checkout'
		);



		printf('<div class="%1$s"><p><strong>%2$s</strong></p></div>', $class, $message);
	}

	/**

	 * Run this method under the "init" action

	 */

	public function init()
	{

		// Load the localization feature

		$this->i18n();



		if (class_exists('WooCommerce')) {

			// Run this plugin normally if WooCommerce is active

			$this->main();
		} else {

			// Throw a notice if WooCommerce is NOT active

			add_action('admin_notices', array($this, 'notice_if_not_woocommerce'));
		}
	}

	/**

	 * Localize the plugin

	 * @since 1.0

	 */

	public function i18n()
	{

		load_plugin_textdomain('checkout', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	/**

	 * The main method to load the components

	 */

	public function main()
	{

		include('inc/class-woocheckout-payment.php');



		add_filter('woocommerce_payment_gateways', function ($methods) {

			$methods[] = 'WooCheckout_Payment';

			return $methods;
		});
	}
}

/** Salekit.com page */

function salekit_text_admin_page()
{

	$textvar = get_option('salekit_plugin_variable', '');

	$last_order = get_option('salekit_plugin_last_order', '');

	$last_time = get_option('salekit_plugin_last_time', '');

	$salekit_last_contact = get_option('salekit_last_contact', '');

	$salekit_last_time_contact = get_option('salekit_last_time_contact', '');

	$salekit_api = get_option('salekit_api', '');

	$salekit_domain_verify = get_option('salekit_domain_verify', '');

	if (isset($_POST['change-clicked']) && isset($_POST['salekit_api'])) {

		update_option('salekit_plugin_variable', $_POST['salekit_api']);

		$salekit_api = get_option('salekit_api', $_POST['salekit_api']);
		// var_dump($salekit_api);

		$url = "https://salekit.com/wordpress/verify";
		$data = array();

		$data_string = json_encode($data);
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		curl_setopt($curl, CURLOPT_HEADER, false);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(

			'Content-Type: application/json',

			'Token: ' . $_POST['salekit_api'] . '',

		));

		$status_code = @curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$result = curl_exec($curl);

		curl_close($curl);

		$object = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true);

		if ($salekit_api) {

			if (!$object['error']) {

				update_option('salekit_domain_verify', $object['name']);

				update_option('salekit_id', $object['id']);
			} else {

				update_option('salekit_domain_verify', 'error');

				update_option('salekit_id', '');
			}
		} else {

			update_option('salekit_domain_verify', '');

			update_option('salekit_id', '');
		}


		$textvar = get_option('salekit_plugin_variable', 'salekit_api');
	}

	if (isset($_POST['salekit_api']) && $_POST['salekit_api'] == '') {

		update_option('salekit_domain_verify', '');
	}

	$salekit_domain_verify = get_option('salekit_domain_verify', '');

?>

	<div id="salekit_admin" class="wrap">

		<div id="tab">

			<ul>

				<li class="tab1 active-tab"><a href="#tab1" data-tab="1">Giới thiệu</a></li>

				<li class="tab2"><a href="#tab2" data-tab="2">Đơn hàng</a></li>

			</ul>

		</div>

		<div id="data-container">

			<div id="tab1" class="tab-content active">

				<p><?php esc_html_e('Salekit.com là nền tảng quản lý đơn hàng đa kênh: Website, Cửa hàng, Sàn,..', 'salekit'); ?></p>

				<p><?php esc_html_e('Bạn có thể đồng bộ sản phẩm từ Salekit > Woocommerce', 'salekit'); ?></p>

				<p>Và đồng bộ Đơn hàng từ Woocommerce về Salekit để xử lý. <a href="https://salekit.com/blog/wordpress.html" target="_blank">Hướng dẫn</a></p>



				<form action="" method="post" style="margin-top:30px">

					<p>Nhập token shop của bạn. Lấy token <a href="https://salekit.com/estore/setting?type=connect" target="_blank">tại đây</a></p>

					<input type="text" value="<?php echo esc_html($textvar); ?>" id="salekit_api" name="salekit_api" placeholder="<?php _e('Ví dụ: 2248e3344a581df238098b4746f93111', 'salekit'); ?>" style="width: 300px; margin-right: 10px; height: 32px;">

					<input type="submit" class="button button-primary button-large" value="<?php _e('Save', 'salekit'); ?>" style="" /><br />

					<p>Trạng thái: <strong>

							<?php

							if ($salekit_domain_verify == '') {
								echo "<span class='text-danger'>Chưa kết nối</span>";
							} elseif ($salekit_domain_verify == 'error') {
								echo "<span class='text-danger'>Sai token</span";
							} else {
								echo "<span class='text-success'>Đã kết nối shop: " . $salekit_domain_verify . '</span>';
							}

							?></strong> <a style="margin-left:20px" href="https://salekit.com/shop/config" target="_blank">Đi tới shop >></a></p>



					<input name="change-clicked" type="hidden" value="1" />



				</form>

			</div>

			<div id="tab2" class="tab-content">

				<form action="" method="post">

					<p>

					<div class="ajax-notify-order" style="margin-bottom:10px"></div>

					<input type="checkbox" id="salekit_auto_order" name="salekit_auto_order" value="1" <?php checked('true', get_option('salekit_auto_order')); ?> />

					<label for="salekit_auto_order" style="vertical-align: bottom;"> Tự động đồng bộ đơn hàng từ Woocommerce

						> Salekit.com Thực hiện ngay khi khách hàng tạo đơn</label><br>

					</p>



					<div class="order-info">

						<?php if ($last_order) { ?>

							<p><?php _e('Mã đơn đồng bộ cuối cùng: ', 'salekit'); ?><strong><?php echo esc_html($last_order); ?></strong>

							</p>

							<p><?php _e('Thời gian đồng bộ cuối cùng: ', 'salekit'); ?><strong><?php echo esc_html($last_time); ?></strong>

							</p>

						<?php } ?>

					</div>

					<?php if ($textvar) { ?>

						<div class="status-loading" style="margin-top:10px">

							<span></span>

							<img src="images/loading.gif" alt="" style="vertical-align: middle; display:none">

						</div>

						<div class="action-button" style="margin-top: 10px">

							<!-- <a class="button button-success button-large" href="#" onClick="sendRsync('product')"><?php _e('Đồng bộ sản phẩm', 'salekit'); ?></a> -->

							<a class="button button-success button-large" href="#" onClick="sendRsync('order')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">

									<path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />

								</svg> <?php _e('Đồng bộ đơn hàng ngay', 'salekit'); ?></a>

						</div>



					<?php } ?>

					<hr style="margin-top:30px" />

					<p>Để đồng bộ sản phẩm từ Salekit.com > Woocommerce Bạn vui lòng đăng nhập <a href="https://salekit.com/product" target="_blank">vào đây</a> và bấm nút “Đồng bộ sàn”</p>

				</form>

			</div>

		</div>

	</div>

	<?php

	//product

	if (is_plugin_active('woocommerce/woocommerce.php')) {
		$query = new WC_Product_Query([
			'orderby' => 'date',

			'order' => 'DESC',

			'status' => 'publish',

			'return' => 'ids',

			'limit' => -1,
		]);

		$products = $query->get_products();

		$custom_product = [];

		foreach ($products as $id) {
			$product = wc_get_product($id);

			$images_id = $product->get_gallery_image_ids();

			$images = [];

			foreach ($images_id as $attachment_id) {
				$image_link = wp_get_attachment_url($attachment_id);

				array_push($images, $image_link);
			}

			$aproduct = [
				'id' => $product->get_id(),

				'name' => $product->get_name(),

				//'create' => $product->get_date_created(),

				'description' => $product->get_description(),

				'short_description' => $product->get_short_description(),

				'sku' => $product->get_sku(),

				'price' => $product->get_sale_price() ? $product->get_regular_price() : $product->get_price(),

				'sale_price' => $product->get_sale_price() ? $product->get_regular_price() : $product->get_price(),

				'package_width' => $product->get_width(),

				'package_length' => $product->get_length(),

				'package_height' => $product->get_height(),

				'weight' => $product->get_weight(),

				'stock' => $product->get_stock_quantity(),

				'images' => $images,

				'featured' => wp_get_attachment_url($product->get_image_id()),
			];

			array_push($custom_product, $aproduct);
		}

		//order

		$orderquery = new WC_Order_Query([
			'orderby' => 'date',

			'order' => 'DESC',

			'return' => 'ids',

			'limit' => -1,
		]);

		$orders = $orderquery->get_orders();

		$custom_order = [];

		foreach ($orders as $id) {
			$order = new WC_Order($id);

			$products_order = [];

			foreach ($order->get_items() as $item_id => $item) {
				$key_product = array_search($item->get_product_id(), array_column($custom_product, 'id'));

				$product_order = [
					'id' => $item->get_product_id(),

					'name' => $item->get_name(),

					'quantity' => $item->get_quantity(),

					'subtotal' => $item->get_subtotal(),

					'total' => $item->get_total(),

					'product' => $custom_product[$key_product],
				];

				array_push($products_order, $product_order);
			}

			$aorder = [
				'id' => $order->get_id(),

				'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),

				'phone' => $order->get_billing_phone(),

				'email' => $order->get_billing_email(),

				'address' => $order->get_billing_address_1() . '-' . $order->get_billing_address_2() . '-' . $order->get_billing_city() . '-' . $order->get_billing_state() . '-' . $order->get_billing_country(),

				'note' => $order->get_customer_note(),

				'pay_type' => $order->get_payment_method(),

				'status' => $order->get_status(),

				'total_price' => $order->get_total(),

				'ship_fee' => $order->get_shipping_total(),

				'total_pay' => $order->get_subtotal(),

				'discount' => $order->get_total_discount(),

				'items' => $products_order,

				'created_at' => date('U', strtotime($order->get_date_created())),
			];

			array_push($custom_order, $aorder);
		}
	}

	?>

	<script type="text/javascript">
		var salekit_api = '<?php echo esc_html($textvar); ?>';

		products = <?php echo json_encode($custom_product, false); ?>;

		orders = <?php echo json_encode($custom_order, false); ?>;

		data = {};

		function sendRsync(type) {

			if (type == 'product') {

				data = {
					api: salekit_api,
					product: products
				};

			} else {

				data = {
					api: salekit_api,
					orders: orders
				}

			}

			$.ajax({

				type: "POST",

				url: 'https://salekit.com/api/v1/product/wordpress/rsync?type=' + type,

				cache: false,

				data: data,

				crossDomain: true,

				headers: {

					'Content-Type': 'application/x-www-form-urlencoded'

				},

				beforeSend: function() {

					$('.status-loading span').text('Đang đồng bộ');

					$('.status-loading img').show();

					$('.action-button .button').css('opacity', '0.6');

					$('.action-button .button').css('pointer-events', 'none');

				},

				success: function(result) {

					$('.status-loading img').hide();

					$('.action-button .button').css('opacity', '1');

					$('.action-button .button').css('pointer-events', 'auto');

					//console.log(result);

					if (!result.error) {

						if (result.estore == false) {

							$('.status-loading span').text(
								'Token không đúng hoặc đã bị thay đổi, vui lòng kiểm tra lại!');

						} else {

							if (result.count == 0) {

								$('.status-loading span').text('Không còn đơn hàng để đồng bộ!');

							} else {

								$('.status-loading span').text('Đã đồng bộ ' + result.count + ' đơn hàng!');

							}

						}





						$('.status-loading span').css('color', '#09ad25');

					} else {

						$('.status-loading span').text('Đồng bộ thất bại!');

						$('.status-loading span').css('color', 'red');

					}

				}

			});

		}



		$("#salekit_auto_order").change(function() {

			console.log(salekit_auto_order);
			if (this.checked) {

				salekit_auto_order = true;

			} else {

				salekit_auto_order = false;

			}
			console.log('Checkbox changed:', this.checked);

			$.ajax({

				type: "post",

				dataType: "json",

				url: '<?php echo admin_url('admin-ajax.php'); ?>',

				data: {

					action: "salekit_option",

					salekit_auto_order: salekit_auto_order,

				},

				context: this,

				success: function(response) {

					// $('.ajax-notify-order').show();

					// if(response.success) {

					// 	$('.ajax-notify-order').text('Lưu thành công!');

					// }

					// else {

					// 	$('.ajax-notify-order').text('Lưu thất bại');

					// }

					// setTimeout(function(){

					// 	$('.ajax-notify-order').hide();

					// }, 2000);
					console.log(response);

				}

			})

		});

		$("#salekit_auto_contact").change(function() {

			if (this.checked) {

				salekit_auto_contact = true;

			} else {

				salekit_auto_contact = false;

			}

			$.ajax({

				type: "post",

				dataType: "json",

				url: '<?php echo admin_url('admin-ajax.php'); ?>',

				data: {

					action: "salekit_option",

					salekit_auto_contact: salekit_auto_contact,

				},

				context: this,

				success: function(response) {



				}

			})

		});
	</script>

<?php

}

?>

<?php

//SalekitIo page

function salekit_io_page()
{

	$salekitio_token = get_option('salekitio_token', '');

	$salekitio_status = get_option('salekitio_status', '');

	$last_order_io = get_option('last_order_io', '');

	$last_time_oi = get_option('last_time_oi', '');

	$last_contact_io = get_option('last_contact_io', '');

	$last_contact_time_oi = get_option('last_contact_time_oi', '');

	if (isset($_POST['change-clicked'])) {

		update_option('salekitio_token', $_POST['salekitio_token']);

		$salekitio_token = get_option('salekitio_token', '');

		$url =   "https://salekit.io/api/v1/shop/verify";

		$data = array();

		$data_string = json_encode($data);

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		curl_setopt($curl, CURLOPT_HEADER, false);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(

			'Content-Type: application/json',

			'Token: ' . $_POST['salekitio_token'] . '',

		));

		$status_code = @curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$result = curl_exec($curl);

		curl_close($curl);

		$object = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true);

		if ($salekitio_token) {

			if (!$object['error']) {

				update_option('salekitio_status', $object['name']);

				update_option('salekitio_id_hash', $object['id_hash']);
			} else {

				update_option('salekitio_status', 'error');

				update_option('salekitio_id_hash', '');
			}
		} else {

			update_option('salekitio_status', '');

			update_option('salekitio_id_hash', '');
		}



		$salekitio_status = get_option('salekitio_status', '');
	}

?>

	<div id="salekit_admin" class="wrap">

		<div id="tab">

			<ul>

				<li class="tab1 active-tab"><a href="#tab1" data-tab="1">Giới thiệu</a></li>

				<li class="tab2"><a href="#tab2" data-tab="2">Đơn hàng</a></li>

				<li class="tab3"><a href="#tab3" data-tab="3">Contact</a></li>

				<li class="tab3"><a href="#tab4" data-tab="4">Goal</a></li>

			</ul>

		</div>

		<div id="data-container">
			<p>

			<div id="tab1" class="tab-content active">

				<p><?php esc_html_e('Salekit.io là nền tảng Chăm Sóc Khách Hàng Tự Động Đa Kênh: Email, SMS, Zalo ZnS, Webpush, AutoCall...', 'salekit'); ?></p>

				<p><?php esc_html_e('Bạn có thể đồng bộ khách hàng từ Woocommerce, Contact form về Salekit.io. ', 'salekit'); ?><a href="https://help.salekit.io/wordpress.html" target="_blank">Hướng dẫn</a></p>

				<form action="" method="post" style="margin-top:30px">

					<p>Nhập token shop của bạn. Lấy token <a href="https://salekit.io/shop/config?type=connect" target="_blank">tại đây</a></p>

					<input type="text" value="<?php echo esc_html($salekitio_token); ?>" id="salekitio_token" name="salekitio_token" placeholder="<?php _e('Ví dụ: 2248e3344a581df238098b4746f93111', 'salekit'); ?>" style="width: 300px; margin-right: 10px; height: 32px;"> <input type="submit" class="button button-primary button-large" value="<?php _e('Save', 'salekit'); ?>" style="" /><br />

					<p>Trạng thái: <strong><?php if ($salekitio_status == '') {
												echo "<span class='text-danger'>Chưa kết nối</span>";
											} elseif ($salekitio_status == 'error') {
												echo "<span class='text-danger'>Sai token</span";
											} else {
												echo "<span class='text-success'>Đã kết nối shop: " . $salekitio_status . '</span>';
											} ?></strong> <a style="margin-left:20px" href="https://salekit.io/audience" target="_blank">Đi tới shop >></a></p>



					<input name="change-clicked" type="hidden" value="1" />



				</form>

			</div>

			<div id="tab2" class="tab-content">

				<div class="ajax-notify-order" style="margin-bottom:10px"></div>

				<input type="checkbox" id="salekitio_auto_order" name="salekitio_auto_order" value="1" <?php checked('true', get_option('salekitio_auto_order')); ?> />

				<label for="salekitio_auto_order" style="vertical-align: bottom;"> Tự động đồng bộ thông tin đơn hàng từ
					Woocommerce

					> Salekit.io Thực hiện ngay khi khách hàng tạo đơn</label><br>

				<div class="order-info">

					<?php if ($last_order_io) { ?>

						<p><?php _e('Mã đơn đồng bộ cuối cùng: ', 'salekit'); ?><strong><?php echo esc_html($last_order_io); ?></strong>

						</p>

						<p><?php _e('Thời gian đồng bộ cuối cùng: ', 'salekit'); ?><strong><?php echo esc_html($last_time_oi); ?></strong>

						</p>

					<?php } ?>

				</div>

			</div>

			<div id="tab3" class="tab-content">

				<input type="checkbox" id="salekitio_auto_contact" name="salekitio_auto_contact" value="1" <?php checked('true', get_option('salekitio_auto_contact')); ?> />

				<label for="salekitio_auto_contact" style="vertical-align: bottom;"> Lưu thông tin liên hệ từ Contact Form
					về Salekit.io Thực hiện ngay khi khách hàng tạo gửi thông tin liên hệ</label><br>

				<div class="order-info">

					<?php if ($last_contact_io) { ?>

						<p><?php _e('Email đồng bộ cuối cùng: ', 'salekit'); ?><strong><?php echo esc_html($last_contact_io); ?></strong>

						</p>

						<p><?php _e('Thời gian đồng bộ cuối cùng: ', 'salekit'); ?><strong><?php echo esc_html($last_contact_time_oi); ?></strong>

						</p>

					<?php } ?>

				</div>

			</div>

			<div id="tab4" class="tab-content">

				<input type="checkbox" id="salekitio_auto_goal" name="salekitio_auto_goal" value="1" <?php checked('true', get_option('salekitio_auto_goal')); ?> />

				<label for="salekitio_auto_goal" style="vertical-align: bottom;"> Bật chế độ đo chuyển đổi Contact -> Đơn
					hàng và sinh ra sự kiện Trigger khi khách hàng truy cập vào trang Web của bạn</label><br>

			</div>

		</div>

	</div>

	<script type="text/javascript">
		$("#salekitio_auto_order").change(function() {

			if (this.checked) {

				salekitio_auto_order = true;

			} else {

				salekitio_auto_order = false;

			}

			$.ajax({

				type: "post",

				dataType: "json",

				url: '<?php echo admin_url('admin-ajax.php'); ?>',

				data: {

					action: "salekit_option",

					salekitio_auto_order: salekitio_auto_order,

				},

				context: this,

				success: function(response) {



				}

			})

		});

		$("#salekitio_auto_contact").change(function() {

			if (this.checked) {

				salekitio_auto_contact = true;

			} else {

				salekitio_auto_contact = false;

			}

			$.ajax({

				type: "post",

				dataType: "json",

				url: '<?php echo admin_url('admin-ajax.php'); ?>',

				data: {

					action: "salekit_option",

					salekitio_auto_contact: salekitio_auto_contact,

				},

				context: this,

				success: function(response) {



				}

			})

		});

		$("#salekitio_auto_goal").change(function() {

			if (this.checked) {

				salekitio_auto_goal = true;

			} else {

				salekitio_auto_goal = false;

			}

			$.ajax({

				type: "post",

				dataType: "json",

				url: '<?php echo admin_url('admin-ajax.php'); ?>',

				data: {

					action: "salekit_option",

					salekitio_auto_goal: salekitio_auto_goal,

				},

				context: this,

				success: function(response) {



				}

			})

		});
	</script>

<?php

}

?>

<?php

//Checkout page

function salekit_checkout_page()
{


	$salekit_checkout_api = get_option('salekit_checkout_api', '');

	$salekit_checkout_token = get_option('salekit_checkout_token', '');

	$checkout_token_status = get_option('checkout_token_status', '');

	if (isset($_POST['change-clicked'])) {

		update_option('salekit_checkout_token', $_POST['salekit_checkout_token']);

		$salekit_checkout_token = get_option('salekit_checkout_token', '');

		update_option('salekit_checkout_api', $_POST['salekit_checkout_api']);

		$salekit_checkout_api = get_option('salekit_checkout_api', '');



		$url =   "https://checkout.vn/api/v1/shop/verify/" . $_POST['salekit_checkout_token'];

		$data = array();

		$data_string = json_encode($data);

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_HEADER, false);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(

			'Content-Type: application/json',

		));

		$status_code = @curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$result = curl_exec($curl);

		curl_close($curl);

		$object = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true);

		if ($_POST['salekit_checkout_token'] && $_POST['salekit_checkout_api']) {

			if (!$object['error']) {

				update_option('checkout_token_status', $object['name']);
			} else {

				update_option('checkout_token_status', 'error');
			}
		} else {

			update_option('checkout_token_status', '');
		}



		$checkout_token_status = get_option('checkout_token_status', '');
	}

?>

	<div id="salekit_admin" class="wrap">

		<p><strong>Checkout.vn</strong> là nơi kết nối đến các cổng thanh toán online và ví điện tử</p>

		<form action="" method="post" style="margin-top:30px">

			<p>Bước 1: Vào <a href="https://checkout.vn/" target="_blank">Checkout.vn</a>, đăng ký và tạo shop mới</p>

			<p>Bước 2: Thiết lập các kênh thanh toán mà bạn có theo hướng dẫn</p>

			<p>Bước 3: Lấy API key và token shop <a href="https://checkout.vn/setting/api-key" target="_blank">tại đây</a>
				và nhập vào ô bên dưới</p>

			<input type="text" value="<?php echo esc_html($salekit_checkout_api); ?>" id="salekit_checkout_api" name="salekit_checkout_api" placeholder="<?php _e('Nhập API Key kết nối trên Checkout.vn', 'salekit'); ?>" style="width: 400px; margin-top: 10px; height: 32px;"><br>

			<input type="text" value="<?php echo esc_html($salekit_checkout_token); ?>" id="salekit_checkout_token" name="salekit_checkout_token" placeholder="<?php _e('Nhập Token kết nối trên Checkout.vn', 'salekit'); ?>" style="width: 400px; margin: 10px 0; height: 32px;"><br>

			<input type="submit" class="button button-primary button-large" value="<?php _e('Save', 'salekit'); ?>" style="" /><br />

			<p>Trạng thái: <strong><?php if ($checkout_token_status == '') {
										echo "<span class='text-danger'>Chưa kết nối</span>";
									} elseif ($checkout_token_status == 'error') {
										echo "<span class='text-danger'>Sai token</span";
									} else {
										echo "<span class='text-success'>Đã kết nối shop: " . $checkout_token_status . '</span>';
									} ?></strong> <a style="margin-left:20px" href="https://checkout.vn/dashboard" target="_blank">Đi tới shop >></a></p>

			<p>Sau khi lưu token và API key, bạn sẽ thấy tùy chọn Checkout.vn trên trang cài đặt <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=checkout'); ?>" target="_blank">Thanh toán Woocommerce</a></p>



			<input name="change-clicked" type="hidden" value="1" />



		</form>

	</div>



<?php

}

?>

<?php

//Webpush page

function salekit_webpush_page()
{

	$textvar = get_option('salekit_plugin_variable', '');

	$salekit_webpushid = get_option('salekit_webpushid', '');

	if (isset($_POST['change-clicked2'])) {

		update_option('salekit_webpushid', $_POST['salekit_webpushid']);

		$salekit_webpushid = get_option('salekit_webpushid', '');
	}

?>

	<div id="salekit_admin" class="wrap">

		<div class="tab-content">

			<p><strong>Webpush Notification</strong> là tính năng gửi thông báo cho người dùng đăng ký (subscriber)</p>

			<form action="" method="post" style="margin-top:30px">

				<p>Bạn vui lòng truy cập webpush.vn, đăng nhập, và đăng ký website Nhập Website ID của bạn. Lấy Website ID
					<a href="https://webpush.vn/setting/embed" target="_blank">tại đây</a>
				</p>

				<input type="text" value="<?php echo esc_html($salekit_webpushid); ?>" id="salekit_webpushid" name="salekit_webpushid" placeholder="<?php _e('Ví dụ: 5fb335f92ceb91212505013c', 'salekit'); ?>" style="width: 300px; margin-right: 10px; height: 32px;"> <input type="submit" class="button button-primary button-large" value="<?php _e('Save', 'salekit'); ?>" style="" /><br />

				<p>Sau khi lưu Website ID, bạn mở website, chờ 3 giây sẽ thấy thông báo hiện lên</p>



				<input name="change-clicked2" type="hidden" value="1" />



			</form>

		</div>

	</div>



<?php

}

?>

<?php

//Fchat page

function salekit_fchat_page()
{

	$textvar = get_option('salekit_plugin_variable', '');

	$salekit_fchat = get_option('salekit_fchat', '');

	if (isset($_POST['change-clicked3'])) {

		update_option('salekit_fchat', $_POST['salekit_fchat']);

		$salekit_fchat = get_option('salekit_fchat', '');
	}

?>

	<div id="salekit_admin" class="wrap">

		<div class="tab-content">

			<p><strong>Fchat</strong> là nền tảng chat và chatbot Đa kênh: website, fanpage, zalo, sàn tmđt,..</p>

			<form action="" method="post" style="margin-top:30px">

				<p>Bạn vui lòng truy cập <strong>fchat.vn</strong>, đăng nhập, và đăng ký website<br>Nhập token website của
					bạn. Lấy token <a href="https://fchat.vn/setting?type=embed" target="_blank">tại đây</a></p>

				<input type="text" value="<?php echo esc_html($salekit_fchat); ?>" id="salekit_fchat" name="salekit_fchat" placeholder="<?php _e('Ví dụ: 5fb335f92ceb91212505013c', 'salekit'); ?>" style="width: 300px; margin-right: 10px; height: 32px;"> <input type="submit" class="button button-primary button-large" value="<?php _e('Save', 'salekit'); ?>" style="" /><br />

				<p>Sau khi lưu token, bạn mở website, sẽ thấy ô chat nằm ở góc phải bên dưới</p>



				<input name="change-clicked3" type="hidden" value="1" />



			</form>

		</div>

	</div>



<?php

}

?>