<?php

/**
 * Class: Kitify_Register
 * Name: Register Form
 * Slug: kitify-register-frm
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Register Widget
 */
class Kitify_Register extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/register.css'), ['kitify-base'], kitify()->get_version());
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-register-frm';
    }

    protected function get_widget_title() {
        return esc_html__( 'Register Form', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-register';
    }

    protected function register_controls() {
        $this->_start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'kitify' ),
            )
        );

        $this->_add_control(
            'label_username',
            array(
                'label'   => esc_html__( 'Username Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Username', 'kitify' ),
            )
        );

        $this->_add_control(
            'placeholder_username',
            array(
                'label'   => esc_html__( 'Username Placeholder', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Username', 'kitify' ),
            )
        );

        $this->_add_control(
            'label_email',
            array(
                'label'   => esc_html__( 'Email Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Email', 'kitify' ),
            )
        );

        $this->_add_control(
            'placeholder_email',
            array(
                'label'   => esc_html__( 'Email Placeholder', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Email', 'kitify' ),
            )
        );

        $this->_add_control(
            'label_pass',
            array(
                'label'   => esc_html__( 'Password Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Password', 'kitify' ),
            )
        );

        $this->_add_control(
            'placeholder_pass',
            array(
                'label'   => esc_html__( 'Password Placeholder', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Password', 'kitify' ),
            )
        );

        $this->_add_control(
            'confirm_password',
            array(
                'label'        => esc_html__( 'Show Confirm Password Field', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->_add_control(
            'label_pass_confirm',
            array(
                'label'     => esc_html__( 'Confirm Password Label', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Please Confirm Password', 'kitify' ),
                'condition' => array(
                    'confirm_password' => 'yes'
                )
            )
        );

        $this->_add_control(
            'placeholder_pass_confirm',
            array(
                'label'     => esc_html__( 'Confirm Password Placeholder', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Confirm Password', 'kitify' ),
                'condition' => array(
                    'confirm_password' => 'yes'
                )
            )
        );

        $this->_add_control(
            'label_submit',
            array(
                'label'   => esc_html__( 'Register Button Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Register', 'kitify' ),
            )
        );

        $this->_add_control(
            'register_redirect',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Redirect After Register', 'kitify' ),
                'default'    => 'home',
                'options'    => array(
                    'home'   => esc_html__( 'Home page', 'kitify' ),
                    'left'   => esc_html__( 'Stay on the current page', 'kitify' ),
                    'custom' => esc_html__( 'Custom URL', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'register_redirect_url',
            array(
                'label'     => esc_html__( 'Redirect URL', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => array(
                    'register_redirect' => 'custom',
                ),
            )
        );

        $this->_add_control(
            'label_registered',
            array(
                'label'   => esc_html__( 'User Registered Message', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'You already registered', 'kitify' ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'register_fields_style',
            array(
                'label'      => esc_html__( 'Fields', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'input_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__input' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'input_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__input' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .kitify-register__input',
            ),
            50
        );

        $this->_add_control(
            'placeholder_style',
            array(
                'label'     => esc_html__( 'Placeholder', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

        $this->_add_control(
            'input_placeholder_color',
            array(
                'label'  => esc_html__( 'Placeholder Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .kitify-register__input::-moz-placeholder'          => 'color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-register__input:-ms-input-placeholder'      => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_placeholder_typography',
                'selector' => '{{WRAPPER}} .kitify-register__input::-webkit-input-placeholder',
            ),
            50
        );

        $this->_add_responsive_control(
            'input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'input_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'input_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .kitify-register__input',
            ),
            50
        );

        $this->_add_responsive_control(
            'input_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-register__input',
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'register_labels_style',
            array(
                'label'      => esc_html__( 'Labels', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'labels_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__label' => 'background-color: {{VALUE}}',
                ),
            ),
            50
        );

        $this->_add_control(
            'labels_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__label' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'labels_typography',
                'selector' => '{{WRAPPER}} .kitify-register__label',
            ),
            50
        );

        $this->_add_responsive_control(
            'labels_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'labels_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'labels_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .kitify-register__label',
            ),
            75
        );

        $this->_add_responsive_control(
            'labels_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'labels_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-register__label',
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'register_submit_style',
            array(
                'label'      => esc_html__( 'Submit', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_form_submit_style' );

        $this->_start_controls_tab(
            'register_form_submit_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'register_submit_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__submit' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'register_submit_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__submit' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'register_form_submit_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'register_submit_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__submit:hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'register_submit_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__submit:hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'register_submit_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'register_submit_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register__submit:hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'register_submit_typography',
                'selector' => '{{WRAPPER}} .kitify-register__submit',
                'fields_options' => array(
                    'typography' => array(
                        'separator' => 'before',
                    ),
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'register_submit_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'register_submit_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'register_submit_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .kitify-register__submit',
            ),
            75
        );

        $this->_add_responsive_control(
            'register_submit_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'register_submit_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-register__submit',
            ),
            100
        );

        $this->_add_responsive_control(
            'register_submit_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register-submit' => 'text-align: {{VALUE}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'login_errors_style',
            array(
                'label'      => esc_html__( 'Errors', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'errors_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register-message' => 'background-color: {{VALUE}}',
                ),
            ),
            50
        );

        $this->_add_control(
            'errors_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-register-message' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'errors_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'errors_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'errors_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .kitify-register-message',
            ),
            75
        );

        $this->_add_responsive_control(
            'errors_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-register-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'errors_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-register-message',
            ),
            100
        );

        $this->_end_controls_section();
    }

    protected function render() {

        $this->_context = 'render';

        $settings = $this->get_settings();

        if ( is_user_logged_in() && ! kitify_integration()->in_elementor() ) {

            $this->_open_wrap();
            echo $settings['label_registered'];
            $this->_close_wrap();

            return;
        }

        $registration_enabled = get_option( 'users_can_register' );

        if ( ! $registration_enabled && ! kitify_integration()->in_elementor() ) {

            $this->_open_wrap();
            esc_html_e( 'Registration disabled', 'kitify' );
            $this->_close_wrap();

            return;
        }

        $this->_open_wrap();

        $redirect_url = site_url( $_SERVER['REQUEST_URI'] );

        switch ( $settings['register_redirect'] ) {

            case 'home':
                $redirect_url = esc_url( home_url( '/' ) );
                break;

            case 'custom':
                $redirect_url = $settings['register_redirect_url'];
                break;
        }

        if ( ! $registration_enabled ) {
            esc_html_e( 'Registration currently disabled and this form will not be visible for guest users. Please, enable registration in Settings/General or remove this widget from the page.', 'kitify' );
        }

        include $this->_get_global_template( 'index' );

        $this->_close_wrap();
    }
    
}