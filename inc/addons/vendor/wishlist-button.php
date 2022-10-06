<?php

/**
 * Class: Kitify_Wishlist_Button
 * Name: Product Price
 * Slug: kitify-wishlist-button
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Wishlist_Button extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-wishlist-button';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }


    public function get_widget_title() {
        return esc_html__( 'Wishlist Button', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-wishtlist-button';
    }


    protected function register_controls() {
      $css_scheme = apply_filters(
          'kitify/wishlist-button/css-scheme',
          array(
              'button'    => '.yith-wcwl-add-to-wishlist',
              'icon'     => '.yith-wcwl-add-to-wishlist .yith-wcwl-icon',
              'label'    => '.yith-wcwl-add-to-wishlist a',
          )
      );
			$this->start_controls_section(
				'product_section',
				array(
					'label' => _x( 'Product', 'Elementor section title', 'kitify' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'product_id',
				array(
					'label'       => _x( 'Product ID', 'Elementor control label', 'kitify' ),
					'type'        => \Elementor\Controls_Manager::NUMBER,
					'input_type'  => 'text',
					'placeholder' => '123',
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'labels_section',
				array(
					'label' => _x( 'Labels', 'Elementor section title', 'kitify' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'label',
				array(
					'label'       => _x( 'Button label', 'Elementor control label', 'kitify' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => __( 'Add to wishlist', 'kitify' ),
				)
			);

			$this->add_control(
				'browse_wishlist_text',
				array(
					'label'       => _x( '"Browse wishlist" label', 'Elementor control label', 'kitify' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => __( 'Browse wishlist', 'kitify' ),
				)
			);

			$this->add_control(
				'already_in_wishslist_text',
				array(
					'label'       => _x( '"Product already in wishlist" label', 'Elementor control label', 'kitify' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => __( 'Product already in wishlist', 'kitify' ),
				)
			);

			$this->add_control(
				'product_added_text',
				array(
					'label'       => _x( '"Product added to wishlist" label', 'Elementor control label', 'kitify' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => __( 'Product added to wishlist', 'kitify' ),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'advanced_section',
				array(
					'label' => _x( 'Advanced', 'Elementor section title', 'kitify' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'wishlist_url',
				array(
					'label'       => _x( 'URL of the wishlist page', 'Elementor control label', 'kitify' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'url',
					'placeholder' => '',
				)
			);

			$this->add_control(
				'icon',
				array(
					'label'       => _x( 'Icon for the button', 'Elementor control label', 'kitify' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => '',
				)
			);

			$this->add_control(
				'link_classes',
				array(
					'label'       => _x( 'Additional CSS classes for the button', 'Elementor control label', 'kitify' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'input_type'  => 'text',
					'placeholder' => '',
				)
			);

			$this->end_controls_section();

      $this->_start_controls_section(
          'section_button_style',
          array(
              'label'      => esc_html__( 'Button', 'kitify' ),
              'tab'        => Controls_Manager::TAB_STYLE,
              'show_label' => false,
          )
      );
      $this->_add_responsive_control(
          'button_padding',
          array(
              'label'      => __( 'Padding', 'kitify' ),
              'type'       => Controls_Manager::DIMENSIONS,
              'size_units' => array( 'px', '%' ),
              'selectors'  => array(
                  '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
              ),
          ),
          25
      );
      $this->_add_group_control(
          Group_Control_Border::get_type(),
          array(
              'name'        => 'toggle_border',
              'label'       => esc_html__( 'Border', 'kitify' ),
              'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
          ),
          25
      );

      $this->_add_control(
          'toggle_border_radius',
          array(
              'label'      => esc_html__( 'Border Radius', 'kitify' ),
              'type'       => Controls_Manager::DIMENSIONS,
              'size_units' => array( 'px', '%' ),
              'selectors'  => array(
                  '{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
              ),
          ),
          25
      );
      $this->_add_control(
          'button_icon_color',
          array(
              'label'     => esc_html__( 'Icon Color', 'kitify' ),
              'type'      => Controls_Manager::COLOR,
              'separator'  => 'before',
              'selectors' => array(
                  '{{WRAPPER}} ' . $css_scheme['icon'] => 'color: {{VALUE}}',
              ),
          ),
          25
      );
      $this->_add_responsive_control(
          'button_icon_size',
          [
              'label' => esc_html__( 'Icon Size', 'kitify' ),
              'type' => Controls_Manager::SLIDER,
              'range' => [
                  'px' => [
                      'min' => 6,
                      'max' => 300,
                  ],
              ],
              'selectors' => [
                  '{{WRAPPER}} ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
              ],
          ]
      );
      $this->_add_control(
          'button_label_color',
          array(
              'label'     => esc_html__( 'Label Color', 'kitify' ),
              'type'      => Controls_Manager::COLOR,
              'separator'  => 'before',
              'selectors' => array(
                  '{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
              ),
          ),
          25
      );

      $this->_add_group_control(
          Group_Control_Typography::get_type(),
          array(
              'name'     => 'button_label_typography',
              'selector' => '{{WRAPPER}} '. $css_scheme['label'],
          ),
          25
      );
      $this->_end_controls_section();
		}

    protected function render() {

			$attribute_string = '';
			$settings         = $this->get_settings_for_display();

			foreach ( $settings as $key => $value ) {
				if ( empty( $value ) || ! is_scalar( $value ) ) {
					continue;
				}
				$attribute_string .= " {$key}=\"{$value}\"";
			}

			echo do_shortcode( "[yith_wcwl_add_to_wishlist {$attribute_string}]" );
		}

}
