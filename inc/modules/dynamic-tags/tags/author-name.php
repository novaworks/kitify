<?php
namespace KitifyThemeBuilder\Modules\DynamicTags\Tags;

use KitifyThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use KitifyThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Author_Name extends Tag {

	public function get_name() {
		return 'author-name';
	}

	public function get_title() {
		return esc_html__( 'Author Name', 'kitify' );
	}

	public function get_group() {
		return Module::AUTHOR_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_the_author() );
	}
}
