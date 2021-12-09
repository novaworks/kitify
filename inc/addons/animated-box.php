<?php

/**
 * Class: Kitify_Animated_Box
 * Name: Animated Box
 * Slug: kitify-animated-box
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

// Elementor Classes
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Animated_Box Widget
 */
class Kitify_Animated_Box extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/animated-box.css'), ['kitify-base'], kitify()->get_version());

        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-animated-box';
    }

    protected function get_widget_title() {
        return esc_html__( 'Animated Box', 'kitify');
    }

    public function get_icon() {
        return 'kitify-icon-animated-box';
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'kitify/animated-box/css-scheme',
            array(
                'animated_box'                => '.kitify-animated-box',
                'animated_box_inner'          => '.kitify-animated-box__inner',
                'animated_box_front'          => '.kitify-animated-box__front',
                'animated_box_back'           => '.kitify-animated-box__back',
                'animated_box_front_inner'    => '.kitify-animated-box__front .kitify-animated-box__inner',
                'animated_box_back_inner'     => '.kitify-animated-box__back .kitify-animated-box__inner',
                'animated_box_icon'           => '.kitify-animated-box__icon',
                'animated_box_icon_box'       => '.kitify-animated-box__icon-box',
                'animated_box_title'          => '.kitify-animated-box__title',
                'animated_box_subtitle'       => '.kitify-animated-box__subtitle',
                'animated_box_description'    => '.kitify-animated-box__description',
                'animated_box_button'         => '.kitify-animated-box__button',
                'animated_box_button_icon'    => '.kitify-animated-box__button-icon',
                'animated_box_icon_front'     => '.kitify-animated-box__icon--front',
                'animated_box_icon_back'      => '.kitify-animated-box__icon--back',
                'animated_box_title_front'    => '.kitify-animated-box__title--front',
                'animated_box_title_back'     => '.kitify-animated-box__title--back',
                'animated_box_subtitle_front' => '.kitify-animated-box__subtitle--front',
                'animated_box_subtitle_back'  => '.kitify-animated-box__subtitle--back',
                'animated_box_desc_front'     => '.kitify-animated-box__description--front',
                'animated_box_desc_back'      => '.kitify-animated-box__description--back',
            )
        );

        $this->_start_controls_section(
            'section_front_content',
            array(
                'label' => esc_html__( 'Front Side Content', 'kitify' ),
            )
        );

        $this->_add_control(
            'front_side_icon',
            [
                'label' => __( 'Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon'
            ]
        );

        $this->_add_control(
            'front_side_title',
            array(
                'label'   => esc_html__( 'Title', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Title', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_add_control(
            'front_side_subtitle',
            array(
                'label'   => esc_html__( 'Subtitle', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Flip Box', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_add_control(
            'front_side_description',
            array(
                'label'   => esc_html__( 'Description', 'kitify' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Easily add or remove any text on your flip box!', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_back_content',
            array(
                'label' => esc_html__( 'Back Side Content', 'kitify' ),
            )
        );

        $this->_add_control(
            'back_side_icon',
            [
                'label' => __( 'Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon'
            ]
        );

        $this->_add_control(
            'back_side_title',
            array(
                'label'   => esc_html__( 'Title', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Back Side', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_add_control(
            'back_side_subtitle',
            array(
                'label'   => esc_html__( 'Subtitle', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_add_control(
            'back_side_description',
            array(
                'label'   => esc_html__( 'Description', 'kitify' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Easily add or remove any text on your flip box!', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_add_control(
            'back_side_button_text',
            array(
                'label'   => esc_html__( 'Button text', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Read More', 'kitify' ),
            )
        );

        $this->_add_control(
            'back_side_button_link',
            array(
                'label'       => esc_html__( 'Button Link', 'kitify' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'default' => array(
                    'url' => '#',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'kitify' ),
            )
        );

        $this->_add_control(
            'animation_effect',
            array(
                'label'   => esc_html__( 'Animation Effect', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'kitify-effect-1',
                'options' => array(
                    'kitify-effect-1'  => esc_html__( 'Flip Horizontal', 'kitify' ),
                    'kitify-effect-2'  => esc_html__( 'Flip Vertical', 'kitify' ),
                    'kitify-effect-3'  => esc_html__( 'Fall Up', 'kitify' ),
                    'kitify-effect-4'  => esc_html__( 'Fall Right', 'kitify' ),
                    'kitify-effect-5'  => esc_html__( 'Slide Down', 'kitify' ),
                    'kitify-effect-6'  => esc_html__( 'Slide Right', 'kitify' ),
                    'kitify-effect-7'  => esc_html__( 'Flip Horizontal 3D', 'kitify' ),
                    'kitify-effect-8'  => esc_html__( 'Flip Vertical 3D', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'title_html_tag',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'   => esc_html__( 'H1', 'kitify' ),
                    'h2'   => esc_html__( 'H2', 'kitify' ),
                    'h3'   => esc_html__( 'H3', 'kitify' ),
                    'h4'   => esc_html__( 'H4', 'kitify' ),
                    'h5'   => esc_html__( 'H5', 'kitify' ),
                    'h6'   => esc_html__( 'H6', 'kitify' ),
                    'div'  => esc_html__( 'div', 'kitify' ),
                    'span' => esc_html__( 'span', 'kitify' ),
                    'p'    => esc_html__( 'p', 'kitify' ),
                ),
                'default' => 'h3',
            )
        );

        $this->_add_control(
            'sub_title_html_tag',
            array(
                'label'   => esc_html__( 'Subtitle HTML Tag', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'   => esc_html__( 'H1', 'kitify' ),
                    'h2'   => esc_html__( 'H2', 'kitify' ),
                    'h3'   => esc_html__( 'H3', 'kitify' ),
                    'h4'   => esc_html__( 'H4', 'kitify' ),
                    'h5'   => esc_html__( 'H5', 'kitify' ),
                    'h6'   => esc_html__( 'H6', 'kitify' ),
                    'div'  => esc_html__( 'div', 'kitify' ),
                    'span' => esc_html__( 'span', 'kitify' ),
                    'p'    => esc_html__( 'p', 'kitify' ),
                ),
                'default' => 'h4',
            )
        );

        $this->_end_controls_section();

        /**
         * General Style Section
         */
        $this->_start_controls_section(
            'section_general_style',
            array(
                'label'      => esc_html__( 'General', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_responsive_control(
            'box_height',
            array(
                'label' => esc_html__( 'Height', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                ),
                'default' => [
                    'size' => 245,
                ],
                'selectors' => array(
                    '{{WRAPPER}} '.$css_scheme['animated_box'] => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_start_controls_tabs( 'general_style_tabs' );

        $this->_start_controls_tab(
            'tab_front_general_styles',
            array(
                'label' => esc_html__( 'Front', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'front_side_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_front']
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'front_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['animated_box_front'],
            )
        );

        $this->_add_responsive_control(
            'front_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_front'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['animated_box_front'] . ' .kitify-animated-box__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_front'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'front_box_shadow',
                'exclude' => array(
                    'box_shadow_position',
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_front'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_back_general_styles',
            array(
                'label' => esc_html__( 'Back', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'back_side_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_back']
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'back_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_back'],
            )
        );

        $this->_add_responsive_control(
            'back_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_back'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['animated_box_back'] . ' .kitify-animated-box__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_back'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'    => 'back_box_shadow',
                'exclude' => array(
                    'box_shadow_position',
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_back'],
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Box Style Section
         */
        $this->_start_controls_section(
            'section_box_inner_styles',
            array(
                'label'      => esc_html__( 'Inner Box', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->_start_controls_tabs( 'box_inner_style_tabs' );

        $this->_start_controls_tab(
            'tab_front_box_inner_styles',
            array(
                'label' => esc_html__( 'Front', 'kitify' ),
            )
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'front_inner_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_front_inner']
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'front_inner_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_front_inner'],
            )
        );

        $this->_add_responsive_control(
            'front_inner_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_front_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_inner_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_front_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'    => 'front_inner_shadow',
                'exclude' => array(
                    'box_shadow_position',
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_front_inner'],
            )
        );
        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_back_box_inner_styles',
            array(
                'label' => esc_html__( 'Back', 'kitify' ),
            )
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'back_inner_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_back_inner']
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'back_inner_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_back_inner'],
            )
        );

        $this->_add_responsive_control(
            'back_inner_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_back_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_inner_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_back_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'    => 'back_inner_shadow',
                'exclude' => array(
                    'box_shadow_position',
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_back_inner'],
            )
        );
        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Icon Style Section
         */
        $this->_start_controls_section(
            'section_icon_style',
            array(
                'label'      => esc_html__( 'Icon', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'icon_style_tabs' );

        $this->_start_controls_tab(
            'tab_front_icon_styles',
            array(
                'label' => esc_html__( 'Front', 'kitify' ),
            )
        );

        $this->_add_control(
            'front_icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-animated-box__icon--front' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'front_icon_bg_color',
            array(
                'label' => esc_html__( 'Icon Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-animated-box__icon--front .kitify-animated-box-icon-inner' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-animated-box__icon--front .kitify-animated-box-icon-inner' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_icon_size',
            array(
                'label'      => esc_html__( 'Icon Box Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .kitify-animated-box-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'front_icon_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .kitify-animated-box-icon-inner',
            )
        );

        $this->_add_control(
            'front_icon_box_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .kitify-animated-box-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_icon_box_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_front']. ' .kitify-animated-box-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'front_icon_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .kitify-animated-box-icon-inner',
            )
        );

        $this->_add_responsive_control(
            'front_icon_box_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_back_icon_styles',
            array(
                'label' => esc_html__( 'Back', 'kitify' ),
            )
        );

        $this->_add_control(
            'back_icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-animated-box__icon--back' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'back_icon_bg_color',
            array(
                'label' => esc_html__( 'Icon Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .kitify-animated-box-icon-inner' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-animated-box__icon--back .kitify-animated-box-icon-inner' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_icon_size',
            array(
                'label'      => esc_html__( 'Icon Box Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .kitify-animated-box-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'back_icon_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .kitify-animated-box-icon-inner',
            )
        );

        $this->_add_control(
            'back_icon_box_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .kitify-animated-box-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_icon_box_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .kitify-animated-box-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'back_icon_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .kitify-animated-box-icon-inner',
            )
        );

        $this->_add_responsive_control(
            'back_icon_box_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Title Style Section
         */
        $this->_start_controls_section(
            'section_title_style',
            array(
                'label'      => esc_html__( 'Title', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'title_style_tabs' );

        $this->_start_controls_tab(
            'tab_front_title_styles',
            array(
                'label' => esc_html__( 'Front', 'kitify' ),
            )
        );

        $this->_add_control(
            'front_title_color',
            array(
                'label'  => esc_html__( 'Title Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'front_title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_title_front'],
            )
        );

        $this->_add_responsive_control(
            'front_title_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_title_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_title_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_title_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_back_title_styles',
            array(
                'label' => esc_html__( 'Back', 'kitify' ),
            )
        );

        $this->_add_control(
            'back_title_color',
            array(
                'label'  => esc_html__( 'Title Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'back_title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_title_back'],
            )
        );

        $this->_add_responsive_control(
            'back_title_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_title_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_title_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_title_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Subtitle Style Section
         */
        $this->_start_controls_section(
            'section_subtitle_style',
            array(
                'label'      => esc_html__( 'Subtitle', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'subtitle_style_tabs' );

        $this->_start_controls_tab(
            'tab_front_subtitle_styles',
            array(
                'label' => esc_html__( 'Front', 'kitify' ),
            )
        );

        $this->_add_control(
            'front_subtitle_color',
            array(
                'label'  => esc_html__( 'Subtitle Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'front_subtitle_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'],
            )
        );

        $this->_add_responsive_control(
            'front_subtitle_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_subtitle_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_subtitle_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_subtitle_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_back_subtitle_styles',
            array(
                'label' => esc_html__( 'Back', 'kitify' ),
            )
        );

        $this->_add_control(
            'back_subtitle_color',
            array(
                'label'  => esc_html__( 'Subtitle Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'back_subtitle_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'],
            )
        );

        $this->_add_responsive_control(
            'back_subtitle_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_subtitle_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_subtitle_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_subtitle_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Description Style Section
         */
        $this->_start_controls_section(
            'section_description_style',
            array(
                'label'      => esc_html__( 'Description', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'description_style_tabs' );

        $this->_start_controls_tab(
            'tab_front_description_styles',
            array(
                'label' => esc_html__( 'Front', 'kitify' ),
            )
        );

        $this->_add_control(
            'front_description_color',
            array(
                'label'  => esc_html__( 'Description Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'front_description_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'],
            )
        );

        $this->_add_responsive_control(
            'front_description_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_description_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'front_description_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_back_description_styles',
            array(
                'label' => esc_html__( 'Back', 'kitify' ),
            )
        );

        $this->_add_control(
            'back_description_color',
            array(
                'label'  => esc_html__( 'Description Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'back_description_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'],
            )
        );

        $this->_add_responsive_control(
            'back_description_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_description_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'back_description_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Action Button Style Section
         */
        $this->_start_controls_section(
            'section_action_button_style',
            array(
                'label'      => esc_html__( 'Action Button', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_responsive_control(
            'back_button_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->_add_control(
            'add_button_icon',
            array(
                'label'        => esc_html__( 'Add Icon', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->_add_control(
            'button_icon',
            [
                'label' => __( 'Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => [
                    'add_button_icon' => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'button_icon_position',
            array(
                'label'   => esc_html__( 'Icon Position', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'before'  => esc_html__( 'Before Text', 'kitify' ),
                    'after' => esc_html__( 'After Text', 'kitify' ),
                ),
                'default'     => 'after',
                'render_type' => 'template',
                'condition' => array(
                    'add_button_icon' => 'yes',
                ),
            )
        );

        $this->_add_control(
            'button_icon_size',
            array(
                'label' => esc_html__( 'Icon Size', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 7,
                        'max' => 90,
                    ),
                ),
                'condition' => array(
                    'add_button_icon' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-wrap-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_control(
            'button_icon_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'add_button_icon' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-wrap-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-wrap-icon svg' => 'fill: {{VALUE}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'button_icon_margin',
            array(
                'label'      => esc_html__( 'Icon Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .elementor-wrap-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_start_controls_tabs( 'tabs_button_style' );

        $this->_start_controls_tab(
            'tab_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'button_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['animated_box_button'],
            )
        );

        $this->_add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'button_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'button_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_button'],
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_button'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'button_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_hover_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['animated_box_button'] . ':hover',
            )
        );

        $this->_add_responsive_control(
            'button_hover_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'button_hover_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'button_hover_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_hover_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover',
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Overlay Style Section
         */
        $this->_start_controls_section(
            'section_overlay_style',
            array(
                'label'      => esc_html__( 'Overlay', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_overlay_style' );

        $this->_start_controls_tab(
            'tab_front_overlay',
            array(
                'label' => esc_html__( 'Front', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'front_overlay_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_front'] . ' .kitify-animated-box__overlay',
            )
        );

        $this->_add_control(
            'front_overlay_opacity',
            array(
                'label'   => esc_html__( 'Opacity', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '0',
                'min'     => 0,
                'max'     => 1,
                'step'    => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_front'] . ' .kitify-animated-box__overlay' => 'opacity: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_back_overlay',
            array(
                'label' => esc_html__( 'Back', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'back_overlay_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_back'] . ' .kitify-animated-box__overlay',
            )
        );

        $this->_add_control(
            'back_overlay_opacity',
            array(
                'label'   => esc_html__( 'Opacity', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '0',
                'min'     => 0,
                'max'     => 1,
                'step'    => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_box_back'] . ' .kitify-animated-box__overlay' => 'opacity: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Order Style Section
         */
        $this->_start_controls_section(
            'section_order_style',
            array(
                'label'      => esc_html__( 'Order', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_order_style' );

        $this->_start_controls_tab(
            'tab_front_order',
            array(
                'label' => esc_html__( 'Front', 'kitify' ),
            )
        );

        $this->_add_control(
            'front_side_icon_order',
            array(
                'label'   => esc_html__( 'Icon Order', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 1,
                'max'     => 2,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['animated_box_icon_front'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->_add_control(
            'front_side_content_order',
            array(
                'label'   => esc_html__( 'Content Order', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 2,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['animated_box_front'] . ' .kitify-animated-box__content' => 'order: {{VALUE}};',
                ),
            )
        );

        $this->_add_control(
            'front_vertical_alignment',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Top', 'kitify' ),
                    'center'        => esc_html__( 'Center', 'kitify' ),
                    'flex-end'      => esc_html__( 'Bottom', 'kitify' ),
                    'space-between' => esc_html__( 'Space Between', 'kitify' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['animated_box_front'] . ' .kitify-animated-box__inner' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_back_order',
            array(
                'label' => esc_html__( 'Back', 'kitify' ),
            )
        );

        $this->_add_control(
            'back_side_icon_order',
            array(
                'label'   => esc_html__( 'Icon Order', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 1,
                'max'     => 2,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['animated_box_icon_back'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->_add_control(
            'back_side_content_order',
            array(
                'label'   => esc_html__( 'Content Order', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 2,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['animated_box_back'] . ' .kitify-animated-box__content' => 'order: {{VALUE}};',
                ),
            )
        );

        $this->_add_control(
            'back_vertical_alignment',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Top', 'kitify' ),
                    'center'        => esc_html__( 'Center', 'kitify' ),
                    'flex-end'      => esc_html__( 'Bottom', 'kitify' ),
                    'space-between' => esc_html__( 'Space Between', 'kitify' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['animated_box_back'] . ' .kitify-animated-box__inner' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();        

    }

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }
}