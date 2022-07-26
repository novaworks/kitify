<?php

/**
 * Class: Kitify_Woo_Single_Product_Stock
 * Name: Product Stock
 * Slug: kitify-wooproduct-stock
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Woo_Single_Product_Stock extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-wooproduct-stock';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'stock', 'quantity', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Stock', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-woo-stock';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_product_stock_style',
            [
                'label' => esc_html__( 'Style', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wc_style_warning',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'kitify' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_responsive_control(
            'padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '.woocommerce {{WRAPPER}} .stock' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '.woocommerce {{WRAPPER}} .stock' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__( 'Typography', 'kitify' ),
                'selector' => '.woocommerce {{WRAPPER}} .stock',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        global $product;
        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        echo wc_get_stock_html( $product );
    }

    public function render_plain_content() {}

}
