<?php

/**
 * Class: Kitify_Tabs
 * Name: Tabs
 * Slug: kitify-tabs
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use KitifyExtensions\Elementor\Controls\Control_Query as QueryControlModule;
use Elementor\Core\Base\Document;

/**
 * Tabs Widget
 */
class Kitify_Tabs extends Kitify_Base {

    protected function enqueue_addon_resources(){
	    if(!kitify_settings()->is_combine_js_css()) {
		    wp_register_script( $this->get_name(), kitify()->plugin_url( 'assets/js/addons/tabs.js' ), [ 'kitify-base' ], kitify()->get_version(), true );
		    wp_register_style( $this->get_name(), kitify()->plugin_url( 'assets/css/addons/tabs.css' ), [ 'kitify-base' ], kitify()->get_version() );

		    $this->add_style_depends( $this->get_name() );
		    $this->add_script_depends( $this->get_name() );
	    }
    }

    public function get_name() {
        return 'kitify-tabs';
    }

    protected function get_widget_title() {
        return esc_html__( 'Tabs', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-tabs';
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'kitify/tabs/css-scheme',
            array(
                'instance'        => '> .elementor-widget-container > .kitify-tabs',
                'control_wrapper' => '> .elementor-widget-container > .kitify-tabs > .kitify-tabs__control-wrapper',
                'control'         => '> .elementor-widget-container > .kitify-tabs > .kitify-tabs__control-wrapper .kitify-tabs__control',
                'content_wrapper' => '> .elementor-widget-container > .kitify-tabs > .kitify-tabs__content-wrapper',
                'content'         => '> .elementor-widget-container > .kitify-tabs > .kitify-tabs__content-wrapper .kitify-tabs__content',
                'label'           => '.kitify-tabs__label-text',
                'sublabel'        => '.kitify-tabs__sublabel-text',
                'icon'            => '.kitify-tabs__label-icon',
                'dropdown'        => '> .elementor-widget-container > .kitify-tabs > .kitify-tabs__control-wrapper .kitify-tabs__controls',
                'dropdown_toggle' => '> .elementor-widget-container > .kitify-tabs > .kitify-tabs__control-wrapper .kitify-tabs__controls__tmp',
                'dropdown_intro'  => '> .elementor-widget-container > .kitify-tabs > .kitify-tabs__control-wrapper .intro-text',
            )
        );
        $preset_type = apply_filters(
          'kitify/'.$this->get_kitify_name().'/control/preset',
          array(
            'default' => esc_html__( 'Default', 'kitify' ),
          )
        );
        $this->_start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'kitify' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_active',
            array(
                'label'        => esc_html__( 'Active', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $repeater->add_control(
            'item_use_image',
            array(
                'label'        => esc_html__( 'Use Image?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $repeater->add_control(
            'item_icon',
            array(
                'label'       => esc_html__( 'Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon'
            )
        );

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Image', 'kitify' ),
                'type'    => Controls_Manager::MEDIA,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'item_use_image',
                            'operator' => '==',
                            'value' => 'yes',
                        ],
                    ],
                ],
            )
        );

        $repeater->add_control(
            'item_label',
            array(
                'label'   => esc_html__( 'Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'New Tab', 'kitify' ),
                'dynamic' => [
                    'active' => true,
                ],
            )
        );

        $repeater->add_control(
            'item_sublabel',
            array(
                'label'   => esc_html__( 'Sub Label', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            )
        );

        $repeater->add_control(
            'content_type',
            [
                'label'       => esc_html__( 'Content Type', 'kitify' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'editor',
                'options'     => [
                    'template' => esc_html__( 'Template', 'kitify' ),
                    'editor'   => esc_html__( 'Editor', 'kitify' ),
                ],
                'label_block' => 'true',
            ]
        );

        $repeater->add_control(
            'item_template_id',
            [
                'label'       => esc_html__( 'Choose Template', 'kitify' ),
                'label_block' => 'true',
                'type'        => QueryControlModule::QUERY_CONTROL_ID,
                'autocomplete' => [
                    'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
                    'query' => [
                        'posts_per_page' => -1,
                        'post_status' => [ 'publish', 'private' ],
                        'meta_query' => [
                            [
                                'key' => Document::TYPE_META_KEY,
                                'value' => ['section', 'container'],
                                'compare' => 'IN'
                            ],
                        ],
                    ],
                ],
                'condition'   => array(
                    'content_type' => 'template',
                ),
            ]
        );

        $repeater->add_control(
            'item_enable_ajax',
            array(
                'label'        => esc_html__( 'Enable Ajax Load', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'false',
                'condition'   => array(
                    'content_type' => 'template',
                ),
            )
        );

        $repeater->add_control(
            'item_editor_content',
            [
                'label'      => __( 'Content', 'kitify' ),
                'type'       => Controls_Manager::WYSIWYG,
                'default'    => __( 'Tab Item Content', 'kitify' ),
                'dynamic' => [
                    'active' => true,
                ],
                'condition'   => [
                    'content_type' => 'editor',
                ]
            ]
        );

        $this->_add_control(
            'tabs',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_label'  => esc_html__( 'Tab #1', 'kitify' ),
                    ),
                    array(
                        'item_label'  => esc_html__( 'Tab #2', 'kitify' ),
                    ),
                    array(
                        'item_label'  => esc_html__( 'Tab #3', 'kitify' ),
                    ),
                ),
                'title_field' => '{{{ item_label }}}',
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_settings_data',
            array(
                'label' => esc_html__( 'Settings', 'kitify' ),
            )
        );

        $this->_add_control(
            'tab_as_dropdown',
            array(
                'label'        => esc_html__( 'Tabs as dropdown', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'no', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );
        $this->_add_control(
            'tab_text_intro',
            array(
                'label'   => esc_html__( 'Intro Text', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'condition'    => [
                    'tab_as_dropdown' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'transfer_to_select_tb',
            array(
                'label'        => esc_html__( 'Is dropdown controls on tablet portrait?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'kitify' ),
                'label_off'    => esc_html__( 'Off', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'false',
                'prefix_class' => 'mttabcontrolisselect-',
                'condition'    => [
                    'tab_as_dropdown' => ''
                ]
            )
        );
        $this->_add_control(
            'transfer_to_select',
            array(
                'label'        => esc_html__( 'Is dropdown controls on mobile?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'kitify' ),
                'label_off'    => esc_html__( 'Off', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'false',
                'prefix_class' => 'mbtabcontrolisselect-',
                'condition'    => [
                    'tab_as_dropdown' => ''
                ]
            )
        );

        $this->_add_control(
            'show_effect',
            array(
                'label'       => esc_html__( 'Show Effect', 'kitify' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'move-up',
                'options' => array(
                    'none'             => esc_html__( 'None', 'kitify' ),
                    'fade'             => esc_html__( 'Fade', 'kitify' ),
                    //'column-fade'      => esc_html__( 'Column Fade', 'kitify' ),
                    'zoom-in'          => esc_html__( 'Zoom In', 'kitify' ),
                    'zoom-out'         => esc_html__( 'Zoom Out', 'kitify' ),
                    'move-up'          => esc_html__( 'Move Up', 'kitify' ),
                    //'column-move-up'   => esc_html__( 'Column Move Up', 'kitify' ),
                    'fall-perspective' => esc_html__( 'Fall Perspective', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'tabs_event',
            array(
                'label'   => esc_html__( 'Tabs Event', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'click',
                'options' => array(
                    'click' => esc_html__( 'Click', 'kitify' ),
                    'hover' => esc_html__( 'Hover', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'auto_switch',
            array(
                'label'        => esc_html__( 'Auto Switch', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'kitify' ),
                'label_off'    => esc_html__( 'Off', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->_add_control(
            'auto_switch_delay',
            array(
                'label'   => esc_html__( 'Auto Switch Delay', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3000,
                'min'     => 1000,
                'max'     => 20000,
                'step'    => 100,
                'condition' => array(
                    'auto_switch' => 'yes',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_general_style',
            array(
                'label'      => esc_html__( 'General', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->add_control(
            'tabs_style',
            array(
                'label'     => esc_html__( 'Style', 'kitify' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'default',
                'options'   => $preset_type
            )
        );
        $this->_add_responsive_control(
            'tabs_position',
            array(
                'label'   => esc_html__( 'Tabs Position', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'kitify' ),
                    'top'   => esc_html__( 'Top', 'kitify' ),
                    'right' => esc_html__( 'Right', 'kitify' ),
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_width',
            array(
                'label'      => esc_html__( 'Tab Control Width', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%',
                ),
                'range'      => array(
                    '%' => array(
                        'min' => 10,
                        'max' => 50,
                    ),
                    'px' => array(
                        'min' => 100,
                        'max' => 500,
                    ),
                ),
                'condition' => array(
                    'tabs_position' => array( 'left', 'right' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'min-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} ' . $css_scheme['content_wrapper'] => 'min-width: calc(100% - {{SIZE}}{{UNIT}})',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_container_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
            )
        );

        $this->_add_responsive_control(
            'tabs_container_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_container_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_container_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
            )
        );

        $this->_add_responsive_control(
            'tabs_container_border_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_container_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
            )
        );

        $this->_end_controls_section();

        /**
         * Tab Control Style Section
         */
        $this->_start_controls_section(
            'section_tabs_control_style',
            array(
                'label'      => esc_html__( 'Tab Control', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_responsive_control(
            'tabs_full',
            array(
                'label'        => esc_html__( '100% Height/Width', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'kitify' ),
                    'no' => esc_html__( 'No', 'kitify' ),
                ),
                'prefix_class' => 'kitify_tab-full%s-',
            )
        );

        $this->_add_responsive_control(
            'tabs_controls_aligment',
            array(
                'label'   => esc_html__( 'Horizontal Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
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
                'selectors'   => [
                    '{{WRAPPER}}' => '--kitify-tabs-h-align: {{VALUE}}'
                ]
            )
        );

        $this->_add_responsive_control(
            'tabs_controls_v_alignment',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Middle', 'kitify' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors'   => [
                    '{{WRAPPER}}' => '--kitify-tabs-v-align: {{VALUE}}'
                ]
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_content_wrapper_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control_wrapper'],
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_wrapper_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['control_wrapper'],
            )
        );

        $this->_add_responsive_control(
            'tabs_control_wrapper_border_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control_wrapper'],
            )
        );

        $this->_end_controls_section();

        /**
         * Tab Control Style Section
         */
        $this->_start_controls_section(
            'section_tabs_dd_style',
            array(
                'label'      => esc_html__( 'Dropdown Controls', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => [
                    'tab_as_dropdown' => 'yes'
                ]
            )
        );

        $this->add_control(
            'dd_heading0',
            [
                'label' => esc_html__( 'Intro Text', 'kitify' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'dd_intro_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['dropdown_intro'] => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dd_intro_font',
                'selector' =>  '{{WRAPPER}} ' . $css_scheme['dropdown_intro']
            ]
        );

        $this->_add_responsive_control(
            'dd_intro_padding',
            array(
                'label'      => __( 'Intro Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['dropdown_intro'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'dd_intro_margin',
            array(
                'label'      => __( 'Intro Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['dropdown_intro'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'dd_heading1',
            [
                'label' => esc_html__( 'Toggle', 'kitify' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->_add_control(
            'dd_toggle_custom_width',
            array(
                'label'        => esc_html__( 'Custom width ?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'no', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
                'prefix_class' => 'dd-custom-width-',
                'condition'  => [
                    'tab_as_dropdown' => 'yes'
                ]
            )
        );

        $this->add_responsive_control(
            'dd_toggle_width',
            [
                'label' => esc_html__( 'Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', '%', 'vh', 'vw'),
                'selectors' => [
                    '{{WRAPPER}}' => '--kitify-dd-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'tab_as_dropdown' => 'yes',
                    'dd_toggle_custom_width' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'dd_toggle_color',
            [
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['dropdown_toggle'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dd_font',
                'selector' => '{{WRAPPER}} ' .$css_scheme['dropdown_toggle'],
            ]
        );

        $this->_add_icon_control(
            'dd_icon',
            [
                'label'       => esc_html__( 'Dropdown Icon', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'file'        => '',
                'skin'        => 'inline',
                'label_block' => false
            ]
        );

        $this->add_responsive_control(
            'dd_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['dropdown_toggle'] . ' .dd-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'dd_icon_padding',
            [
                'label' => esc_html__( 'Icon padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['dropdown_toggle'] . ' .dd-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'dd_toggle_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['dropdown_toggle'],
            )
        );
        $this->_add_responsive_control(
            'dd_toggle_padding',
            array(
                'label'      => __( 'Toggle Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['dropdown_toggle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'dd_toggle_border',
                'label'       => esc_html__( 'Toggle rorder', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['dropdown_toggle'],
            )
        );
        $this->_add_responsive_control(
            'dd_toggle_radius',
            array(
                'label'      => __( 'Toggle border radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['dropdown_toggle'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'dd_toggle_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['dropdown_toggle'],
            )
        );

        $this->add_control(
            'dd_heading2',
            [
                'label' => esc_html__( 'Dropdown', 'kitify' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'dd_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['dropdown'],
            )
        );
        $this->_add_responsive_control(
            'dd_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['dropdown'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'dd_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['dropdown'],
            )
        );
        $this->_add_responsive_control(
            'dd_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['dropdown'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'dd_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['dropdown'],
            )
        );

        $this->_end_controls_section();

        /**
         * Tab Control Style Section
         */
        $this->_start_controls_section(
            'section_tabs_control_item_style',
            array(
                'label'      => esc_html__( 'Tab Control Item', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_responsive_control(
            'tabs_controls_item_aligment_top_icon',
            array(
                'label'   => esc_html__( 'Item Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
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
                    'tabs_control_icon_position' => ['top', 'bottom']
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' .kitify-tabs__control-inner' => 'align-items: {{VALUE}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_controls_item_aligment_left_icon',
            array(
                'label'   => esc_html__( 'Item Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
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
                    'space-between' => array(
                        'title' => esc_html__( 'Stretch', 'kitify' ),
                        'icon'  => 'eicon-align-stretch-h',
                    ),
                ),
                'condition' => array(
                    'tabs_control_icon_position' => ['left', 'right']
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' .kitify-tabs__control-inner' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_item_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' .kitify-tabs__control-inner' => 'text-align: {{VALUE}};',
                )
            )
        );

        $this->_add_responsive_control(
            'tabs_control_item_width',
            array(
                'label'      => esc_html__( 'Item Width', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', '%', 'vh', 'vw'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_control(
            'tabs_control_icon_style_heading',
            array(
                'label'     => esc_html__( 'Icon Styles', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->_add_responsive_control(
            'tabs_control_icon_margin',
            array(
                'label'      => esc_html__( 'Icon Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' .kitify-tabs__label-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_image_margin',
            array(
                'label'      => esc_html__( 'Image Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' .kitify-tabs__label-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_image_width',
            array(
                'label'      => esc_html__( 'Image Width', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' .kitify-tabs__label-image' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_control(
            'tabs_control_icon_position',
            array(
                'label'       => esc_html__( 'Icon Position', 'kitify' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'left',
                'options' => array(
                    'left' => esc_html__( 'Left', 'kitify' ),
                    'right' => esc_html__( 'Right', 'kitify' ),
                    'top'  => esc_html__( 'Top', 'kitify' ),
                    'bottom'  => esc_html__( 'Bottom', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'tabs_control_state_style_heading',
            array(
                'label'     => esc_html__( 'State Styles', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

	    $this->_add_control(
		    'text_wrap',
		    array(
			    'label'       => esc_html__( 'Text Wrap', 'kitify' ),
			    'type'        => Controls_Manager::SELECT,
			    'default'     => 'nowrap',
			    'options' => array(
				    'normal' => esc_html__( 'Normal', 'kitify' ),
				    'nowrap' => esc_html__( 'No Wrap', 'kitify' ),
			    ),
			    'selectors' => array(
				    '{{WRAPPER}} '. $css_scheme['label'] => 'white-space: {{VALUE}}',
			    ),
		    )
	    );
        $this->_start_controls_tabs( 'tabs_control_styles' );

        $this->_start_controls_tab(
            'tabs_control_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'tabs_control_label_color',
            array(
                'label'  => esc_html__( 'Label Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['label'] => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'tabs_control_sublabel_color',
            array(
                'label'  => esc_html__( 'SubLabel Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['sublabel'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_label_typography',
                'selector' => '{{WRAPPER}} '. $css_scheme['control'] . ' ' . $css_scheme['label'],
                'label'    => esc_html__('Label Typography', 'kitify')
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_sublabel_typography',
                'selector' => '{{WRAPPER}} '. $css_scheme['control'] . ' ' . $css_scheme['sublabel'],
                'label'    => esc_html__('SubLabel Typography', 'kitify')
            )
        );

        $this->_add_control(
            'tabs_control_icon_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['icon'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'tabs_control_sublabel_margin',
            array(
                'label'      => __( 'SubLabel Spacing', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['sublabel'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_control_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'],
            )
        );

        $this->_add_responsive_control(
            'tabs_control_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ' .kitify-tabs__control-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['control'],
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tabs_control_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'tabs_control_label_color_hover',
            array(
                'label'  => esc_html__( 'Label Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['label'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'tabs_control_sublabel_color_hover',
            array(
                'label'  => esc_html__( 'SubLabel Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['sublabel'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_label_typography_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['label'],
                'label'  => esc_html__( 'Label Typography', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_sublabel_typography_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['sublabel'],
                'label'  => esc_html__( 'SubLabel Typography', 'kitify' ),
            )
        );

        $this->_add_control(
            'tabs_control_icon_color_hover',
            array(
                'label'     => esc_html__( 'Icon Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['icon'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_icon_size_hover',
            array(
                'label'      => esc_html__( 'Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_control_background_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover',
            )
        );

        $this->_add_responsive_control(
            'tabs_control_padding_hover',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ':hover' . ' .kitify-tabs__control-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_margin_hover',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border_hover',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover',
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tabs_control_active',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'tabs_control_label_color_active',
            array(
                'label'  => esc_html__( 'Label Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['label'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'tabs_control_sublabel_color_active',
            array(
                'label'  => esc_html__( 'SubLabel Text Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['sublabel'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_label_typography_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['label'],
                'label'  => esc_html__( 'Label Typography', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tabs_control_sublabel_typography_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['sublabel'],
                'label'  => esc_html__( 'SubLabel Typography', 'kitify' ),
            )
        );

        $this->_add_control(
            'tabs_control_icon_color_active',
            array(
                'label'     => esc_html__( 'Icon Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['icon'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_icon_size_active',
            array(
                'label'      => esc_html__( 'Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_control_background_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab',
            )
        );

        $this->_add_responsive_control(
            'tabs_control_padding_active',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab' . ' .kitify-tabs__control-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_margin_active',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'tabs_control_border_radius_active',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_control_box_shadow_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab',
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_control_border_active',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab',
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        /**
         * Tabs Content Style Section
         */
        $this->_start_controls_section(
            'section_tabs_content_style',
            array(
                'label'      => esc_html__( 'Tabs Content', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'tabs_content_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
            )
        );

        $this->_add_responsive_control(
            'tabs_content_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'tabs_content_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
            )
        );

        $this->_add_responsive_control(
            'tabs_content_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' , 'em', 'vw', 'vh'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrapper'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'tabs_content_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
            )
        );

        $this->_end_controls_section();

    }

    /**
     * [render description]
     * @return [type] [description]
     */
    protected function render() {

        $this->__context = 'render';

        $tabs = $this->get_settings_for_display( 'tabs' );

        if ( ! $tabs || empty( $tabs ) ) {
            return false;
        }

        $id_int = substr( $this->get_id_int(), 0, 3 );

        $tab_as_dropdown = filter_var($this->get_settings_for_display('tab_as_dropdown'), FILTER_VALIDATE_BOOLEAN);
        $tabs_style = $this->get_settings( 'tabs_style' );
        $tabs_position = $this->get_settings( 'tabs_position' );
        $tabs_position_laptop = $this->get_settings( 'tabs_position_laptop' );
        $tabs_position_tablet = $this->get_settings( 'tabs_position_tablet' );
        $tabs_position_tabletportrait = $this->get_settings( 'tabs_position_tabletportrait' );
        if(empty($this->get_settings( 'tabs_position_tabletportrait' ))){
            $tabs_position_tabletportrait = $this->get_settings( 'tabs_position_mobile_extra' );
        }
        $tabs_position_mobile = $this->get_settings( 'tabs_position_mobile' );
        $show_effect = $this->get_settings( 'show_effect' );

        $active_index = 0;

        foreach ( $tabs as $index => $item ) {
            if ( array_key_exists( 'item_active', $item ) && filter_var( $item['item_active'], FILTER_VALIDATE_BOOLEAN ) ) {
                $active_index = $index;
            }
        }

        $settings = array(
            'activeIndex'     => $active_index,
            'event'           => $this->get_settings( 'tabs_event' ),
            'autoSwitch'      => filter_var( $this->get_settings( 'auto_switch' ), FILTER_VALIDATE_BOOLEAN ),
            'autoSwitchDelay' => $this->get_settings( 'auto_switch_delay' ),
        );

        $this->add_render_attribute( 'instance', array(
            'class' => array(
                'kitify-tabs',
                'kitify-tabs-style-' . $tabs_style,
                'kitify-tabs-position-' . $tabs_position,
                'kitify-tabs-' . $show_effect . '-effect',
            ),
            'data-settings' => json_encode( $settings ),
        ) );

        if( $tab_as_dropdown ) {
            $this->add_render_attribute( 'instance', 'class', [
                'tab-as-dropdown'
            ] );
        }

        if ( ! empty( $tabs_position_laptop ) ) {
            $this->add_render_attribute( 'instance', 'class', [
                'kitify-tabs-position-laptop-' . $tabs_position_laptop
            ] );
        }

        if ( ! empty( $tabs_position_tablet ) ) {
            $this->add_render_attribute( 'instance', 'class', [
                'kitify-tabs-position-tablet-' . $tabs_position_tablet
            ] );
        }

        if ( ! empty( $tabs_position_tabletportrait ) ) {
            $this->add_render_attribute( 'instance', 'class', [
                'kitify-tabs-position-tabletp-' . $tabs_position_tabletportrait
            ] );
        }

        if ( ! empty( $tabs_position_mobile ) ) {
            $this->add_render_attribute( 'instance', 'class', [
                'kitify-tabs-position-mobile-' . $tabs_position_mobile
            ] );
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
            <div class="kitify-tabs__control-wrapper">
                <?php
                if( $tab_as_dropdown ) {
                    echo '<div class="kitify-tabs__controls--dd">';
                    $intro_text = $this->get_settings_for_display('tab_text_intro');
                    if(!empty($intro_text)){
                        echo sprintf('<div class="intro-text">%1$s</div>', $intro_text);
                    }
                    echo '<div class="kitify-tabs__controls--ddw">';
                    echo '<div class="kitify-tabs__controls">';
                }
                foreach ( $tabs as $index => $item ) {
                    $tab_count = $index + 1;
                    $tab_title_setting_key = $this->get_repeater_setting_key( 'lastudio_tab_control', 'tabs', $index );

                    $this->add_render_attribute( $tab_title_setting_key, array(
                        'id'            => 'kitify-tabs-control-' . $id_int . $tab_count,
                        'class'         => array(
                            'kitify-tabs__control',
                            'kitify-tabs__control-icon-' . $this->get_settings( 'tabs_control_icon_position' ),
                            $index === $active_index ? 'active-tab' : '',
                        ),
                        'data-tab'      => $tab_count,
                        'tabindex'      => $id_int . $tab_count,
                    ) );

                    $title_icon_html = '';

                    if ( ! empty( $item['item_icon'] ) ) {
                        ob_start();
                        Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] );
                        $icon_html = ob_get_clean();
                        if(!empty($icon_html)){
                            $title_icon_html = sprintf( '<div class="kitify-tabs__label-icon">%1$s</div>', $icon_html );
                        }
                    }

                    $title_image_html = '';

                    if ( ! empty( $item['item_image']['url'] ) ) {
                        $title_image_html = sprintf( '<img class="kitify-tabs__label-image" src="%1$s" alt="">', apply_filters('lastudio_wp_get_attachment_image_url', $item['item_image']['url']) );
                    }

                    $title_label_html = '';

                    if ( ! empty( $item['item_sublabel'] ) ) {
                        $title_label_html .= sprintf( '<div class="kitify-tabs__sublabel-text">%1$s</div>', $item['item_sublabel'] );
                    }
                    if ( ! empty( $item['item_label'] ) ) {
                        $title_label_html .= sprintf( '<div class="kitify-tabs__label-text">%1$s</div>', $item['item_label'] );
                    }
                    if(!empty($title_label_html)){
                        $title_label_html = sprintf('<div class="kitify-tabs__label">%1$s</div>', $title_label_html);
                    }

                    if( in_array($this->get_settings( 'tabs_control_icon_position' ), ['right', 'bottom']) ){
                        echo sprintf(
                            '<div %1$s><div class="kitify-tabs__control-inner">%2$s%3$s</div></div>',
                            $this->get_render_attribute_string( $tab_title_setting_key ),
                            $title_label_html,
                            filter_var( $item['item_use_image'], FILTER_VALIDATE_BOOLEAN ) ? $title_image_html : $title_icon_html
                        );
                    }
                    else{
                        echo sprintf(
                            '<div %1$s><div class="kitify-tabs__control-inner">%2$s%3$s</div></div>',
                            $this->get_render_attribute_string( $tab_title_setting_key ),
                            filter_var( $item['item_use_image'], FILTER_VALIDATE_BOOLEAN ) ? $title_image_html : $title_icon_html,
                            $title_label_html
                        );
                    }
                }

                if( $tab_as_dropdown ) {
                    $icon_dd = $this->_get_icon('dd_icon', '<span class="dd-icon">%1$s</span>');
                    echo '</div>';
                    echo sprintf('<div class="kitify-tabs__controls__tmp"><div class="kitify-tabs__controls__text"></div>%1$s</div>', $icon_dd);
                    echo '</div>';
                    echo '</div>';
                }
                else{
                    echo '<div class="kitify-tabs__control-wrapper-mobile"><a href="#" rel="nofollow" target="_self"></a></div>';
                }
                ?>
            </div>
            <div class="kitify-tabs__content-wrapper">
                <?php

                $template_processed = array();

                foreach ( $tabs as $index => $item ) {
                    $tab_count = $index + 1;
                    $tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

                    $item_enable_ajax = !empty($item['item_enable_ajax']) && filter_var($item['item_enable_ajax'], FILTER_VALIDATE_BOOLEAN);

                    $this->add_render_attribute( $tab_content_setting_key, array(
                        'id'       => 'kitify-tabs-content-' . $id_int . $tab_count,
                        'class'    => array(
                            'kitify-tabs__content',
                            $index === $active_index ? 'active-content' : ''
                        ),
                        'data-tab' => $tab_count,
                    ) );

                    $content_html = '';

                    switch ( $item[ 'content_type' ] ) {
                        case 'template':

                            if ( '0' !== $item['item_template_id'] ) {

                                if(in_array( $item['item_template_id'], $template_processed )){
	                                $template_content = $template_processed[$item['item_template_id']];
                                }
                                else{
                                    if($item_enable_ajax && !Plugin::instance()->editor->is_edit_mode()){
                                        $template_content = '<div data-kitify_ajax_loadtemplate="true" data-template-id="'.esc_attr($item['item_template_id']).'"><span class="kitify-css-loader"></span></div>';
                                    }
                                    else{
                                        $template_content = Plugin::instance()->frontend->get_builder_content_for_display( $item['item_template_id'] );
                                    }

	                                $template_processed[$item['item_template_id']] = $template_content;
                                }

                                if ( ! empty( $template_content ) ) {
                                    $content_html .= $template_content;

                                    if ( Plugin::instance()->editor->is_edit_mode() ) {
                                        $link = add_query_arg(
                                            array(
                                                'elementor' => '',
                                            ),
                                            get_permalink( $item['item_template_id'] )
                                        );

                                        $content_html .= sprintf( '<div class="kitify-tabs__edit-cover" data-template-edit-link="%s"><i class="eicon-edit"></i><span>%s</span></div>', $link, esc_html__( 'Edit Template', 'kitify' ) );
                                    }
                                }
                                else {
                                    $content_html = $this->no_template_content_message();
                                }
                            }
                            else {
                                $content_html = $this->no_templates_message();
                            }
                            break;

                        case 'editor':
                            $content_html = $this->parse_text_editor( $item['item_editor_content'] );
                            break;
                    }

                    echo sprintf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( $tab_content_setting_key ), $content_html );
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * [no_templates_message description]
     * @return [type] [description]
     */
    public function no_templates_message() {
        $message = '<span>' . esc_html__( 'Template is not defined. ', 'kitify' ) . '</span>';

        $link = add_query_arg(
            array(
                'post_type'     => 'elementor_library',
                'action'        => 'elementor_new_post',
                '_wpnonce'      => wp_create_nonce( 'elementor_action_new_post' ),
                'template_type' => 'section',
            ),
            esc_url( admin_url( '/edit.php' ) )
        );

        $new_link = '<span>' . esc_html__( 'Select an existing template or create a ', 'kitify' ) . '</span><a class="kitify-tabs-new-template-link elementor-clickable" target="_blank" href="' . $link . '">' . esc_html__( 'new one', 'kitify' ) . '</a>' ;

        return sprintf(
            '<div class="kitify-tabs-no-template-message">%1$s%2$s</div>',
            $message,
            ( Plugin::instance()->editor->is_edit_mode() || Plugin::instance()->preview->is_preview_mode() ) ? $new_link : ''
        );
    }

    /**
     * [no_template_content_message description]
     * @return [type] [description]
     */
    public function no_template_content_message() {
        $message = '<span>' . esc_html__( 'The tabs are working. Please, note, that you have to add a template to the library in order to be able to display it inside the tabs.', 'kitify' ) . '</span>';

        return sprintf( '<div class="lastudio-toogle-no-template-message">%1$s</div>', $message );
    }

    /**
     * [get_template_edit_link description]
     * @param  [type] $template_id [description]
     * @return [type]              [description]
     */
    public function get_template_edit_link( $template_id ) {

        $link = add_query_arg( 'elementor', '', get_permalink( $template_id ) );

        return '<a target="_blank" class="elementor-edit-template elementor-clickable" href="' . $link .'"><i class="eicon-edit"></i> ' . esc_html__( 'Edit Template', 'kitify' ) . '</a>';
    }

}
