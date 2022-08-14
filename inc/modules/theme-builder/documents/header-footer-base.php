<?php
namespace KitifyThemeBuilder\Modules\ThemeBuilder\Documents;

use Elementor\Core\DocumentTypes\Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Header_Footer_Base extends Theme_Section_Document {

	public function get_css_wrapper_selector() {
		return '.elementor-' . $this->get_main_id();
	}

	protected static function get_editor_panel_categories() {
		// Move to top as active.
		$categories = [
			'kitify-builder' => [
				'title' => __( 'Kitify Builder', 'kitify' ),
				'active' => true,
			],
            'kitify' => [
				'title' => __( 'Kitify', 'kitify' ),
				'active' => true,
			],
		];

		return $categories + parent::get_editor_panel_categories();
	}

	protected function register_controls() {
		parent::register_controls();

		Post::register_style_controls( $this );

		$this->update_control(
			'section_page_style',
			[
				'label' => __( 'Style', 'kitify' ),
			]
		);
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = $this->get_name();

		return $config;
	}
}
