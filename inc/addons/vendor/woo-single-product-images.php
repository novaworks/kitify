<?php

/**
 * Class: Kitify_Woo_Single_Product_Images
 * Name: Product Images
 * Slug: kitify-wooproduct-images
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Woo_Single_Product_Images extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-wooproduct-images';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'image', 'product', 'gallery', 'lightbox' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Images', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-product-images';
    }

    protected function register_controls() {

        if( kitify()->get_theme_support('kitify') ){
            $this->register_nova_theme_controls();
        }
        else{
            $this->start_controls_section(
                'section_product_gallery_style',
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
                'layout_type',
                [
                    'label' => esc_html__( 'Gallery Layout', 'kitify' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '1' => esc_html__('Type 01', 'kitify'),
                        '2' => esc_html__('Type 02', 'kitify'),
                        '3' => esc_html__('Type 03', 'kitify'),
                        '4' => esc_html__('Type 04', 'kitify'),
                        '5' => esc_html__('Type 05', 'kitify'),
                    ],
                    'default' => '1',
                ]
            );

            $this->add_control(
                'sale_flash',
                [
                    'label' => esc_html__( 'Sale Flash', 'kitify' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'kitify' ),
                    'label_off' => esc_html__( 'Hide', 'kitify' ),
                    'render_type' => 'template',
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'prefix_class' => '',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_border',
                    'selector' => '.woocommerce {{WRAPPER}} .woocommerce-product-gallery__trigger + .woocommerce-product-gallery__wrapper,
				.woocommerce {{WRAPPER}} .flex-viewport, .woocommerce {{WRAPPER}} .flex-control-thumbs img',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'kitify' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woocommerce-product-gallery__trigger + .woocommerce-product-gallery__wrapper,
					.woocommerce {{WRAPPER}} .flex-viewport' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'spacing',
                [
                    'label' => esc_html__( 'Spacing', 'kitify' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .flex-viewport:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'heading_thumbs_style',
                [
                    'label' => esc_html__( 'Thumbnails', 'kitify' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'thumbs_border',
                    'selector' => '.woocommerce {{WRAPPER}} .flex-control-thumbs img',
                ]
            );

            $this->add_responsive_control(
                'thumbs_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'kitify' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .flex-control-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'spacing_thumbs',
                [
                    'label' => esc_html__( 'Spacing', 'kitify' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .flex-control-thumbs li' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-bottom: {{SIZE}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .flex-control-thumbs' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
                    ],
                ]
            );

            $this->end_controls_section();

            do_action('kitify/woocommerce/single/setting/product-images', $this);
        }
    }

    protected function register_nova_theme_controls()
    {
        $this->start_controls_section(
            'section_product_gallery_layout',
            [
                'label' => esc_html__( 'Setting', 'kitify' ),
            ]
        );
        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Gallery Layout', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('Thumbnail at bottom', 'kitify'),
                    '2' => esc_html__('Thumbnail at left', 'kitify'),
                    '3' => esc_html__('Thumbnail at right', 'kitify'),
                    '4' => esc_html__('No thumbnail', 'kitify'),
                    '5' => esc_html__('Metro', 'kitify'),
                    '6' => esc_html__('Flat', 'kitify'),
                    'wc' => esc_html__('Default from WooCommerce', 'kitify'),
                ],
                'default' => '1',
            ]
        );
        $this->add_responsive_control(
            'gallery_column',
            [
                'label' => esc_html__( 'Gallery Column', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ),
                ),
                'selectors' => [
                    '{{WRAPPER}}' => '--singleproduct-image-column: {{SIZE}}',
                ],
                'condition' => [
                    'layout_type' => ['1', '4']
                ]
            ]
        );
        $this->add_responsive_control(
            'thumb_width',
            [
                'label' => esc_html__( 'Thumbnail Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%', 'vh', 'vw' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--singleproduct-thumbs-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'layout_type' => ['2','3']
                ]
            ]
        );
        $this->add_control(
            'sale_flash',
            [
                'label' => esc_html__( 'Sale Flash', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'kitify' ),
                'label_off' => esc_html__( 'Hide', 'kitify' ),
                'render_type' => 'template',
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_product_image_style',
            [
                'label' => esc_html__( 'Main gallery', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'main_image_spacing',
            [
                'label' => esc_html__( 'Main image spacing', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--singleproduct-image-spacing: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'thumb_image_spacing',
            [
                'label' => esc_html__( 'Thumbnail spacing', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--singleproduct-thumb-spacing: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_big_height',
            [
                'label' => esc_html__( 'Image big height', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%', 'vh', 'vw' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--singleproduct-image-height2: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'layout_type' => ['5']
                ]
            ]
        );
        $this->add_responsive_control(
            'image_small_height',
            [
                'label' => esc_html__( 'Image small height', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%', 'vh', 'vw' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--singleproduct-image-height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'layout_type' => ['5']
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'gallery_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .zoominner',
            )
        );

        $this->add_responsive_control(
            'gallery_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .zoominner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'gallery_shadow',
                'selector' => '{{WRAPPER}} .zoominner',
            )
        );

        $this->add_responsive_control(
            'gallery_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .zoominner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_thumbnail',
            [
                'label' => esc_html__( 'Thumbnail', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'thumb_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .flex-control-thumbs li img',
            )
        );

        $this->add_responsive_control(
            'thumb_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .flex-control-thumbs li img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'thumb_shadow',
                'selector' => '{{WRAPPER}} .flex-control-thumbs li img',
            )
        );

        $this->add_responsive_control(
            'thumb_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .flex-control-thumbs li img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_sale_label',
            [
                'label' => esc_html__( 'Sale Label', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->_add_control(
            'sale_label_color',
            array(
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-images .onsale' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'sale_label_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-images .onsale' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'sale_label_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .kitify-product-images .onsale',
            )
        );

        $this->add_responsive_control(
            'sale_label_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-images .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'sale_label_shadow',
                'selector' => '{{WRAPPER}} .kitify-product-images .onsale',
            )
        );

        $this->add_responsive_control(
            'sale_label_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-images .onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->end_controls_section();
    }

    public function render() {
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        $layout_type = $this->get_settings_for_display('layout_type');

        echo '<div class="kitify-product-images layout-type-'.esc_attr($layout_type).'">';

        if ( 'yes' === $this->get_settings_for_display('sale_flash') ) {
            wc_get_template( 'loop/sale-flash.php' );
        }

        wc_get_template( 'single-product/product-image.php' );

        // On render widget from Editor - trigger the init manually.
        if ( wp_doing_ajax() ) {
            ?>
            <script>
                jQuery( '.woocommerce-product-gallery' ).each( function() {
                    jQuery( this ).wc_product_gallery();
                } );
                jQuery(document).trigger('kitify/woocommerce/single/product-images');
            </script>
            <?php
        }

        echo '</div>';
    }

}
