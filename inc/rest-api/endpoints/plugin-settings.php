<?php
namespace Kitify\Endpoints;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Posts class
 */
class Plugin_Settings extends Base {

	/**
	 * [get_method description]
	 * @return [type] [description]
	 */
	public function get_method() {
		return 'POST';
	}

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'plugin-settings';
	}

	/**
     * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
     * Non-scalar values are ignored.
     *
     * @param string|array $var Data to sanitize.
     * @return string|array
	 */

    public function clean_var ( $var ){
        if ( is_array( $var ) ) {
            return array_map( array( $this, 'clean_var' ) , $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }

	public function callback( $request ) {

		$data = $request->get_params();

		$current = get_option( kitify_settings()->key, array() );

		if ( is_wp_error( $current ) ) {
			return rest_ensure_response( [
				'status'  => 'error',
				'message' => __( 'Server Error', 'kitify' )
			] );
		}

		foreach ( $data as $key => $value ) {
            if($key === 'head_code' || $key === 'footer_code' || $key === 'custom_css'){
                $current[ $key ] = apply_filters('kitify/settings/sanitize_save', $value, $value, $key);
            }
            else{
                $current[ $key ] = apply_filters('kitify/settings/sanitize_save', $this->clean_var( $value ), $value, $key);
            }
		}

		update_option( kitify_settings()->key, $current );

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Settings have been saved', 'kitify' )
		] );
	}

}
