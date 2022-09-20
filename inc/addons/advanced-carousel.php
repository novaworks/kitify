<?php

/**
 * Class: Kitify_Advanced_Carousel
 * Name: Advanced Carousel
 * Slug: kitify-carousel
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
 * Advanced_Carousel Widget
 */
class Kitify_Advanced_Carousel extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( 'kitify-banner', kitify()->plugin_url('assets/css/addons/banner.css'), ['kitify-base'], kitify()->get_version());
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/advanced-carousel.css'), ['kitify-banner'], kitify()->get_version());

        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-advanced-carousel';
    }

    protected function get_widget_title() {
        return esc_html__( 'Advanced Carousel', 'kitify');
    }

    public function get_icon() {
        return 'kitify-icon-carousel';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_slides',
            array(
                'label' => esc_html__( 'Slides', 'kitify' ),
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
            'item_content_type',
            array(
                'label'   => esc_html__( 'Content Type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'  => esc_html__( 'Default', 'kitify' ),
                    'template' => esc_html__( 'Template', 'kitify' ),
                ),
            )
        );

	    $repeater->add_control(
		    'item_icon',
		    [
			    'label'            => __( 'Icon', 'kitify' ),
			    'type'             => Controls_Manager::ICONS,
			    'fa4compatibility' => 'icon',
			    'skin'             => 'inline',
			    'label_block'      => false,
		    ]
	    );

        $repeater->add_control(
            'item_title',
            array(
                'label'       => esc_html__( 'Item Title', 'kitify' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array( 'active' => true ),
                'condition'   => array(
                    'item_content_type' => 'default',
                ),
            )
        );

        $repeater->add_control(
            'item_text',
            array(
                'label'       => esc_html__( 'Item Description', 'kitify' ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => array( 'active' => true ),
                'condition'   => array(
                    'item_content_type' => 'default',
                ),
            )
        );

        $repeater->add_control(
            'item_link',
            array(
                'label'       => esc_html__( 'Item Link', 'kitify' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'kitify' ),
                'condition'   => array(
                    'item_content_type' => 'default',
                ),
            )
        );

        $repeater->add_control(
            'item_button_text',
            array(
                'label'       => esc_html__( 'Item Button Text', 'kitify' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'dynamic'     => array(
                    'active' => true
                ),
                'condition'   => array(
                    'item_content_type' => 'default',
                ),
            )
        );

        $repeater->add_control(
            'template_id',
            array(
                'label'       => esc_html__( 'Choose Template', 'kitify' ),
                'label_block' => 'true',
                'type'        => 'kitify-query',
                'object_type' => \Elementor\TemplateLibrary\Source_Local::CPT,
                'filter_type' => 'by_id',
                'condition'   => array(
                    'item_content_type' => 'template',
                ),
            )
        );

        $this->_add_control(
            'items_list',
            array(
                'type'    => Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'default' => array(
                    array(
                        'item_image' => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title' => esc_html__( 'Item #1', 'kitify' ),
                        'item_text'  => esc_html__( 'Item #1 Description', 'kitify' ),
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->_add_control(
            'item_link_type',
            array(
                'label'   => esc_html__( 'Item link type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'link',
                'options' => array(
                    'link'     => esc_html__( 'Url', 'kitify' ),
                    'lightbox' => esc_html__( 'Lightbox', 'kitify' ),
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'kitify' ),
            )
        );
        $item_layout = apply_filters(
            'kitify/advanced_carousel/control/item_layout',
            array(
              'banners'   => esc_html__( 'Banners', 'kitify' ),
              'simple' => esc_html__( 'Simple', 'kitify' ),
            )
        );
        $this->_add_control(
            'item_layout',
            array(
                'label'   => esc_html__( 'Items Layout', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'simple',
                'options' => $item_layout,
            )
        );
        $simple_style = apply_filters(
            'kitify/banner/control/simple_style',
            array(
              'none'   => esc_html__( 'None', 'kitify' ),
            )
        );
        $this->_add_control(
            'simple_style',
            array(
                'label'   => esc_html__( 'Simple Style', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => $simple_style,
                'condition' => array(
                    'item_layout' => 'simple',
                ),
            )
        );

        $animation_effect = apply_filters(
            'kitify/banner/control/animation_effect',
            array(
              'none'   => esc_html__( 'None', 'kitify' ),
              'hidden-content'   => esc_html__( 'Hidden Content', 'kitify' ),
              'lily'   => esc_html__( 'Lily', 'kitify' ),
              'sadie'  => esc_html__( 'Sadie', 'kitify' ),
              'layla'  => esc_html__( 'Layla', 'kitify' ),
              'oscar'  => esc_html__( 'Oscar', 'kitify' ),
              'marley' => esc_html__( 'Marley', 'kitify' ),
              'ruby'   => esc_html__( 'Ruby', 'kitify' ),
              'roxy'   => esc_html__( 'Roxy', 'kitify' ),
              'bubba'  => esc_html__( 'Bubba', 'kitify' ),
              'romeo'  => esc_html__( 'Romeo', 'kitify' ),
              'sarah'  => esc_html__( 'Sarah', 'kitify' ),
              'chico'  => esc_html__( 'Chico', 'kitify' )
            )
        );
        $this->_add_control(
            'animation_effect',
            array(
                'label'   => esc_html__( 'Animation Effect', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lily',
                'options' => $animation_effect,
                'condition' => array(
                    'item_layout' => 'banners',
                ),
            )
        );

        $this->_add_control(
            'img_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'kitify' ),
                'default'    => 'full',
                'options'    => kitify_helper()->get_image_sizes(),
            )
        );

        $this->_add_control(
            'title_html_tag',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => kitify_helper()->get_available_title_html_tags(),
                'default' => 'h5',
            )
        );

        $this->_add_control(
            'link_title',
            array(
                'label'     => esc_html__( 'Link Title', 'kitify' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => '',
                'condition' => array(
                    'item_layout' => 'simple',
                ),
            )
        );

        $this->_add_control(
            'equal_height_cols',
            array(
                'label'        => esc_html__( 'Equal Columns Height', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->_add_control(
            'equal_custom_img_height',
            array(
                'label'        => esc_html__( 'Custom Image Height?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class'  => 'kitify-adv-custom-img-height-',
            )
        );

        $this->_add_responsive_control(
            'custom_img_height',
            array(
                'label'      => esc_html__( 'Image Height', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'vw', 'vh', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--kitify-banner-image-height: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'equal_custom_img_height' => 'true',
                ),
            )
        );
        $this->_add_control(
            'custom_img_position',
            array(
                'label'   => esc_html__( 'Cropped Image Position', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'center' => esc_html__( 'Center', 'kitify' ),
                    'top' => esc_html__( 'Top', 'kitify' ),
                    'bottom' => esc_html__( 'Bottom', 'kitify' ),
                ],
                'default' => 'center',
                'condition' => array(
                    'equal_custom_img_height' => 'true',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-carousel .kitify-banner__img,{{WRAPPER}} .kitify-carousel .kitify-carousel__item-img' => 'object-position: {{value}}',
                ),
            )
        );

        $this->_end_controls_section();

        $this->register_carousel_section([], false, false);

        $css_scheme = apply_filters(
            'kitify/advanced-carousel/css-scheme',
            array(
                'arrow_next'     => '.kitify-carousel .kitify-arrow.next-arrow',
                'arrow_prev'     => '.kitify-carousel .kitify-arrow.prev-arrow',
                'arrow_next_hov' => '.kitify-carousel .kitify-arrow.next-arrow:hover',
                'arrow_prev_hov' => '.kitify-carousel .kitify-arrow.prev-arrow:hover',
                'dot'            => '.kitify-carousel .kitify-carousel__dots .swiper-pagination-bullet',
                'dot_hover'      => '.kitify-carousel .kitify-carousel__dots .swiper-pagination-bullet:hover',
                'dot_active'     => '.kitify-carousel .kitify-carousel__dots .swiper-pagination-bullet-active',
                'wrap'           => '.kitify-carousel',
                'carousel_inner' => '.kitify-carousel-inner',
                'column'         => '.kitify-carousel .kitify-carousel__item',
                'image'          => '.kitify-carousel__item-img',
                'items'          => '.kitify-carousel__content',
                'items_title'    => '.kitify-carousel__content .kitify-carousel__item-title',
                'items_text'     => '.kitify-carousel__content .kitify-carousel__item-text',
                'items_icon'     => '.kitify-carousel__item-icon',
                'items_icon_inner' => '.kitify-icon-inner',
                'items_button'   => '.elementor-button',
                'button_icon'    => '.elementor-button .btn-icon',
                'banner'         => '.kitify-banner',
                'banner_content' => '.kitify-banner__content',
                'banner_overlay' => '.kitify-banner__overlay',
                'banner_title'   => '.kitify-banner__title',
                'banner_text'    => '.kitify-banner__text',
            )
        );

        $this->_start_controls_section(
            'section_column_style',
            array(
                'label'      => esc_html__( 'Column', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_responsive_control(
            'column_padding',
            array(
                'label'       => esc_html__( 'Column Padding', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'render_type' => 'template',
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' => '--kitify-carousel-item-top-space: {{TOP}}{{UNIT}}; --kitify-carousel-item-right-space: {{RIGHT}}{{UNIT}};--kitify-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-carousel-item-left-space: {{LEFT}}{{UNIT}};--kitify-gcol-top-space: {{TOP}}{{UNIT}}; --kitify-gcol-right-space: {{RIGHT}}{{UNIT}};--kitify-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-gcol-left-space: {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'column_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['column'] . ' .kitify-carousel__item-inner',
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_simple_item_style',
            array(
                'label'      => esc_html__( 'Simple Item', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'item_layout' => 'simple',
                ),
            )
        );

        $this->_add_control(
            'item_image_heading',
            array(
                'label' => esc_html__( 'Image', 'kitify' ),
                'type'  => Controls_Manager::HEADING,
            ),
            75
        );

        $this->add_responsive_control(
            'item_image_size',
            array(
                'label' => esc_html__( 'Image Size', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_image_maxheight',
            array(
                'label' => esc_html__( 'Image Max Height', 'kitify' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'max-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'item_image_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
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
                'selectors_dictionary' => [
                    'left'    => 'text-align:left; align-items: flex-start;',
                    'center' => 'text-align:center; align-items: center;',
                    'right' => 'text-align:right; align-items: flex-end;',
                ],
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-carousel__item-link' => '{{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'item_image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'item_image_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'item_image_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'item_image_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['image'],
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_image_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['image'],
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_image_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['image'],
            ),
            75
        );

        $this->_add_control(
            'item_content_heading',
            array(
                'label'     => esc_html__( 'Content', 'kitify' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

	    $this->_add_responsive_control(
		    'items_alignment',
		    array(
			    'label'   => esc_html__( 'Alignment', 'kitify' ),
			    'type'    => Controls_Manager::CHOOSE,
			    'default' => 'left',
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
				    '{{WRAPPER}} ' . $css_scheme['items'] => 'text-align: {{VALUE}};',
			    ),
		    ),
		    25
	    );

	    $this->_add_control(
		    'enable_zoom_on_hover',
		    array(
			    'label'     => esc_html__( 'Enable Zoom on hover', 'kitify' ),
			    'type'      => Controls_Manager::SWITCHER,
			    'return_value' => 'kitify--enable-zoom-hover',
			    'default'   => '',
			    'prefix_class' => ''
		    )
	    );
	    $this->_add_responsive_control(
		    'content_zoom_level',
		    [
			    'label' => esc_html__( 'Level', 'kitify' ),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => [
					    'min' => 0.5,
					    'max' => 2.0,
					    'step' => 0.1
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}}' => '--kitify-content-zoom-lv: {{SIZE}}',
			    ],
		    ]
	    );

        $this->_start_controls_tabs( 'tabs_item_style' );

        $this->_start_controls_tab(
            'tab_item_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'simple_item_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['items'],
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['items'],
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['items'],
            ),
            100
        );

	    $this->_add_responsive_control(
		    'items_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_add_responsive_control(
		    'items_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_add_responsive_control(
		    'items_border_radius',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    75
	    );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_item_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'simple_item_bg_hover',
                'selector' => '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items'],
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border_hover',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items'],
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_box_shadow_hover',
                'selector' => '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items'],
            ),
            100
        );

	    $this->_add_responsive_control(
		    'items_padding_hover',
		    array(
			    'label'      => esc_html__( 'Padding', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_add_responsive_control(
		    'items_margin_hover',
		    array(
			    'label'      => esc_html__( 'Margin', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_add_responsive_control(
		    'items_border_radius_hover',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    75
	    );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_banner_item_style',
            array(
                'label'      => esc_html__( 'Banner Item', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'item_layout' => 'banners',
                ),
            )
        );

        $this->_start_controls_tabs( 'tabs_background' );

        $this->_start_controls_tab(
            'tab_background_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'items_content_color',
            array(
                'label'     => esc_html__( 'Additional Elements Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-effect-layla ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-layla ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-oscar ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-marley ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-ruby ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-roxy ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-roxy ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-bubba ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-bubba ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-romeo ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-romeo ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-sarah ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-chico ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner_overlay'],
            ),
            25
        );

        $this->_add_control(
            'normal_opacity',
            array(
                'label'   => esc_html__( 'Opacity', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '0',
                'min'     => 0,
                'max'     => 1,
                'step'    => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_overlay'] => 'opacity: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_background_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'items_content_hover_color',
            array(
                'label'     => esc_html__( 'Additional Elements Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-effect-layla:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-layla:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-oscar:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-marley:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-ruby:hover ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-roxy:hover ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-roxy:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-bubba:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-bubba:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-romeo:hover ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-romeo:hover ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-sarah:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-effect-chico:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'background_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'],
            ),
            25
        );

        $this->_add_control(
            'hover_opacity',
            array(
                'label'   => esc_html__( 'Opacity', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '0.4',
                'min'     => 0,
                'max'     => 1,
                'step'    => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'] => 'opacity: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
          'items_content_radius',
          [
            'label' =>esc_html__( 'Border Radius', 'kitify' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px'],
            'selectors' => [
              '{{WRAPPER}} ' . $css_scheme['banner'] =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
          ]
        );
        $this->_end_controls_section();

	    $this->_start_controls_section(
		    'section_icon_style',
		    array(
			    'label'      => esc_html__( 'Item Icon', 'kitify' ),
			    'tab'        => Controls_Manager::TAB_STYLE,
		    )
	    );

	    $this->_add_responsive_control(
		    'item_icon_size',
		    [
			    'label' => esc_html__( 'Size', 'kitify' ),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => [
					    'min' => 6,
					    'max' => 300,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->_add_responsive_control(
		    'item_icon_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );
	    $this->_add_responsive_control(
		    'item_icon_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_start_controls_tabs( 'tabs_item_icon' );
	    $this->_start_controls_tab(
		    'tab_item_icon_normal',
		    [
			    'label' => esc_html__( 'Normal', 'kitify' ),
		    ]
	    );
	    $this->_add_control(
		    'item_icon_color',
		    [
			    'label' => esc_html__( 'Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->_add_control(
		    'item_icon_bgcolor',
		    [
			    'label' => esc_html__( 'Background Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'item_icon_border',
			    'label' => esc_html__( 'Border', 'kitify' ),
			    'selector' => '{{WRAPPER}} ' . $css_scheme['items_icon_inner'],
		    ]
	    );
	    $this->_add_responsive_control(
		    'item_icon_radius',
		    [
			    'label' =>esc_html__( 'Border Radius', 'kitify' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px'],
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'item_icon_shadow',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['items_icon_inner'],
		    ]
	    );

	    $this->_end_controls_tab();
	    $this->_start_controls_tab(
		    'tab_item_icon_hover',
		    [
			    'label' => esc_html__( 'Hover', 'kitify' ),
		    ]
	    );
	    $this->_add_control(
		    'item_icon_color_hover',
		    [
			    'label' => esc_html__( 'Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items_icon_inner'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $this->_add_control(
		    'item_icon_bgcolor_hover',
		    [
			    'label' => esc_html__( 'Background Color', 'kitify' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items_icon_inner'] => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'item_icon_border_hover',
			    'label' => esc_html__( 'Border', 'kitify' ),
			    'selector' => '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items_icon_inner'],
		    ]
	    );
	    $this->_add_responsive_control(
		    'item_icon_radius_hover',
		    [
			    'label' =>esc_html__( 'Border Radius', 'kitify' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px'],
			    'selectors' => [
				    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items_icon_inner'] =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'item_icon_shadow_hover',
			    'selector' => '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items_icon_inner'],
		    ]
	    );
	    $this->_end_controls_tab();
	    $this->_end_controls_tabs();

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_item_title_style',
            array(
                'label'      => esc_html__( 'Item Title', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_title_style' );

        $this->_start_controls_tab(
            'tab_title_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'items_title_color',
            array(
                'label'     => esc_html__( 'Title Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['items_title'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_title_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'items_title_color_hover',
            array(
                'label'     => esc_html__( 'Title Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items_title'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'items_title_link_color_hover',
            array(
                'label'     => esc_html__( 'Link Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-carousel__item ' . $css_scheme['items_title'] . ' a:hover' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'item_layout' => 'simple',
                    'link_title' => 'yes',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'items_title_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['items_title'] . ', {{WRAPPER}}  ' . $css_scheme['items_title'] . ' a, {{WRAPPER}} ' . $css_scheme['banner_title'],
                'separator' => 'before',
            ),
            50
        );

        $this->_add_responsive_control(
            'items_title_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );
        $this->_add_responsive_control(
            'items_title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_item_text_style',
            array(
                'label'      => esc_html__( 'Item Description', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_text_style' );

        $this->_start_controls_tab(
            'tab_text_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'items_text_color',
            array(
                'label'     => esc_html__( 'Content Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['items_text'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_text_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'items_text_color_hover',
            array(
                'label'     => esc_html__( 'Content Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['items_text'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-carousel__item:hover ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'items_text_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['items_text'] . ', {{WRAPPER}} ' . $css_scheme['banner_text'],
                'separator' => 'before',
            ),
            50
        );

        $this->_add_responsive_control(
            'items_text_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'items_text_margin',
            array(
                'label'      => esc_html__( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        /**
         * Action Button Style Section
         */
        $this->_start_controls_section(
            'section_action_button_style',
            array(
                'label'      => esc_html__( 'Action Button', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

	    $this->_add_control(
		    'show_btn_hover',
		    array(
			    'label'     => esc_html__( 'Show Button On Hover', 'kitify' ),
			    'type'      => Controls_Manager::SWITCHER,
			    'default'   => '',
			    'prefix_class' => 'kitify--show-btn-hover-'
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
				    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'flex-direction: {{VALUE}}',
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

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}}  ' . $css_scheme['items_button'],
            ),
            50
        );

        $this->_add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'button_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_start_controls_tabs( 'tabs_button_style' );

        $this->_start_controls_tab(
            'tab_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'kitify' ),
            )
        );

        $this->_add_control(
            'button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'button_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => array(
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['items_button'],
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['items_button'],
            ),
            100
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'kitify' ),
            )
        );

        $this->_add_control(
            'button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-carousel__content ' . $css_scheme['items_button'] . ':hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-banner:hover' . $css_scheme['items_button'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'primary_button_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-carousel__content ' . $css_scheme['items_button'] . ':hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .kitify-banner:hover' . $css_scheme['items_button'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_hover_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'selector'    => '{{WRAPPER}} .kitify-carousel__content ' . $css_scheme['items_button'] . ':hover, {{WRAPPER}} .kitify-banner:hover' . $css_scheme['items_button']
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_hover_box_shadow',
                'selector'    => '{{WRAPPER}} .kitify-carousel__content ' . $css_scheme['items_button'] . ':hover, {{WRAPPER}} .kitify-banner:hover' . $css_scheme['items_button']
            ),
            100
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        $this->register_carousel_arrows_dots_style_section();

    }

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

    public function get_advanced_carousel_options( $carousel_columns = false, $widget_id = '', $settings = null ) {
        $opts = parent::get_advanced_carousel_options($carousel_columns, $widget_id, $settings);
        $opts = array_merge([
            'content_selector' => 'banners' == $this->get_settings_for_display('item_layout') ? '.kitify-banner__content' : '.kitify-carousel__content',
            'content_effect_in' => 'fadeInUp',
            'content_effect_out' => 'fadeOutDown',
        ], $opts);
        return $opts;
    }

    public function get_advanced_carousel_img( $class = '' ) {

        $settings = $this->get_settings_for_display();
        $size     = isset( $settings['img_size'] ) ? $settings['img_size'] : 'full';

        $item_settings = $this->_processed_item;
        $item_settings['item_image_size'] = $size;

        if(empty( $item_settings['item_image']['url'] )){
            return;
        }

        $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

        $img_html = str_replace('class="', 'class="' . $class . ' ', $img_html);

        return $img_html;

    }

	protected function _loop_icon( $format ){
		$item = $this->_processed_item;
		return $this->_get_icon_setting( $item['item_icon'], $format );
	}

	protected function _btn_icon( $format ){
    	$settings = $this->get_settings_for_display();
		return $this->_get_icon_setting( $settings['selected_btn_icon'], $format );
	}

    /**
     * Get item template content.
     *
     * @return string|void
     */
    protected function _loop_item_template_content() {
        $template_id = $this->_processed_item['template_id'];

        if ( empty( $template_id ) ) {
            return;
        }

        // for multi-language plugins
        $template_id = apply_filters( 'kitify/widgets/template_id', $template_id, $this );
        $content     = kitify()->elementor()->frontend->get_builder_content_for_display( $template_id );

        if ( kitify()->elementor()->editor->is_edit_mode() ) {
            $edit_url = add_query_arg(
                array(
                    'elementor' => '',
                ),
                get_permalink( $template_id )
            );
            $edit_link = sprintf(
                '<a class="kitify-edit-template-link" data-template-edit-link="%1$s" href="%1$s" title="%2$s" target="_blank"><span class="dashicons dashicons-edit"></span></a>',
                esc_url( $edit_url ),
                esc_html__( 'Edit Template', 'kitify' )
            );
            $content .= $edit_link;
        }
        return $content;
    }
}
