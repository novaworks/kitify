<?php
/**
 * Cart Link
 */
$this->add_render_attribute( 'cart-link', 'href', esc_url( wc_get_cart_url() ) );
$this->add_render_attribute( 'cart-link', 'class', 'kitify-cart__heading-link main-color' );
$this->add_render_attribute( 'cart-link', 'title', esc_attr__( 'View your shopping cart', 'kitify' ) );

?>
<a <?php echo $this->get_render_attribute_string( 'cart-link' ); ?>><?php

	$this->_icon( 'cart_icon', '<span class="kitify-cart__icon kitify-blocks-icon">%s</span>' );
	$this->_html( 'cart_label', '<span class="kitify-cart__label">%s</span>' );

	if ( 'yes' === $settings['show_count'] ) {
		?>
		<span class="kitify-cart__count"><?php
			ob_start();
			include $this->_get_global_template( 'cart-count' );
			printf( $settings['count_format'], ob_get_clean() );
		?></span>
		<?php
	}

	if ( 'yes' === $settings['show_total'] ) {
		?>
		<span class="kitify-cart__total"><?php
			ob_start();
			include $this->_get_global_template( 'cart-totals' );
			printf( $settings['total_format'], ob_get_clean() );
		?></span>
		<?php
	}

?></a>