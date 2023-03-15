<?php
namespace KitifyThemeBuilder\Modules\NestedElements\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use KitifyThemeBuilder\Modules\NestedElements\Base\Widget_Nested_Base;
use KitifyThemeBuilder\Modules\NestedElements\Controls\Control_Nested_Repeater;

class NestedTabs extends Widget_Nested_Base {

    protected function enqueue_addon_resources() {
        if ( ! kitify_settings()->is_combine_js_css() ) {
            wp_register_script( $this->get_name(), kitify()->plugin_url( 'assets/js/addons/n-tabs.js' ), [ 'kitify-base' ], kitify()->get_version(), true );
            $this->add_script_depends( $this->get_name() );
            if ( ! kitify()->is_optimized_css_mode() ) {
                wp_register_style( $this->get_name(), kitify()->plugin_url( 'assets/css/addons/n-tabs.min.css' ), [ 'kitify-base' ], kitify()->get_version() );
                $this->add_style_depends( $this->get_name() );
            }
        }
    }

    public function get_widget_css_config( $widget_name ) {
        $file_url  = kitify()->plugin_url( 'assets/css/addons/n-tabs.min.css' );
        $file_path = kitify()->plugin_path( 'assets/css/addons/n-tabs.min.css' );

        return [
            'key'       => $widget_name,
            'version'   => kitify()->get_version( true ),
            'file_path' => $file_path,
            'data'      => [
                'file_url' => $file_url
            ]
        ];
    }

	public function get_name() {
		return 'kitify-nested-tabs';
	}

	public function get_widget_title() {
		return esc_html__( 'Nested Tabs', 'kitify' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_keywords() {
		return [ 'nested', 'tabs', 'accordion', 'toggle' ];
	}

	protected function get_default_children_elements() {
		return [
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Tab #1', 'kitify' ),
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Tab #2', 'kitify' ),
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Tab #3', 'kitify' ),
				],
			],
		];
	}

	protected function get_default_repeater_title_setting_key() {
		return 'tab_title';
	}

	protected function get_default_children_title() {
		return esc_html__( 'Tab #%d', 'kitify' );
	}

	protected function get_default_children_placeholder_selector() {
		return '.kitify-ntabs-content';
	}

