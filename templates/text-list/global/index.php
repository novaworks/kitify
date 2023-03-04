<?php
	$items = $this->get_settings_for_display('items');
	$box_classes = ['kitify-text-list'];
	$this->add_render_attribute('text_list_wrapper', 'class', $box_classes);
 ?>
<div <?php echo $this->get_render_attribute_string('text_list_wrapper') ?>>
	<ul>
			<?php
			foreach ( $items as $item ) {
				?>
				<li>
				<span class="kitify-text-list__title" ><?php echo esc_html( $item['title'] ); ?></span>
				<span class="kitify-text-list__value" ><?php echo esc_html( $item['value'] ); ?></span>
				</li>
			<?php } ?>
		</ul>
</div>
