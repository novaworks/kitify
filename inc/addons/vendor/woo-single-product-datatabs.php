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
        return 'kitify-icon-woocommerce-tabs';
    }

    protected function register_controls() {
        $tabs_layout = apply_filters(
            'kitify/wootabs/layout/tabs_layout',
            array(
                'default' => esc_html__( 'Default', 'kitify' ),
                'accordion' => esc_html__( 'Accordion', 'kitify' ),
            )
        );
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
        }
        else {
            $this->add_control(
                'layout_type',
                [
                    'label' => esc_html__( 'Layout', 'kitify' ),
                    'type' => Controls_Manager::SELECT,
                    'options'   => $tabs_layout,
                    'default' => 'default',
                ]
            );
        }


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
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs' => 'justify-content: {{VALUE}};'
                )
            )
        );

        $this->_add_control(
            'tabs_content_wrapper_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_wrapper_border',
                'selector'    => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs',
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .layout-type-accordion .wc-tabs-wrapper .wc-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs'
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
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title ' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title > a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_control_text_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_text_typography',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title > a'
            )
        );

        $this->_add_responsive_control(
            'tabs_control_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title',
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
                  '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title.is-active' => 'color: {{VALUE}};',
                  '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title.is-active > a' => 'color: {{VALUE}};',
                  '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title > a:hover' => 'color: {{VALUE}};',
                ),
            )
        );
        $this->_add_control(
            'tabs_control_text_bgcolor_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title.is-active:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title.is-active' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_text_typography_hover',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title > a:hover,{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title.is-active > a'
            )
        );


        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border_hover',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title.is-active,{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title:hover',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title.is-active,{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .tabs .tabs-title:hover',
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
                'selector' => '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content'
            )
        );
        $this->_add_control(
            'tabs_content_text_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_heading_color',
            array(
                'label'  => esc_html__( 'Heading Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content h1,{{WRAPPER}} .nova-woocommerce-tabs .tabs-content h2,{{WRAPPER}} .nova-woocommerce-tabs .tabs-content h3,{{WRAPPER}} .nova-woocommerce-tabs .tabs-content h4,{{WRAPPER}} .nova-woocommerce-tabs .tabs-content h5,{{WRAPPER}} .nova-woocommerce-tabs .tabs-content h6' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_link_color',
            array(
                'label'  => esc_html__( 'Link Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_content_link_hover_color',
            array(
                'label'  => esc_html__( 'Link Hover Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content a:hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_content_background',
                'selector' => '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content',
            )
        );

        $this->_add_responsive_control(
            'tabs_content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_content_border',
                'selector'  => '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content',
            )
        );

        $this->_add_responsive_control(
            'tabs_content_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_content_box_shadow',
                'selector' => '{{WRAPPER}} .nova-woocommerce-tabs .tabs-content',
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_tabs_accordion_item_style',
            array(
                'label'      => esc_html__( 'Accordion Item', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->_start_controls_tabs( 'tabs_acc_item_styles' );
        $this->_start_controls_tab(
            'tabs_acc_item_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );
        $this->_add_control(
            'tabs_acc_item_text_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_acc_item_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_acc_item_typography',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a'
            )
        );
        $this->_add_responsive_control(
            'tabs_acc_item_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a:before' => 'right: {{TOP}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'tabs_acc_item_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'tabs_acc_item_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_acc_item_border',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_acc_item_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a',
            )
        );
        $this->_end_controls_tab();
        $this->_start_controls_tab(
            'tabs_acc_item_hover',
            array(
                'label' => esc_html__( 'Hover & Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'tabs_acc_item_text_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                  '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item.is-active > a' => 'color: {{VALUE}};',
                  '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a:hover' => 'color: {{VALUE}};',
                ),
            )
        );
        $this->_add_control(
            'tabs_acc_item_text_bgcolor_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item:hover > a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item.is-active > a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item:hover > a' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_acc_item_text_typography_hover',
                'selector' => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item > a:hover,{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item.is-active > a'
            )
        );


        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_acc_item_border_hover',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item.is-active,{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item:hover',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_acc_item_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item.is-active,{{WRAPPER}} .kitify-product-tabs .nova-woocommerce-tabs .accordion .accordion-item:hover',
            )
        );

        $this->_end_controls_tab();
        $this->_end_controls_tabs();
        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_tabs_acc_content_style',
            array(
                'label'      => esc_html__( 'Accordion Content', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_acc_content_typography',
                'selector' => '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content'
            )
        );
        $this->_add_control(
            'tabs_acc_content_text_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_acc_content_heading_color',
            array(
                'label'  => esc_html__( 'Heading Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content h1,{{WRAPPER}} .nova-woocommerce-tabs .accordion-content h2,{{WRAPPER}} .nova-woocommerce-tabs .accordion-content h3,{{WRAPPER}} .nova-woocommerce-tabs .accordion-content h4,{{WRAPPER}} .nova-woocommerce-tabs .accordion-content h5,{{WRAPPER}} .nova-woocommerce-tabs .accordion-content h6' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_acc_content_link_color',
            array(
                'label'  => esc_html__( 'Link Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content a' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_acc_content_link_hover_color',
            array(
                'label'  => esc_html__( 'Link Hover Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content a:hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_acc_content_background',
                'selector' => '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content',
            )
        );

        $this->_add_responsive_control(
            'tabs_acc_content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_acc_content_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_acc_content_border',
                'selector'  => '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content',
            )
        );

        $this->_add_responsive_control(
            'tabs_acc_content_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_acc_content_box_shadow',
                'selector' => '{{WRAPPER}} .nova-woocommerce-tabs .accordion-content',
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

        echo '<div class="kitify-product-tabs layout-type-'.esc_attr($layout_type).'">';
        if($layout_type == 'accordion') {
          wc_get_template( 'single-product/tabs/tabs-accordion.php' );
        }else{
          wc_get_template( 'single-product/tabs/tabs.php' );
        }

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
