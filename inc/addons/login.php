<?php

/**
 * Class: Kitify_Login
 * Name: Login Form
 * Slug: kitify-login-frm
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Login Widget
 */
class Kitify_Login extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/login.css'), ['kitify-base'], kitify()->get_version());
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-login-frm';
    }

    protected function get_widget_title() {
        return esc_html__( 'Login Form', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-login';
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
            'label_password',
            array(
                'label'   => esc_html__( 'Password Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Password', 'kitify' ),
            )
        );

        $this->_add_control(
            'label_remember',
            array(
                'label'   => esc_html__( 'Remember Me Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Remember Me', 'kitify' ),
            )
        );

        $this->_add_control(
            'label_log_in',
            array(
                'label'   => esc_html__( 'Log In Button Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Log In', 'kitify' ),
            )
        );

        $this->_add_control(
            'login_redirect',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Redirect After Login', 'kitify' ),
                'default'    => 'home',
                'options'    => array(
                    'home'   => esc_html__( 'Home page', 'kitify' ),
                    'left'   => esc_html__( 'Stay on the current page', 'kitify' ),
                    'custom' => esc_html__( 'Custom URL', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'login_redirect_url',
            array(
                'label'     => esc_html__( 'Redirect URL', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'dynamic'   => array(
                    'active' => true,
                ),
                'condition' => array(
                    'login_redirect' => 'custom',
                ),
            )
        );

        $this->_add_control(
            'label_logged_in',
            array(
                'label'   => esc_html__( 'Logged in message', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'You already logged in', 'kitify' ),
            )
        );

        $this->_add_control(
            'lost_password_link',
            array(
                'label'   => esc_html__( 'Lost Password link', 'kitify' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            )
        );

        $this->_add_control(
            'lost_password_link_text',
            array(
                'label'     => esc_html__( 'Lost Password link text', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Lost your password?', 'kitify' ),
                'condition' => array(
                    'lost_password_link' => 'yes',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'login_fields_style',
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
                    '{{WRAPPER}} .kitify-login input.input' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .kitify-login input.input' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .kitify-login input.input',
            ),
            50
        );

        $this->_add_responsive_control(
            'input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-login input.input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .kitify-login input.input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector'       => '{{WRAPPER}} .kitify-login input.input',
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
                    '{{WRAPPER}} .kitify-login input.input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-login input.input',
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'login_labels_style',
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
                    '{{WRAPPER}} .kitify-login label' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .kitify-login label' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'labels_typography',
                'selector' => '{{WRAPPER}} .kitify-login label',
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
                    '{{WRAPPER}} .kitify-login label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .kitify-login label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector'       => '{{WRAPPER}} .kitify-login label',
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
                    '{{WRAPPER}} .kitify-login label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'labels_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-login label',
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'login_submit_style',
            array(
                'label'      => esc_html__( 'Submit', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_form_submit_style' );

        $this->_start_controls_tab(
            'login_form_submit_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'login_submit_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'login_submit_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'login_form_submit_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'login_submit_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]:hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'login_submit_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]:hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'login_submit_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'login_submit_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]:hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'login_submit_typography',
                'selector'  => '{{WRAPPER}} input[type="submit"]',
                'fields_options' => array(
                    'typography' => array(
                        'separator' => 'before',
                    ),
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'login_submit_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'login_submit_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'login_submit_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} input[type="submit"]',
            ),
            75
        );

        $this->_add_responsive_control(
            'login_submit_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'login_submit_box_shadow',
                'selector' => '{{WRAPPER}} input[type="submit"]',
            ),
            100
        );

        $this->_add_responsive_control(
            'login_submit_alignment',
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
                    '{{WRAPPER}} .login-submit' => 'text-align: {{VALUE}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'lost_password_link_style',
            array(
                'label'      => esc_html__( 'Lost Password Link', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'lost_password_link' => 'yes',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'lost_password_link_typography',
                'selector' => '{{WRAPPER}} .kitify-login-lost-password-link',
            ),
            50
        );

        $this->_add_control(
            'lost_password_link_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-login-lost-password-link' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'lost_password_link_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-login-lost-password-link:hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'lost_password_link_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-login-lost-password-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
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
                    '{{WRAPPER}} .kitify-login-message' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .kitify-login-message' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'errors_link_color',
            array(
                'label'  => esc_html__( 'Link Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-login-message a' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'errors_link_hover_color',
            array(
                'label'  => esc_html__( 'Link Hover Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-login-message a:hover' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .kitify-login-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .kitify-login-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector'       => '{{WRAPPER}} .kitify-login-message',
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
                    '{{WRAPPER}} .kitify-login-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'errors_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-login-message',
            ),
            100
        );

        $this->_end_controls_section();

    }

    protected function render() {

        $this->_context = 'render';

        $settings = $this->get_settings_for_display();

        if ( is_user_logged_in() && ! kitify_integration()->in_elementor() ) {

            $this->_open_wrap();
            echo $settings['label_logged_in'];
            $this->_close_wrap();

            return;
        }

        $this->_open_wrap();

        $redirect_url = site_url( $_SERVER['REQUEST_URI'] );

        switch ( $settings['login_redirect'] ) {

            case 'home':
                $redirect_url = esc_url( home_url( '/' ) );
                break;

            case 'custom':
                $redirect_url = esc_url( do_shortcode( $settings['login_redirect_url'] ) );
                break;
        }

        add_filter( 'login_form_bottom', array( $this, 'add_login_fields' ) );

        $login_form = wp_login_form( array(
            'echo'           => false,
            'redirect'       => $redirect_url,
            'label_username' => $settings['label_username'],
            'label_password' => $settings['label_password'],
            'label_remember' => $settings['label_remember'],
            'label_log_in'   => $settings['label_log_in'],
        ) );

        remove_filter( 'login_form_bottom', array( $this, 'add_login_fields' ) );

        $login_form = preg_replace( '/action=[\'\"].*?[\'\"]/', '', $login_form );

        echo '<div class="kitify-login">';
        echo $login_form;
        include $this->_get_global_template( 'lost-password-link' );
        include $this->_get_global_template( 'messages' );
        echo '</div>';

        $this->_close_wrap();
    }

    /**
     * Add form fields
     *
     * @param  string $content
     * @return string
     */
    public function add_login_fields( $content ) {
        $content .= '<input type="hidden" name="kitify_login" value="1">';
        return $content;
    }
    
}