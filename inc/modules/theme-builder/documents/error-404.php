<?php
namespace KitifyThemeBuilder\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Error_404 extends Single_Base {

	protected static function get_site_editor_type() {
		return 'error-404';
	}

	public static function get_sub_type() {
		return 'not_found404';
	}

	public static function get_title() {
		return esc_html__( 'Error 404', 'kitify' );
	}

	protected static function get_site_editor_icon() {
		return 'eicon-error-404';
	}

	protected static function get_site_editor_tooltip_data() {
		return [
			'title' => esc_html__( 'What is a 404 Page Template?', 'kitify' ),
			'content' => esc_html__( 'A 404 page template allows you to easily design the layout and style of the page that is displayed when a visitor arrives at a page that does not exist.', 'kitify' ),
			'tip' => esc_html__( 'Keep your site\'s visitors happy when they get lost by displaying your recent posts, a search bar, or any information that might help the user find what they were looking for.', 'kitify' ),
			'docs' => 'https://go.elementor.com/app-theme-builder-error-404',
			'video_url' => 'https://www.youtube.com/embed/ACCNp9tBMQg',
		];
	}

	public static function get_preview_as_options() {
		return [
			'page/404' => __( '404', 'kitify' ),
		];
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = '404 page';

		return $config;
	}
}
