<?php
namespace KitifyThemeBuilder\Modules\NestedElements\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Control_Repeater;

/**
 * Changing the default repeater control behavior for custom item title defaults.
 * For custom management of nested repeater controls.
 */
class Control_Nested_Repeater extends Control_Repeater {

	const CONTROL_TYPE = 'nested-elements-repeater';

	public function get_type() {
		return static::CONTROL_TYPE;
	}
}
