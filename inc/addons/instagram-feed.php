<?php

/**
 * Class: Kitify_Instagram_Feed
 * Name: Instagram Feed
 * Slug: kitify-instagram-feed
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Images_Layout Widget
 */
class Kitify_Instagram_Feed extends Kitify_Base {

    /**
     * Instagram API-server URL.
     *
     * @since 1.0.0
     * @var string
     */
    private $api_url = 'https://www.instagram.com/';

    /**
     * New Instagram API-server URL.
     *
     * @var string
     */
    private $new_api_url = 'https://graph.instagram.com/';

    /**
     * Graph Api Url.
     *
     * @var string
     */
    private $graph_api_url = 'https://graph.facebook.com/';

    /**
     * Access token.
     *
     * @var string
     */
    private $access_token = null;

    /**
     * Business account config.
     *
     * @var array|null
     */
    private $business_account_config = null;

    /**
     * Request config
     *
     * @var array
     */
    public $config = array();

    protected function enqueue_addon_resources(){
	    $this->add_script_depends( 'jquery-isotope' );
	    if(!kitify_settings()->is_combine_js_css()) {
		    wp_register_style( $this->get_name(), kitify()->plugin_url( 'assets/css/addons/instagram-feed.css' ), [ 'kitify-base' ], kitify()->get_version() );

		    $this->add_style_depends( $this->get_name() );
		    $this->add_script_depends( 'kitify-base' );
	    }
    }

    public function get_name() {
        return 'kitify-instagram-feed';
    }

    protected function get_widget_title() {
        return esc_html__( 'Instagram Feed', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-instagram';
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'kitify/images-layout/css-scheme',
            array(
                'instance'          => '.kitify-instagram-feed',
                'list_container'    => '.kitify-instagram-feed__list',
                'item'              => '.kitify-instagram-feed__item',
                'inner'             => '.kitify-instagram-feed__inner',
                'image_wrap'        => '.kitify-instagram-feed__image',
                'image_instance'    => '.kitify-instagram-feed__image-instance',
                'content_wrap'      => '.kitify-instagram-feed__content',
                'icon'              => '.kitify-instagram-feed__icon',
                'title'             => '.kitify-instagram-feed__title',
                'desc'              => '.kitify-instagram-feed__desc',
                'button'            => '.kitify-instagram-feed__button',
                'button_icon'       => '.kitify-instagram-feed__button .btn-icon',
            )
        );


        $this->start_controls_section(
            'section_instagram_settings',
            array(
                'label' => esc_html__( 'Instagram Settings', 'kitify' ),
            )
        );

        $this->add_control(
            'endpoint',
            array(
                'label'   => esc_html__( 'What to display', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'self',
                'options' => array(
                    'self'     => esc_html__( 'My Photos', 'kitify' ),
//                    'hashtag'  => esc_html__( 'Tagged Photos', 'kitify' ),
                ),
            )
        );

        $this->add_control(
            'hashtag',
            array(
                'label'       => esc_html__( 'Hashtag (enter without `#` symbol)', 'kitify' ),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'condition' => array(
                    'endpoint' => 'hashtag',
                ),
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                    ),
                ),
            )
        );

