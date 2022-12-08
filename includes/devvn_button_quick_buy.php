<?php

$atts = shortcode_atts(
    array(
        'id' => '',
        'view' => true,
        'button_text1' => '',
        'button_text2' => '',
        'small_link' => false
    ),
    $atts,
    'devvn_quickbuy'
);

$id = $atts['id'];
$view = (bool) $atts['view'];
$button_text1 = $atts['button_text1'];
$button_text2 = $atts['button_text2'];
$small_link = (bool) $atts['small_link'];
global $quickbuy_settings;
if (!$id) {
    global $product;
    $this_product = $product;
} else {
    $this_product = wc_get_product($id);
}
if ($this_product) {
    ob_start();
    if ((!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) && $this_product->is_in_stock()) {
        if (!$small_link) {
?>
<a href="javascript:void(0);" class="devvn_buy_now devvn_buy_now_style"
    data-id="<?php echo $this_product->get_id(); ?>">
    <strong>
        <?php echo ($button_text1) ? $button_text1 : $quickbuy_settings['button_text1']; ?>
    </strong>
    <span>
        <?php echo ($button_text2) ? $button_text2 : $quickbuy_settings['button_text2']; ?>
    </span>
</a>
<?php
            if ($view) {
                $this->quick_buy_popup_content($atts, true);
            }
        } else {
?>
<a href="javascript:void(0);" class="devvn_buy_now devvn_buy_now_ajax" data-id="<?php echo $this_product->get_id(); ?>">
    <?php echo ($button_text1) ? $button_text1 : $quickbuy_settings['button_text1']; ?>
</a>
<?php
        }
    }
    $result = ob_get_clean();
}