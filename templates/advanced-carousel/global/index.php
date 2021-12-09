<?php
/**
 * Advanced carousel template
 */
$layout     = $this->get_settings_for_display( 'item_layout' );

$this->_get_global_looped_template( esc_attr( $layout ) . '/items', 'items_list' );
