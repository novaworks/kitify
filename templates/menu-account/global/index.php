<?php
/**
 * Menu Account template
 */
$classes = [
    'kitify-menu-account',
];

$class_string = implode( ' ', $classes );
$show_label = $this->get_settings( 'show_label' );
?>
<div class="<?php echo $class_string; ?>">
	<?php if ( is_user_logged_in() ){ ?>
		<div class="kitify-menu-account__box">
			<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
				<?php $this->_icon( 'acc_icon', '<span class="kitify-menu-account__icon kitify-blocks-icon">%s</span>' ); ?>
        <?php if($show_label):?>
          <span class="kitify-menu-account__label"><?php echo esc_html__('My account','kitify')?></span>
        <?php endif;?>
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
    <a data-toggle="AcccountCanvas_Popup">
      	<?php $this->_icon( 'acc_icon', '<span class="kitify-menu-account__icon kitify-blocks-icon">%s</span>' ); ?>
      	<?php if($show_label):?>
          <span class="kitify-menu-account__label"><?php echo esc_html__('My account','kitify')?></span>
        <?php endif;?>
    </a>
    </div>
<?php } ?>
</div>
