<?php

/**
 * Class: Kitify_Woo_Single_Product_Rating
 * Name: Product Rating
 * Slug: kitify-wooproduct-rating
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Woo_Single_Product_Rating extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-wooproduct-rating';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'rating', 'review', 'comments', 'stars', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Rating', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-woo-rating';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_product_rating_style',
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

        $this->add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .star-rating' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label' => esc_html__( 'Empty Star Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .star-rating::before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Link Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .woocommerce-review-link' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '.woocommerce {{WRAPPER}} .woocommerce-review-link',
            ]
        );

        $this->add_control(
            'star_size',
            [
                'label' => esc_html__( 'Star Size', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 4,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 4,
                        'step' => 0.1,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '.woocommerce:not(.rtl) {{WRAPPER}} .star-rating' => 'margin-right: {{SIZE}}{{UNIT}}',
                    '.woocommerce.rtl {{WRAPPER}} .star-rating' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
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
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'kitify' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors_dictionary' => [
                    'left'    => 'justify-content: flex-start;',
                    'center' => 'justify-content: center;',
                    'right' => 'justify-content: flex-end;',
                    'justify' => 'justify-content: space-between;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-product-rating' => '{{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if ( ! post_type_supports( 'product', 'comments' ) ) {
            return;
        }

        global $product;
        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        wc_get_template( 'single-product/rating.php' );
    }

    public function render_plain_content() {}

}
