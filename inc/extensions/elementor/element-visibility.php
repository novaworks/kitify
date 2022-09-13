<?php

namespace KitifyExtensions\Elementor;
if (!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

class Element_Visibility {

    public function __construct() {
        add_action('elementor/element/common/_section_style/after_section_end', [
            $this,
            'init_module'
        ]);

        add_action('elementor/element/section/section_advanced/after_section_end', [
            $this,
            'init_module'
        ]);

		    add_action('elementor/element/container/section_layout/after_section_end', [
            $this,
            'init_module'
        ]);

        add_filter( 'elementor/widget/render_content', [ $this, 'content_change' ], 999, 2 );
//        add_filter( 'elementor/section/render_content', [ $this, 'content_change' ], 999, 2 );
//        add_filter( 'elementor/container/render_content', [ $this, 'content_change' ], 999, 2 );

        add_filter( 'elementor/frontend/section/should_render', [ $this, 'item_should_render' ], 10, 2 );
        add_filter( 'elementor/frontend/widget/should_render', [ $this, 'item_should_render' ], 10, 2 );
        add_filter( 'elementor/frontend/repeater/should_render', [ $this, 'item_should_render' ], 10, 2 );
        add_filter( 'elementor/frontend/container/should_render', [ $this, 'item_should_render' ], 10, 2 );
    }

    private function get_roles() {
        global $wp_roles;

        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new \WP_Roles();
        }
        $all_roles      = $wp_roles->roles;
        $editable_roles = apply_filters( 'editable_roles', $all_roles );

        $data = [
            'kitify-vlogic-guest' => esc_html__('Guests', 'kitify'),
            'kitify-vlogic-user' => esc_html__('Logged in users', 'kitify')
        ];

        foreach ( $editable_roles as $k => $role ) {
            $data[ $k ] = $role['name'];
        }

        return $data;
    }

    public function init_module($element) {
        $element->start_controls_section('section_kitify_vlogic', [
            'label' => esc_html__('Kitify Visibility Logic', 'kitify'),
            'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]);
        $element->add_control('kitify_vlogic_enabled', [
            'label' => esc_html__('Enable Conditions', 'kitify'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'render_type' => 'none',
            'default'      => '',
            'label_on'     => esc_html__( 'Yes', 'kitify' ),
            'label_off'    => esc_html__( 'No', 'kitify' ),
            'return_value' => 'yes',
        ]);
        $element->add_control('kitify_vlogic_role_visible', [
            'label' => esc_html__('Visible for:', 'kitify'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'render_type' => 'none',
            'options'     => $this->get_roles(),
            'multiple'    => true,
            'label_block' => true,
            'conditions' => [
	            'relation' => 'and',
	            'terms' => [
		            [
			            'name' => 'kitify_vlogic_enabled',
			            'operator' => '==',
			            'value' => 'yes'
		            ],
		            [
			            'name' => 'kitify_vlogic_role_hidden',
			            'operator' => '==',
			            'value' => ''
		            ]
	            ]
            ],
        ]);
        $element->add_control('kitify_vlogic_role_hidden', [
            'label' => esc_html__('Hidden for:', 'kitify'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'render_type' => 'none',
            'options'     => $this->get_roles(),
            'multiple'    => true,
            'label_block' => true,
            'conditions' => [
	            'relation' => 'and',
	            'terms' => [
		            [
			            'name' => 'kitify_vlogic_enabled',
			            'operator' => '==',
			            'value' => 'yes'
		            ],
		            [
			            'name' => 'kitify_vlogic_role_visible',
			            'operator' => '==',
			            'value' => ''
		            ]
	            ]
            ],
        ]);
        $element->end_controls_section();
    }

    public function item_should_render(  $should_render, $section ){

        if( \Elementor\Plugin::$instance->editor->is_edit_mode() ){
            return $should_render;
        }

        // Get the settings
        $settings = $section->get_settings();

        if ( ! $this->should_render( $settings ) ) {
            return false;
        }

        return $should_render;
    }

    /**
     * @param string $content
     * @param $widget \Elementor\Widget_Base
     *
     * @return string
     */
    public function content_change( $content, $widget ) {

        if( \Elementor\Plugin::$instance->editor->is_edit_mode() ){
            return $content;
        }

        // Get the settings
        $settings = $widget->get_settings();

        if ( ! $this->should_render( $settings ) ) {
            return '';
        }

        return $content;
    }

    /**
     * Check if conditions are matched
     *
     * @param array $settings
     *
     * @return boolean
     */
    private function should_render( $settings ) {
        $user_state = is_user_logged_in();

        if ( $settings['kitify_vlogic_enabled'] == 'yes' ) {

            //visible for
            if ( ! empty( $settings['kitify_vlogic_role_visible'] ) ) {
                if ( in_array( 'kitify-vlogic-guest', $settings['kitify_vlogic_role_visible'] ) ) {
                    if ( $user_state == true ) {
                        return false;
                    }
                }
                elseif ( in_array( 'kitify-vlogic-user', $settings['kitify_vlogic_role_visible'] ) ) {
                    if ( $user_state == false ) {
                        return false;
                    }
                }
                else {
                    if ( $user_state == false ) {
                        return false;
                    }
                    $user = wp_get_current_user();

                    $has_role = false;
                    foreach ( $settings['kitify_vlogic_role_visible'] as $setting ) {
                        if ( in_array( $setting, (array) $user->roles ) ) {
                            $has_role = true;
                        }
                    }
                    if ( $has_role === false ) {
                        return false;
                    }
                }

            } //hidden for
            elseif ( ! empty( $settings['kitify_vlogic_role_hidden'] ) ) {

                if ( $user_state === false && in_array( 'kitify-vlogic-guest', $settings['kitify_vlogic_role_hidden'], false ) ) {
                    return false;
                }
                elseif ( $user_state === true && in_array( 'kitify-vlogic-user', $settings['kitify_vlogic_role_hidden'], false ) ) {
                    return false;
                }
                else {
                    if ( $user_state === false ) {
                        return true;
                    }
                    $user = wp_get_current_user();

                    foreach ( $settings['kitify_vlogic_role_hidden'] as $setting ) {
                        if ( in_array( $setting, (array) $user->roles, false) ) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

}
