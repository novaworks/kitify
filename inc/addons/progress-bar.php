<?php

/**
 * Class: Kitify_Progress_Bar
 * Name: Progress Bar
 * Slug: kitify-progress-bar
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

// Elementor Classes
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Animated_Box Widget
 */
class Kitify_Progress_Bar extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/progress-bar.css'), null, kitify()->get_version() );
	    wp_register_script( 'kitify-anime-js', kitify()->plugin_url('assets/js/lib/anime.min.js'), [], kitify()->get_version(), true);
	    wp_register_script(  $this->get_name() , kitify()->plugin_url('assets/js/addons/progress-bar.js') , ['kitify-anime-js'],  kitify()->get_version() , true );
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

    public function get_name() {
        return 'kitify-progress-bar';
    }

    protected function get_widget_title() {
        return esc_html__( 'Progress Bar', 'kitify');
    }

    public function get_icon() {
        return 'kitify-icon-skill-bar';
    }

	protected function register_controls() {
		$css_scheme = apply_filters(
			'kitify/progress-bar/css-scheme',
			array(
				'instance'         => '.kitify-progress-bar',
				'title'            => '.kitify-progress-bar__title',
				'title_icon'       => '.kitify-progress-bar__title-icon',
				'title_text'       => '.kitify-progress-bar__title-text',
				'progress_wrapper' => '.kitify-progress-bar__wrapper',
				'status_bar'       => '.kitify-progress-bar__status-bar',
				'endpoint'         => '.kitify-progress-bar__status-bar:after',
				'percent'          => '.kitify-progress-bar__percent',
			)
		);

		$this->start_controls_section(
			'section_progress',
			array(
				'label' => esc_html__( 'Progress Bar', 'kitify' ),
			)
		);

		$this->add_control(
			'progress_type',
			array(
				'label' => esc_html__( 'Type', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'type-1',
				'options' => array(
					'type-1' => esc_html__( 'Inside the bar', 'kitify' ),
					'type-2' => esc_html__( 'Placed bar above ', 'kitify' ),
					'type-2b' => esc_html__( 'Placed title above ', 'kitify' ),
					'type-3' => esc_html__( 'Shown as tip', 'kitify' ),
					'type-4' => esc_html__( 'On the right', 'kitify' ),
					'type-5' => esc_html__( 'Inside the empty bar', 'kitify' ),
					'type-6' => esc_html__( 'Inside the bar with title', 'kitify' ),
					'type-7' => esc_html__( 'Inside the vertical bar', 'kitify' ),
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'kitify' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'kitify' ),
				'default'     => esc_html__( 'Title', 'kitify' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->_add_advanced_icon_control(
			'icon',
			[
				'label'     => esc_html__( 'Icon', 'kitify' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'percent',
			array(
				'label'       => esc_html__( 'Percentage', 'kitify' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 50,
				'min'         => 0,
				'max'         => 100,
				'label_block' => false,
			)
		);

		$this->end_controls_section();

		/**
		 * General Section
		 */
		$this->start_controls_section(
			'section_bar_general',
			array(
				'label'      => esc_html__( 'Wrapper', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'progress_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);
		$this->add_responsive_control(
			'progress_padding',
			array(
				'label'      => esc_html__( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'progress_margin',
			array(
				'label'      => esc_html__( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'progress_border',
				'label'       => esc_html__( 'Border', 'kitify' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_responsive_control(
			'progress_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'progress_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->end_controls_section();

		/**
		 * Progress Bar Style Section
		 */
		$this->start_controls_section(
			'section_progress_style',
			array(
				'label'      => esc_html__( 'Progress Bar', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'progress_wrapper_height',
			array(
				'label'      => esc_html__( 'Progress Height', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .kitify-progress-bar' => '--kitify-progress-bar-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'progress_wrapper_width',
			array(
				'label'      => esc_html__( 'Progress Width', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'condition' => array(
					'progress_type' => array( 'type-7' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .kitify-progress-bar' => '--kitify-progress-bar-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'progress_wrapper_margin',
			array(
				'label'      => esc_html__( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['progress_wrapper'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'progress_wrapper_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['progress_wrapper'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'progress_wrapper_border',
				'label'       => esc_html__( 'Border', 'kitify' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['progress_wrapper'],
			)
		);

		$this->add_responsive_control(
			'progress_wrapper_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['progress_wrapper'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'progress_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['progress_wrapper'],
			)
		);

		$this->add_control(
			'status_bar_heading',
			array(
				'label' => esc_html__( 'Status Bar', 'kitify' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'status_bar_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['status_bar'],
			)
		);

		$this->add_responsive_control(
			'status_bar_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['status_bar'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'endpoint_heading',
			array(
				'label' => esc_html__( 'Status Bar EndPoint', 'kitify' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->_add_control(
			'enable_endpoint',
			[
				'label' => esc_html__( 'Enable', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'kitify' ),
				'label_off' => esc_html__( 'No', 'kitify' ),
				'default' => '',
				'return_value' => 'kitify--progressbar-endpoint',
				'prefix_class' => '',
			]
		);

		$this->add_responsive_control(
			'endpoint_height',
			array(
				'label'      => esc_html__( 'Height', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['endpoint'] => 'height: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'enable_endpoint!' => '',
				),
			)
		);
		$this->add_responsive_control(
			'endpoint_width',
			array(
				'label'      => esc_html__( 'Width', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['endpoint'] => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'enable_endpoint!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'endpoint_border',
				'label'       => esc_html__( 'Border', 'kitify' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['endpoint'],
				'condition' => array(
					'enable_endpoint!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'endpoint_radius',
			array(
				'label'      => __( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['endpoint'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'enable_endpoint!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'endpoint_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['endpoint'],
				'condition' => array(
					'enable_endpoint!' => '',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Title Style Section
		 */
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Title', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'title_alignment',
			array(
				'label'       => esc_html__( 'Title Alignment', 'kitify' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => '',
				'options'     => array(
					'flex-start' => array(
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
					'progress_type' => array( 'type-1', 'type-2', 'type-3', 'type-5' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['title'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'title_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'title_border',
				'label'       => esc_html__( 'Border', 'kitify' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->add_responsive_control(
			'title_border_radius',
			array(
				'label'      => __( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'title_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => __( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->_add_control(
			'icon_block',
			[
				'label' => esc_html__( 'Enable Block', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'kitify' ),
				'label_off' => esc_html__( 'No', 'kitify' ),
				'default' => '',
				'return_value' => 'yes',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'flex-flow: column nowrap;',
				),
			]
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title_icon'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title_icon'] => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title_icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_text_heading',
			array(
				'label'     => esc_html__( 'Text', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'  => esc_html__( 'Text Color', 'kitify' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title_text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title_text'],
			)
		);

		$this->add_responsive_control(
			'text_alignment',
			array(
				'label'       => esc_html__( 'Text Alignment', 'kitify' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => '',
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
					'{{WRAPPER}} '. $css_scheme['title_text'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Percent Style Section
		 */
		$this->start_controls_section(
			'section_percent_style',
			array(
				'label'      => esc_html__( 'Percent', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'percent_width',
			array(
				'label'      => esc_html__( 'Percent Width', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
				),
				'condition' => array(
					'progress_type' => array( 'type-3' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['percent'] => 'width: {{SIZE}}{{UNIT}}; margin-right: calc( {{SIZE}}{{UNIT}}/-2 );',
				),
			)
		);

		$this->add_responsive_control(
			'percent_alignment',
			array(
				'label'       => esc_html__( 'Percent Alignment', 'kitify' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => '',
				'options'     => array(
					'flex-start' => array(
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
					'progress_type' => array( 'type-1' ,'type-2' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['percent'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'percent_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['percent'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'percent_border',
				'label'       => esc_html__( 'Border', 'kitify' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['percent'],
			)
		);

		$this->add_responsive_control(
			'percent_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['percent'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'percent_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['percent'],
			)
		);

		$this->add_responsive_control(
			'percent_margin',
			array(
				'label'      => esc_html__( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['percent'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'percent_padding',
			array(
				'label'      => esc_html__( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['percent'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'percent_color',
			array(
				'label'  => esc_html__( 'Text Color', 'kitify' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['percent'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'percent_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['percent'],
			)
		);

		$this->add_responsive_control(
			'number_suffix_font_size',
			array(
				'label'      => esc_html__( 'Suffix Font Size', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em' ,
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['percent'] . ' .kitify-progress-bar__percent-suffix' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'percent_suffix_alignment',
			array(
				'label'       => esc_html__( 'Percent Suffix Alignment', 'kitify' ),
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
					'{{WRAPPER}} '. $css_scheme['percent'] . ' .kitify-progress-bar__percent-suffix' => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

    public function get_type_template( $type = '' ){
	    $widget_name = str_replace(['kitify-', 'kitify-'], '', $this->get_name());
	    $template = kitify()->get_template($widget_name . '/global/types/' . $type . '.php' );
	    if(!empty($template)){
	    	include $template;
	    }
    }
}
