<?php

/**
 * Class: Kitify_Banner_List
 * Name: Banner List
 * Slug: kitify-banner-list
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Kitify_Banner_List Widget
 */
class Kitify_Banner_List extends Kitify_Base {

    /**
     * [$item_counter description]
     * @var integer
     */
    public $item_counter = 0;

    protected function enqueue_addon_resources(){
	    $this->add_script_depends( 'jquery-isotope' );
	    if(!kitify_settings()->is_combine_js_css()) {
		    wp_register_style( $this->get_name(), kitify()->plugin_url( 'assets/css/addons/banner-list.css' ), [ 'kitify-base' ], kitify()->get_version() );

		    $this->add_style_depends( $this->get_name() );
		    $this->add_script_depends( 'kitify-base' );
	    }
    }

    public function get_name() {
        return 'kitify-banner-list';
    }

    protected function get_widget_title() {
        return esc_html__( 'Banner List', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-banner-list';
    }

	public function get_keywords() {
		return [ 'banner', 'image', 'gallery', 'carousel', 'slide' ];
	}

    protected function register_controls() {

        $css_scheme = apply_filters(
            'kitify/banner-list/css-scheme',
            array(
                'instance'          => '.kitify-bannerlist',
                'list_container'    => '.kitify-bannerlist__list',
                'item'              => '.kitify-bannerlist__item',
                'inner'             => '.kitify-bannerlist__inner',
                'image'             => '.kitify-bannerlist__image',
                'image_instance'    => '.kitify-bannerlist__image-instance',
                'content'           => '.kitify-bannerlist__content',
                'content_inner'     => '.kitify-bannerlist__content-inner',
                'subtitle'          => '.kitify-bannerlist__subtitle',
                'title'             => '.kitify-bannerlist__title',
                'desc'              => '.kitify-bannerlist__desc',
                'subdesc'           => '.kitify-bannerlist__subdesc',
                'button'            => '.kitify-bannerlist__btn',
                'button_icon'       => '.btn-icon',
            )
        );

        $this->_register_section_setting($css_scheme);

        $this->_register_section_items($css_scheme);

        $this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ], false );

        $this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns');

        $this->_register_section_general_styles($css_scheme);

