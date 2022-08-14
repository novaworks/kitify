<?php
namespace KitifyThemeBuilder\Modules\DynamicTags\Tags;

use KitifyThemeBuilder\Modules\DynamicTags\Tags\Base\Data_Tag;
use KitifyThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Comments_URL extends Data_Tag {

	public function get_name() {
		return 'comments-url';
	}

	public function get_title() {
		return esc_html__( 'Comments URL', 'kitify' );
	}

	public function get_group() {
		return Module::COMMENTS_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		return get_comments_link();
	}
}
