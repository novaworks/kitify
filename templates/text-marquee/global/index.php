<?php
	$items = $this->get_settings_for_display('items');
	$reverse_direction = $this->get_settings_for_display('reverse_direction');
	$text_stroke_effect = $this->get_settings_for_display('text_stroke_effect');
	$box_classes = ['kitify-text-marquee'];
	if ($text_stroke_effect == 'yes') {
	    $box_classes[] = 'kitify-text-stroke-effect';
	}
	$this->add_render_attribute('m_text_wrapper', 'class', $box_classes);
 ?>
<div <?php echo $this->get_render_attribute_string('m_text_wrapper') ?>>
	<div class="kititfy-m-content">
		<div class="kitify-text-marquee__text text--original">
			<?php
			foreach ( $items as $item ) {
				$item_stroke_classes = '';
				$item_class = 'kitify-text-marquee__item elementor-repeater-item-' . $item['_id'];
				if ($item['text_stroke_effect'] == 'yes') {
					$item_stroke_classes = ' kitify-item-stroke-effect';
				}
				?>
				<?php
				if(empty($item['link']['url'])){
				?>
				<span class="<?php echo esc_attr($item_class)?><?php echo esc_attr($item_stroke_classes)?>" ><?php echo wp_kses( $item['text'], array( 'span' => array( 'style' => true ) ) ); ?></span>
			<?php }else{ ?>
				<a href="<?php echo esc_url($item['link']['url'])?>"><span class="<?php echo esc_attr($item_class)?>" ><?php echo wp_kses( $item['text'], array( 'span' => array( 'style' => true ) ) ); ?></span></a>
			<?php } ?>
				<?php $this->_icon( 'separator_icon', '<span class="kitify-e-icon-holder">%s</span>' ); ?>
			<?php } ?>
		</div>
		<div class="kitify-text-marquee__text text--clone">
			<?php
			foreach ( $items as $item ) {
				$item_stroke_classes = '';
				$item_class = 'kitify-text-marquee__item elementor-repeater-item-' . $item['_id'];
				if ($item['text_stroke_effect'] == 'yes') {
					$item_stroke_classes = ' kitify-item-stroke-effect';
				}
				?>
				<?php
				if(empty($item['link']['url'])){
				?>
				<span class="<?php echo esc_attr($item_class)?><?php echo esc_attr($item_stroke_classes)?>" ><?php echo wp_kses( $item['text'], array( 'span' => array( 'style' => true ) ) ); ?></span>
			<?php }else{ ?>
				<a href="<?php echo esc_url($item['link']['url'])?>"><span class="<?php echo esc_attr($item_class)?>" ><?php echo wp_kses( $item['text'], array( 'span' => array( 'style' => true ) ) ); ?></span></a>
			<?php } ?>
			<?php $this->_icon( 'separator_icon', '<span class="kitify-e-icon-holder">%s</span>' ); ?>
			<?php } ?>
		</div>
	</div>
</div>
