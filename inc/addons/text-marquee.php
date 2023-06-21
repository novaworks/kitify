<?php
/**
 * Class: Kitify_Text_Marquee
 * Name: Text Marquee
 * Slug: kitify-text-marquee
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

class Kitify_Text_Marquee extends Kitify_Base {

	public function get_name() {
		return 'kitify-text-marquee';
	}

	public function get_title() {
		return esc_html__( 'Kitify Text Marquee', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-text-marquee';
	}

	public function get_categories() {
		return array( 'kitify' );
	}
	protected function enqueue_addon_resources(){
		wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/text-marquee.css'), null, kitify()->get_version() );
		$this->add_style_depends( $this->get_name() );
	}
	protected function register_controls() {
		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'kitify' ),
			)
		);
		$repeater = new Repeater();
		$repeater->add_control(
				'text',
				[
						'label' => __( 'Text', 'kitify' ),
						'type' => Controls_Manager::TEXT,
						'default' => __( 'Title #1', 'kitify' ),
						'dynamic' => array( 'active' => true ),
				]
		);
		$repeater->add_control(
				'link',
				[
						'label' => __( 'Link', 'kitify' ),
						'type' => Controls_Manager::URL,
						'dynamic' => [
								'active' => true,
						],
						'placeholder' => __( 'https://your-link.com', 'kitify' ),
				]
		);
		$repeater->add_control(
				'text_color',
				[
						'label' => __( 'Color', 'kitify' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}.kitify-text-marquee__item' => 'color: {{VALUE}}',
						],
				]
		);
		$repeater->add_group_control(
				Group_Control_Typography::get_type(),
				array(
						'name'     => 'text_typography',
						'label' => __( 'Typography', 'kitify' ),
						'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.kitify-text-marquee__item',
				)
		);
		$this->add_control(
				'items',
				[
						'label' => __( 'Items', 'kitify' ),
						'type' => Controls_Manager::REPEATER,
						'show_label' => true,
						'fields' => $repeater->get_controls(),
						'title_field' => '{{{ text }}}',
						'default' => [
								[
										'text' => __( 'Title #1', 'kitify' ),
								],
								[
										'text' => __( 'Title #2', 'kitify' ),
								],
								[
										'text' => __( 'Title #3', 'kitify' ),
								],
						],
				]
		);
		$this->_add_advanced_icon_control(
				'separator_icon',
				array(
						'label'       => esc_html__( 'Separator Icon', 'kitify' ),
						'type'        => Controls_Manager::ICON,
						'label_block' => false,
						'skin'        => 'inline',
						'file'        => '',
				)
		);
		$this->add_control(
			'duration',
			array(
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Animation Duration (Seconds)', 'kitify' ),
				'placeholder' => '20',
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 20,
			)
		);
		$this->add_control(
				'reverse_direction',
				array(
						'label'        => esc_html__( 'Reverse Direction', 'kitify' ),
						'type'         => Controls_Manager::SWITCHER,
						'label_on'     => esc_html__( 'Yes', 'kitify' ),
						'label_off'    => esc_html__( 'No', 'kitify' ),
						'return_value' => 'yes',
						'default'      => '',
						'selectors_dictionary' => array(
							'yes' => 'animation: kitify-move-horizontal-reverse-text-marquee',
							''    => 'animation: kitify-move-horizontal-normal-text-marquee',
						),
						'selectors'            => array(
							'{{WRAPPER}} .kitify-text-marquee__text.text--original' => '{{VALUE}} {{duration.VALUE}}s linear infinite',
							'{{WRAPPER}} .kitify-text-marquee__text.text--clone'     => '{{VALUE}}-clone {{duration.VALUE}}s linear infinite',
						),
				)
		);
		$this->_end_controls_section();
		// Tab style
		$this->_start_controls_section(
				'text_style',
				array(
						'label'      => esc_html__( 'Style', 'kitify' ),
						'tab'        => Controls_Manager::TAB_STYLE,
				)
		);
		$this->_add_control(
				'color',
				[
						'label' => esc_html__( 'Color', 'kitify' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
								'{{WRAPPER}} .kitify-text-marquee__item' => 'color: {{VALUE}};',
						],
				]
		);
		$this->_add_group_control(
				Group_Control_Typography::get_type(),
				[
						'name' => 'typography',
						'label' => esc_html__( 'Typography', 'kitify' ),
						'selector' => '{{WRAPPER}} .kitify-text-marquee__item',
				]
		);
		$this->add_control(
				'text_stroke_effect',
				array(
						'label'        => esc_html__( 'Text Stroke Effect', 'kitify' ),
						'type'         => Controls_Manager::SWITCHER,
						'label_on'     => esc_html__( 'Yes', 'kitify' ),
						'label_off'    => esc_html__( 'No', 'kitify' ),
						'return_value' => 'yes',
						'default'      => '',
				)
		);
		$this->_add_control(
				'text_stroke_color',
				[
						'label' => esc_html__( 'Text Stroke Color', 'kitify' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
								'{{WRAPPER}} .kitify-text-marquee__item' => '-webkit-text-stroke-color: {{VALUE}};',
						],
						'condition' => [
								'text_stroke_effect' => 'yes',
						]
				]
		);
		$this->add_control(
			'text_stroke_width',
			array(
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Text Stroke Width', 'kitify' ),
				'selectors'  => array(
					'{{WRAPPER}} .kitify-text-marquee__item ' => '-webkit-text-stroke-width: {{SIZE}}px;',
				),
				'condition' => array(
					'text_stroke_effect' => 'yes',
				)
			)
		);
		$this->_add_control(
				'icon_color',
				[
						'label' => esc_html__( 'Icon Color', 'kitify' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
								'{{WRAPPER}} .kitify-e-icon-holder' => 'color: {{VALUE}};',
						],
						'separator' => 'before',
				]
		);
		$this->_add_responsive_control(
				'icon_size',
				[
						'label' => esc_html__( 'Icon Size', 'kitify' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
								'px' => [
										'min' => 6,
										'max' => 300,
								],
						],
						'selectors' => [
								'{{WRAPPER}} .kitify-e-icon-holder' => 'font-size: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .kitify-e-icon-holder svg' => 'width: {{SIZE}}{{UNIT}};',
						],
				]
		);
		$this->_add_responsive_control(
				'space_between_items',
				[
						'label' => esc_html__( 'Space Between Items', 'kitify' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
								'{{WRAPPER}} .kitify-text-marquee__item' => 'padding-right: calc({{SIZE}}{{UNIT}}/2); padding-left: calc({{SIZE}}{{UNIT}}/2);',
						],
						'separator' => 'before',
				]
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
