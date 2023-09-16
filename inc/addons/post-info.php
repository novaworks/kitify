<?php

/**
 * Class: Kitify_Post_Info
 * Name: Post Info
 * Slug: kitify-post-info
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Post Excerpt Widget
 */
class Kitify_Post_Info extends Kitify_Base {

    protected function enqueue_addon_resources(){
	    if(!kitify_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'kitify-base' );
		    $this->add_style_depends( 'kitify-posts' );
	    }
    }

    public function get_name() {
        return 'kitify-post-info';
    }

    protected function get_widget_title() {
        return esc_html__( 'Post Info', 'kitify' );
    }

    public function get_icon() {
        return 'eicon-post-info';
    }

    public function get_categories() {
        return [ 'kitify-builder' ];
    }

    protected function register_controls() {


	    $css_scheme = [
		    'meta'         => '.kitify-posts__meta',
		    'meta-item'    => '.kitify-posts__meta .kitify-posts__meta__item',
        ];

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Post Info', 'kitify' ),
            ]
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
				    'view'  => esc_html__( 'View', 'kitify' ),
			    ] )
		    ]
	    );

	    $this->_add_control(
		    'metadata',
		    array(
			    'label'         => esc_html__( 'MetaData', 'kitify' ),
			    'type'          => Controls_Manager::REPEATER,
			    'fields'        => $repeater->get_controls(),
			    'title_field'   => '{{{ item_label }}}',
			    'prevent_empty' => false
		    )
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
		    )
	    );

	    $this->end_controls_section();

	    $this->_start_controls_section(
		    'section_meta',
		    array(
			    'label'     => esc_html__( 'Post Info', 'kitify' ),
			    'tab'       => Controls_Manager::TAB_STYLE,
		    )
	    );

	    $this->_add_control(
		    'meta_bg',
		    array(
			    'label'     => esc_html__( 'Background Color', 'kitify' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['meta'] => 'background-color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->_add_control(
		    'meta_color',
		    array(
			    'label'     => esc_html__( 'Text Color', 'kitify' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['meta'] => 'color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->_add_control(
		    'meta_link_color',
		    array(
			    'label'     => esc_html__( 'Links Color', 'kitify' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['meta'] . ' a' => 'color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->_add_control(
		    'meta_link_color_hover',
		    array(
			    'label'     => esc_html__( 'Links Hover Color', 'kitify' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['meta'] . ' a:hover' => 'color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->_add_group_control(
		    Group_Control_Typography::get_type(),
		    array(
			    'name'     => 'meta_typography',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['meta'],
		    )
	    );

	    $this->_add_responsive_control(
		    'meta_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->_add_responsive_control(
		    'meta_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'kitify' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->_add_responsive_control(
		    'meta_alignment',
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
				    '{{WRAPPER}} ' . $css_scheme['meta'] => 'justify-content: {{VALUE}};',
			    )
		    )
	    );

	    $this->_add_responsive_control(
		    'meta_text_alignment',
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
				    '{{WRAPPER}} ' . $css_scheme['meta'] => 'text-align: {{VALUE}};',
			    ),
		    )
	    );

	    $this->_add_control(
		    'meta_divider',
		    array(
			    'label'     => esc_html__( 'Meta Divider', 'kitify' ),
			    'type'      => Controls_Manager::TEXT,
			    'default'   => '',
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['meta-item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
			    ),
		    )
	    );

	    $this->_add_responsive_control(
		    'meta_divider_gap',
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
				    '{{WRAPPER}} ' . $css_scheme['meta-item'] . ':not(:first-child):before' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->_add_responsive_control(
		    'meta_icon_size',
		    array(
			    'label'      => esc_html__( 'Icon Size', 'kitify' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => array( 'px', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['meta-item'] . ' .meta--icon' => 'font-size: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );
		$this->_add_responsive_control(
		    'meta_image_size',
		    array(
			    'label'      => esc_html__( 'Avata Size', 'kitify' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => array( 'px', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['meta-item'] . ' .meta--icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );
		$this->_add_responsive_control(
			'box_border_radius',
			array(
				'label'      => __( 'Avata Border Radius', 'kitify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta-item'] . ' .meta--icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	    $this->_add_responsive_control(
		    'meta_icon_spacing',
		    array(
			    'label'      => esc_html__( 'Icon Spacing', 'kitify' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => array( 'px', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['meta-item'] . ' .meta--icon' => 'margin-right: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->_end_controls_section();

    }

    protected function render() {

	    $metadata = $this->get_settings_for_display('metadata');
	    $show_author_avatar = $this->get_settings_for_display('show_author_avatar');
	    $output = '';

	    foreach ($metadata as $meta) {
		    $item_type = isset($meta['item_type']) ? $meta['item_type'] : '';
		    $meta_icon = $this->_get_icon_setting($meta['item_icon'], '<span class="meta--icon">%s</span>', '', false);
		    $meta_label = !empty($meta['item_label']) ? sprintf('<span class="meta--label">%s</span>', $meta['item_label']) : '';
		    $meta_value = '';
		    $item_type_class = '';

		    switch ($item_type) {
			    case 'category':
				    $meta_value = get_the_category_list('<span class="cspr">, </span>');
				    $item_type_class = 'post__cat';
				    break;
			    case 'tag':
				    $meta_value = get_the_tag_list('', '<span class="cspr">, </span>');
				    $item_type_class = 'post__tag';
				    break;
			    case 'author':
				    if(filter_var($show_author_avatar, FILTER_VALIDATE_BOOLEAN)){
					    $meta_icon = sprintf('<span class="meta--icon">%s</span>', get_avatar( get_the_author_meta( "ID" )));
				    }
				    $meta_value = sprintf('<a href="%1$s" class="posted-by__author" rel="author">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ) );
				    $item_type_class = 'post__author posted-by';
				    break;
			    case 'date':
				    $meta_value = get_the_date();
				    $item_type_class = 'post__date';
				    break;
			    case 'comment':
				    $meta_value = sprintf('<a href="%1$s">%2$s</a>', esc_url( get_comments_link() ), esc_html( get_comments_number() ) );
				    $item_type_class = 'post__comment';
				    break;
			    case 'view':
					$views_count = (int) get_post_meta(get_the_ID(), 'post_views_count', true);
				    $meta_value = sprintf( _n( '%s view', '%s views', $views_count, 'kitify' ), kitify_helper()->number_format_short($views_count, 2) );
				    $item_type_class = 'post__views';
				    break;
		    }

		    if (!empty($meta_value)) {
			    $meta_value = sprintf('<span class="meta--value">%s</span>', $meta_value);
		    }

		    if (!empty($meta_value)) {
			    $output .= sprintf('<div class="kitify-posts__meta__item kitify-posts__meta__item--%4$s %5$s">%1$s%2$s%3$s</div>', $meta_icon, $meta_label, $meta_value, $item_type, $item_type_class);
		    }

	    }

	    if (!empty($output)) {
		    echo sprintf('<div class="kitify-posts__meta kitify-posts__meta">%s</div>', $output);
	    }
    }

    
}