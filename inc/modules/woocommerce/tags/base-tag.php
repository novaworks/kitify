<?php
namespace KitifyThemeBuilder\Modules\Woocommerce\Tags;

use KitifyThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use KitifyThemeBuilder\Modules\Woocommerce\Module;
use KitifyThemeBuilder\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base_Tag extends Tag {
	use Tag_Product_Id;

	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
}
