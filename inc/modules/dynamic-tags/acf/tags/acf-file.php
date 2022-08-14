<?php
namespace KitifyThemeBuilder\Modules\DynamicTags\ACF\Tags;

use KitifyThemeBuilder\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_File extends ACF_Image {

	public function get_name() {
		return 'acf-file';
	}

	public function get_title() {
		return esc_html__( 'ACF', 'kitify' ) . ' ' . esc_html__( 'File Field', 'kitify' );
	}

	public function get_categories() {
		return [
			Module::MEDIA_CATEGORY,
		];
	}

	public function get_supported_fields() {
		return [
			'file',
		];
	}
}
