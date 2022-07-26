<?php

/**
 * Class: Kitify_Post_Navigation
 * Name: Post Navigation
 * Slug: kitify-post-navigation
 */

namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Kitify_Post_Navigation extends Kitify_Base {

    protected function enqueue_addon_resources(){

        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/post-navigation.css'), [], kitify()->get_version());

        $this->add_style_depends( $this->get_name() );
    }

    public function get_name() {
		return 'kitify-post-navigation';
	}

	public function get_widget_title() {
		return __( 'Post Navigation', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-post-navigation';
	}
    public function get_categories() {
        return [ 'kitify-builder' ];
    }

	public function get_keywords() {
        return [ 'post', 'navigation', 'menu', 'links' ];
	}

	protected function register_controls() {
        $this->start_controls_section(
            'section_post_navigation_content',
            [
                'label' => __( 'Post Navigation', 'kitify' ),
            ]
        );

        $this->add_control(
            'show_menu',
            [
                'label' => __( 'Show Menu', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'kitify' ),
                'label_off' => __( 'Hide', 'kitify' ),
                'return' => 'yes',
                'default' => 'no',
            ]
        );
        $this->_add_control(
            'menu_link',
            [
                'label' => __( 'Menu Link', 'kitify' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'kitify' ),
                'default' => [
                    'url' => '#',
                ],
                'condition' => array(
                    'show_menu' => 'yes',
                ),
            ]
        );

        $this->add_control(
            'show_label',
            [
                'label' => __( 'Label', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'kitify' ),
                'label_off' => __( 'Hide', 'kitify' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'prev_label',
            [
                'label' => __( 'Previous Label', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Previous', 'kitify' ),
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'next_label',
            [
                'label' => __( 'Next Label', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Next', 'kitify' ),
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'menu_label',
            [
                'label' => __( 'Menu Label', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Blog', 'kitify' ),
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_arrow',
            [
                'label' => __( 'Arrows', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'kitify' ),
                'label_off' => __( 'Hide', 'kitify' ),
                'default' => 'yes',
            ]
        );

        $this->_add_icon_control(
            'arrow_prev',
            array(
                'label'       => esc_html__( 'Previous Arrow Icon', 'kitify' ),
                'label_block' => true,
                'default'     => 'novaicon-arrow-left',
                'fa5_default' => array(
                    'value'   => 'novaicon-arrow-left',
                    'library' => 'novaicon',
                ),
                'condition' => array(
                    'show_arrow' => 'yes',
                ),
            )
        );

        $this->_add_icon_control(
            'arrow_next',
            array(
                'label'       => esc_html__( 'Next Arrow Icon', 'kitify' ),
                'label_block' => true,
                'default'     => 'novaicon-arrow-right',
                'fa5_default' => array(
                    'value'   => 'novaicon-arrow-right',
                    'library' => 'novaicon',
                ),
                'condition' => array(
                    'show_arrow' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_title',
            [
                'label' => __( 'Post Title', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'kitify' ),
                'label_off' => __( 'Hide', 'kitify' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_borders',
            [
                'label' => __( 'Borders', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'kitify' ),
                'label_off' => __( 'Hide', 'kitify' ),
                'default' => 'yes',
                'prefix_class' => 'elementor-post-navigation-borders-',
            ]
        );

        // Filter out post type without taxonomies
        $post_type_options = [];
        $post_type_taxonomies = [];
        foreach ( kitify_helper()->get_post_types() as $post_type => $post_type_label ) {
            $taxonomies = kitify_helper()->get_taxonomies( [ 'object_type' => $post_type ], false );
            if ( empty( $taxonomies ) ) {
                continue;
            }

            $post_type_options[ $post_type ] = $post_type_label;
            $post_type_taxonomies[ $post_type ] = [];
            foreach ( $taxonomies as $taxonomy ) {
                $post_type_taxonomies[ $post_type ][ $taxonomy->name ] = $taxonomy->label;
            }
        }

        $this->add_control(
            'in_same_term',
            [
                'label' => __( 'In same Term', 'kitify' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $post_type_options,
                'default' => '',
                'multiple' => true,
                'label_block' => true,
                'description' => __( 'Indicates whether next post must be within the same taxonomy term as the current post, this lets you set a taxonomy per each post type', 'kitify' ),
            ]
        );

        foreach ( $post_type_options as $post_type => $post_type_label ) {
            $this->add_control(
                $post_type . '_taxonomy',
                [
                    'label' => $post_type_label . ' ' . __( 'Taxonomy', 'kitify' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => $post_type_taxonomies[ $post_type ],
                    'default' => '',
                    'condition' => [
                        'in_same_term' => $post_type,
                    ],
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'label_style',
            [
                'label' => __( 'Label', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_label_style' );

        $this->start_controls_tab(
            'label_color_normal',
            [
                'label' => __( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
                'selectors' => [
                    '{{WRAPPER}} span.post-navigation__prev--label' => 'color: {{VALUE}};',
                    '{{WRAPPER}} span.post-navigation__next--label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'label_color_hover',
            [
                'label' => __( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control(
            'label_hover_color',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.post-navigation__prev--label:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} span.post-navigation__next--label:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} span.post-navigation__prev--label, {{WRAPPER}} span.post-navigation__next--label',
                'exclude' => [ 'line_height' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style',
            [
                'label' => __( 'Title', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_post_navigation_style' );

        $this->start_controls_tab(
            'tab_color_normal',
            [
                'label' => __( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} span.post-navigation__prev--title, {{WRAPPER}} span.post-navigation__next--title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_color_hover',
            [
                'label' => __( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.post-navigation__prev--title:hover, {{WRAPPER}} span.post-navigation__next--title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} span.post-navigation__prev--title, {{WRAPPER}} span.post-navigation__next--title',
                'exclude' => [ 'line_height' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'arrow_style',
            [
                'label' => __( 'Arrow', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_arrow' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_post_navigation_arrow_style' );

        $this->start_controls_tab(
            'arrow_color_normal',
            [
                'label' => __( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-navigation__arrow-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'arrow_color_hover',
            [
                'label' => __( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-navigation__arrow-wrapper:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => __( 'Size', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-navigation__arrow-wrapper' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_padding',
            [
                'label' => __( 'Gap', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .post-navigation__arrow-prev' => 'padding-right: {{SIZE}}{{UNIT}};',
                    'body:not(.rtl) {{WRAPPER}} .post-navigation__arrow-next' => 'padding-left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .post-navigation__arrow-prev' => 'padding-left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .post-navigation__arrow-next' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'borders_section_style',
            [
                'label' => __( 'Borders', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_borders!' => '',
                ],
            ]
        );

        $this->add_control(
            'sep_color',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-navigation__separator' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-post-navigation' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'borders_width',
            [
                'label' => __( 'Size', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-navigation__separator' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-post-navigation' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-post-navigation__next.elementor-post-navigation__link' => 'width: calc(50% - ({{SIZE}}{{UNIT}} / 2))',
                    '{{WRAPPER}} .elementor-post-navigation__prev.elementor-post-navigation__link' => 'width: calc(50% - ({{SIZE}}{{UNIT}} / 2))',
                ],
            ]
        );

        $this->add_control(
            'borders_spacing',
            [
                'label' => __( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-navigation' => 'padding: {{SIZE}}{{UNIT}} 0;',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function render() {
        $settings = $this->get_active_settings();

        $prev_label = '';
        $next_label = '';
        $menu_label = '';
        $prev_arrow = '';
        $next_arrow = '';

        if ( 'yes' === $settings['show_label'] ) {
            $prev_label = '<span class="post-navigation__prev--label">' . $settings['prev_label'] . '</span>';
            $next_label = '<span class="post-navigation__next--label">' . $settings['next_label'] . '</span>';
            if(!empty($settings['menu_label'])){
                $menu_label = '<span class="elementor-post-navigation__link__menu"><span class="post-navigation__menu--label">' . $settings['menu_label'] . '</span></span>';
            }
        }

        if ( 'yes' === $settings['show_arrow'] ) {
            $prev_arrow = $this->_get_icon('arrow_prev', '<span class="post-navigation__arrow-wrapper post-navigation__arrow-prev">%s</span>');
            $next_arrow = $this->_get_icon('arrow_next', '<span class="post-navigation__arrow-wrapper post-navigation__arrow-next">%s</span>');
        }

        $prev_title = '';
        $next_title = '';

        if ( 'yes' === $settings['show_title'] ) {
            $prev_title = '<span class="post-navigation__prev--title">%title</span>';
            $next_title = '<span class="post-navigation__next--title">%title</span>';
        }

        $in_same_term = false;
        $taxonomy = 'category';
        $post_type = get_post_type( get_queried_object_id() );

        if ( ! empty( $settings['in_same_term'] ) && is_array( $settings['in_same_term'] ) && in_array( $post_type, $settings['in_same_term'] ) ) {
            if ( isset( $settings[ $post_type . '_taxonomy' ] ) ) {
                $in_same_term = true;
                $taxonomy = $settings[ $post_type . '_taxonomy' ];
            }
        }

        if ( ! empty( $settings['menu_link']['url'] ) ) {
            $this->add_link_attributes( 'menu_link', $settings['menu_link'] );
        }

        ?>
        <div class="elementor-post-navigation">
            <div class="elementor-post-navigation__prev elementor-post-navigation__link">
                <?php previous_post_link( '%link', $prev_arrow . '<span class="elementor-post-navigation__link__prev">' . $prev_label . $prev_title . '</span>', $in_same_term, '', $taxonomy ); ?>
            </div>
            <?php if ( 'yes' === $settings['show_menu'] ) : ?>
            <div class="elementor-post-navigation__menu elementor-post-navigation__link">
                <a <?php echo $this->get_render_attribute_string( 'menu_link' ); ?>>
                    <span class="post-navigation__arrow-wrapper post-navigation__arrow-menu"><i aria-hidden="true" class="novaicon novaicon-menu-8-1"></i></span>
                    <?php echo $menu_label; ?>
                </a>
            </div>
            <?php endif; ?>
            <?php if ( 'yes' === $settings['show_borders'] ) : ?>
                <div class="elementor-post-navigation__separator-wrapper">
                    <div class="elementor-post-navigation__separator"></div>
                </div>
            <?php endif; ?>
            <div class="elementor-post-navigation__next elementor-post-navigation__link">
                <?php next_post_link( '%link', '<span class="elementor-post-navigation__link__next">' . $next_label . $next_title . '</span>' . $next_arrow, $in_same_term, '', $taxonomy ); ?>
            </div>
        </div>
        <?php
    }
}
