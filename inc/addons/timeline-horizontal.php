<?php
/**
 * Class: Kitify_Timeline_Horizontal
 * Name: Timeline Horizontal
 * Slug: kitify-timeline-horizontal
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use KitifyExtensions\Elementor\Controls\Group_Control_Box_Style;

/**
 * Kitify_Timeline_Horizontal Widget
 */
class Kitify_Timeline_Horizontal extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/timeline-horizontal.css'), ['kitify-base'], kitify()->get_version());
        wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/timeline-horizontal.js'), ['kitify-base'], kitify()->get_version(), true);
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

    public function get_name() {
        return 'kitify-timeline-horizontal';
    }

    public function get_widget_title() {
        return esc_html__( 'Timeline Horizontal', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-htimeline';
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'kitify/timeline-horizontal/css-scheme',
            array(
                'track'              => '.kitify-htimeline-track',
                'line'               => '.kitify-htimeline__line',
                'progress'           => '.kitify-htimeline__line-progress',
                'item'               => '.kitify-htimeline-item',
                'item_point'         => '.kitify-htimeline-item__point',
                'item_point_content' => '.kitify-htimeline-item__point-content',
                'item_meta'          => '.kitify-htimeline-item__meta',
                'card'               => '.kitify-htimeline-item__card',
                'card_inner'         => '.kitify-htimeline-item__card-inner',
                'card_img'           => '.kitify-htimeline-item__card-img',
                'card_title'         => '.kitify-htimeline-item__card-title',
                'card_desc'          => '.kitify-htimeline-item__card-desc',
                'card_arrow'         => '.kitify-htimeline-item__card-arrow',
                'arrow'              => '.kitify-htimeline .kitify-arrow',
                'prev_arrow'         => '.kitify-htimeline .kitify-arrow.prev-arrow',
                'next_arrow'         => '.kitify-htimeline .kitify-arrow.next-arrow',
            )
        );

        $this->start_controls_section(
            'section_items',
            array(
                'label' => esc_html__( 'Items', 'kitify' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'is_item_active',
            array(
                'label'   => esc_html__( 'Active', 'kitify' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            )
        );

        $repeater->add_control(
            'show_item_image',
            array(
                'label'   => esc_html__( 'Show Image', 'kitify' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
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
                'dynamic' => array( 'active' => true ),
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
                'type'    => Controls_Manager::TEXTAREA,
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

        $this->add_control(
            'cards_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'is_item_active'  => 'yes',
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

        $this->add_control(
            'item_title_size',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'   => esc_html__( 'H1', 'kitify' ),
                    'h2'   => esc_html__( 'H2', 'kitify' ),
                    'h3'   => esc_html__( 'H3', 'kitify' ),
                    'h4'   => esc_html__( 'H4', 'kitify' ),
                    'h5'   => esc_html__( 'H5', 'kitify' ),
                    'h6'   => esc_html__( 'H6', 'kitify' ),
                    'div'  => esc_html__( 'div', 'kitify' ),
                    'span' => esc_html__( 'span', 'kitify' ),
                    'p'    => esc_html__( 'p', 'kitify' ),
                ),
                'default' => 'h5',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'show_card_arrows',
            array(
                'label'   => esc_html__( 'Show Card Arrows', 'kitify' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            array(
                'label' => esc_html__( 'Layout', 'kitify' ),
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => esc_html__( 'Columns', 'kitify' ),
                'type'           => Controls_Manager::NUMBER,
                'min'            => 1,
                'max'            => 6,
                'default'        => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'selectors'      => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'flex: 0 0 calc(100%/{{VALUE}}); max-width: calc(100%/{{VALUE}});',
                ),
                'render_type'    => 'template',
            )
        );

        $this->add_control(
            'vertical_layout',
            array(
                'label'   => esc_html__( 'Layout', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'toggle'  => false,
                'default' => 'top',
                'options' => array(
                    'top' => array(
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'chess' => array(
                        'title' => esc_html__( 'Chess', 'kitify' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
            )
        );

        $this->add_control(
            'layout_chess_reverse',
            array(
                'label'   => esc_html__( 'Chess Layout', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'column',
                'options' => array(
                    'column'            => esc_html__( 'Default', 'kitify' ),
                    'column-reverse'    => esc_html__( 'Reverse', 'kitify' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-htimeline-track' => 'flex-flow: {{VALUE}}',
                ),
                'condition' => array(
                    'vertical_layout' => 'chess'
                ),
                'render_type' => 'ui',
            )
        );

        $this->add_control(
            'horizontal_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'toggle'  => false,
                'default' => 'left',
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

        $this->add_control(
            'navigation_type',
            array(
                'label'   => esc_html__( 'Navigation Type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'scroll-bar',
                'options' => array(
                    'scroll-bar' => esc_html__( 'Scroll Bar', 'kitify' ),
                    'arrows-nav' => esc_html__( 'Arrows Navigation', 'kitify' ),
                )
            )
        );
        $this->_add_icon_control(
            'prev_arrow_icon',
            [
                'label' => __( 'Prev Icon', 'kitify' ),
                'label_block' => true,
                'default'     => 'novaicon novaicon-left-arrow',
                'fa5_default' => array(
                    'value'   => 'novaicon-left-arrow',
                    'library' => 'novaicon',
                ),
                'condition' => array(
                    'navigation_type' => 'arrows-nav',
                ),
            ]
        );

        $this->_add_icon_control(
            'next_arrow_icon',
            [
                'label' => __( 'Next Icon', 'kitify' ),
                'label_block' => true,
                'default'     => 'novaicon novaicon-right-arrow',
                'fa5_default' => array(
                    'value'   => 'novaicon-right-arrow',
                    'library' => 'novaicon',
                ),
                'condition' => array(
                    'navigation_type' => 'arrows-nav',
                ),
            ]
        );

        $this->end_controls_section();

        /**
         * `General` Style Section
         */
        $this->start_controls_section(
            'section_general_style',
            array(
                'label' => esc_html__( 'General', 'kitify' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'items_gap',
            array(
                'label' => esc_html__( 'Items Gap', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
                ),
                'render_type' => 'template',
            )
        );

        $this->end_controls_section();

        /**
         * `Cards` Style Section
         */
        $this->start_controls_section(
            'section_cards_style',
            array(
                'label' => esc_html__( 'Cards', 'kitify' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'cards_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['card_arrow'],
            )
        );

        $this->add_responsive_control(
            'cards_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'cards_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'cards_spacing',
            array(
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-htimeline-list--top ' . $css_scheme['card'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-htimeline-list--bottom ' . $css_scheme['card'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'after'
            )
        );

        $this->start_controls_tabs( 'cards_style_tabs' );

        $this->start_controls_tab(
            'cards_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->add_control(
            'cards_background_normal',
            array(
                'label'     => esc_html__( 'Background', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_normal',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['card_arrow'],
                'exclude'  => array(
                    'box_shadow_position',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'cards_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->add_control(
            'cards_background_hover',
            array(
                'label'     => esc_html__( 'Background', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'cards_border_color_hover',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card'] => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};'
                ),
                'condition' => array(
                    'cards_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'],
                'exclude'  => array(
                    'box_shadow_position',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'cards_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->add_control(
            'cards_background_active',
            array(
                'label'     => esc_html__( 'Background', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'cards_border_color_active',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card'] => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'cards_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'],
                'exclude'  => array(
                    'box_shadow_position',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'cards_arrow_heading',
            array(
                'label'     => esc_html__( 'Arrow', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_card_arrows' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'cards_arrow_width',
            array(
                'label' => esc_html__( 'Size', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_arrow'] => 'width:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_card_arrows' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'cards_arrow_offset',
            array(
                'label' => esc_html__( 'Offset', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-htimeline--align-left ' . $css_scheme['card_arrow'] => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-htimeline--align-right ' . $css_scheme['card_arrow'] => 'right: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_card_arrows' => 'yes',
                    'horizontal_alignment!' => 'center',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Cards Content` Style Section
         */
        $this->start_controls_section(
            'section_image_style',
            array(
                'label' => esc_html__( 'Cards Content', 'kitify' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'cards_content_align',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
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
                    'justify' => array(
                        'title' => esc_html__( 'Justified', 'kitify' ),
                        'icon'  => 'eicon-text-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'image_heading',
            array(
                'label'     => esc_html__( 'Image', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'move_image_to_meta',
            array(
                'label'   => esc_html__( 'Show Image In Meta', 'kitify' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            )
        );

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

        $this->add_responsive_control(
            'image_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-htimeline:not(.kitify-htimeline--layout-chess) ' . $css_scheme['card_img'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-htimeline--layout-chess .kitify-htimeline-list--top ' . $css_scheme['card_img'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-htimeline--layout-chess .kitify-htimeline-list--bottom ' . $css_scheme['card_img'] => 'margin: {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}} {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
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
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_img'] . ' img',
            )
        );

        $this->add_control(
            'title_heading',
            array(
                'label'     => esc_html__( 'Title', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_title'],
            )
        );

        $this->add_responsive_control(
            'card_title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'card_title_style_tabs' );

        $this->start_controls_tab(
            'card_title_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->add_control(
            'card_title_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_title_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->add_control(
            'card_title_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_title_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->add_control(
            'card_title_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'desc_heading',
            array(
                'label'     => esc_html__( 'Description', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_desc'],
            )
        );

        $this->add_responsive_control(
            'card_desc_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'card_desc_style_tabs' );

        $this->start_controls_tab(
            'card_desc_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->add_control(
            'card_desc_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_desc_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->add_control(
            'card_desc_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_desc_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->add_control(
            'card_desc_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'orders_heading',
            array(
                'label'     => esc_html__( 'Orders', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'image_order',
            array(
                'label' => esc_html__( 'Image Order', 'kitify' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 0,
                'max'   => 10,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'title_order',
            array(
                'label' => esc_html__( 'Title Order', 'kitify' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 0,
                'max'   => 10,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'desc_order',
            array(
                'label' => esc_html__( 'Description Order', 'kitify' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 0,
                'max'   => 10,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Meta` Style Section
         */
        $this->start_controls_section(
            'section_meta_style',
            array(
                'label' => esc_html__( 'Meta', 'kitify' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'meta_typography',
                'selector' => '{{WRAPPER}} ' .  $css_scheme['item_meta'],
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'meta_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->add_control(
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

        $this->add_responsive_control(
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

        $this->add_responsive_control(
            'meta_spacing',
            array(
                'label' => esc_html__( 'Spacing', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-htimeline-list--top ' . $css_scheme['item_meta'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-htimeline-list--bottom ' . $css_scheme['item_meta'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'after'
            )
        );

        $this->start_controls_tabs( 'meta_style_tabs' );

        $this->start_controls_tab(
            'meta_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->add_control(
            'meta_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_normal_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'meta_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->add_control(
            'meta_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'meta_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'meta_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->add_control(
            'meta_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'meta_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_active_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'],
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * `Point` Style Section
         */
        $this->start_controls_section(
            'section_point_style',
            array(
                'label' => esc_html__( 'Point', 'kitify' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs( 'point_type_style_tabs' );

        $this->start_controls_tab(
            'point_type_text_styles',
            array(
                'label' => esc_html__( 'Text', 'kitify' ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'point_text_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'point_type_icon_styles',
            array(
                'label' => esc_html__( 'Icon', 'kitify' ),
            )
        );

        $this->add_responsive_control(
            'point_type_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em'),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'point_size',
            array(
                'label' => esc_html__( 'Point Size', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'point_offset',
            array(
                'label' => esc_html__( 'Offset', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-htimeline--align-left ' . $css_scheme['item_point_content'] => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-htimeline--align-right ' . $css_scheme['item_point_content'] => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'horizontal_alignment!' => 'center',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'point_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
            )
        );

        $this->add_control(
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

        $this->start_controls_tabs( 'point_style_tabs' );

        $this->start_controls_tab(
            'point_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->add_control(
            'point_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'point_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->add_control(
            'point_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'point_border_border!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'point_active_styles',
            array(
                'label' => esc_html__( 'Active', 'kitify' ),
            )
        );

        $this->add_control(
            'point_active_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'point_border_border!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * `Line` Style Section
         */
        $this->start_controls_section(
            'section_line_style',
            array(
                'label' => esc_html__( 'Line', 'kitify' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'line_fullscreen',
            array(
                'label'   => esc_html__( 'Enable 100% browser width', 'kitify' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
                'prefix_class'  => 'kitify-htimeline-line100-',
            )
        );

        $this->add_control(
            'line_background_color',
            array(
                'label'     => esc_html__( 'Line Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .kitify-htimeline-inner:before' => 'background-color: {{VALUE}};',
                ),
            )
        );

//		$this->add_control(
//			'progress_background_color',
//			array(
//				'label'     => esc_html__( 'Progress Color', 'kitify' ),
//				'type'      => Controls_Manager::COLOR,
//				'selectors' => array(
//					'{{WRAPPER}} ' . $css_scheme['progress'] => 'background-color: {{VALUE}};',
//				),
//			)
//		);

        $this->add_responsive_control(
            'line_height',
            array(
                'label' => esc_html__( 'Height', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 15,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .kitify-htimeline-inner:before' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Scrollbar` Style Section
         */
        $this->start_controls_section(
            'section_scrollbar_style',
            array(
                'label' => esc_html__( 'Scrollbar', 'kitify' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'navigation_type' => 'scroll-bar',
                ),
            )
        );

        $this->add_control(
            'non_webkit_notice',
            array(
                'type' => Controls_Manager::RAW_HTML,
                'raw'  => esc_html__( 'Currently works only in -webkit- browsers', 'kitify' ),
                'content_classes' => 'elementor-descriptor',
            )
        );

        $this->add_control(
            'scrollbar_bg',
            array(
                'label'     => esc_html__( 'Scrollbar Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'scrollbar_thumb_bg',
            array(
                'label'     => esc_html__( 'Scrollbar Thumb Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar-thumb' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'scrollbar_height',
            array(
                'label' => esc_html__( 'Scrollbar Height', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'scrollbar_offset',
            array(
                'label' => esc_html__( 'Scrollbar Offset', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'scrollbar_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Arrows` Style Section
         */
        $this->start_controls_section(
            'section_arrows_style',
            array(
                'label'     => esc_html__( 'Arrows', 'kitify' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'navigation_type' => 'arrows-nav',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_prev',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'     => 'arrows_style',
                'label'    => esc_html__( 'Arrows Style', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['arrow'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_next_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'     => 'arrows_hover_style',
                'label'    => esc_html__( 'Arrows Style', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['arrow'] . ':not(.arrow-disabled):hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'prev_arrow_position',
            array(
                'label'     => esc_html__( 'Prev Arrow Position', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'prev_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'kitify' ),
                    'right' => esc_html__( 'Right', 'kitify' ),
                ),
                'render_type'=> 'ui',
            )
        );

        $this->add_responsive_control(
            'prev_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' .  $css_scheme['prev_arrow'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'prev_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' .  $css_scheme['prev_arrow'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->add_control(
            'next_arrow_position',
            array(
                'label'     => esc_html__( 'Next Arrow Position', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'next_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'kitify' ),
                    'right' => esc_html__( 'Right', 'kitify' ),
                ),
                'render_type'=> 'ui',
            )
        );

        $this->add_responsive_control(
            'next_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' .  $css_scheme['next_arrow'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'next_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' .  $css_scheme['next_arrow'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
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

    public function _render_image( $item_settings ) {
        $show_image = filter_var( $item_settings['show_item_image'], FILTER_VALIDATE_BOOLEAN );

        if ( ! $show_image || empty( $item_settings['item_image']['url'] ) ) {
            return;
        }

        $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

        $image_format = apply_filters( 'kitify/timeline-horizontal/image-format', '<div class="kitify-htimeline-item__card-img">%s</div>' );

        printf( $image_format, $img_html );
    }

    public function _render_point_content( $item_settings ) {
        echo '<div class="kitify-htimeline-item__point">';
        echo '<div class="kitify-htimeline-item__point-content">';
        switch ( $item_settings['item_point_type'] ) {
            case 'icon':
                echo $this->_get_icon_setting( $item_settings['item_point_icon'], '%s' );
                break;
            case 'text':
                echo $this->_loop_item( array( 'item_point_text' ), '%s' );
                break;
        }
        echo '</div>';
        echo '</div>';
    }

}