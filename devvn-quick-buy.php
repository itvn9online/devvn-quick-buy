<?php
/*
 * Plugin Name: Echbay Quick Buy
 * Version: 2.2.4
 * Description: Echbay Quick Buy là plugin giúp khách hàng có thể mua nhanh sản phẩm ngay tại trang chi tiết dưới dạng popup. Tương thích với WooCommerce HPOS.
 * Author: Dao Quoc Dai
 * Author URI: https://github.com/itvn9online/devvn-quick-buy
 * Plugin URI: https://github.com/itvn9online/devvn-quick-buy
 * Text Domain: devvn-quickbuy
 * Domain Path: /languages
 * WC requires at least: 3.5.4
 * WC tested up to: 8.0.0
 * Requires Plugins: woocommerce
 */
defined('ABSPATH') or die('No script kiddies please!');
// if (is_multisite() || in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
if (!class_exists('DevVN_Quick_Buy')) {
    class DevVN_Quick_Buy
    {
        protected static $instance;
        public $_version = '2.2.4';
        public $_optionName = 'quickbuy_options';
        public $_optionGroup = 'quickbuy-options-group';
        public $_defaultOptions = array(
            'enable' => '1',
            'enable_ship' => '',
            'enable_coupon' => 0,
            'hidden_email' => '0',
            'require_email' => '0',
            'require_district' => '0',
            'require_village' => '0',
            'require_address' => '0',
            'enable_location' => '',
            'enable_payment' => '0',
            'popup_infor_enable' => '1',
            'hidden_note' => '0',
            'hidden_address' => '0',
            'valid_phone' => '0',
            'in_loop_prod' => '0',
            'button_text1' => 'Mua ngay',
            'button_text2' => 'Gọi điện xác nhận và giao hàng tận nơi',
            'popup_title' => 'Đặt mua %s',
            'popup_gotothankyou' => '0',
            'popup_mess' => 'Bạn vui lòng nhập đúng số điện thoại để chúng tôi sẽ gọi xác nhận đơn hàng trước khi giao hàng. Xin cảm ơn!',
            'popup_sucess' => '<div class="popup-message success" style="color:#333;"><p class="clearfix" style="font-size:22px;color: #00c700;text-align:center">Đặt hàng thành công!</p><p class="clearfix" style="color: #00c700;padding: 10px 0;">Mã đơn hàng <span style="color: #333;font-weight: bold">#%%order_id%%</span></p><p class="clearfix">DevVN SHOP sẽ liên hệ với bạn trong 12h tới. Cám ơn bạn đã cho chúng tôi cơ hội được phục vụ.<br><strong>Hotline:</strong> 0936.xxx.xxx</p><div></div><div></div></div>',
            'popup_error' => 'Đặt hàng thất bại. Vui lòng đặt hàng lại. Xin cảm ơn!',
            'out_of_stock_mess' => 'Hết hàng!',
        );

        public static function init()
        {
            is_null(self::$instance) and self::$instance = new self;
            return self::$instance;
        }

        public function __construct()
        {
            $this->define_constants();
            global $quickbuy_settings;
            $quickbuy_settings = $this->get_dvlsoptions();
            add_filter('plugin_action_links_' . DEVVN_QB_BASENAME, array($this, 'add_action_links'), 10, 2);
            add_action('admin_menu', array($this, 'admin_menu'));
            add_action('admin_init', array($this, 'dvls_register_mysettings'));
            add_action('plugins_loaded', array($this, 'dvls_load_textdomain'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

            // Declare HPOS compatibility
            add_action('before_woocommerce_init', array($this, 'declare_hpos_compatibility'));

            add_filter('woocommerce_quantity_input_args', array($this, 'devvn_woocommerce_quantity_input_args'));

            if ($quickbuy_settings['enable']) {
                add_action('wp_enqueue_scripts', array($this, 'load_plugins_scripts'));

                add_shortcode('devvn_quickbuy', array($this, 'devvn_button_quick_buy'));
                add_shortcode('devvn_quickbuy_form', array($this, 'devvn_form_quick_buy'));

                add_action('woocommerce_after_add_to_cart_button', array($this, 'add_button_quick_buy'), 5);
                add_action('woocommerce_after_single_product', array($this, 'quick_buy_popup_content_single'));

                add_action('wp_ajax_devvn_quickbuy', array($this, 'devvn_quickbuy_func'));
                add_action('wp_ajax_nopriv_devvn_quickbuy', array($this, 'devvn_quickbuy_func'));

                add_action('wp_ajax_devvn_apply_coupon', array($this, 'devvn_apply_coupon_func'));
                add_action('wp_ajax_nopriv_devvn_apply_coupon', array($this, 'devvn_apply_coupon_func'));

                add_action('wp_ajax_devvn_form_quickbuy', array($this, 'devvn_form_quickbuy_func'));
                add_action('wp_ajax_nopriv_devvn_form_quickbuy', array($this, 'devvn_form_quickbuy_func'));

                add_action('wp_ajax_quickbuy_load_diagioihanhchinh', array($this, 'load_diagioihanhchinh_func'));
                add_action('wp_ajax_nopriv_quickbuy_load_diagioihanhchinh', array($this, 'load_diagioihanhchinh_func'));

                add_action('devvn_prod_variable', 'woocommerce_template_single_add_to_cart');
                if ($quickbuy_settings['in_loop_prod']) {
                    add_action('woocommerce_after_shop_loop_item', array($this, 'add_quick_buy_to_loop_func'), 15);
                }
            }

            // chỉ include update khi ở trang /plugins.php
            if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false) {
                include_once('includes/updates.php');
            }
        }

        public function define_constants()
        {
            defined('DEVVN_QB_VERSION_NUM') || define('DEVVN_QB_VERSION_NUM', $this->_version);
            defined('DEVVN_QB_URL') || define('DEVVN_QB_URL', plugin_dir_url(__FILE__));
            defined('DEVVN_QB_BASENAME') || define('DEVVN_QB_BASENAME', plugin_basename(__FILE__));
            defined('DEVVN_QB_PLUGIN_DIR') || define('DEVVN_QB_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }

        function dvls_load_textdomain()
        {
            load_textdomain('devvn-quickbuy', dirname(__FILE__) . '/languages/devvn-quickbuy-' . get_locale() . '.mo');
        }

        function devvn_button_quick_buy($atts)
        {
            $result = '';
            include __DIR__ . '/includes/devvn_button_quick_buy.php';
            return $result;
        }

        function devvn_form_quickbuy_func()
        {
            $prodID = isset($_POST['prodid']) ? intval($_POST['prodid']) : '';
            if ($prodID) {
                $args = array(
                    'id' => $prodID,
                );
                $this->quick_buy_popup_content($args, true, true);
            }
            die();
        }

        function form_popup_content($args = array())
        {
            include __DIR__ . '/includes/form_popup_content.php';
        }

        function quick_buy_popup_content_single()
        {
            global $product;
            $args = array(
                'id' => $product->get_id()
            );
            $this->quick_buy_popup_content($args, true);
        }

        function quick_buy_popup_content($args = array(), $view = true, $f_ajax = false)
        {
            include __DIR__ . '/includes/quick_buy_popup_content.php';
        }

        function check_plugin_active($base_plugin = 'devvn-woo-address-selectbox/devvn-woo-address-selectbox.php')
        {
            if (
                in_array($base_plugin, apply_filters('active_plugins', get_option('active_plugins'))) ||
                in_array('woo-vietnam-checkout/devvn-woo-address-selectbox.php', apply_filters('active_plugins', get_option('active_plugins'))) ||
                in_array('devvn-woo-ghtk/devvn-woo-ghtk.php', apply_filters('active_plugins', get_option('active_plugins'))) ||
                in_array('devvn-vietnam-shipping/devvn-vietnam-shipping.php', apply_filters('active_plugins', get_option('active_plugins')))
            ) {
                return true;
            }
            return false;
        }

        function add_button_quick_buy()
        {
            global $product;
            echo do_shortcode('[devvn_quickbuy view="0" id="' . $product->get_id() . '"]');
        }

        function add_quick_buy_to_loop_func()
        {
            global $product;
            echo do_shortcode('[devvn_quickbuy small_link="1" id="' . $product->get_id() . '"]');
        }

        function devvn_get_rates($package = array(), $product_info = array())
        {
            $available_methods = $old_cart_key = array();
            $package = wp_parse_args(
                $package,
                array(
                    'country' => 'VN',
                    'state' => '01',
                    'city' => '',
                    'postcode' => '',
                )
            );
            $old_cart = WC()->cart->get_cart_contents();
            if ($old_cart && !empty($old_cart)) {
                foreach ($old_cart as $k => $cartItem) {
                    $old_cart_key[] = $k;
                    WC()->cart->remove_cart_item($k);
                }
            }
            if ($product_info && is_array($product_info) && !empty($product_info)) {
                $product_id = isset($product_info['product_id']) ? intval($product_info['product_id']) : '';
                if (!$product_id) {
                    $product_id = isset($product_info['add-to-cart']) ? intval($product_info['add-to-cart']) : '';
                }
                $quantity = isset($product_info['quantity']) ? intval($product_info['quantity']) : 0;
                $variation_id = isset($product_info['variation_id']) ? intval($product_info['variation_id']) : '';
                if ($product_id) {
                    if ($variation_id && $variation_id != "" && $variation_id > 0) {
                        $variation = array();
                        foreach ($product_info as $k => $v) {
                            if (strpos($k, 'attribute_') !== false) {
                                $variation[$k] = $product_info[$k];
                            }
                        }
                        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
                    } else {
                        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
                    }
                    WC()->customer->set_billing_location($package['country'], $package['state'], $package['postcode'], $package['city']);
                    WC()->customer->set_shipping_location($package['country'], $package['state'], $package['postcode'], $package['city']);
                    $packages = WC()->cart->get_shipping_packages();
                    $packages = WC()->shipping->calculate_shipping($packages);
                    $available_methods = WC()->shipping->get_packages();
                    $available_methods = isset($available_methods[0]['rates']) ? $available_methods[0]['rates'] : array();

                    WC()->cart->remove_cart_item($cart_item_key);
                }
                if ($old_cart && !empty($old_cart)) {
                    foreach ($old_cart as $k => $cartItem) {
                        $product_id = isset($cartItem['product_id']) ? intval($cartItem['product_id']) : '';
                        $variation_id = isset($cartItem['variation_id']) ? intval($cartItem['variation_id']) : '';
                        $variation = isset($cartItem['variation']) ? wc_clean($cartItem['variation']) : array();
                        $quantity = isset($cartItem['quantity']) ? intval($cartItem['quantity']) : '';
                        if ($product_id) {
                            if ($variation_id > 0) {
                                WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
                            } else {
                                WC()->cart->add_to_cart($product_id, $quantity);
                            }
                        }
                    }
                }
            }
            wc_clear_notices();
            return $available_methods;
        }

        function check_product_incart($product_info = array())
        {
            $product_id = isset($product_info['product_id']) ? intval($product_info['product_id']) : '';
            $variation_id = isset($product_info['variation_id']) ? intval($product_info['variation_id']) : '';
            foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
                $_product_id = isset($values['product_id']) ? $values['product_id'] : '';
                $_variation_id = isset($values['variation_id']) ? $values['variation_id'] : '';
                if (($product_id == $_product_id && $_variation_id == '') || ($product_id == $_product_id && $_variation_id && $variation_id == $_variation_id)) {
                    return $cart_item_key;
                }
            }
            return false;
        }

        function get_productkey_incart($product_id)
        {
            foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
                $_product = $values['data'];
                if ($product_id == $_product->id) {
                    return true;
                }
            }
            return false;
        }

        function devvn_calculate_shipping($package = array(), $product_info = array())
        {
            $result = '';
            include __DIR__ . '/includes/devvn_calculate_shipping.php';
            return $result;
        }

        function load_diagioihanhchinh_func()
        {
            global $quickbuy_settings;
            if (
                !class_exists('Woo_Address_Selectbox_Class') &&
                !class_exists('DevVN_Woo_Vietnam_Checkout_Class') &&
                !class_exists('DevVN_Woo_GHTK_Class')
            )
                wp_send_json_error();
            if (class_exists('Woo_Address_Selectbox_Class')) {
                $address = new Woo_Address_Selectbox_Class;
            } elseif (class_exists('DevVN_Woo_Vietnam_Checkout_Class')) {
                $address = new DevVN_Woo_Vietnam_Checkout_Class;
            } else {
                $address = new DevVN_Woo_GHTK_Class;
            }
            $matp = isset($_POST['matp']) ? sanitize_text_field($_POST['matp']) : '';
            $maqh = isset($_POST['maqh']) ? intval($_POST['maqh']) : '';
            $getvalue = isset($_POST['getvalue']) ? intval($_POST['getvalue']) : 1;
            $result['shipping'] = '';
            if ($quickbuy_settings['enable_ship']) {
                $package['country'] = 'VN';
                $package['state'] = sanitize_text_field($matp);
                $package['city'] = sprintf("%03d", $maqh);
                parse_str(wc_clean($_POST['product_info']), $product_info);
                if (!isset($product_info['add-to-cart']) && isset($_POST['prod_id'])) {
                    $prod_id = isset($_POST['prod_id']) ? intval($_POST['prod_id']) : '';
                    $product_info['add-to-cart'] = $prod_id;
                }
                $result['shipping'] = $this->devvn_calculate_shipping($package, $product_info);
            }
            if ($getvalue == 1 && $matp) {
                $result['list_district'] = $address->get_list_district($matp);
                wp_send_json_success($result);
            } elseif ($getvalue == 2 && $maqh) {
                $result['list_district'] = $address->get_list_village($maqh);
                wp_send_json_success($result);
            }
            wp_send_json_error();
            die();
        }

        function devvn_quickbuy_func()
        {

            global $quickbuy_settings;
            $prod_id = isset($_POST['prod_id']) ? intval($_POST['prod_id']) : '';
            $prod_check = wc_get_product($prod_id);

            if (!$prod_check || is_wp_error($prod_check))
                wp_send_json_error();

            parse_str($_POST['customer_info'], $customer_info);
            parse_str($_POST['product_info'], $product_info);

            $qty = isset($product_info['quantity']) ? (float) $product_info['quantity'] : 1;
            $variation_id = isset($product_info['variation_id']) ? (float) $product_info['variation_id'] : '';
            $product_id = isset($product_info['product_id']) ? (int) $product_info['product_id'] : '';
            if (!$product_id) {
                if (!isset($product_info['add-to-cart']) && $prod_id) {
                    $product_info['add-to-cart'] = $prod_id;
                }
                $product_id = isset($product_info['add-to-cart']) ? (int) $product_info['add-to-cart'] : '';
            }

            $customer_gender = (isset($customer_info['customer-gender']) && $customer_info['customer-gender'] == 1) ? 'Anh' : 'Chị';
            $customer_name = isset($customer_info['customer-name']) ? sanitize_text_field($customer_info['customer-name']) : '';
            $customer_email = isset($customer_info['customer-email']) ? sanitize_email($customer_info['customer-email']) : '';
            $customer_phone = isset($customer_info['customer-phone']) ? sanitize_text_field($customer_info['customer-phone']) : '';
            $customer_address = isset($customer_info['customer-address']) ? sanitize_textarea_field($customer_info['customer-address']) : '';
            $customer_location = isset($customer_info['customer-location']) ? sanitize_text_field($customer_info['customer-location']) : '';
            $customer_note = isset($customer_info['order-note']) ? sanitize_textarea_field($customer_info['order-note']) : '';
            $customer_quan = isset($customer_info['customer-quan']) ? sanitize_text_field($customer_info['customer-quan']) : '';
            $customer_xa = isset($customer_info['customer-xa']) ? sanitize_text_field($customer_info['customer-xa']) : '';
            $shipping_method = isset($customer_info['shipping_method']) ? $customer_info['shipping_method'] : '';
            $order_total = isset($customer_info['order_total']) ? $customer_info['order_total'] : 0;

            $customer_coupon = isset($customer_info['customer-coupon']) ? sanitize_text_field(wp_unslash($customer_info['customer-coupon'])) : '';
            $customer_coupon_val = isset($customer_info['coupon_amout_val']) ? sanitize_text_field(wp_unslash($customer_info['coupon_amout_val'])) : '';

            $customer_paymentmethob = (isset($customer_info['customer-paymentmethob'])) ? sanitize_text_field($customer_info['customer-paymentmethob']) : '';

            $address = array(
                'first_name' => $customer_gender,
                'last_name' => $customer_name,
                'email' => $customer_email,
                'phone' => $customer_phone,
                'address_1' => $customer_address,
                'state' => $customer_location,
                'city' => $customer_quan,
                'address_2' => $customer_xa,
                'country' => 'VN'
            );

            // Now we create the order
            $order = new WC_Order();

            if (!is_wp_error($order)) {
                $args = $variation = array();
                if ($variation_id && is_array($product_info)) {
                    foreach ($product_info as $k => $v) {
                        if (strpos($k, 'attribute_') !== false) {
                            $variation[$k] = $product_info[$k];
                        }
                    }
                    if ($variation) {
                        $args = array(
                            'variation_id' => $variation_id,
                            'variation' => $variation,
                            'product_id' => $product_id
                        );
                    }
                    $prod_check = wc_get_product($variation_id);
                }
                $order->add_product($prod_check, $qty, $args);
                $order->set_address($address, 'billing');
                $order->set_address($address, 'shipping');

                // Store gender meta data for HPOS compatibility
                $gender_value = ($customer_gender == 'Anh') ? 'male' : 'female';
                $order->update_meta_data('_billing_gender', $gender_value);

                if ($customer_note) {
                    $order->set_customer_note($customer_note);
                }

                if ($quickbuy_settings['enable_payment'] && $customer_paymentmethob) {
                    // Set payment gateway
                    $payment_gateways = WC()->payment_gateways->payment_gateways();
                    $order->set_payment_method($payment_gateways[$customer_paymentmethob]);
                }

                $package = array();
                $package['country'] = 'VN';
                $package['state'] = sanitize_text_field($customer_location);
                if ($customer_quan) {
                    $package['city'] = sprintf("%03d", $customer_quan);
                }

                if ($quickbuy_settings['enable_ship'] && $shipping_method) {
                    $item = new WC_Order_Item_Shipping();

                    $available_methods = $this->devvn_get_rates($package, $product_info);

                    $shipping_rate = isset($available_methods[$shipping_method[0]]) ? $available_methods[$shipping_method[0]] : array();
                    if ($shipping_rate) {
                        $item->set_props(
                            array(
                                'method_title' => $shipping_rate->label,
                                'method_id' => $shipping_rate->id,
                                'total' => wc_format_decimal($shipping_rate->cost),
                                'taxes' => $shipping_rate->taxes,
                                'order_id' => $order->get_id(),
                            )
                        );
                        foreach ($shipping_rate->get_meta_data() as $key => $value) {
                            $item->add_meta_data($key, $value, true);
                        }
                        $item->save();
                        $order->add_item($item);
                    }
                }

                if ($quickbuy_settings['enable_coupon'] && $customer_coupon && $customer_coupon_val) {
                    $order->apply_coupon($customer_coupon);
                }

                $order->set_total($order_total);

                $order->calculate_totals();

                $valid_order_statuses = apply_filters('devvn_quickbuy_status_processing', array('cod'));
                if (in_array($customer_paymentmethob, $valid_order_statuses)) {
                    $order->update_status("processing", 'Đơn hàng từ plugin MUA HÀNG NHANH', TRUE);
                } else {
                    $order->update_status("on-hold", 'Đơn hàng từ plugin MUA HÀNG NHANH', TRUE);
                }

                if ($this->check_woo_version()) {
                    wc_reduce_stock_levels($order->get_id());
                }

                if (is_user_logged_in()) {
                    // Use HPOS compatible method
                    $order->set_customer_id(get_current_user_id());
                }

                do_action('woocommerce_checkout_order_processed', $order->get_id(), array(), $order);

                $result['content'] = str_replace('%%order_id%%', $order->get_order_number(), $quickbuy_settings['popup_sucess']);
                $result['gotothankyou'] = ($quickbuy_settings['popup_gotothankyou'] == 1) ? true : false;

                if ($quickbuy_settings['enable_payment'] && ($order->needs_payment() || !in_array($customer_paymentmethob, $valid_order_statuses))) {
                    $payment_method = $customer_paymentmethob;
                    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

                    $results = $available_gateways[$payment_method]->process_payment($order->get_id());

                    // Redirect to success/confirmation/payment page
                    if ('success' === $results['result']) {
                        $result['gotothankyou'] = true;
                        $result['thankyou_link'] = $results['redirect'];
                    }
                } else {
                    $result['thankyou_link'] = $order->get_checkout_order_received_url();
                }

                wp_send_json_success($result);
            }
            wp_send_json_error();
            die();
        }

        /**
         * Declare HPOS compatibility
         */
        public function declare_hpos_compatibility()
        {
            if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
            }
        }

        /**
         * Check if HPOS is enabled
         */
        public function is_hpos_enabled()
        {
            if (class_exists('\Automattic\WooCommerce\Utilities\OrderUtil')) {
                return \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
            }
            return false;
        }

        /**
         * Display admin notice about HPOS compatibility
         */
        public function hpos_compatibility_notice()
        {
            if ($this->is_hpos_enabled()) {
                echo '<div class="notice notice-success"><p><strong>Echbay Quick Buy:</strong> Plugin đã tương thích với WooCommerce High-Performance Order Storage (HPOS).</p></div>';
            }
        }

        public function check_woo_version($version = '3.0.0')
        {
            if (defined('WOOCOMMERCE_VERSION') && version_compare(WOOCOMMERCE_VERSION, $version, '>=')) {
                return true;
            }
            return false;
        }

        public function add_action_links($links, $file)
        {
            if (strpos($file, 'devvn-quick-buy.php') !== false) {
                $settings_link = '<a href="' . admin_url('options-general.php?page=quickkbuy-setting') . '" title="' . __('Settings', 'devvn-quickbuy') . '">' . __('Settings', 'devvn-quickbuy') . '</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }

        function load_plugins_scripts()
        {
            global $quickbuy_settings;
            wp_enqueue_style('devvn-quickbuy-style', plugins_url('css/devvn-quick-buy.css', __FILE__), array(), $this->_version, 'all');
            wp_enqueue_script('jquery.validate', plugins_url('js/jquery.validate.min.js', __FILE__), array('jquery'), $this->_version, true);
            wp_enqueue_script('devvn-quickbuy-script', plugins_url('js/devvn-quick-buy.js', __FILE__), array('jquery', 'wc-add-to-cart-variation'), $this->_version, true);
            $array = array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'siteurl' => home_url(),
                'popup_error' => $quickbuy_settings['popup_error'],
                'out_of_stock_mess' => $quickbuy_settings['out_of_stock_mess'],
                'price_decimal' => wc_get_price_decimal_separator(),
                'num_decimals' => wc_get_price_decimals(),
                'price_thousand' => wc_get_price_thousand_separator(),
                'currency_format' => get_woocommerce_currency_symbol(),
                'qty_text' => __('Quantity', 'devvn-quickbuy'),
                'name_text' => __('Full name is required', 'devvn-quickbuy'),
                'phone_text' => __('Phone number is required', 'devvn-quickbuy'),
                'valid_phone_text' => __('Confirm your phone number is required', 'devvn-quickbuy'),
                'valid_phone_text_equalto' => __('Please enter the same phone number again', 'devvn-quickbuy'),
                'email_text' => __('Email is required', 'devvn-quickbuy'),
                'quan_text' => __('District is required', 'devvn-quickbuy'),
                'xa_text' => __('Ward is required', 'devvn-quickbuy'),
                'address_text' => __('Stress is required', 'devvn-quickbuy')
            );
            wp_localize_script('devvn-quickbuy-script', 'devvn_quickbuy_array', $array);
        }

        public function admin_enqueue_scripts()
        {
            $current_screen = get_current_screen();
            if (isset($current_screen->base) && $current_screen->base == 'settings_page_quickkbuy-setting') {
                wp_enqueue_style('devvn-quickbuy-admin-styles', plugins_url('/css/admin-style.css', __FILE__), array(), $this->_version, 'all');
                wp_enqueue_script('devvn-quickbuy-admin-js', plugins_url('/js/admin-jquery.js', __FILE__), array('jquery'), $this->_version, true);
            }
        }

        function get_dvlsoptions()
        {
            return wp_parse_args(get_option($this->_optionName), $this->_defaultOptions);
        }

        function admin_menu()
        {
            add_options_page(
                __('Quick Buy Setting', 'devvn-quickbuy'),
                __('Quick Buy Setting', 'devvn-quickbuy'),
                'manage_options',
                'quickkbuy-setting',
                array(
                    $this,
                    'devvn_settings_page'
                )
            );
        }

        function dvls_register_mysettings()
        {
            register_setting($this->_optionGroup, $this->_optionName);
        }

        function devvn_settings_page()
        {
            global $quickbuy_settings;
            // print_r($quickbuy_settings);
            // $quickbuy_settings = $this->get_dvlsoptions();
            // print_r($quickbuy_settings);
            // print_r(wp_parse_args(get_option($this->_optionName)));
            // echo 'aaaaaaaa';
            include __DIR__ . '/includes/devvn_settings_page.php';
        }

        function devvn_form_quick_buy($atts)
        {
            return false;
        }

        function devvn_apply_coupon_func()
        {
            wp_send_json_success();
            die();
        }
        function devvn_apply_coupons($thisCoupon = '', $package = array(), $product_info = array())
        {

            $thisCoupon = strtolower($thisCoupon);

            $coupon_note = $old_cart_key = array();
            $package = wp_parse_args(
                $package,
                array(
                    'country' => 'VN',
                )
            );
            $old_cart = WC()->cart->get_cart_contents();
            if ($old_cart && !empty($old_cart)) {
                foreach ($old_cart as $k => $cartItem) {
                    $old_cart_key[] = $k;
                    WC()->cart->remove_cart_item($k);
                }
            }
            if ($product_info && is_array($product_info) && !empty($product_info)) {
                $product_id = isset($product_info['product_id']) ? intval($product_info['product_id']) : '';
                if (!$product_id) {
                    $product_id = isset($product_info['add-to-cart']) ? intval($product_info['add-to-cart']) : '';
                }
                $quantity = isset($product_info['quantity']) ? intval($product_info['quantity']) : 0;
                $variation_id = isset($product_info['variation_id']) ? intval($product_info['variation_id']) : '';
                if ($product_id) {
                    if ($variation_id && $variation_id != "" && $variation_id > 0) {
                        $variation = array();
                        foreach ($product_info as $k => $v) {
                            if (strpos($k, 'attribute_') !== false) {
                                $variation[$k] = $product_info[$k];
                            }
                        }
                        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
                    } else {
                        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
                    }

                    WC()->cart->add_discount($thisCoupon);
                    ob_start();
                    wc_print_notices();
                    $coupon_note['mess'] = ob_get_clean();
                    $coupon_note['total_discount'] = WC()->cart->get_coupon_discount_amount($thisCoupon);

                    WC()->cart->remove_cart_item($cart_item_key);
                }
                if ($old_cart && !empty($old_cart)) {
                    foreach ($old_cart as $k => $cartItem) {
                        $product_id = isset($cartItem['product_id']) ? intval($cartItem['product_id']) : '';
                        $variation_id = isset($cartItem['variation_id']) ? intval($cartItem['variation_id']) : '';
                        $variation = isset($cartItem['variation']) ? wc_clean($cartItem['variation']) : array();
                        $quantity = isset($cartItem['quantity']) ? intval($cartItem['quantity']) : '';
                        if ($product_id) {
                            if ($variation_id > 0) {
                                WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
                            } else {
                                WC()->cart->add_to_cart($product_id, $quantity);
                            }
                        }
                    }
                }
            }
            wc_clear_notices();
            return $coupon_note;
        }

        function quickbuy_get_cart($package, $product_info)
        {
            $cart = $old_cart_key = array();
            $package = wp_parse_args(
                $package,
                array(
                    'country' => 'VN',
                    'state' => '01',
                    'city' => '',
                    'postcode' => '',
                )
            );
            $old_cart = WC()->cart->get_cart_contents();
            if ($old_cart && !empty($old_cart)) {
                foreach ($old_cart as $k => $cartItem) {
                    $old_cart_key[] = $k;
                    WC()->cart->remove_cart_item($k);
                }
            }
            if ($product_info && is_array($product_info) && !empty($product_info)) {
                $product_id = isset($product_info['product_id']) ? intval($product_info['product_id']) : '';
                if (!$product_id) {
                    $product_id = isset($product_info['add-to-cart']) ? intval($product_info['add-to-cart']) : '';
                }
                $quantity = isset($product_info['quantity']) ? intval($product_info['quantity']) : 0;
                $variation_id = isset($product_info['variation_id']) ? intval($product_info['variation_id']) : '';
                if ($product_id) {
                    if ($variation_id && $variation_id != "" && $variation_id > 0) {
                        $variation = array();
                        foreach ($product_info as $k => $v) {
                            if (strpos($k, 'attribute_') !== false) {
                                $variation[$k] = $product_info[$k];
                            }
                        }
                        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
                    } else {
                        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
                    }
                    WC()->customer->set_billing_location($package['country'], $package['state'], $package['postcode'], $package['city']);
                    WC()->customer->set_shipping_location($package['country'], $package['state'], $package['postcode'], $package['city']);

                    $cart = WC()->cart;
                }
                if ($old_cart && !empty($old_cart)) {
                    foreach ($old_cart as $k => $cartItem) {
                        $product_id = isset($cartItem['product_id']) ? intval($cartItem['product_id']) : '';
                        $variation_id = isset($cartItem['variation_id']) ? intval($cartItem['variation_id']) : '';
                        $variation = isset($cartItem['variation']) ? wc_clean($cartItem['variation']) : array();
                        $quantity = isset($cartItem['quantity']) ? intval($cartItem['quantity']) : '';
                        if ($product_id) {
                            if ($variation_id > 0) {
                                WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
                            } else {
                                WC()->cart->add_to_cart($product_id, $quantity);
                            }
                        }
                    }
                }
            }
            return $cart;
        }

        function devvn_woocommerce_quantity_input_args($args)
        {
            if (isset($args['product_name']))
                $args['product_name'] = '';
            return $args;
        }

        /**
         * Get order meta data compatible with HPOS
         */
        public function get_order_meta($order_id, $key, $single = true)
        {
            $order = wc_get_order($order_id);
            if ($order) {
                return $order->get_meta($key, $single);
            }
            return get_post_meta($order_id, $key, $single);
        }

        /**
         * Update order meta data compatible with HPOS
         */
        public function update_order_meta($order_id, $key, $value)
        {
            $order = wc_get_order($order_id);
            if ($order) {
                $order->update_meta_data($key, $value);
                $order->save();
            } else {
                update_post_meta($order_id, $key, $value);
            }
        }
    }

    $devvn_quickbuy = new DevVN_Quick_Buy();
}
// }


if (!function_exists('devvn_woocommerce_localisation_address_formats_vn')) {
    add_filter('woocommerce_localisation_address_formats', 'devvn_woocommerce_localisation_address_formats_vn', 999999);
    function devvn_woocommerce_localisation_address_formats_vn($arg)
    {
        if (isset($arg['default']))
            unset($arg['default']);
        if (isset($arg['VN']))
            unset($arg['VN']);
        $arg['default'] = "{gender} {name}\n{company}\n{address_1}\n{address_2}\n{city}\n{state}\n{country}";
        $arg['VN'] = "{gender} {name}\n{company}\n{address_1}\n{address_2}\n{city}\n{state}\n{country}";
        return $arg;
    }
}

if (!function_exists('devvn_woocommerce_order_formatted_billing_address_gender')) {
    add_filter('woocommerce_order_formatted_billing_address', 'devvn_woocommerce_order_formatted_billing_address_gender', 10, 2);
    function devvn_woocommerce_order_formatted_billing_address_gender($address_arg, $thisParent)
    {
        // Use HPOS compatible method
        $gender = $thisParent->get_meta('_billing_gender', true);
        if (!$gender) {
            $gender = get_post_meta($thisParent->get_id(), '_billing_gender', true);
        }

        if (!isset($address_arg['gender']) && $gender) {
            $gender = ($gender == 'male') ? 'Anh' : 'Chị';
            $address_arg['gender'] = $gender;
        }
        return $address_arg;
    }
}

if (!function_exists('devvn_woocommerce_formatted_address_replacements_gender')) {
    add_filter('woocommerce_formatted_address_replacements', 'devvn_woocommerce_formatted_address_replacements_gender', 10, 2);
    function devvn_woocommerce_formatted_address_replacements_gender($replace, $args)
    {
        if (!isset($replace['{gender}']) && isset($args['gender'])) {
            $replace['{gender}'] = $args['gender'];
        } else {
            $replace['{gender}'] = '';
        }
        return $replace;
    }
}

if (!function_exists('devvn_custom_checkout_field_display_admin_order_meta_gender')) {
    add_action('woocommerce_admin_order_data_after_billing_address', 'devvn_custom_checkout_field_display_admin_order_meta_gender', 10, 1);
    function devvn_custom_checkout_field_display_admin_order_meta_gender($order)
    {
        // Use HPOS compatible method
        $gender = $order->get_meta('_billing_gender', true);
        if (!$gender) {
            $gender = get_post_meta($order->get_id(), '_billing_gender', true);
        }

        if ($gender) {
            $gender = ($gender == 'male') ? 'Anh' : 'Chị';

            echo '<p><strong>' . __('Xưng hô', 'devvn-quickbuy') . ':</strong> ' . $gender . '</p>';
        }
    }
}
