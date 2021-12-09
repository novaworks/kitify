<?php
namespace Kitify\Endpoints;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Endpoint_Base class
 */
abstract class Base {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	abstract function get_name();

	/**
	 * API callback
	 * @return void
	 */
	abstract function callback( $request );

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Check user access to current end-popint
	 *
	 * @return bool
	 */
	public function permission_callback() {
		return true;
	}

	/**
	 * Get query param. Regex with query parameters
	 *
	 * Example:
	 *
	 * (?P<id>[\d]+)/(?P<meta_key>[\w-]+)
	 *
	 * @return string
	 */
	public function get_query_params() {
		return '';
	}

	/**
	 * Returns arguments config
	 *
	 * Example:
	 *
	 * 	array(
	 * 		array(
	 * 			'type' => array(
	 * 			'default'  => '',
	 * 			'required' => false,
	 * 		),
	 * 	)
	 *
	 * @return array
	 */
	public function get_args() {
		return array();
	}

}
