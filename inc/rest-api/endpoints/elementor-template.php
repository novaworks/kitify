<?php
namespace Kitify\Endpoints;

if ( ! defined( 'WPINC' ) ) {
	die;
}

// If this file is called directly, abort.
use Kitify\Template_Helper;

/**
 * Define Posts class
 */
class Elementor_Template extends Base {

	/**
	 * [$depended_scripts description]
	 * @var array
	 */
	public $depended_scripts = [];

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'elementor-template';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

        return array(
            'id' => array(
                'default'    => '',
                'required'   => false,
            ),
            'dev' => array(
                'default'    => 'false',
                'required'   => false,
            ),
            'raw' => array(
                'default'    => 'false',
                'required'   => false,
            ),
        );
	}

    public function callback( $request ) {

        $args = $request->get_params();

        $helper = Template_Helper::get_instance();

        return $helper->callback($args);

    }

}
