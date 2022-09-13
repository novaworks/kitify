<?php

/**
 * Class: Kitify_Portfolio
 * Name: Portfolio
 * Slug: kitify-portfolio
 */


namespace Elementor;

use KitifyExtensions\Elementor\Controls\Group_Control_Related as Group_Control_Related;

if (!defined('WPINC')) {
	die;
}

if(!class_exists('Kitify_Posts')){
	require_once kitify()->plugin_path('inc/addons/posts.php');
}

/**
 * Posts Widget
 */
class Kitify_Portfolio extends Kitify_Posts {

	protected function enqueue_addon_resources(){
		wp_register_style( 'kitify-posts', kitify()->plugin_url('assets/css/addons/posts.css'), ['kitify-base'], kitify()->get_version());
		wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/portfolio.css'), ['kitify-posts'], kitify()->get_version());
		$this->add_style_depends( $this->get_name() );
		$this->add_script_depends( 'jquery-isotope' );
		$this->add_script_depends( 'kitify-base' );
	}

	private $_query = null;

	public $item_counter = 0;

	public function get_name() {
		return 'kitify-portfolio';
	}

	protected function get_widget_title() {
		return esc_html__( 'Portfolio', 'kitify' );
	}
	public function get_icon() {
			return 'kitify-icon-image-layout';
	}
	public function get_keywords() {
		return [ 'portfolio' ];
	}