	protected function register_controls() {
		$start = is_rtl() ? 'right' : 'left';
		$end = is_rtl() ? 'left' : 'right';
		$tooltip_start = is_rtl() ? esc_html__( 'Right', 'kitify' ) : esc_html__( 'Left', 'kitify' );
		$tooltip_end = is_rtl() ? esc_html__( 'Left', 'kitify' ) : esc_html__( 'Right', 'kitify' );

        $nested_tabs_heading_selector_class = '{{WRAPPER}} .kitify-ntabs-{{ID}} > .kitify-ntabs-heading';
        $nested_tabs_content_selector_class = '{{WRAPPER}} .kitify-ntabs-{{ID}} > .kitify-ntabs-content';

		$this->start_controls_section( 'section_tabs', [
			'label' => esc_html__( 'Tabs', 'kitify' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'tab_title', [
			'label' => esc_html__( 'Title', 'kitify' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Tab Title', 'kitify' ),
			'placeholder' => esc_html__( 'Tab Title', 'kitify' ),
			'label_block' => true,
			'dynamic' => [
				'active' => true,
			],
		] );

        $repeater->add_control( 'tab_subtitle', [
			'label' => esc_html__( 'Sub Title', 'kitify' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
			'dynamic' => [
				'active' => true,
			],
		] );

        $repeater->add_control(
            'use_image',
            array(
                'label'        => esc_html__( 'Use Image?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $repeater->add_control(
            'tab_image',
            array(
                'label'      => esc_html__( 'Image', 'kitify' ),
                'type'       => Controls_Manager::MEDIA,
                'condition' => [
                    'use_image' => 'yes'
                ]
            )
        );

		$repeater->add_control(
			'tab_icon',
			[
				'label' => esc_html__( 'Icon', 'kitify' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
                'condition' => [
                    'use_image!' => 'yes'
                ]
			]
		);

		$repeater->add_control(
			'tab_icon_active',
			[
				'label' => esc_html__( 'Active Icon', 'kitify' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
                    'use_image!' => 'yes',
					'tab_icon[value]!' => '',
				],
			]
		);

		$repeater->add_control(
			'element_id',
			[
				'label' => esc_html__( 'CSS ID', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'kitify' ),
				'style_transfer' => false,
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$this->add_control( 'tabs', [
			'label' => esc_html__( 'Tabs Items', 'kitify' ),
			'type' => Control_Nested_Repeater::CONTROL_TYPE,
			'fields' => $repeater->get_controls(),
			'default' => [
				[
					'tab_title' => esc_html__( 'Tab #1', 'kitify' ),
				],
				[
					'tab_title' => esc_html__( 'Tab #2', 'kitify' ),
				],
				[
					'tab_title' => esc_html__( 'Tab #3', 'kitify' ),
				],
			],
			'title_field' => '{{{ tab_title }}}',
			'button_text' => 'Add Tab',
		] );

		$this->add_responsive_control( 'tabs_direction', [
			'label' => esc_html__( 'Direction', 'kitify' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'top' => [
					'title' => esc_html__( 'Top', 'kitify' ),
					'icon' => 'eicon-v-align-top',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'kitify' ),
					'icon' => 'eicon-v-align-bottom',
				],
				'end' => [
					'title' => $tooltip_end,
					'icon' => 'eicon-h-align-' . $end,
				],
				'start' => [
					'title' => $tooltip_start,
					'icon' => 'eicon-h-align-' . $start,
				],
			],
			'separator' => 'before',
			'selectors_dictionary' => [
				'top' => '--n-tabs-direction: column; --n-tabs-heading-direction: row; --n-tabs-heading-width: initial;',
				'bottom' => '--n-tabs-direction: column-reverse; --n-tabs-heading-direction: row; --n-tabs-heading-width: initial;',
				'end' => '--n-tabs-direction: row-reverse; --n-tabs-heading-direction: column; --n-tabs-heading-width: 240px;',
				'start' => '--n-tabs-direction: row; --n-tabs-heading-direction: column; --n-tabs-heading-width: 240px;',
			],
			'selectors' => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'tabs_justify_horizontal', [
			'label' => esc_html__( 'Justify', 'kitify' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'start' => [
					'title' => esc_html__( 'Start', 'kitify' ),
					'icon' => 'eicon-flex eicon-align-start-h',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'kitify' ),
					'icon' => 'eicon-h-align-center',
				],
				'end' => [
					'title' => esc_html__( 'End', 'kitify' ),
					'icon' => 'eicon-flex eicon-align-end-h',
				],
				'stretch' => [
					'title' => esc_html__( 'Justified', 'kitify' ),
					'icon' => 'eicon-h-align-stretch',
				],
			],
			'selectors_dictionary' => [
				'start' => '--n-tabs-heading-justify-content: flex-start; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: center;',
				'center' => '--n-tabs-heading-justify-content: center; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: center;',
				'end' => '--n-tabs-heading-justify-content: flex-end; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: center',
				'stretch' => '--n-tabs-heading-justify-content: initial; --n-tabs-title-width: 100%; --n-tabs-title-height: initial; --n-tabs-title-align-items: center;',
			],
			'selectors' => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
			'condition' => [
				'tabs_direction' => [
					'',
					'top',
					'bottom',
				],
			],
		] );

		$this->add_responsive_control( 'tabs_justify_vertical', [
			'label' => esc_html__( 'Justify', 'kitify' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'start' => [
					'title' => esc_html__( 'Start', 'kitify' ),
					'icon' => 'eicon-flex eicon-align-start-v',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'kitify' ),
					'icon' => 'eicon-v-align-middle',
				],
				'end' => [
					'title' => esc_html__( 'End', 'kitify' ),
					'icon' => 'eicon-flex eicon-align-end-v',
				],
				'stretch' => [
					'title' => esc_html__( 'Justified', 'kitify' ),
					'icon' => 'eicon-v-align-stretch',
				],
			],
			'selectors_dictionary' => [
				'start' => '--n-tabs-heading-justify-content: flex-start; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: initial;',
				'center' => '--n-tabs-heading-justify-content: center; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: initial;',
				'end' => '--n-tabs-heading-justify-content: flex-end; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: initial;',
				'stretch' => '--n-tabs-heading-justify-content: flex-start; --n-tabs-title-width: initial; --n-tabs-title-height: 100%; --n-tabs-title-align-items: center;',
			],
			'selectors' => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
			'condition' => [
				'tabs_direction' => [
					'start',
					'end',
				],
			],
		] );

		$this->add_responsive_control( 'tabs_width', [
			'label' => esc_html__( 'Width', 'kitify' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'%' => [
					'min' => 10,
					'max' => 50,
				],
				'px' => [
					'min' => 20,
					'max' => 600,
				],
			],
			'default' => [
				'unit' => '%',
			],
			'size_units' => [ '%', 'px' ],
			'selectors' => [
				'{{WRAPPER}}' => '--n-tabs-heading-width: {{SIZE}}{{UNIT}}',
			],
			'condition' => [
				'tabs_direction' => [
					'start',
					'end',
				],
			],
		] );

		$this->add_responsive_control( 'title_alignment', [
			'label' => esc_html__( 'Align Title', 'kitify' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'start' => [
					'title' => esc_html__( 'Left', 'kitify' ),
					'icon' => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'kitify' ),
					'icon' => 'eicon-text-align-center',
				],
				'end' => [
					'title' => esc_html__( 'Right', 'kitify' ),
					'icon' => 'eicon-text-align-right',
				],
			],
			'selectors_dictionary' => [
				'start' => '--n-tabs-title-justify-content: flex-start; --n-tabs-title-align-items: flex-start;',
				'center' => '--n-tabs-title-justify-content: center; --n-tabs-title-align-items: center;',
				'end' => '--n-tabs-title-justify-content: flex-end; --n-tabs-title-align-items: flex-end;',
			],
			'selectors' => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
		] );

        $this->add_control(
            'tab_effect',
            array(
                'label'   => esc_html__( 'Tab Effect', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'move-up',
                'options' => array(
                    'none'             => esc_html__( 'None', 'kitify' ),
                    'fade'             => esc_html__( 'Fade', 'kitify' ),
                    'zoom-in'          => esc_html__( 'Zoom In', 'kitify' ),
                    'zoom-out'         => esc_html__( 'Zoom Out', 'kitify' ),
                    'move-up'          => esc_html__( 'Move Up', 'kitify' ),
                    'fall-perspective' => esc_html__( 'Fall Perspective', 'kitify' ),
                ),
                'prefix_class' => 'kitify-ntabs-effect--',
            )
        );
        $this->add_control(
            'sticky_tab_control',
            array(
                'label'        => esc_html__( 'Sticky tab controls ?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'no', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );
        $this->add_control(
            'sticky_breakpoint',
            [
                'label' => esc_html__( 'Breakpoint', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__( 'Note: this option will not work if Direction is Left or Right.', 'kitify' ),
                'options' => [
                        'none'  => esc_html__( 'None', 'kitify' ),
                        'all'   => esc_html__( 'All', 'kitify' ),
                    ] + kitify_helper()->get_active_breakpoints(false, true),
                'default' => 'none',
                'frontend_available' => true,
                'condition' => [
                    'sticky_tab_control' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'tab_as_selectbox',
            array(
                'label'        => esc_html__( 'Tabs as SelectBox', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'no', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
                'frontend_available' => true
            )
        );
        $this->add_control(
            'tab_text_intro',
            array(
                'label'     => esc_html__( 'Intro Text', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => [
                    'active' => true,
                ],
                'condition' => [
                    'tab_as_selectbox' => 'yes'
                ]
            )
        );

        $this->add_control(
            'breakpoint_selector',
            [
                'label' => esc_html__( 'Responsive Settings', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__( 'Note: Choose at which breakpoint tabs will automatically switch to a SelectBox layout.', 'kitify' ),
                'options' => [
                        'none' => esc_html__( 'None', 'kitify' )
                    ] + kitify_helper()->get_active_breakpoints(false, true),
                'default' => 'mobile',
                'frontend_available' => true,
                'condition' => [
                    'tab_as_selectbox!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'selectbox_icon',
            array(
                'label'       => esc_html__( 'SelectBox Icon', 'kitify' ),
                'label_block' => false,
                'type'        => Controls_Manager::ICONS,
                'skin'        => 'inline',
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value'   => 'lastudioicon-arrow-down',
                    'library' => 'lastudioicon',
                ),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        ['name' => 'tab_as_selectbox', 'operator' => '===', 'value' => 'yes'],
                        ['name' => 'breakpoint_selector', 'operator' => '!=', 'value' => 'none'],
                    ],
                ],
            )
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_selectbox_style', [
            'label' => esc_html__( 'SelectBox', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'tab_as_selectbox' => 'yes'
            ]
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'introtext_typography',
            'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox--intro",
            'label' => esc_html__( 'Intro Text Typography', 'kitify' )
        ] );

        $this->add_responsive_control( 'selectbox_space_between', [
            'label' => esc_html__( 'Gap between', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                "$nested_tabs_heading_selector_class .ntabs-selectbox" => 'gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control( 'selectbox_width', [
            'label' => esc_html__( 'Selectbox Width', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors' => [
                "$nested_tabs_heading_selector_class .ntabs-selectbox--wrap" => 'width: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_control(
            'selectbox_bgcolor',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--select" => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'selectbox_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--select" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'selectbox_border',
                'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox--wrap"
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'selectbox_shadow',
                'label' => esc_html__( 'Shadow', 'kitify' ),
                'separator' => 'after',
                'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox--select",
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_tabs_style', [
            'label' => esc_html__( 'Tabs', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tabs_background_color',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tabs_heading_selector_class}",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'kitify' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}}',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_margin',
            [
                'label' => esc_html__( 'Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_control_item_style', [
            'label' => esc_html__( 'Control Items', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'tabs_title_space_between', [
            'label' => esc_html__( 'Gap between tabs', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-title-gap: {{SIZE}}{{UNIT}}',
            ],
        ] );
        $this->add_responsive_control( 'tabs_title_spacing', [
            'label' => esc_html__( 'Distance from content', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->start_controls_tabs( 'tabs_title_style' );

        $this->start_controls_tab(
            'tabs_title_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tabs_title_background_color',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):not( :hover )",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'kitify' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}}',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_title_border',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):not( :hover )",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'kitify' ),
                    ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_title_box_shadow',
                'label' => esc_html__( 'Shadow', 'kitify' ),
                'separator' => 'after',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):not( :hover )",
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_title_hover',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tabs_title_background_color_hover',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):hover",
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'kitify' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}};',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_title_border_hover',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):hover",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'kitify' ),
                    ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_title_box_shadow_hover',
                'label' => esc_html__( 'Shadow', 'kitify' ),
                'separator' => 'after',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):hover",
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'kitify' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_control(
            'tabs_title_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration (s)', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-transition: {{SIZE}}s',
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_title_active',
            [
                'label' => esc_html__( 'Active', 'kitify' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tabs_title_background_color_active',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title.e-active",
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'kitify' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}};',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_title_border_active',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title.e-active",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'kitify' ),
                    ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_title_box_shadow_active',
                'label' => esc_html__( 'Shadow', 'kitify' ),
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title.e-active",
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tabs_title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_title_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-padding-top: {{TOP}}{{UNIT}}; --n-tabs-title-padding-right: {{RIGHT}}{{UNIT}}; --n-tabs-title-padding-bottom: {{BOTTOM}}{{UNIT}}; --n-tabs-title-padding-left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_title_style', [
            'label' => esc_html__( 'Titles', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title .ntabs--title",
            'label' => esc_html__( 'Title Typography', 'kitify' ),
            'fields_options' => [
                'font_size' => [
                    'selectors' => [
                        '{{WRAPPER}}' => '--n-tabs-title-font-size: {{SIZE}}{{UNIT}}',
                    ],
                ],
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'subtitle_typography',
            'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title .ntabs--subtitle",
            'label' => esc_html__( 'Subtitle Typography', 'kitify' ),
            'fields_options' => [
                'font_size' => [
                    'selectors' => [
                        '{{WRAPPER}}' => '--n-tabs-subtitle-font-size: {{SIZE}}{{UNIT}}',
                    ],
                ],
            ],
        ] );

        $this->start_controls_tabs( 'title_style' );

        $this->start_controls_tab(
            'title_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subtitle_text_color',
            [
                'label' => esc_html__( 'Subtitle Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-subtitle-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):not( :hover ) .ntabs--title",
                'fields_options' => [
                    'text_shadow_type' => [
                        'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):not( :hover ) .ntabs--title",
                'fields_options' => [
                    'text_stroke_type' => [
                        'label' => esc_html__( 'Stroke', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_hover',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control(
            'title_text_color_hover',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):hover" => '--n-tabs-title-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subtitle_text_color_hover',
            [
                'label' => esc_html__( 'Subtitle Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):hover" => '--n-tabs-subtitle-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow_hover',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):hover .ntabs--title",
                'fields_options' => [
                    'text_shadow_type' => [
                        'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'kitify' ),
                    ],
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke_hover',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title:not( .e-active ):hover .ntabs--title",
                'fields_options' => [
                    'text_stroke_type' => [
                        'label' => esc_html__( 'Stroke', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_active',
            [
                'label' => esc_html__( 'Active', 'kitify' ),
            ]
        );

        $this->add_control(
            'title_text_color_active',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-color-active: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subtitle_text_color_active',
            [
                'label' => esc_html__( 'Subtitle Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-subtitle-color-active: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow_active',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title.e-active .ntabs--title",
                'fields_options' => [
                    'text_shadow_type' => [
                        'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke_active',
                'selector' => "{$nested_tabs_heading_selector_class} .kitify-ntab-title.e-active .ntabs--title",
                'fields_options' => [
                    'text_stroke_type' => [
                        'label' => esc_html__( 'Stroke', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section( 'icon_section_style', [
            'label' => esc_html__( 'Icon', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'icon_position', [
            'label' => esc_html__( 'Position', 'kitify' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__( 'Top', 'kitify' ),
                    'icon' => 'eicon-v-align-top',
                ],
                'end' => [
                    'title' => $tooltip_end,
                    'icon' => 'eicon-h-align-' . $end,
                ],
                'bottom' => [
                    'title' => esc_html__( 'Bottom', 'kitify' ),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'start' => [
                    'title' => $tooltip_start,
                    'icon' => 'eicon-h-align-' . $start,
                ],
            ],
            'selectors_dictionary' => [
                // The toggle variables for 'align items' and 'justify content' have been added to separate the styling of the two 'flex direction' modes.
                'top' => '--n-tabs-title-direction: column; --n-tabs-icon-order: initial; --n-tabs-title-justify-content-toggle: center; --n-tabs-title-align-items-toggle: initial;',
                'end' => '--n-tabs-title-direction: row; --n-tabs-icon-order: 1; --n-tabs-title-justify-content-toggle: initial; --n-tabs-title-align-items-toggle: center;',
                'bottom' => '--n-tabs-title-direction: column; --n-tabs-icon-order: 1; --n-tabs-title-justify-content-toggle: center; --n-tabs-title-align-items-toggle: initial;',
                'start' => '--n-tabs-title-direction: row; --n-tabs-icon-order: initial; --n-tabs-title-justify-content-toggle: initial; --n-tabs-title-align-items-toggle: center;',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '{{VALUE}}',
            ],
        ] );

        $this->add_responsive_control( 'icon_size', [
            'label' => esc_html__( 'Size', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'unit' => 'px',
            ],
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-icon-size: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control( 'icon_spacing', [
            'label' => esc_html__( 'Spacing', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'unit' => 'px',
            ],
            'size_units' => [ 'px', 'vw' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-icon-gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->start_controls_tabs( 'icon_style_states' );

        $this->start_controls_tab(
            'icon_section_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control( 'icon_color', [
            'label' => esc_html__( 'Color', 'kitify' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-icon-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_section_hover',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control( 'icon_color_hover', [
            'label' => esc_html__( 'Color', 'kitify' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                "$nested_tabs_heading_selector_class .kitify-ntab-title:not( .e-active ):hover" => '--n-tabs-icon-color-hover: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_section_active',
            [
                'label' => esc_html__( 'Active', 'kitify' ),
            ]
        );

        $this->add_control( 'icon_color_active', [
            'label' => esc_html__( 'Color', 'kitify' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-icon-color-active: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section( 'section_box_style', [
            'label' => esc_html__( 'Content', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_background_color',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tabs_content_selector_class}",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => "{$nested_tabs_content_selector_class}",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'kitify' ),
                    ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'kitify' ),
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-content-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_box_shadow',
                'selector' => "{$nested_tabs_content_selector_class}",
                'condition' => [
                    'box_height!' => 'height',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_divider_style', [
            'label' => esc_html__( 'Divider', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE
        ] );

        $this->add_control(
            'enable_divider',
            array(
                'label'        => esc_html__( 'Enable Divider', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_responsive_control( 'divider_position', [
            'label' => esc_html__( 'Position', 'kitify' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__( 'Top', 'kitify' ),
                    'icon' => 'eicon-v-align-top',
                ],
                'end' => [
                    'title' => $tooltip_end,
                    'icon' => 'eicon-h-align-' . $end,
                ],
                'bottom' => [
                    'title' => esc_html__( 'Bottom', 'kitify' ),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'start' => [
                    'title' => $tooltip_start,
                    'icon' => 'eicon-h-align-' . $start,
                ],
            ],
            'selectors_dictionary' => [
                'top' => '--n-tabs-divider-pos-left:50%;--n-tabs-divider-pos-right:initial;--n-tabs-divider-pos-top:0;--n-tabs-divider-pos-bottom:initial;--n-tabs-divider-transform:translateX(-50%)',
                'end' => '--n-tabs-divider-pos-left:initial;--n-tabs-divider-pos-right:0;--n-tabs-divider-pos-top:50%;--n-tabs-divider-pos-bottom:initial;--n-tabs-divider-transform:translateY(-50%);--n-tabs-divider-last:0',
                'bottom' => '--n-tabs-divider-pos-left:50%;--n-tabs-divider-pos-right:initial;--n-tabs-divider-pos-top:initial;--n-tabs-divider-pos-bottom:0;--n-tabs-divider-transform:translateX(-50%);--n-tabs-divider-last:initial',
                'start' => '--n-tabs-divider-pos-left:0;--n-tabs-divider-pos-right:initial;--n-tabs-divider-pos-top:50%;--n-tabs-divider-pos-bottom:initial;--n-tabs-divider-transform:translateY(-50%)',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '{{VALUE}}',
            ],
            'condition' => [
                'enable_divider' => 'yes'
            ]
        ] );

        $this->start_controls_tabs( 'tabs_divider_style', [
            'condition' => [
                'enable_divider' => 'yes'
            ]
        ] );
        $this->start_controls_tab(
            'tabs_divider_normal',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );
        $this->add_responsive_control( 'divider_height', [
            'label' => esc_html__( 'Divider Height', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-divider-height: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control( 'divider_width', [
            'label' => esc_html__( 'Divider Width', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-divider-width: {{SIZE}}{{UNIT}}',
            ],
        ] );
        $this->add_control(
            'divider_color',
            [
                'label' => esc_html__( 'Divider Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-divider-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_divider_active',
            [
                'label' => esc_html__( 'Active', 'kitify' ),
            ]
        );
        $this->add_responsive_control( 'divider_height_active', [
            'label' => esc_html__( 'Divider Height', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-divider-active-height: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control( 'divider_width_active', [
            'label' => esc_html__( 'Divider Width', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-divider-active-width: {{SIZE}}{{UNIT}}',
            ],
        ] );
        $this->add_control(
            'divider_color_active',
            [
                'label' => esc_html__( 'Divider Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-divider-active-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section( 'section_selectbox_icon_style', [
            'label' => esc_html__( 'SelectBox Icon', 'kitify' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    ['name' => 'tab_as_selectbox', 'operator' => '===', 'value' => 'yes'],
                    ['name' => 'breakpoint_selector', 'operator' => '!=', 'value' => 'none'],
                ],
            ],
        ] );

        $this->add_control(
            'selectbox_icon_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs--selectboxicon" => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control( 'selectbox_icon_size', [
            'label' => esc_html__( 'Icon Size', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                "$nested_tabs_heading_selector_class .ntabs--selectboxicon" => 'font-size: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control(
            'selectbox_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs--selectboxicon" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$tabs = $settings['tabs'];

		$id_int = substr( $this->get_id_int(), 0, 3 );

		$this->add_render_attribute( 'elementor-tabs', 'class', ['kitify-ntabs', 'kitify-ntabs-' . $this->get_id()] );
		$this->add_render_attribute( 'tab-title-text', 'class', 'kitify-ntab-title-text' );
		$this->add_render_attribute( 'tab-icon', 'class', 'kitify-ntab-icon' );
		$this->add_render_attribute( 'tab-icon-active', 'class', [ 'kitify-ntab-icon', 'e-active' ] );

		$tabs_title_html = '';
		$mobile_tabs_title_html = '';
        $first_item = '';
        $animationClass = $settings['hover_animation']  ? 'elementor-animation-'. $settings['hover_animation'] : '';

		foreach ( $tabs as $index => $item ) {
			// Tabs title.
			$tab_count = $index + 1;
			$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );
			$tab_title = sprintf('<div class="ntabs--title">%1$s</div>', $item['tab_title']);
            if(!empty($item['tab_subtitle'])){
                $tab_title = $tab_title . sprintf('<div class="ntabs--subtitle">%1$s</div>', $item['tab_subtitle']);
            }

			$tab_id = empty( $item['element_id'] ) ? 'kitify-ntabs-title-' . $id_int . $tab_count : $item['element_id'];

			$this->add_render_attribute( $tab_title_setting_key, [
				'id' => $tab_id,
				'class' => [ 'kitify-ntab-title', 'e-normal', $animationClass ],
                'data-tabindex' => $tab_count,
			] );

			$title_render_attributes = $this->get_render_attribute_string( $tab_title_setting_key );
			$tab_title_class = $this->get_render_attribute_string( 'tab-title-text' );
			$tab_icon_class = $this->get_render_attribute_string( 'tab-icon' );

            $icon_html = self::try_get_icon_html( $item['tab_icon'], [ 'aria-hidden' => 'true' ] );
            $icon_active_html = $icon_html;
            if ( $this->is_active_icon_exist( $item ) ) {
                $icon_active_html = self::try_get_icon_html( $item['tab_icon_active'], [ 'aria-hidden' => 'true' ] );
            }

            if ( $item['use_image'] === 'yes' &&  ! empty( $item['tab_image']['url'] ) ) {
                $icon_html = sprintf( '<img src="%1$s" alt="" width="16" height="16"/>', apply_filters( 'lastudio_wp_get_attachment_image_url', $item['tab_image']['url'] ) );
                $icon_active_html = '';
            }

			$tabs_title_html .= "<div {$title_render_attributes}>";
            if(!empty($icon_html) || !empty($icon_active_html)){
                $tabs_title_html .= "\t<div {$tab_icon_class}>{$icon_html}{$icon_active_html}</div>";
            }
			$tabs_title_html .= "\t<div {$tab_title_class}>{$tab_title}</div>";
			$tabs_title_html .= '</div>';

            if($index === 0){
                $first_item =  str_replace('id="'.$tab_id.'"', '', $tabs_title_html);
                $first_item =  str_replace('kitify-ntab-title ', 'kitify-ntab-title clone--item ', $first_item);
                if(!empty($animationClass)){
                    $first_item =  str_replace($animationClass, '', $first_item);
                }
            }

			// Tabs content.
			ob_start();
			$this->print_child( $index );
			$tab_content = ob_get_clean();

			$mobile_tabs_title_html .= $tab_content;
		}

        $selectbox_icon = self::try_get_icon_html( $settings['selectbox_icon'], [ 'aria-hidden' => 'true' ] );
        $dd_icon = '';
        if($selectbox_icon){
            $dd_icon = sprintf('<span class="ntabs--selectboxicon">%1$s</span>', $selectbox_icon);
        }
		?>
		<div <?php $this->print_render_attribute_string( 'elementor-tabs' ); ?>>
			<div class="kitify-ntabs-heading">
				<?php
                if( $settings['tab_as_selectbox'] === 'yes' ) {
                    $first_item .= $dd_icon;
                    $intro_text = !empty( $settings['tab_text_intro'] ) ? sprintf('<div class="ntabs-selectbox--intro">%1$s</div>', $settings['tab_text_intro']) : '';
                    echo sprintf(
                        '<div class="ntabs-selectbox">%1$s<div class="ntabs-selectbox--wrap"><div class="ntabs-selectbox--label">%2$s</div><div class="ntabs-selectbox--select">%3$s</div></div></div>',
                        $intro_text,
                        $first_item,
                        $tabs_title_html
                    );
                }
                else{
                    $tabs_title_html .= $dd_icon;
                    echo $tabs_title_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
			</div>
			<div class="kitify-ntabs-content">
				<?php echo $mobile_tabs_title_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<div class="kitify-ntabs kitify-ntabs-{{{view.getID()}}}">
			<# if ( settings['tabs'] ) {

			var elementUid = view.getIDInt().toString().substr( 0, 3 );
            var _tabControlHTML = '',
                firstItem = '',
                selectboxIcon = elementor.helpers.renderIcon( view, settings.selectbox_icon, { 'aria-hidden': true }, 'i' , 'object' ),
                selectboxIconHTML = '';
            if(selectboxIcon.value){
                selectboxIconHTML = `<span class="ntabs--selectboxicon">${selectboxIcon.value}</span>`;
            }

            var hoverAnimationClass = settings['hover_animation'] ? `elementor-animation-${ settings['hover_animation'] }` : '';

            #>
			<div class="kitify-ntabs-heading">
            <# _.each( settings['tabs'], function( item, index ) {
				let tabCount = index + 1,
					tabUid = elementUid + tabCount,
					tabWrapperKey = tabUid,
					tabTitleKey = 'tab-title-' + tabUid,
					tabIconKey = 'tab-icon-' + tabUid,
					tabIcon = elementor.helpers.renderIcon( view, item.tab_icon, { 'aria-hidden': true }, 'i' , 'object' ),
					tabActiveIcon = tabIcon,
					tabId = 'kitify-ntab-title-' + tabUid,
                    tabImageHTML = '',
                    iconHTML = '';

				if ( '' !== item.tab_icon_active.value ) {
					tabActiveIcon = elementor.helpers.renderIcon( view, item.tab_icon_active, { 'aria-hidden': true }, 'i' , 'object' );
				}

				if ( '' !== item.element_id ) {
					tabId = item.element_id;
				}

                if(tabIcon.value){
                    iconHTML += tabIcon.value;
                }
                if(tabActiveIcon.value){
                    iconHTML += tabActiveIcon.value;
                }

                if(item.use_image === 'yes' && item.tab_image.url){
                    let imageObj = {
                        id: item.tab_image.id,
                        url: item.tab_image.url,
                        size: 'full',
                        model: view.getEditModel()
                    };
                    let image_url = elementor.imagesManager.getImageUrl( imageObj );
                    tabImageHTML = '<img src="' + image_url + '"/>';
                    iconHTML = tabImageHTML;
                }

				view.addRenderAttribute( tabWrapperKey, {
					'id': tabId,
					'class': [ 'kitify-ntab-title','e-normal', hoverAnimationClass ],
                    'data-tabindex': tabCount,
				} );

				view.addRenderAttribute( tabTitleKey, {
					'class': [ 'kitify-ntab-title-text' ],
					'data-binding-type': 'repeater-item',
					'data-binding-repeater-name': 'tabs',
					'data-binding-setting': [ 'tab_title tab_subtitle' ],
					'data-binding-index': tabCount,
				} );

				view.addRenderAttribute( tabIconKey, {
					'class': [ 'kitify-ntab-icon' ],
					'data-binding-type': 'repeater-item',
					'data-binding-repeater-name': 'tabs',
					'data-binding-setting': [ 'tab_icon.value', 'tab_icon_active.value' ],
					'data-binding-index': tabCount,
				} );

                _tabControlHTML += `<div ${view.getRenderAttributeString( tabWrapperKey )}><div ${view.getRenderAttributeString( tabIconKey )}>${iconHTML}</div><div ${view.getRenderAttributeString( tabTitleKey )}><div class="ntabs--title">${item.tab_title}</div><div class="ntabs--subtitle">${item.tab_subtitle}</div></div></div>`;
                if(index === 0){
                    firstItem = _tabControlHTML;
                }
            } )
                if(settings.tab_as_selectbox === 'yes'){
                    view.addRenderAttribute( 'introtext', 'class', [ 'ntabs-selectbox--intro' ] );
                    let tmpHtml = '<div class="ntabs-selectbox">';
                    if( settings.tab_text_intro ){
                        tmpHtml += `<div ${view.getRenderAttributeString("introtext")}>${settings.tab_text_intro}</div>`;
                    }
                    firstItem = firstItem.replace('class="kitify-ntab-title ', 'class="kitify-ntab-title e-active clone--item ');
                    tmpHtml += `<div class="ntabs-selectbox--wrap"><div class="ntabs-selectbox--label">${firstItem}${selectboxIconHTML}</div><div class="ntabs-selectbox--select">${_tabControlHTML}</div></div>`;
                    tmpHtml += '</div>';
                    _tabControlHTML = tmpHtml;
                }
                else{
                    _tabControlHTML += selectboxIconHTML;
                }
            #>
                {{{ _tabControlHTML }}}
			</div>
			<div class="kitify-ntabs-content"></div>
			<# } #>
		</div>
		<?php
	}

	/**
	 * @param $item
	 * @return bool
	 */
	private function is_active_icon_exist( $item ) {
		return array_key_exists( 'tab_icon_active', $item ) && ! empty( $item['tab_icon_active'] ) && ! empty( $item['tab_icon_active']['value'] );
	}
}
