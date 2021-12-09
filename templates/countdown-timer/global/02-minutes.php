<?php echo $this->blocks_separator(); ?>
<div class="kitify-countdown-timer__item item-minutes">
	<div class="kitify-countdown-timer__item-value" data-value="minutes"><?php echo $this->date_placeholder(); ?></div>
	<?php $this->_html( 'label_min', '<div class="kitify-countdown-timer__item-label">%s</div>' ); ?>
</div>
