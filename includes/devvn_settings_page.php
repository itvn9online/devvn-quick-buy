<?php
defined('ABSPATH') or die('No script kiddies please!');

//global $quickbuy_settings;
//print_r($quickbuy_settings);

?>
<div class="wrap devvn_quickbuy">
    <h1>
        <?php _e('Quick Buy Setting', 'devvn-quickbuy'); ?>
    </h1>
    <form method="post" action="options.php" novalidate="novalidate">
        <?php settings_fields($this->_optionGroup); ?>
        <h2>
            <?php _e('General settings', 'devvn-quickbuy'); ?>
        </h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="enable">
                            <?php _e('Enable', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label>
                            <input type="radio" id="enable" value="1" <?php checked(1, $quickbuy_settings['enable']); ?> name="quickbuy_options[enable]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                        <label>
                            <input type="radio" id="enable" value="0" <?php checked(0, $quickbuy_settings['enable']); ?> name="quickbuy_options[enable]">
                            <?php _e('Deactive', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_location">
                            <?php _e('Select location', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label>
                            <input type="checkbox" id="enable_location" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['enable_location']
                                                                                    ); ?> name="quickbuy_options[enable_location]">
                            <?php _e('Active', 'devvn-quickbuy'); ?><br>
                            <small>
                                <?php _e('Requires have state', 'devvn-quickbuy'); ?>
                            </small>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_ship">
                            <?php _e('Shipping', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="enable_ship" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['enable_ship']
                                                                                    ); ?> name="quickbuy_options[enable_ship]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_coupon">
                            <?php _e('Coupons', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="enable_coupon" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['enable_coupon']
                                                                                    ); ?> name="quickbuy_options[enable_coupon]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="hidden_email">
                            <?php _e('Hidden Email', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="hidden_email" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['hidden_email']
                                                                                    ); ?> name="quickbuy_options[hidden_email]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="require_email">
                            <?php _e('Require Email', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="require_email" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['require_email']
                                                                                    ); ?> name="quickbuy_options[require_email]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <?php
                if ($this->check_plugin_active()) {
                ?>
                    <tr>
                        <th scope="row"><label for="require_district">
                                <?php _e('Require District', 'devvn-quickbuy'); ?>
                            </label></th>
                        <td>
                            <label><input type="checkbox" id="require_district" value="1" <?php checked(
                                                                                                1,
                                                                                                $quickbuy_settings['require_district']
                                                                                            ); ?> name="quickbuy_options[require_district]">
                                <?php _e('Require', 'devvn-quickbuy'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="require_village">
                                <?php _e('Require Village', 'devvn-quickbuy'); ?>
                            </label></th>
                        <td>
                            <label><input type="checkbox" id="require_village" value="1" <?php checked(
                                                                                                1,
                                                                                                $quickbuy_settings['require_village']
                                                                                            ); ?> name="quickbuy_options[require_village]">
                                <?php _e('Require', 'devvn-quickbuy'); ?>
                            </label>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <th scope="row"><label for="require_address">
                            <?php _e('Require Address box', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="require_address" value="1" <?php checked(
                                                                                            1,
                                                                                            $quickbuy_settings['require_address']
                                                                                        ); ?> name="quickbuy_options[require_address]">
                            <?php _e('Require', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_payment">
                            <?php _e('Enable Payment methob', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="enable_payment" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['enable_payment']
                                                                                    ); ?> name="quickbuy_options[enable_payment]">
                            <?php _e('Yes/No', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="hidden_note">
                            <?php _e('Hidden Note', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="hidden_note" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['hidden_note']
                                                                                    ); ?> name="quickbuy_options[hidden_note]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="hidden_address">
                            <?php _e('Hidden Address', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="hidden_address" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['hidden_address']
                                                                                    ); ?> name="quickbuy_options[hidden_address]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="valid_phone">
                            <?php _e('Valid Phone', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="valid_phone" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['valid_phone']
                                                                                    ); ?> name="quickbuy_options[valid_phone]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <h2>
            <?php _e('Button quick buy', 'devvn-quickbuy'); ?>
        </h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="in_loop_prod">
                            <?php _e('Hiển thị trong list sản phẩm', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="in_loop_prod" value="1" <?php checked(
                                                                                        1,
                                                                                        $quickbuy_settings['in_loop_prod']
                                                                                    ); ?> name="quickbuy_options[in_loop_prod]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="button_text1">
                            <?php _e('Button text', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <input type="text" id="button_text1" value="<?php echo esc_attr($quickbuy_settings['button_text1']); ?>" name="quickbuy_options[button_text1]">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="button_text2">
                            <?php _e('Button sub text', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <input type="text" id="button_text1" value="<?php echo esc_attr($quickbuy_settings['button_text2']); ?>" name="quickbuy_options[button_text2]">
                    </td>
                </tr>
            </tbody>
        </table>
        <h2>
            <?php _e('Popup setting', 'devvn-quickbuy'); ?>
        </h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="popup_infor_enable">
                            <?php _e('View product info on mobile', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="radio" id="popup_infor_enable" value="1" <?php checked(
                                                                                            1,
                                                                                            $quickbuy_settings['popup_infor_enable']
                                                                                        ); ?> name="quickbuy_options[popup_infor_enable]">
                            <?php _e('Yes', 'devvn-quickbuy'); ?>
                        </label>
                        <label><input type="radio" id="popup_infor_enable" value="2" <?php checked(
                                                                                            2,
                                                                                            $quickbuy_settings['popup_infor_enable']
                                                                                        ); ?> name="quickbuy_options[popup_infor_enable]">
                            <?php _e('No', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="popup_title">
                            <?php _e('Popup title', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <input type="text" id="popup_title" value="<?php echo esc_attr($quickbuy_settings['popup_title']); ?>" name="quickbuy_options[popup_title]" />
                        <br><small>
                            <?php _e('%s to view product title', 'devvn-quickbuy'); ?>
                        </small>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="popup_mess">
                            <?php _e('Popup mess', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <?php
                        $settings = array(
                            'textarea_name' => 'quickbuy_options[popup_mess]',
                            'textarea_rows' => 5,
                        );
                        wp_editor($quickbuy_settings['popup_mess'], 'popup_mess', $settings); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="popup_gotothankyou">
                            <?php _e('Go to thank you page', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <label><input type="checkbox" id="popup_gotothankyou" value="1" <?php checked(
                                                                                            1,
                                                                                            $quickbuy_settings['popup_gotothankyou']
                                                                                        ); ?> name="quickbuy_options[popup_gotothankyou]">
                            <?php _e('Active', 'devvn-quickbuy'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="popup_sucess">
                            <?php _e('Checkout successfully mess', 'devvn-quickbuy'); ?>
                        </label><br>
                        <small>
                            <?php _e('Type %%order_id%% to view Order ID', 'devvn-quickbuy') ?>
                        </small>
                    </th>
                    <td>
                        <?php
                        $settings = array(
                            'textarea_name' => 'quickbuy_options[popup_sucess]',
                            'textarea_rows' => 15,
                        );
                        wp_editor($quickbuy_settings['popup_sucess'], 'popup_sucess', $settings); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="popup_error">
                            <?php _e('Checkout error message', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <input type="text" id="popup_error" value="<?php echo esc_attr($quickbuy_settings['popup_error']); ?>" name="quickbuy_options[popup_error]">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="out_of_stock_mess">
                            <?php _e('Out of stock message', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <input type="text" id="out_of_stock_mess" value="<?php echo esc_attr($quickbuy_settings['out_of_stock_mess']); ?>" name="quickbuy_options[out_of_stock_mess]">
                    </td>
                </tr>
            </tbody>
        </table>
        <h2>
            <?php _e('License', 'devvn-quickbuy'); ?>
        </h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="license_key">
                            <?php _e('License key', 'devvn-quickbuy'); ?>
                        </label></th>
                    <td>
                        <input type="text" id="license_key" value="<?php echo esc_attr($quickbuy_settings['license_key']); ?>" name="quickbuy_options[license_key]">
                        <?php if (!$quickbuy_settings['license_key']) : ?><br><small>
                                <?php echo sprintf(__('<strong>Gửi email + domain qua <a href="%s" target="_blank">facebook</a> để nhận license<strong>', 'devvn-quickbuy'), 'http://m.me/levantoan.wp'); ?>
                            </small>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php do_settings_fields('quickbuy-options-group', 'default'); ?>
        <?php do_settings_sections('quickbuy-options-group', 'default'); ?>
        <?php submit_button(); ?>
    </form>
</div>