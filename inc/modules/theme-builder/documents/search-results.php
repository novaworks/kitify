<?php
namespace KitifyThemeBuilder\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Search_Results extends Archive {

	public function get_name() {
		return 'search-results';
	}

	public static function get_sub_type() {
		return 'search';
	}

	public static function get_title() {
		return esc_html__( 'Search Results', 'kitify' );
	}

	protected static function get_site_editor_icon() {
		return 'eicon-search-results';
	}

	protected static function get_site_editor_tooltip_data() {
		return [
			'title' => esc_html__( 'What is a Search Results Template?', 'kitify' ),
			'content' => esc_html__( 'You can easily control the layout and design of the Search Results page with the Search Results template, which is simply a special archive template just for displaying search results.', 'kitify' ),
			'tip' => esc_html__( 'You can customize the message if there are no results for the search term.', 'kitify' ),
			'docs' => 'https://go.elementor.com/app-theme-builder-search-results',
			'video_url' => 'https://www.youtube.com/embed/KKkIU_L5sDo',
		];
	}

	public static function get_preview_as_default() {
		return 'search';
	}

	public static function get_preview_as_options() {
		$options = [
			'search' => __( 'Search Results', 'kitify' ),
		];

		return [
			'archive' => [
				'label' => __( 'Archive', 'kitify' ),
				'options' => $options,
			],
		];
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = 'archive';

		return $config;
	}
}
