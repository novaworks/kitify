<?php

/**
 * Class: Kitify_Woo_Single_Product_Datatabs
 * Name: Product Data Tabs
 * Slug: kitify-wooproduct-datatabs
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Woo_Single_Product_Datatabs extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-wooproduct-datatabs';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'data', 'product', 'tabs' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Data Tabs', 'kitify' );
    }

    public function get_icon() {
        return 'eicon-product-tabs';
        return 'kitify-icon-woocommerce-pages';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_product_tabs_setting',
            [
                'label' => esc_html__( 'Settings', 'kitify' ),
            ]
        );

        if( !kitify()->get_theme_support('kitify')) {
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
                    'label' => esc_html__( 'Layout', 'kitify' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default' => esc_html__('Default', 'kitify'),
                    ],
                    'default' => 'default',
                ]
            );
            $this->add_control(
                'accordion_icon',
                [
                    'label' => esc_html__( 'Accordion Icon', 'kitify' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'plus'   => esc_html__('Plus/Minus Icon', 'kitify'),
                    ],
                    'default' => 'plus',
                ]
            );
        }
        else{
            $this->add_control(
                'layout_type',
                [
                    'label' => esc_html__( 'Layout', 'kitify' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default'   => esc_html__('Default', 'kitify'),
                        'tab_left'  => esc_html__('Tab left', 'kitify'),
                        'tab_right' => esc_html__('Tab Right', 'kitify'),
                        'accordion' => esc_html__('Accordion', 'kitify'),
                    ],
                    'default' => 'default',
                ]
            );

            $this->add_control(
                'accordion_icon',
                [
                    'label' => esc_html__( 'Accordion Icon', 'kitify' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'plus'   => esc_html__('Plus/Minus Icon', 'kitify'),
                        'arrow'  => esc_html__('Up/Down Icon', 'kitify'),
                    ],
                    'default' => 'plus',
                ]
            );
        }

        $this->_add_responsive_control(
            'tabs_controls_width',
            array(
                'label'      => esc_html__( 'Tabs Controls Width', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%', 'em', 'vw', 'vh',
                ),
                'condition' => array(
                    'layout_type' => array( 'tab_left', 'tab_right' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--singleproduct-datatab-width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->end_controls_section();

        $this->_start_controls_section(
            'section_tabs_control_style',
            array(
                'label'      => esc_html__( 'Tabs Control', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->add_control(
            'tabs_controls_alignment',
            array(
                'label'   => esc_html__( 'Tabs Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
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
                ),
                'condition' => array(
                    'layout_type' => 'default',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls' => 'justify-content: {{VALUE}};'
                )
            )
        );

        $this->_add_control(
            'tabs_controls_width_auto',
            array(
                'label'        => esc_html__( 'Auto Width', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'kitify' ),
                'label_off'    => esc_html__( 'Off', 'kitify' ),
                'return_value' => 'yes',
                'prefix_class' => 'kitify-tab-auto-with-',
                'condition' => array(
                    'layout_type' => 'default',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_wrapper_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_padding',
            array(
                'label'      =>esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_margin',
            array(
                'label'      =>esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_wrapper_border',
                'selector'    => '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs, {{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab',
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs, {{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab'
            )
        );
        
        $this->end_controls_section();

        $this->_start_controls_section(
            'section_tabs_control_item_style',
            array(
                'label'      => esc_html__( 'Tabs Control Item', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_control_styles' );
        $this->_start_controls_tab(
            'tabs_control_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );
        $this->_add_control(
            'tabs_control_text_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wc-tab-title a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_control_text_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wc-tab-title a' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_text_typography',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li, {{WRAPPER}} .wc-tab-title a'
            )
        );

        $this->_add_responsive_control(
            'tabs_control_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wc-tab-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wc-tab-title a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wc-tab-title a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li, {{WRAPPER}} .wc-tab-title a',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li, {{WRAPPER}} .wc-tab-title a',
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tabs_control_hover',
            array(
                'label' => esc_html__( 'Hover & Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'tabs_control_text_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li.active' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_control_text_bgcolor_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li.active' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_text_typography_hover',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li:hover,{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li.active, {{WRAPPER}} .active .wc-tab-title a'
            )
        );

        $this->_add_responsive_control(
            'tabs_control_padding_hover',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_margin_hover',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_border_radius_hover',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .active .wc-tab-title a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border_hover',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li:hover,{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li.active,{{WRAPPER}} .active .wc-tab-title a',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li:hover,{{WRAPPER}} .kitify-product-tabs .wc-tabs-wrapper .kitify-wc-tabs--controls ul.wc-tabs li.active,{{WRAPPER}} .active .wc-tab-title a',
            )
        );

        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->end_controls_section();

        $this->_start_controls_section(
            'section_tabs_content_style',
            array(
                'label'      => esc_html__( 'Tabs Content', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_content_typography',
                'selector' => '{{WRAPPER}} .tab-content'
            )
        );
        $this->_add_control(
            'tabs_content_text_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .tab-content' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_heading_color',
            array(
                'label'  => esc_html__( 'Heading Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .tab-content h1,{{WRAPPER}} .tab-content h2,{{WRAPPER}} .tab-content h3,{{WRAPPER}} .tab-content h4,{{WRAPPER}} .tab-content h5,{{WRAPPER}} .tab-content h6' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_link_color',
            array(
                'label'  => esc_html__( 'Link Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .tab-content a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_link_hover_color',
            array(
                'label'  => esc_html__( 'Link Hover Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .tab-content a:hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_content_background',
                'selector' => '{{WRAPPER}} .tab-content',
            )
        );

        $this->_add_responsive_control(
            'tabs_content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_content_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_content_border',
                'selector'  => '{{WRAPPER}} .tab-content',
            )
        );

        $this->_add_responsive_control(
            'tabs_content_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_content_box_shadow',
                'selector' => '{{WRAPPER}} .tab-content',
            )
        );

        $this->_end_controls_section();

        do_action('kitify/woocommerce/single/setting/product-tabs', $this);
    }

    protected function render() {
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        $layout_type = $this->get_settings_for_display('layout_type');
        $accordion_icon = $this->get_settings_for_display('accordion_icon');

        echo '<div class="kitify-product-tabs layout-type-'.esc_attr($layout_type).' kitifyicon-type-'.esc_attr($accordion_icon).'">';

        wc_get_template( 'single-product/tabs/tabs.php' );

        echo '</div>';

        // On render widget from Editor - trigger the init manually.
        if ( wp_doing_ajax() ) {
            ?>
            <script>
                jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
                jQuery(document).trigger('kitify/woocommerce/single/product-tabs');
            </script>
            <?php
        }
    }

    public function render_plain_content() {}

}