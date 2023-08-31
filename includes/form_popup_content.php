<?php
defined('ABSPATH') or die('No script kiddies please!');

global $quickbuy_settings, $product;
if ($args) {
    $prodID = isset($args['id']) ? intval($args['id']) : '';
    if ($prodID) {
        $thisProduct = wc_get_product($prodID);
        if ($thisProduct) {
            $_randID = time();
            $require_email = $quickbuy_settings['require_email'];
?>
            <div class="devvn-popup-content-left <?php echo ($quickbuy_settings['popup_infor_enable'] == 1) ? '' : 'popup_quickbuy_hidden_mobile'; ?>">
                <div class="devvn-popup-prod">
                    <?php
                    $thumbArgs = wp_get_attachment_image_src(get_post_thumbnail_id($thisProduct->get_id()), 'shop_thumbnail');
                    if ($thumbArgs) : ?>
                        <div class="devvn-popup-img"><img src="<?php echo $thumbArgs[0] ?>" alt="" /></div>
                    <?php endif; ?>
                    <div class="devvn-popup-info">
                        <span class="devvn_title">
                            <?php echo $thisProduct->get_title(); ?>
                        </span>
                        <?php if ($thisProduct->get_type() == 'simple') : ?><span class="devvn_price">
                                <?php echo $thisProduct->get_price_html(); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="devvn_prod_variable" data-simpleprice="<?php echo $thisProduct->get_price(); ?>">
                    <?php
                    $product = $thisProduct;
                    do_action('devvn_prod_variable');
                    ?>
                </div>
                <div class="devvn-popup-content-desc">
                    <?php echo apply_filters('wpautop', $quickbuy_settings['popup_mess']); ?>
                </div>
            </div>
            <div class="devvn-popup-content-right">
                <form class="devvn_cusstom_info" id="devvn_cusstom_info" method="post">
                    <div class="popup-customer-info">
                        <div class="popup-customer-info-title">
                            <?php _e('Your information', 'devvn-quickbuy') ?>
                        </div>
                        <?php do_action('before_field_devvn_quickbuy'); ?>
                        <div class="popup-customer-info-group popup-customer-info-radio">
                            <label>
                                <input type="radio" name="customer-gender" value="1" checked />
                                <span>
                                    <?php _e('Mr', 'devvn-quickbuy'); ?>
                                </span>
                            </label>
                            <label>
                                <input type="radio" name="customer-gender" value="2" />
                                <span>
                                    <?php _e('Mrs', 'devvn-quickbuy'); ?>
                                </span>
                            </label>
                        </div>
                        <div class="popup-customer-info-group">
                            <div class="popup-customer-info-item-2 popup-customer-info-name">
                                <input type="text" class="customer-name" name="customer-name" placeholder="<?php _e('Full name', 'devvn-quickbuy'); ?>">
                            </div>
                            <div class="popup-customer-info-item-2 popup-customer-info-phone">
                                <input type="text" class="customer-phone" name="customer-phone" id="your-phone-<?php echo $_randID; ?>" placeholder="<?php _e('Phone number', 'devvn-quickbuy'); ?>">
                            </div>
                        </div>

                        <?php if ($quickbuy_settings['valid_phone']) : ?>
                            <div class="popup-customer-info-group">
                                <div class="popup-customer-info-item-1  popup-customer-info-valid-phone">
                                    <input type="text" class="customer-valid-phone" data-rule-equalTo="#your-phone-<?php echo $_randID; ?>" name="customer-valid-phone" placeholder="<?php _e('Confirm your phone number', 'devvn-quickbuy'); ?>" />
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!$quickbuy_settings['hidden_email']) : ?>
                            <div class="popup-customer-info-group">
                                <div class="popup-customer-info-item-1">
                                    <?php if ($require_email) : ?>
                                        <input type="text" class="customer-email" name="customer-email" data-required="true" required placeholder="<?php _e('Email', 'devvn-quickbuy'); ?>">
                                    <?php else : ?>
                                        <input type="text" class="customer-email" name="customer-email" data-required="false" placeholder="<?php _e('Email (Not required)', 'devvn-quickbuy'); ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php
                        $countries = new WC_Countries;
                        $vn_states = $countries->get_states('VN');
                        if ($vn_states && is_array($vn_states) && $quickbuy_settings['enable_location']) :
                        ?>
                            <?php if (!$this->check_plugin_active()) : ?>
                                <div class="popup-customer-info-group">
                                    <div class="popup-customer-info-item-1">
                                        <select class="customer-location" name="customer-location" id="devvn_city">
                                            <?php foreach ($vn_states as $k => $v) : ?>
                                                <option value="<?php echo $k; ?>">
                                                    <?php echo $v; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php if (!$quickbuy_settings['hidden_address']) : ?>
                                    <div class="popup-customer-info-group">
                                        <div class="popup-customer-info-item-1">
                                            <textarea class="customer-address" name="customer-address" placeholder="<?php _e('Address', 'devvn-quickbuy'); ?> <?php echo ($quickbuy_settings['require_address'] == 0) ? __('(Not required)', 'devvn-quickbuy') : ''; ?>"></textarea>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php
                                $default_state = wc_get_base_location();
                                ?>
                                <div class="popup-customer-info-group">
                                    <div class="popup-customer-info-item-3-13">
                                        <select class="customer-location" name="customer-location" id="devvn_city">
                                            <option value="">
                                                <?php _e('City', 'devvn-quickbuy') ?>
                                            </option>
                                            <?php foreach ($vn_states as $k => $v) : ?>
                                                <option value="<?php echo $k; ?>" <?php selected($k, $default_state['state'], true); ?>>
                                                    <?php echo $v; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="popup-customer-info-item-3-23">
                                        <select class="customer-quan" name="customer-quan" id="devvn_district">
                                            <option value="">
                                                <?php _e('District', 'devvn-quickbuy') ?>
                                            </option>
                                        </select>
                                        <input name="require_district" id="require_district" type="hidden" value="<?php echo $quickbuy_settings['require_district']; ?>" />
                                    </div>
                                    <div class="popup-customer-info-item-3-33">
                                        <select class="customer-xa" name="customer-xa" id="devvn_ward">
                                            <option value="">
                                                <?php _e('Ward', 'devvn-quickbuy'); ?>
                                            </option>
                                        </select>
                                        <input name="require_village" id="require_village" type="hidden" value="<?php echo $quickbuy_settings['require_village']; ?>" />
                                    </div>
                                </div>
                                <?php if (!$quickbuy_settings['hidden_address']) : ?>
                                    <div class="popup-customer-info-group">
                                        <div class="popup-customer-info-item-1">
                                            <input type="text" class="customer-address" name="customer-address" placeholder="<?php _e('Street', 'devvn-quickbuy'); ?> <?php echo ($quickbuy_settings['require_address'] == 0) ? __('(Not required)', 'devvn-quickbuy') : ''; ?>" />
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php if (!$quickbuy_settings['hidden_address']) : ?>
                                <div class="popup-customer-info-group">
                                    <div class="popup-customer-info-item-1">
                                        <textarea class="customer-address" name="customer-address" placeholder="<?php _e('Address', 'devvn-quickbuy'); ?> <?php echo ($quickbuy_settings['require_address'] == 0) ? __('(Not required)', 'devvn-quickbuy') : ''; ?>"></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (!$quickbuy_settings['hidden_note']) : ?>
                            <div class="popup-customer-info-group">
                                <div class="popup-customer-info-item-1">
                                    <textarea class="order-note" name="order-note" placeholder="<?php _e('Note (Not required)', 'devvn-quickbuy'); ?>"></textarea>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($quickbuy_settings['enable_ship'] && $quickbuy_settings['enable_location'] && $vn_states && is_array($vn_states)) : ?>
                            <div class="popup-customer-info-group">
                                <div class="popup-customer-info-item-1 popup_quickbuy_shipping">
                                    <div class="popup_quickbuy_shipping_title">
                                        <?php _e('Shipping:', 'devvn-quickbuy'); ?>
                                    </div>
                                    <div class="popup_quickbuy_shipping_calc"></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php
                        if ($quickbuy_settings['enable_payment']) :
                            $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
                            $devvn_available_gateways = array();
                            if ($available_gateways && !empty($available_gateways)) {
                                foreach ($available_gateways as $k => $gateways) {
                                    //if($k == 'cod' || $k == 'bacs' ) {
                                    $devvn_available_gateways[$k] = $gateways;
                                    //}
                                }
                            }
                            if (!empty($devvn_available_gateways)) : ?>
                                <div class="popup-customer-info-title customer_coupon_title">
                                    <?php _e('Payment methob', 'devvn-quickbuy') ?>
                                </div>
                                <div class="popup-customer-info-group popup-customer-info-radio paymentmethob-wrap">
                                    <?php $stt = 1;
                                    foreach ($devvn_available_gateways as $gateway) : ?>
                                        <label>
                                            <input type="radio" name="customer-paymentmethob" value="<?php echo $gateway->id; ?>" <?php echo ($stt == 1) ? 'checked' : ''; ?> />
                                            <span>
                                                <?php echo $gateway->title; ?>
                                            </span>
                                        </label>
                                    <?php $stt++;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($quickbuy_settings['enable_coupon']) : ?>
                            <div class="popup-customer-info-group customer_coupon_wrap">
                                <div class="popup-customer-info-title customer_coupon_title">
                                    <?php _e('Coupon code', 'devvn-quickbuy') ?>
                                </div>
                                <div class="popup-customer-info-group customer_coupon_field">
                                    <div class="popup-customer-info-item-2-3">
                                        <input type="text" class="customer-coupon" name="customer-coupon" placeholder="<?php _e('Coupon code', 'devvn-quickbuy'); ?>" />
                                    </div>
                                    <div class="popup-customer-info-item-2-1">
                                        <button type="button" class="apply_coupon">
                                            <?php _e('Apply', 'devvn-quickbuy'); ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="popup-customer-info-group customer_coupon_field_mess">
                                    <div class="quickbuy_coupon_mess"></div>
                                    <div class="quickbuy_coupon_mess_amout">
                                        <?php _e('Coupon', 'devvn-quickbuy'); ?>: -<span class="quickbuy_coupon_amout"></span>
                                    </div>
                                    <input type="hidden" name="coupon_amout_val" class="coupon_amout_val" value="">
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="popup-customer-info-group">
                            <div class="popup-customer-info-item-1 popup_quickbuy_shipping">
                                <div class="popup_quickbuy_shipping_title">
                                    <?php _e('Total:', 'devvn-quickbuy'); ?>
                                </div>
                                <div class="popup_quickbuy_total_calc">Liên hệ</div>
                            </div>
                        </div>
                        <div class="popup-customer-info-group popup-infor-submit">
                            <div class="popup-customer-info-item-1">
                                <button type="button" class="devvn-order-btn">
                                    <?php _e('Buy Now', 'devvn-quickbuy'); ?>
                                </button>
                                <?php do_action('devvn_after_quickbuy_button'); ?>
                            </div>
                        </div>
                        <div class="popup-customer-info-group">
                            <div class="popup-customer-info-item-1">
                                <div class="devvn_quickbuy_mess"></div>
                            </div>
                        </div>
                        <?php do_action('after_field_devvn_quickbuy'); ?>
                    </div>
                    <input type="hidden" name="prod_id" id="prod_id" value="<?php echo $thisProduct->get_id(); ?>">
                    <input type="hidden" name="prod_nonce" id="prod_nonce" value="">
                    <input type="hidden" name="order_total" id="order_total" value="">
                    <input type="hidden" name="enable_ship" id="enable_ship" value="<?php echo $quickbuy_settings['enable_ship']; ?>">
                    <input name="require_address" id="require_address" type="hidden" value="<?php echo $quickbuy_settings['require_address']; ?>" />
                </form>
            </div>
<?php
        }
    }
}
