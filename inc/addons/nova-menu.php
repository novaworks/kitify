<?php

/**
 * Class: Kitify_Nova_Menu
 * Name: Nova Menu
 * Slug: kitify-nova-menu
 */

 namespace Elementor;

 if (!defined('WPINC')) {
     die;
 }

class Kitify_Nova_Menu extends Kitify_Base {

  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/nova-menu.css'), ['kitify-base'], kitify()->get_version());
      wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/nova-menu.js'), ['kitify-base'], kitify()->get_version(), true);

      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends( $this->get_name() );
  }

  public function get_name() {
      return 'kitify-nova-menu';
  }

  public function get_categories() {
      return [ 'kitify-builder' ];
  }

  protected function get_widget_title() {
      return esc_html__( 'Nova Menu', 'kitify' );
  }

  public function get_icon() {
      return 'kitify-icon-nav-menu';
  }

  protected function register_controls() {
    $menu_style = apply_filters(
        'kitify/nova-menu/control/style',
        array(
          'default' => esc_html__( 'Default', 'kitify' ),
          'top-line' => esc_html__( 'Top Line', 'kitify' ),
          'bottom-line' => esc_html__( ' Bottom Line', 'kitify' ),
        )
    );

    $this->start_controls_section(
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
        'nova_nav_menu',
        array(
            'label'   => esc_html__( 'Select Menu', 'kitify' ),
            'type'    => Controls_Manager::SELECT,
            'default' => $default,
            'options' => $menus,
        )
    );
    $this->_add_control(
        'dropdown_icon',
        array(
            'label'   => esc_html__( 'Dropdown Icon', 'kitify' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'novaicon-down-arrow',
            'options' => $this->dropdown_arrow_icons_list(),
        )
    );
    $this->_add_responsive_control(
        'nova_menu_alignment',
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
                '{{WRAPPER}} .main-navigation' => '{{VALUE}}',
            )
        )
    );

    $this->_add_control(
        'nova_nav_style',
        array(
            'label'   => esc_html__( 'Menu Style', 'kitify' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'default',
            'options' => $menu_style,
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
    $this->_add_advanced_icon_control(
        'mobile_trigger_icon',
        array(
            'label'       => esc_html__( 'Mobile Trigger Icon', 'kitify' ),
            'label_block' => false,
            'type'        => Controls_Manager::ICON,
            'skin'        => 'inline',
            'default'     => 'dlicon ui-2_menu-34',
            'fa5_default' => array(
                'value'   => 'dlicon ui-2_menu-34',
                'library' => 'dlicon',
            ),
            'condition'   => array(
                'mobile_trigger_visible' => 'yes',
            ),
        )
    );
    $this->end_controls_section();

    $css_scheme = \apply_filters(
        'kitify/nova-menu/css-scheme',
        array(
            'nova_menu_wrap'            => '.kitify-nova-menu',
            'nova_menu_item'            => '.kitify-nova-menu .main-navigation .nav-menu > li',
            'nova_menu_item_active'     => '.kitify-nova-menu .main-navigation > ul > li.current-menu-item > a',
            'nova_menu_dropdown'        => '.kitify-nova-menu .main-navigation .nav-menu > li > ul.sub-menu',
            'nova_menu_dropdown_li'        => '.kitify-nova-menu .main-navigation .nav-menu > li > ul.sub-menu li',
            'nova_menu_dropdown_li_active'        => '.kitify-nova-menu .main-navigation .nav-menu > li > ul.sub-menu li.current-menu-item > a',
            'mobile_menu_canvas'        => '.site-canvas-menu',
            'mobile_menu_item_active'        => '.site-canvas-menu li.current-menu-item > a',
        )
    );

    $this->_start_controls_section(
        'menu_style',
        array(
            'label'      => esc_html__( 'Menu Item', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );
    $this->add_responsive_control(
        'menu_item_height',
        [
            'label' => __( 'Item Height', 'kitify' ),
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
                '{{WRAPPER}} ' . $css_scheme['nova_menu_item'] => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $this->_add_responsive_control(
        'nova_nav_items_padding',
        array(
            'label'      => esc_html__( 'Padding', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%', 'em' ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['nova_menu_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
            'separator' => 'before',
        ),
        25
    );
    $this->_add_control(
        'nova_nav_items_color',
        array(
            'label'  => esc_html__( 'Text Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['nova_menu_item'].' > a' => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_control(
        'nova_nav_items_hover_color',
        array(
            'label'  => esc_html__( 'Text Hover Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['nova_menu_item'].' > a:hover' => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_control(
        'nova_nav_items_active_color',
        array(
            'label'  => esc_html__( 'Text Active Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['nova_menu_item_active'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->_add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'nova_nav_items_typography',
            'selector' => '{{WRAPPER}} '. $css_scheme['nova_menu_item'].' > a',
        ),
        50
    );
    $this->end_controls_section();

    $this->_start_controls_section(
        'dropdown_icon_section',
        array(
            'label'      => esc_html__( 'Dropdown Icon', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
            'condition' => array(
                'dropdown_icon!' => '',
            ),
        )
    );
    $this->_add_control(
        'nav_items_text_icon_color',
        array(
            'label'  => esc_html__( 'Dropdown Icon Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} .main-navigation > ul > li > a > i.kitify-nav-arrow' => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->_add_control(
        'nav_items_text_icon_color_hover',
        array(
            'label'  => esc_html__( 'Dropdown Hover Icon Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} .main-navigation > ul > li:hover > a > i.kitify-nav-arrow' => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->_add_control(
        'nav_items_text_icon_color_active',
        array(
            'label'  => esc_html__( 'Dropdown Active Icon Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} .main-navigation > ul > li.current-menu-item > a > i.kitify-nav-arrow' => 'color: {{VALUE}}',
            ),
        ),
        25
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
            'selectors' => array(
                '{{WRAPPER}} .main-navigation a i.kitify-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
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
            'selectors'  => array(
                '{{WRAPPER}} .main-navigation a i.kitify-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',
            ),
        ),
        50
    );
    $this->end_controls_section();

    $this->_start_controls_section(
        'dropdown_style',
        array(
            'label'      => esc_html__( 'Dropdown Style', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );

    $this->_add_control(
        'dropdown_bg_color',
        array(
            'label'  => esc_html__( 'Background Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['nova_menu_dropdown'] => 'background-color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_group_control(
        Group_Control_Box_Shadow::get_type(),
        array(
            'name'     => 'dropdown_box_shadow',
            'selector' => '{{WRAPPER}} '. $css_scheme['nova_menu_dropdown'],
        ),
        75
    );

    $this->_add_control(
        'dropdown_items_color',
        array(
            'label'  => esc_html__( 'Text Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['nova_menu_dropdown_li'].'  span' => 'color: {{VALUE}}',
                '{{WRAPPER}} '. $css_scheme['nova_menu_dropdown_li'].'  a' => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_control(
        'dropdown_items_hover_color',
        array(
            'label'  => esc_html__( 'Text Hover Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['nova_menu_dropdown_li'].'  a:hover' => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_control(
        'dropdown_items_active_color',
        array(
            'label'  => esc_html__( 'Text Active Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['nova_menu_dropdown_li_active'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );


    $this->_add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'dropdown_items_typography',
            'selector' => '{{WRAPPER}} '. $css_scheme['nova_menu_dropdown_li'].' > a',
        ),
        50
    );
    $this->end_controls_section();

    $this->_start_controls_section(
        'mobile_menu_style',
        array(
            'label'      => esc_html__( 'Mobile Menu Style', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );

    $this->_add_control(
        'mobile_bg_color',
        array(
            'label'  => esc_html__( 'Background Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['mobile_menu_canvas'] => 'background-color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->_add_control(
        'mobile_items_color',
        array(
            'label'  => esc_html__( 'Text Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['mobile_menu_canvas'].'  a' => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->_add_control(
        'mobile_items_hover_color',
        array(
            'label'  => esc_html__( 'Text Hover Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['mobile_menu_canvas'].'  a:hover' => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_control(
        'mobile_items_active_color',
        array(
            'label'  => esc_html__( 'Text Active Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} '. $css_scheme['mobile_menu_item_active'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->end_controls_section();
    $this->_start_controls_section(
        'mobile_trigger_styles',
        array(
            'label'      => esc_html__( 'Mobile Trigger', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );
    $this->_add_responsive_control(
        'nova_menu_trigger_alignment',
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
                'flex-start'    => 'justify-content: flex-start; text-align: left;',
                'center'        => 'justify-content: center; text-align: center;',
                'flex-end'      => 'justify-content: flex-end; text-align: right;',
                'space-between' => 'justify-content: space-between; text-align: left;',
            ),
            'selectors' => array(
                '{{WRAPPER}} .kitify-nova-menu.kitify-active--mbmenu .kitify-nova-menu__mobile-trigger' => '{{VALUE}}',
            )
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger' => 'background-color: {{VALUE}}',
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger' => 'color: {{VALUE}}',
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger i' => 'color: {{VALUE}}',
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger:hover' => 'background-color: {{VALUE}}',
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger i:hover' => 'color: {{VALUE}}',
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger:hover i' => 'color: {{VALUE}}',
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger:hover' => 'border-color: {{VALUE}};',
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
            'selector'    => '{{WRAPPER}} .kitify-nova-menu__mobile-trigger',
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        ),
        75
    );

    $this->_add_responsive_control(
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger' => 'width: {{SIZE}}{{UNIT}};',
            ),
            'separator' => 'before',
        ),
        50
    );

    $this->_add_responsive_control(
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger' => 'height: {{SIZE}}{{UNIT}};',
            ),
        ),
        50
    );

    $this->_add_responsive_control(
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
                '{{WRAPPER}} .kitify-nova-menu__mobile-trigger' => 'font-size: {{SIZE}}{{UNIT}};',
            ),
        ),
        50
    );

    $this->_end_controls_section();

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
  /**
   * Returns available icons for dropdown list
   *
   * @return array
   */
  public function dropdown_arrow_icons_list() {

      return apply_filters( 'kitify/nova-menu/dropdown-icons', array(
          'novaicon-down-arrow'          => esc_html__( 'Angle', 'kitify' ),
          'novaicon-small-triangle-down' => esc_html__( 'Triangle', 'kitify' ),
          'novaicon-arrow-down'          => esc_html__( 'Arrow', 'kitify' ),
          'novaicon-i-add'               => esc_html__( 'Plus', 'kitify' ),
          'novaicon-i-add-2'             => esc_html__( 'Plus 2', 'kitify' ),
          'novaicon-e-add'               => esc_html__( 'Plus 3', 'kitify' ),
          ''                             => esc_html__( 'None', 'kitify' ),
      ) );

  }
  protected function render() {

    $settings = $this->get_settings();

    if ( ! $settings['nova_nav_menu'] ) {
        return;
    }
    $trigger_visible = filter_var( $settings['mobile_trigger_visible'], FILTER_VALIDATE_BOOLEAN );
    $mobile_menu_breakpoint = isset($settings['mobile_menu_breakpoint']) ? $settings['mobile_menu_breakpoint'] : 'tablet';
    $active_breakpoints = kitify_helper()->get_active_breakpoints();
    $breakpoint_value = 1024;
    if(isset($active_breakpoints[$mobile_menu_breakpoint])){
        $breakpoint_value = $active_breakpoints[$mobile_menu_breakpoint];
    }
    $this->add_render_attribute( 'nav-wrapper', 'class', 'kitify-nova-menu' );

    if ( $trigger_visible ) {
        $this->add_render_attribute( 'nav-wrapper', 'class', 'kitify-nova-mobile-menu');
        $this->add_render_attribute( 'nav-wrapper', 'class', 'kitify-nova-menu--style-'.$settings['nova_nav_style'] );
        $this->add_render_attribute( 'nav-wrapper', 'data-mobile-breakpoint', esc_attr($breakpoint_value) );
    }
    echo '<div ' . $this->get_render_attribute_string( 'nav-wrapper' ) . '>';
    if ( $trigger_visible ) {
        include $this->_get_global_template( 'mobile-trigger' );
        add_action('kitify/theme/canvas_panel', [ $this, 'add_panel' ] );
    }
    if ( class_exists( 'Nova_Mega_Menu_Walker' ) ) {
      echo '<nav class="main-navigation header-primary-nav">';
      wp_nav_menu(array(
        'menu'              => $settings['nova_nav_menu'],
        'container'         => false,
        'menu_class'        => 'menu nav-menu',
        'link_before'       => '',
        'link_after'        => '',
        'fallback_cb'     	=> 'Nova_Mega_Menu_Walker',
        'walker'            => new \Nova_Mega_Menu_Walker(),
        'widget_settings' => array(
            'dropdown_icon'   => $settings['dropdown_icon'],
        ),
      ));
      echo '</nav>';
    }else{
      echo '<nav class="main-navigation header-primary-nav">';
      wp_nav_menu(array(
        'menu'              => $settings['nova_nav_menu'],
        'container'         => false,
        'menu_class'        => 'menu nav-menu',
        'link_before'       => '',
        'link_after'        => '',
      ));
      echo '</nav>';
    }
      echo '</div>';
  }
  public function add_panel() {
      include $this->_get_global_template( 'mobile-canvas' );
  }
}
