<?php

/**
 * Class: Kitify_Woo_Menu_Account
 * Name: Menu Account
 * Slug: kitify-menuaccount
 */

 namespace Elementor;

 if (!defined('WPINC')) {
     die;
 }

class Kitify_Woo_Menu_Account extends Kitify_Base {

  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/menu-account.css'), ['kitify-base','kitify-canvas'], kitify()->get_version());
      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends('kitify-base' );
  }

  public function get_name() {
      return 'kitify-menu-account';
  }

  public function get_categories() {
      return [ 'kitify-woocommerce' ];
  }

  protected function get_widget_title() {
      return esc_html__( 'Menu Account', 'kitify' );
  }

  public function get_icon() {
      return 'kitify-icon-author-box';
  }

  protected function register_controls() {
    $this->start_controls_section(
        'section_settings',
        array(
            'label' => esc_html__( 'Settings', 'kitify' ),
        )
    );
    $this->_add_advanced_icon_control(
        'acc_icon',
        array(
            'label'       => esc_html__( 'Icon', 'kitify' ),
            'type'        => Controls_Manager::ICON,
            'label_block' => false,
            'file'        => '',
            'skin'        => 'inline',
            'default'     => 'dlicon users_single-03',
            'fa5_default' => array(
                'value' => 'dlicon users_single-03',
                'library' => 'dlicon',
            ),
        )
    );
    $this->add_control(
        'show_label',
        array(
            'label'        => esc_html__( 'Show Label', 'kitify' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'kitify' ),
            'label_off'    => esc_html__( 'No', 'kitify' ),
            'return_value' => 'true',
            'default'      => '',
        )
    );
    $this->end_controls_section();

    $css_scheme = \apply_filters(
        'kitify/woo-menu-acc/css-scheme',
        array(
            'acc_box'    => '.kitify-menu-account__box',
            'acc_icon_box'    => '.kitify-menu-account__icon',
            'acc_icon'    => '.kitify-menu-account__icon  i, .kitify-menu-account__icon  svg',
            'acc_label'    => '.kitify-menu-account__label',
            'acc_sub_menu'    => '.kitify-menu-account__box .sub-menu',
            'acc_sub_menu_item'    => '.kitify-menu-account__box .sub-menu li a',
            'acc_sub_menu_item_hover'    => '.kitify-menu-account__box .sub-menu li a:hover',
        )
    );


    $this->_start_controls_section(
        'section_general_style',
        array(
            'label'      => esc_html__( 'General Styles', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );
    $this->_add_control(
        'acc_icon_color',
        array(
            'label'  => esc_html__( 'Icon Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['acc_icon'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->_add_responsive_control(
        'acc_icon_size',
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
                '{{WRAPPER}} ' . $css_scheme['acc_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
            ),
        ),
        25
    );
    $this->_add_responsive_control(
        'acc_icon_space',
        array(
            'label'      => esc_html__( 'Icon Space', 'kitify' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array( 'px' ),
            'range'      => array(
                'px' => array(
                    'min' => 1,
                    'max' => 100,
                ),
            ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['acc_icon_box'] => 'margin-right: {{SIZE}}{{UNIT}};',
            ),
        ),
        25
    );
    $this->_add_control(
        'acc_label_color',
        array(
            'label'  => esc_html__( 'Label Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'separator' => 'before',
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['acc_label'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'acc_label_typography',
            'selector' => '{{WRAPPER}} ' . $css_scheme['acc_label'],
        )
    );

    $this->_add_responsive_control(
        'acc_icon_padding',
        array(
            'label'      => esc_html__( 'Padding', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%', 'em' ),
            'selectors'  => array(
              '{{WRAPPER}} ' . $css_scheme['acc_box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
            'separator' => 'before',
        ),
        25
    );

    $this->_add_control(
        'acc_menu_dropddown_bg',
        array(
            'label'  => esc_html__( 'Dropdown Background Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['acc_sub_menu'] => 'background-color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_control(
        'acc_menu_item',
        array(
            'label'  => esc_html__( 'Dropdown Item Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['acc_sub_menu_item'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_control(
        'acc_menu_item_hover',
        array(
            'label'  => esc_html__( 'Dropdown Item Color Hover', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['acc_sub_menu_item_hover'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->end_controls_section();

  }

  protected function render() {

      $settings = $this->get_settings();

      $this->__context = 'render';

      $this->_open_wrap();
      include $this->_get_global_template( 'index' );
      $this->_close_wrap();

  }
  public function add_panel() {
    include $this->_get_global_template( 'panel' );
  }
}
