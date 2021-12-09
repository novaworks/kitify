<?php
namespace Kitify\Endpoints;

if ( ! defined( 'WPINC' ) ) {
	die;
}

// If this file is called directly, abort.
use Kitify\Template_Helper;
use mysql_xdevapi\Exception;

/**
 * Define Posts class
 */
class Elementor_Widget extends Base {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'elementor-widget';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

        return array(
            'template_id' => array(
                'default'    => '',
                'required'   => true,
            ),
            'widget_id' => array(
                'default'    => '',
                'required'   => true,
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

        return $helper->widget_callback($args);

    }

}
