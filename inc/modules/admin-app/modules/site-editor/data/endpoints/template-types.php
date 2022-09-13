<?php
namespace KitifyThemeBuilder\Modules\AdminApp\Modules\SiteEditor\Data\Endpoints;
use KitifyThemeBuilder\Modules\AdminApp\Modules\SiteEditor\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Template_Types extends Base_Endpoint {
	/**
	 * @return string
	 */
	public function get_name() {
		return 'template-types';
	}

	public function get_items( $request ) {
        /** @var Module $site_editor_module */
        $site_editor_module = Module::instance();
	    return $site_editor_module->get_template_types();
	}
}
