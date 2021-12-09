<?php
/**
 * Class: Kitify_Timeline_Vertical
 * Name: Timeline Vertical
 * Slug: kitify-timeline-vertical
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Kitify_Timeline_Vertical Widget
 */
class Kitify_Timeline_Vertical extends Kitify_Base {

    public $_processed_item_index = 0;

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/timeline-vertical.css'), ['kitify-base'], kitify()->get_version());
        wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/timeline-vertical.js'), ['kitify-base'], kitify()->get_version(), true);
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

    public function get_name() {
        return 'kitify-timeline-vertical';
    }

    public function get_widget_title() {
        return esc_html__( 'Timeline Vertical', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-vtimeline';
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'kitify/timeline-vertical/css-scheme',
            array(
                'line'               => '.kitify-vtimeline__line',
                'progress'           => '.kitify-vtimeline__line-progress',
                'item'               => '.kitify-vtimeline-item',
                'item_point'         => '.kitify-vtimeline-item__point',
                'item_point_content' => '.kitify-vtimeline-item__point-content',
                'item_meta'          => '.kitify-vtimeline-item__meta-content',
                'card'               => '.kitify-vtimeline-item__card',
                'card_inner'         => '.kitify-vtimeline-item__card-inner',
                'card_img'           => '.kitify-vtimeline-item__card-img',
                'card_content'       => '.kitify-vtimeline-item__card-content',
                'card_title'         => '.kitify-vtimeline-item__card-title',
                'card_subtitle'      => '.kitify-vtimeline-item__card-subtitle',
                'card_desc'          => '.kitify-vtimeline-item__card-desc',
                'card_arrow'         => '.kitify-vtimeline-item__card-arrow',
            )
        );

        $this->_start_controls_section(
            'section_cards',
            array(
                'label' => esc_html__( 'Cards', 'kitify' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'show_item_image',
            array(
                'label'        => esc_html__( 'Show Image', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $repeater->add_control(
            'item_image',
            array(
                'label'     => esc_html__( 'Image', 'kitify' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'condition' => array(
                    'show_item_image' => 'yes'
                ),
                'dynamic'  => array( 'active' => true ),
            )
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'      => 'item_image',
                'default'   => 'full',
                'condition' => array(
                    'show_item_image' => 'yes'
                ),
            )
        );

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );
        $repeater->add_control(
            'item_subtitle',
            array(
                'label'   => esc_html__( 'Sub Title', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_meta',
            array(
                'label'   => esc_html__( 'Meta', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_desc',
            array(
                'label'   => esc_html__( 'Description', 'kitify' ),
                'type'    => Controls_Manager::WYSIWYG,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_point',
            array(
                'label'     => esc_html__( 'Point', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $repeater->add_control(
            'item_point_type',
            array(
                'label'   => esc_html__( 'Point Content Type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => array(
                    'icon' => esc_html__( 'Icon', 'kitify' ),
                    'text' => esc_html__( 'Text', 'kitify' ),
                ),
            )
        );

        $repeater->add_control(
            'item_point_icon',
            array(
                'label'       => esc_html__( 'Point Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition'   => array(
                    'item_point_type' => 'icon'
                ),
            )
        );

        $repeater->add_control(
            'item_point_text',
            array(
                'label'     => esc_html__( 'Point Text', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'A',
                'condition' => array(
                    'item_point_type' => 'text'
                )
            )
        );

	    $repeater->add_control(
		    'item_cstyle',
		    array(
			    'label'     => esc_html__( 'Custom Style', 'kitify' ),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    )
	    );
	    $repeater->add_control(
		    'item_bgcolor',
		    [
			    'label' => __( 'Background Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card'] => 'background-color: {{VALUE}}',
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_titlecolor',
		    [
			    'label' => __( 'Title Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_stitlecolor',
		    [
			    'label' => __( 'SubTitle Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_subtitle'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_metacolor',
		    [
			    'label' => __( 'Meta Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_desccolor',
		    [
			    'label' => __( 'Description Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_pointcolor',
		    [
			    'label' => __( 'Point Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );

        $this->_add_control(
            'cards_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_title'      => esc_html__( 'Card #1', 'kitify' ),
                        'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'kitify' ),
                        'item_meta'       => esc_html__( 'Thursday, August 31, 2018', 'kitify' ),
                    ),
                    array(
                        'item_title'      => esc_html__( 'Card #2', 'kitify' ),
                        'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'kitify' ),
                        'item_meta'       => esc_html__( 'Thursday, August 29, 2018', 'kitify' ),
                    ),
                    array(
                        'item_title'      => esc_html__( 'Card #3', 'kitify' ),
                        'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'kitify' ),
                        'item_meta'       => esc_html__( 'Thursday, August 28, 2018', 'kitify' ),
                    ),
                    array(
                        'item_title'      => esc_html__( 'Card #4', 'kitify' ),
                        'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'kitify' ),
                        'item_meta'       => esc_html__( 'Thursday, August 27, 2018', 'kitify' ),
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_layout',
            array(
                'label' => esc_html__( 'Layout', 'kitify' ),
            )
        );

        $this->_add_control(
            'animate_cards',
            array(
                'label'        => esc_html__( 'Animate Cards', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'kitify-vtimeline-item--animated',
                'default'      => '',
            )
        );

        $this->_add_control(
            'image_in_meta',
            array(
                'label'        => esc_html__( 'Display image in meta', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->_add_control(
            'horizontal_alignment',
            array(
                'label'   => esc_html__( 'Horizontal Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
            )
        );

        $this->_add_control(
            'vertical_alignment',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'middle',
                'options' => array(
                    'top'    => array(
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'middle' => array(
                        'title' => esc_html__( 'Middle', 'kitify' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
            )
        );

        $this->_add_responsive_control(
            'horizontal_space',
            array(
                'label'      => esc_html__( 'Horizontal Space', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 150,
                    ),
                ),
                'default'    => array(
                    'size' => 20,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-vtimeline--align-center ' . $css_scheme['item_point'] => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .kitify-vtimeline--align-left ' . $css_scheme['item_point']   => 'margin-right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .kitify-vtimeline--align-right ' . $css_scheme['item_point']  => 'margin-left: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'vertical_space',
            array(
                'label'      => esc_html__( 'Vertical Space', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 150,
                    ),
                ),
                'default'    => array(
                    'size' => 30,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '+' . $css_scheme['item'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_cards_style',
            array(
                'label'      => esc_html__( 'Cards', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_cards( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_image_style',
            array(
                'label'      => esc_html__( 'Image', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_image( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_meta_style',
            array(
                'label'      => esc_html__( 'Meta', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_meta( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_card_content_style',
            array(
                'label'      => esc_html__( 'Content', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_card_content( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_card_title_style',
            array(
                'label'      => esc_html__( 'Title', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_card_title( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_card_subtitle_style',
            array(
                'label'      => esc_html__( 'Sub Title', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_card_subtitle( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_desc_style',
            array(
                'label'      => esc_html__( 'Description', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_card_desc( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_point_style',
            array(
                'label'      => esc_html__( 'Point', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_points( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_line_style',
            array(
                'label'      => esc_html__( 'Line', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_line( $css_scheme );

        $this->_end_controls_section();
    }

    public function _control_section_cards( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'cards_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['card'] . ',' . '{{WRAPPER}} ' . $css_scheme['card_arrow'],
            )
        );

        $this->_add_responsive_control(
            'cards_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card']       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;'
                ),
            )
        );

        $this->_add_responsive_control(
            'cards_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'cards_style_tabs' );

        $this->_start_controls_tab(
            'cards_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'cards_background_normal',
            array(
                'label'     => esc_html__( 'Background', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_normal',
                'selector' => '{{WRAPPER}} '. $css_scheme['card'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'cards_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'cards_background_hover',
            array(
                'label'     => esc_html__( 'Background', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'cards_border_color_hover',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card']       => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};'
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'cards_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'cards_background_active',
            array(
                'label'     => esc_html__( 'Background', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'cards_border_color_active',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card']       => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card'],
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'cards_arrow_heading',
            array(
                'label'     => esc_html__( 'Arrow', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->_add_responsive_control(
            'cards_arrow_width',
            array(
                'label'      => esc_html__( 'Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 60,
                    ),
                ),
                'default'    => array(
                    'size' => 20,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_arrow'] => 'width:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .kitify-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '(desktop){{WRAPPER}} .kitify-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '(desktop){{WRAPPER}} .kitify-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '(desktop) .rtl {{WRAPPER}} .kitify-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '(desktop) .rtl {{WRAPPER}} .kitify-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .kitify-vtimeline--align-left ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .kitify-vtimeline--align-right ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
                ),
            )
        );

    }

    public function _control_section_image( $css_scheme ) {

        $this->add_responsive_control(
            'image_size',
            array(
                'label' => esc_html__( 'Image Size', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] . ' img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'image_alignment',
            array(
                'label'   => esc_html__( 'Image Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] => 'text-align: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'image_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 200,
                    ),
                ),
                'default'    => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] . ' img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

    }

    public function _control_section_meta( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'meta_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'meta_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->_add_control(
            'meta_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
            )
        );

        $this->_add_responsive_control(
            'meta_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'meta_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'meta_style_tabs' );

        $this->_start_controls_tab(
            'meta_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'meta_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_normal_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'meta_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'meta_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'meta_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'meta_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_active_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'],
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_card_content( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'card_content_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_content'],
            )
        );

        $this->_add_control(
            'card_content_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
            )
        );

        $this->_add_responsive_control(
            'card_content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'card_content_style_tabs' );

        $this->_start_controls_tab(
            'card_content_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_content_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'card_content_normal_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_content'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_content_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_content_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'card_content_hover_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'],
            )
        );

        $this->_add_control(
            'card_content_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_content_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_content_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'card_content_active_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'],
            )
        );

        $this->_add_control(
            'card_content_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'card_arrow_heading',
            array(
                'label'     => esc_html__( 'Card Arrow', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'vertical_alignment!' => 'middle'
                )
            )
        );

        $this->_add_responsive_control(
            'card_arrow_offset',
            array(
                'label'      => esc_html__( 'Card Arrow Offset', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                    '%'  => array(
                        'min' => 0,
                        'max' => 80,
                    ),
                ),
                'default'    => array(
                    'size' => 12,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-vtimeline--align-top ' . $css_scheme['card_arrow']    => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-vtimeline--align-bottom ' . $css_scheme['card_arrow'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'vertical_alignment!' => 'middle'
                )
            )
        );
    }

    public function _control_section_card_title( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_title'],
            )
        );

        $this->_add_responsive_control(
            'card_title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'card_title_style_tabs' );

        $this->_start_controls_tab(
            'card_title_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_title_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_title_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_title_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_title_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_title_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_card_subtitle( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_subtitle_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_subtitle'],
            )
        );

        $this->_add_responsive_control(
            'card_subtitle_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_subtitle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'card_subtitle_style_tabs' );

        $this->_start_controls_tab(
            'card_subtitle_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_subtitle_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_subtitle'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_subtitle_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_subtitle_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_subtitle'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_subtitle_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_subtitle_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_subtitle'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_card_desc( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_desc'],
            )
        );

        $this->_add_responsive_control(
            'card_desc_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'card_desc_style_tabs' );

        $this->_start_controls_tab(
            'card_desc_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_desc_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_desc_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_desc_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_desc_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'card_desc_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_points( $css_scheme ) {

        $this->_start_controls_tabs( 'point_type_style_tabs' );

        $this->_start_controls_tab(
            'point_type_text_styles',
            array(
                'label' => esc_html__( 'Text', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'point_text_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_point_content'] . '.kitify-vtimeline-item__point-content--text',
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'point_type_icon_styles',
            array(
                'label' => esc_html__( 'Icon', 'kitify' ),
            )
        );

        $this->_add_responsive_control(
            'point_type_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 5,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'size' => 16,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] . '.kitify-vtimeline-item__point-content--icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'point_size',
            array(
                'label'      => esc_html__( 'Point Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'size' => 40,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content']               => 'height:{{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-vtimeline--align-center ' . $css_scheme['line'] => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .kitify-vtimeline--align-left ' . $css_scheme['line']   => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .kitify-vtimeline--align-right ' . $css_scheme['line']   => 'margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'point_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
            )
        );

        $this->_add_control(
            'point_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_start_controls_tabs( 'point_style_tabs' );

        $this->_start_controls_tab(
            'point_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'point_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'point_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'point_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'point_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->_add_control(
            'point_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_line( $css_scheme ) {

        $this->_add_control(
            'line_background_color',
            array(
                'label'     => esc_html__( 'Line Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'progress_background_color',
            array(
                'label'     => esc_html__( 'Progress Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['progress'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'line_width',
            array(
                'label'      => esc_html__( 'Thickness', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 15,
                    ),
                ),
                'default'    => array(
                    'size' => 2,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'line_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['line'],
            )
        );

        $this->_add_control(
            'line_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

    }

    public function _generate_point_content( $item_settings ) {
        echo '<div class="kitify-vtimeline-item__point">';
        switch ( $item_settings['item_point_type'] ) {
            case 'icon':
                echo $this->_get_icon_setting( $item_settings['item_point_icon'], '<div class="kitify-vtimeline-item__point-content timeline-item__point-content--icon">%s</div>' );
                break;
            case 'text':
                echo $this->_loop_item( array( 'item_point_text' ), '<div class="kitify-vtimeline-item__point-content timeline-item__point-content--text">%s</div>' );
                break;
        }
        echo '</div>';
    }

    public function _render_image( $item_settings ) {
        $show_image = filter_var( $item_settings['show_item_image'], FILTER_VALIDATE_BOOLEAN );

        if ( ! $show_image || empty( $item_settings['item_image']['url'] ) ) {
            return;
        }

        $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

        $image_format = apply_filters( 'kitify/timeline-vertical/image-format', '<div class="kitify-vtimeline-item__card-img">%s</div>' );

        printf( $image_format, $img_html );
    }

    public function get_item_inline_editing_attributes( $settings_item_key, $repeater_item_key, $index, $classes ) {
        $item_key = $this->get_repeater_setting_key( $settings_item_key, $repeater_item_key, $index );
        $this->add_render_attribute( $item_key, [ 'class' => $classes ] );
        $this->add_inline_editing_attributes( $item_key, 'basic' );

        return $this->get_render_attribute_string( $item_key );
    }

    protected function render() {
        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();

        $this->_processed_item_index = 0;
    }

}