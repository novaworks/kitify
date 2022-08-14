<?php
namespace KitifyThemeBuilder\Modules;

use Elementor\Core\Base\Module as Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class Modules_Manager {

	private $modules = [];

	public function __construct() {
		$modules = [
						'theme-builder',
            'woocommerce',
						'dynamic-tags',
		];

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = '\KitifyThemeBuilder\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
            if( ( $class_name::is_active() && ( !kitify()->has_elementor_pro() || $module_name == 'woocommerce' ) ) ){
                $this->modules[ $module_name ] = $class_name::instance();
            }
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}
			return null;
		}

		return $this->modules;
	}
}
