<?php echo $this->blocks_separator(); ?>
<div class="kitify-countdown-timer__item item-seconds">
	<div class="kitify-countdown-timer__item-value" data-value="seconds"><?php echo $this->date_placeholder(); ?></div>
	<?php $this->_html( 'label_sec', '<div class="kitify-countdown-timer__item-label">%s</div>' ); ?>
</div>
