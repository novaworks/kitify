<?php
/**
 * Timeline list item template
 */
$settings      = $this->get_settings_for_display();
$item_settings = $this->_processed_item;

$this->add_render_attribute(
	'item_middle_' . $item_settings['_id'],
	array(
		'class' => array(
			'kitify-htimeline-item',
			'elementor-repeater-item-' . esc_attr( $item_settings['_id'] )
		),
		'data-item-id' => esc_attr( $item_settings['_id'] )
	)
);

if ( filter_var( $item_settings['is_item_active'], FILTER_VALIDATE_BOOLEAN ) ) {
	$this->add_render_attribute( 'item_middle_' . $item_settings['_id'], 'class', 'is-active' );
}
?>

<div <?php $this->print_render_attribute_string( 'item_middle_' . $item_settings['_id'] ) ?>>
	<?php include $this->_get_global_template( 'point' ); ?>
</div>