        $this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes' ] );
    }
    /**
     * Get loop image html
     *
     */

    public function get_loop_image_item() {
        $image_data = $this->_loop_image_item('item_image', '', false);
        if(!empty($image_data)){
	        $giflazy = $image_data[0];
            $srcset = sprintf('width="%1$d" height="%2$d" style="--img-height:%3$dpx"', $image_data[1], $image_data[2], $image_data[2]);
            return sprintf( apply_filters('kitify/banner-list/image-format', '<img src="%1$s" alt="" loading="lazy" class="%3$s" %4$s>'), $giflazy, $image_data[0], 'kitify-bannerlist__image-instance' , $srcset);
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

            $params[] = apply_filters('lastudio_wp_get_attachment_image_url', $image_data[0]);
            $params[] = $image_data[1];
            $params[] = $image_data[2];
        }
        else {
            $params[] = isset($image_item['url']) ? $image_item['url'] : Utils::get_placeholder_image_src();
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

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

    private function _register_section_setting( $css_scheme ){
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
                'default' => 'overlay',
                'options' => array(
                    'overlay'    => esc_html__( 'Overlay', 'kitify' ),
                    'flat'    => esc_html__( 'Flat', 'kitify' ),
                ),
            )
        );

        $this->add_control(
            'preset_overlay',
            array(
                'label'   => esc_html__( 'Preset', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'prefix_class' => 'bannerlist--preset-',
                'options' => apply_filters('kitify/banner-list/preset_overlay', [
                    'default' => esc_html__( 'Default', 'kitify' ),
                ]),
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            )
        );
        $this->add_control(
            'preset_flat',
            array(
                'label'   => esc_html__( 'Preset', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'prefix_class' => 'bannerlist--preset-',
                'options' => apply_filters('kitify/banner-list/preset_flat', [
                    'default' => esc_html__( 'Default', 'kitify' ),
                ]),
                'condition' => [
                    'layout_type' => 'flat'
                ]
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => kitify_helper()->get_select_range( 10 ),
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

        $this->end_controls_section();
    }

    private function _register_section_items( $css_scheme ){

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'kitify' ),
            )
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'items_repeater' );

        $repeater->start_controls_tab( 'background', [ 'label' => __( 'Image', 'kitify' ) ] );

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

        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'content', [ 'label' => __( 'Content', 'kitify' ) ] );

        $repeater->add_control(
            'subtitle',
            [
                'label' => __( 'Sub Title', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Banner Title', 'kitify' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'kitify' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'subdescription',
            [
                'label' => __( 'Sub-Description', 'kitify' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Click Here', 'kitify' ),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'kitify' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'kitify' ),
            ]
        );

        $repeater->add_control(
            'link_click',
            [
                'label' => __( 'Apply Link On', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'item' => __( 'Whole item', 'kitify' ),
                    'button' => __( 'Button Only', 'kitify' ),
                ],
                'default' => 'item',
                'condition' => [
                    'link[url]!' => '',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'style', [ 'label' => __( 'Style', 'kitify' ) ] );

        $repeater->add_control(
            'el_class',
            [
                'label' => __( 'Item CSS Class', 'kitify' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $repeater->add_control(
            'custom_style',
            [
                'label' => __( 'Custom', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'description' => __( 'Set custom style that will only affect this specific item.', 'kitify' ),
            ]
        );

        $repeater->add_responsive_control(
            'icontent_width',
            [
                'label' => __( 'Content Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--kitify-bannerlist-content-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'icontent_horizontal',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => is_rtl() ? 'right' : 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'icontent_offset_x',
            [
                'label' => esc_html__( 'Offset', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['content_inner'] => 'left: initial; right: initial;{{icontent_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'icontent_vertical',
            [
                'label' => esc_html__( 'Vertical Orientation', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
                'toggle' => false,
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'icontent_offset_y',
            [
                'label' => esc_html__( 'Offset', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['content_inner'] => 'top: initial; bottom: initial;{{icontent_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'icontent_padding',
            [
                'label' => __( 'Content Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--kitify-bannerlist-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'icontent_margin',
            [
                'label' => __( 'Content Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--kitify-bannerlist-content-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'itext_align',
            [
                'label' => __( 'Text Align', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['content_inner'] => 'text-align: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'isubtitle_color',
            [
                'label' => __( 'Sub-Title Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['subtitle'] => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ititle_color',
            [
                'label' => __( 'Title Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'idesc_color',
            [
                'label' => __( 'Description Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'isubdesc_color',
            [
                'label' => __( 'SubDescription Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} '  . $css_scheme['subdesc'] => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ibtn_color',
            [
                'label' => __( 'Button Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .kitify-bannerlist__btn:not(:hover)' => 'color: {{VALUE}}; border-color: {{VALUE}}; background-color: transparent',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_fontsize',
                'label' => __( 'Subtitle font size', 'kitify' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['subtitle'],
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_fontsize',
                'label' => __( 'Title font size', 'kitify' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['title'],
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_fontsize',
                'label' => __( 'Description font size', 'kitify' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['desc'],
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'subdesc_fontsize',
                'label' => __( 'SubDescription font size', 'kitify' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['subdesc'],
            )
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'image_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(),
                'title_field' => '{{{ title }}}',
            )
        );

        $this->end_controls_section();
    }

    private function _register_section_general_styles( $css_scheme ){
        $this->start_controls_section(
            'section_style_items',
            array(
                'label' => esc_html__( 'Item', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );
        $this->add_responsive_control(
            'item_gap',
            array(
                'label' => esc_html__( 'Item Gap', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--kitify-bannerlist-col-gap: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'item_row_gap',
            array(
                'label' => esc_html__( 'Row Spacing', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}}' => '--kitify-bannerlist-row-gap: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'item_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
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

        $this->start_controls_section(
            'section_style_image',
            array(
                'label' => esc_html__( 'Image', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
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
            )
        );

        $this->add_control(
            'enable_image_original_size',
            array(
                'label'        => esc_html__( 'Enable image original size', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class' => 'enable-original-size-',
            )
        );

        $this->add_responsive_control(
            'image_height',
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
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ),
                'condition' => [
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->start_controls_tabs( 'tabs_image_style' );

        $this->start_controls_tab(
            'tabs_image_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify'),
            )
        );
        $this->add_control(
            'image_opacity',
            [
                'label' => esc_html__( 'Opacity', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-bannerlist__image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_scale',
            [
                'label' => __( 'Image Scale', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => '--kitify-bannerlist-image-scale: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filter',
                'selector' => '{{WRAPPER}} .kitify-bannerlist__image img',
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            array(
                'label'      => esc_html__( 'Image Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'image_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .kitify-bannerlist__image'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_shadow',
                'selector'    => '{{WRAPPER}} .kitify-bannerlist__image'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_image_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify'),
            )
        );

        $this->add_control(
            'image_opacity_hover',
            [
                'label' => esc_html__( 'Opacity', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-bannerlist__link:hover .kitify-bannerlist__image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_scale_hover',
            [
                'label' => __( 'Image Scale', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => '--kitify-bannerlist-image-scale-hover: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filter_hover',
                'selector' => '{{WRAPPER}} .kitify-bannerlist__link:hover .kitify-bannerlist__image img',
            ]
        );

        $this->add_responsive_control(
            'image_padding_hover',
            array(
                'label'      => esc_html__( 'Image Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__link:hover .kitify-bannerlist__image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_radius_hover',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__link:hover .kitify-bannerlist__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'image_border_hover',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .kitify-bannerlist__link:hover .kitify-bannerlist__image'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_shadow_hover',
                'selector'    => '{{WRAPPER}} ..kitify-bannerlist__link:hover .kitify-bannerlist__image'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image_overlay',
            array(
                'label' => esc_html__( 'Image Overlay', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
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
                'selector' => '{{WRAPPER}} .kitify-bannerlist__image:after',
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
                    '{{WRAPPER}} .kitify-bannerlist__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );

        $this->add_control(
            'overlay_zindex',
            array(
                'label'    => esc_html__( 'Z-Index', 'kitify' ),
                'type'     => Controls_Manager::NUMBER,
                'min'      => -1,
                'max'      => 10,
                'step'     => 1,
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__image:after' => 'z-index: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'overlay_position',
            array(
                'label'      => __( 'Position', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__image:after' => 'top:{{TOP}}{{UNIT}};right:{{RIGHT}}{{UNIT}};bottom:{{BOTTOM}}{{UNIT}};left:{{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'overlay_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__image:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'overlay_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .kitify-bannerlist__image:after'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'overlay_shadow',
                'selector'    => '{{WRAPPER}} .kitify-bannerlist__image:after'
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
                'name'     => 'overlay_background_hover',
                'selector' => '{{WRAPPER}} .kitify-bannerlist__inner:hover .kitify-bannerlist__image:after'
            )
        );

        $this->add_control(
            'overlay_opacity_hover',
            array(
                'label'    => esc_html__( 'Opacity', 'kitify' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.6,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__inner:hover .kitify-bannerlist__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );
        $this->add_responsive_control(
            'overlay_position_hover',
            array(
                'label'      => __( 'Position', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__inner:hover .kitify-bannerlist__image:after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'overlay_radius_hover',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-bannerlist__inner:hover .kitify-bannerlist__image:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'overlay_border_hover',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .kitify-bannerlist__inner:hover .kitify-bannerlist__image:after'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'overlay_shadow_hover',
                'selector'    => '{{WRAPPER}} .kitify-bannerlist__inner:hover .kitify-bannerlist__image:after'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            array(
                'label' => esc_html__( 'Content', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );

        $this->_add_control(
            'content_visible_on_hover',
            [
                'label'        => esc_html__( 'Show on hover', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'return_value' => 'yes',
                'prefix_class' => 'content-visible-hover-',
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->add_responsive_control(
            'content_width',
            [
                'label' => __( 'Content Width', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--kitify-bannerlist-content-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->_add_control(
            'content_horizontal',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => is_rtl() ? 'right' : 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->add_responsive_control(
            'content_offset_x',
            [
                'label' => esc_html__( 'Offset', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => '{{content_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->_add_control(
            'content_vertical',
            [
                'label' => esc_html__( 'Vertical Orientation', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
                'toggle' => false,
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->add_responsive_control(
            'content_offset_y',
            [
                'label' => esc_html__( 'Offset', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => '{{content_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'content_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'content_border',
                'label'       => esc_html__( 'Border', 'kitify'),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'content_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->add_responsive_control(
            'content_radius',
            [
                'label' => __( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--kitify-bannerlist-content-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Content Padding', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--kitify-bannerlist-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => __( 'Content Margin', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} '=> '--kitify-bannerlist-content-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'content_text_align',
            [
                'label' => __( 'Text Align', 'kitify' ),
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
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $sections = [
            'title' => __('Title', 'kitify'),
            'subtitle' => __('Sub Title', 'kitify'),
            'desc' => __('Description', 'kitify'),
            'subdesc' => __('Sub Description', 'kitify'),
        ];
        foreach ($sections as $section_key => $section_label){
            $this->start_controls_section(
                'section_style_' . $section_key,
                array(
                    'label' => $section_label,
                    'tab'        => Controls_Manager::TAB_STYLE,
                )
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => $section_key . '_typography',
                    'selector' => '{{WRAPPER}} ' . $css_scheme[$section_key],
                )
            );
            $this->add_control(
                $section_key .'_color',
                array(
                    'label' => esc_html__( 'Color', 'kitify' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme[$section_key] => 'color: {{VALUE}}',
                    ),
                )
            );
            $this->add_responsive_control(
                $section_key .'_margin',
                array(
                    'label'      => __( 'Margin', 'kitify' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme[$section_key] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => $section_key . '_border',
                    'label'       => esc_html__( 'Border', 'kitify' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '{{WRAPPER}} ' . $css_scheme[$section_key],
                )
            );
            $this->end_controls_section();
        }

        /**
         * Button Style Section
         */
        $this->start_controls_section(
            'section_style_button',
            array(
                'label'      => esc_html__( 'Button', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_icon_control(
            'btn_icon',
            [
                'label'       => __( 'Add Icon', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'file'        => '',
                'skin'        => 'inline',
                'label_block' => false
            ]
        );

        $this->_add_control(
            'btn_icon_position',
            array(
                'label'     => esc_html__( 'Icon Position', 'kitify' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'row-reverse' => esc_html__( 'Before Text', 'kitify' ),
                    'row'         => esc_html__( 'After Text', 'kitify' ),
                ),
                'default'   => 'row',
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'flex-direction: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'btn_icon_size',
            array(
                'label' => esc_html__( 'Icon Size', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'button_icon_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'after',
            ),
            50
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
    }
}
