<?php

namespace KitifyExtensions\Elementor\Controls;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Control_Query extends \Elementor\Control_Select2 {


    const QUERY_CONTROL_ID = 'kitify-query';

    const AUTOCOMPLETE_ERROR_CODE = 'QueryControlAutocomplete';
    const GET_TITLES_ERROR_CODE = 'QueryControlGetTitles';

    // Supported objects for query:
    const QUERY_OBJECT_POST = 'post';
    const QUERY_OBJECT_TAX = 'tax';
    const QUERY_OBJECT_AUTHOR = 'author';
    const QUERY_OBJECT_USER = 'user';
    const QUERY_OBJECT_LIBRARY_TEMPLATE = 'library_template';
    const QUERY_OBJECT_ATTACHMENT = 'attachment';

    // Objects that are manipulated by js (not sent in AJAX):
    const QUERY_OBJECT_CPT_TAX = 'cpt_tax';
    const QUERY_OBJECT_JS = 'js';

	public function get_type() {
		return self::get_control_type();
	}

	public static function get_control_type() {
		return self::QUERY_CONTROL_ID;
	}

	/**
	 * @param string|array $value
	 * @param array $config
	 *
	 * @return string|array
	 */
	public function before_save( $value, array $config ) {
		if ( ! is_array( $value ) ) {
			if ( ! empty( $value ) ) {
				$value = absint( $value );
			}
		} else {
			$value = array_map( 'absint', $value );
		}

		return $value;
	}

	protected function get_default_settings() {
		return array_merge(
			parent::get_default_settings(), array(
				'query'      => array(),
			)
		);
	}
}
