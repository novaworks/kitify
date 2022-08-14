<?php
namespace KitifyThemeBuilder\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Header extends Header_Footer_Base {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'header';

		return $properties;
	}

	public static function get_type() {
		return 'header';
	}

	public static function get_title() {
		return __( 'Header', 'kitify' );
	}

	protected static function get_site_editor_icon() {
		return 'eicon-header';
	}

	protected static function get_site_editor_tooltip_data() {
		return [
			'title' => __( 'What is a Header Template?', 'kitify' ),
			'content' => __( 'The header template allows you to easily design and edit custom WordPress headers so you are no longer constrained by your theme’s header design limitations.', 'kitify' ),
			'tip' => __( 'You can create multiple headers, and assign each to different areas of your site.', 'kitify' ),
			'docs' => 'https://trk.elementor.com/app-theme-builder-header',
			'video_url' => 'https://www.youtube.com/embed/HHy5RK6W-6I',
		];
	}
}
