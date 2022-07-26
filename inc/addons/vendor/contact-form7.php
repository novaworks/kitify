<?php

/**
 * Class: Kitify_Contact_Form7
 * Name: Contact Form 7
 * Slug: kitify-contactform7
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Kitify_ContactForm7 Widget
 */
class Kitify_Contact_Form7 extends Kitify_Base {

    public function get_name() {
        return 'kitify-contactform7';
    }

    public function get_title() {
        return esc_html__( 'Contact Form 7', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-form7';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Form', 'kitify' ),
            )
        );

        $avaliable_forms = $this->get_availbale_forms();

        $active_form = '';

        if ( ! empty( $avaliable_forms ) ) {
            $active_form = array_keys( $avaliable_forms )[0];
        }

        $this->add_control( 'form_shortcode', array(
            'label'   => esc_html__( 'Select Form', 'kitify' ),
            'type'    => Controls_Manager::SELECT,
            'default' => $active_form,
            'options' => $avaliable_forms,
        ) );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_text_style',
            array(
                'label'      => esc_html__( 'Form Texts', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'text_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 *:not(.wpcf7-form-control):not(option)' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_typography',
                'selector' => '{{WRAPPER}} .wpcf7 *:not(.wpcf7-form-control):not(option)',
            )
        );

        $this->add_control(
            'invalid_heading',
            array(
                'label'     => esc_html__( 'Not Valid Notices', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'invalid_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 span.wpcf7-not-valid-tip' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'invalid_typography',
                'selector' => '{{WRAPPER}} .wpcf7 span.wpcf7-not-valid-tip',
            )
        );

        $this->add_responsive_control(
            'invalid_notice_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 span.wpcf7-not-valid-tip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};display: block;',
                ),
            )
        );

        $this->add_responsive_control(
            'invalid_notice_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 span.wpcf7-not-valid-tip' => 'text-align: {{VALUE}};display: block;',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_inputs_style',
            array(
                'label'      => esc_html__( 'Controls', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_input_style' );

        $this->start_controls_tab(
            'tab_input_noraml',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'input_background',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]):not(.wpcf7-acceptance)',
            )
        );

        $this->add_control(
            'input_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"])' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'input_placeholder_color',
            array(
                'label'     => esc_html__( 'Placeholder Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-form-control::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-form-control::-moz-placeholder'          => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-form-control:-ms-input-placeholder'      => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"])',
            )
        );

        $this->add_responsive_control(
            'input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'input_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form-control-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'input_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]):not(.wpcf7-acceptance)',
            )
        );

        $this->add_responsive_control(
            'input_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]):not(.wpcf7-acceptance)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]):not(.wpcf7-acceptance)',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_focus',
            array(
                'label' => esc_html__( 'Focus', 'kitify' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'input_focus_background',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]):not(.wpcf7-acceptance):focus',
            )
        );

        $this->add_control(
            'input_focus_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]):not(.wpcf7-acceptance):focus' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'input_placeholder_focus_color',
            array(
                'label'     => esc_html__( 'Placeholder Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-form-control:focus::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-form-control:focus::-moz-placeholder'          => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-form-control:focus:-ms-input-placeholder'      => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'input_focus_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]):not(.wpcf7-acceptance):focus',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_focus_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]):not(.wpcf7-acceptance):focus',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_invalid',
            array(
                'label' => esc_html__( 'Not Valid', 'kitify' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'input_invalid_background',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]).wpcf7-not-valid',
            )
        );

        $this->add_control(
            'input_invalid_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]).wpcf7-not-valid' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'input_invalid_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]).wpcf7-not-valid',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_invalid_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form-control:not(.wpcf7-submit):not([type="checkbox"]):not([type="radio"]).wpcf7-not-valid',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'select_min_height',
            array(
                'label'       => esc_html__( 'Select Minimal Height', 'kitify' ),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form-control.wpcf7-select' => 'height: {{VALUE}}px; min-height: {{VALUE}}px;',
                ),
            )
        );
        $this->add_responsive_control(
            'textarea_min_height',
            array(
                'label'       => esc_html__( 'Textarea Minimal Height', 'kitify' ),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .wpcf7 .wpcf7-form-control.wpcf7-textarea' => 'height: {{VALUE}}px; min-height: {{VALUE}}px;',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'submit_style',
            array(
                'label'      => esc_html__( 'Submit Button', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_submit_style' );

        $this->start_controls_tab(
            'submit_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->add_control(
            'submit_bg',
            array(
                'label'       => _x( 'Background Type', 'Background Control', 'kitify' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'color' => array(
                        'title' => _x( 'Classic', 'Background Control', 'kitify' ),
                        'icon'  => 'eicon-paint-brush',
                    ),
                    'gradient' => array(
                        'title' => _x( 'Gradient', 'Background Control', 'kitify' ),
                        'icon'  => 'eicon-barcode',
                    ),
                ),
                'default'     => 'color',
                'label_block' => false,
                'render_type' => 'ui',
            )
        );

        $this->add_control(
            'submit_bg_color',
            array(
                'label'     => _x( 'Color', 'Background Control', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'title'     => _x( 'Background Color', 'Background Control', 'kitify' ),
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'submit_bg' => array( 'color', 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'submit_bg_color_stop',
            array(
                'label'      => _x( 'Location', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 0,
                ),
                'render_type' => 'ui',
                'condition' => array(
                    'submit_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_bg_color_b',
            array(
                'label'       => _x( 'Second Color', 'Background Control', 'kitify' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#f2295b',
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_bg_color_b_stop',
            array(
                'label'      => _x( 'Location', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 100,
                ),
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_bg_gradient_type',
            array(
                'label'   => _x( 'Type', 'Background Control', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'linear' => _x( 'Linear', 'Background Control', 'kitify' ),
                    'radial' => _x( 'Radial', 'Background Control', 'kitify' ),
                ),
                'default'     => 'linear',
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_bg_gradient_angle',
            array(
                'label'      => _x( 'Angle', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'deg' ),
                'default'    => array(
                    'unit' => 'deg',
                    'size' => 180,
                ),
                'range' => array(
                    'deg' => array(
                        'step' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{submit_bg_color.VALUE}} {{submit_bg_color_stop.SIZE}}{{submit_bg_color_stop.UNIT}}, {{submit_bg_color_b.VALUE}} {{submit_bg_color_b_stop.SIZE}}{{submit_bg_color_b_stop.UNIT}})',
                ),
                'condition' => array(
                    'submit_bg'               => array( 'gradient' ),
                    'submit_bg_gradient_type' => 'linear',
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_bg_gradient_position',
            array(
                'label'   => _x( 'Position', 'Background Control', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'center center' => _x( 'Center Center', 'Background Control', 'kitify' ),
                    'center left'   => _x( 'Center Left', 'Background Control', 'kitify' ),
                    'center right'  => _x( 'Center Right', 'Background Control', 'kitify' ),
                    'top center'    => _x( 'Top Center', 'Background Control', 'kitify' ),
                    'top left'      => _x( 'Top Left', 'Background Control', 'kitify' ),
                    'top right'     => _x( 'Top Right', 'Background Control', 'kitify' ),
                    'bottom center' => _x( 'Bottom Center', 'Background Control', 'kitify' ),
                    'bottom left'   => _x( 'Bottom Left', 'Background Control', 'kitify' ),
                    'bottom right'  => _x( 'Bottom Right', 'Background Control', 'kitify' ),
                ),
                'default' => 'center center',
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{submit_bg_color.VALUE}} {{submit_bg_color_stop.SIZE}}{{submit_bg_color_stop.UNIT}}, {{submit_bg_color_b.VALUE}} {{submit_bg_color_b_stop.SIZE}}{{submit_bg_color_b_stop.UNIT}})',
                ),
                'condition' => array(
                    'submit_bg'               => array( 'gradient' ),
                    'submit_bg_gradient_type' => 'radial',
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_color',
            array(
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'submit_typography',
                'selector' => '{{WRAPPER}}  .wpcf7 input.wpcf7-submit',
            )
        );

        $this->add_control(
            'submit_text_decor',
            array(
                'label'   => esc_html__( 'Text Decoration', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'none'      => esc_html__( 'None', 'kitify' ),
                    'underline' => esc_html__( 'Underline', 'kitify' ),
                ),
                'default' => 'none',
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'text-decoration: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'submit_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'submit_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'submit_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .wpcf7 input.wpcf7-submit',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'submit_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 input.wpcf7-submit',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submit_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->add_control(
            'submit_hover_bg',
            array(
                'label'       => _x( 'Background Type', 'Background Control', 'kitify' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'color' => array(
                        'title' => _x( 'Classic', 'Background Control', 'kitify' ),
                        'icon'  => 'eicon-paint-brush',
                    ),
                    'gradient' => array(
                        'title' => _x( 'Gradient', 'Background Control', 'kitify' ),
                        'icon'  => 'eicon-barcode',
                    ),
                ),
                'default'     => 'color',
                'label_block' => false,
                'render_type' => 'ui',
            )
        );

        $this->add_control(
            'submit_hover_bg_color',
            array(
                'label'     => _x( 'Color', 'Background Control', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'title'     => _x( 'Background Color', 'Background Control', 'kitify' ),
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'submit_hover_bg' => array( 'color', 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'submit_hover_bg_color_stop',
            array(
                'label'      => _x( 'Location', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 0,
                ),
                'render_type' => 'ui',
                'condition' => array(
                    'submit_hover_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_hover_bg_color_b',
            array(
                'label'       => _x( 'Second Color', 'Background Control', 'kitify' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#f2295b',
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_hover_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_hover_bg_color_b_stop',
            array(
                'label'      => _x( 'Location', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 100,
                ),
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_hover_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_hover_bg_gradient_type',
            array(
                'label'   => _x( 'Type', 'Background Control', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'linear' => _x( 'Linear', 'Background Control', 'kitify' ),
                    'radial' => _x( 'Radial', 'Background Control', 'kitify' ),
                ),
                'default'     => 'linear',
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_hover_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_hover_bg_gradient_angle',
            array(
                'label'      => _x( 'Angle', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'deg' ),
                'default'    => array(
                    'unit' => 'deg',
                    'size' => 180,
                ),
                'range' => array(
                    'deg' => array(
                        'step' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{submit_hover_bg_color.VALUE}} {{submit_hover_bg_color_stop.SIZE}}{{submit_hover_bg_color_stop.UNIT}}, {{submit_hover_bg_color_b.VALUE}} {{submit_hover_bg_color_b_stop.SIZE}}{{submit_hover_bg_color_b_stop.UNIT}})',
                ),
                'condition' => array(
                    'submit_hover_bg'               => array( 'gradient' ),
                    'submit_hover_bg_gradient_type' => 'linear',
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_hover_bg_gradient_position',
            array(
                'label'   => _x( 'Position', 'Background Control', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'center center' => _x( 'Center Center', 'Background Control', 'kitify' ),
                    'center left'   => _x( 'Center Left', 'Background Control', 'kitify' ),
                    'center right'  => _x( 'Center Right', 'Background Control', 'kitify' ),
                    'top center'    => _x( 'Top Center', 'Background Control', 'kitify' ),
                    'top left'      => _x( 'Top Left', 'Background Control', 'kitify' ),
                    'top right'     => _x( 'Top Right', 'Background Control', 'kitify' ),
                    'bottom center' => _x( 'Bottom Center', 'Background Control', 'kitify' ),
                    'bottom left'   => _x( 'Bottom Left', 'Background Control', 'kitify' ),
                    'bottom right'  => _x( 'Bottom Right', 'Background Control', 'kitify' ),
                ),
                'default' => 'center center',
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{submit_hover_bg_color.VALUE}} {{submit_hover_bg_color_stop.SIZE}}{{submit_hover_bg_color_stop.UNIT}}, {{submit_hover_bg_color_b.VALUE}} {{submit_hover_bg_color_b_stop.SIZE}}{{submit_hover_bg_color_b_stop.UNIT}})',
                ),
                'condition' => array(
                    'submit_hover_bg'               => array( 'gradient' ),
                    'submit_hover_bg_gradient_type' => 'radial',
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_hover_color',
            array(
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'submit_hover_typography',
                'label' => esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}}  .wpcf7 input.wpcf7-submit:hover',
            )
        );

        $this->add_control(
            'submit_hover_text_decor',
            array(
                'label'   => esc_html__( 'Text Decoration', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'none'      => esc_html__( 'None', 'kitify' ),
                    'underline' => esc_html__( 'Underline', 'kitify' ),
                ),
                'default' => 'none',
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover' => 'text-decoration: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'submit_hover_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'submit_hover_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'submit_hover_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'submit_hover_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 input.wpcf7-submit:hover',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submit_focus',
            array(
                'label' => esc_html__( 'Focus', 'kitify' ),
            )
        );

        $this->add_control(
            'submit_focus_bg',
            array(
                'label'       => _x( 'Background Type', 'Background Control', 'kitify' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => array(
                    'color' => array(
                        'title' => _x( 'Classic', 'Background Control', 'kitify' ),
                        'icon'  => 'eicon-paint-brush',
                    ),
                    'gradient' => array(
                        'title' => _x( 'Gradient', 'Background Control', 'kitify' ),
                        'icon'  => 'eicon-barcode',
                    ),
                ),
                'default'     => 'color',
                'label_block' => false,
                'render_type' => 'ui',
            )
        );

        $this->add_control(
            'submit_focus_bg_color',
            array(
                'label'     => _x( 'Color', 'Background Control', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'title'     => _x( 'Background Color', 'Background Control', 'kitify' ),
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'submit_focus_bg' => array( 'color', 'gradient' ),
                ),
            )
        );

        $this->add_control(
            'submit_focus_bg_color_stop',
            array(
                'label'      => _x( 'Location', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 0,
                ),
                'render_type' => 'ui',
                'condition' => array(
                    'submit_focus_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_focus_bg_color_b',
            array(
                'label'       => _x( 'Second Color', 'Background Control', 'kitify' ),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#f2295b',
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_focus_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_focus_bg_color_b_stop',
            array(
                'label'      => _x( 'Location', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 100,
                ),
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_focus_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_focus_bg_gradient_type',
            array(
                'label'   => _x( 'Type', 'Background Control', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'linear' => _x( 'Linear', 'Background Control', 'kitify' ),
                    'radial' => _x( 'Radial', 'Background Control', 'kitify' ),
                ),
                'default'     => 'linear',
                'render_type' => 'ui',
                'condition'   => array(
                    'submit_focus_bg' => array( 'gradient' ),
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_focus_bg_gradient_angle',
            array(
                'label'      => _x( 'Angle', 'Background Control', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'deg' ),
                'default'    => array(
                    'unit' => 'deg',
                    'size' => 180,
                ),
                'range' => array(
                    'deg' => array(
                        'step' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{submit_focus_bg_color.VALUE}} {{submit_focus_bg_color_stop.SIZE}}{{submit_focus_bg_color_stop.UNIT}}, {{submit_focus_bg_color_b.VALUE}} {{submit_focus_bg_color_b_stop.SIZE}}{{submit_focus_bg_color_b_stop.UNIT}})',
                ),
                'condition' => array(
                    'submit_focus_bg'               => array( 'gradient' ),
                    'submit_focus_bg_gradient_type' => 'linear',
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_focus_bg_gradient_position',
            array(
                'label'   => _x( 'Position', 'Background Control', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'center center' => _x( 'Center Center', 'Background Control', 'kitify' ),
                    'center left'   => _x( 'Center Left', 'Background Control', 'kitify' ),
                    'center right'  => _x( 'Center Right', 'Background Control', 'kitify' ),
                    'top center'    => _x( 'Top Center', 'Background Control', 'kitify' ),
                    'top left'      => _x( 'Top Left', 'Background Control', 'kitify' ),
                    'top right'     => _x( 'Top Right', 'Background Control', 'kitify' ),
                    'bottom center' => _x( 'Bottom Center', 'Background Control', 'kitify' ),
                    'bottom left'   => _x( 'Bottom Left', 'Background Control', 'kitify' ),
                    'bottom right'  => _x( 'Bottom Right', 'Background Control', 'kitify' ),
                ),
                'default' => 'center center',
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{submit_focus_bg_color.VALUE}} {{submit_focus_bg_color_stop.SIZE}}{{submit_focus_bg_color_stop.UNIT}}, {{submit_focus_bg_color_b.VALUE}} {{submit_focus_bg_color_b_stop.SIZE}}{{submit_focus_bg_color_b_stop.UNIT}})',
                ),
                'condition' => array(
                    'submit_focus_bg'               => array( 'gradient' ),
                    'submit_focus_bg_gradient_type' => 'radial',
                ),
                'of_type' => 'gradient',
            )
        );

        $this->add_control(
            'submit_focus_color',
            array(
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'submit_focus_typography',
                'label' => esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}}  .wpcf7 input.wpcf7-submit:focus',
            )
        );

        $this->add_control(
            'submit_focus_text_decor',
            array(
                'label'   => esc_html__( 'Text Decoration', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'none'      => esc_html__( 'None', 'kitify' ),
                    'underline' => esc_html__( 'Underline', 'kitify' ),
                ),
                'default' => 'none',
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus' => 'text-decoration: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'submit_focus_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'submit_focus_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'submit_focus_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'submit_focus_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 input.wpcf7-submit:focus',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'arrows',
            array(
                'label'        => esc_html__( 'Fullwidth Button', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'block',
                'default'      => '',
                'selectors'    => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'display: {{VALUE}}; width: 100%;',
                ),
            )
        );

        $this->add_responsive_control(
            'submit_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 input.wpcf7-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_alerts_style',
            array(
                'label'      => esc_html__( 'Alerts', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'alert_typography',
                'selector' => '{{WRAPPER}} .wpcf7 div.wpcf7-response-output',
            )
        );

        $this->add_responsive_control(
            'alert_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-response-output' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'alert_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-response-output' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'alert_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-response-output' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'alert_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-response-output' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'sent_heading',
            array(
                'label'     => esc_html__( 'Sent Success', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'sent_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-mail-sent-ok' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'sent_bg',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-mail-sent-ok' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'sent_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .wpcf7 div.wpcf7-mail-sent-ok',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'sent_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 div.wpcf7-mail-sent-ok',
            )
        );

        $this->add_control(
            'error_heading',
            array(
                'label'     => esc_html__( 'Sent Error', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'error_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-mail-sent-ng' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'error_bg',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-mail-sent-ng' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'error_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .wpcf7 div.wpcf7-mail-sent-ng',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'error_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 div.wpcf7-mail-sent-ng',
            )
        );

        $this->add_control(
            'invalid_alert_heading',
            array(
                'label'     => esc_html__( 'Not Valid', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'invalid_alert_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-validation-errors' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'invalid_alert_bg',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-validation-errors' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'invalid_alert_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .wpcf7 div.wpcf7-validation-errors',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'invalid_alert_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 div.wpcf7-validation-errors',
            )
        );

        $this->add_control(
            'spam_heading',
            array(
                'label'     => esc_html__( 'Spam Blocked', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'spam_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-spam-blocked' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'spam_bg',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wpcf7 div.wpcf7-spam-blocked' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'spam_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .wpcf7 div.wpcf7-spam-blocked',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'spam_box_shadow',
                'selector' => '{{WRAPPER}} .wpcf7 div.wpcf7-spam-blocked',
            )
        );

        $this->end_controls_section();
    }

    /**
     * Retrieve available forms list.
     * @return [type] [description]
     */
    protected function get_availbale_forms() {

        if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
            return array();
        }

        $forms = \WPCF7_ContactForm::find( array(
            'orderby' => 'title',
            'order'   => 'ASC',
        ) );

        if ( empty( $forms ) ) {
            return array();
        }

        $result = array();

        foreach ( $forms as $item ) {
            $key            = sprintf( '%1$s::%2$s', $item->id(), $item->title() );
            $result[ $key ] = $item->title();
        }

        return $result;
    }

    /**
     * [render description]
     *
     * @return [type] [description]
     */
    protected function render() {

        $settings = $this->get_settings();

        $this->_context = 'render';

        $this->_open_wrap();

        $avaliable_forms = $this->get_availbale_forms();

        $shortcode = $this->get_settings( 'form_shortcode' );

        if ( ! array_key_exists( $shortcode, $avaliable_forms ) ) {
            $shortcode = array_keys( $avaliable_forms )[0];
        }

        $data = explode( '::', $shortcode );

        if ( ! empty( $data ) && 2 === count( $data ) ) {
            echo do_shortcode( sprintf( '[contact-form-7 id="%1$d" title="%2$s"]', $data[0], $data[1] ) );
        }

        $this->_close_wrap();

    }

}
