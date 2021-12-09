<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
/**
 * Fixed `Filterable Gallery` title margin issue or Essential Addons
 */
add_action('elementor/element/eael-filterable-gallery/eael_section_fg_item_content_style_settings/before_section_end', function ( $element ){
    $element->add_responsive_control(
        'eael_fg_item_content_title_margin',
        [
            'label' => esc_html__('Title Margin', 'kitify'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-card .fg-item-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ],
        ]
    );
}, 10 );

/**
 * Fixed `Filterable Gallery` control filter margin issue for Essential Addons
 */
add_action('elementor/element/eael-filterable-gallery/eael_section_fg_control_style_settings/before_section_end', function ( $element ){
    $element->add_responsive_control(
        'eael_fg_control_ul_margin',
        [
            'label' => esc_html__('Filters Margin', 'kitify'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .eael-filter-gallery-control ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ],
        ]
    );
    $element->add_responsive_control(
        'eael_fg_control_ul_alignment',
        [
            'label' => __('Alignment', 'kitify'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'kitify'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'kitify'),
                    'icon' => 'eicon-h-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'kitify'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .eael-filter-gallery-control ul' => 'text-align: {{VALUE}}; width: 100%;',
            ],
        ]
    );
}, 10 );

/**
 * Fixed `Testimonial` control wrap content issue for ElementsKit Lite
 */
add_action('elementor/element/elementskit-testimonial/ekit_testimonial_section_wraper_style/before_section_end', function ( $element ){
    $element->update_responsive_control(
        'ekit_testimonial_section_wraper_padding',
        [
            'selectors' => [
                '{{WRAPPER}} .ekit-wid-con .elementskit-commentor-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $element->add_responsive_control(
        'ekit_testimonial_section_wraper_margin',
        [
            'label' => esc_html__( 'Margin', 'kitify' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .elementskit-commentor-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $element->add_responsive_control(
        'ekit_testimonial_section_wraper_radius',
        [
            'label' => esc_html__( 'Border Radius', 'kitify' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .elementskit-commentor-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $element->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
            'name' => 'ekit_testimonial_section_wraper_background',
            'label' => esc_html__( 'Background', 'kitify' ),
            'types' => [ 'classic', 'gradient' ],
            'selector' => '{{WRAPPER}} .elementskit-commentor-content',
        ]
    );
    $element->add_group_control(
        \Elementor\Group_Control_Box_Shadow::get_type(),
        [
            'name' => 'ekit_testimonial_section_wraper_shadow',
            'label' => esc_html__( 'Box Shadow', 'kitify' ),
            'selector' => '{{WRAPPER}} .elementskit-commentor-content'
        ]
    );
}, 10);

/**
 * Added `Featured Image as background` for `Blog Posts` widget of ElementsKit Lite
 */
add_action('elementor/element/elementskit-blog-posts/ekit_blog_posts_feature_img_style/before_section_end', function ( $element ){
    $element->add_control(
        'ekit_featured_image_as_bg',
        [
            'label'     => esc_html__( 'Make as background', 'kitify' ),
            'type'      => \Elementor\Controls_Manager::SWITCHER,
            'label_on'  => esc_html__( 'Yes', 'kitify' ),
            'label_off' => esc_html__( 'No', 'kitify' ),
            'default'   => 'no',
            'prefix_class'   => 'fi-as-bg-',
            'condition' => [
                'ekit_blog_posts_layout_style' => 'elementskit-post-image-card'
            ],
            'selectors' => [
                '{{WRAPPER}}.fi-as-bg-yes .elementskit-post-image-card' => 'overflow: hidden;position: relative;display: flex;align-items: flex-end;',
                '{{WRAPPER}}.fi-as-bg-yes .elementskit-entry-header' => 'position: absolute;top: 0;left: 0;bottom: 0;right: 0;z-index: 0;',
                '{{WRAPPER}}.fi-as-bg-yes .elementskit-entry-header .elementskit-entry-thumb' => 'height: 100%;',
                '{{WRAPPER}}.fi-as-bg-yes .elementskit-entry-header .elementskit-entry-thumb img' => 'height: 100%; object-fit:cover;opacity: 1;',
                '{{WRAPPER}}.fi-as-bg-yes .elementskit-post-body' => 'position: relative;',
                '{{WRAPPER}}.fi-as-bg-yes .elementskit-entry-header:after' => 'content: ""; position: absolute;top: 0;left: 0;bottom: 0;right: 0;',
            ],
        ]
    );
    $element->add_responsive_control(
        'ekit_featured_image_height',
        [
            'label'     => esc_html__( 'Box Height', 'kitify' ),
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'condition' => [
                'ekit_blog_posts_layout_style' => 'elementskit-post-image-card',
                'ekit_featured_image_as_bg' => 'yes',
            ],
            'size_units' => [ 'px', 'em', 'vh', 'vw' ],
            'selectors' => [
                '{{WRAPPER}} .elementskit-post-image-card' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $element->add_control(
        'ekit_featured_image_fallback_bg',
        [
            'label' => esc_html__( 'Fallback Background Color',  'kitify' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .elementskit-entry-header' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'ekit_blog_posts_layout_style' => 'elementskit-post-image-card',
                'ekit_featured_image_as_bg' => 'yes',
            ],
        ]
    );
    $element->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
            'name'     => 'ekit_featured_image_overlay',
            'label'    => esc_html__( 'Background Overlay', 'kitify' ),
            'types'    => [ 'classic', 'gradient' ],
            'selector' => '{{WRAPPER}} .elementskit-entry-header:after',
            'condition' => [
                'ekit_blog_posts_layout_style' => 'elementskit-post-image-card',
                'ekit_featured_image_as_bg' => 'yes',
            ],
            'fields_options' => [
                'background' => [
                    'label' => esc_html__( 'Background Overlay', 'kitify' ),
                ]
            ]
        ]
    );
}, 10);