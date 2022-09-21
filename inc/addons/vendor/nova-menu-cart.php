<?php

/**
 * Class: Kitify_Nova_Menu_Cart
 * Name: Nova Mini Cart
 * Slug: kitify-nova-cart
 */

 namespace Elementor;

 if (!defined('WPINC')) {
     die;
 }

class Kitify_Nova_Menu_Cart extends Kitify_Base {

  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/nova-cart.css'), ['kitify-base'], kitify()->get_version());
      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends('kitify-base' );
  }

  public function get_name() {
      return 'kitify-nova-cart';
  }

  public function get_categories() {
      return [ 'kitify-woocommerce' ];
  }

  protected function get_widget_title() {
      return esc_html__( 'Nova Mini Cart', 'kitify' );
  }

  public function get_icon() {
      return 'kitify-icon-cart';
  }

  protected function register_controls() {
    $preset_type = apply_filters(
      'kitify/nova-menu-cart/control/preset',
      array(
        'default' => esc_html__( 'Default', 'kitify' ),
      )
    );
    $this->start_controls_section(
        'section_settings',
        array(
            'label' => esc_html__( 'Settings', 'kitify' ),
        )
    );
    $this->_add_control(
      'preset',
      array(
        'label'   => esc_html__( 'Preset', 'kitify' ),
        'type'    => Controls_Manager::SELECT,
        'default' => 'default',
        'options' => $preset_type
      )
    );
    $this->_add_advanced_icon_control(
        'novacart_icon',
        array(
            'label'       => esc_html__( 'Icon', 'kitify' ),
            'type'        => Controls_Manager::ICON,
            'label_block' => false,
            'file'        => '',
            'skin'        => 'inline',
            'default'     => 'dlicon shopping_cart-simple',
            'fa5_default' => array(
                'value' => 'dlicon shopping_cart-simple',
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
        'kitify/nova-cart/css-scheme',
        array(
            'cart_box'    => '.kitify-nova-cart',
            'cart_icon'    => '.kitify-nova-cart__icon',
            'cart_count'    => '.kitify-nova-cart .header-cart-box .count-badge',
            'cart_label'    => '.kitify-nova-cart .header-cart-box .cart-text .count-text',
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
        'novacart_icon_color',
        array(
            'label'  => esc_html__( 'Icon Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['cart_icon'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->_add_responsive_control(
        'novacart_icon_size',
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
                '{{WRAPPER}} ' . $css_scheme['cart_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
            ),
        ),
        50
    );
    $this->_add_control(
      'novacart_icon_bg',
      array(
        'label'     => esc_html__( 'Background Color', 'kitify' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['cart_icon'] => 'background-color: {{VALUE}}',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Border::get_type(),
      array(
        'name'        => 'novacart_icon_border',
        'label'       => esc_html__( 'Border', 'kitify' ),
        'placeholder' => '0',
        'default'     => '0',
        'selector'    => '{{WRAPPER}} ' . $css_scheme['cart_icon'],
      )
    );

    $this->_add_responsive_control(
      'novacart_icon_border_radius',
      array(
        'label'      => __( 'Border Radius', 'kitify' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} ' . $css_scheme['cart_icon'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_responsive_control(
        'novacart_icon_padding',
        array(
            'label'      => esc_html__( 'Padding', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%', 'em' ),
            'selectors'  => array(
              '{{WRAPPER}} ' . $css_scheme['cart_icon'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        ),
        25
    );

    $this->_add_control(
        'novacart_count_bg',
        array(
            'label'  => esc_html__( 'Count Background Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['cart_count'] => 'background-color: {{VALUE}}',
            ),
            'separator' => 'before',
        ),
        25
    );
    $this->_add_control(
        'novacart_count_text',
        array(
            'label'  => esc_html__( 'Count Text Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['cart_count'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name' => 'novacart_label_typography',
            'selector' => '{{WRAPPER}} ' . $css_scheme['cart_count'],
        ]
    );
    $this->_add_control(
        'novacart_label_text',
        array(
            'label'  => esc_html__( 'Label Color', 'kitify' ),
            'type'   => Controls_Manager::COLOR,
            'separator' => 'before',
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['cart_label'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );
    $this->_add_responsive_control(
        'novacart_label_top',
        array(
            'label'      => esc_html__( 'Position Top', 'kitify' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array( 'px','%' ),
            'range'      => array(
                'px' => array(
                    'min' => -50,
                    'max' => 50,
                ),
            ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['cart_count'] => 'top: {{SIZE}}{{UNIT}};',
            ),
        ),
        50
    );
    $this->_add_responsive_control(
        'novacart_label_left',
        array(
            'label'      => esc_html__( 'Position Left', 'kitify' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array( 'px','%' ),
            'range'      => array(
                'px' => array(
                  'min' => -50,
                  'max' => 50,
                ),
            ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['cart_count'] => 'left: {{SIZE}}{{UNIT}};',
            ),
        ),
        50
    );
    $this->end_controls_section();
    $this->_start_controls_section(
        'section_box_style',
        array(
            'label'      => esc_html__( 'Box Styles', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );
    $this->_add_responsive_control(
        'novacart_box_padding',
        array(
            'label'      => esc_html__( 'Padding', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%', 'em' ),
            'selectors'  => array(
              '{{WRAPPER}} ' . $css_scheme['cart_box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
      add_action('kitify/theme/canvas_panel', [ $this, 'add_panel' ] );
  }
  public function add_panel() {
    include $this->_get_global_template( 'panel' );
  }
}
