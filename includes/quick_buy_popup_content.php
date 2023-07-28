<?php
defined('ABSPATH') or die('No script kiddies please!');

global $quickbuy_settings;
if ($args) {
    $prodID = isset($args['id']) ? intval($args['id']) : '';
    if ($prodID) {
        $thisProduct = wc_get_product($prodID);
        if ($thisProduct) {
?>
            <div class="devvn-popup-quickbuy <?php if (!$f_ajax) : ?>mfp-hide<?php endif; ?>" id="popup_content_<?php echo $thisProduct->get_id(); ?>">
                <div class="devvn-popup-inner">
                    <div class="devvn-popup-title">
                        <span>
                            <?php printf($quickbuy_settings['popup_title'], $thisProduct->get_title()); ?>
                        </span>
                        <button type="button" class="devvn-popup-close"></button>
                    </div>
                    <div class="devvn-popup-content devvn-popup-content_<?php echo $thisProduct->get_id(); ?> <?php echo (!$view) ? 'ghn_not_loaded' : ''; ?>">
                        <?php if ($view) : ?>
                            <?php $this->form_popup_content($args); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
