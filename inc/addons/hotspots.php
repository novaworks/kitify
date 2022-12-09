<?php
/**
 * Class: Kitify_Hotspots
 * Name: Hotspots
 * Slug: kitify-hotspots
 */

namespace Elementor;

if (!defined('WPINC')) {
	die;
}

class Kitify_Hotspots extends Widget_Image {

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->enqueue_addon_resources();
    }

	protected function enqueue_addon_resources(){
		if(!kitify_settings()->is_combine_js_css()) {
			wp_register_style( $this->get_name(), kitify()->plugin_url( 'assets/css/addons/hotspots.css' ), [ 'kitify-base' ], kitify()->get_version() );
			wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/hotspots.js'), ['elementor-frontend'], kitify()->get_version(), true);
			$this->add_style_depends( $this->get_name() );
			$this->add_script_depends( $this->get_name() );
		}
	}

	public function get_name() {
		return 'kitify-hotspots';
	}

	public function get_title() {
		return 'Kitify ' . __( 'Hotspots', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-hotspot';
	}

	public function get_keywords() {
        return [ 'image', 'tooltip', 'CTA', 'dot', 'hotspot' ];
	}

    protected function register_controls() {
        parent::register_controls();

        /**
         * Image Section
         */

        $this->remove_control( 'caption_source' );
        $this->remove_control( 'caption' );
        $this->remove_control( 'link_to' );
        $this->remove_control( 'link' );
        $this->remove_control( 'open_lightbox' );

        /**
         * Section Hotspot
         */
        $this->start_controls_section(
            'hotspot_section',
            [
                'label' => esc_html__( 'Hotspot', 'kitify' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'hotspot_repeater' );

        $repeater->start_controls_tab(
            'hotspot_content_tab',
            [
                'label' => esc_html__( 'Content', 'kitify' ),
            ]
        );

        $repeater->add_control(
            'hotspot_content_type',
            array(
                'label'   => esc_html__( 'Content Type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'  => esc_html__( 'Default', 'kitify' ),
                    'template' => esc_html__( 'Product', 'kitify' ),
                ),
            )
        );

        $repeater->add_control(
            'hotspot_label',
            [
                'label' => esc_html__( 'Label', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_link',
            [
                'label' => esc_html__( 'Link', 'kitify' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'kitify' ),
                'condition' => [
                    'hotspot_content_type' => 'default'
                ]
            ]
        );

        $repeater->add_control(
            'hotspot_icon',
            [
                'label' => esc_html__( 'Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'hotspot_icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Icon Start', 'kitify' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Icon End', 'kitify' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'start' => 'grid-column: 1;',
                    'end' => 'grid-column: 2;',
                ],
                'condition' => [
                    'hotspot_icon[value]!' => '',
                    'hotspot_label[value]!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .kitify-hotspot__icon' => '{{VALUE}}',
                ],
                'default' => 'start',
            ]
        );

        $repeater->add_control(
            'hotspot_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => '5',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .kitify-hotspot__button' =>
                        'grid-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'hotspot_icon[value]!' => '',
                    'hotspot_label[value]!' => '',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_custom_size',
            [
                'label' => esc_html__( 'Custom Hotspot Size', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'kitify' ),
                'label_on' => esc_html__( 'On', 'kitify' ),
                'default' => 'no',
                'description' => esc_html__( 'Set custom Hotspot size that will only affect this specific hotspot.', 'kitify' ),
                'condition'   => array(
                    'hotspot_content_type' => 'default',
                ),
            ]
        );

        $repeater->add_control('hotspot_width',
            [
                'label' => esc_html__( 'Min Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--hotspot-min-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'hotspot_custom_size' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_height',
            [
                'label' => esc_html__( 'Min Height', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--hotspot-min-height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'hotspot_custom_size' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_tooltip_content',
            [
                'render_type' => 'template',
                'label' => esc_html__( 'Tooltip Content', 'kitify' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'Add Your Tooltip Text Here', 'kitify' ),
                'condition'   => array(
                    'hotspot_content_type' => 'default',
                ),
            ]
        );
        $repeater->add_control(
            'product_id',
            array(
                'label'       => esc_html__( 'Choose Product', 'kitify' ),
                'label_block' => 'true',
                'type'        => 'kitify-query',
                'object_type' => 'product',
                'filter_type' => 'by_id',
                'condition'   => array(
                    'hotspot_content_type' => 'template',
                ),
            )
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'hotspot_position_tab',
            [
                'label' => esc_html__( 'POSITION', 'kitify' ),
            ]
        );

        $repeater->add_control(
            'always_show',
            [
                'label' => esc_html__( 'Always show', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'kitify' ),
                'label_on' => esc_html__( 'On', 'kitify' ),
                'default' => 'no',
                'description' => esc_html__( 'Always show tooltip', 'kitify' ),
            ]
        );

        $repeater->add_control(
            'hotspot_horizontal',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => is_rtl() ? 'right' : 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
            ]
        );

        $repeater->add_responsive_control(
            'hotspot_offset_x',
            [
                'label' => esc_html__( 'Offset', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => '50',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' =>
                        '{{hotspot_horizontal.VALUE}}: {{SIZE}}%; --hotspot-translate-x: {{SIZE}}%;',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_vertical',
            [
                'label' => esc_html__( 'Vertical Orientation', 'kitify' ),
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
                ],
                'default' => 'top',
                'toggle' => false,
            ]
        );

        $repeater->add_responsive_control(
            'hotspot_offset_y',
            [
                'label' => esc_html__( 'Offset', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => '50',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' =>
                        '{{hotspot_vertical.VALUE}}: {{SIZE}}%; --hotspot-translate-y: {{SIZE}}%;',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_tooltip_position',
            [
                'label' => esc_html__( 'Custom Tooltip Properties', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'kitify' ),
                'label_on' => esc_html__( 'On', 'kitify' ),
                'default' => 'no',
                'description' => sprintf( esc_html__( 'Set custom Tooltip opening that will only affect this specific hotspot.', 'kitify' ), '<code>|</code>' ),
            ]
        );

        $repeater->add_control(
            'hotspot_heading',
            [
                'label' => esc_html__( 'Box', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'hotspot_tooltip_position' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'hotspot_position',
            [
                'label' => esc_html__( 'Position', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'left' => [
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'top' => [
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'top-left' => [
                        'title' => esc_html__( 'Top left', 'kitify' ),
                        'icon' => 'dlicon arrows-4_block-top-left',
                    ],
                    'top-right' => [
                        'title' => esc_html__( 'Top right', 'kitify' ),
                        'icon' => 'dlicon arrows-4_block-top-right',
                    ],
                    'bottom-left' => [
                        'title' => esc_html__( 'Bottom left', 'kitify' ),
                        'icon' => 'dlicon arrows-4_block-bottom-left',
                    ],
                    'bottom-right' => [
                        'title' => esc_html__( 'Bottom right', 'kitify' ),
                        'icon' => 'dlicon arrows-4_block-bottom-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'right'     => 'right: initial;bottom: initial;left: initial;top: initial;right: calc(100% + 15px );',
                    'bottom'    => 'right: initial;bottom: initial;left: initial;top: initial;bottom: calc(100% + 15px );',
                    'left'      => 'right: initial;bottom: initial;left: initial;top: initial;left: calc(100% + 15px );',
                    'top'       => 'right: initial;bottom: initial;left: initial;top: initial;top: calc(100% + 15px );',
                    'top-left'  => 'right: initial;bottom: 0;left: initial;top: initial;right: calc(100% + 15px );',
                    'top-right' => 'right: initial;bottom: 0;left: initial;top: initial;left: calc(100% + 15px );',
                    'bottom-left'  => 'right: initial;bottom: initial;left: initial;top: 0;right: calc(100% + 15px );',
                    'bottom-right'  => 'right: initial;bottom: initial;left: initial;top: 0;left: calc(100% + 15px );',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .kitify-hotspot--tooltip-position' => '{{VALUE}}',
                ],
                'condition' => [
                    'hotspot_tooltip_position' => 'yes',
                ],
                'render_type' => 'template',
                'label_block' => true,
            ]
        );

        $repeater->add_responsive_control(
            'hotspot_tooltip_width',
            [
                'label' => esc_html__( 'Min Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .kitify-hotspot__tooltip' => 'min-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'hotspot_tooltip_position' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_tooltip_text_wrap',
            [
                'label' => esc_html__( 'Text Wrap', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'kitify' ),
                'label_on' => esc_html__( 'On', 'kitify' ),
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--white-space: normal',
                ],
                'condition' => [
                    'hotspot_tooltip_position' => 'yes',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'hotspot',
            [
                'label' => esc_html__( 'Hotspot', 'kitify' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ hotspot_label }}}',
                'default' => [
                    [
                        // Default #1 circle
                    ],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'hotspot_animation',
            [
                'label' => esc_html__( 'Animation', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'kitify-hotspot--soft-beat' => esc_html__( 'Soft Beat', 'kitify' ),
                    'kitify-hotspot--expand' => esc_html__( 'Expand', 'kitify' ),
                    'kitify-hotspot--overlay' => esc_html__( 'Overlay', 'kitify' ),
                    '' => esc_html__( 'None', 'kitify' ),
                ],
                'default' => 'kitify-hotspot--expand',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hotspot_sequenced_animation',
            [
                'label' => esc_html__( 'Sequenced Animation', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'kitify' ),
                'label_on' => esc_html__( 'On', 'kitify' ),
                'default' => 'no',
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'hotspot_sequenced_animation_duration',
            [
                'label' => esc_html__( 'Sequence Duration (ms)', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 20000,
                    ],
                ],
                'condition' => [
                    'hotspot_sequenced_animation' => 'yes',
                ],
                'frontend_available' => true,
                'render_type' => 'ui',
            ]
        );

        $this->end_controls_section();

        /**
         * Tooltip Section
         */
        $this->start_controls_section(
            'tooltip_section',
            [
                'label' => esc_html__( 'Tooltip', 'kitify' ),
            ]
        );

        $this->add_responsive_control(
            'tooltip_position',
            [
                'label' => esc_html__( 'Position', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'top',
                'toggle' => false,
                'options' => [
                    'right' => [
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'left' => [
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'top' => [
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-hotspot--tooltip-position' => 'right: initial;bottom: initial;left: initial;top: initial;{{VALUE}}: calc(100% + 15px );',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'tooltip_trigger',
            [
                'label' => esc_html__( 'Trigger', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'mouseenter' => esc_html__( 'Hover', 'kitify' ),
                    'click' => esc_html__( 'Click', 'kitify' ),
                    'none' => esc_html__( 'None', 'kitify' ),
                ],
                'default' => 'click',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'tooltip_animation',
            [
                'label' => esc_html__( 'Animation', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'kitify-hotspot--fade-in-out' => esc_html__( 'Fade In/Out', 'kitify' ),
                    'kitify-hotspot--fade-grow' => esc_html__( 'Fade Grow', 'kitify' ),
                    'kitify-hotspot--fade-direction' => esc_html__( 'Fade By Direction', 'kitify' ),
                    'kitify-hotspot--slide-direction' => esc_html__( 'Slide By Direction', 'kitify' ),
                ],
                'default' => 'kitify-hotspot--fade-in-out',
                'placeholder' => esc_html__( 'Enter your image caption', 'kitify' ),
                'condition' => [
                    'tooltip_trigger!' => 'none',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'tooltip_animation_duration',
            [
                'label' => esc_html__( 'Duration (ms)', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tooltip-transition-duration: {{SIZE}}ms;',
                ],
                'condition' => [
                    'tooltip_trigger!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();

        /*************
         * Style Tab
         ************/
        /**
         * Section Style Image
         */

        $this->remove_control( 'section_style_caption' );

        $this->remove_control( 'caption_align' );

        $this->remove_control( 'text_color' );

        $this->remove_control( 'caption_background_color' );

        $this->remove_control( 'caption_typography' );

        $this->remove_control( 'caption_text_shadow' );

        $this->remove_control( 'caption_space' );

        $this->update_control( 'align', [
            'options' => [
                'flex-start' => [
                    'title' => esc_html__( 'Start', 'kitify' ),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__( 'Center', 'kitify' ),
                    'icon' => 'eicon-text-align-center',
                ],
                'flex-end' => [
                    'title' => esc_html__( 'End', 'kitify' ),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--background-align: {{VALUE}};',
            ],
        ] );

        $this->update_control(
            'width',
            [
                'selectors' => [
                    '{{WRAPPER}}' => '--container-width: {{SIZE}}{{UNIT}}; --image-width: 100%;',
                ],
            ]
        );

        $this->update_control(
            'space',
            [
                'selectors' => [
                    '{{WRAPPER}}' => '--container-max-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->update_control(
            'height',
            [
                'selectors' => [
                    '{{WRAPPER}}' => '--container-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->remove_control( 'hover_animation' );

        $this->update_control(
            'opacity',
            [
                'selectors' => [
                    '{{WRAPPER}}' => '--opacity: {{SIZE}};',
                ],
            ]
        );

        $this->update_control(
            'opacity_hover',
            [
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container>img:hover' => '--opacity: {{SIZE}};',
                ],
            ]
        );

        /**
         * Section Style Hotspot
         */
        $this->start_controls_section(
            'section_style_hotspot',
            [
                'label' => esc_html__( 'Hotspot', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'style_hotspot_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--hotspot-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_hotspot_size',
            [
                'label' => esc_html__( 'Size', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--hotspot-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_typography',
                'selector' => '{{WRAPPER}} .kitify-hotspot__label',
            ]
        );

        $this->add_responsive_control(
            'style_hotspot_width',
            [
                'label' => esc_html__( 'Min Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--hotspot-min-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_hotspot_height',
            [
                'label' => esc_html__( 'Min Height', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--hotspot-min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'style_hotspot_box_color',
            [
                'label' => esc_html__( 'Box Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--hotspot-box-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_hotspot_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--hotspot-padding: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_control(
            'style_hotspot_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--hotspot-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_hotspot_box_shadow',
                'selector' => '
					{{WRAPPER}} .kitify-hotspot:not(.kitify-hotspot--circle) .kitify-hotspot__button,
					{{WRAPPER}} .kitify-hotspot.kitify-hotspot--circle .kitify-hotspot__button .kitify-hotspot__outer-circle
				',
            ]
        );

        $this->end_controls_section();

        /**
         * Section Style Tooltip
         */
        $this->start_controls_section(
            'section_style_tooltip',
            [
                'label' => esc_html__( 'Tooltip', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'style_tooltip_text_color',
            [
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--tooltip-text-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_tooltip_typography',
                'selector' => '{{WRAPPER}} .kitify-hotspot__tooltip',
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_align',
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
                'selectors' => [
                    '{{WRAPPER}}' => '--tooltip-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'style_tooltip_heading',
            [
                'label' => esc_html__( 'Box', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_width',
            [
                'label' => esc_html__( 'Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tooltip-min-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tooltip-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'style_tooltip_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--tooltip-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'style_tooltip_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tooltip-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-hotspot__tooltip',
            ]
        );
				$this->add_control(
            'style_tooltip_heading_ptitle',
            [
                'label' => esc_html__( 'Product Title', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'style_tooltip_ptile_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'style_tooltip_heading2',
            [
                'label' => esc_html__( 'Product Price', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'style_tooltip_price_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--price' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_tooltip_price_typography',
                'selector' => '{{WRAPPER}} .product_item--price',
            ]
        );
        $this->add_responsive_control(
            'style_tooltip_price_margin',
            [
                'label' => esc_html__( 'Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'style_tooltip_heading3',
            [
                'label' => esc_html__( 'Product Button', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_tooltip_btn_typography',
                'selector' => '{{WRAPPER}} .product_item--action',
            ]
        );

        $this->start_controls_tabs('style_tooltip_btn_tabs');
        $this->start_controls_tab(
            'style_tooltip_btn_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control(
            'style_tooltip_btn_color',
            [
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--action' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'style_tooltip_btn_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--action' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_btn_padding',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_btn_margin',
            [
                'label' => esc_html__( 'Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'style_tooltip_btn_border',
                'label' => esc_html__( 'Border', 'kitify' ),
                'selector' => '{{WRAPPER}} .product_item--action',
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_btn_radius',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_tooltip_btn_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control(
            'style_tooltip_btn_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--action:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'style_tooltip_btn_bgcolor_hover',
            [
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product_item--action:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_btn_padding_hover',
            [
                'label' => esc_html__( 'Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--action:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_btn_margin_hover',
            [
                'label' => esc_html__( 'Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--action:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'style_tooltip_btn_border_hover',
                'label' => esc_html__( 'Border', 'kitify' ),
                'selector' => '{{WRAPPER}} .product_item--action:hover',
            ]
        );

        $this->add_responsive_control(
            'style_tooltip_btn_radius_hover',
            [
                'label' => esc_html__( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product_item--action:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }


    protected function render() {
        $settings = $this->get_settings_for_display();

        $is_tooltip_direction_animation = 'kitify-hotspot--slide-direction' === $settings['tooltip_animation'] || 'kitify-hotspot--fade-direction' === $settings['tooltip_animation'];
        $show_tooltip = 'none' === $settings['tooltip_trigger'];
        $sequenced_animation_class = 'yes' === $settings['hotspot_sequenced_animation'] ? 'kitify-hotspot--sequenced' : '';

        // Main Image
        Group_Control_Image_Size::print_attachment_image_html( $settings, 'image', 'image' );

        // Hotspot
        foreach ( $settings['hotspot'] as $key => $hotspot ) :
            $is_circle = ! $hotspot['hotspot_label'] && ! $hotspot['hotspot_icon']['value'];
            $is_only_icon = ! $hotspot['hotspot_label'] && $hotspot['hotspot_icon']['value'];
            $hotspot_position_x = '%' === $hotspot['hotspot_offset_x']['unit'] ? 'kitify-hotspot--position-' . $hotspot['hotspot_horizontal'] : '';
            $hotspot_position_y = '%' === $hotspot['hotspot_offset_y']['unit'] ? 'kitify-hotspot--position-' . $hotspot['hotspot_vertical'] : '';
            $is_hotspot_link = ! empty( $hotspot['hotspot_link']['url'] );
            $tooltip_type = $hotspot['hotspot_content_type'];
            $tooltip_product_id = !empty($hotspot['product_id']) ? $hotspot['product_id'] : 0;
            if($tooltip_type == 'template'){
                $is_hotspot_link = false;
            }
            $hotspot_element_tag = $is_hotspot_link ? 'a' : 'div';

            // hotspot attributes
            $hotspot_repeater_setting_key = $this->get_repeater_setting_key( 'hotspot', 'hotspots', $key );
            $this->add_render_attribute(
                $hotspot_repeater_setting_key, [
                    'class' => [
                        'kitify-hotspot',
                        'elementor-repeater-item-' . $hotspot['_id'],
                        $sequenced_animation_class,
                        $hotspot_position_x,
                        $hotspot_position_y,
                        $is_hotspot_link ? 'kitify-hotspot--link' : '',
                        ( 'click' === $settings['tooltip_trigger'] && $is_hotspot_link ) ? 'kitify-hotspot--no-tooltip' : '',
                        'hotspot-content-type-' . $tooltip_type
                    ],
                ]
            );
            $this->add_render_attribute( $hotspot_repeater_setting_key, 'data-id', $hotspot['_id'] );
            if ( filter_var($hotspot['always_show'], FILTER_VALIDATE_BOOLEAN) ) {
                $this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'kitify-hotspot--active kitify-hotspot--always' );
            }
            if ( $is_circle ) {
                $this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'kitify-hotspot--circle' );
            }
            if ( $is_only_icon ) {
                $this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'kitify-hotspot--icon' );
            }

            if ( $is_hotspot_link ) {
                $this->add_link_attributes( $hotspot_repeater_setting_key, $hotspot['hotspot_link'] );
            }

            // hotspot trigger attributes
            $trigger_repeater_setting_key = $this->get_repeater_setting_key( 'trigger', 'hotspots', $key );
            $this->add_render_attribute(
                $trigger_repeater_setting_key, [
                    'class' => [
                        'kitify-hotspot__button',
                        $settings['hotspot_animation'],
                    ],
                ]
            );

            //direction mask attributes
            $direction_mask_repeater_setting_key = $this->get_repeater_setting_key( 'kitify-hotspot__direction-mask', 'hotspots', $key );
            $this->add_render_attribute(
                $direction_mask_repeater_setting_key, [
                    'class' => [
                        'kitify-hotspot__direction-mask',
                        ( $is_tooltip_direction_animation ) ? 'kitify-hotspot--tooltip-position' : '',
                    ],
                ]
            );

            //tooltip attributes
            $tooltip_custom_position = ( $is_tooltip_direction_animation && $hotspot['hotspot_tooltip_position'] && $hotspot['hotspot_position'] ) ? 'kitify-hotspot--override-tooltip-animation-from-' . $hotspot['hotspot_position'] : '';
            $tooltip_repeater_setting_key = $this->get_repeater_setting_key( 'tooltip', 'hotspots', $key );

            $this->add_render_attribute( $tooltip_repeater_setting_key, 'data-id', $hotspot['_id'] );
            $this->add_render_attribute(
                $tooltip_repeater_setting_key, [
                    'class' => [
                        'kitify-hotspot__tooltip',
                        ( $show_tooltip ) ? 'kitify-hotspot--show-tooltip' : '',
                        ( ! $is_tooltip_direction_animation ) ? 'kitify-hotspot--tooltip-position' : '',
                        ( ! $show_tooltip ) ? $settings['tooltip_animation'] : '',
                        $tooltip_custom_position,
                    ],
                ]
            ); ?>

            <?php // Hotspot ?>
            <<?php Utils::print_validated_html_tag( $hotspot_element_tag ); ?> <?php $this->print_render_attribute_string( $hotspot_repeater_setting_key ); ?>>

            <?php // Hotspot Trigger ?>
            <div <?php $this->print_render_attribute_string( $trigger_repeater_setting_key ); ?>>
                <?php if ( $is_circle ) : ?>
                    <div class="kitify-hotspot__outer-circle"></div>
                    <div class="kitify-hotspot__inner-circle"></div>
                <?php else : ?>
                    <?php if ( $hotspot['hotspot_icon']['value'] ) : ?>
                        <div class="kitify-hotspot__icon"><?php Icons_Manager::render_icon( $hotspot['hotspot_icon'] ); ?></div>
                    <?php endif; ?>
                    <?php if ( $hotspot['hotspot_label'] ) : ?>
                        <div class="kitify-hotspot__label"><?php
                            // PHPCS - the main text of a widget should not be escaped.
                            echo $hotspot['hotspot_label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php
            // Hotspot Tooltip
            $hotspot_tooltip_content = '';
            if( $tooltip_type == 'template' ){
                if( function_exists('wc_get_product') ){
                    $product_obj = wc_get_product($tooltip_product_id);
                    if($product_obj){
                        $tpl = '<div class="kitify-hotspot__product">%1$s<div class="kitify-hotspot__product_info">%2$s%3$s%4$s</div></div>';
                        $product_image = $product_obj->get_image();
                        $product_title = sprintf('<a class="product_item--title" href="%1$s">%2$s</a>', esc_url($product_obj->get_permalink()), $product_obj->get_title());
                        $product_price = sprintf('<span class="product_item--price price">%1$s</span>', $product_obj->get_price_html());
                        $product_action = sprintf('<a class="product_item--action elementor-button elementor-size-xs" href="%1$s">%2$s</a>', esc_url($product_obj->get_permalink()), $product_obj->add_to_cart_text());
                        $hotspot_tooltip_content = sprintf( $tpl, $product_image, $product_title, $product_price, $product_action);
                    }
                }
            }
            else{
                $hotspot_tooltip_content = $hotspot['hotspot_tooltip_content'];
            }

            ?>
            <?php if ( $hotspot_tooltip_content && ! ( 'click' === $settings['tooltip_trigger'] && $is_hotspot_link ) ) : ?>
                <?php if ( $is_tooltip_direction_animation ) : ?>
                    <div <?php $this->print_render_attribute_string( $direction_mask_repeater_setting_key ); ?>>
                <?php endif; ?>
                <div <?php $this->print_render_attribute_string( $tooltip_repeater_setting_key ); ?> >
                    <?php
                    // PHPCS - the main text of a widget should not be escaped.
                    echo $hotspot_tooltip_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </div>
                <?php if ( $is_tooltip_direction_animation ) : ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            </<?php Utils::print_validated_html_tag( $hotspot_element_tag ); ?>>

        <?php endforeach; ?>

        <?php
    }

    /**
     * Render Hotspot widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since  2.9.0
     * @access protected
     */
    protected function content_template() {         ?>
        <#
        const image = {
            id: settings.image.id,
            url: settings.image.url,
            size: settings.image_size,
            dimension: settings.image_custom_dimension,
            model: view.getEditModel()
        };

        const imageUrl = elementor.imagesManager.getImageUrl( image );

        let productHTMLPlaceholder = '<div class="kitify-hotspot__product">';
            productHTMLPlaceholder += '<img src="<?php echo esc_url(Utils::get_placeholder_image_src()); ?>" title="" alt=""/>';
            productHTMLPlaceholder += '<div class="kitify-hotspot__product_info">';
                productHTMLPlaceholder += '<a href="#" class="product_item--title">Name of product</a>';
                productHTMLPlaceholder += '<span class="product_item--price price"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>59.99</bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>35.99</bdi></span></ins></span>';
                productHTMLPlaceholder += '<a class="product_item--action elementor-button elementor-size-xs" href="#">Shop Now</a>';
            productHTMLPlaceholder += '</div>';
            productHTMLPlaceholder += '</div>';

        #>
        <img src="{{ imageUrl }}" title="" alt="">
        <#
        const isTooltipDirectionAnimation = (settings.tooltip_animation==='kitify-hotspot--slide-direction' || settings.tooltip_animation==='kitify-hotspot--fade-direction' ) ? true : false;
        const showTooltip = ( settings.tooltip_trigger === 'none' );

        _.each( settings.hotspot, ( hotspot, index ) => {
            const iconHTML = elementor.helpers.renderIcon( view, hotspot.hotspot_icon, {}, 'i' , 'object' );
            const isCircle = !hotspot.hotspot_label && !hotspot.hotspot_icon.value;
            const isOnlyIcon = !hotspot.hotspot_label && hotspot.hotspot_icon.value;
            const hotspotPositionX = '%' === hotspot.hotspot_offset_x.unit ? 'kitify-hotspot--position-' + hotspot.hotspot_horizontal : '';
            const hotspotPositionY = '%' === hotspot.hotspot_offset_y.unit ? 'kitify-hotspot--position-' + hotspot.hotspot_vertical : '';
            const hotspotLink = (hotspot.hotspot_content_type != 'template') ? hotspot.hotspot_link.url : false;

            if(hotspot.hotspot_content_type == 'template'){
                hotspot.hotspot_tooltip_content = productHTMLPlaceholder;
            }

            const hotspotElementTag = hotspotLink ? 'a': 'div';

            // hotspot attributes
            const hotspotRepeaterSettingKey = view.getRepeaterSettingKey( 'hotspot', 'hotspots', index );
            view.addRenderAttribute( hotspotRepeaterSettingKey, {
                'class' : [
                    'kitify-hotspot',
                    'elementor-repeater-item-' + hotspot._id,
                    hotspotPositionX,
                    hotspotPositionY,
                    hotspotLink ? 'kitify-hotspot--link' : '',
                ]
            });

            view.addRenderAttribute( hotspotRepeaterSettingKey, 'data-id', hotspot._id );

            if ( isCircle ) {
                view.addRenderAttribute( hotspotRepeaterSettingKey, 'class', 'kitify-hotspot--circle' );
            }

            if ( isOnlyIcon ) {
                view.addRenderAttribute( hotspotRepeaterSettingKey, 'class', 'kitify-hotspot--icon' );
            }

            // hotspot trigger attributes
            const triggerRepeaterSettingKey = view.getRepeaterSettingKey( 'trigger', 'hotspots', index );
            view.addRenderAttribute(triggerRepeaterSettingKey, {
                'class' : [
                    'kitify-hotspot__button',
                    settings.hotspot_animation,
                    //'hotspot-trigger-' + hotspot.hotspot_icon_position
                ]
            });

            //direction mask attributes
            const directionMaskRepeaterSettingKey = view.getRepeaterSettingKey( 'kitify-hotspot__direction-mask', 'hotspots', index );
            view.addRenderAttribute(directionMaskRepeaterSettingKey, {
                'class' : [
                    'kitify-hotspot__direction-mask',
                    ( isTooltipDirectionAnimation ) ? 'kitify-hotspot--tooltip-position' : ''
                ]
            });

            //tooltip attributes
            const tooltipCustomPosition = ( isTooltipDirectionAnimation && hotspot.hotspot_tooltip_position && hotspot.hotspot_position ) ? 'kitify-hotspot--override-tooltip-animation-from-' + hotspot.hotspot_position : '';
            const tooltipRepeaterSettingKey = view.getRepeaterSettingKey('tooltip', 'hotspots', index);

            view.addRenderAttribute( tooltipRepeaterSettingKey, 'data-id', hotspot._id );
            view.addRenderAttribute( tooltipRepeaterSettingKey, {
                'class': [
                    'kitify-hotspot__tooltip',
                    ( showTooltip ) ? 'kitify-hotspot--show-tooltip' : '',
                    ( !isTooltipDirectionAnimation ) ? 'kitify-hotspot--tooltip-position' : '',
                    ( !showTooltip ) ? settings.tooltip_animation : '',
                    tooltipCustomPosition,
                    ( hotspot.always_show ) ? 'kitify-hotspot--active kitify-hotspot--always' : 'ddd'
                ],
            });

        #>
        <{{{ hotspotElementTag }}} {{{ view.getRenderAttributeString( hotspotRepeaterSettingKey ) }}}>

        <?php // Hotspot Trigger ?>
        <div {{{ view.getRenderAttributeString( triggerRepeaterSettingKey ) }}}>
            <# if ( isCircle ) { #>
            <div class="kitify-hotspot__outer-circle"></div>
            <div class="kitify-hotspot__inner-circle"></div>
            <# } else { #>
            <# if (hotspot.hotspot_icon.value){ #>
            <div class="kitify-hotspot__icon">{{{ iconHTML.value }}}</div>
            <# } #>
            <# if ( hotspot.hotspot_label ){ #>
            <div class="kitify-hotspot__label">{{{ hotspot.hotspot_label }}}</div>
            <# } #>
            <# } #>
        </div>

        <?php // Hotspot Tooltip ?>
        <# if( hotspot.hotspot_tooltip_content && ! ( 'click' === settings.tooltip_trigger && hotspotLink ) ){ #>
            <# if( isTooltipDirectionAnimation ){ #>
            <div {{{ view.getRenderAttributeString( directionMaskRepeaterSettingKey ) }}}>
                <# } #>
                <div {{{ view.getRenderAttributeString( tooltipRepeaterSettingKey ) }}}>
                    {{{ hotspot.hotspot_tooltip_content }}}
                </div>
                <# if( isTooltipDirectionAnimation ){ #>
            </div>
            <# } #>
        <# } #>

        </{{{ hotspotElementTag }}}>
        <# }); #>
        <?php
    }

}
