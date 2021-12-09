<?php
namespace Kitify_Extension\Modules;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Controls_Manager;

class General_Extensions {

    public function __construct() {
        add_action('elementor/element/column/layout/before_section_end', [ $this , 'add_order_into_column' ], 99);
        add_action('elementor/element/before_section_end', [ $this , 'add_order_into_widget' ], 99, 2);
        if ( version_compare( ELEMENTOR_VERSION, '3.4.0', '<' ) ) {
            add_action('elementor/element/before_section_end', [ $this , 'add_breakpoint_visible_control' ], 99, 2);
        }
    }

    public function add_order_into_column( $stack ){

	    $active_breakpoints = [
	    	'no' => esc_html__('None', 'kitify')
	    ];
	    $active_breakpoints = array_merge($active_breakpoints, kitify_helper()->get_active_breakpoints(false, true));

        $stack->add_responsive_control(
            '_c_order',
            array(
                'label'   => esc_html__( 'Column Order', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -5,
                'max'     => 10,
                'step'    => 1,
                'selectors'  => array(
                    '{{WRAPPER}}' => 'order: {{VALUE}};-webkit-order: {{VALUE}};',
                ),
            )
        );
	    $stack->add_control(
		    'kitify_col_width_auto',
		    [
			    'label' => __( 'Enable Column AutoWidth', 'kitify' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => 'no',
			    'prefix_class' => 'kitify-col-width-auto-',
			    'options' => $active_breakpoints,
		    ]
	    );
	    $stack->add_control(
		    'kitify_col_align',
		    array(
			    'label'   => esc_html__( 'Column Align', 'kitify' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => 'none',
			    'options' => array(
				    'none'      => esc_html__( 'None', 'kitify' ),
				    'left'      => esc_html__( 'Left', 'kitify' ),
				    'center'    => esc_html__( 'Center', 'kitify' ),
				    'right'     => esc_html__( 'Right', 'kitify' ),
			    ),
			    'prefix_class' => 'kitify-col-align-',
			    'condition' => [
				    'kitify_col_width_auto!' => 'no'
			    ]
		    )
	    );

      $stack->add_control(
        'kitify_col_disable_relative',
        [
          'label' => __( 'Disable Column Relative', 'kitify' ),
          'type'    => Controls_Manager::SELECT,
          'default' => 'no',
          'prefix_class' => 'kitify-disable-relative-',
          'options' => array(
				    'no'      => esc_html__( 'No', 'kitify' ),
				    'yes'      => esc_html__( 'Yes', 'kitify' ),
			    ),
        ]
      );
    }

    public function add_order_into_widget( $stack, $section_id ) {
        if($section_id == '_section_style'){
            $stack->add_responsive_control(
                '_w_order',
                array(
                    'label'   => esc_html__( 'Widget Order', 'kitify' ),
                    'type'    => Controls_Manager::NUMBER,
                    'min'     => -5,
                    'max'     => 100,
                    'step'    => 1,
                    'selectors'  => array(
                        '{{WRAPPER}}' => 'order: {{VALUE}};-webkit-order: {{VALUE}};',
                    ),
                )
            );
	        $stack->add_control(
		        '_w_fullright',
		        [
			        'label' => __( 'Wide widget on the right', 'kitify' ),
			        'type' => Controls_Manager::SWITCHER,
			        'default' => '',
			        'prefix_class' => '',
			        'label_on' => __('Yes', 'kitify'),
			        'label_off' => __('No', 'kitify'),
			        'return_value' => 'widget_full_right',
		        ]
	        );
        }
    }

    public function add_breakpoint_visible_control( $stack, $section_id ){
        if( '_section_responsive' === $section_id ) {
            if(kitify()->elementor()->breakpoints->get_active_breakpoints('laptop')){
                $stack->add_control(
                    'hide_laptop',
                    [
                        'label' => __( 'Hide On Laptop', 'kitify' ),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => '',
                        'prefix_class' => 'elementor-',
                        'label_on' => 'Hide',
                        'label_off' => 'Show',
                        'return_value' => 'hidden-laptop',
                    ]
                );
            }
            if(kitify()->elementor()->breakpoints->get_active_breakpoints('mobile_extra')){
                $stack->add_control(
                    'hide_mobile_extra',
                    [
                        'label' => __( 'Hide On Mobile Extra', 'kitify' ),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => '',
                        'prefix_class' => 'elementor-',
                        'label_on' => 'Hide',
                        'label_off' => 'Show',
                        'return_value' => 'hidden-mobile-extra',
                    ]
                );
            }
        }
    }
}

new General_Extensions();
