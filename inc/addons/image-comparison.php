<?php
/**
 * Class: Kitify_Image_Comparison
 * Name: Image Comparison
 * Slug: kitify-image-comparison
 */
 namespace Elementor;

 if (!defined('WPINC')) {
     die;
 }
class Kitify_Image_Comparison extends Kitify_Base {
	public function get_name() {
		return 'kitify-image-comparison';
	}

	public function get_title() {
		return esc_html__( 'Image Comparison', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-image-comparison';
	}

	public function get_script_depends() {
		return array(
			'kitify-slick',
			'kitify-juxtapose',
		);
	}

	public function get_style_depends() {
		return array( 'kitify-juxtapose-css' );
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'kitify/image-comparison/css-scheme',
			array(
				'instance'         => '.kitify-image-comparison__instance',
				'jx_instance'      => '.jx-slider',
				'before_container' => '.jx-left',
				'before_label'     => '.jx-left .jx-label',
				'after_container'  => '.jx-right',
				'after_label'      => '.jx-right .jx-label',
				'handle'           => '.jx-handle',
				'arrow'            => '.kitify-arrow',
				'dots'             => '.kitify-slick-dots',
			)
		);

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Items', 'kitify' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_before_label',
			array(
				'label'   => esc_html__( 'Before Label', 'kitify' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_before_image',
			array(
				'label'   => esc_html__( 'Before Image', 'kitify' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'item_after_label',
			array(
				'label'   => esc_html__( 'After Label', 'kitify' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_after_image',
			array(
				'label'   => esc_html__( 'After Image', 'kitify' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'item_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_before_label' => esc_html__( 'Before', 'kitify' ),
						'item_before_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_after_label' => esc_html__( 'After', 'kitify' ),
						'item_after_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
					array(
						'item_before_label' => esc_html__( 'Before', 'kitify' ),
						'item_before_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_after_label' => esc_html__( 'After', 'kitify' ),
						'item_after_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
				),
				'title_field' => '{{{ item_before_label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'kitify' ),
			)
		);

		$this->add_control(
			'handler_settings_heading',
			array(
				'label'     => esc_html__( 'Handler Settings', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'starting_position',
			array(
				'label'      => esc_html__( 'Divider Starting Position', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 50,
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'handle_prev_arrow',
			array(
				'label'       => esc_html__( 'Prev Arrow Icon', 'kitify' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'fa fa-angle-left',
				'render_type' => 'template',
				'options'     => kitify_tools()->get_available_prev_arrows_list(),
			)
		);

		$this->add_control(
			'handle_next_arrow',
			array(
				'label'       => esc_html__( 'Next Arrow Icon', 'kitify' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'fa fa-angle-right',
				'render_type' => 'template',
				'options'     => kitify_tools()->get_available_next_arrows_list(),
			)
		);

		$this->add_control(
			'carousel_settings_heading',
			array(
				'label'     => esc_html__( 'Carousel Settings', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'              => esc_html__( 'Slides to Show', 'kitify' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '1',
				'options'            => kitify_tools()->get_select_range( 10 ),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'slides_to_scroll',
			array(
				'label'     => esc_html__( 'Slides to Scroll', 'kitify' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => kitify_tools()->get_select_range( 10 ),
				'condition' => array(
					'slides_to_show!' => '1',
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => esc_html__( 'Pause on Hover', 'kitify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'kitify' ),
				'label_off'    => esc_html__( 'No', 'kitify' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'kitify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'kitify' ),
				'label_off'    => esc_html__( 'No', 'kitify' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed', 'kitify' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'autoplay' => 'true',
				),
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'   => esc_html__( 'Effect', 'kitify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => array(
					'slide' => esc_html__( 'Slide', 'kitify' ),
					'fade'  => esc_html__( 'Fade', 'kitify' ),
				),
				'condition' => array(
					'slides_to_show' => '1',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => esc_html__( 'Animation Speed', 'kitify' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'        => esc_html__( 'Show Arrows Navigation', 'kitify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'kitify' ),
				'label_off'    => esc_html__( 'No', 'kitify' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->_add_advanced_icon_control(
			'prev_arrow',
			array(
				'label'       => esc_html__( 'Prev Arrow Icon', 'kitify' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-angle-left',
				'fa5_default' => array(
					'value'   => 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'arrows' => 'true',
				),
			)
		);

		$this->_add_advanced_icon_control(
			'next_arrow',
			array(
				'label'       => esc_html__( 'Next Arrow Icon', 'kitify' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-angle-right',
				'fa5_default' => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'arrows' => 'true',
				),
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'        => esc_html__( 'Show Dots Navigation', 'kitify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'kitify' ),
				'label_off'    => esc_html__( 'No', 'kitify' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->end_controls_section();

		/**
		 * General Style Section
		 */
		$this->_start_controls_section(
			'section_services_general_style',
			array(
				'label'      => esc_html__( 'General', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			),
			100
		);

		$this->_add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'container_border',
				'label'       => esc_html__( 'Border', 'kitify' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->_add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .slick-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'container_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->_end_controls_section(100);

		/**
		 * Label Style Section
		 */
		$this->_start_controls_section(
			'section_image_comparison_label_style',
			array(
				'label'      => esc_html__( 'Label', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'tabs_label_styles' );

		$this->_start_controls_tab(
			'tab_label_before',
			array(
				'label' => esc_html__( 'Before', 'kitify' ),
			)
		);

		$this->_add_control(
			'before_label_horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Alignment', 'kitify' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'kitify' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'kitify' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'kitify' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['before_container'] => 'justify-content: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'before_label_vertical_alignment',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'kitify' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
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
					'{{WRAPPER}} '. $css_scheme['before_container'] => 'align-items: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'before_label_color',
			array(
				'label' => esc_html__( 'Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['before_label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'before_label_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['before_label'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'before_label_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['before_label'],
			),
			25
		);

		$this->_add_responsive_control(
			'before_label_margin',
			array(
				'label'      => __( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['before_label'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'before_label_padding',
			array(
				'label'      => __( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['before_label'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_label_after',
			array(
				'label' => esc_html__( 'After', 'kitify' ),
			)
		);

		$this->_add_control(
			'after_label_horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Alignment', 'kitify' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-end',
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'kitify' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'kitify' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'kitify' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['after_container'] => 'justify-content: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'after_label_vertical_alignment',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'kitify' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
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
					'{{WRAPPER}} '. $css_scheme['after_container'] => 'align-items: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'after_label_color',
			array(
				'label' => esc_html__( 'Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['after_label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'after_label_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['after_label'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'after_label_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['after_label'],
			),
			25
		);

		$this->_add_responsive_control(
			'after_label_margin',
			array(
				'label'      => __( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['after_label'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'after_label_padding',
			array(
				'label'      => __( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['after_label'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Handle Style Section
		 */
		$this->_start_controls_section(
			'section_image_comparison_handle_style',
			array(
				'label'      => esc_html__( 'Handle', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'handle_control_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'kitify' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => array(
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
					'{{WRAPPER}} ' . $css_scheme['jx_instance'] . ' .jx-controller' => 'align-self: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'handle_control_width',
			array(
				'label'      => esc_html__( 'Control Width', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['handle']                     => 'width: {{SIZE}}{{UNIT}}; margin-left: calc( {{SIZE}}{{UNIT}} / -2 );',
					'{{WRAPPER}} ' . $css_scheme['handle'] . ' .jx-control'    => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['handle'] . ' .jx-controller' => 'width: {{SIZE}}{{UNIT}};',
				)
			),
			50
		);

		$this->_add_responsive_control(
			'handle_control_height',
			array(
				'label'      => esc_html__( 'Height', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['handle'] . ' .jx-controller' => 'height: {{SIZE}}{{UNIT}};',
				)
			),
			50
		);

		$this->_add_responsive_control(
			'handle_divider_margin',
			array(
				'label'      => __( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['handle'] . ' .jx-controller' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			100
		);

		$this->_add_responsive_control(
			'handle_divider_radius',
			array(
				'label'      => __( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['handle'] . ' .jx-controller' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_start_controls_tabs( 'tabs_handle_styles' );

		$this->_start_controls_tab(
			'tab_handle_normal',
			array(
				'label' => esc_html__( 'Normal', 'kitify' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'handle_control_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['jx_instance'] . ' .jx-controller',
			),
			25
		);

		$this->_add_control(
			'handle_arrow_color',
			array(
				'label' => esc_html__( 'Arrow Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['jx_instance'] . ' .jx-controller i' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'handle_control_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['jx_instance'] . ' .jx-controller',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_handle_hover',
			array(
				'label' => esc_html__( 'Hover', 'kitify' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'handle_control_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['jx_instance'] . ':hover .jx-controller',
			),
			25
		);

		$this->_add_control(
			'handle_arrow_color_hover',
			array(
				'label' => esc_html__( 'Arrow Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['jx_instance'] . ':hover .jx-controller i' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'handle_control_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['jx_instance'] . ':hover .jx-controller',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_control(
			'heading_handle_divider_style',
			array(
				'label'     => esc_html__( 'Handle Divider', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'handle_divider_width',
			array(
				'label'      => esc_html__( 'Divider Width', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['handle'] . ' .jx-control:before' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc( {{SIZE}}{{UNIT}}/-2);',
				)
			),
			50
		);

		$this->_add_control(
			'handle_divider_color',
			array(
				'label'   => esc_html__( 'Divider Color', 'kitify' ),
				'type'    => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['handle'] . ' .jx-control:before' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'heading_handle_arrow_style',
			array(
				'label'     => esc_html__( 'Handle Arrow', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			50
		);

		$this->_add_responsive_control(
			'handle_arrow_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'kitify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em'
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['jx_instance'] . ' .jx-controller i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'handle_arrow_margin',
			array(
				'label'      => __( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['handle'] . ' .jx-controller i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		/*
		 * Arrows section
		 */
     $this->register_carousel_arrows_dots_style_section( [ 'enable_carousel' => 'yes' ] );

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();

		include $this->_get_global_template( 'index' );

		$this->_close_wrap();
	}

	/**
	 * Generate setting json
	 *
	 * @return string
	 */
	public function generate_setting_json() {
		$settings = $this->get_settings();
		$widget_id = $this->get_id();

		$instance_settings = array(
			'slidesToShow'   => array(
				'desktop' => absint( $settings['slides_to_show'] )
			),
			'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
			'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
			'pauseOnHover'   => filter_var( $settings['pause_on_hover'], FILTER_VALIDATE_BOOLEAN ),
			'speed'          => absint( $settings['speed'] ),
			'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
			'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
			'slidesToScroll' => absint( $settings['slides_to_scroll'] ),
			'prevArrow'      => '.kitify-image-comparison__prev-arrow-' . $widget_id,
			'nextArrow'      => '.kitify-image-comparison__next-arrow-' . $widget_id,
			'rtl' => is_rtl(),
		);

		if ( 'fade' === $settings['effect'] ) {
			$instance_settings['fade'] = true;
		}

		$instance_settings = json_encode( $instance_settings );

		return sprintf( 'data-settings=\'%1$s\'', $instance_settings );
	}
}
