<?php

/**
 * Class: Kitify_Woo_Single_Stock_Progress_Bar
 * Name: Product Stock Progress Bar
 * Slug: kitify-wooproduct-stock-bar
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Woo_Single_Stock_Progress_Bar extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-wooproduct-stock-bar';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'stock', 'quantity', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Stock Progress Bar', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-skill-bar';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_product_stock_style',
            [
                'label' => esc_html__( 'Style', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_responsive_control(
            'bar_height',
            array(
                'label' => esc_html__( 'Bar Height', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'default' => [
                    'size' => 4,
                ],
                'selectors' => array(
                    '{{WRAPPER}} .kitify-progress-bar' => '--kitify-progress-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->add_control(
            'bar_color',
            [
                'label' => esc_html__( 'Bar Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-progress-bar' => '--kitity-bar-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'bar_active_color',
            [
                'label' => esc_html__( 'Bar Active Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-progress-bar' => '--kitity-bar-active-color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_responsive_control(
            'bar_border_radius',
            array(
                'label' => esc_html__( 'Bar Border Radius', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'default' => [
                    'size' => 0,
                ],
                'selectors' => array(
                    '{{WRAPPER}} .kitify-progress-bar' => '--kitify-brd-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
          'stock_info_margin',
          array(
            'label'      => __( 'Stock Info Margin', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
              '{{WRAPPER}} .kitify-progress-bar .stock-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
          ),
          100
        );
        $this->_add_responsive_control(
          'stock_bar_margin',
          array(
            'label'      => __( 'Stock Bar Margin', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
              '{{WRAPPER}} .kitify-progress-bar .progress-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
          ),
          100
        );
        $this->end_controls_section();

        $this->_start_controls_section(
    			'stock_progress_bar_order',
    			array(
    				'label'      => esc_html__( 'Order', 'kitify' ),
    				'tab'        => Controls_Manager::TAB_STYLE,
    				'show_label' => false,
    			)
    		);

    		$this->_add_control(
    			'info_order',
    			array(
    				'label'   => esc_html__( 'Info Order', 'kitify' ),
    				'type'    => Controls_Manager::NUMBER,
    				'default' => 1,
    				'min'     => 1,
    				'max'     => 2,
    				'step'    => 1,
    				'selectors' => array(
    					'{{WRAPPER}} .kitify-progress-bar .stock-info' => 'order: {{VALUE}};',
    				),
    			),
    			100
    		);

    		$this->_add_control(
    			'bar_order',
    			array(
    				'label'   => esc_html__( 'Bar Order', 'kitify' ),
    				'type'    => Controls_Manager::NUMBER,
    				'default' => 2,
    				'min'     => 1,
    				'max'     => 2,
    				'step'    => 1,
    				'selectors' => array(
    					'{{WRAPPER}} .kitify-progress-bar .progress-area' => 'order: {{VALUE}};',
    				),
    			),
    			100
    		);

    		$this->_end_controls_section();
    }
    public function stock_progress_bar() {
  		$product_id  = get_the_ID();
  		$total_stock = (int) get_post_meta( $product_id, 'nova_total_stock_quantity', true );

  		if ( ! $total_stock ) {
  			return;
  		}

  		$current_stock = round( (int) get_post_meta( $product_id, '_stock', true ) );

  		$total_sold = $total_stock > $current_stock ? $total_stock - $current_stock : 0;
  		$percentage = $total_sold > 0 ? round( $total_sold / $total_stock * 100 ) : 0;
      $total = $total_sold + $current_stock;
  		if ( $current_stock > 0 ) {
  			echo '<div class="kitify-progress-bar kitify-stock-progress-bar">';
  				echo '<div class="stock-info">';
  					echo '<div class="total-sold">' . esc_html__( 'Sold:', 'kitify' ) . '<span> ' . esc_html( $total_sold ) . '/'.$total.'</span></div>';
  				echo '</div>';
  				echo '<div class="progress-area" title="' . esc_html__( 'Sold', 'kitify' ) . ' ' . esc_attr( $percentage ) . '%">';
  					echo '<div class="progress-bar" style="width:' . esc_attr( $percentage ) . '%;"></div>';
  				echo '</div>';
  			echo '</div>';
  		}
  	}
    protected function render() {
        $this->stock_progress_bar();
    }

    public function render_plain_content() {}

}
