<?php
/**
 * Pricing table main template
 */
?>
<div class="kitify-pricing-table<?php $this->_html( 'featured', ' featured-table' ); ?>">
	<?php $this->_glob_inc_if( 'heading', array( 'icon', 'title', 'subtitle' ) ); ?>
	<?php $this->_glob_inc_if( 'price', array( 'price_prefix', 'price', 'price_suffix' ) ); ?>
	<?php $this->_get_global_looped_template( 'features', 'features_list' ); ?>
	<?php $this->_glob_inc_if( 'action', array( 'button_before', 'button_url', 'button_text', 'button_after' ) ); ?>
	<?php $this->_glob_inc_if( 'badge', array( 'featured' ) ); ?>
</div>
