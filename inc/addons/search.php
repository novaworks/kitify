<?php
/**
 * Class: Kitify_Search
 * Name: Search
 * Slug: kitify-search
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Search extends Kitify_Base {
  protected function enqueue_addon_resources(){

      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/search.css'), ['kitify-base'], kitify()->get_version());
      $this->add_style_depends( 'animate' );
      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends( 'animatedModal' );
      $this->add_script_depends( 'kitify-base' );
  }
	public function get_name() {
		return 'kitify-search';
	}

	public function get_widget_title() {
		return esc_html__( 'Search', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-search';
	}


	protected function register_controls() {

        $this->_start_controls_section(
            'section_search_general_settings',
            array(
                'label' => esc_html__( 'General Settings', 'kitify' ),
            )
        );

        $this->_add_control(
            'search_placeholder',
            array(
                'label'   => esc_html__( 'Search Placeholder', 'kitify' ),
                'default' => esc_html__( 'Search &hellip;', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
            )
        );

        $this->_add_control(
            'show_search_submit',
            array(
                'label'        => esc_html__( 'Show Submit Button', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->_add_control(
            'search_submit_label',
            array(
                'label'     => esc_html__( 'Submit Button Label', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => array(
                    'show_search_submit' => 'true',
                ),
            )
        );

        $this->_add_advanced_icon_control(
            'search_submit_icon',
            array(
                'label'     => esc_html__( 'Submit Button Icon', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => false,
                'file'        => '',
                'skin'        => 'inline',
                'default'     => 'novaicon-zoom-1',
                'fa5_default' => array(
                    'value'   => 'novaicon-zoom-1',
                    'library' => 'novaicon',
                ),
                'condition' => array(
                    'show_search_submit' => 'true',
                ),
            )
        );

        $this->_add_control(
            'show_search_in_popup',
            array(
                'label'        => esc_html__( 'Show Search Form in Popup', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->_add_advanced_icon_control(
            'search_popup_trigger_icon',
            array(
                'label'       => esc_html__( 'Popup Trigger Icon', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => false,
                'file'        => '',
                'skin'        => 'inline',
                'default'     => 'dlicon ui-1_zoom',
                'fa5_default' => array(
                    'value'   => 'dlicon ui-1_zoom',
                    'library' => 'dlicon',
                ),
                'condition' => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_control(
            'is_product_search',
            array(
                'label'        => esc_html__( 'Is Product Search', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => '',
                'separator'    => 'before',
            )
        );

        $this->end_controls_section();

        $css_scheme = apply_filters(
            'kitify/search/css-scheme',
            array(
                'form'                    => '.kitify-search__form',
                'form_input'              => '.kitify-search__field',
                'form_submit'             => '.kitify-search__submit',
                'form_submit_icon'        => '.kitify-search__submit-icon',
                'popup'                   => '.kitify-search__popup',
                'popup_full_screen'       => '.kitify-search__popup--full-screen',
                'popup_content'           => '.kitify-search__popup-content',
                'popup_close'             => '.kitify-search__popup-close',
                'popup_close_icon'        => '.kitify-search__popup-close-icon',
                'popup_trigger_container' => '.kitify-search__popup-trigger-container',
                'popup_trigger'           => '.kitify-search__popup-trigger #js_header_search_modal',
                'popup_trigger_icon'      => '.kitify-search__popup-trigger-icon',
            )
        );

        $this->_start_controls_section(
            'section_form_style',
            array(
                'label' => esc_html__( 'Form', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'form_input_style',
            array(
                'label'     => esc_html__( 'Input Field', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
            ),
            25
        );

		$this->_add_control(
			'form_input_align',
			[
				'label' => __( 'Text Alignment', 'kitify' ),
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
					'{{WRAPPER}} ' . $css_scheme['form_input'] => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'form_input_typography',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['form_input'],
            ),
            50
        );

        $this->_start_controls_tabs( 'form_input_tabs' );

        $this->_start_controls_tab(
            'form_input_tab_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'form_input_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_placeholder_color',
            array(
                'label'  => esc_html__( 'Placeholder Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . '::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . '::-moz-placeholder'          => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':-ms-input-placeholder'      => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'form_input_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form_input'],
            ),
            100
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'form_input_tab_focus',
            array(
                'label' => esc_html__( 'Focus', 'kitify' ),
            )
        );

        $this->_add_control(
            'form_input_bg_color_focus',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_color_focus',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_placeholder_color_focus',
            array(
                'label'  => esc_html__( 'Placeholder Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus::-moz-placeholder'          => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus:-ms-input-placeholder'      => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_border_color_focus',
            array(
                'label'  => esc_html__( 'Border Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'border-color: {{VALUE}}',
                ),
                'condition' => array(
                    'form_input_border_border!' => '',
                ),
            ),75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'form_input_box_shadow_focus',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus',
            ),
            100
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'form_input_height',
            array(
                'label'      => esc_html__( 'Height', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] => 'height: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );
        $this->_add_responsive_control(
            'form_input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_input'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'form_input_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_input'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'form_input_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['form_input'],
            ),
            75
        );

        $this->_add_responsive_control(
            'form_input_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'form_submit_style',
            array(
                'label'     => esc_html__( 'Submit Button', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'form_submit_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form_submit'],
            ),
            50
        );

        $this->_add_responsive_control(
            'form_submit_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_form_submit_style' );

        $this->_start_controls_tab(
            'tab_form_submit_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'form_submit_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_submit_color',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_form_submit_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'form_submit_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_submit_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_submit_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'form_submit_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'form_submit_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_submit'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'form_submit_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_submit'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'form_submit_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['form_submit'],
            ),
            75
        );

        $this->_add_responsive_control(
            'form_submit_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'form_submit_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form_submit'],
            ),
            100
        );

        $this->_add_control(
            'form_style',
            array(
                'label'     => esc_html__( 'Form Style', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),25
        );

        $this->_add_control(
            'form_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'form_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'form_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'form_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['form'],
            ),
            75
        );

        $this->_add_responsive_control(
            'form_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'form_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form'],
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_popup_trigger_style',
            array(
                'label'      => esc_html__( 'Popup Trigger', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_responsive_control(
            'popup_trigger_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_popup_trigger_style' );

        $this->_start_controls_tab(
            'tab_popup_trigger_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'popup_trigger_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_trigger_color',
            array(
                'label'  => esc_html__( 'Icon Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'].' i' => 'color: {{VALUE}}',
                ),
            ),
            25
        );
        $this->_add_control(
            'popup_trigger_label_color',
            array(
                'label'  => esc_html__( 'Label Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger_container'].' .kitify-search__trigger-label' => 'color: {{VALUE}}',
                ),
            ),
            25
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'popup_trigger_label_typography',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['popup_trigger_container']. ' .kitify-search__trigger-label',
            ),
            50
        );
        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_popup_trigger_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'popup_trigger_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_trigger_color_hover',
            array(
                'label'  => esc_html__( 'Icon Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ' i:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger_container'] . ' #js_header_search_modal:hover i' => 'color: {{VALUE}}',
                ),
            ),
            25
        );
        $this->_add_control(
            'popup_trigger_label_color_hover',
            array(
                'label'  => esc_html__( 'Label Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger_container'] . ' .kitify-search__trigger-label:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger_container'] . ' > a:hover .kitify-search__trigger-label' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_trigger_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'popup_trigger_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ':hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'popup_trigger_label_space',
            array(
                'label'      => esc_html__( 'Label space', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 20,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger_container'] . ' .kitify-search__trigger-label' => 'padding-left: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            50
        );
        $this->_add_responsive_control(
            'popup_trigger_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
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
                'selectors' => array(
                    '{{WRAPPER}} '  . $css_scheme['popup_trigger_container'] => 'justify-content: {{VALUE}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_trigger_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['popup_trigger'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_trigger_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['popup_trigger'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'popup_trigger_border',
                'label'          => esc_html__( 'Border', 'kitify' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['popup_trigger'],
            ),
            75
        );

        $this->_add_responsive_control(
            'popup_trigger_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'popup_trigger_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['popup_trigger'],
            ),
            100
        );

        $this->_end_controls_section();


	}

	protected function render() {

		$this->_context = 'render';
    $settings = $this->get_settings();
    if ( 'true' === $settings['show_search_in_popup'] ) {
      add_action('kitify/theme/canvas_panel', [ $this, 'add_panel' ] );
    }
		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}
  public function add_panel() {
    include $this->_get_global_template( 'panel' );
  }

}
