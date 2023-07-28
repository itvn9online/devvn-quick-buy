<?php
defined('ABSPATH') or die('No script kiddies please!');

$available_methods = $this->devvn_get_rates($package, $product_info);
//print_r($available_methods);
ob_start();
?>
<?php if (1 < count($available_methods)) : ?>
    <ul id="shipping_method">
        <?php $stt = 1;
        foreach ($available_methods as $method) : ?>
            <li>
                <?php
                printf(
                    '<input type="radio" name="shipping_method[%1$d]" data-cost="%6$d" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />
								<label for="shipping_method_%1$d_%2$s">%5$s</label>',
                    0,
                    sanitize_title($method->id),
                    esc_attr($method->id),
                    ($stt == 1) ? 'checked' : '',
                    wc_cart_totals_shipping_method_label($method),
                    sanitize_text_field($method->cost)
                );

                do_action('woocommerce_after_shipping_rate', $method, 0);
                ?>
            </li>
        <?php $stt++;
        endforeach; ?>
    </ul>
<?php elseif (1 === count($available_methods)) : ?>
    <?php
    $method = current($available_methods);
    printf('%3$s <input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" data-cost="%4$d" id="shipping_method_%1$d" value="%2$s" class="shipping_method" checked/>', 0, esc_attr($method->id), wc_cart_totals_shipping_method_label($method), sanitize_text_field($method->cost));
    do_action('woocommerce_after_shipping_rate', $method, 0);
    ?>
<?php endif; ?>
<?php
$result = ob_get_clean();
