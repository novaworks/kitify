<?php
/**
 * Menu Account template
 */
$classes = [
    'kitify-menu-account',
];

$class_string = implode( ' ', $classes );

?>
<div class="<?php echo $class_string; ?>">
	<?php if ( is_user_logged_in() ){ ?>
		<div class="kitify-menu-account__box">
			<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
				<?php $this->_icon( 'acc_icon', '<span class="kitify-menu-account__icon kitify-blocks-icon">%s</span>' ); ?>
			</a>
			<ul class="sub-menu">
			<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
				<li class="menu-item">
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	<?php }else{  ?>
    <div class="kitify-menu-account__box">
    <a<?php if ( Nova_OP::getOption('header_user_action') == 'account-page' ) : ?> href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"<?php endif; ?><?php if ( Nova_OP::getOption('header_user_action') == 'modal' ) : ?> data-toggle="AcccountCanvas"<?php endif; ?>>
      	<?php $this->_icon( 'acc_icon', '<span class="kitify-menu-account__icon kitify-blocks-icon">%s</span>' ); ?>
    </a>
    </div>
<?php } ?>
</div>
