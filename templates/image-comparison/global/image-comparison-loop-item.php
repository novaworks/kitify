<?php
/**
 * Image Comparison item template
 */

$settings = $this->get_settings();
$prevArrow = $settings['handle_prev_arrow'];
$nextArrow = $settings['handle_next_arrow'];
$starting_position = $settings['starting_position'];
$starting_position_string = $starting_position['size'] . $starting_position['unit'];
$item_before_label = $this->_loop_item( array( 'item_before_label' ), 'data-label="%s"' );
$item_before_image = $this->_loop_item( array( 'item_before_image', 'url' ), '%s' );
$item_after_label = $this->_loop_item( array( 'item_after_label' ), 'data-label="%s"' );
$item_after_image = $this->_loop_item( array( 'item_after_image', 'url' ), '%s' );

$img_before_alt = Elementor\Control_Media::get_image_alt( $this->_processed_item['item_before_image'] );
$img_after_alt  = Elementor\Control_Media::get_image_alt( $this->_processed_item['item_after_image'] );

if ( empty( $item_before_image ) || empty( $item_after_image ) ) {
	return;
}

?>
<div class="kitify-image-comparison__item">
	<div class="kitify-image-comparison__container kitify-juxtapose" data-prev-icon="<?php echo $prevArrow; ?>" data-next-icon="<?php echo $nextArrow; ?>" data-makeresponsive="true" data-startingposition="<?php echo $starting_position_string; ?>">
		<img class="kitify-image-comparison__before-image a3-notlazy" src="<?php echo $item_before_image; ?>" <?php echo $item_before_label; ?> alt="<?php echo esc_attr( $img_before_alt ); ?>" data-no-lazy="1">
		<img class="kitify-image-comparison__after-image a3-notlazy" src="<?php echo $item_after_image; ?>" <?php echo $item_after_label; ?> alt="<?php echo esc_attr( $img_after_alt ); ?>" data-no-lazy="1">
	</div>
</div>
