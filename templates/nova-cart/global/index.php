<?php
/**
 * Menu Account template
 */
$classes = [
    'kitify-nova-cart',
];

$class_string = implode( ' ', $classes );
$elementor    = Elementor\Plugin::instance();
$is_edit_mode = $elementor->editor->is_edit_mode();

if ( ( $is_edit_mode && ! wp_doing_ajax() ) || null === WC()->cart ) {
	$count = '';
} else {
	$count = WC()->cart->get_cart_contents_count();
}
?>
<div class="<?php echo $class_string; ?>">
  <a<?php if ( Nova_OP::getOption('header_cart_action') == 'cart-page' ) : ?> href="<?php echo esc_url( wc_get_cart_url() );?>"<?php endif; ?><?php if ( Nova_OP::getOption('header_cart_action') == 'mini-cart' ) : ?> href="javascript:;" data-toggle="MiniCartCanvas"<?php endif; ?>>
    <div class="header-cart-box">
      <?php $this->_icon( 'novacart_icon', '<span class="kitify-nova-cart__icon kitify-blocks-icon">%s</span>' ); ?>
      <div class="count-badge js_count_bag_item"><?php echo esc_html($count); ?></div>
    </div>
  </a>
</div>
