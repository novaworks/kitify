<?php
/**
 * Menu Account template
 */
 $preset = $this->get_settings( 'preset' );
 $show_label = $this->get_settings( 'show_label' );
 if($show_label) {
   $label = 'kitify-nova-cart-label-on';
 }else {
   $label = 'kitify-nova-cart-label-off';
 }
$classes = [
    'kitify-nova-cart',
    'kitify-nova-cart-style-'.$preset,
    $label,
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
      <div class="cart-text">
        <div class="count-badge js_count_bag_item"><?php echo esc_html($count); ?></div>
        <?php if ( $show_label ): ?>
          <div class="count-text"><?php echo esc_html__('Item(s) Added', 'elime'); ?></div>
        <?php endif;?>
      </div>
    </div>
  </a>
</div>
