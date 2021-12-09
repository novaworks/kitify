<?php
/**
 * Pricing table action button
 */

$this->add_render_attribute( 'button', array(
	'class' => array(
		'elementor-button',
		'elementor-size-md',
		'kitify-pricing-table-button',
		'button-' . $this->get_settings_for_display( 'button_size' ) . '-size',
	)
) );
$this->add_link_attributes('button', $this->get_settings_for_display('button_url'))

?>
<a <?php echo $this->get_render_attribute_string( 'button' ); ?>><span class="elementor-button-content-wrapper"><?php

	$position = $this->get_settings_for_display( 'button_icon_position' );
	$icon     = $this->get_settings_for_display( 'add_button_icon' );

	if ( $icon && 'left' === $position ) {
	    echo $this->_get_icon('button_icon', '<span class="elementor-button-icon elementor-align-icon-left">%s</span>');
	}

	echo $this->_html( 'button_text', '<span class="elementor-button-text">%s</span>' );

	if ( $icon && 'right' === $position ) {
        echo $this->_get_icon('button_icon', '<span class="elementor-button-icon elementor-align-icon-right">%s</span>');
	}

?></span></a>
