<?php
/**
 * Cart count template
 */

$elementor    = Elementor\Plugin::instance();
$is_edit_mode = $elementor->editor->is_edit_mode();

if ( ( $is_edit_mode && ! wp_doing_ajax() ) || null === WC()->cart ) {
	$count = '';
} else {
	$count = WC()->cart->get_cart_contents_count();
}

?>
<span class="kitify-cart__count-val"><?php
	echo $count;
?></span>