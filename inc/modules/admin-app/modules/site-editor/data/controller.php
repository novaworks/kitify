<?php
namespace KitifyThemeBuilder\Modules\AdminApp\Modules\SiteEditor\Data;

use Elementor\Data\Base\Controller as Controller_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Controller extends Controller_Base {
	public function get_name() {
		return 'site-editor';
	}

	public function register_endpoints() {
		$this->register_endpoint( Endpoints\Templates::class );
		$this->register_endpoint( Endpoints\Conditions_Config::class );
		$this->register_endpoint( Endpoints\Templates_Conditions::class );
		$this->register_endpoint( Endpoints\Templates_Conditions_Conflicts::class );
	}

	public function get_permission_callback( $request ) {
		return kitify()->elementor()->kits_manager->get_active_kit()->is_editable_by_current_user();
	}
}
