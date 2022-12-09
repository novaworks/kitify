<?php
/**
 * Class: Kitify_Woo_Products
 * Name: Products
 * Slug: kitify-wooproducts
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use KitifyExtensions\Elementor\Classes\Query_Control as Module_Query;
use KitifyExtensions\Elementor\Controls\Group_Control_Query;
use KitifyThemeBuilder\Modules\Woocommerce\Classes\Products_Renderer;
use KitifyThemeBuilder\Modules\Woocommerce\Classes\Current_Query_Renderer;

class Kitify_Woo_Products extends Kitify_Base {

    public static $__called_index = 0;
    public static $__called_item = false;

    protected function enqueue_addon_resources(){
	    if(!kitify_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'kitify-woocommerce' );
		    $this->add_script_depends( 'kitify-base' );
	    }
    }

    public function get_name() {
        return 'kitify-wooproducts';
    }

    public function get_widget_title() {
        return esc_html__( 'Products', 'kitify' );
    }

    public function get_categories() {
        return [ 'kitify-woocommerce' ];
    }

    public function get_icon() {
        return 'kitify-icon-products';
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'product', 'archive' ];
    }

    protected function register_advance_control_layout(){

    }

    protected function register_query_controls() {
        $this->_start_controls_section(
            'section_query',
            [
                'label' => esc_html__( 'Query', 'kitify' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->_add_group_control(
            Group_Control_Query::get_type(),
            [
                'name' => 'query',
                'post_type' => 'product',
                'presets' => [ 'full' ],
                'fields_options' => [
                    'post_type' => [
                        'default' => 'product',
                        'options' => [
                            'current_query' => esc_html__( 'Current Query', 'kitify' ),
                            'product' => esc_html__( 'Latest Products', 'kitify' ),
                            'sale' => esc_html__( 'Sale', 'kitify' ),
                            'featured' => esc_html__( 'Featured', 'kitify' ),
                            'related' => esc_html__( 'Related', 'kitify' ),
                            'upsells' => esc_html__( 'Up-Sells', 'kitify' ),
                            'by_id' => esc_html_x( 'Manual Selection', 'Posts Query Control', 'kitify' ),
                        ],
                    ],
                    'orderby' => [
                        'default' => 'date',
                        'options' => [
                            'date' => esc_html__( 'Date', 'kitify' ),
                            'title' => esc_html__( 'Title', 'kitify' ),
                            'price' => esc_html__( 'Price', 'kitify' ),
                            'popularity' => esc_html__( 'Popularity', 'kitify' ),
                            'rating' => esc_html__( 'Rating', 'kitify' ),
                            'rand' => esc_html__( 'Random', 'kitify' ),
                            'menu_order' => esc_html__( 'Menu Order', 'kitify' ),
                        ],
                    ],
                    'exclude' => [
                        'options' => [
                            'current_post' => esc_html__( 'Current Post', 'kitify' ),
                            'manual_selection' => esc_html__( 'Manual Selection', 'kitify' ),
                            'terms' => esc_html__( 'Term', 'kitify' ),
                        ],
                    ],
                    'include' => [
                        'options' => [
                            'terms' => esc_html__( 'Term', 'kitify' ),
                        ],
                    ],
                    'exclude_ids' => [
                        'object_type' => 'product',
                    ],
                    'include_ids' => [
                        'object_type' => 'product',
                    ],
                ],
                'exclude' => [
                    'posts_per_page',
                    'exclude_authors',
                    'authors',
                    'offset',
                    'related_fallback',
                    'related_ids',
                    'query_id',
                    'ignore_sticky_posts',
                ],
            ]
        );

        $this->_add_control(
            'nothing_found_message',
            [
                'label' => esc_html__( 'Nothing Found Message', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'heading',
            [
                'label' => esc_html__( 'Custom Heading', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'html_tag',
            [
                'label' => esc_html__( 'Heading HTML Tag', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'h2',
                'condition' => [
                    'heading!' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_section_style_pagination( ){
        /**
         * Pagination section
         */
        $this->_start_controls_section(
            'section_pagination_style',
            [
                'label'     => __( 'Pagination', 'kitify' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_align',
            [
                'label'     => __( 'Alignment', 'kitify' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->_add_responsive_control(
            'pagination_spacing',
            [
                'label'     => __( 'Spacing', 'kitify' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_control(
            'show_pagination_border',
            [
                'label'        => __( 'Border', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => __( 'Hide', 'kitify' ),
                'label_on'     => __( 'Show', 'kitify' ),
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->_add_control(
            'pagination_border_color',
            [
                'label'     => __( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-border-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_pagination_border' => 'yes',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_item_width',
            [
                'label'     => __( 'Item Width', 'kitify' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-item-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_item_spacing',
            [
                'label'     => __( 'Item Spacing', 'kitify' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-item-spacing: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_item_radius',
            [
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => [
                    '{{WRAPPER}} .kitify-pagination .page-numbers' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'pagination_padding',
            [
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => [
                    '{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'selector' => '{{WRAPPER}} .kitify-pagination',
            ]
        );

        $this->_start_controls_tabs( 'pagination_style_tabs' );

        $this->_start_controls_tab( 'pagination_style_normal',
            [
                'label' => __( 'Normal', 'kitify' ),
            ]
        );

        $this->_add_control(
            'pagination_link_color',
            [
                'label'     => __( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-link-color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_control(
            'pagination_link_bg_color',
            [
                'label'     => __( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-link-bg-color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab( 'pagination_style_hover',
            [
                'label' => __( 'Active', 'kitify' ),
            ]
        );

        $this->_add_control(
            'pagination_link_color_hover',
            [
                'label'     => __( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-link-hover-color: {{VALUE}}',
                ],
            ]
        );

        $this->_add_control(
            'pagination_link_bg_color_hover',
            [
                'label'     => __( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-link-hover-bg-color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();
    }

    protected function kitify_register_controls(){
        $grid_style = apply_filters(
            'kitify/products/control/grid_style',
            array(
                '1' => esc_html__( 'Type-1', 'kitify' ),
                '2' => esc_html__( 'Type-2', 'kitify' )
            )
        );

        $list_style = apply_filters(
            'kitify/products/control/list_style',
            array(
                '1' => esc_html__( 'Type-1', 'kitify' ),
                '2' => esc_html__( 'Type-2', 'kitify' )
            )
        );

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Layout', 'kitify' ),
            ]
        );

        $this->add_control(
            'layout',
            array(
                'label'     => esc_html__( 'Layout', 'kitify' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'grid',
                'render_type' => 'template',
                'options'   => [
                    'grid'      => esc_html__( 'Grid', 'kitify' ),
                    'list'      => esc_html__( 'List', 'kitify' ),
                ]
            )
        );

        $this->add_control(
            'grid_style',
            array(
                'label'     => esc_html__( 'Style', 'kitify' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $grid_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'grid'
                ]
            )
        );

        $this->add_control(
            'list_style',
            array(
                'label'     => esc_html__( 'Style', 'kitify' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $list_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'list'
                ]
            )
        );

        $this->_add_control(
            'enable_masonry',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Enable Masonry?', 'kitify' ),
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'kitify' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'kitify' ),
                'type' => Controls_Manager::NUMBER,
                'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
                'render_type' => 'template',
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
            ]
        );

        $this->add_control(
            'item_html_tag',
            [
                'label' => esc_html__( 'Product Title HTML Tag', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'enable_custom_image_size',
            [
                'label' => esc_html__( 'Enable Custom Image Size', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'image_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'kitify' ),
                'default'    => 'shop_catalog',
                'options'    => kitify_helper()->get_image_sizes(),
                'condition' => [
                    'enable_custom_image_size' => 'yes'
                ]
            )
        );

        $this->add_control(
            'enable_alt_image',
            [
                'label' => esc_html__( 'Enable Crossfade Image Effect', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => ''
            ]
        );
        if( kitify()->get_theme_support('kitify-woo::stock-progress-bar') ){
          $this->add_control(
              'enable_stock_progress_bar',
              [
                  'label' => esc_html__( 'Enable Stock progress bar', 'kitify' ),
                  'type' => Controls_Manager::SWITCHER,
                  'return_value' => 'yes',
                  'default' => ''
              ]
          );
        }
        $this->register_advance_control_layout();

        $this->add_control(
            'allow_order',
            [
                'label' => esc_html__( 'Allow Order', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'show_result_count',
            [
                'label' => esc_html__( 'Show Result Count', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'paginate',
            [
                'label' => esc_html__( 'Pagination', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'paginate_as_loadmore',
            [
                'label' => esc_html__( 'Use Load More', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'paginate_infinite',
            [
                'label' => esc_html__( 'Infinite loading', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loadmore_text',
            [
                'label' => esc_html__( 'Load More Text', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Load More',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->register_query_controls();

        $this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ] );

        $this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns');

        $this->start_controls_section(
            'section_products_style',
            [
                'label' => esc_html__( 'Products', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => esc_html__( 'Columns Gap', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products:not(.swiper-wrapper)' => 'margin-right: -{{SIZE}}{{UNIT}}; margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ul.products li.product' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}' => '--kitify-carousel-item-right-space: {{SIZE}}{{UNIT}}; --kitify-carousel-item-left-space: {{SIZE}}{{UNIT}};--kitify-gcol-left-space: {{SIZE}}{{UNIT}}; --kitify-gcol-right-space: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => esc_html__( 'Rows Gap', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_image_style',
            [
                'label' => esc_html__( 'Image', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_bg',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'image2_bg',
            [
                'label' => esc_html__( 'Crossfade Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit.p_img-second' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}' => '--kitify-p_img_color: {{VALUE}}',
                ],
                'condition' => [
                    'enable_alt_image' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'custom_image_width',
            array(
                'label' => esc_html__( 'Image Width', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%'
                ],
                'selectors' => \apply_filters('kitify/products/thumbnail_width_selector', array(
                    '{{WRAPPER}} .woocommerce-loop-product__link' => 'width: {{SIZE}}{{UNIT}};'
                ))
            )
        );

        $this->add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class' => 'active-object-fit active-object-fit-',
            )
        );

        $this->add_responsive_control(
            'custom_image_height',
            array(
                'label' => esc_html__( 'Image Height', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 2000,
                    )
                ),
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%'
                ],
                'selectors' => apply_filters('kitify/products/thumbnail_height_selector', array(
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit' => 'padding-bottom: {{SIZE}}{{UNIT}};'
                )),
                'condition' => [
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->add_control(
            'image_pos',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Position', 'kitify' ),
                'default'    => 'center',
                'options'    => [
                    'center'    => esc_html__( 'Center', 'kitify' ),
                    'top'       => esc_html__( 'Top', 'kitify' ),
                    'bottom'    => esc_html__( 'Bottom', 'kitify' ),
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'selectors' => apply_filters('kitify/products/thumbnail_height_selector', array(
                    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit > *' => 'object-position: {{VALUE}}; background-position: {{VALUE}}'
                )),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} ul.products li.product .product_item--thumbnail',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label' => esc_html__( 'Title', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .woocommerce-loop-product__title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_rating_style',
            [
                'label' => esc_html__( 'Rating', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .star-rating span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label' => esc_html__( 'Empty Star Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .star-rating' => 'color: {{VALUE}}',
                ],
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
                    '{{WRAPPER}} ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_spacing',
            [
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_price_style',
            [
                'label' => esc_html__( 'Price', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .price' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price ins' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price ins .amount' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .price',
            ]
        );

        $this->add_control(
            'heading_old_price_style',
            [
                'label' => esc_html__( 'Regular Price', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'old_price_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .price del' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price del .amount' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'old_price_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .price del  ',
            ]
        );

        $this->add_responsive_control(
            'price_spacing',
            [
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_button_style',
            [
                'label' => esc_html__( 'Button', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_fz',
            [
                'label' => esc_html__( 'Font Size', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}. ul.products li.product .button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'button_border',
                'exclude' => [ 'color' ],
                'selector' => '{{WRAPPER}} ul.products li.product .button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_excerpt_style',
            [
                'label' => esc_html__( 'Excerpt', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .item--excerpt' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .item--excerpt',
            ]
        );


        $this->add_responsive_control(
            'excerpt_spacing',
            [
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .item--excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'heading_cat_style',
            [
                'label' => esc_html__( 'Category', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cat_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--category-link' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .product_item--category-link',
            ]
        );

        $this->add_responsive_control(
            'cat_spacing',
            [
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .product_item--category-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_design_box',
            [
                'label' => esc_html__( 'Box', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_border_width',
            [
                'label' => esc_html__( 'Border Width', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'box_margin',
            [
                'label' => esc_html__( 'Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->start_controls_tabs( 'box_style_tabs' );

        $this->start_controls_tab( 'classic_style_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product'),
            ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_color',
            [
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'classic_style_hover',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_hover',
                'selector' => apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover',
            ]
        );

        $this->add_control(
            'box_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    apply_filters('kitify/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_design_content',
            [
                'label' => esc_html__( 'Content Box', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Left', 'kitify'),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify'),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify'),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ul.products .product_item--info' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} ul.products .product-item__description--info' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'content_border_width',
            [
                'label' => esc_html__( 'Border Width', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} ul.products .product-item__description--info' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'content_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'border-radius: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} ul.products .product-item__description--info' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} ul.products .product-item__description--info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__( 'Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--kitify-p_item--c-mt:{{TOP}}{{UNIT}}; --kitify-p_item--c-mr:{{RIGHT}}{{UNIT}}; --kitify-p_item--c-mb:{{BOTTOM}}{{UNIT}}; --kitify-p_item--c-ml:{{LEFT}}{{UNIT}} ',
                    '{{WRAPPER}} ul.products .product_item--info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} ul.products .product-item__description--info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->start_controls_tabs( 'content_style_tabs' );

        $this->start_controls_tab( 'content_style_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow',
                'selector' => '{{WRAPPER}} ul.products .product_item--info',
                'selector' => '{{WRAPPER}} ul.products .product-item__description--info',
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ul.products .product-item__description--info' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_border_color',
            [
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--info' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} ul.products .product-item__description--info' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'content_style_hover',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow_hover',
                'selector' => '{{WRAPPER}} ul.products li.product:hover .product_item--info',
                'selector' => '{{WRAPPER}} ul.products li.product:hover .product-item__description--info',
            ]
        );

        $this->add_control(
            'content_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product:hover .product_item--info' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product:hover .product-item__description--info' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product:hover .product_item--info' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product:hover .product-item__description--info' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->register_section_style_pagination();

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .kitify-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .kitify-heading',
            ]
        );

        $this->add_responsive_control(
            'heading_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => esc_html__( 'Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'heading_border',
                'label' => esc_html__( 'Border', 'kitify' ),
                'selector' => '{{WRAPPER}} .kitify-heading',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'heading_text_shadow',
                'selector' => '{{WRAPPER}} .kitify-heading',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_product_stock_style',
            [
                'label' => esc_html__( 'Stock Progress Bar Style', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_stock_progress_bar!' => ''
                ]
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
            'label'      => esc_html__( 'Stock Progress Bar Order', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
            'condition' => [
                'enable_stock_progress_bar!' => ''
            ]
          )
        );

        $this->_add_control(
          'bar_info_order',
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

        $this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes', 'enable_carousel' => 'yes' ] );
    }

    protected function register_controls() {
      $this->kitify_register_controls();
    }

    protected function get_shortcode_object( $settings ) {
        if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
            return new Current_Query_Renderer( $settings, 'current_query' );
        }
        return new Products_Renderer( $settings, 'products' );
    }

    protected function render() {

        $settings = $this->get_settings();

        if ('current_query' === $this->get_settings(Products_Renderer::QUERY_CONTROL_NAME . '_post_type') && WC()->session && function_exists('wc_print_notices')) {
            wc_print_notices();
        }

        if(self::$__called_item == $this->get_id()){
            self::$__called_index++;
        }
        else{
            self::$__called_item = $this->get_id();
        }

        $unique_id = self::$__called_item . '_' . self::$__called_index;

        // For Products_Renderer.
        if ( ! isset( $GLOBALS['post'] ) ) {
            $GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        }

        $settings['unique_id'] = $unique_id;
        $settings['widget_id'] = self::$__called_item;

        $carousel_dot_html = '';
        $carousel_arrow_html = '';
        $carousel_scrollbar_html = '';
        $masonry_filter = '';
        $masonry_settings = '';

        if( filter_var($this->get_settings_for_display('enable_masonry'), FILTER_VALIDATE_BOOLEAN) ) {
            $masonry_settings = $this->get_masonry_options('li.product', '.kitify-products__list');
            $masonry_filter = $this->render_masonry_filters('.kitify_wc_widget_'.$unique_id.' .kitify-products__list', false);
        }
        elseif (filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN)) {
            if (filter_var($this->get_settings_for_display('carousel_dots'), FILTER_VALIDATE_BOOLEAN)) {
                $carousel_dot_html = '<div class="kitify-carousel__dots kitify-carousel__dots_'.$unique_id.' swiper-pagination"></div>';
            }
            if (filter_var($this->get_settings_for_display('carousel_arrows'), FILTER_VALIDATE_BOOLEAN)) {
                $carousel_arrow_html = sprintf('<div class="kitify-carousel__prev-arrow-%s kitify-arrow prev-arrow">%s</div>', $unique_id, $this->_render_icon('carousel_prev_arrow', '%s', '', false));
                $carousel_arrow_html .= sprintf('<div class="kitify-carousel__next-arrow-%s kitify-arrow next-arrow">%s</div>', $unique_id, $this->_render_icon('carousel_next_arrow', '%s', '', false));
            }
            if (filter_var($this->get_settings_for_display('carousel_scrollbar'), FILTER_VALIDATE_BOOLEAN)) {
                $carousel_scrollbar_html = '<div class="kitify-carousel__scrollbar swiper-scrollbar"></div>';
            }
        }

        $carousel_settings = $this->get_advanced_carousel_options('columns', $unique_id, $settings);

        $settings['kitify_extra_settings'] = [
            'carousel_settings' => $carousel_settings,
            'masonry_settings'  => $masonry_settings,
            'masonry_filter'  => $masonry_filter,
            'carousel_dot_html' => $carousel_dot_html,
            'carousel_arrow_html' => $carousel_arrow_html,
            'carousel_scrollbar_html' => $carousel_scrollbar_html,
        ];


        $shortcode = $this->get_shortcode_object( $settings );

        do_action('kitify/products/before_render', $settings);

        $content = $shortcode->get_content();

        $nothing_found_message = $this->get_settings_for_display( 'nothing_found_message' );

        if ( $content ) {
            echo $content;
        }
        elseif ( !empty($nothing_found_message) ) {
            echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $nothing_found_message ) . '</div>';
        }

        do_action('kitify/products/after_render', $settings);
    }

    public function render_plain_content() {}
}
