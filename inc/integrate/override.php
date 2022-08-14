<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/** Add Shortcode **/
if(!defined('ELEMENTOR_PRO_VERSION')) {
    if (is_admin()) {
        add_action('manage_' . \Elementor\TemplateLibrary\Source_Local::CPT . '_posts_columns', function ($defaults) {
            $defaults['shortcode'] = __('Shortcode', 'kitify');
            return $defaults;
        });
        add_action('manage_' . \Elementor\TemplateLibrary\Source_Local::CPT . '_posts_custom_column', function ( $column_name, $post_id) {
            if ( 'shortcode' === $column_name ) {
                // %s = shortcode, %d = post_id
                $shortcode = esc_attr( sprintf( '[%s id="%d"]', 'elementor-template', $post_id ) );
                printf( '<input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="%s" />', $shortcode );
            }
        }, 10, 2);
    }
    add_shortcode( 'elementor-template', function( $attributes = [] ){
        if ( empty( $attributes['id'] ) ) {
            return '';
        }
        $include_css = false;
        if ( isset( $attributes['css'] ) && 'false' !== $attributes['css'] ) {
            $include_css = (bool) $attributes['css'];
        }
        $template_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $attributes['id'], $include_css );
        return $template_content;
    } );

}

/**
 * Add `Border Radius` for `Toggle` widget of Elementor
 */
