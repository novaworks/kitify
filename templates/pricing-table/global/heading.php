<?php
/**
 * Pricing table heading template
 */
?>
<div class="kitify-pricing-table__heading">
    <?php echo $this->__generate_icon(); ?>
	<?php $this->_html( 'title', '<h3 class="kitify-pricing-table__title">%s</h3>' ); ?>
	<?php $this->_html( 'subtitle', '<div class="kitify-pricing-table__subtitle">%s</div>' ); ?>
</div>