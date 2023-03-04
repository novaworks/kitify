<?php
/**
 * Class: Kitify_Circle_Progress
 * Name: Circle Progress
 * Slug: kitify-circle-progress
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Circle_Progress extends Kitify_Base {

	public function get_name() {
		return 'kitify-circle-progress';
	}

	public function get_title() {
		return esc_html__( 'Kitify Circle Progress', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-circle-progress';
	}

	public function get_categories() {
		return array( 'kitify' );
	}
	protected function enqueue_addon_resources(){
		wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/circle-progress.css'), null, kitify()->get_version() );
		wp_register_script(  $this->get_name() , kitify()->plugin_url('assets/js/addons/circle-progress.js') ,[ 'kitify-base','jquery-numerator' ],  kitify()->get_version() , true );
		$this->add_style_depends( $this->get_name() );
		$this->add_script_depends( $this->get_name() );
	}
	protected function register_controls() {

		$this->start_controls_section(
			'section_values',
			array(
				'label' => esc_html__( 'Values', 'kitify' ),
			)
		);

		$this->add_control(
			'values_type',
			array(
				'label'   => esc_html__( 'Progress Values Type', 'kitify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'percent',
				'options' => array(
					'percent'  => esc_html__( 'Percent', 'kitify' ),
					'absolute' => esc_html__( 'Absolute', 'kitify' ),
				),
			)
		);

		$this->add_control(
			'percent_value',
			array(
				'label'      => esc_html__( 'Current Percent', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'custom' ),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'dynamic' => version_compare( ELEMENTOR_VERSION, '2.7.0', '>=' ) ?
					array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
							TagsModule::NUMBER_CATEGORY,
						),
					) : array(),
				'condition' => array(
					'values_type' => 'percent',
				),
			)
		);

		$this->add_control(
			'absolute_value_curr',
			array(
				'label'     => esc_html__( 'Current Value', 'kitify' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 50,
				'dynamic'   => version_compare( ELEMENTOR_VERSION, '2.7.0', '>=' ) ?
					array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
							TagsModule::NUMBER_CATEGORY,
						),
					) : array(),
				'condition' => array(
					'values_type' => 'absolute',
				),
			)
		);

		$this->add_control(
			'absolute_value_max',
			array(
				'label'     => esc_html__( 'Max Value', 'kitify' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 100,
				'dynamic'   => version_compare( ELEMENTOR_VERSION, '2.7.0', '>=' ) ?
					array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
							TagsModule::NUMBER_CATEGORY,
						),
					) : array(),
				'condition' => array(
					'values_type' => 'absolute',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'kitify' ),
			)
		);

		$this->add_control(
			'prefix',
			array(
				'label'       => esc_html__( 'Value Number Prefix', 'kitify' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => '+',
			)
		);

		$this->add_control(
			'suffix',
			array(
				'label'       => esc_html__( 'Value Number Suffix', 'kitify' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '%',
				'placeholder' => '%',
			)
		);

		$this->add_control(
			'thousand_separator',
			array(
				'label'     => esc_html__( 'Show Thousand Separator in Value', 'kitify' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'kitify' ),
				'label_off' => esc_html__( 'Hide', 'kitify' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Counter Title', 'kitify' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'   => esc_html__( 'Counter Subtitle', 'kitify' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'percent_position',
			array(
				'label'   => esc_html__( 'Percent Position', 'kitify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'in-circle',
				'options' => array(
					'in-circle'  => esc_html__( 'Inside of Circle', 'kitify' ),
					'out-circle' => esc_html__( 'Outside of Circle', 'kitify' ),
				),
			)
		);

		$this->add_control(
			'labels_position',
			array(
				'label'   => esc_html__( 'Label Position', 'kitify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'out-circle',
				'options' => array(
					'in-circle'  => esc_html__( 'Inside of Circle', 'kitify' ),
					'out-circle' => esc_html__( 'Outside of Circle', 'kitify' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_size',
			array(
				'label' => esc_html__( 'Settings', 'kitify' ),
			)
		);

		$this->add_responsive_control(
			'circle_size',
			array(
				'label'      => esc_html__( 'Circle Size', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'custom' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 185,
				),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 600,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-progress-bar' => 'max-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .circle-progress' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .position-in-circle'  => 'height: {{SIZE}}{{UNIT}}',

				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_responsive_control(
			'value_stroke',
			array(
				'label'      => esc_html__( 'Value Stoke Width', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'custom' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 7,
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 300,
					),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_responsive_control(
			'bg_stroke',
			array(
				'label'      => esc_html__( 'Background Stoke Width', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'custom' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 7,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'bg_stroke_type',
			array(
				'label'       => esc_html__( 'Background Stroke Type', 'kitify' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'color' => array(
						'title' => esc_html__( 'Classic', 'kitify' ),
						'icon'  => 'eicon-paint-brush',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'kitify' ),
						'icon'  => 'eicon-barcode',
					),
				),
				'default'     => 'color',
				'label_block' => false,
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'val_bg_color',
			array(
				'label'     => esc_html__( 'Background Stroke Color', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e6e9ec',
				'selectors' => array(
					'{{WRAPPER}} .circle-progress__meter'  => 'stroke: {{VALUE}};',
				),
				'condition' => array(
					'bg_stroke_type' => array( 'color' ),
				),
			)
		);

		$this->add_control(
			'val_bg_gradient_color_a',
			array(
				'label'     => esc_html__( 'Background Stroke Color A', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#54595f',
				'selectors' => array(
					'{{WRAPPER}} .circle-progress-meter-gradient-a' => 'stop-color: {{VALUE}}',
				),
				'condition' => array(
					'bg_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_bg_gradient_color_b',
			array(
				'label'     => esc_html__( 'Background Stroke Color B', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#858d97',
				'selectors' => array(
					'{{WRAPPER}} .circle-progress-meter-gradient-b' => 'stop-color: {{VALUE}}',
				),
				'condition' => array(
					'bg_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_bg_gradient_angle',
			array(
				'label'     => esc_html__( 'Background Stroke Gradient Angle', 'kitify' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 45,
				'min'       => 0,
				'max'       => 360,
				'step'      => 0,
				'condition' => array(
					'bg_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_stroke_type',
			array(
				'label'       => esc_html__( 'Value Stroke Type', 'kitify' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'color' => array(
						'title' => esc_html__( 'Classic', 'kitify' ),
						'icon'  => 'eicon-paint-brush',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'kitify' ),
						'icon'  => 'eicon-barcode',
					),
				),
				'default'     => 'color',
				'label_block' => false,
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'val_stroke_color',
			array(
				'label'     => esc_html__( 'Value Stroke Color', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6ec1e4',
				'selectors' => array(
					'{{WRAPPER}} .circle-progress__value'  => 'stroke: {{VALUE}};',
				),
				'condition' => array(
					'val_stroke_type' => array( 'color' ),
				),
			)
		);

		$this->add_control(
			'val_stroke_gradient_color_a',
			array(
				'label'     => esc_html__( 'Value Stroke Color A', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6ec1e4',
				'selectors' => array(
					'{{WRAPPER}} .circle-progress-value-gradient-a'  => 'stop-color: {{VALUE}};',
				),
				'condition' => array(
					'val_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_stroke_gradient_color_b',
			array(
				'label'     => esc_html__( 'Value Stroke Color B', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#b6e0f1',
				'selectors' => array(
					'{{WRAPPER}} .circle-progress-value-gradient-b'  => 'stop-color: {{VALUE}};',
				),
				'condition' => array(
					'val_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'val_stroke_gradient_angle',
			array(
				'label'     => esc_html__( 'Value Stroke Angle', 'kitify' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 45,
				'min'       => 0,
				'max'       => 360,
				'step'      => 1,
				'condition' => array(
					'val_stroke_type' => array( 'gradient' ),
				),
			)
		);

		$this->add_control(
			'duration',
			array(
				'label'   => esc_html__( 'Animation Duration', 'kitify' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1000,
				'min'     => 100,
				'step'    => 100,
			)
		);

		$this->end_controls_section();

		$this->_start_controls_section(
			'section_progress_style',
			array(
				'label'      => esc_html__( 'Progress Circle Style', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'circle_fill_color',
			array(
				'label'     => esc_html__( 'Circle Fill Color', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .circle-progress__meter' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'line_endings',
			array(
				'label'   => esc_html__( 'Progress Line Endings', 'kitify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'butt',
				'options' => array(
					'butt'  => esc_html__( 'Flat', 'kitify' ),
					'round' => esc_html__( 'Rounded', 'kitify' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-progress__value' => 'stroke-linecap: {{VALUE}}',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'circle_box_shadow',
				'label'    => esc_html__( 'Circle Box Shadow', 'kitify' ),
				'selector' => '{{WRAPPER}} .circle-progress',
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content Style', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'number_style',
			array(
				'label'     => esc_html__( 'Number Styles', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'number_color',
			array(
				'label' => esc_html__( 'Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-counter .circle-val' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .circle-counter .circle-val',
			),
			50
		);

		$this->_add_responsive_control(
			'number_padding',
			array(
				'label'      => esc_html__( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-val' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'number_prefix_font_size',
			array(
				'label'      => esc_html__( 'Prefix Font Size', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', 'custom'
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'number_prefix_gap',
			array(
				'label'      => esc_html__( 'Prefix Gap (px)', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'custom'
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'number_prefix_alignment',
			array(
				'label'       => esc_html__( 'Prefix Alignment', 'kitify' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'center',
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'kitify' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'kitify' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'kitify' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-val .circle-counter__prefix' => 'align-self: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'number_suffix_font_size',
			array(
				'label'      => esc_html__( 'Suffix Font Size', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', 'custom'
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'number_suffix_gap',
			array(
				'label'      => esc_html__( 'Suffix Gap (px)', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'custom'
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'margin-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'margin-right: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'number_suffix_alignment',
			array(
				'label'       => esc_html__( 'Suffix Alignment', 'kitify' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'center',
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'kitify' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'kitify' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'kitify' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-val .circle-counter__suffix' => 'align-self: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'title_style',
			array(
				'label'     => esc_html__( 'Title Styles', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'title_color',
			array(
				'label' => esc_html__( 'Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-counter .circle-counter__title' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .circle-counter .circle-counter__title',
			),
			50
		);

		$this->_add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-counter__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_control(
			'subtitle_style',
			array(
				'label'     => esc_html__( 'Subtitle Styles', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'subtitle_color',
			array(
				'label'  => esc_html__( 'Color', 'kitify' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} .circle-counter .circle-counter__subtitle' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .circle-counter .circle-counter__subtitle',
			),
			50
		);

		$this->_add_responsive_control(
			'subtitle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .circle-counter .circle-counter__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

}
