<?php
/**
 * Meta item template
 */

echo $this->_loop_item( array( 'item_meta' ), '<div class="kitify-htimeline-item__meta">%s</div>' );

if( filter_var($this->get_settings_for_display('move_image_to_meta'), FILTER_VALIDATE_BOOLEAN) ) {
    $this->_render_image( $item_settings );
}
