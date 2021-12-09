<?php
namespace Kitify_Dashboard\Compatibility\Theme;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Kitify_Dashboard\Compatibility\Base_Theme as Base_Theme;

class Hello extends Base_Theme {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'helloelementor';
	}

	/**
	 * [theme_info_data description]
	 * @param  array  $theme_data [description]
	 * @return [type]             [description]
	 */
	public function theme_info_data( $theme_data = array() ) {
		return $theme_data;
	}
}
