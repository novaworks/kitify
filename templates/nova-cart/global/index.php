<?php
/**
 * Menu Account template
 */
$classes = [
    'kitify-nova-cart',
];

$class_string = implode( ' ', $classes );

?>
<div class="<?php echo $class_string; ?>">
  <a<?php if ( Nova_OP::getOption('header_cart_action') == 'cart-page' ) : ?> href="<?php echo esc_url( wc_get_cart_url() );?>"<?php endif; ?><?php if ( Nova_OP::getOption('header_cart_action') == 'mini-cart' ) : ?> href="javascript:;" data-toggle="MiniCartCanvas"<?php endif; ?>>
    <div class="header-cart-box">
      <?php $this->_icon( 'novacart_icon', '<span class="kitify-nova-cart__icon kitify-blocks-icon">%s</span>' ); ?>
      <div class="count-badge js_count_bag_item"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></div>
    </div>
  </a>
</div>
