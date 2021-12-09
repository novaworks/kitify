<?php
namespace KitifyThemeBuilder\Modules\ThemeBuilder\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class General extends Condition_Base {

	protected $sub_conditions = [
		'archive',
		'singular',
	];

	public static function get_type() {
		return 'general';
	}

	public function get_name() {
		return 'general';
	}

	public function get_label() {
		return esc_html__( 'General', 'kitify' );
	}

	public function get_all_label() {
		return esc_html__( 'Entire Site', 'kitify' );
	}

	public function check( $args ) {
		return true;
	}
}
