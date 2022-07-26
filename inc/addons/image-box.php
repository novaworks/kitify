<?php
/**
 * Class: Kitify_Image_Box
 * Name: Image Box
 * Slug: kitify-image-box
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Icon Box Widget
 */
class Kitify_Image_Box extends Kitify_Base {

    protected function enqueue_addon_resources(){

        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/imagebox.css'), ['kitify-base'], kitify()->get_version());

        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-image-box';
    }

    protected function get_widget_title() {
        return esc_html__( 'Image Box', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-image-box';
    }

    protected function register_controls() {


        $css_scheme = apply_filters(
            'kitify/imagebox/css-scheme',
            array(
                'box'                   => '.kitify-imagebox',
                'box_header'            => '.kitify-imagebox__header',
                'box_image'       		=> '.kitify-imagebox__main_img',
                'box_body'              => '.kitify-imagebox__body',
                'box_body_inner'        => '.kitify-imagebox__body_inner',
                'box_title'             => '.kitify-imagebox__title',
                'box_title_icon'        => '.kitify-imagebox__title_icon',
                'box_desc'             	=> '.kitify-imagebox__desc',
                'button_wrap'           => '.kitify-iconbox__button_wrapper',
                'button'           		=> '.elementor-button',
                'button_icon'           => '.elementor-button .elementor-button-icon',
            )
        );

        // start content section for set Image
        $this->_start_controls_section(
            'box_section_infoboxwithimage',
            [
                'label' => esc_html__( 'Image', 'kitify' ),
            ]
        );

        // Image insert
        $this->_add_control(
            'box_image',
            [
                'label' => esc_html__( 'Choose Image', 'kitify' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'box_thumbnail',
                'default' => 'full',
                'separator' => 'none',
            ]
        );

        //  simple  style

        $this->_add_control(
            'box_style_simple',
            [
                'label' => esc_html__( 'Content Area', 'kitify' ),
                'type' =>  Controls_Manager::SELECT,
                'default' => 'simple-card',
                'options' => [
                    'simple-card'  => esc_html__( 'Simple', 'kitify' ),
                    'style-modern' => esc_html__( 'Classic Curves', 'kitify' ),
                    'floating-style' => esc_html__( 'Floating box', 'kitify' ),
                    'hover-border-bottom' => esc_html__( 'Hover Border', 'kitify' ),
                    'style-sideline' => esc_html__( 'Side Line', 'kitify' ),
                    'shadow-line' => esc_html__( 'Shadow line', 'kitify' ),
                ],
            ]
        );

        $this->_add_control(
            'enable_equal_height',
            [
                'label'     => esc_html__( 'Equal Height?', 'kitify' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'enable' => esc_html__( 'Enable', 'kitify' ),
                    'disable' => esc_html__( 'Disable', 'kitify' ),
                ],
                'default'   => 'disable',
                'prefix_class'  => 'kitify-equal-height-',
                'selectors' => [
                    '{{WRAPPER}}.kitify-equal-height-enable .kitify-imagebox' => 'height: 100%;',
                ],
                'condition' => [
                    'box_style_simple!'   => 'floating-style'
                ]
            ]
        );

        $this->_add_control(
            'box_enable_link',
            [
                'label' => esc_html__( 'Enable Link', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'kitify' ),
                'label_off' => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
            ]
        );

        $this->_add_control(
            'box_website_link',
            [
                'label' => esc_html__( 'Link', 'kitify' ),
                'type' => Controls_Manager::URL,
                'show_external' => true,
                'condition' => [
                    'box_enable_link' => 'yes'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        // end content section for set Image
        $this->_end_controls_section();


        // start content section for image title and sub title
        $this->_start_controls_section(
            'box_section_for_image_title',
            [
                'label' => esc_html__( 'Body', 'kitify' ),
            ]
        );

        $this->_add_control(
            'box_title_text',
            [
                'label' => esc_html__( 'Title ', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'This is the heading', 'kitify' ),
                'placeholder' => esc_html__( 'Enter your title', 'kitify' ),
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'box_front_title_icons__switch',
            [
                'label' => esc_html__('Add icon? ', 'kitify'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' =>esc_html__( 'Yes', 'kitify' ),
                'label_off' =>esc_html__( 'No', 'kitify' ),
                'condition' => [
                    'box_style_simple' => 'floating-style',
                ]
            ]
        );

        $this->_add_advanced_icon_control(
            'box_front_title_icons',
            [
                'label' => esc_html__( 'Title Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'box_style_simple' => 'floating-style',
                    'box_front_title_icons__switch'  => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'box_front_title_icon_position',
            [
                'label' => esc_html__( 'Title Icon Position', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' =>esc_html__( 'Before', 'kitify' ),
                    'right' =>esc_html__( 'After', 'kitify' ),
                ],
                'condition' => [
                    'box_front_title_icons__switch'  => 'yes',
                    'box_style_simple' => 'floating-style',
                ]
            ]
        );

        // title tag
        $this->_add_control(
            'box_title_size',
            [
                'label' => esc_html__( 'Title HTML Tag', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $this->_add_control(
            'box_description_text',
            [
                'label' => esc_html__( 'Description', 'kitify' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Click edit  to change this text. Lorem ipsum dolor sit amet, cctetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'kitify' ),
                'placeholder' => esc_html__( 'Enter your description', 'kitify' ),
                'separator' => 'none',
                'rows' => 10,
                'show_label' => false,
            ]
        );

        // Text aliment

        $this->_add_control(
            'box_content_text_align',
            [
                'label' => esc_html__( 'Alignment', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
            ]
        );

        // end content section for image title and sub title
        $this->_end_controls_section();

        // start content section for button
        //  Section Button

        $this->_start_controls_section(
            'box_section_button',
            [
                'label' => esc_html__( 'Button', 'kitify' ),
            ]
        );
        $this->_add_control(
            'box_enable_btn',
            [
                'label' => esc_html__( 'Enable Button', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'box_btn_text',
            [
                'label' =>esc_html__( 'Label', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' =>esc_html__( 'Learn more ', 'kitify' ),
                'placeholder' =>esc_html__( 'Learn more ', 'kitify' ),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'box_enable_btn' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'box_btn_url',
            [
                'label' =>esc_html__( 'URL', 'kitify' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'box_enable_btn' => 'yes',
                ]
            ]
        );
        $this->_add_control(
            'box_icons__switch',
            [
                'label' => esc_html__('Add icon? ', 'kitify'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' =>esc_html__( 'Yes', 'kitify' ),
                'label_off' =>esc_html__( 'No', 'kitify' ),
                'condition' => [
                    'box_enable_btn' => 'yes',
                ]
            ]
        );
        $this->_add_advanced_icon_control(
            'box_icons',
            [
                'label' =>esc_html__( 'Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'box_enable_btn' => 'yes',
                    'box_icons__switch' => 'yes'
                ]
            ]
        );
        $this->_add_control(
            'box_icon_align',
            [
                'label' =>esc_html__( 'Icon Position', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' =>esc_html__( 'Before', 'kitify' ),
                    'right' =>esc_html__( 'After', 'kitify' ),
                ],
                'condition' => [
                    'box_icons__switch' => 'yes',
                    'box_enable_btn' => 'yes',
                ],
            ]
        );
        // end content section for button
        $this->_end_controls_section();

        // start style section here


        // start floating box style
        $this->_start_controls_section(
            'box_image_floating_box',
            [
                'label' => esc_html__( 'Floating Style', 'kitify' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'floating-style',
                ]
            ]
        );

        $this->_start_controls_tabs(
            'box_image_floating_box_heights'
        );

        $this->_start_controls_tab(
            'box_image_floating_box_normal_height_tab',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_height',
            [
                'label' => esc_html__( 'Height', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title_icon'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_image_floating_box_hover_height_tab',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_hover_height',
            [
                'label' => esc_html__( 'Hover Height', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_body'] => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_title_icon'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'box_image_floating_box_tab_separetor',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_icon_font_size',
            [
                'label' => esc_html__( 'Icon Font Size', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] => '--kitify-imagebox-icon-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_responsive_control(
            'box_image_floating_box_icon_vspacing',
            [
                'label' => esc_html__( 'Icon Vertical Position', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] => '--kitify-imagebox-icon-vspacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->_add_responsive_control(
            'box_image_floating_box_margin_top',
            [
                'label' => esc_html__( 'Margin Top', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_width',
            [
                'label' => esc_html__( 'Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_image_floating_box_background',
                'label' => esc_html__( 'Background', 'kitify' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ',{{WRAPPER}} ' . $css_scheme['box_body'] . ':before, ' . '{{WRAPPER}} ' . $css_scheme['box_body'] . ':after',
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_floating_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ',{{WRAPPER}} ' . $css_scheme['box_body'] . ':before, ' . '{{WRAPPER}} ' . $css_scheme['box_body'] . ':after',
            ]
        );

        $this->_end_controls_section();

        // start classic curves style
        $this->_start_controls_section(
            'box_image_classic_curves',
            [
                'label' => esc_html__( 'Classic Curves', 'kitify' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'style-modern',
                ]
            ]
        );

        $this->_add_responsive_control(
            'box_image_classic_curves_width',
            [
                'label' => esc_html__( 'Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_classic_curves_margin',
            [
                'label' => esc_html__( 'Margin', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_end_controls_section();

        // start border bottom hover style
        $this->_start_controls_section(
            'box_border_bottom_hover',
            [
                'label' => esc_html__( 'Hover Border Bottom', 'kitify' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'hover-border-bottom',
                ]
            ]
        );

        $this->_add_responsive_control(
            'box_border_hover_height',
            [
                'label' => esc_html__( 'Border Bottom Height', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'box_style_simple' => 'hover-border-bottom',
                ]
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_border_hover_background',
                'label' => esc_html__( 'Background', 'kitify' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before',
                'condition' => [
                    'box_style_simple' => 'hover-border-bottom',
                ]
            ]
        );

        $this->_add_control(
            'box_border_hover_background_direction',
            [
                'label' => esc_html__( 'Hover Direction', 'kitify' ),
                'type' =>   Controls_Manager::CHOOSE,
                'options' => [
                    'hover_from_left' => [
                        'title' => esc_html__( 'From Left', 'kitify' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'hover_from_center' => [
                        'title' => esc_html__( 'From Center', 'kitify' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'hover_from_right' => [
                        'title' => esc_html__( 'From Right', 'kitify' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                ],
                'default' => 'hover_from_right',
                'toggle' => true,
                'condition'  => [
                    'box_style_simple' => 'hover-border-bottom',
                ]
            ]
        );

        $this->_end_controls_section();

        // start side line style
        $this->_start_controls_section(
            'box_image_side_line',
            [
                'label' => esc_html__( 'Side Line', 'kitify' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'style-sideline',
                ]
            ]
        );

        $this->_add_responsive_control(
            'box_image_side_line_border_width',
            [
                'label' => esc_html__( 'Border Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body_inner'] => 'border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_side_line_border_type',
            [
                'label' => esc_html__( 'Border Type', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'none' =>esc_html__( 'None', 'kitify' ),
                    'solid' =>esc_html__( 'Solid', 'kitify' ),
                    'double' =>esc_html__( 'Double', 'kitify' ),
                    'dotted' =>esc_html__( 'Dotted', 'kitify' ),
                    'dashed' =>esc_html__( 'Dashed', 'kitify' ),

                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body_inner'] => 'border-style: {{VALUE}}',
                ],
            ]
        );

        $this->_start_controls_tabs(
            'side_line_tabs'
        );
        $this->_start_controls_tab(
            'side_line_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );
        $this->_add_responsive_control(
            'box_image_side_line_border',
            [
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body_inner'] => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'side_line_hover',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );
        $this->_add_responsive_control(
            'side_line_hover_color',
            [
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_body_inner'] => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->_end_controls_section();

        // start line shadow style
        $this->_start_controls_section(
            'box_image_shadow_line',
            [
                'label' => esc_html__( 'Shadow Line', 'kitify' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'shadow-line',
                ]
            ]
        );

        $this->_start_controls_tabs(
            'box_image_shadow_line_tabs'
        );

        $this->_start_controls_tab(
            'box_image_shadow_line_left_tab',
            [
                'label' => esc_html__( 'Left Line', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_image_shadow_left_line_width',
            [
                'label' => esc_html__( 'Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_shadow_left_line_shadow',
                'label' => esc_html__( 'Box Shadow', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before',
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_image_shadow_left_line_background',
                'label' => esc_html__( 'Background', 'kitify' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before',
            ]
        );

        $this->_end_controls_tab();

        // right line
        $this->_start_controls_tab(
            'box_image_shadow_line_right_tab',
            [
                'label' => esc_html__( 'Right Line', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_image_shadow_right_line_width',
            [
                'label' => esc_html__( 'Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] . ':after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_shadow_right_line_shadow',
                'label' => esc_html__( 'Box Shadow', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ':after',
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_image_shadow_right_line_background',
                'label' => esc_html__( 'Background', 'kitify' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' =>'{{WRAPPER}} ' . $css_scheme['box_body'] . ':after',
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();


        // start image section style
        $this->_start_controls_section(
            'box_image_section',
            [
                'label' => esc_html__( 'Image', 'kitify' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'box_image_width',
            [
                'label' => esc_html__( 'Image Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_image'] => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_start_controls_tabs(
            'box_style_tabs_image'
        );

        $this->_start_controls_tab(
            'box_style_normal_tab_image',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_image'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_image_border',
                'label' => esc_html__( 'Border', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_image'],
            ]
        );

        $this->_add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_image'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_opacity',
            [
                'label' => esc_html__( 'Image opacity', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => .01,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_image'] => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_shadow',
                'label' => esc_html__( 'Box Shadow', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_image'],
            ]
        );


        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_style_hover_tab_image',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_border_radius_hover',
            [
                'label' => esc_html__( 'Border radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_image'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_image_border_hover',
                'label' => esc_html__( 'Border', 'kitify' ),
                'selector' => '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_image'],
            ]
        );

        $this->_add_responsive_control(
            'box_padding_hover',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_image'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_opacity_hover',
            [
                'label' => esc_html__( 'Image opacity', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => .01,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_image'] => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_shadow_hover',
                'label' => esc_html__( 'Box Shadow', 'kitify' ),
                'selector' => '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_image'],
            ]
        );

        $this->_add_responsive_control(
            'box_image_scale_on_hover',
            [
                'label' => esc_html__( 'Image Scale on Hover', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 2,
                        'step' => .1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1.1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_image'] => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();
        //end image section style

        // start body style section
        $this->_start_controls_section(
            'box_style_body_section',
            [
                'label' => esc_html__( 'Body', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_control(
            'imagebox_general_border_heading_title',
            [
                'label' => esc_html__( 'General', 'kitify' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'imagebox_container_border_group',
                'label' => esc_html__( 'Border', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'],
            ]
        );

        $this->_add_responsive_control(
            'body_radius',
            [
                'label'         => esc_html__( 'Border Radius', 'kitify' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', '%', 'em' ],
                'selectors'     => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] . ',{{WRAPPER}} ' . $css_scheme['box_body'] . ':before,{{WRAPPER}} ' . $css_scheme['box_body'] . ':after'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'imagebox_container_background',
                'label' => esc_html__( 'Background', 'kitify' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'],
            ]
        );

        $this->_add_responsive_control(
            'imagebox_container_spacing',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_group',
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'],
            ]
        );

        // title
        $this->_add_control(
            'imagebox_title_border_heading_title',
            [
                'label' => esc_html__( 'Title', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_responsive_control(
            'box_title_bottom_space',
            [
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => 'true',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'box_title_typography',
                'label' => esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_title'],
            ]
        );

        $this->_start_controls_tabs('box_style_heading_tabs');

        $this->_start_controls_tab(
            'box_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_heading_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_heading_color_hover',
            [
                'label' => esc_html__( 'Color (Hover)', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_title'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        // sub Description
        $this->_add_control(
            'imagebox_description_border_heading_title',
            [
                'label' => esc_html__( 'Description', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_responsive_control(
            'box_title_bottom_space_description',
            [
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '14',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => 'true',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'box_title_typography_description',
                'label' => esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_desc'],
            ]
        );

        $this->_start_controls_tabs('box_style_description_tabs');

        $this->_start_controls_tab(
            'box_style_normal_tab_description',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_heading_color_description',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_desc'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_style_hover_tab_description',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_heading_color_hover_description',
            [
                'label' => esc_html__( 'Color (Hover)', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-imagebox:hover ' . $css_scheme['box_desc'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        // start style csetion for button
        // Button

        $this->_start_controls_section(
            'box_section_style',
            [
                'label' => esc_html__( 'Button', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_enable_btn' => 'yes',
                ]
            ]
        );
        $this->_add_control(
            'box_btn_fullwidth',
            [
                'label' => esc_html__( 'Enable Fullwidth Button', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'width: 100%',
                ]
            ]
        );
        $this->_add_responsive_control(
            'box_btn_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'nova' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'nova' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'nova' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'condition' => array(
                    'box_btn_fullwidth' => 'yes'
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'justify-content: {{VALUE}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'box_text_padding',
            [
                'label' =>esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'box_typography_group',
                'label' =>esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );
        $this->_add_responsive_control(
            'box_btn_icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->_start_controls_tabs( 'tabs_button_style' );

        $this->_start_controls_tab(
            'box_tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_btn_background_group',
                'label' => esc_html__( 'Background', 'kitify' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_button_border_color_group',
                'label' => esc_html__( 'Border', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );
        $this->_add_responsive_control(
            'box_btn_border_radius',
            [
                'label' =>esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '' ,
                    'left' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->_add_responsive_control(
            'box_btn_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_btn_background_hover_group',
                'label' => esc_html__( 'Background', 'kitify' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_button_border_hv_color_group',
                'label' => esc_html__( 'Border', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            ]
        );
        $this->_add_responsive_control(
            'box_btn_hover_border_radius',
            [
                'label' =>esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px'],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_button_box_shadow_hover_group',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

    }

    /**
     * [render description]
     * @return [type] [description]
     */
    protected function render() {
        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

    public function get_main_image( $main_format = '%s', $echo = false ) {

        $settings = $this->get_settings_for_display();

        $main_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'box_thumbnail', 'box_image' );

        if(empty($main_image)){
            return;
        }

        if(false === strpos($main_image, 'class="')){
            $main_image = str_replace('<img', '<img class=""', $main_image);
        }

        $main_image = str_replace('class="', 'class="kitify-imagebox__main_img ', $main_image);

        if(!$echo){
            return sprintf( $main_format, $main_image );
        }
        else{
            echo sprintf( $main_format, $main_image );
        }
    }

    public function get_main_icon( $main_format = '<span class="kitify-imagebox__title_icon">%s</span>' ){
        if( !filter_var($this->get_settings_for_display('box_front_title_icons__switch'), FILTER_VALIDATE_BOOLEAN) ){
            return;
        }
        return $this->_get_icon( 'box_front_title_icons', $main_format );
    }

    public function get_button_icon( $main_format = '%s' ){
        return $this->_get_icon( 'box_icons', $main_format, 'kitify-imagebox__btn_icon' );
    }

}