        $this->add_control(
            'use_insta_graph_api',
            array(
                'label'     => esc_html__( 'Use Instagram Graph API', 'kitify' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => '',
                'condition' => array(
                    'endpoint' => 'hashtag',
                ),
            )
        );

        $business_account_config = $this->get_business_account_config();

        if ( empty( $business_account_config['token'] ) || empty( $business_account_config['user_id'] ) ) {
            $this->add_control(
                'set_business_access_token',
                array(
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => sprintf(
                        esc_html__( 'Please set Business Instagram Access Token and User ID on the %1$s.', 'kitify' ),
                        '<a target="_blank" href="' . kitify_settings()->get_settings_page_link( 'integrations' ) . '">' . esc_html__( 'settings page', 'kitify' ) . '</a>'
                    ),
                    'content_classes' => 'elementor-descriptor',
                    'condition' => array(
                        'endpoint'            => 'hashtag',
                        'use_insta_graph_api' => 'yes',
                    ),
                )
            );
        }

        $this->add_control(
            'order_by',
            array(
                'label'   => esc_html__( 'Order By', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'recent_media',
                'options' => array(
                    'recent_media' => esc_html__( 'Recent Media', 'kitify' ),
                    'top_media'    => esc_html__( 'Top Media', 'kitify' ),
                ),
                'condition' => array(
                    'endpoint'            => 'hashtag',
                    'use_insta_graph_api' => 'yes',
                ),
            )
        );

        $this->add_control(
            'access_token_source',
            array(
                'label'   => esc_html__( 'Access Token', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    '' => esc_html__( 'Default', 'kitify' ),
                    'custom'  => esc_html__( 'Custom', 'kitify' ),
                ),
                'condition' => array(
                    'endpoint' => 'self',
                ),
            )
        );

        if ( ! $this->get_access_token_from_settings() ) {
            $this->add_control(
                'set_access_token',
                array(
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => sprintf(
                        esc_html__( 'Please set Instagram Access Token on the %1$s.', 'kitify' ),
                        '<a target="_blank" href="' . kitify_settings()->get_settings_page_link( 'integrations' ) . '">' . esc_html__( 'settings page', 'kitify' ) . '</a>'
                    ),
                    'content_classes' => 'elementor-descriptor',
                    'condition' => array(
                        'endpoint' => 'self',
                        'access_token_source' => '',
                    ),
                )
            );
        }

        $this->add_control(
            'custom_access_token',
            array(
                'label'       => esc_html__( 'Custom Access Token', 'kitify' ),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'condition' => array(
                    'endpoint' => 'self',
                    'access_token_source' => 'custom',
                ),
                'dynamic' => array(
                    'active'     => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                    ),
                ),
            )
        );

        $this->add_control(
            'cache_timeout',
            array(
                'label'   => esc_html__( 'Cache Timeout', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hour',
                'options' => array(
                    'none'   => esc_html__( 'None', 'kitify' ),
                    'minute' => esc_html__( 'Minute', 'kitify' ),
                    'hour'   => esc_html__( 'Hour', 'kitify' ),
                    'day'    => esc_html__( 'Day', 'kitify' ),
                    'week'   => esc_html__( 'Week', 'kitify' ),
                ),
            )
        );

        $this->add_control(
            'photo_size',
            array(
                'label'   => esc_html__( 'Photo Size', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'high',
                'options' => array(
                    'thumbnail' => esc_html__( 'Thumbnail (150x150)', 'kitify' ),
                    'low'       => esc_html__( 'Low (320x320)', 'kitify' ),
                    'standard'  => esc_html__( 'Standard (640x640)', 'kitify' ),
                    'high'      => esc_html__( 'High (original)', 'kitify' ),
                ),
            )
        );

        $this->add_control(
            'posts_counter',
            array(
                'label'   => esc_html__( 'Number of instagram posts', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => 1,
                'max'     => 50,
                'step'    => 1,
            )
        );

        $this->add_control(
            'post_link',
            array(
                'label'        => esc_html__( 'Enable linking photos', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'post_link_type',
            array(
                'label'   => esc_html__( 'Link type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post-link',
                'options' => array(
                    'post-link' => esc_html__( 'Post Link', 'kitify' ),
                    'lightbox'  => esc_html__( 'Lightbox', 'kitify' ),
                ),
                'condition' => array(
                    'post_link' => 'yes',
                ),
            )
        );

        $this->add_control(
            'post_caption',
            array(
                'label'        => esc_html__( 'Enable caption', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'post_caption_length',
            array(
                'label'   => esc_html__( 'Caption length', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 50,
                'min'     => 1,
                'max'     => 300,
                'step'    => 1,
                'condition' => array(
                    'post_caption' => 'yes',
                ),
            )
        );

        $this->add_control(
            'post_comments_count',
            array(
                'label'        => esc_html__( 'Enable Comments Count', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'endpoint!' => 'self',
                ),
            )
        );

        $this->add_control(
            'post_likes_count',
            array(
                'label'        => esc_html__( 'Enable Likes Count', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'endpoint!' => 'self',
                ),
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
                    'type-3' => esc_html__( 'Type 3', 'kitify' ),
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
                'label' => esc_html__( 'Item Spacing', 'kitify' ),
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

        $this->add_responsive_control(
            'item_row_gap',
            array(
                'label' => esc_html__( 'Row Spacing', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item']          => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-instagram-feed-icon-inner' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'icon_bg_color',
            array(
                'label' => esc_html__( 'Icon Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-instagram-feed-icon-inner' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-instagram-feed-icon-inner' => 'font-size: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-instagram-feed-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-instagram-feed-icon-inner',
            )
        );

        $this->add_control(
            'icon_box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-instagram-feed-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-instagram-feed-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .kitify-instagram-feed-icon-inner',
            )
        );

        $this->end_controls_section();

        /**
         * Description Style Section
         */
        $this->start_controls_section(
            'section_images_layout_desc_style',
            array(
                'label'      => esc_html__( 'Caption', 'kitify' ),
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
                'selector' => '{{WRAPPER}} .kitify-instagram-feed__content:before,{{WRAPPER}} .kitify-instagram-feed__image:after',
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
                    '{{WRAPPER}} .kitify-instagram-feed__content:before' => 'opacity: {{VALUE}};',
                    '{{WRAPPER}} .kitify-instagram-feed__image:after' => 'opacity: {{VALUE}};'
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
                'selector' => '{{WRAPPER}} .kitify-instagram-feed__inner:hover .kitify-instagram-feed__content:before,{{WRAPPER}} .kitify-instagram-feed__inner:hover .kitify-instagram-feed__image:after'
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
                    '{{WRAPPER}} .kitify-instagram-feed__inner:hover .kitify-instagram-feed__content:before' => 'opacity: {{VALUE}};',
                    '{{WRAPPER}} .kitify-instagram-feed__inner:hover .kitify-instagram-feed__image:after' => 'opacity: {{VALUE}};'
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

	    $this->add_responsive_control(
		    'item_content_halignment',
		    array(
			    'label'   => esc_html__( 'Content Alignment', 'kitify' ),
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
			    'selectors'  => array(
				    '{{WRAPPER}} '. $css_scheme['content_wrap']  => 'align-items: {{VALUE}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'item_content_alignment',
		    array(
			    'label'   => esc_html__( 'Content Vertical Alignment', 'kitify' ),
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
			    'selectors'  => array(
				    '{{WRAPPER}} '. $css_scheme['content_wrap']  => 'justify-content: {{VALUE}};',
			    ),
		    )
	    );

	    $this->add_control(
		    'content_bg',
		    array(
			    'label'     => esc_html__( 'Content Background', 'kitify' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'background-color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'item_content_padding',
		    array(
			    'label'      => __( 'Content Padding', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%', 'vh'],
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

        $this->end_controls_section();

        $this->register_carousel_arrows_dots_style_section( [ 'enable_carousel' => 'yes' ] );
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
            return sprintf( apply_filters('kitify/images-layout/image-format', '<img src="%1$s" data-src="%2$s" alt="" loading="lazy" class="%3$s" %4$s>'), $giflazy, $image_data[0], 'kitify-instagram-feed__image-instance' , $srcset);
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

            $params[] = apply_filters('kitify_wp_get_attachment_image_url', $image_data[0]);
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

	protected function _btn_icon( $format ){
		$settings = $this->get_settings_for_display();
		return $this->_get_icon_setting( $settings['selected_btn_icon'], $format );
	}

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }


    /**
     * Render gallery html.
     *
     * @return void
     */
    public function render_gallery() {
        $settings = $this->get_settings_for_display();

        if ( 'hashtag' === $settings['endpoint'] ) {

            if ( empty( $settings['hashtag'] ) ) {
                $this->print_notice( esc_html__( 'Please, enter #hashtag.', 'kitify' ) );
                return;
            }

            if ( ! empty( $settings['use_insta_graph_api'] ) ) {
                $business_account_config = $this->get_business_account_config();

                if ( empty( $business_account_config['token'] || empty( $business_account_config['user_id'] ) ) ) {
                    $this->print_notice( esc_html__( 'Please, enter Business Access Token and User ID.', 'kitify' ) );
                    return;
                }
            }
        }

        if ( 'self' === $settings['endpoint'] && ! $this->get_access_token() ) {
            $this->print_notice( esc_html__( 'Please, enter Access Token.', 'kitify' ) );
            return;
        }

        $html = '';
        $col_class = '';

        $enable_carousel  = filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN);
        if($enable_carousel){
            $col_class = ' swiper-slide';
        }
        if ( ('grid' == $settings['layout_type'] || 'masonry' == $settings['layout_type']) && !$enable_carousel ) {
            $col_class = kitify_helper()->col_new_classes('columns', $settings);
        }

        // Endpoint.
        $endpoint = $this->sanitize_endpoint();

        switch ( $settings['cache_timeout'] ) {
            case 'none':
                $cache_timeout = 1;
                break;

            case 'minute':
                $cache_timeout = MINUTE_IN_SECONDS;
                break;

            case 'hour':
                $cache_timeout = HOUR_IN_SECONDS;
                break;

            case 'day':
                $cache_timeout = DAY_IN_SECONDS;
                break;

            case 'week':
                $cache_timeout = WEEK_IN_SECONDS;
                break;

            default:
                $cache_timeout = HOUR_IN_SECONDS;
                break;
        }

        $this->config = array(
            'endpoint'            => $endpoint,
            'target'              => ( 'hashtag' === $endpoint ) ? sanitize_text_field( $settings[ $endpoint ] ) : 'users',
            'posts_counter'       => $settings['posts_counter'],
            'post_link'           => filter_var( $settings['post_link'], FILTER_VALIDATE_BOOLEAN ),
            'post_link_type'      => $settings['post_link_type'],
            'photo_size'          => $settings['photo_size'],
            'post_caption'        => filter_var( $settings['post_caption'], FILTER_VALIDATE_BOOLEAN ),
            'post_caption_length' => ! empty( $settings['post_caption_length'] ) ? $settings['post_caption_length'] : 50,
            'post_comments_count' => filter_var( $settings['post_comments_count'], FILTER_VALIDATE_BOOLEAN ),
            'post_likes_count'    => filter_var( $settings['post_likes_count'], FILTER_VALIDATE_BOOLEAN ),
            'cache_timeout'       => $cache_timeout,
            'use_graph_api'       => isset( $settings['use_insta_graph_api'] ) ? filter_var( $settings['use_insta_graph_api'], FILTER_VALIDATE_BOOLEAN ) : false,
            'order_by'            => ! empty( $settings['order_by'] ) ? $settings['order_by'] : 'recent_media',
        );

        $posts = $this->get_posts( $this->config );

        if ( ! empty( $posts ) && ! is_wp_error( $posts ) ) {

            $c_icon = '<div class="kitify-instagram-feed__icon"><div class="kitify-instagram-feed-icon-inner"><i aria-hidden="true" class="kitifyicon fab fa-instagram"></i></div></div>';

            foreach ( $posts as $post_data ) {
                $item_html   = '';
                $link        = ( 'hashtag' === $endpoint && ! $this->config['use_graph_api'] ) ? sprintf( $this->get_post_url(), $post_data['link'] ) : $post_data['link'];
                $the_image   = $this->the_image( $post_data );
                $the_caption = $c_icon . $this->the_caption( $post_data );
                $the_meta    = $this->the_meta( $post_data );

                $item_html = sprintf(
                    '<div class="kitify-instagram-feed__image">%1$s</div><div class="kitify-instagram-feed__content">%2$s%3$s</div>',
                    $the_image,
                    $the_caption,
                    $the_meta
                );

                if ( $this->config['post_link'] ) {
                    $link_format = '<a class="kitify-instagram-feed__link" href="%1$s" target="_blank" rel="nofollow"%3$s>%2$s</a>';
                    $link_format = apply_filters( 'kitify/instagram-gallery/link-format', $link_format );

                    $attr = '';

                    if ( 'lightbox' === $this->config['post_link_type'] ) {
                        $img_data = $this->get_image_data( $post_data, 'high' );
                        $link = $img_data['src'];
                        $attr = ' data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . $this->get_id() . '"';
                    }

                    $item_html = sprintf( $link_format, esc_url( $link ), $item_html, $attr );
                }

                $html .= sprintf( '<div class="kitify-instagram-feed__item %s"><div class="kitify-instagram-feed__inner">%s</div></div>', $col_class, $item_html );
            }

        } else {
            $message = is_wp_error( $posts ) ? $posts->get_error_message() : esc_html__( 'Posts not found', 'kitify' );

            $html .= sprintf(
                '<div class="kitify-instagram-feed__item"><div class="kitify-instagram-feed__inner">%s</div></div>',
                $message
            );
        }

        echo $html;
    }

    /**
     * Print widget notice.
     *
     * @param $notice
     */
    public function print_notice( $notice ) {
        if ( ! is_user_logged_in() ) {
            return;
        }

        printf( '<div class="kitify-instagram-feed__notice">%s</div>', $notice );
    }

    /**
     * Display a HTML link with image.
     *
     * @since  1.0.0
     * @param  array $item Item photo data.
     * @return string
     */
    public function the_image( $item ) {

        $size = $this->get_settings_for_display( 'photo_size' );

        $img_data = $this->get_image_data( $item, $size );

        $width          = $img_data['width'];
        $height         = $img_data['height'];
        $post_photo_url = $img_data['src'];

        if ( empty( $post_photo_url ) ) {
            return '';
        }

        $attr = '';

        if ( ! empty( $width ) && ! empty( $height ) ) {
            $attr = ' width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '"';
        }

        $photo_format = '<img class="kitify-instagram-feed__image-instance" src="%1$s"%2$s alt="%3$s" loading="lazy">';
        $photo_format = apply_filters( 'kitify/instagram-gallery/photo-format', $photo_format );

        $image = sprintf( $photo_format, esc_url( $post_photo_url ), $attr, esc_attr( $item['caption'] ) );

        return $image;
    }

    /**
     * Get image data
     *
     * @param  array  $item Item photo data.
     * @param  string $size Image size.
     * @return array
     */
    public function get_image_data( $item, $size = 'high' ) {
        $thumbnail_resources = $item['thumbnail_resources'];

        if ( ! empty( $thumbnail_resources[ $size ] ) ) {
            $width = $thumbnail_resources[ $size ]['config_width'];
            $height = $thumbnail_resources[ $size ]['config_height'];
            $post_photo_url = $thumbnail_resources[ $size ]['src'];
        } else {
            $width = isset( $item['dimensions']['width'] ) ? $item['dimensions']['width'] : '';
            $height = isset( $item['dimensions']['height'] ) ? $item['dimensions']['height'] : '';
            $post_photo_url = isset( $item['image'] ) ? $item['image'] : '';
        }

        return array(
            'width'  => $width,
            'height' => $height,
            'src'    => $post_photo_url,
        );
    }

    /**
     * Display a caption.
     *
     * @since  1.0.0
     * @param  array $item Item photo data.
     * @return string
     */
    public function the_caption( $item ) {

        if ( ! $this->config['post_caption'] || empty( $item['caption'] ) ) {
            return;
        }

        $format = apply_filters(
            'kitify/instagram-gallery/the-caption-format', '<div class="kitify-instagram-feed__desc">%s</div>'
        );

        return sprintf( $format, $item['caption'] );
    }

    /**
     * Display a meta.
     *
     * @since  1.0.0
     * @param  array $item Item photo data.
     * @return string
     */
    public function the_meta( $item ) {

        if ( ! $this->config['post_comments_count'] && ! $this->config['post_likes_count'] ) {
            return;
        }

        $meta_html = '';

        if ( $this->config['post_comments_count'] ) {
            $meta_html .= sprintf(
                '<div class="kitify-instagram-feed__meta-item kitify-instagram-feed__comments-count"><span class="kitify-instagram-feed__comments-icon kitify-instagram-feed__meta-icon"><i class="%s"></i></span><span class="kitify-instagram-feed__comments-label kitify-instagram-feed__meta-label">%s</span></div>',
                $this->get_settings_for_display( 'comments_icon' ),
                $item['comments']
            );
        }

        if ( $this->config['post_likes_count'] ) {
            $meta_html .= sprintf(
                '<div class="kitify-instagram-feed__meta-item kitify-instagram-feed__likes-count"><span class="kitify-instagram-feed__likes-icon kitify-instagram-feed__meta-icon"><i class="%s"></i></span><span class="kitify-instagram-feed__likes-label kitify-instagram-feed__meta-label">%s</span></div>',
                $this->get_settings_for_display( 'likes_icon' ),
                $item['likes']
            );
        }

        $format = apply_filters( 'kitify/instagram-gallery/the-meta-format', '<div class="kitify-instagram-feed__meta">%s</div>' );

        return sprintf( $format, $meta_html );
    }

    /**
     * Retrieve a photos.
     *
     * @since  1.0.0
     * @param  array $config Set of configuration.
     * @return array
     */
    public function get_posts( $config ) {

        $transient_key = md5( $this->get_transient_key() );

        $data = get_transient( $transient_key );

        if ( ! empty( $data ) && 1 !== $config['cache_timeout'] && array_key_exists( 'thumbnail_resources', $data[0] ) ) {
            return $data;
        }

        if ( $config['use_graph_api'] ) {
            $response = $this->remote_get_from_qraph_api( $config );
        } else {
            $response = $this->remote_get( $config );
        }

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( 'hashtag' === $config['endpoint'] && ! $config['use_graph_api'] ) {
            $data = $this->get_response_data( $response );
        } else {
            $data = $this->get_response_data_from_official_api( $response );
        }

        if ( empty( $data ) ) {
            return array();
        }

        set_transient( $transient_key, $data, $config['cache_timeout'] );

        return $data;
    }

    /**
     * Retrieve the raw response from the HTTP request using the GET method from Graph API.
     *
     * @param array $config
     *
     * @return mixed|string|void|\WP_Error
     */
    public function remote_get_from_qraph_api( $config ) {

        $account_config = $this->get_business_account_config();

        $access_token = $account_config['token'];
        $user_id      = $account_config['user_id'];

        $url = add_query_arg(
            array(
                'user_id'      => $user_id,
                'q'            => $config['target'],
                'access_token' => $access_token,
            ),
            $this->graph_api_url . 'ig_hashtag_search'
        );

        $response = wp_remote_get( $url, array( 'timeout' => 10 ) );

        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );

        if ( ! is_array( $body ) ) {
            return new \WP_Error( 'invalid-data', esc_html__( 'Invalid data', 'kitify' ) );
        }

        if ( isset( $body['error']['message'] ) ) {
            return new \WP_Error( 'invalid-data', $body['error']['message'] );
        }

        if ( empty( $body['data'][0]['id'] ) ) {
            return new \WP_Error( 'invalid-data', esc_html__( 'Can\'t find the tag ID.', 'kitify' ) );
        }

        $tag_id = $body['data'][0]['id'];

        $url = add_query_arg(
            array(
                'user_id'      => $user_id,
                'access_token' => $access_token,
                'limit'        => 50,
                'fields'       => 'id,media_type,media_url,caption,comments_count,like_count,permalink,children{media_url,id,media_type,permalink}',
            ),
            $this->graph_api_url . $tag_id . '/' . $config['order_by']
        );

        $response = wp_remote_get( $url, array( 'timeout' => 10 ) );

        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );

        if ( ! is_array( $body ) ) {
            return new \WP_Error( 'invalid-data', esc_html__( 'Invalid data', 'kitify' ) );
        }

        if ( isset( $body['error']['message'] ) ) {
            return new \WP_Error( 'invalid-data', $body['error']['message'] );
        }

        return $body;
    }

    /**
     * Retrieve the raw response from the HTTP request using the GET method.
     *
     * @since  1.0.0
     * @return array|object
     */
    public function remote_get( $config ) {

        $url = $this->get_grab_url( $config );

        $response = wp_remote_get( $url, array(
            'timeout' => 60,
        ) );

        $response_code = wp_remote_retrieve_response_code( $response );

        if ( 200 !== $response_code ) {

            $body = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( is_array( $body ) && isset( $body['error']['message'] ) ) {
                $message = $body['error']['message'];
            } else {
                $message = esc_html__( 'Posts not found', 'kitify' );
            }

            return new \WP_Error( $response_code, $message );
        }

        $result = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ! is_array( $result ) ) {
            return new \WP_Error( 'invalid-data', esc_html__( 'Invalid data', 'kitify' ) );
        }

        return $result;
    }

    /**
     * Get prepared response data.
     *
     * @param $response
     *
     * @return array
     */
    public function get_response_data( $response ) {

        $key = 'hashtag' == $this->config['endpoint'] ? 'hashtag' : 'user';

        if ( 'hashtag' === $key ) {
            $response = isset( $response['graphql'] ) ? $response['graphql'] : $response;
        }

        $response_items = ( 'hashtag' === $key ) ? $response[ $key ]['edge_hashtag_to_media']['edges'] : $response['graphql'][ $key ]['edge_owner_to_timeline_media']['edges'];

        if ( empty( $response_items ) ) {
            return array();
        }

        $data  = array();
        $nodes = array_slice(
            $response_items,
            0,
            $this->config['posts_counter'],
            true
        );

        foreach ( $nodes as $post ) {

            $_post               = array();
            $_post['link']       = $post['node']['shortcode'];
            $_post['image']      = $post['node']['thumbnail_src'];
            $_post['caption']    = isset( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ? wp_html_excerpt( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'], $this->config['post_caption_length'], '&hellip;' ) : '';
            $_post['comments']   = $post['node']['edge_media_to_comment']['count'];
            $_post['likes']      = $post['node']['edge_liked_by']['count'];
            $_post['dimensions'] = $post['node']['dimensions'];
            $_post['thumbnail_resources'] = $this->_generate_thumbnail_resources( $post );

            array_push( $data, $_post );
        }

        return $data;
    }

    /**
     * Get prepared response data from official api.
     *
     * @param $response
     *
     * @return array
     */
    public function get_response_data_from_official_api( $response ) {

        if ( ! isset( $response['data'] ) ) {
            return array();
        }

        $response_items = $response['data'];

        if ( empty( $response_items ) ) {
            return array();
        }

        if ( $this->config['use_graph_api'] ) {
            $response_items = $this->remove_video_items( $response_items );
        }

        $data  = array();
        $nodes = array_slice(
            $response_items,
            0,
            $this->config['posts_counter'],
            true
        );

        foreach ( $nodes as $post ) {

            $media_url = ! empty( $post['media_url'] ) ? $post['media_url'] : '';

            switch ( $post['media_type'] ) {
                case 'VIDEO':
                    $media_url = ! empty( $post['thumbnail_url'] ) ? $post['thumbnail_url'] : '';
                    break;

                case 'CAROUSEL_ALBUM':
                    $media_url = ! empty( $post['children']['data'][0]['media_url'] ) ? $post['children']['data'][0]['media_url'] : $media_url;
                    break;
            }

            $_post             = array();
            $_post['link']     = $post['permalink'];
            $_post['image']    = $media_url;
            $_post['caption']  = ! empty( $post['caption'] ) ? wp_html_excerpt( $post['caption'], $this->config['post_caption_length'], '&hellip;' ) : '';
            $_post['comments'] = ! empty( $post['comments_count'] ) ? $post['comments_count'] : 0;           // TODO: available only for Graph Api
            $_post['likes']    = ! empty( $post['like_count'] ) ? $post['like_count'] : 0;                   // TODO: available only for Graph Api
            $_post['thumbnail_resources'] = $this->_generate_thumbnail_resources_from_official_api( $post ); // TODO: this data now not available

            array_push( $data, $_post );
        }

        return $data;
    }

    /**
     * Remove video items.
     *
     * @param  array $data
     * @return array
     */
    public function remove_video_items( $data ) {

        $result = array();

        foreach ( $data as $item ) {

            if ( ! empty( $item['media_type'] ) && 'VIDEO' === $item['media_type'] ) {
                continue;
            }

            if ( ! empty( $item['children']['data'] ) ) {
                $item['children']['data'] = $this->remove_video_items( $item['children']['data'] );

                if ( empty( $item['children']['data'] ) ) {
                    continue;
                }
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * Generate thumbnail resources.
     *
     * @param $post_data
     *
     * @return array
     */
    public function _generate_thumbnail_resources( $post_data ) {
        $post_data = $post_data['node'];

        $thumbnail_resources = array(
            'thumbnail' => false,
            'low'       => false,
            'standard'  => false,
            'high'      => false,
        );

        if ( ! empty( $post_data['thumbnail_resources'] ) && is_array( $post_data['thumbnail_resources'] ) ) {
            foreach ( $post_data['thumbnail_resources'] as $key => $resources_data ) {

                if ( 150 === $resources_data['config_width'] ) {
                    $thumbnail_resources['thumbnail'] = $resources_data;

                    continue;
                }

                if ( 320 === $resources_data['config_width'] ) {
                    $thumbnail_resources['low'] = $resources_data;

                    continue;
                }

                if ( 640 === $resources_data['config_width'] ) {
                    $thumbnail_resources['standard'] = $resources_data;

                    continue;
                }
            }
        }

        if ( ! empty( $post_data['display_url'] ) ) {
            $thumbnail_resources['high'] = array(
                'src'           => $post_data['display_url'],
                'config_width'  => $post_data['dimensions']['width'],
                'config_height' => $post_data['dimensions']['height'],
            ) ;
        }

        return $thumbnail_resources;
    }

    /**
     * Generate thumbnail resources from official api.
     *
     * @param $post_data
     *
     * @return array
     */
    public function _generate_thumbnail_resources_from_official_api( $post_data ) {
        $thumbnail_resources = array(
            'thumbnail' => false,
            'low'       => false,
            'standard'  => false,
            'high'      => false,
        );

        if ( ! empty( $post_data['images'] ) && is_array( $post_data['images'] ) ) {

            $thumbnails_data = $post_data['images'];

            $thumbnail_resources['thumbnail'] = array(
                'src'           => $thumbnails_data['thumbnail']['url'],
                'config_width'  => $thumbnails_data['thumbnail']['width'],
                'config_height' => $thumbnails_data['thumbnail']['height'],
            );

            $thumbnail_resources['low'] = array(
                'src'           => $thumbnails_data['low_resolution']['url'],
                'config_width'  => $thumbnails_data['low_resolution']['width'],
                'config_height' => $thumbnails_data['low_resolution']['height'],
            );

            $thumbnail_resources['standard'] = array(
                'src'           => $thumbnails_data['standard_resolution']['url'],
                'config_width'  => $thumbnails_data['standard_resolution']['width'],
                'config_height' => $thumbnails_data['standard_resolution']['height'],
            );

            $thumbnail_resources['high'] = $thumbnail_resources['standard'];
        }

        return $thumbnail_resources;
    }

    /**
     * Retrieve a grab URL.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_grab_url( $config ) {

        if ( 'hashtag' == $config['endpoint'] ) {
            $url = sprintf( $this->get_tags_url(), $config['target'] );
            $url = add_query_arg( array( '__a' => 1 ), $url );
        }
        else {
            $url = $this->get_self_url();
            $url = add_query_arg(
                array(
                    'fields'       => 'id,media_type,media_url,thumbnail_url,permalink,caption',
                    'access_token' => $this->get_access_token(),
                ),
                $url
            );
        }

        return $url;
    }

    /**
     * Retrieve a URL for photos by hashtag.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_tags_url() {
        return apply_filters( 'kitify/instagram-gallery/get-tags-url', $this->api_url . 'explore/tags/%s/' );
    }

    /**
     * Retrieve a URL for self photos.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_self_url() {
        return apply_filters( 'kitify/instagram-gallery/get-self-url', $this->new_api_url . 'me/media/' );
    }

    /**
     * Retrieve a URL for post.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_post_url() {
        return apply_filters( 'kitify/instagram-gallery/get-post-url', $this->api_url . 'p/%s/' );
    }

    /**
     * sanitize endpoint.
     *
     * @since  1.0.0
     * @return string
     */
    public function sanitize_endpoint() {
        return in_array( $this->get_settings( 'endpoint' ) , array( 'hashtag', 'self' ) ) ? $this->get_settings( 'endpoint' ) : 'hashtag';
    }

    /**
     * Get transient key.
     *
     * @since  1.0.0
     * @return string
     */
    public function get_transient_key() {
        return sprintf( 'kitify_instagram_%s_%s%s_posts_count_%s_caption_%s',
            $this->config['endpoint'],
            $this->config['target'],
            $this->config['use_graph_api'] ? '_order_' . $this->config['order_by'] : '',
            $this->config['posts_counter'],
            $this->config['post_caption_length']
        );
    }

    /**
     * Get access token.
     *
     * @return string
     */
    public function get_access_token() {
        if ( ! $this->access_token ) {
            $source = $this->get_settings( 'access_token_source' );

            if ( 'custom' === $source ) {
                $this->access_token = $this->get_settings( 'custom_access_token' );
            } else {
                $this->access_token = kitify_settings()->get( 'insta_access_token' );
            }
        }

        return $this->access_token;
    }

    /**
     * Get business account config.
     *
     * @return array
     */
    public function get_business_account_config() {
        if ( ! $this->business_account_config ) {
            $this->business_account_config['token']   = kitify_settings()->get( 'insta_business_access_token' );
            $this->business_account_config['user_id'] = kitify_settings()->get( 'insta_business_user_id' );
        }

        return $this->business_account_config;
    }

    /**
     * Get access token from the plugin settings.
     *
     * @return string
     */
    public function get_access_token_from_settings() {
        return kitify_settings()->get( 'insta_access_token' );
    }

}