add_action('elementor/element/toggle/section_toggle_style/before_section_end', function ( $element ){
    $element->add_responsive_control(
        'tg_border_radius',
        [
            'label' => esc_html__( 'Border Radius', 'kitify' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .elementor-toggle .elementor-toggle-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
}, 10);

/**
 * Add `Icon Vertical Space` for `Toggle` widget of Elementor
 */
add_action('elementor/element/toggle/section_toggle_style_icon/before_section_end', function ( $element ){
    $element->add_responsive_control(
        'icon_v_space',
        [
            'label' => __( 'Vertical Spacing', 'kitify' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .elementor-toggle .elementor-toggle-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
}, 10);

/**
 * Add `Border Radius` for `Accordion` widget of Elementor
 */
add_action('elementor/element/accordion/section_title_style/before_section_end', function ( $element ){
    $element->add_responsive_control(
        'ac_space_between',
        [
            'label' => __( 'Space Between', 'kitify' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-accordion .elementor-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
            ],
        ]
    );
    $element->add_responsive_control(
        'ac_border_radius',
        [
            'label' => esc_html__( 'Border Radius', 'kitify' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .elementor-accordion .elementor-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $element->add_group_control(
        \Elementor\Group_Control_Box_Shadow::get_type(),
        [
            'name' => 'ac_box_shadow',
            'selector' => '{{WRAPPER}} .elementor-accordion .elementor-accordion-item',
        ]
    );
}, 10);
add_action('elementor/element/accordion/section_toggle_style_title/before_section_end', function ( $element ){
	$element->add_control(
		'active_title_background',
		[
			'label' => __( 'Active Background', 'kitify' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .elementor-active.elementor-tab-title' => 'background-color: {{VALUE}};',
			],
		],
		[
			'index' => 18
		]
	);
});

/**
 * Add `Icon Vertical Space` for `Accordion` widget of Elementor
 */
add_action('elementor/element/accordion/section_toggle_style_icon/before_section_end', function ( $element ){
    $element->add_responsive_control(
        'icon_v_space',
        [
            'label' => __( 'Vertical Spacing', 'kitify' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .elementor-accordion .elementor-accordion-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
}, 10);

/**
 * Add `Close All` for `Accordion` widget of Elementor
 */
add_action('elementor/element/accordion/section_title/before_section_end', function ( $element ){
    $element->add_control(
        'close_all',
        [
            'label' => __( 'Close All ?', 'kitify' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
	        'return_value' => 'accordion-close-all',
	        'prefix_class' => '',
            'separator' => 'before',
        ]
    );
}, 10);

/**
 * Modify Divider - Weight control
 */
add_action('elementor/element/divider/section_divider_style/before_section_end', function( $element ){
    $element->remove_control('weight');
    $element->add_responsive_control(
        'weight',
        [
            'label' => __( 'Weight', 'kitify' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'render_type' => 'template',
            'selectors' => [
                '{{WRAPPER}}' => '--divider-border-width: {{SIZE}}{{UNIT}}'
            ]
        ]
    );
}, 10 );

/**
 * Modify Icon List - Text Indent control
 */

add_action('elementor/element/icon-list/section_text_style/before_section_end', function( $element ){
    $element->remove_control('text_indent');
    $element->update_control('icon_color', [
        'selectors' => [
            '{{WRAPPER}} .elementor-icon-list-icon i' => 'color: {{VALUE}};',
            '{{WRAPPER}} .elementor-icon-list-icon svg' => 'fill: {{VALUE}};color: {{VALUE}};',
        ]
    ]);
    $element->add_responsive_control(
        'text_indent',
        [
            'label' => __( 'Text Indent', 'elementor' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
}, 10 );

add_action('elementor/element/icon-list/section_icon_list/before_section_end', function( $element ){
	$element->update_control('divider_height', [
		'selectors' => [
			'{{WRAPPER}}' => '--divider-height: {{SIZE}}{{UNIT}}',
			'{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
		]
	]);
});

/**
 * Modify Counter - Visible control
 */
add_action('elementor/element/counter/section_number/before_section_end', function( $element ){
    $element->add_control(
        'hide_prefix',
        array(
            'label'        => esc_html__( 'Hide Prefix', 'kitify' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'kitify' ),
            'label_off'    => esc_html__( 'No', 'kitify' ),
            'return_value' => 'yes',
            'selectors' => [
                '{{WRAPPER}} .elementor-counter-number-prefix' => 'display: none',
            ],
        )
    );
    $element->add_control(
        'hide_suffix',
        array(
            'label'        => esc_html__( 'Hide Suffix', 'kitify' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'kitify' ),
            'label_off'    => esc_html__( 'No', 'kitify' ),
            'return_value' => 'yes',
            'selectors' => [
                '{{WRAPPER}} .elementor-counter-number-suffix' => 'display: none',
            ],
        )
    );
    $element->add_responsive_control(
        'number_spacing',
        [
            'label' => __( 'Spacing', 'elementor' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .elementor-counter-number-wrapper' => 'padding-bottom: {{SIZE}}{{UNIT}}',
            ],
        ]
    );
}, 10 );

/**
 * Modify Counter - Align control
 */
add_action('elementor/element/counter/section_title/before_section_end', function( $element ){
    $element->add_responsive_control(
        'text_alignment',
        array(
            'label'   => esc_html__( 'Text Alignment', 'kitify' ),
            'type'    => \Elementor\Controls_Manager::CHOOSE,
            'options' => array(
                'left'    => array(
                    'title' => esc_html__( 'Left', 'kitify' ),
                    'icon'  => 'eicon-text-align-left',
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'kitify' ),
                    'icon'  => 'eicon-text-align-center',
                ),
                'right' => array(
                    'title' => esc_html__( 'Right', 'kitify' ),
                    'icon'  => 'eicon-text-align-right',
                ),
            ),
            'selectors'  => array(
                '{{WRAPPER}} .elementor-counter-title' => 'text-align: {{VALUE}};',
            )
        )
    );
}, 10 );


/**
 * Modify Icon - Padding & shadow
 */
add_action('elementor/element/icon/section_style_icon/before_section_end', function( $element ){
    $element->remove_control('icon_padding');
    $element->add_responsive_control(
        'icon_padding',
        [
            'label' => __( 'Padding', 'elementor' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
            ],
            'range' => [
                'em' => [
                    'min' => 0,
                    'max' => 10,
                ],
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'size_units' => [ 'em', 'px' ],
            'condition' => [
                'view!' => 'default',
            ],
        ]
    );
    $element->add_group_control(
        Elementor\Group_Control_Box_Shadow::get_type(),
        [
            'name'     => 'i_shadow',
            'selector' => '{{WRAPPER}} .elementor-icon',
            'condition' => [
                'view!' => 'default',
            ]
        ]
    );
}, 10 );

/**
 * Modify Spacer
 */
add_action('elementor/element/spacer/section_spacer/before_section_end', function( $element ){

    $element->add_control(
        'full_height',
        [
	        'label'        => esc_html__( '100% Height', 'kitify' ),
	        'type'         => \Elementor\Controls_Manager::SWITCHER,
	        'label_on'     => esc_html__( 'Yes', 'kitify' ),
	        'label_off'    => esc_html__( 'No', 'kitify' ),
	        'return_value' => 'yes',
	        'selectors' => [
	        	'{{WRAPPER}}' => 'display: flex;height: 100%',
	        	'{{WRAPPER}} .elementor-widget-container' => 'width: 100%'
	        ],
        ]
    );

}, 10 );

/**
 * Modify Heading - Color Hover
 */
add_action('elementor/element/heading/section_title_style/before_section_end', function( $element ){
	$element->add_control(
		'title_hover_color',
		[
			'label' => __( 'Text Hover Color', 'kitify' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .elementor-heading-title:hover' => 'color: {{VALUE}};',
			],
		]
	);
}, 10 );

/**
 * Modify Image Box - Color Hover
 */
add_action('elementor/element/image-box/section_style_content/before_section_end', function ( $element ){
	$element->add_control(
		'title_hover_color',
		[
			'label' => __( 'Hover Color', 'kitify' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .elementor-image-box-wrapper:hover .elementor-image-box-title' => 'color: {{VALUE}};',
			],
		],
		[
			'index' => 39
		]
	);
	$element->add_control(
		'description_hover_color',
		[
			'label' => __( 'Hover Color', 'kitify' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .elementor-image-box-wrapper:hover .elementor-image-box-description' => 'color: {{VALUE}};',
			],
		],
		[
			'index' => 53
		]
	);
});
