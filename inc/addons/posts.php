<?php

/**
 * Class: Kitify_Posts
 * Name: Posts
 * Slug: kitify-posts
 */


namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use KitifyExtensions\Elementor\Classes\Query_Control as Module_Query;
use KitifyExtensions\Elementor\Controls\Group_Control_Related as Group_Control_Related;


/**
 * Posts Widget
 */
if(!class_exists('Kitify_Posts')) {
	class Kitify_Posts extends Kitify_Base {

		protected function enqueue_addon_resources() {
			wp_register_style( $this->get_name(), kitify()->plugin_url( 'assets/css/addons/posts.css' ), [ 'kitify-base' ], kitify()->get_version() );
			$this->add_style_depends( $this->get_name() );
			$this->add_script_depends( 'jquery-isotope' );
			$this->add_script_depends( 'kitify-base' );
		}

		private $_query = null;

		public $item_counter = 0;

		public function get_name() {
			return 'kitify-posts';
		}

		protected function get_widget_title() {
			return esc_html__( 'Posts', 'kitify' );
		}

		public function get_icon() {
			return 'kitify-icon-posts';
		}

		public function get_keywords() {
			return [ 'posts', 'blog', 'news' ];
		}

		protected function _register_section_layout( $css_scheme ){

			$preset_type = apply_filters(
				'kitify/'.$this->get_kitify_name().'/control/preset',
				array(
					'grid-1' => esc_html__( 'Grid 1', 'kitify' ),
					'grid-2' => esc_html__( 'Grid 2', 'kitify' ),
					'list-1' => esc_html__( 'List 1', 'kitify' ),
					'list-2' => esc_html__( 'List 2', 'kitify' ),
				)
			);

			$default_preset_type = array_keys( $preset_type );


			/** Layout section */
			$this->_start_controls_section(
				'section_settings',
				array(
					'label' => esc_html__( 'Layout', 'kitify' ),
				)
			);

			$this->_add_control(
				'layout_type',
				array(
					'label'   => esc_html__( 'Layout type', 'kitify' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'grid',
					'options' => array(
						'grid' => esc_html__( 'Default', 'kitify' )
					),
				)
			);

			$this->_add_control(
				'preset',
				array(
					'label'   => esc_html__( 'Preset', 'kitify' ),
					'type'    => Controls_Manager::SELECT,
					'default' => $default_preset_type[0],
					'options' => $preset_type
				)
			);

			$this->_add_responsive_control(
				'columns',
				array(
					'label'                  => esc_html__( 'Columns', 'kitify' ),
					'type'                   => Controls_Manager::SELECT,
					'default'                => 3,
					'tablet_default'         => 2,
					'mobile_extra_default'   => 2,
					'tabletportrait_default' => 2,
					'mobile_default'         => 1,
					'options'                => kitify_helper()->get_select_range( 6 )
				)
			);

			$this->_add_control(
				'keep_layout_mb',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Keep Layout on mobile', 'kitify' ),
					'description'  => esc_html__( 'This option only works with the List preset', 'kitify' ),
					'label_on'     => esc_html__( 'Yes', 'kitify' ),
					'label_off'    => esc_html__( 'No', 'kitify' ),
					'return_value' => 'yes',
					'default'      => '',
					'prefix_class' => 'kitify-keep-mbl-',
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


			$this->_add_control(
				'title_html_tag',
				array(
					'label'     => esc_html__( 'Title HTML Tag', 'kitify' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
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
					'default'   => 'h4',
					'separator' => 'before',
				)
			);

			$this->_add_control(
				'show_title',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Show Posts Title', 'kitify' ),
					'label_on'     => esc_html__( 'Yes', 'kitify' ),
					'label_off'    => esc_html__( 'No', 'kitify' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'separator'    => 'before',
				)
			);

			$this->_add_control(
				'title_trimmed',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Title Word Trim', 'kitify' ),
					'label_on'     => esc_html__( 'Yes', 'kitify' ),
					'label_off'    => esc_html__( 'No', 'kitify' ),
					'return_value' => 'yes',
					'default'      => 'no',
					'condition'    => array(
						'show_title' => 'yes',
					),
				)
			);

			$this->_add_control(
				'title_length',
				array(
					'type'      => 'number',
					'label'     => esc_html__( 'Title Length', 'kitify' ),
					'default'   => 5,
					'min'       => 1,
					'max'       => 50,
					'step'      => 1,
					'condition' => array(
						'title_trimmed' => 'yes',
					),
				)
			);

			$this->_add_control(
				'title_trimmed_ending_text',
				array(
					'type'      => 'text',
					'label'     => esc_html__( 'Title Trimmed Ending', 'kitify' ),
					'default'   => '...',
					'condition' => array(
						'title_trimmed' => 'yes',
					)
				)
			);

			$this->_add_control(
				'show_image',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Show Posts Featured Image', 'kitify' ),
					'label_on'     => esc_html__( 'Yes', 'kitify' ),
					'label_off'    => esc_html__( 'No', 'kitify' ),
					'return_value' => 'yes',
					'default'      => 'yes'
				)
			);

			$this->_add_control(
				'thumb_size',
				array(
					'type'      => Controls_Manager::SELECT,
					'label'     => esc_html__( 'Featured Image Size', 'kitify' ),
					'default'   => 'full',
					'options'   => kitify_helper()->get_image_sizes(),
					'condition' => array(
						'show_image' => 'yes'
					)
				)
			);

			$this->_add_control(
				'show_excerpt',
				array(
					'label'        => esc_html__( 'Show Excerpt?', 'kitify' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'kitify' ),
					'label_off'    => esc_html__( 'No', 'kitify' ),
					'return_value' => 'true',
					'default'      => false
				)
			);

			$this->_add_control(
				'excerpt_length',
				array(
					'label'     => esc_html__( 'Custom Excerpt Length', 'kitify' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 20,
					'min'       => 0,
					'max'       => 200,
					'step'      => 1,
					'condition' => array(
						'show_excerpt' => 'true'
					)
				)
			);

			$this->_add_control(
				'show_more',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Show Read More Button', 'kitify' ),
					'label_on'     => esc_html__( 'Yes', 'kitify' ),
					'label_off'    => esc_html__( 'No', 'kitify' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->_add_control(
				'more_text',
				array(
					'type'      => 'text',
					'label'     => esc_html__( 'Read More Button Text', 'kitify' ),
					'default'   => esc_html__( 'Read More', 'kitify' ),
					'condition' => array(
						'show_more' => 'yes',
					),
				)
			);

			$this->_add_icon_control(
				'more_icon',
				[
					'label'       => __( 'Read More Icon', 'kitify' ),
					'type'        => Controls_Manager::ICON,
					'file'        => '',
					'skin'        => 'inline',
					'label_block' => false,
					'condition'   => array(
						'show_more' => 'yes',
					),
				]
			);

			$this->_end_controls_section();
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
				'floating_postformat',
				[
					'label'     => esc_html__( 'Show Post Format', 'kitify' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'kitify' ),
					'label_off' => esc_html__( 'No', 'kitify' ),
					'default'   => 'no'
				]
			);

			$this->_add_control(
				'floating_counter',
				[
					'label'     => esc_html__( 'Show Post Counter', 'kitify' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'kitify' ),
					'label_off' => esc_html__( 'No', 'kitify' ),
					'default'   => 'no'
				]
			);
			$this->_add_control(
				'floating_counter_as',
				[
					'label'     => esc_html__( 'Counter as Icon', 'kitify' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'kitify' ),
					'label_off' => esc_html__( 'No', 'kitify' ),
					'default'   => 'no',
					'condition' => [
						'floating_counter' => 'yes'
					]
				]
			);
			$this->_add_icon_control(
				'counter_icon',
				[
					'label'       => __( 'Custom Icon', 'kitify' ),
					'type'        => Controls_Manager::ICONS,
					'file'        => '',
					'skin'        => 'inline',
					'label_block' => false,
					'condition'   => [
						'floating_counter'    => 'yes',
						'floating_counter_as' => 'yes',
					],
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
						'tag'      => esc_html__( 'Tags', 'kitify' ),
						'author'   => esc_html__( 'Author', 'kitify' ),
						'date'     => esc_html__( 'Posted Date', 'kitify' ),
						'comment'  => esc_html__( 'Comment', 'kitify' ),
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
						'after_button'  => esc_html__( 'After Button', 'kitify' ),
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
						'after_button'  => esc_html__( 'After Button', 'kitify' ),
					],
					'default'   => 'after_title',
					'condition' => [
						'show_meta' => 'yes',
					]
				]
			);

			$this->_add_control(
				'show_author_avatar',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Show Author Image', 'kitify' ),
					'label_on'     => esc_html__( 'Yes', 'kitify' ),
					'label_off'    => esc_html__( 'No', 'kitify' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => [
						'show_meta' => 'yes',
					]
				)
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
					'object_type' => '',
					'presets'     => [ 'full' ]
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

		}

		protected function _register_section_style_general( $css_scheme ){
			/** Style section */
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
						'{{WRAPPER}} '                         => '--kitify-carousel-item-top-space: {{TOP}}{{UNIT}}; --kitify-carousel-item-right-space: {{RIGHT}}{{UNIT}};--kitify-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-carousel-item-left-space: {{LEFT}}{{UNIT}};--kitify-gcol-top-space: {{TOP}}{{UNIT}}; --kitify-gcol-right-space: {{RIGHT}}{{UNIT}};--kitify-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-gcol-left-space: {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_box_style',
				array(
					'label'      => esc_html__( 'Item', 'kitify' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_responsive_control(
				'boxcontent_alignment',
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
						'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'text-align: {{VALUE}};',
					),
				)
			);
			$this->_add_responsive_control(
				'boxcontent_v_alignment',
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
						'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'align-items: {{VALUE}};',
					),
				)
			);

			$this->_add_control(
				'box_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'background-color: {{VALUE}}',
					),
				)
			);
			$this->_add_control(
				'box_overlay_heading',
				[
					'label'       => esc_html__( 'Background Overlay', 'kitify' ),
					'type'        => Controls_Manager::HEADING,
					'label_block' => true,
					'separator'   => 'before',
				]
			);
			$this->_start_controls_tabs( 'box_bg_tabs' );
			$this->_start_controls_tab( 'box_bg_tab_normal', [
				'label' => __( 'Normal', 'kitify' ),
			] );
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'box_bg_overlay',
					'selector' => '{{WRAPPER}} .kitify-posts__inner-box:after',
				),
				25
			);
			$this->_end_controls_tab();
			$this->_start_controls_tab( 'box_bg_tab_hover', [
				'label' => __( 'Hover', 'kitify' ),
			] );
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'box_bg_overlay_hover',
					'selector' => '{{WRAPPER}} .kitify-posts__inner-box:hover:after',
				),
				25
			);
			$this->_end_controls_tab();
			$this->_end_controls_tabs();

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'box_border',
					'label'       => esc_html__( 'Border', 'kitify' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['inner-box'],
					'separator'   => 'before',
				)
			);

			$this->_add_responsive_control(
				'box_border_radius',
				array(
					'label'      => __( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'inner_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['inner-box'],
				)
			);

			$this->_add_responsive_control(
				'box_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_thumb_style',
				array(
					'label'      => esc_html__( 'Thumbnail', 'kitify' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_responsive_control(
				'custom_thumb_width',
				array(
					'label'       => esc_html__( 'Thumbnail Width', 'kitify' ),
					'description' => esc_html__( 'This option only works with the List preset', 'kitify' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 100,
							'max' => 1000,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						)
					),
					'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
					'selectors'   => [
						'{{WRAPPER}} .kitify-posts' => '--kitify-posts-thumbnail-width: {{SIZE}}{{UNIT}};'
					],
				)
			);
			$this->_add_responsive_control(
				'custom_thumb_spacing',
				array(
					'label'       => esc_html__( 'Thumbnail Spacing', 'kitify' ),
					'description' => esc_html__( 'This option only works with the List preset', 'kitify' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 100,
							'max' => 1000,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						)
					),
					'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
					'selectors'   => [
						'{{WRAPPER}} .kitify-posts' => '--kitify-posts-thumbnail-spacing: {{SIZE}}{{UNIT}};'
					],
				)
			);

			$this->_add_control(
				'enable_custom_image_height',
				array(
					'label'        => esc_html__( 'Enable Custom Image Height', 'kitify' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'kitify' ),
					'label_off'    => esc_html__( 'No', 'kitify' ),
					'return_value' => 'true',
					'default'      => '',
					'prefix_class' => 'active-object-fit active-object-fit-',
				)
			);

			$this->_add_responsive_control(
				'custom_image_height',
				array(
					'label'      => esc_html__( 'Image Height', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'min' => 100,
							'max' => 1000,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						)
					),
					'size_units' => [ 'px', '%', 'vh', 'vw' ],
					'default'    => [
						'size' => 300,
						'unit' => 'px'
					],
					'selectors'  => [
						'{{WRAPPER}} ' . $css_scheme['link'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
					],
					'condition'  => [
						'enable_custom_image_height!' => ''
					]
				)
			);

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'thumb_border',
					'label'       => esc_html__( 'Border', 'kitify' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['thumb'],
				)
			);

			$this->_add_responsive_control(
				'thumb_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['thumb'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'thumb_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['thumb'],
				)
			);

			$this->_add_responsive_control(
				'thumb_margin',
				array(
					'label'      => esc_html__( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['thumb'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'thumb_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['thumb'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_content_style',
				array(
					'label'      => esc_html__( 'Item Content', 'kitify' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_control(
				'show_content_hover',
				[
					'label'   => __( 'Show Content When Hover', 'kitify' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => '',
					'condition' => [
						'preset' => ['grid-2'],
					],
					'prefix_class' => 'kitify--content-hover-',
				]
			);

			$this->_add_responsive_control(
				'content_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'content_margin',
				array(
					'label'      => esc_html__( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					)
				)
			);

			$this->_add_responsive_control(
				'content_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_start_controls_tabs( 'content_style_tabs' );
			$this->_start_controls_tab( 'content_normal',
				[
					'label' => __( 'Normal', 'kitify' ),
				]
			);
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'content_bg',
					'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
				),
				25
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'content_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
				)
			);

			$this->_end_controls_tab();
			$this->_start_controls_tab( 'content_hover',
				[
					'label' => __( 'Hover', 'kitify' ),
				]
			);
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'content_bg_hover',
					'selector' => '{{WRAPPER}} .kitify-posts__outer-box:hover .kitify-posts__inner-content',
				),
				25
			);
			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'content_shadow_hover',
					'selector' => '{{WRAPPER}} .kitify-posts__inner-box:hover .kitify-posts__inner-content',
				)
			);

			$this->_end_controls_tab();
			$this->_end_controls_tabs();

			$this->_end_controls_section();

			$this->_register_section_style_content_inner( $css_scheme );

			$this->_start_controls_section(
				'section_title_style',
				array(
					'label'      => esc_html__( 'Title', 'kitify' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_control(
				'title_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['title'] => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_start_controls_tabs( 'tabs_title_color' );

			$this->_start_controls_tab(
				'tab_title_color_normal',
				array(
					'label' => esc_html__( 'Normal', 'kitify' ),
				)
			);

			$this->_add_control(
				'title_color',
				array(
					'label'     => esc_html__( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
						'{{WRAPPER}} ' . $css_scheme['title'] .' a'=> 'color: {{VALUE}}',
					),
				)
			);
      $this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'title_border',
					'label'       => esc_html__( 'Border', 'kitify' ),
					'placeholder' => '',
					'default'     => '',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['title'],
				)
			);
			$this->_end_controls_tab();

			$this->_start_controls_tab(
				'tab_title_color_hover',
				array(
					'label' => esc_html__( 'Hover', 'kitify' ),
				)
			);

			$this->_add_control(
				'title_color_hover',
				array(
					'label'     => esc_html__( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['title'] . ':hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} ' . $css_scheme['title'] . ':hover a' => 'color: {{VALUE}}',
						'{{WRAPPER}} ' . $css_scheme['title'] . ' a:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_end_controls_tab();

			$this->_end_controls_tabs();

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
				)
			);

			$this->_add_responsive_control(
				'title_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'kitify' ),
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
						'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_responsive_control(
				'title_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'title_margin',
				array(
					'label'      => esc_html__( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_excerpt_style',
				array(
					'label'      => esc_html__( 'Excerpt', 'kitify' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
					'condition'  => [
						'show_excerpt' => 'true'
					]
				)
			);
			$this->_add_responsive_control(
				'excerpt_width',
				array(
					'label'      => esc_html__( 'Width', 'nova' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => ['px', '%'],
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'excerpt_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'excerpt_color',
				array(
					'label'     => esc_html__( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'excerpt_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['excerpt'],
				)
			);

			$this->_add_responsive_control(
				'excerpt_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'kitify' ),
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
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_responsive_control(
				'excerpt_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'excerpt_margin',
				array(
					'label'      => esc_html__( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_button_style',
				array(
					'label'      => esc_html__( 'Button', 'kitify' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);
			$this->_add_control(
				'show_btn_hover',
				[
					'label'   => __( 'Show Button When Hover', 'kitify' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => '',
					'prefix_class' => 'kitify--button-hover-',
				]
			);

			$this->_add_control(
				'button_icon_position',
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

			$this->_start_controls_tabs( 'tabs_button_style' );

			$this->_start_controls_tab(
				'tab_button_normal',
				array(
					'label' => esc_html__( 'Normal', 'kitify' ),
				)
			);
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'button_bg',
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
					'exclude'  => array(
						'image',
						'position',
						'xpos',
						'ypos',
						'attachment',
						'attachment_alert',
						'repeat',
						'size',
						'bg_width'
					),
				)
			);

			$this->_add_control(
				'button_color',
				array(
					'label'     => esc_html__( 'Text Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
					)
				)
			);


			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'button_typography',
					'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
				)
			);

			$this->_add_responsive_control(
				'button_icon_size',
				array(
					'label'     => esc_html__( 'Icon Size', 'kitify' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'button_icon_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_responsive_control(
				'button_icon_margin',
				array(
					'label'      => esc_html__( 'Icon Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
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

			$this->_add_responsive_control(
				'button_margin',
				array(
					'label'      => esc_html__( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
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

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'button_border',
					'label'       => esc_html__( 'Border', 'kitify' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
				)
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'button_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
				)
			);

			$this->_end_controls_tab();

			$this->_start_controls_tab(
				'tab_button_hover',
				array(
					'label' => esc_html__( 'Hover', 'kitify' ),
				)
			);

			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'button_hover_bg',
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
					'exclude'  => array(
						'image',
						'position',
						'xpos',
						'ypos',
						'attachment',
						'attachment_alert',
						'repeat',
						'size',
						'bg_width'
					),
				)
			);

			$this->_add_control(
				'button_hover_color',
				array(
					'label'     => esc_html__( 'Text Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'button_hover_typography',
					'label'    => esc_html__( 'Typography', 'kitify' ),
					'selector' => '{{WRAPPER}}  ' . $css_scheme['button'] . ':hover',
				)
			);

			$this->_add_control(
				'button_hover_text_decor',
				array(
					'label'     => esc_html__( 'Text Decoration', 'kitify' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'none'      => esc_html__( 'None', 'kitify' ),
						'underline' => esc_html__( 'Underline', 'kitify' ),
					),
					'default'   => 'none',
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'text-decoration: {{VALUE}}',
					),
				)
			);

			$this->_add_responsive_control(
				'button_icon_size_hover',
				array(
					'label'     => esc_html__( 'Icon Size', 'kitify' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover .kitify-btn-more-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'button_icon_color_hover',
				array(
					'label'     => esc_html__( 'Icon Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover .kitify-btn-more-icon' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_responsive_control(
				'button_icon_margin_hover',
				array(
					'label'      => esc_html__( 'Icon Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover .kitify-btn-more-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'button_hover_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'button_hover_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'button_hover_border',
					'label'       => esc_html__( 'Border', 'kitify' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
				)
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'button_hover_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
				)
			);

			$this->_end_controls_tab();

			$this->_end_controls_tabs();

			$this->_add_responsive_control(
				'button_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'kitify' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'kitify' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'kitify' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'kitify' ),
							'icon'  => 'eicon-text-align-right',
						)
					),
					'selectors' => array(
						'{{WRAPPER}} .kitify-posts__more-wrap' => 'text-align: {{VALUE}};',
					),
					'separator' => 'before',
				)
			);

			$this->_end_controls_section();
		}

		protected function _register_section_style_meta( $css_scheme ) {
			$this->_start_controls_section(
				'section_meta1',
				array(
					'label'     => esc_html__( 'Meta 1', 'kitify' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_meta' => 'yes'
					],
				)
			);

			$this->_add_control(
				'meta1_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta1_color',
				array(
					'label'     => esc_html__( 'Text Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta1_link_color',
				array(
					'label'     => esc_html__( 'Links Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] . ' a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta1_link_color_hover',
				array(
					'label'     => esc_html__( 'Links Hover Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] . ' a:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'meta1_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['meta1'],
				)
			);

			$this->_add_responsive_control(
				'meta1_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta1_margin',
				array(
					'label'      => esc_html__( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta1_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'kitify' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start'    => array(
							'title' => esc_html__( 'Left', 'kitify' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center'        => array(
							'title' => esc_html__( 'Center', 'kitify' ),
							'icon'  => 'eicon-h-align-center',
						),
						'flex-end'      => array(
							'title' => esc_html__( 'Right', 'kitify' ),
							'icon'  => 'eicon-h-align-right',
						),
						'space-between' => array(
							'title' => esc_html__( 'Stretch', 'kitify' ),
							'icon'  => 'eicon-h-align-stretch',
						)
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'justify-content: {{VALUE}};',
					)
				)
			);

			$this->_add_responsive_control(
				'meta1_text_alignment',
				array(
					'label'     => esc_html__( 'Text Alignment', 'kitify' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'kitify' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'kitify' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'kitify' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_control(
				'meta1_divider',
				array(
					'label'     => esc_html__( 'Meta Divider', 'kitify' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1-item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
					),
				)
			);

			$this->_add_control(
				'meta1_divider_gap',
				array(
					'label'      => esc_html__( 'Divider Gap', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 90,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1-item'] . ':not(:first-child):before' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta1_icon_size',
				array(
					'label'      => esc_html__( 'Icon Size', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1-item'] . ' .meta--icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'meta1_icon_spacing',
				array(
					'label'      => esc_html__( 'Icon Spacing', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1-item'] . ' .meta--icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_meta2',
				array(
					'label'     => esc_html__( 'Meta 2', 'kitify' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_meta' => 'yes'
					],
				)
			);

			$this->_add_control(
				'meta2_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta2_color',
				array(
					'label'     => esc_html__( 'Text Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta2_link_color',
				array(
					'label'     => esc_html__( 'Links Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] . ' a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta2_link_color_hover',
				array(
					'label'     => esc_html__( 'Links Hover Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] . ' a:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'meta2_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['meta2'],
				)
			);

			$this->_add_responsive_control(
				'meta2_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta2_margin',
				array(
					'label'      => esc_html__( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta2_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'kitify' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start'    => array(
							'title' => esc_html__( 'Left', 'kitify' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center'        => array(
							'title' => esc_html__( 'Center', 'kitify' ),
							'icon'  => 'eicon-h-align-center',
						),
						'flex-end'      => array(
							'title' => esc_html__( 'Right', 'kitify' ),
							'icon'  => 'eicon-h-align-right',
						),
						'space-between' => array(
							'title' => esc_html__( 'Stretch', 'kitify' ),
							'icon'  => 'eicon-h-align-stretch',
						)
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'justify-content: {{VALUE}};',
					)
				)
			);

			$this->_add_responsive_control(
				'meta2_text_alignment',
				array(
					'label'     => esc_html__( 'Text Alignment', 'kitify' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'kitify' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'kitify' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'kitify' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_control(
				'meta2_divider',
				array(
					'label'     => esc_html__( 'Meta Divider', 'kitify' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2-item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
					),
				)
			);

			$this->_add_control(
				'meta2_divider_gap',
				array(
					'label'      => esc_html__( 'Divider Gap', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 90,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2-item'] . ':not(:first-child):before' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'meta2_icon_size',
				array(
					'label'      => esc_html__( 'Icon Size', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2-item'] . ' .meta--icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'meta2_icon_spacing',
				array(
					'label'      => esc_html__( 'Icon Spacing', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2-item'] . ' .meta--icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();
		}

		protected function _register_section_style_pagination( $css_scheme ){
			/**
			 * Pagination section
			 */
			$this->_start_controls_section(
				'section_pagination_style',
				[
					'label'     => __( 'Pagination', 'kitify' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'paginate' => 'yes',
					],
				]
			);

			$this->_add_responsive_control(
				'pagination_align',
				[
					'label'     => __( 'Alignment', 'kitify' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => __( 'Left', 'kitify' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'kitify' ),
							'icon'  => 'eicon-h-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'kitify' ),
							'icon'  => 'eicon-h-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => 'text-align: {{VALUE}}'
					]
				]
			);

			$this->_add_responsive_control(
				'pagination_spacing',
				[
					'label'     => __( 'Spacing', 'kitify' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->_add_control(
				'show_pagination_border',
				[
					'label'        => __( 'Border', 'kitify' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_off'    => __( 'Hide', 'kitify' ),
					'label_on'     => __( 'Show', 'kitify' ),
					'default'      => 'yes',
					'return_value' => 'yes',
				]
			);

			$this->_add_control(
				'pagination_border_color',
				[
					'label'     => __( 'Border Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-border-color: {{VALUE}}',
					],
					'condition' => [
						'show_pagination_border' => 'yes',
					],
				]
			);

			$this->_add_responsive_control(
				'pagination_item_width',
				[
					'label'     => __( 'Item Width', 'kitify' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-item-width: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->_add_responsive_control(
				'pagination_item_spacing',
				[
					'label'     => __( 'Item Spacing', 'kitify' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-item-spacing: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->_add_responsive_control(
				'pagination_item_radius',
				[
					'label'      => __( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .kitify-pagination .page-numbers' => 'border-radius: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->_add_responsive_control(
				'pagination_padding',
				[
					'label'      => __( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'pagination_typography',
					'selector' => '{{WRAPPER}} .kitify-pagination',
				]
			);

			$this->_start_controls_tabs( 'pagination_style_tabs' );

			$this->_start_controls_tab( 'pagination_style_normal',
				[
					'label' => __( 'Normal', 'kitify' ),
				]
			);

			$this->_add_control(
				'pagination_link_color',
				[
					'label'     => __( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-link-color: {{VALUE}}',
					],
				]
			);

			$this->_add_control(
				'pagination_link_bg_color',
				[
					'label'     => __( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-link-bg-color: {{VALUE}}',
					],
				]
			);

			$this->_end_controls_tab();

			$this->_start_controls_tab( 'pagination_style_hover',
				[
					'label' => __( 'Active', 'kitify' ),
				]
			);

			$this->_add_control(
				'pagination_link_color_hover',
				[
					'label'     => __( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-link-hover-color: {{VALUE}}',
					],
				]
			);

			$this->_add_control(
				'pagination_link_bg_color_hover',
				[
					'label'     => __( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-pagination' => '--kitify-pagination-link-hover-bg-color: {{VALUE}}',
					],
				]
			);

			$this->_end_controls_tab();

			$this->_end_controls_tabs();

			$this->_end_controls_section();
		}

		protected function _register_section_style_floating_date( $css_scheme ){
			/** Floating date **/
			$this->_start_controls_section(
				'section_floating_date',
				array(
					'label'     => esc_html__( 'Floating Date', 'kitify' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'floating_date' => 'yes'
					]
				)
			);

			$this->_add_control(
				'floating_date_bgcolor',
				array(
					'label'     => esc_html__( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .kitify-posts__floating_date' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'floating_date_color',
				array(
					'label'     => esc_html__( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .kitify-posts__floating_date' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'floating_date_typography',
					'selector' => '{{WRAPPER}} .kitify-posts__floating_date',
				)
			);
			$this->_add_responsive_control(
				'floating_date_width',
				array(
					'label'      => esc_html__( 'Width', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_date' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_date_height',
				array(
					'label'      => esc_html__( 'Height', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_date' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_date_border',
				array(
					'label'      => esc_html__( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_date' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_date_top_position',
				array(
					'label'      => esc_html__( 'Top Indent', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_date' => 'top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_date_left_position',
				array(
					'label'      => esc_html__( 'Left Indent', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_date' => 'left: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_end_controls_section();
		}

		protected function _register_section_style_floating_counter( $css_scheme ) {
			/** Floating Counter **/
			$this->_start_controls_section(
				'section_floating_counter',
				array(
					'label'     => esc_html__( 'Floating Counter', 'kitify' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'floating_counter' => 'yes'
					]
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'floating_counter_typography',
					'selector' => '{{WRAPPER}} .kitify-floating-counter',
				)
			);

			$this->_add_responsive_control(
				'floating_counter_padding',
				array(
					'label'      => esc_html__( 'Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .kitify-floating-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				)
			);
			$this->_add_responsive_control(
				'floating_counter_margin',
				array(
					'label'      => esc_html__( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .kitify-floating-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				)
			);
			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'floating_counter_border',
					'label'     => esc_html__( 'Border', 'kitify' ),
					'selector'  => '{{WRAPPER}} .kitify-floating-counter',
					'separator' => 'before',
				)
			);

			$this->_add_responsive_control(
				'floating_counter_radius',
				array(
					'label'      => __( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-floating-counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_start_controls_tabs( 'floating_counter_style_tabs' );

			$this->_start_controls_tab( 'floating_counter_style_normal',
				[
					'label' => __( 'Normal', 'kitify' ),
				]
			);

			$this->_add_control(
				'floating_counter_color',
				[
					'label'     => __( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-floating-counter' => 'color: {{VALUE}}',
					],
				]
			);

			$this->_add_control(
				'floating_counter_bgcolor',
				[
					'label'     => __( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-floating-counter' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->_end_controls_tab();
			$this->_start_controls_tab( 'floating_counter_style_hover',
				[
					'label' => __( 'Normal', 'kitify' ),
				]
			);

			$this->_add_control(
				'floating_counter_hover_color',
				[
					'label'     => __( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-posts__item:hover .kitify-floating-counter' => 'color: {{VALUE}}',
					],
				]
			);

			$this->_add_control(
				'floating_counter_hover_bgcolor',
				[
					'label'     => __( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-posts__item:hover .kitify-floating-counter' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->_add_control(
				'floating_counter_hover_bordercolor',
				[
					'label'     => __( 'Border Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .kitify-posts__item:hover .kitify-floating-counter' => 'border-color: {{VALUE}}',
					],
				]
			);

			$this->_end_controls_tab();
			$this->_end_controls_tabs();
			$this->_end_controls_section();
		}

		protected function _register_section_style_floating_category( $css_scheme ) {
			/** Floating Category **/
			$this->_start_controls_section(
				'section_floating_cat',
				array(
					'label'     => esc_html__( 'Floating Category', 'kitify' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'floating_category' => 'yes'
					]
				)
			);

			$this->_add_control(
				'floating_cat_bgcolor',
				array(
					'label'     => esc_html__( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .kitify-posts__floating_category a' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'floating_cat_color',
				array(
					'label'     => esc_html__( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .kitify-posts__floating_category a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'floating_cat_typography',
					'selector' => '{{WRAPPER}} .kitify-posts__floating_category a',
				)
			);
			$this->_add_responsive_control(
				'floating_cat_padding',
				array(
					'label'      => esc_html__( 'Item Padding', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .kitify-posts__floating_category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				)
			);
			$this->_add_responsive_control(
				'floating_cat_margin',
				array(
					'label'      => esc_html__( 'Item Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .kitify-posts__floating_category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				)
			);
			$this->_add_responsive_control(
				'floating_cat_border',
				array(
					'label'      => esc_html__( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_category a' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_cat_top_position',
				array(
					'label'      => esc_html__( 'Top Indent', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_category' => 'top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_cat_left_position',
				array(
					'label'      => esc_html__( 'Left Indent', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_category' => 'left: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_end_controls_section();
		}

		protected function _register_section_style_floating_postformat( $css_scheme ){
			/** Floating Post Format **/
			$this->_start_controls_section(
				'section_floating_format',
				array(
					'label'     => esc_html__( 'Floating PostFormat', 'kitify' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'floating_postformat' => 'yes'
					]
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_margin',
				[
					'label'      => __( 'Margin', 'kitify' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .kitify-posts__floating_postformat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->_add_control(
				'floating_pfm_bgcolor',
				array(
					'label'     => esc_html__( 'Background Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .kitify-posts__floating_postformat' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'floating_pfm_color',
				array(
					'label'     => esc_html__( 'Color', 'kitify' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .kitify-posts__floating_postformat' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'floating_pfm_typography',
					'selector' => '{{WRAPPER}} .kitify-posts__floating_postformat',
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_width',
				array(
					'label'      => esc_html__( 'Width', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_postformat' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_height',
				array(
					'label'      => esc_html__( 'Height', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_postformat' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_border',
				array(
					'label'      => esc_html__( 'Border Radius', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_postformat' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_top_position',
				array(
					'label'      => esc_html__( 'Top Indent', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_postformat' => 'top: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_left_position',
				array(
					'label'      => esc_html__( 'Left Indent', 'kitify' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => - 400,
							'max' => 400,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 100,
						),
						'em' => array(
							'min' => - 50,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .kitify-posts__floating_postformat' => 'left: {{SIZE}}{{UNIT}};',
					),
				)
			);
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

			$this->_register_section_style_floating_counter( $css_scheme );

			$this->_register_section_style_floating_category( $css_scheme );

			$this->_register_section_style_floating_postformat( $css_scheme );

			$this->_register_section_style_pagination( $css_scheme );

			$this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes' ] );

		}

		protected function render() {
			$this->__context = 'render';

			$paged_key = 'post-page' . esc_attr( $this->get_id() );

			$query_post_type = $this->get_settings_for_display( 'query_post_type' );

			if ( $query_post_type == 'current_query' ) {
				$paged_key = 'paged';
				$this->add_render_attribute( 'main-container', 'data-widget_current_query', 'yes' );
			}

			$page = absint( empty( $_GET[ $paged_key ] ) ? 1 : $_GET[ $paged_key ] );
			if ( $query_post_type == 'current_query' ) {
				$page = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
				if ( ! empty( $_GET[ $paged_key ] ) ) {
					$page = $_GET[ $paged_key ];
				}
			}

			$query_args = [
				'posts_per_page' => $this->get_settings_for_display( 'query_posts_per_page' ),
				'paged'          => 1,
			];

			if ( 1 < $page ) {
				$query_args['paged'] = $page;
			}

			$module_query = Module_Query::get_instance();
			$this->_query = $module_query->get_query( $this, 'query', $query_args, [] );

			$this->_open_wrap();
			include $this->_get_global_template( 'index' );
			$this->_close_wrap();
		}

		protected function the_query() {
			return $this->_query;
		}

		protected function render_post_format_icon( $type ) {
			$output = '';
			switch ( $type ) {
				case 'video':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>';
					break;
				case 'audio':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M481.44 0a29.38 29.38 0 0 0-9.25 1.5l-290.78 96C168.72 101.72 160 114 160 128v244.75C143 360 120.69 352 96 352c-53 0-96 35.81-96 80s43 80 96 80 96-35.81 96-80V256l288-96v148.75C463 296 440.69 288 416 288c-53 0-96 35.81-96 80s43 80 96 80 96-35.81 96-80V32c0-18.25-14.31-32-30.56-32zM96 480c-34.69 0-64-22-64-48s29.31-48 64-48 64 22 64 48-29.31 48-64 48zm320-64c-34.69 0-64-22-64-48s29.31-48 64-48 64 22 64 48-29.31 48-64 48zm64-289.72l-288 96V128h-.56v-.12L480 32.62z"></path></svg>';
					break;
				case 'gallery':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M528 32H112c-26.51 0-48 21.49-48 48v16H48c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48v-16h16c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zm-48 400c0 8.822-7.178 16-16 16H48c-8.822 0-16-7.178-16-16V144c0-8.822 7.178-16 16-16h16v240c0 26.51 21.49 48 48 48h368v16zm64-64c0 8.822-7.178 16-16 16H112c-8.822 0-16-7.178-16-16V80c0-8.822 7.178-16 16-16h416c8.822 0 16 7.178 16 16v288zM176 200c30.928 0 56-25.072 56-56s-25.072-56-56-56-56 25.072-56 56 25.072 56 56 56zm0-80c13.234 0 24 10.766 24 24s-10.766 24-24 24-24-10.766-24-24 10.766-24 24-24zm240.971 23.029c-9.373-9.373-24.568-9.373-33.941 0L288 238.059l-31.029-31.03c-9.373-9.373-24.569-9.373-33.941 0l-88 88A24.002 24.002 0 0 0 128 312v28c0 6.627 5.373 12 12 12h360c6.627 0 12-5.373 12-12v-92c0-6.365-2.529-12.47-7.029-16.971l-88-88zM480 320H160v-4.686l80-80 48 48 112-112 80 80V320z"></path></svg>';
					break;
				case 'image':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm16 336c0 8.822-7.178 16-16 16H48c-8.822 0-16-7.178-16-16V112c0-8.822 7.178-16 16-16h416c8.822 0 16 7.178 16 16v288zM112 232c30.928 0 56-25.072 56-56s-25.072-56-56-56-56 25.072-56 56 25.072 56 56 56zm0-80c13.234 0 24 10.766 24 24s-10.766 24-24 24-24-10.766-24-24 10.766-24 24-24zm207.029 23.029L224 270.059l-31.029-31.029c-9.373-9.373-24.569-9.373-33.941 0l-88 88A23.998 23.998 0 0 0 64 344v28c0 6.627 5.373 12 12 12h360c6.627 0 12-5.373 12-12v-92c0-6.365-2.529-12.47-7.029-16.971l-88-88c-9.373-9.372-24.569-9.372-33.942 0zM416 352H96v-4.686l80-80 48 48 112-112 80 80V352z"></path></svg>';
					break;
				case 'link':
					$output = '<svg aria-hidden="true" focusable="false"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M301.148 394.702l-79.2 79.19c-50.778 50.799-133.037 50.824-183.84 0-50.799-50.778-50.824-133.037 0-183.84l79.19-79.2a132.833 132.833 0 0 1 3.532-3.403c7.55-7.005 19.795-2.004 20.208 8.286.193 4.807.598 9.607 1.216 14.384.481 3.717-.746 7.447-3.397 10.096-16.48 16.469-75.142 75.128-75.3 75.286-36.738 36.759-36.731 96.188 0 132.94 36.759 36.738 96.188 36.731 132.94 0l79.2-79.2.36-.36c36.301-36.672 36.14-96.07-.37-132.58-8.214-8.214-17.577-14.58-27.585-19.109-4.566-2.066-7.426-6.667-7.134-11.67a62.197 62.197 0 0 1 2.826-15.259c2.103-6.601 9.531-9.961 15.919-7.28 15.073 6.324 29.187 15.62 41.435 27.868 50.688 50.689 50.679 133.17 0 183.851zm-90.296-93.554c12.248 12.248 26.362 21.544 41.435 27.868 6.388 2.68 13.816-.68 15.919-7.28a62.197 62.197 0 0 0 2.826-15.259c.292-5.003-2.569-9.604-7.134-11.67-10.008-4.528-19.371-10.894-27.585-19.109-36.51-36.51-36.671-95.908-.37-132.58l.36-.36 79.2-79.2c36.752-36.731 96.181-36.738 132.94 0 36.731 36.752 36.738 96.181 0 132.94-.157.157-58.819 58.817-75.3 75.286-2.651 2.65-3.878 6.379-3.397 10.096a163.156 163.156 0 0 1 1.216 14.384c.413 10.291 12.659 15.291 20.208 8.286a131.324 131.324 0 0 0 3.532-3.403l79.19-79.2c50.824-50.803 50.799-133.062 0-183.84-50.802-50.824-133.062-50.799-183.84 0l-79.2 79.19c-50.679 50.682-50.688 133.163 0 183.851z"></path></svg>';
					break;
				case 'quote':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M176 32H64C28.7 32 0 60.7 0 96v128c0 35.3 28.7 64 64 64h64v24c0 30.9-25.1 56-56 56H56c-22.1 0-40 17.9-40 40v32c0 22.1 17.9 40 40 40h16c92.6 0 168-75.4 168-168V96c0-35.3-28.7-64-64-64zm32 280c0 75.1-60.9 136-136 136H56c-4.4 0-8-3.6-8-8v-32c0-4.4 3.6-8 8-8h16c48.6 0 88-39.4 88-88v-56H64c-17.7 0-32-14.3-32-32V96c0-17.7 14.3-32 32-32h112c17.7 0 32 14.3 32 32v216zM448 32H336c-35.3 0-64 28.7-64 64v128c0 35.3 28.7 64 64 64h64v24c0 30.9-25.1 56-56 56h-16c-22.1 0-40 17.9-40 40v32c0 22.1 17.9 40 40 40h16c92.6 0 168-75.4 168-168V96c0-35.3-28.7-64-64-64zm32 280c0 75.1-60.9 136-136 136h-16c-4.4 0-8-3.6-8-8v-32c0-4.4 3.6-8 8-8h16c48.6 0 88-39.4 88-88v-56h-96c-17.7 0-32-14.3-32-32V96c0-17.7 14.3-32 32-32h112c17.7 0 32 14.3 32 32v216z"></path></svg>';
					break;
				case 'aside':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M504 304H296a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0 128H296a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-256H296a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-128H296a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8V56a8 8 0 0 0-8-8zM216 432H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-128H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-256H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8V56a8 8 0 0 0-8-8zm0 128H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8z"></path></svg>';
					break;
				case 'chat':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M569.9 441.1c-.5-.4-22.6-24.2-37.9-54.9 27.5-27.1 44-61.1 44-98.2 0-80-76.5-146.1-176.2-157.9C368.4 72.5 294.3 32 208 32 93.1 32 0 103.6 0 192c0 37 16.5 71 44 98.2-15.3 30.7-37.3 54.5-37.7 54.9-6.3 6.7-8.1 16.5-4.4 25 3.6 8.5 12 14 21.2 14 53.5 0 96.7-20.2 125.2-38.8 9.1 2.1 18.4 3.7 28 4.8 31.5 57.5 105.5 98 191.8 98 20.8 0 40.8-2.4 59.8-6.8 28.5 18.5 71.6 38.8 125.2 38.8 9.2 0 17.5-5.5 21.2-14 3.6-8.5 1.9-18.3-4.4-25zM155.4 314l-13.2-3-11.4 7.4c-20.1 13.1-50.5 28.2-87.7 32.5 8.8-11.3 20.2-27.6 29.5-46.4L83 283.7l-16.5-16.3C50.7 251.9 32 226.2 32 192c0-70.6 79-128 176-128s176 57.4 176 128-79 128-176 128c-17.7 0-35.4-2-52.6-6zm289.8 100.4l-11.4-7.4-13.2 3.1c-17.2 4-34.9 6-52.6 6-65.1 0-122-25.9-152.4-64.3C326.9 348.6 416 278.4 416 192c0-9.5-1.3-18.7-3.3-27.7C488.1 178.8 544 228.7 544 288c0 34.2-18.7 59.9-34.5 75.4L493 379.7l10.3 20.7c9.4 18.9 20.8 35.2 29.5 46.4-37.1-4.2-67.5-19.4-87.6-32.4z"></path></svg>';
					break;
			}

			return apply_filters( 'kitify/'.$this->get_kitify_name().'/format-icon', $output, $type );
		}
	}
}
