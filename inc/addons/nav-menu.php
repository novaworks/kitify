<?php
/**
 * Class: Kitify_Nav_Menu
 * Name: Nav Menu
 * Slug: kitify-nav-menu
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Nav_Menu extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/nav-menu.css'), ['kitify-base'], kitify()->get_version());
        wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/nav-menu.js'), ['hoverIntent', 'kitify-base'], kitify()->get_version(), true);

        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

	public function get_name() {
		return 'kitify-nav-menu';
	}

	public function get_widget_title() {
		return esc_html__( 'Nav Menu', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-nav-menu';
	}

    public function get_categories() {
        return [ 'kitify-builder' ];
    }

    protected function register_controls() {

        $this->_start_controls_section(
            'section_menu',
            array(
                'label' => esc_html__( 'Menu', 'kitify' ),
            )
        );

        $menus   = $this->get_available_menus();
        $default = '';

        if ( ! empty( $menus ) ) {
            $ids     = array_keys( $menus );
            $default = $ids[0];
        }

        $this->_add_control(
            'nav_menu',
            array(
                'label'   => esc_html__( 'Select Menu', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => $default,
                'options' => $menus,
            )
        );

        $this->_add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => array(
                    'horizontal' => esc_html__( 'Horizontal', 'kitify' ),
                    'vertical'   => esc_html__( 'Vertical', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'dropdown_position',
            array(
                'label'   => esc_html__( 'Dropdown Placement', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right-side',
                'options' => array(
                    'left-side'  => esc_html__( 'Left Side', 'kitify' ),
                    'right-side' => esc_html__( 'Right Side', 'kitify' ),
                    'bottom'     => esc_html__( 'At the bottom', 'kitify' ),
                ),
                'condition' => array(
                    'layout' => 'vertical',
                )
            )
        );

        $this->_add_control(
            'dropdown_icon',
            array(
                'label'   => esc_html__( 'Dropdown Icon', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->dropdown_arrow_icons_list(),
            )
        );

        $this->_add_control(
            'show_items_desc',
            array(
                'label'   => esc_html__( 'Show Items Description', 'kitify' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->_add_responsive_control(
            'menu_alignment',
            array(
                'label'   => esc_html__( 'Menu Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => array(
                    'flex-start' => array(
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
                    'space-between' => array(
                        'title' => esc_html__( 'Justified', 'kitify' ),
                        'icon'  => 'eicon-h-align-stretch',
                    ),
                ),
                'selectors_dictionary' => array(
                    'flex-start'    => 'justify-content: flex-start; text-align: left;--kitify-navmenu--item-flex-grow:0;--kitify-navmenu--item-margin: 0',
                    'center'        => 'justify-content: center; text-align: center;--kitify-navmenu--item-flex-grow:0;--kitify-navmenu--item-margin: 0',
                    'flex-end'      => 'justify-content: flex-end; text-align: right;--kitify-navmenu--item-flex-grow:0;--kitify-navmenu--item-margin: 0',
                    'space-between' => 'justify-content: space-between; text-align: left;--kitify-navmenu--item-flex-grow:1;--kitify-navmenu--item-margin: auto',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav--horizontal' => '{{VALUE}}',
                    '{{WRAPPER}} .kitify-nav--vertical .menu-item-link-top' => '{{VALUE}}',
                    '{{WRAPPER}} .kitify-nav--vertical-sub-bottom .menu-item-link-sub' => '{{VALUE}}',
                    '{{WRAPPER}} .kitify-mobile-menu.kitify-active--mbmenu .menu-item-link' => '{{VALUE}}',
                )
            )
        );

        $this->_add_control(
            'menu_alignment_style',
            array(
                'type'       => Controls_Manager::HIDDEN,
                'default'    => 'style',
                'selectors'  => array(
                    'body:not(.rtl) {{WRAPPER}} .kitify-nav--horizontal .kitify-nav__sub' => 'text-align: left;',
                    'body.rtl {{WRAPPER}} .kitify-nav--horizontal .kitify-nav__sub' => 'text-align: right;',
                ),
                'condition' => array(
                    'layout' => 'horizontal',
                ),
            )
        );

        $this->_add_control(
            'mobile_trigger_visible',
            array(
                'label'     => esc_html__( 'Enable Mobile Trigger', 'kitify' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
            )
        );

        $this->_add_control(
            'mobile_menu_breakpoint',
            array(
                'label' => esc_html__( 'Breakpoint', 'kitify' ),
                'type'  => Controls_Manager::SELECT,
                'default' => 'tablet',
                'options' => kitify_helper()->get_active_breakpoints(false, true),
                'condition' => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_control(
            'mobile_trigger_alignment',
            array(
                'label'   => esc_html__( 'Mobile Trigger Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left' => array(
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
                'condition' => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_advanced_icon_control(
            'mobile_trigger_icon',
            array(
                'label'       => esc_html__( 'Mobile Trigger Icon', 'kitify' ),
                'label_block' => false,
                'type'        => Controls_Manager::ICON,
                'skin'        => 'inline',
                'default'     => 'novaicon-menu-8-1',
                'fa5_default' => array(
                    'value'   => 'novaicon-menu-8-1',
                    'library' => 'novaicon',
                ),
                'condition'   => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_advanced_icon_control(
            'mobile_trigger_close_icon',
            array(
                'label'       => esc_html__( 'Mobile Trigger Close Icon', 'kitify' ),
                'label_block' => false,
                'type'        => Controls_Manager::ICON,
                'skin'        => 'inline',
                'default'     => 'novaicon-e-remove',
                'fa5_default' => array(
                    'value'   => 'novaicon-e-remove',
                    'library' => 'novaicon',
                ),
                'condition'   => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_control(
            'mobile_menu_layout',
            array(
                'label' => esc_html__( 'Mobile Menu Layout', 'kitify' ),
                'type'  => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'    => esc_html__( 'Default', 'kitify' ),
                    'full-width' => esc_html__( 'Full Width', 'kitify' ),
                    'left-side'  => esc_html__( 'Slide From The Left Side ', 'kitify' ),
                    'right-side' => esc_html__( 'Slide From The Right Side ', 'kitify' ),
                ),
                'condition' => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_control(
            'enable_logo',
            array(
                'label'     => esc_html__( 'Display Logo', 'kitify' ),
                'type'      => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => [
                    'layout' => 'horizontal'
                ]
            )
        );
        $this->_add_control(
            'logo_position',
            [
                'label' => esc_html__( 'Logo Position', 'kitify' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'condition' => [
                    'layout' => 'horizontal',
                    'enable_logo' => 'yes',
                ]
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Logo', 'kitify' ),
                'condition' => [
                    'layout' => 'horizontal',
                    'enable_logo' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'logo_type',
            array(
                'type'    => 'select',
                'label'   => esc_html__( 'Logo Type', 'kitify' ),
                'default' => 'text',
                'options' => array(
                    'text'  => esc_html__( 'Text', 'kitify' ),
                    'image' => esc_html__( 'Image', 'kitify' ),
                    'both'  => esc_html__( 'Both Text and Image', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'logo_image',
            array(
                'label'     => esc_html__( 'Logo Image', 'kitify' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => array(
                    'logo_type!' => 'text',
                ),
            )
        );

        $this->_add_control(
            'logo_image_4l',
            array(
                'label'     => esc_html__( 'Retina Logo Image', 'kitify' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => array(
                    'logo_type!' => 'text',
                ),
            )
        );

        $this->_add_control(
            'logo_text_from',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Logo Text From', 'kitify' ),
                'default'    => 'site_name',
                'options'    => array(
                    'site_name' => esc_html__( 'Site Name', 'kitify' ),
                    'custom'    => esc_html__( 'Custom', 'kitify' ),
                ),
                'condition' => array(
                    'logo_type!' => 'image',
                ),
            )
        );

        $this->_add_control(
            'logo_text',
            array(
                'label'     => esc_html__( 'Custom Logo Text', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => array(
                    'logo_text_from' => 'custom',
                    'logo_type!'     => 'image',
                ),
            )
        );

        $this->_add_control(
            'logo_display',
            array(
                'type'        => 'select',
                'label'       => esc_html__( 'Display Logo Image and Text', 'kitify' ),
                'label_block' => true,
                'default'     => 'block',
                'options'     => array(
                    'inline' => esc_html__( 'Inline', 'kitify' ),
                    'block'  => esc_html__( 'Text Below Image', 'kitify' ),
                ),
                'condition' => array(
                    'logo_type' => 'both',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'nav_items_style',
            array(
                'label'      => esc_html__( 'Top Level Items', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_responsive_control(
            'nav_vertical_menu_width',
            array(
                'label' => esc_html__( 'Vertical Menu Width', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'layout' => 'vertical',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'nav_vertical_menu_align',
            array(
                'label'       => esc_html__( 'Vertical Menu Alignment', 'kitify' ),
                'label_block' => true,
                'type'        => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
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
                'selectors_dictionary' => array(
                    'left'   => 'margin-left: 0; margin-right: auto;',
                    'center' => 'margin-left: auto; margin-right: auto;',
                    'right'  => 'margin-left: auto; margin-right: 0;',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav-wrap' => '{{VALUE}}',
                ),
                'condition'  => array(
                    'layout' => 'vertical',
                ),
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_nav_items_style' );

        $this->_start_controls_tab(
            'nav_items_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'nav_items_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item-link-top' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item-link-top' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_text_bg_color',
            array(
                'label'  => esc_html__( 'Text Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item-link-top .kitify-nav-link-text' => 'background-color: {{VALUE}}',
                ),
            ),
            75
        );

        $this->_add_control(
            'nav_items_text_icon_color',
            array(
                'label'  => esc_html__( 'Dropdown Icon Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item-link-top .kitify-nav-arrow' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'nav_items_typography',
                'selector' => '{{WRAPPER}} .menu-item-link-top .kitify-nav-link-text',
            ),
            50
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'nav_items_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'nav_items_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_text_bg_color_hover',
            array(
                'label'  => esc_html__( 'Text Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item:hover > .menu-item-link-top .kitify-nav-link-text' => 'background-color: {{VALUE}}',
                ),
            ),
            75
        );

        $this->_add_control(
            'nav_items_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'nav_items_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'nav_items_text_icon_color_hover',
            array(
                'label'  => esc_html__( 'Dropdown Icon Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item:hover > .menu-item-link-top .kitify-nav-arrow' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'nav_items_typography_hover',
                'selector' => '{{WRAPPER}} .menu-item:hover > .menu-item-link-top .kitify-nav-link-text',
            ),
            50
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'nav_items_active',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'nav_items_bg_color_active',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_color_active',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_text_bg_color_active',
            array(
                'label'  => esc_html__( 'Text Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .kitify-nav-link-text' => 'background-color: {{VALUE}}',
                ),
            ),
            75
        );

        $this->_add_control(
            'nav_items_active_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'nav_items_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'nav_items_text_icon_color_active',
            array(
                'label'  => esc_html__( 'Dropdown Icon Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .kitify-nav-arrow' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'nav_items_typography_active',
                'selector' => '{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .kitify-nav-link-text',
            ),
            50
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'nav_items_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .menu-item-link-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'nav_items_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav > .kitify-nav__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'nav_items_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .menu-item-link-top',
            ),
            75
        );

        $this->_add_responsive_control(
            'nav_items_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .menu-item-link-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'nav_items_icon_size',
            array(
                'label'      => esc_html__( 'Dropdown Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'condition' => array(
                    'dropdown_icon!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .menu-item-link-top .kitify-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'nav_items_icon_gap',
            array(
                'label'      => esc_html__( 'Gap Before Dropdown Icon', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'condition' => array(
                    'dropdown_icon!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .menu-item-link-top .kitify-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-nav--vertical-sub-left-side .menu-item-link-top .kitify-nav-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

                    '{{WRAPPER}} .kitify-mobile-menu.kitify-active--mbmenu .kitify-nav--vertical-sub-left-side .menu-item-link-top .kitify-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
                ),
            ),
            50
        );

        $this->_add_control(
            'nav_items_desc_heading',
            array(
                'label'     => esc_html__( 'Description', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_items_desc' => 'yes',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'nav_items_desc_typography',
                'selector'  => '{{WRAPPER}} .menu-item-link-top .kitify-nav-item-desc',
                'condition' => array(
                    'show_items_desc' => 'yes',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'sub_items_style',
            array(
                'label'      => esc_html__( 'Dropdown', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'sub_items_container_style_heading',
            array(
                'label' => esc_html__( 'Container Styles', 'kitify' ),
                'type'  => Controls_Manager::HEADING,
            ),
            25
        );

        $this->_add_responsive_control(
            'sub_items_container_width',
            array(
                'label'      => esc_html__( 'Container Width', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__sub' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'layout',
                            'operator' => '===',
                            'value'    => 'horizontal',
                        ),
                        array(
                            'relation' => 'and',
                            'terms' => array(
                                array(
                                    'name'     => 'layout',
                                    'operator' => '===',
                                    'value'    => 'vertical',
                                ),
                                array(
                                    'name'     => 'dropdown_position',
                                    'operator' => '!==',
                                    'value'    => 'bottom',
                                )
                            ),
                        ),
                    ),
                ),
            ),
            25
        );

        $this->_add_control(
            'sub_items_container_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__sub' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'sub_items_container_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .kitify-nav__sub',
            ),
            75
        );

        $this->_add_responsive_control(
            'sub_items_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav__sub' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-nav__sub > .menu-item:first-child > .menu-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                    '{{WRAPPER}} .kitify-nav__sub > .menu-item:last-child > .menu-item-link' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'sub_items_container_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-nav__sub',
            ),
            75
        );


        $this->_add_responsive_control(
            'sub_items_container_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav__sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'sub_items_container_top_gap',
            array(
                'label'      => esc_html__( 'Gap Before 1st Level Sub', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav--horizontal .kitify-nav-depth-0' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-nav--vertical-sub-left-side .kitify-nav-depth-0' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-nav--vertical-sub-right-side .kitify-nav-depth-0' => 'margin-left: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'layout',
                            'operator' => '===',
                            'value'    => 'horizontal',
                        ),
                        array(
                            'relation' => 'and',
                            'terms' => array(
                                array(
                                    'name'     => 'layout',
                                    'operator' => '===',
                                    'value'    => 'vertical',
                                ),
                                array(
                                    'name'     => 'dropdown_position',
                                    'operator' => '!==',
                                    'value'    => 'bottom',
                                )
                            ),
                        ),
                    ),
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'sub_items_container_left_gap',
            array(
                'label'      => esc_html__( 'Gap Before 2nd Level Sub', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav-depth-0 .kitify-nav__sub' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-nav--vertical-sub-left-side .kitify-nav-depth-0 .kitify-nav__sub' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'layout',
                            'operator' => '===',
                            'value'    => 'horizontal',
                        ),
                        array(
                            'relation' => 'and',
                            'terms' => array(
                                array(
                                    'name'     => 'layout',
                                    'operator' => '===',
                                    'value'    => 'vertical',
                                ),
                                array(
                                    'name'     => 'dropdown_position',
                                    'operator' => '!==',
                                    'value'    => 'bottom',
                                )
                            ),
                        ),
                    ),
                ),
            ),
            50
        );

        $this->_add_control(
            'sub_items_style_heading',
            array(
                'label'     => esc_html__( 'Items Styles', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'sub_items_typography',
                'selector' => '{{WRAPPER}} .menu-item-link-sub .kitify-nav-link-text',
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_sub_items_style' );

        $this->_start_controls_tab(
            'sub_items_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'sub_items_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item-link-sub' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'sub_items_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item-link-sub' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'sub_items_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'sub_items_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item:hover > .menu-item-link-sub' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'sub_items_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item:hover > .menu-item-link-sub' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'sub_items_active',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'sub_items_bg_color_active',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item.current-menu-item > .menu-item-link-sub' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'sub_items_color_active',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .menu-item.current-menu-item > .menu-item-link-sub' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'sub_items_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .menu-item-link-sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );
        $this->_add_responsive_control(
            'sub_items_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .menu-item-link-sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'sub_items_icon_size',
            array(
                'label'      => esc_html__( 'Dropdown Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'condition' => array(
                    'dropdown_icon!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .menu-item-link-sub .kitify-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'sub_items_icon_gap',
            array(
                'label'      => esc_html__( 'Gap Before Dropdown Icon', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'condition' => array(
                    'dropdown_icon!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .menu-item-link-sub .kitify-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-nav--vertical-sub-left-side .menu-item-link-sub .kitify-nav-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

                    '{{WRAPPER}} .kitify-mobile-menu.kitify-active--mbmenu .kitify-nav--vertical-sub-left-side .menu-item-link-sub .kitify-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
                ),
            ),
            50
        );

        $this->_add_control(
            'sub_items_divider_heading',
            array(
                'label'     => esc_html__( 'Divider', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'sub_items_divider',
                'selector' => '{{WRAPPER}} .kitify-nav__sub > .kitify-nav-item-sub:not(:last-child)',
                'exclude'  => array( 'width' ),
            ),
            75
        );

        $this->_add_control(
            'sub_items_divider_width',
            array(
                'label' => esc_html__( 'Border Width', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'default' => array(
                    'size' => 1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__sub > .kitify-nav-item-sub:not(:last-child)' => 'border-width: 0; border-bottom-width: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'sub_items_divider_border!' => '',
                ),
            ),
            75
        );

        $this->_add_control(
            'sub_items_desc_heading',
            array(
                'label'     => esc_html__( 'Description', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_items_desc' => 'yes',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'sub_items_desc_typography',
                'selector'  => '{{WRAPPER}} .menu-item-link-sub .kitify-nav-item-desc',
                'condition' => array(
                    'show_items_desc' => 'yes',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'mobile_trigger_styles',
            array(
                'label'      => esc_html__( 'Mobile Trigger', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_mobile_trigger_style' );

        $this->_start_controls_tab(
            'mobile_trigger_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'mobile_trigger_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_trigger_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'mobile_trigger_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'mobile_trigger_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger:hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_trigger_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger:hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_trigger_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'mobile_trigger_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger:hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'mobile_trigger_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .kitify-nav__mobile-trigger',
                'separator'   => 'before',
            ),
            75
        );

        $this->_add_control(
            'mobile_trigger_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'mobile_trigger_width',
            array(
                'label'      => esc_html__( 'Width', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 200,
                    ),
                    '%' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            50
        );

        $this->_add_control(
            'mobile_trigger_height',
            array(
                'label'      => esc_html__( 'Height', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger' => 'height: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_control(
            'mobile_trigger_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-nav__mobile-trigger' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'mobile_menu_styles',
            array(
                'label' => esc_html__( 'Mobile Menu', 'kitify' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->_add_control(
            'mobile_menu_width',
            array(
                'label' => esc_html__( 'Width', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 150,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => 30,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-active--mbmenu .kitify-nav' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'mobile_menu_layout' => array(
                        'left-side',
                        'right-side',
                    ),
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_menu_max_height',
            array(
                'label' => esc_html__( 'Max Height', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'vh' ),
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 500,
                    ),
                    'vh' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-active--mbmenu .kitify-nav' => 'max-height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'mobile_menu_layout' => 'full-width',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_menu_bg_color',
            array(
                'label' => esc_html__( 'Background color', 'kitify' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-active--mbmenu .kitify-nav' => 'background-color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_menu_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-active--mbmenu .kitify-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'mobile_menu_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-active--mbmenu.kitify-mobile-menu-active .kitify-nav',
            ),
            75
        );

        $this->_add_control(
            'mobile_close_icon_heading',
            array(
                'label' => esc_html__( 'Close icon', 'kitify' ),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'mobile_menu_layout' => array(
                        'left-side',
                        'right-side',
                    ),
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_close_icon_color',
            array(
                'label' => esc_html__( 'Color', 'kitify' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__mobile-close-btn' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'mobile_menu_layout' => array(
                        'left-side',
                        'right-side',
                    ),
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_close_icon_font_size',
            array(
                'label' => esc_html__( 'Font size', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-nav__mobile-close-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'mobile_menu_layout' => array(
                        'left-side',
                        'right-side',
                    ),
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'logo_style',
            array(
                'label'      => esc_html__( 'Logo', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'layout' => 'horizontal',
                    'enable_logo' => 'yes',
                    'logo_type'   => ['image', 'both']
                ]
            )
        );
        $this->_add_responsive_control(
            'logo_padding',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'logo_width',
            [
                'label' => __( 'Logo Width', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-logo' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'logo_alignment',
            array(
                'label'   => esc_html__( 'Logo Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Start', 'kitify' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'End', 'kitify' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-logo' => 'justify-content: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'vertical_logo_alignment',
            array(
                'label'       => esc_html__( 'Image and Text Vertical Alignment', 'kitify' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'label_block' => true,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Middle', 'kitify' ),
                        'icon' => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                    'baseline' => array(
                        'title' => esc_html__( 'Baseline', 'kitify' ),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-logo__link' => 'align-items: {{VALUE}}',
                ),
                'condition' => array(
                    'logo_type'    => 'both',
                    'logo_display' => 'inline',
                ),
            ),
            25
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'text_logo_style',
            array(
                'label'      => esc_html__( 'Logo Text', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'layout' => 'horizontal',
                    'enable_logo' => 'yes',
                    'logo_type'   => ['text', 'both']
                ]
            )
        );

        $this->_add_control(
            'text_logo_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-logo-lightext' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_logo_typography',
                'selector' => '{{WRAPPER}} .kitify-logo-lightext',
            ),
            50
        );

        $this->_add_control(
            'text_logo_gap',
            array(
                'label'      => esc_html__( 'Gap', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'default'    => array(
                    'size' => 5,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-logo-display-block .kitify-logo__img'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .kitify-logo-display-inline .kitify-logo__img' => 'margin-right: {{SIZE}}{{UNIT}}',
                ),
                'condition'  => array(
                    'logo_type' => 'both',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'text_logo_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
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
                'selectors' => array(
                    '{{WRAPPER}} .kitify-logo-lightext' => 'text-align: {{VALUE}}',
                ),
                'condition' => array(
                    'logo_type'    => 'both',
                    'logo_display' => 'block',
                ),
            ),
            50
        );

        $this->_end_controls_section();
    }

    /**
     * Returns available icons for dropdown list
     *
     * @return array
     */
    public function dropdown_arrow_icons_list() {

        return apply_filters( 'kitify/nav-menu/dropdown-icons', array(
            'novaicon-down-arrow'          => esc_html__( 'Angle', 'kitify' ),
            'novaicon-small-triangle-down' => esc_html__( 'Triangle', 'kitify' ),
            'novaicon-arrow-down'          => esc_html__( 'Arrow', 'kitify' ),
            'novaicon-i-add'               => esc_html__( 'Plus', 'kitify' ),
            'novaicon-i-add-2'             => esc_html__( 'Plus 2', 'kitify' ),
            'novaicon-e-add'               => esc_html__( 'Plus 3', 'kitify' ),
            ''                                 => esc_html__( 'None', 'kitify' ),
        ) );

    }

    /**
     * Get available menus list
     *
     * @return array
     */
    public function get_available_menus() {

        $raw_menus = wp_get_nav_menus();
        $menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );

        return $menus;
    }

    protected function render() {

        $settings = $this->get_settings();

        if ( ! $settings['nav_menu'] ) {
            return;
        }

        $trigger_visible = filter_var( $settings['mobile_trigger_visible'], FILTER_VALIDATE_BOOLEAN );
        $trigger_align   = $settings['mobile_trigger_alignment'];
        $mobile_menu_breakpoint = isset($settings['mobile_menu_breakpoint']) ? $settings['mobile_menu_breakpoint'] : 'tablet';
        $active_breakpoints = kitify_helper()->get_active_breakpoints();
        $breakpoint_value = 1024;
        if(isset($active_breakpoints[$mobile_menu_breakpoint])){
            $breakpoint_value = $active_breakpoints[$mobile_menu_breakpoint];
        }

        require_once kitify()->plugin_path( 'inc/class-nav-walker.php' );

        $this->add_render_attribute( 'nav-wrapper', 'class', 'kitify-nav-wrap' );

        if ( $trigger_visible ) {
            $this->add_render_attribute( 'nav-wrapper', 'class', 'kitify-mobile-menu' );
            $this->add_render_attribute( 'nav-wrapper', 'data-mobile-breakpoint', esc_attr($breakpoint_value) );

            if ( isset( $settings['mobile_menu_layout'] ) ) {
                $this->add_render_attribute( 'nav-wrapper', 'class', sprintf( 'kitify-mobile-menu--%s', esc_attr( $settings['mobile_menu_layout'] ) ) );
                $this->add_render_attribute( 'nav-wrapper', 'data-mobile-layout', esc_attr( $settings['mobile_menu_layout'] ) );
            }
        }

        $this->add_render_attribute( 'nav-menu', 'class', 'kitify-nav' );

        if ( isset( $settings['layout'] ) ) {
            $this->add_render_attribute( 'nav-menu', 'class', 'kitify-nav--' . esc_attr( $settings['layout'] ) );

            if ( 'vertical' === $settings['layout'] && isset( $settings['dropdown_position'] ) ) {
                $this->add_render_attribute( 'nav-menu', 'class', 'kitify-nav--vertical-sub-' . esc_attr( $settings['dropdown_position'] ) );
            }
        }

        $menu_html = '<div ' . $this->get_render_attribute_string( 'nav-menu' ) . '>%3$s</div>';

        if ( $trigger_visible && in_array( $settings['mobile_menu_layout'], array( 'left-side', 'right-side' ) ) ) {
            $close_btn = $this->_get_icon( 'mobile_trigger_close_icon', '<div class="kitify-nav__mobile-close-btn kitify-blocks-icon">%s</div>' );

            $menu_html = '<div ' . $this->get_render_attribute_string( 'nav-menu' ) . '>%3$s' . $close_btn . '</div>';
        }

        $args = array(
            'menu'            => $settings['nav_menu'],
            'fallback_cb'     => '',
            'items_wrap'      => $menu_html,
            'walker'          => new \Kitify_Nav_Walker,
            'widget_settings' => array(
                'dropdown_icon'   => $settings['dropdown_icon'],
                'show_items_desc' => $settings['show_items_desc'],
            ),
        );

        if( filter_var( $this->get_settings_for_display('enable_logo') ) && ( isset($settings['layout']) && $settings['layout'] === 'horizontal' ) ){
            $args['widget_settings']['logo_html'] = $this->get_logo_html();
            $args['widget_settings']['logo_position'] = !empty($settings['logo_position']) ? absint($settings['logo_position']) : 0;
        }

        echo '<div ' . $this->get_render_attribute_string( 'nav-wrapper' ) . '>';
        if ( $trigger_visible ) {
            include $this->_get_global_template( 'mobile-trigger' );
        }
        wp_nav_menu( $args );
        echo '</div>';

    }

    public function get_logo_html(){
        $output = sprintf(
            '<div class="%1$s"><a href="%2$s" class="kitify-logo__link">%3$s%4$s</a></div>',
            esc_attr( $this->_get_logo_classes() ),
            esc_url( home_url( '/' ) ),
            $this->_get_logo_image(),
            $this->_get_logo_text()
        );
        return $output;
    }

    /**
     * Returns logo text
     *
     * @return string Text logo HTML markup.
     */
    public function _get_logo_text() {

        $settings    = $this->get_settings();
        $type        = isset( $settings['logo_type'] ) ? esc_attr( $settings['logo_type'] ) : 'text';
        $text_from   = isset( $settings['logo_text_from'] ) ? esc_attr( $settings['logo_text_from'] ) : 'site_name';
        $custom_text = isset( $settings['logo_text'] ) ? wp_kses_post( $settings['logo_text'] ) : '';

        if ( 'image' === $type ) {
            return;
        }

        if ( 'site_name' === $text_from ) {
            $text = get_bloginfo( 'name' );
        } else {
            $text = $custom_text;
        }

        $format = apply_filters(
            'kitify/logo/text-foramt',
            '<div class="kitify-logo-lightext">%s</div>'
        );

        return sprintf( $format, $text );
    }

    /**
     * Returns logo classes string
     *
     * @return string
     */
    public function _get_logo_classes() {

        $settings = $this->get_settings();

        $classes = array(
            'kitify-logo',
            'kitify-logo-type-' . $settings['logo_type'],
            'kitify-logo-display-' . $settings['logo_display'],
        );

        return implode( ' ', $classes );
    }

    /**
     * Returns logo image
     *
     * @return string Image logo HTML markup.
     */
    public function _get_logo_image() {

        $settings = $this->get_settings();
        $type     = isset( $settings['logo_type'] ) ? esc_attr( $settings['logo_type'] ) : 'text';
        $image    = isset( $settings['logo_image'] ) ? $settings['logo_image'] : false;
        $image_4l = isset( $settings['logo_image_4l'] ) ? $settings['logo_image_4l'] : false;

        if ( 'text' === $type || ! $image ) {
            return;
        }

        $image_src    = $this->_get_logo_image_src( $image );
        $image_4l_src = $this->_get_logo_image_src( $image_4l );

        $image_src = apply_filters('kitify/logo/attr/src', $image_src);
        $image_4l_src = apply_filters('kitify/logo/attr/src4l', $image_4l_src);

        if ( empty( $image_src ) && empty( $image_4l_src ) ) {
            return;
        }

        $format = apply_filters(
            'kitify/logo/image-format',
            '<img src="%1$s" class="kitify-logo__img" alt="%2$s"%3$s>'
        );

        $image_data = ! empty( $image['id'] ) ? wp_get_attachment_image_src( $image['id'], 'full' ) : array();
        $width      = isset( $image_data[1] ) ? $image_data[1] : false;
        $height     = isset( $image_data[2] ) ? $image_data[2] : false;

        $width      = apply_filters('kitify/logo/attr/width', $width);
        $height      = apply_filters('kitify/logo/attr/height', $height);

        $attrs = sprintf(
            '%1$s%2$s%3$s',
            $width ? ' width="' . $width . '"' : '',
            $height ? ' height="' . $height . '"' : '',
            ( ! empty( $image_4l_src ) ? ' srcset="' . esc_url( $image_4l_src ) . ' 2x"' : '' )
        );

        return sprintf( $format, esc_url( $image_src ), get_bloginfo( 'name' ), $attrs );
    }

    public function _get_logo_image_src( $args = array() ) {

        if ( ! empty( $args['id'] ) ) {
            $img_data = wp_get_attachment_image_src( $args['id'], 'full' );

            return ! empty( $img_data[0] ) ? $img_data[0] : false;
        }

        if ( ! empty( $args['url'] ) ) {
            return $args['url'];
        }

        return false;
    }

}
