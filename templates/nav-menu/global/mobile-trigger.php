<?php
/**
 * Mobile menu trigger template
 */
?>

<div class="main-color kitify-nav__mobile-trigger kitify-nav-mobile-trigger-align-<?php echo esc_attr( $trigger_align ); ?>">
	<?php $this->_icon( 'mobile_trigger_icon', '<span class="kitify-nav__mobile-trigger-open kitify-blocks-icon">%s</span>' ); ?>
	<?php $this->_icon( 'mobile_trigger_close_icon', '<span class="kitify-nav__mobile-trigger-close kitify-blocks-icon">%s</span>' ); ?>
</div>