<?php
/**
 * Class: Kitify_Text_List
 * Name: Text List
 * Slug: kitify-text-list
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Text_List extends Kitify_Base {

	public function get_name() {
		return 'kitify-text-list';
	}

	public function get_title() {
		return esc_html__( 'Kitify Text List', 'kitify' );
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
				'title',
				[
						'label' => __( 'Title', 'kitify' ),
						'type' => Controls_Manager::TEXT,
						'default' => __( 'Title #1', 'kitify' ),
						'dynamic' => array( 'active' => true ),
				]
		);
		$repeater->add_control(
				'value',
				[
						'label' => __( 'Value', 'kitify' ),
						'type' => Controls_Manager::TEXT,
						'default' => __( 'Value #1', 'kitify' ),
						'dynamic' => array( 'active' => true ),
				]
		);

		$this->add_control(
				'items',
				[
						'label' => __( 'Items', 'kitify' ),
						'type' => Controls_Manager::REPEATER,
						'show_label' => true,
						'fields' => $repeater->get_controls(),
						'title_field' => '{{{ title }}}',
						'default' => [
								[
										'title' => __( 'Title #1', 'kitify' ),
										'value' => __( 'Value #1', 'kitify' ),
								],
								[
										'title' => __( 'Title #2', 'kitify' ),
										'value' => __( 'Value #2', 'kitify' ),
								],
								[
										'title' => __( 'Title #3', 'kitify' ),
										'value' => __( 'Value #3', 'kitify' ),
								],
						],
				]
		);
		$this->_end_controls_section();
		// Tab style
		$this->_start_controls_section(
				'item_style',
				array(
						'label'      => esc_html__( 'List item', 'kitify' ),
						'tab'        => Controls_Manager::TAB_STYLE,
				)
		);
		$this->_add_responsive_control(
				'item_margin',
				[
						'label' => esc_html__( 'Margin', 'kitify' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
								'{{WRAPPER}} .kitify-text-list ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
				]
		);
		$this->_end_controls_section();
		$this->_start_controls_section(
				'title_style',
				array(
						'label'      => esc_html__( 'Title', 'kitify' ),
						'tab'        => Controls_Manager::TAB_STYLE,
				)
		);
		$this->_add_control(
				'title_color',
				[
						'label' => esc_html__( 'Color', 'kitify' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
								'{{WRAPPER}} .kitify-text-list .kitify-text-list__title' => 'color: {{VALUE}};',
						],
				]
		);
		$this->_add_group_control(
				Group_Control_Typography::get_type(),
				[
						'name' => 'title_typography',
						'label' => esc_html__( 'Typography', 'kitify' ),
						'selector' => '{{WRAPPER}} .kitify-text-list .kitify-text-list__title',
				]
		);
		$this->add_responsive_control(
				'title_align',
				[
						'label' => __( 'Alignment', 'kitify' ),
						'type' => Controls_Manager::CHOOSE,
						'options' => [
								'left' => [
										'title' => __( 'Left', 'kitify' ),
										'icon' => 'eicon-text-align-left',
								],
								'center' => [
										'title' => __( 'Center', 'kitify' ),
										'icon' => 'eicon-text-align-center',
								],
								'right' => [
										'title' => __( 'Right', 'kitify' ),
										'icon' => 'eicon-text-align-right',
								],
								'justify' => [
										'title' => __( 'Justified', 'kitify' ),
										'icon' => 'eicon-text-align-justify',
								],
						],
						'default' => '',
						'selectors' => [
								'{{WRAPPER}} .kitify-text-list .kitify-text-list__title' => 'text-align: {{VALUE}};',
						],
				]
		);
		$this->_end_controls_section();
		$this->_start_controls_section(
				'value_style',
				array(
						'label'      => esc_html__( 'Value', 'kitify' ),
						'tab'        => Controls_Manager::TAB_STYLE,
				)
		);
		$this->_add_control(
				'value_color',
				[
						'label' => esc_html__( 'Color', 'kitify' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
								'{{WRAPPER}} .kitify-text-list .kitify-text-list__value' => 'color: {{VALUE}};',
						],
				]
		);
		$this->_add_group_control(
				Group_Control_Typography::get_type(),
				[
						'name' => 'value_typography',
						'label' => esc_html__( 'Typography', 'kitify' ),
						'selector' => '{{WRAPPER}} .kitify-text-list .kitify-text-list__value',
				]
		);
		$this->add_responsive_control(
				'value_align',
				[
						'label' => __( 'Alignment', 'kitify' ),
						'type' => Controls_Manager::CHOOSE,
						'options' => [
								'left' => [
										'title' => __( 'Left', 'kitify' ),
										'icon' => 'eicon-text-align-left',
								],
								'center' => [
										'title' => __( 'Center', 'kitify' ),
										'icon' => 'eicon-text-align-center',
								],
								'right' => [
										'title' => __( 'Right', 'kitify' ),
										'icon' => 'eicon-text-align-right',
								],
								'justify' => [
										'title' => __( 'Justified', 'kitify' ),
										'icon' => 'eicon-text-align-justify',
								],
						],
						'default' => '',
						'selectors' => [
								'{{WRAPPER}} .kitify-text-list .kitify-text-list__value' => 'text-align: {{VALUE}};',
						],
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
