<?php
/**
 * Animated box action button
 */

use Elementor\Icons_Manager;

$position    = $this->get_settings_for_display( 'button_icon_position' );
$use_icon    = $this->get_settings_for_display( 'add_button_icon' );
$button_url  = $this->get_settings_for_display( 'back_side_button_link' );
$button_icon = $this->get_settings_for_display( 'button_icon' );

if ( empty( $button_url ) ) {
	return false;
}

if ( is_array( $button_url ) && empty( $button_url['url'] ) ) {
	return false;
}

$this->add_render_attribute( 'url', 'class', array(
	'elementor-button',
	'elementor-size-md',
	'kitify-animated-box__button',
	'kitify-animated-box__button--back',
	'kitify-animated-box__button--icon-' . $position,
) );

if ( is_array( $button_url ) ) {
	$this->add_render_attribute( 'url', 'href', $button_url['url'] );

	if ( $button_url['is_external'] ) {
		$this->add_render_attribute( 'url', 'target', '_blank' );
	}

	if ( ! empty( $button_url['nofollow'] ) ) {
		$this->add_render_attribute( 'url', 'rel', 'nofollow' );
	}

} else {
	$this->add_render_attribute( 'url', 'href', $button_url );
}

?>
<a <?php echo $this->get_render_attribute_string( 'url' ); ?>><?php
	echo $this->_html( 'back_side_button_text', '<span class="kitify-animated-box__button-text">%s</span>' );

	if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
		if ( !empty( $button_icon['value'] ) ) {
		    echo '<span class="elementor-wrap-icon">';
			Icons_Manager::render_icon( $button_icon, [ 'aria-hidden' => 'true', 'class' => 'kitify-animated-box__button-icon' ] );
		    echo '</span>';
        }
	}
?></a>

