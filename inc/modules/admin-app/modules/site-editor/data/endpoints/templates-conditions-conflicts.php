<?php
namespace KitifyThemeBuilder\Modules\AdminApp\Modules\SiteEditor\Data\Endpoints;

use KitifyThemeBuilder\Modules\ThemeBuilder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Templates_Conditions_Conflicts extends Base_Endpoint {
	/**
	 * @return string
	 */
	public function get_name() {
		return 'templates-conditions-conflicts';
	}

	public function get_items( $request ) {
		/** @var Module $theme_builder */
		$theme_builder = kitify()->modules_manager->get_modules( 'theme-builder' );

		return $theme_builder
			->get_conditions_manager()
			->get_conditions_conflicts( intval( $request['post_id'] ), $request['condition'] );
	}
}
