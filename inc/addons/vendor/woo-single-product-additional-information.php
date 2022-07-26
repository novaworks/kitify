<?php

/**
 * Class: Kitify_Woo_Single_Product_Additional_Information
 * Name: Product Additional Information
 * Slug: kitify-wooproduct-additional-information
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Woo_Single_Product_Additional_Information extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-wooproduct-additional-information';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Additional Information', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-product-info';
    }

    protected function register_controls() {

        $this->start_controls_section( 'section_additional_info_style', [
            'label' => esc_html__( 'General', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control(
            'show_heading',
            [
                'label' => esc_html__( 'Heading', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'kitify' ),
                'label_off' => esc_html__( 'Hide', 'kitify' ),
                'render_type' => 'ui',
                'return_value' => 'yes',
                'default' => 'yes',
                'prefix_class' => 'elementor-show-heading-',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} h2' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => esc_html__( 'Typography', 'kitify' ),
                'selector' => '.woocommerce {{WRAPPER}} h2',
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .shop_attributes' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => esc_html__( 'Typography', 'kitify' ),
                'selector' => '.woocommerce {{WRAPPER}} .shop_attributes',
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

        wc_get_template( 'single-product/tabs/additional-information.php' );
    }

    public function render_plain_content() {}

}
