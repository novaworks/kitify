<?php

/**
 * Class: Kitify_Images_Layout
 * Name: Images Layout
 * Slug: kitify-images-layout
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Images_Layout Widget
 */
class Kitify_Images_Layout extends Kitify_Base {

    /**
     * [$item_counter description]
     * @var integer
     */
    public $item_counter = 0;

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/images-layout.css'), ['kitify-base'], kitify()->get_version());

        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( 'jquery-isotope' );
        $this->add_script_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-images-layout';
    }

    protected function get_widget_title() {
        return esc_html__( 'Images Layout', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-image-layout';
    }


    protected function register_controls() {

        $css_scheme = apply_filters(
            'kitify/images-layout/css-scheme',
            array(
                'instance'          => '.kitify-images-layout',
                'list_container'    => '.kitify-images-layout__list',
                'item'              => '.kitify-images-layout__item',
                'inner'             => '.kitify-images-layout__inner',
                'image_wrap'        => '.kitify-images-layout__image',
                'image_instance'    => '.kitify-images-layout__image-instance',
                'content_wrap'      => '.kitify-images-layout__content',
                'icon'              => '.kitify-images-layout__icon',
                'title'             => '.kitify-images-layout__title',
                'desc'              => '.kitify-images-layout__desc',
                'button'            => '.kitify-images-layout__button',
            )
        );

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'kitify' ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'   => esc_html__( 'Layout type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => array(
                    'grid'    => esc_html__( 'Grid', 'kitify' ),
                    'list'    => esc_html__( 'List', 'kitify' ),
                ),
            )
        );

        $this->add_control(
            'preset',
            array(
                'label'   => esc_html__( 'Preset', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'prefix_class' => 'imagelayout-preset-',
                'options' => apply_filters('kitify/images-layout/preset', [
                    'default' => esc_html__( 'Default', 'kitify' ),
                    'type-1' => esc_html__( 'Type 1', 'kitify' ),
                    'type-2' => esc_html__( 'Type 2', 'kitify' ),
                ])
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => kitify_helper()->get_select_range( 6 ),
                'condition' => array(
                    'layout_type' => ['grid']
                ),
            )
        );

        $this->_add_control(
            'enable_masonry',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Enable Masonry?', 'kitify' ),
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class' => 'enable-c-height-',
                'condition' => array(
                    'layout_type!' => 'list'
                ),
            )
        );

        $this->add_responsive_control(
            'item_height',
            array(
                'label' => esc_html__( 'Image Height', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'size_units' => ['px', '%', 'vh'],
                'default' => [
                    'size' => 300,
                    'unit' => 'px'
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ),
                'condition' => [
                    'layout_type!' => 'list',
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'kitify' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Image', 'kitify' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_icon',
            array(
                'label'       => esc_html__( 'Icon', 'kitify' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false
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
            'item_desc',
            array(
                'label'   => esc_html__( 'Description', 'kitify' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_link_type',
            array(
                'label'   => esc_html__( 'Link type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lightbox',
                'options' => array(
                    'lightbox' => esc_html__( 'Lightbox', 'kitify' ),
                    'external' => esc_html__( 'External', 'kitify' ),
                    'none'     => esc_html__( 'None', 'kitify' )
                ),
            )
        );
	    $repeater->add_control(
		    'item_link_text',
		    array(
			    'label'   => esc_html__( 'Button Title', 'kitify' ),
			    'type'    => Controls_Manager::TEXT,
			    'condition' => array(
				    'item_link_type' => 'external',
			    ),
			    'dynamic' => array( 'active' => true ),
		    )
	    );
        $repeater->add_control(
            'item_url',
            array(
                'label'   => esc_html__( 'External Link', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '#',
                'condition' => array(
                    'item_link_type' => 'external',
                ),
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ),
                ),
            )
        );

        $repeater->add_control(
            'item_target',
            array(
                'label'        => esc_html__( 'Open external link in new window', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => '_blank',
                'default'      => '',
                'condition'    => array(
                    'item_link_type' => 'external',
                ),
            )
        );

        $repeater->add_control(
            'item_css_class',
            array(
                'label'   => esc_html__( 'Item CSS class', 'kitify' ),
                'type'    => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'image_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #1', 'kitify' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'kitify' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #2', 'kitify' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'kitify' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #3', 'kitify' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'kitify' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #4', 'kitify' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'kitify' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #5', 'kitify' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'kitify' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #6', 'kitify' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'kitify' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->add_control(
            'title_html_tag',
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

        $this->end_controls_section();

        $this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ], false );

        $this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns');

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_images_layout_general_style',
            array(
                'label'      => esc_html__( 'General', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'item_margin',
            array(
                'label' => esc_html__( 'Items Margin', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--kitify-carousel-item-left-space: {{SIZE}}{{UNIT}};--kitify-carousel-item-right-space: {{SIZE}}{{UNIT}};--kitify-gcol-left-space: {{SIZE}}{{UNIT}};--kitify-gcol-right-space: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['item']          => 'padding: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['list_container'] . ':not(.swiper-wrapper)' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
            )
        );

        $this->add_responsive_control(
            'item_border_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'item_shadow',
                'exclude' => array(
                    'box_shadow_position',
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
            )
        );

        $this->end_controls_section();

        /**
         * Icon Style Section
         */
        $this->start_controls_section(
            'section_images_layout_icon_style',
            array(
                'label'      => esc_html__( 'Icon', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-images-layout-icon-inner' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'icon_bg_color',
            array(
                'label' => esc_html__( 'Icon Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-images-layout-icon-inner' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em' ,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-images-layout-icon-inner' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => esc_html__( 'Icon Box Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-images-layout-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'icon_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-images-layout-icon-inner',
            )
        );

        $this->add_control(
            'icon_box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-images-layout-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_box_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-images-layout-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-images-layout-icon-inner',
            )
        );


        $this->add_control(
            'icon_horizontal_alignment',
            array(
                'label'   => esc_html__( 'Horizontal Alignment', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Left', 'kitify' ),
                    'center'        => esc_html__( 'Center', 'kitify' ),
                    'flex-end'      => esc_html__( 'Right', 'kitify' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['icon'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'icon_vertical_alignment',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Top', 'kitify' ),
                    'center'        => esc_html__( 'Center', 'kitify' ),
                    'flex-end'      => esc_html__( 'Bottom', 'kitify' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['icon'] => 'align-items: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_images_layout_title_style',
            array(
                'label'      => esc_html__( 'Title', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'  => esc_html__( 'Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
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

        $this->add_responsive_control(
            'title_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Description Style Section
         */
        $this->start_controls_section(
            'section_images_layout_desc_style',
            array(
                'label'      => esc_html__( 'Description', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'desc_color',
            array(
                'label'  => esc_html__( 'Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->add_responsive_control(
            'desc_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();



	    /**
	     * Button Style Section
	     */
	    $this->start_controls_section(
		    'section_button_style',
		    array(
			    'label'      => esc_html__( 'Button', 'kitify' ),
			    'tab'        => Controls_Manager::TAB_STYLE,
			    'show_label' => false,
		    )
	    );


	    $this->start_controls_tabs( 'tabs_button_style' );

	    $this->start_controls_tab(
		    'tab_button_normal',
		    array(
			    'label' => esc_html__( 'Normal', 'kitify' ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'button_bg',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			    'fields_options' => array(
				    'background' => array(
					    'default' => 'classic',
				    ),
				    'color' => array(
					    'label'  => _x( 'Background Color', 'Background Control', 'kitify' )
				    ),
				    'color_b' => array(
					    'label' => _x( 'Second Background Color', 'Background Control', 'kitify' ),
				    ),
			    ),
			    'exclude' => array(
				    'image',
				    'position',
				    'attachment',
				    'attachment_alert',
				    'repeat',
				    'size',
			    ),
		    )
	    );

	    $this->add_control(
		    'button_color',
		    array(
			    'label'     => esc_html__( 'Text Color', 'kitify' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    array(
			    'name'     => 'button_typography',
			    'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
		    )
	    );

	    $this->add_responsive_control(
		    'button_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'button_margin',
		    array(
			    'label'      => __( 'Margin', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'button_border_radius',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'button_border',
			    'label'       => esc_html__( 'Border', 'kitify' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    array(
			    'name'     => 'button_box_shadow',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
		    )
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_button_hover',
		    array(
			    'label' => esc_html__( 'Hover', 'kitify' ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'button_bg_hover',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			    'fields_options' => array(
				    'background' => array(
					    'default' => 'classic',
				    ),
				    'color' => array(
					    'label' => _x( 'Background Color', 'Background Control', 'kitify' ),
				    ),
				    'color_b' => array(
					    'label' => _x( 'Second Background Color', 'Background Control', 'kitify' ),
				    ),
			    ),
			    'exclude' => array(
				    'image',
				    'position',
				    'attachment',
				    'attachment_alert',
				    'repeat',
				    'size',
			    ),
		    )
	    );

	    $this->add_control(
		    'button_color_hover',
		    array(
			    'label'     => esc_html__( 'Text Color', 'kitify' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    array(
			    'name'     => 'button_typography_hover',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
		    )
	    );

	    $this->add_responsive_control(
		    'button_padding_hover',
		    array(
			    'label'      => esc_html__( 'Padding', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'button_margin_hover',
		    array(
			    'label'      => __( 'Margin', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'button_border_radius_hover',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'button_border_hover',
			    'label'       => esc_html__( 'Border', 'kitify' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover'
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    array(
			    'name'     => 'button_box_shadow_hover',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover'
		    )
	    );

	    $this->end_controls_tab();

	    $this->end_controls_tabs();

	    $this->end_controls_section();


        /**
         * Overlay Style Section
         */
        $this->start_controls_section(
            'section_images_layout_overlay_style',
            array(
                'label'      => esc_html__( 'Overlay', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_overlay_style' );

        $this->start_controls_tab(
            'tabs_overlay_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify'),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_background',
                'selector' => '{{WRAPPER}} .kitify-images-layout__content:before,{{WRAPPER}} .kitify-images-layout__image:after',
            )
        );

        $this->add_control(
            'overlay_opacity',
            array(
                'label'    => esc_html__( 'Opacity', 'kitify' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.6,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-images-layout__content:before' => 'opacity: {{VALUE}};',
                    '{{WRAPPER}} .kitify-images-layout__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_overlay_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify'),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_h_background',
                'selector' => '{{WRAPPER}} .kitify-images-layout__inner:hover .kitify-images-layout__content:before,{{WRAPPER}} .kitify-images-layout__inner:hover .kitify-images-layout__image:after'
            )
        );

        $this->add_control(
            'overlay_h_opacity',
            array(
                'label'    => esc_html__( 'Opacity', 'kitify' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.6,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-images-layout__inner:hover .kitify-images-layout__content:before' => 'opacity: {{VALUE}};',
                    '{{WRAPPER}} .kitify-images-layout__inner:hover .kitify-images-layout__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'overlay_paddings',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Order Style Section
         */
        $this->start_controls_section(
            'section_order_style',
            array(
                'label'      => esc_html__( 'Content Order and Alignment', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'item_title_order',
            array(
                'label'   => esc_html__( 'Title Order', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'item_content_order',
            array(
                'label'   => esc_html__( 'Content Order', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'item_button_order',
            array(
                'label'   => esc_html__( 'Button Order', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['button'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'item_content_alignment',
            array(
                'label'   => esc_html__( 'Content Vertical Alignment', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'flex-end',
                'options' => array(
                    'flex-start'    => esc_html__( 'Top', 'kitify' ),
                    'center'        => esc_html__( 'Center', 'kitify' ),
                    'flex-end'      => esc_html__( 'Bottom', 'kitify' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['content_wrap']  => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes' ] );
    }
    /**
     * Get loop image html
     *
     */

    public function get_loop_image_item() {

        $image_data = $this->_loop_image_item('item_image', '', false);

        if(!empty($image_data)){
	        $giflazy = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
	        $giflazy = $image_data[0];
            $srcset = sprintf('width="%d" height="%d" srcset="%s" style="--img-height:%dpx"', $image_data[1], $image_data[2], $giflazy, $image_data[2]);
            return sprintf( apply_filters('kitify/images-layout/image-format', '<img src="%1$s" data-src="%2$s" alt="" loading="lazy" class="%3$s" %4$s>'), $giflazy, $image_data[0], 'kitify-images-layout__image-instance' , $srcset);
        }

        return '';
    }

    /**
     * Get loop image html
     *
     */
    protected function _loop_image_item( $key = '', $format = '%s', $html_return = true ) {
        $item = $this->_processed_item;
        $params = [];

        if ( ! array_key_exists( $key, $item ) ) {
            return false;
        }

        $image_item = $item[ $key ];

        if ( ! empty( $image_item['id'] ) && wp_attachment_is_image($image_item['id']) ) {
            $image_data = wp_get_attachment_image_src( $image_item['id'], 'full' );

            $params[] = apply_filters('nova_wp_get_attachment_image_url', $image_data[0]);
            $params[] = $image_data[1];
            $params[] = $image_data[2];
        } else {
            $params[] = $image_item['url'];
            $params[] = 1200;
            $params[] = 800;
        }

        if($html_return){
            return vsprintf( $format, $params );
        }
        else{
            return $params;
        }
    }

    protected function _loop_icon( $format ){
        $item = $this->_processed_item;
        return $this->_get_icon_setting( $item['item_icon'], $format );
    }

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

}
