<?php
/**
 * Class: Kitify_Banner
 * Name: Banner
 * Slug: kitify-banner
 */

namespace Elementor;

use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Banner extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/banner.css'), ['kitify-base'], kitify()->get_version());
        $this->add_style_depends( $this->get_name() );
    }

	public function get_name() {
		return 'kitify-banner';
	}

	public function get_widget_title() {
		return esc_html__( 'Banner', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-banner';
	}


	protected function register_controls() {

		$this->_start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'kitify' ),
			)
		);

		$this->_add_control(
			'banner_image',
			array(
				'label'   => esc_html__( 'Image', 'kitify' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->_add_control(
			'banner_image_size',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Image Size', 'kitify' ),
				'default'    => 'full',
				'options'    => kitify_helper()->get_image_sizes(),
			)
		);

		$this->_add_control(
			'banner_title',
			array(
				'label'   => esc_html__( 'Title', 'kitify' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->_add_control(
			'banner_title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'kitify' ),
				'type'    => Controls_Manager::SELECT,
				'options' => kitify_helper()->get_available_title_html_tags(),
				'default' => 'h5',
			)
		);

		$this->_add_control(
			'banner_text',
			array(
				'label'   => esc_html__( 'Description', 'kitify' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

    $this->add_control(
    'btn_text',
        array(
            'label'   => esc_html__( 'Item Button Text', 'kitify' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        )
    );


		$this->_add_control(
			'banner_link',
			array(
				'label'   => esc_html__( 'Link', 'kitify' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
			)
		);

		$this->_add_control(
			'banner_link_target',
			array(
				'label'        => esc_html__( 'Open link in new window', 'kitify' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '_blank',
				'condition'    => array(
					'banner_link!' => '',
				),
			)
		);

		$this->_add_control(
			'banner_link_rel',
			array(
				'label'        => esc_html__( 'Add nofollow', 'kitify' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'nofollow',
				'condition'    => array(
					'banner_link!' => '',
				),
			)
		);
    $this->_add_control(
        'banner_link_icons__switch',
        [
            'label' => esc_html__('Add icon? ', 'kitify'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' =>esc_html__( 'Yes', 'kitify' ),
            'label_off' =>esc_html__( 'No', 'kitify' ),
            'condition' => [
                'banner_link!' => '',
            ]
        ]
    );
    $this->_add_advanced_icon_control(
        'banner_link_icons',
        [
            'label' =>esc_html__( 'Icon', 'kitify' ),
            'type' => Controls_Manager::ICONS,
            'label_block' => true,
            'condition' => [
                'banner_link!' => '',
                'banner_link_icons__switch' => 'yes'
            ]
        ]
    );
    $this->_add_control(
        'banner_link_icon_align',
        [
            'label' =>esc_html__( 'Icon Position', 'kitify' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left' =>esc_html__( 'Before', 'kitify' ),
                'right' =>esc_html__( 'After', 'kitify' ),
            ],
            'condition' => [
                'banner_link_icons__switch' => 'yes',
                'banner_link!' => '',
            ],
        ]
    );
		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'kitify' ),
			)
		);

    $this->add_control(
    'custom_height',
        array(
            'label'        => esc_html__( 'Enable Custom Image Height', 'kitify' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'kitify' ),
            'label_off'    => esc_html__( 'No', 'kitify' ),
            'return_value' => 'true',
            'default'      => ''
        )
    );

    $this->add_responsive_control(
        'height',
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
                '{{WRAPPER}} .kitify-banner' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .kitify-banner img' => 'position: absolute; width: 100%; height: 100%; left: 0; top: 0; object-fit: cover'
            ),
            'render_type' => 'template',
            'condition' => [
                'custom_height!' => ''
            ]
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
			)
		);

		$this->_end_controls_section();

		$css_scheme = apply_filters(
			'kitify/banner/css-scheme',
			array(
				'banner'         => '.kitify-banner',
				'banner_content' => '.kitify-banner__content',
				'banner_overlay' => '.kitify-banner__overlay',
				'banner_title'   => '.kitify-banner__title',
				'banner_text'    => '.kitify-banner__text',
				'banner_button'    => '.kitify-banner__button',
				'banner_button_icon'    => '.kitify-banner__button_icon',
			)
		);

		$this->_start_controls_section(
			'section_banner_item_style',
			array(
				'label'      => esc_html__( 'General', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'banner_container_heading',
			array(
				'label'     => esc_html__( 'Container', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
			),
			100
		);

		$this->_add_responsive_control(
			'banner_padding',
			array(
				'label'      => __( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'banner_margin',
			array(
				'label'      => __( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'banner_border',
				'label'       => esc_html__( 'Border', 'kitify' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['banner'],
			),
			100
		);

		$this->_add_responsive_control(
			'banner_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'banner_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner'],
			),
			100
		);
    $this->_add_control(
			'banner_content_heading',
			array(
				'label'     => esc_html__( 'Content', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
			),
			100
		);
    $this->_add_responsive_control(
      'banner_content_padding',
      array(
        'label'      => __( 'Padding', 'kitify' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', '%' ),
        'selectors'  => array(
          '{{WRAPPER}} ' . $css_scheme['banner_content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      ),
      100
    );
    $this->add_responsive_control(
    'ha',
        array(
            'label'   => esc_html__( 'Horizontal Alignment', 'kitify' ),
            'type'    => Controls_Manager::CHOOSE,
            'default' => 'center',
            'options' => array(
                'flex-start'    => array(
                    'title' => esc_html__( 'Left', 'kitify' ),
                    'icon'  => 'eicon-arrow-left',
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'kitify' ),
                    'icon'  => 'eicon-text-align-center',
                ),
                'flex-end' => array(
                    'title' => esc_html__( 'Right', 'kitify' ),
                    'icon'  => 'eicon-arrow-right',
                )
            ),
            'selectors'  => array(
                '{{WRAPPER}} '. $css_scheme['banner_content'] => 'justify-content: {{VALUE}};',
            ),
        )
    );

    $this->add_responsive_control(
        'va',
        array(
            'label'   => esc_html__( 'Vertical Alignment', 'kitify' ),
            'type'    => Controls_Manager::CHOOSE,
            'default' => 'center',
            'options' => array(
                'flex-start'    => array(
                    'title' => esc_html__( 'Start', 'kitify' ),
                    'icon'  => 'eicon-v-align-top',
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'kitify' ),
                    'icon'  => 'eicon-v-align-middle',
                ),
                'flex-end' => array(
                    'title' => esc_html__( 'End', 'kitify' ),
                    'icon'  => 'eicon-v-align-bottom',
                )
            ),
            'selectors'  => array(
                '{{WRAPPER}} '. $css_scheme['banner_content'] => 'align-items: {{VALUE}};',
            ),
        )
    );

		$this->_add_control(
			'banner_overlay_heading',
			array(
				'label'     => esc_html__( 'Overlay', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
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

		$this->_add_control(
			'banner_order_heading',
			array(
				'label'     => esc_html__( 'Order', 'kitify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			100
		);

		$this->_add_control(
			'banner_title_order',
			array(
				'label'   => esc_html__( 'Title Order', 'kitify' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['banner_title'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'banner_text_order',
			array(
				'label'   => esc_html__( 'Description Order', 'kitify' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['banner_text'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'banner_button_order',
			array(
				'label'   => esc_html__( 'Button Order', 'kitify' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['banner_button'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_banner_title_style',
			array(
				'label'      => esc_html__( 'Title', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);


		$this->_add_responsive_control(
			'title_alignment',
      array(
        'label'     => esc_html__( 'Alignment', 'kitify' ),
        'type'      => Controls_Manager::CHOOSE,
        'default' => 'center',
        'options'   => array(
          'flex-start' => array(
            'title' => esc_html__( 'Top', 'kitify' ),
            'icon'  => 'eicon-v-align-top',
          ),
          'center'     => array(
            'title' => esc_html__( 'Middle', 'kitify' ),
            'icon'  => 'eicon-v-align-middle',
          ),
          'flex-end'   => array(
            'title' => esc_html__( 'Bottom', 'kitify' ),
            'icon'  => 'eicon-v-align-bottom',
          ),
        ),
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'justify-content: {{VALUE}};',
        ),
      ),
			25
		);

		$this->_add_control(
			'banner_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['banner_title'].':before' => 'background-color: {{VALUE}}',
				),
			),
			25
		);
    $this->_add_control(
			'banner_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'banner_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner_title'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'banner_title_text_shadow',
				'label'     => esc_html__( 'Text Shadow', 'kitify' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner_title'],
			),
			50
		);
    $this->_add_responsive_control(
      'title_text_alignment',
      array(
        'label'   => esc_html__( 'Text Align', 'kitify' ),
        'type'    => Controls_Manager::CHOOSE,
        'default' => 'center',
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
          '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'text-align: {{VALUE}};',
        ),
      ),
      25
    );
    $this->_add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);
    $this->_add_group_control(
        Group_Control_Border::get_type(),
        [
            'name' => 'title_border',
            'selector' => '{{WRAPPER}} '.$css_scheme['banner_title'],
        ]
    );
		$this->_add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_banner_text_style',
			array(
				'label'      => esc_html__( 'Description', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'text_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'kitify' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['banner_text'] => 'text-align: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'banner_text_color',
			array(
				'label'     => esc_html__( 'Description Color', 'kitify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'banner_text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner_text'],
			),
			50
		);

		$this->_add_responsive_control(
			'text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

    /**
     * Submit Button Style Section
     */
    $this->start_controls_section(
        's_button_style',
        array(
            'label'      => esc_html__( 'Banner Button', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );


    $this->add_responsive_control(
        'btn_w',
        array(
            'label'      => esc_html__( 'Width', 'kitify' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array(
                'px', 'em', '%',
            ),
            'range'      => array(
                'px' => array(
                    'min' => 0,
                    'max' => 1000,
                ),
                '%' => array(
                    'min' => 0,
                    'max' => 100,
                ),
            ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'width: {{SIZE}}{{UNIT}};',
            ),
        )
    );

    $this->add_responsive_control(
        'btn_a',
        array(
            'label'   => esc_html__( 'Alignment', 'kitify' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => array(
                'flex-start' => array(
                    'title' => esc_html__( 'Left', 'kitify' ),
                    'icon'  => 'eicon-arrow-left',
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'kitify' ),
                    'icon'  => 'eicon-text-align-center',
                ),
                'flex-end' => array(
                    'title' => esc_html__( 'Right', 'kitify' ),
                    'icon'  => 'eicon-arrow-right',
                ),
            ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['banner_button']  => 'align-self: {{VALUE}};',
            ),
        )
    );
    $this->_add_responsive_control(
        'tab_btn_icon_font_size',
        [
            'label' => esc_html__( 'Icon Font Size', 'kitify' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} ' . $css_scheme['banner_button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $this->start_controls_tabs( 'tabs_btn_style' );

    $this->start_controls_tab(
        'tab_btn_n',
        array(
            'label' => esc_html__( 'Normal', 'kitify' ),
        )
    );

    $this->add_group_control(
        Group_Control_Background::get_type(),
        array(
            'name'     => 'btn_bg',
            'selector' => '{{WRAPPER}} ' . $css_scheme['banner_button'],
            'fields_options' => array(
                'background' => array(
                    'default' => 'classic',
                ),
                'color' => array(
                    'label'  => _x( 'Background Color', 'Background Control', 'kitify' ),
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
        'btn_c',
        array(
            'label'     => esc_html__( 'Text Color', 'kitify' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'color: {{VALUE}}',
            ),
        )
    );

    $this->add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'btn_t',

            'selector' => '{{WRAPPER}}  ' . $css_scheme['banner_button'],
        )
    );

    $this->add_responsive_control(
        'btn_pd',
        array(
            'label'      => esc_html__( 'Padding', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%', 'em' ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $this->add_responsive_control(
        'btn_mg',
        array(
            'label'      => __( 'Margin', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $this->add_responsive_control(
        'btn_bdr',
        array(
            'label'      => esc_html__( 'Border Radius', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $this->add_group_control(
        Group_Control_Border::get_type(),
        array(
            'name'        => 'btn_bd',
            'label'       => esc_html__( 'Border', 'kitify' ),
            'placeholder' => '1px',
            'default'     => '1px',
            'selector'    => '{{WRAPPER}} ' . $css_scheme['banner_button'],
        )
    );

    $this->add_group_control(
        Group_Control_Box_Shadow::get_type(),
        array(
            'name'     => 'btn_bsd',
            'selector' => '{{WRAPPER}} ' . $css_scheme['banner_button'],
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
            'name'     => 'btn_bg_h',
            'selector' => '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_button'],
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
        'btn_c_h',
        array(
            'label'     => esc_html__( 'Text Color', 'kitify' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_button'] => 'color: {{VALUE}}',
            ),
        )
    );

    $this->add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'btn_t_h',
            'selector' => '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_button'],
        )
    );

    $this->add_responsive_control(
        'btn_pd_h',
        array(
            'label'      => esc_html__( 'Padding', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%', 'em' ),
            'selectors'  => array(
                '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $this->add_responsive_control(
        'btn_mg_h',
        array(
            'label'      => __( 'Margin', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
                '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $this->add_responsive_control(
        'btn_bdr_h',
        array(
            'label'      => esc_html__( 'Border Radius', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
                '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        )
    );

    $this->add_group_control(
        Group_Control_Border::get_type(),
        array(
            'name'        => 'btn_bd_h',
            'label'       => esc_html__( 'Border', 'kitify' ),
            'placeholder' => '1px',
            'default'     => '1px',
            'selector'    => '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_button']
        )
    );

    $this->add_group_control(
        Group_Control_Box_Shadow::get_type(),
        array(
            'name'     => 'btn_bsd_h',
            'selector' => '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_button']
        )
    );

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();
}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	public function _get_banner_image() {

		$image = $this->get_settings_for_display( 'banner_image' );

		if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
			return;
		}

		$format = apply_filters( 'kitify/banner/image-format', '<img src="%1$s" alt="%2$s" class="kitify-banner__img">' );

		if ( empty( $image['id'] ) ) {
			return sprintf( $format, $image['url'], '' );
		}

		$size = $this->get_settings_for_display( 'banner_image_size' );

		if ( ! $size ) {
			$size = 'full';
		}

		$image_url = wp_get_attachment_image_url( $image['id'], $size );
		$alt       = esc_attr( Control_Media::get_image_alt( $image ) );

		return sprintf( $format, $image_url, $alt );
	}
  public function get_button_icon( $main_format = '%s' ){
      return $this->_get_icon( 'banner_link_icons', $main_format, 'kitify-banner__button_icon' );
  }
}
