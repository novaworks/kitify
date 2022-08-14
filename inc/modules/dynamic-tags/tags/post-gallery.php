<?php
namespace KitifyThemeBuilder\Modules\DynamicTags\Tags;

use KitifyThemeBuilder\Modules\DynamicTags\Tags\Base\Data_Tag;
use KitifyThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Gallery extends Data_Tag {

	public function get_name() {
		return 'post-gallery';
	}

	public function get_title() {
		return esc_html__( 'Post Image Attachments', 'kitify' );
	}

	public function get_categories() {
		return [ Module::GALLERY_CATEGORY ];
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_value( array $options = [] ) {
		$images = get_attached_media( 'image' );

		$value = [];

		foreach ( $images as $image ) {
			$value[] = [
				'id' => $image->ID,
			];
		}

		return $value;
	}
}
