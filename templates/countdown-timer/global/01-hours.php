<?php echo $this->blocks_separator(); ?>
<div class="kitify-countdown-timer__item item-hours">
	<div class="kitify-countdown-timer__item-value" data-value="hours"><?php echo $this->date_placeholder(); ?></div>
	<?php $this->_html( 'label_hours', '<div class="kitify-countdown-timer__item-label">%s</div>' ); ?>
</div>
