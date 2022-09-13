<?php
namespace KitifyThemeBuilder\Modules\AdminApp\Modules\SiteEditor\Data\Endpoints;

use KitifyThemeBuilder\Modules\ThemeBuilder\Module as ThemeBuilderModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Conditions_Config extends Base_Endpoint {
	/**
	 * @return string
	 */
	public function get_name() {
		return 'conditions-config';
	}

	public function get_items( $request ) {
		$conditions_manager = ThemeBuilderModule::instance()->get_conditions_manager();

		return $conditions_manager->get_conditions_config();
	}
}
