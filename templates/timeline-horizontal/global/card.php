<?php
/**
 * Card item template
 */
$show_arrow = filter_var( $settings['show_card_arrows'], FILTER_VALIDATE_BOOLEAN );
$title_tag  = ! empty( $settings['item_title_size'] ) ? $settings['item_title_size'] : 'h5';
?>

<div class="kitify-htimeline-item__card">
	<div class="kitify-htimeline-item__card-inner">
		<?php
        if( ! filter_var($this->get_settings_for_display('move_image_to_meta'), FILTER_VALIDATE_BOOLEAN) ) {
            $this->_render_image( $item_settings );
        }
		echo $this->_loop_item( array( 'item_title' ) , '<' . $title_tag .' class="kitify-htimeline-item__card-title">%s</' . $title_tag . '>' );
		echo $this->_loop_item( array( 'item_desc' ), '<div class="kitify-htimeline-item__card-desc">%s</div>' );
		?>
	</div>
	<?php if ( $show_arrow ) { ?>
		<div class="kitify-htimeline-item__card-arrow"></div>
	<?php } ?>
</div>