	protected function _register_section_meta( $css_scheme ){
		$this->_start_controls_section(
			'section_meta',
			[
				'label' => __( 'Meta Data', 'kitify' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->_add_control(
			'floating_date',
			[
				'label'     => esc_html__( 'Show Floating Date', 'kitify' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'kitify' ),
				'label_off' => esc_html__( 'No', 'kitify' ),
				'default'   => 'no'
			]
		);

		$this->_add_control(
			'floating_date_style',
			[
				'label'     => esc_html__( 'Floating Date Style', 'kitify' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'circle' => esc_html__( 'Circle', 'kitify' )
				],
				'condition' => [
					'floating_date' => 'yes',
				]
			]
		);

		$this->_add_control(
			'floating_category',
			[
				'label'     => esc_html__( 'Show Floating Category', 'kitify' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'kitify' ),
				'label_off' => esc_html__( 'No', 'kitify' ),
				'default'   => 'no'
			]
		);

		$this->_add_control(
			'show_meta',
			array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Meta Data', 'kitify' ),
				'label_on'     => esc_html__( 'Yes', 'kitify' ),
				'label_off'    => esc_html__( 'No', 'kitify' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_label',
			array(
				'label' => esc_html__( 'Label', 'kitify' ),
				'type'  => Controls_Manager::TEXT,
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
			'item_type',
			[
				'label'   => esc_html__( 'Type', 'kitify' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => apply_filters( 'kitify/'.$this->get_kitify_name().'/metadata', [
					'category' => esc_html__( 'Category', 'kitify' ),
					'author'   => esc_html__( 'Author', 'kitify' ),
					'date'     => esc_html__( 'Posted Date', 'kitify' ),
					'tag'      => esc_html__( 'Tags', 'kitify' ),
				] )
			]
		);

		$this->_add_control(
			'metadata1',
			array(
				'label'         => esc_html__( 'MetaData 1', 'kitify' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ item_label }}}',
				'prevent_empty' => false,
				'condition'     => array(
					'show_meta' => 'yes'
				)
			)
		);

		$this->_add_control(
			'meta_position1',
			[
				'label'     => esc_html__( 'MetaData 1 Position', 'kitify' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'before_title'  => esc_html__( 'Before Title', 'kitify' ),
					'after_title'   => esc_html__( 'After Title', 'kitify' ),
					'after_content' => esc_html__( 'After Content', 'kitify' ),
				],
				'default'   => 'before_title',
				'condition' => [
					'show_meta' => 'yes',
				]
			]
		);

		$this->_add_control(
			'metadata2',
			array(
				'label'         => esc_html__( 'MetaData 2', 'kitify' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ item_label }}}',
				'prevent_empty' => false,
				'condition'     => array(
					'show_meta' => 'yes'
				)
			)
		);
		$this->_add_control(
			'meta_position2',
			[
				'label'     => esc_html__( 'MetaData 2 Position', 'kitify' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'before_title'  => esc_html__( 'Before Title', 'kitify' ),
					'after_title'   => esc_html__( 'After Title', 'kitify' ),
					'after_content' => esc_html__( 'After Content', 'kitify' ),
				],
				'default'   => 'after_title',
				'condition' => [
					'show_meta' => 'yes',
				]
			]
		);

		$this->_end_controls_section();
	}

	protected function _register_section_query( $css_scheme ) {
		/** Query section */
		$this->_start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'kitify' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->_add_group_control(
			Group_Control_Related::get_type(),
			[
				'name'        => 'query',
				'object_type' => 'nova_portfolio',
				'post_type' => 'nova_portfolio',
				'presets'     => [ 'full' ],
				'fields_options' => [
					'post_type' => [
						'default' => 'nova_portfolio',
						'options' => [
							'current_query' => __( 'Current Query', 'kitify' ),
							'nova_portfolio' => __( 'Latest Portfolio', 'kitify' ),
							'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'kitify' ),
							'related' => _x( 'Related', 'Posts Query Control', 'kitify' ),
						],
					],
					'orderby' => [
						'default' => 'date',
						'options' => [
							'date'          => __( 'Date', 'kitify' ),
							'title'         => __( 'Title', 'kitify' ),
							'rand'          => __( 'Random', 'kitify' ),
							'menu_order'    => __( 'Menu Order', 'kitify' ),
							'post__in'      => __( 'Manual Selection', 'kitify' ),
						],
					],
					'exclude' => [
						'options' => [
							'current_post' => __( 'Current Post', 'kitify' ),
							'manual_selection' => __( 'Manual Selection', 'kitify' ),
							'terms' => __( 'Portfolio Category', 'kitify' ),
						],
					],
					'exclude_ids' => [
						'object_type' => 'nova_portfolio',
					],
					'include_ids' => [
						'object_type' => 'nova_portfolio',
					],
				],
				'exclude' => [
					'exclude_authors',
					'authors',
					'offset',
					'query_id',
					'ignore_sticky_posts',
				],
			]
		);

		$this->_add_control(
			'paginate',
			[
				'label'   => __( 'Pagination', 'kitify' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->_add_control(
			'paginate_as_loadmore',
			[
				'label'     => __( 'Use Load More', 'kitify' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->_add_control(
			'loadmore_text',
			[
				'label'     => __( 'Load More Text', 'kitify' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Load More',
				'condition' => [
					'paginate'             => 'yes',
					'paginate_as_loadmore' => 'yes',
				]
			]
		);

		$this->_end_controls_section();
	}

	protected function _register_section_style_content_inner( $css_scheme ){

		$this->_start_controls_section(
			'section_inner_content_style',
			array(
				'label'      => esc_html__( 'Item Content Inner', 'kitify' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'preset' => ['grid-2'],
				]
			)
		);

		$this->_add_responsive_control(
			'content_width',
			array(
				'label'      => esc_html__( 'Width', 'nova' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner-content'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->_add_responsive_control(
			'inner_content_alignment',
			array(
				'label'     => esc_html__( 'Horizontal Alignment', 'kitify' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
					'{{WRAPPER}} ' . $css_scheme['content'] => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->_add_responsive_control(
			'inner_content_v_alignment',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'kitify' ),
				'type'      => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} ' . $css_scheme['content'] => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->_add_control(
			'enable_right_btn',
			[
				'label'     => esc_html__( 'Enable Right Button', 'kitify' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'kitify' ),
				'label_off' => esc_html__( 'No', 'kitify' ),
				'default'   => '',
				'prefix_class' => 'kitify--portfolio-btn-right-',
				'condition' => [
					'show_more' => 'yes',
				]
			]
		);

		$this->_add_responsive_control(
			'inner_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner-content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_responsive_control(
			'inner_content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner-content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
			)
		);
		$this->_add_responsive_control(
			'inner_content_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner-content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->_start_controls_tabs( 'inner_content_style_tabs' );
		$this->_start_controls_tab( 'inner_content_normal',
			[
				'label' => __( 'Normal', 'kitify' ),
			]
		);
		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'inner_content_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner-content'],
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inner_content_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner-content'],
			)
		);

		$this->_end_controls_tab();
		$this->_start_controls_tab( 'inner_content_hover',
			[
				'label' => __( 'Hover', 'kitify' ),
			]
		);
		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'inner_content_bg_hover',
				'selector' => '{{WRAPPER}} .kitify-posts__outer-box:hover .kitify-posts__inner-content-inner',
			),
			25
		);
		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inner_content_shadow_hover',
				'selector' => '{{WRAPPER}} .kitify-posts__inner-box:hover .kitify-posts__inner-content-inner',
			)
		);

		$this->_end_controls_tab();
		$this->_end_controls_tabs();

		$this->_end_controls_section();
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'kitify/'.$this->get_kitify_name().'/css-scheme',
			array(
				'wrap_outer'    => '.kitify-posts',
				'wrap'          => '.kitify-posts .kitify-posts__list_wrapper',
				'column'        => '.kitify-posts .kitify-posts__outer-box',
				'inner-box'     => '.kitify-posts .kitify-posts__inner-box',
				'content'       => '.kitify-posts .kitify-posts__inner-content',
				'inner-content' => '.kitify-posts .kitify-posts__inner-content-inner',
				'link'          => '.kitify-posts .kitify-posts__thumbnail-link',
				'thumb'         => '.kitify-posts .kitify-posts__thumbnail',
				'title'         => '.kitify-posts .kitify-posts__title',
				'excerpt'       => '.kitify-posts .kitify-posts__excerpt',
				'button'        => '.kitify-posts .kitify-posts__btn-more',
				'button_icon'   => '.kitify-posts .kitify-btn-more-icon',
				'meta1'         => '.kitify-posts .kitify-posts__meta1',
				'meta1-item'    => '.kitify-posts .kitify-posts__meta1 .kitify-posts__meta__item',
				'meta2'         => '.kitify-posts .kitify-posts__meta2',
				'meta2-item'    => '.kitify-posts .kitify-posts__meta2 .kitify-posts__meta__item',
			)
		);

		$this->_register_section_layout( $css_scheme );

		$this->_register_section_meta( $css_scheme );

		$this->_register_section_query( $css_scheme );

		$this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ] );

		$this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns' );

		$this->_register_section_style_general( $css_scheme );

		$this->_register_section_style_meta( $css_scheme );

		$this->_register_section_style_floating_date( $css_scheme );

		$this->_register_section_style_floating_category( $css_scheme );

		$this->_register_section_style_pagination( $css_scheme );

		$this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes' ] );

	}

}
