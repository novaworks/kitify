<?php
/**
 * Pricing table price block template
 */
?>
<div class="kitify-pricing-table__price"><?php
	$this->_html( 'price_prefix', '<span class="kitify-pricing-table__price-prefix">%s</span>' );
	$this->_html( 'price', '<span class="kitify-pricing-table__price-val">%s</span>' );
	$this->_html( 'price_suffix', '<span class="kitify-pricing-table__price-suffix">%s</span>' );
	$this->_html( 'price_desc', '<p class="kitify-pricing-table__price-desc">%s</p>' );
?></div>