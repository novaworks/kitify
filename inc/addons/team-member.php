<?php
/**
 * Class: Kitify_Team_Member
 * Name: Team Member
 * Slug: kitify-teammember
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use KitifyExtensions\Elementor\Controls\Group_Control_Query;
use KitifyExtensions\Elementor\Classes\Query_Control as Module_Query;

/**
 * Team_Member Widget
 */
class Kitify_Team_Member extends Kitify_Base {

    private $_query = null;

    public $item_counter = 0;

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/team-member.css'), ['kitify-base'], kitify()->get_version());
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-team-member';
    }

    protected function get_widget_title() {
        return esc_html__( 'Team Member', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-person';
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'kitify/team-member/css-scheme',
            array(
                'wrap'              => '.kitify-team-member__list',
                'column'            => '.kitify-team-member__item',
                'inner-box'         => '.kitify-team-member__inner-box',
                'content'           => '.kitify-team-member__content',
                'image_wrap'        => '.kitify-team-member__image',
                'image_instance'    => '.kitify-team-member__image-instance',
                'title'             => '.kitify-team-member__name',
                'desc'              => '.kitify-team-member__desc',
                'position'          => '.kitify-team-member__position',
                'socials'           => '.kitify-team-member__socials',
                'slick_list'        => '.kitify-team-member .slick-list'
            )
        );

        $preset_type = apply_filters(
            'kitify/team-member/control/preset',
            array(
                'type-1' => esc_html__( 'Type 1', 'kitify' ),
                'type-2' => esc_html__( 'Type 2', 'kitify' ),
                'type-3' => esc_html__( 'Type 3', 'kitify' ),
                'type-4' => esc_html__( 'Type 4', 'kitify' ),
                'type-5' => esc_html__( 'Type 5', 'kitify' ),
                'type-6' => esc_html__( 'Type 6', 'kitify' ),
                'type-7' => esc_html__( 'Type 7', 'kitify' ),
                'type-8' => esc_html__( 'Type 8', 'kitify' ),
                'type-9' => esc_html__( 'Type 9', 'kitify' ),
            )
        );

        $datasource = apply_filters(
            'kitify/team-member/control/data-source',
            array(
                'custom' => __( 'Custom', 'kitify' ),
                //'post_type' => __( 'Member Post Type', 'kitify' ),
            )
        );
        /** Data Source section */
        $this->start_controls_section(
            'section_data_source',
            array(
                'label' => esc_html__( 'Data Source', 'kitify' ),
            )
        );

        $this->add_control(
            'data_source',
            array(
                'label'     => esc_html__( 'Data Source', 'kitify' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'custom',
                'options'   => $datasource
            )
        );

        $repeater = new Repeater();
        $repeater->start_controls_tabs( 'items_repeater' );
        $repeater->start_controls_tab( 'general', [ 'label' => __( 'General', 'kitify' ) ] );
        $repeater->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Image', 'kitify' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true )
            )
        );
        $repeater->add_control(
            'name',
            [
                'label' => __( 'Name', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Member #1', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'role',
            [
                'label' => __( 'Role', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Role', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'kitify' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'dynamic' => array( 'active' => true ),
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
        $repeater->end_controls_tab();
        $repeater->start_controls_tab( 'socials', [ 'label' => __( 'Social', 'kitify' ) ] );

        $repeater->add_control(
            's_icon_1',
            array(
                'label'       => esc_html__( 'Item Icon 1', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon()
            )
        );
        $repeater->add_control(
            's_link_1',
            [
                'label' => __( 'Item Link 1', 'kitify' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'kitify' )
            ]
        );
        $repeater->add_control(
            's_icon_2',
            array(
                'label'       => esc_html__( 'Item Icon 2', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_2',
            [
                'label' => __( 'Item Link 2', 'kitify' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'kitify' )
            ]
        );
        $repeater->add_control(
            's_icon_3',
            array(
                'label'       => esc_html__( 'Item Icon 3', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_3',
            [
                'label' => __( 'Item Link 3', 'kitify' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'kitify' )
            ]
        );
        $repeater->add_control(
            's_icon_4',
            array(
                'label'       => esc_html__( 'Item Icon 4', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_4',
            [
                'label' => __( 'Item Link 4', 'kitify' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'kitify' )
            ]
        );
        $repeater->add_control(
            's_icon_5',
            array(
                'label'       => esc_html__( 'Item Icon 5', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_5',
            [
                'label' => __( 'Item Link 5', 'kitify' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'kitify' )
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'items',
            [
                'label' => __( 'Custom Member List', 'kitify' ),
                'type' => Controls_Manager::REPEATER,
                'show_label' => true,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
                        'name' => __( 'Leila Henry', 'kitify' ),
                        'role' => __( 'Creative Director', 'kitify' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'kitify' ),
                        's_icon_1' => 'novaicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'novaicon-b-twitter',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ],
                    [
                        'name' => __( 'Leila Henry', 'kitify' ),
                        'role' => __( 'Creative Director', 'kitify' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'kitify' ),
                        's_icon_1' => 'novaicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'novaicon-b-twitter',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ],
                    [
                        'name' => __( 'Leila Henry', 'kitify' ),
                        'role' => __( 'Creative Director', 'kitify' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'kitify' ),
                        's_icon_1' => 'novaicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'novaicon-b-twitter',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ]
                ],
                'condition' => [
                    'data_source' => 'custom'
                ]
            ]
        );

        $this->end_controls_section();

        /** Layout section */
        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Layout', 'kitify' ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'   => esc_html__( 'Layout type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => array(
                    'grid'    => esc_html__( 'Grid', 'kitify' )
                ),
            )
        );

        $this->add_control(
            'preset',
            array(
                'label'   => esc_html__( 'Preset', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'type-1',
                'options' => $preset_type
            )
        );

        $this->add_control(
            'thumb_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Featured Image Size', 'kitify' ),
                'default'    => 'full',
                'options'    => kitify_helper()->get_image_sizes()
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => kitify_helper()->get_select_range( 6 )
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
                'default' => 'h4',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'show_role',
            array(
                'label'        => esc_html__( 'Show Role/Position?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_social',
            array(
                'label'        => esc_html__( 'Show Social?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_excerpt',
            array(
                'label'        => esc_html__( 'Show Excerpt?', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'excerpt_length',
            array(
                'label'   => esc_html__( 'Custom Excerpt Length', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 20,
                'min'     => 0,
                'max'     => 200,
                'step'    => 1,
                'condition' => array(
                    'show_excerpt' => 'true'
                )
            )
        );

        $this->end_controls_section();

        /** Query section */
        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'kitify' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'data_source' => 'post_type',
                ]

            ]
        );

        $this->add_group_control(
            Group_Control_Query::get_type(),
            [
                'name' => 'query',
                'post_type' => 'la_team_member',
                'presets' => [ 'full' ],
                'fields_options' => [
                    'post_type' => [
                        'default' => 'la_team_member',
                        'options' => [
                            'current_query' => __( 'Current Query', 'kitify' ),
                            'la_team_member' => __( 'Latest', 'kitify' ),
                            'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'kitify' ),
                        ],
                    ],
                    'orderby' => [
                        'default' => 'date',
                        'options' => [
                            'date'  => __( 'Date', 'kitify' ),
                            'title' => __( 'Title', 'kitify' ),
                            'rand' => __( 'Random', 'kitify' ),
                            'menu_order' => __( 'Menu Order', 'kitify' ),
                        ],
                    ],
                    'exclude' => [
                        'options' => [
                            'current_post' => __( 'Current Post', 'kitify' ),
                            'manual_selection' => __( 'Manual Selection', 'kitify' ),
                        ],
                    ],
                    'posts_ids' => [
                        'object_type' => 'la_team_member'
                    ],
                    'exclude_ids' => [
                        'object_type' => 'la_team_member',
                    ],
                    'include_ids' => [
                        'object_type' => 'la_team_member',
                    ]
                ],
                'exclude' => [
                    'exclude_authors',
                    'authors',
                    'offset',
                    'related_fallback',
                    'related_ids',
                    'query_id',
                    'avoid_duplicates',
                    'ignore_sticky_posts'
                ],
            ]
        );

        $this->add_control(
            'paginate',
            [
                'label' => __( 'Pagination', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'paginate_as_loadmore',
            [
                'label' => __( 'Use Load More', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loadmore_text',
            [
                'label' => __( 'Load More Text', 'kitify' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Load More',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->register_carousel_section( [  ], 'columns');

        $this->start_controls_section(
            'section_column_style',
            array(
                'label'      => esc_html__( 'Column', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'column_padding',
            array(
                'label'       => esc_html__( 'Column Padding', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' => '--kitify-carousel-item-top-space: {{TOP}}{{UNIT}}; --kitify-carousel-item-right-space: {{RIGHT}}{{UNIT}};--kitify-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-carousel-item-left-space: {{LEFT}}{{UNIT}};--kitify-gcol-top-space: {{TOP}}{{UNIT}}; --kitify-gcol-right-space: {{RIGHT}}{{UNIT}};--kitify-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-gcol-left-space: {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_box_style',
            array(
                'label'      => esc_html__( 'Item', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->add_responsive_control(
            'text_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'text-align: {{VALUE}};',
                )
            )
        );

        $this->add_responsive_control(
            'item_width',
            array(
                'label'      => esc_html__( 'Item Width', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', '%', 'vh', 'vw'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'box_bg',
            array(
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'box_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['inner-box'],
            )
        );

        $this->add_responsive_control(
            'box_border_radius',
            array(
                'label'      => __( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'inner_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['inner-box'],
            )
        );

        $this->add_responsive_control(
            'box_padding',
            array(
                'label'      => esc_html__( 'Box Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['inner-box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_style',
            array(
                'label'      => esc_html__( 'Content', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'content_bg',
            array(
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => esc_html__( 'Content Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_margin',
            array(
                'label'      => esc_html__( 'Content Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'content_border',
			    'label'       => esc_html__( 'Border', 'kitify' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['content'],
		    )
	    );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_thumb_style',
            array(
                'label'      => esc_html__( 'Image', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
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
            )
        );

        $this->add_control(
            'custom_image_height',
            [
                'label' => __( 'Custom Image Height', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 75,
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->start_controls_tabs( 'image_effects' );

        $this->start_controls_tab( 'normal',
            [
                'label' => __( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control(
            'img_overlay',
            array(
                'label'  => esc_html__( 'Bg Overlay', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-team-member__link:after' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'opacity',
            [
                'label' => __( 'Opacity', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.00,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-team-member__link:after' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} ' . $css_scheme['image_wrap'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'hover',
            [
                'label' => __( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control(
            'img_overlay_hover',
            array(
                'label'  => esc_html__( 'Bg Overlay', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-team-member__inner:hover .kitify-team-member__link:after' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'opacity_hover',
            [
                'label' => __( 'Opacity', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.00,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-team-member__inner:hover .kitify-team-member__link:after' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .kitify-team-member__inner:hover .kitify-team-member__image',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .kitify-team-member__image_wrap',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-team-member__image_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['image_wrap'],
            ]
        );

        $this->end_controls_section();


        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_title_style',
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
                    '{{WRAPPER}} ' . $css_scheme['title'] .' a' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'title_color_hover',
            array(
                'label'  => esc_html__( 'Color Hover', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] . ' a:hover' => 'color: {{VALUE}}',
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

        $this->end_controls_section();

        /**
         * Position Style Section
         */
        $this->start_controls_section(
            'section_position_style',
            array(
                'label'      => esc_html__( 'Position/Role', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'position_color',
            array(
                'label'  => esc_html__( 'Color', 'kitify' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'position_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['position'],
            )
        );

        $this->add_responsive_control(
            'position_padding',
            array(
                'label'      => __( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'position_margin',
            array(
                'label'      => __( 'Margin', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();


        /**
         * Desc Style Section
         */
        $this->start_controls_section(
            'section_desc_style',
            array(
                'label'      => esc_html__( 'Excerpt', 'kitify' ),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'desc_border',
                'label'       => esc_html__( 'Border', 'kitify' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->end_controls_section();
        /**
         * Social Style Section
         */
        $this->start_controls_section('section_social_style', array(
                'label' => esc_html__('Social', 'kitify'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ));
        $this->start_controls_tabs('social_style');
        $this->start_controls_tab('social_normal', [
                'label' => __('Normal', 'kitify'),
            ]);
        $this->add_control('social_bg_color', array(
                'label' => esc_html__('Bg Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'background-color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_color', array(
                'label' => esc_html__('Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'color: {{VALUE}}',
                ),
            ));
        $this->add_responsive_control('social_fz', [
                'label' => __('Font Size', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'font-size: {{SIZE}}px;',
                ],
            ]);
        $this->add_responsive_control('social_pd', [
                'label' => __('Padding', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'padding: {{SIZE}}px;',
                ],
            ]);
        $this->add_responsive_control('social_bd_w', [
                'label' => __('Border Width', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'border-width: {{SIZE}}px;',
                ],
            ]);
        $this->add_control('social_bd_c', array(
                'label' => esc_html__('Border Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'border-color: {{VALUE}}',
                ),
            ));
        $this->add_responsive_control('social_br', array(
                'label' => __('Border Radius', 'kitify'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%'
                ),
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ));
        $this->add_responsive_control('social_mr', array(
                'label' => __('Item Margin', 'kitify'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ));
        $this->end_controls_tab();
        $this->start_controls_tab('social_hover', [
                'label' => __('Hover', 'kitify'),
            ]);
        $this->add_control('social_bg_color_hover', array(
                'label' => esc_html__('Bg Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'background-color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_color_hover', array(
                'label' => esc_html__('Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_bd_c_hover', array(
                'label' => esc_html__('Border Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'border-color: {{VALUE}}',
                ),
            ));
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /**
         * Pagination
         */
        $this->start_controls_section(
            'section_pagination_style',
            [
                'label' => __( 'Pagination', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => __( 'Alignment', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'kitify' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'kitify' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'kitify' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pagination_spacing',
            [
                'label' => __( 'Spacing', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'show_pagination_border',
            [
                'label' => __( 'Border', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'kitify' ),
                'label_on' => __( 'Show', 'kitify' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'pagination_border_color',
            [
                'label' => __( 'Border Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_pagination_border' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_padding',
            [
                'label' => __( 'Padding', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => [ 'em' ],
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a, {{WRAPPER}} nav.la-pagination ul li span' => 'padding: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} nav.la-pagination',
            ]
        );

        $this->start_controls_tabs( 'pagination_style_tabs' );

        $this->start_controls_tab( 'pagination_style_normal',
            [
                'label' => __( 'Normal', 'kitify' ),
            ]
        );

        $this->add_control(
            'pagination_link_color',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color',
            [
                'label' => __( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'pagination_style_hover',
            [
                'label' => __( 'Hover', 'kitify' ),
            ]
        );

        $this->add_control(
            'pagination_link_color_hover',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color_hover',
            [
                'label' => __( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'pagination_style_active',
            [
                'label' => __( 'Active', 'kitify' ),
            ]
        );

        $this->add_control(
            'pagination_link_color_active',
            [
                'label' => __( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li span.current' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color_active',
            [
                'label' => __( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li span.current' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->register_carousel_arrows_dots_style_section( [ 'enable_carousel' => 'yes' ] );

    }

    protected function the_query(){
        return $this->_query;
    }

    protected function render() {
        $this->_context = 'render';

        $data_source = $this->get_settings_for_display('data_source');
        if($data_source == 'custom'){
            $this->_open_wrap();
            include $this->_get_global_template( 'custom' );
            $this->_close_wrap();
        }
        else{
            $paged_key = 'member-page' . esc_attr($this->get_id());

            $page = absint( empty( $_GET[$paged_key] ) ? 1 : $_GET[$paged_key] );

            $query_args = [
                'posts_per_page' => $this->get_settings_for_display('query_posts_per_page'),
                'paged' => 1,
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
    }

    public function _get_member_image( $image_item ) {



        $image_size = $this->get_settings_for_display('thumb_size');

        $item_settings = [];
        $item_settings['item_image'] = $image_item;
        $item_settings['item_image_size'] = $image_size;

        if(empty( $item_settings['item_image']['url'] )){
            return;
        }

        $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

        $class = 'kitify-team-member__image-instance wp-post-image';

        $img_html = str_replace('class="', 'class="' . $class . ' ', $img_html);

        return sprintf('<figure class="figure__object_fit kitify-team-member__image">%1$s</figure>', $img_html);
    }

    public function _get_member_social( $member_item ){

        $html = '';
        $icon_lists = self::get_labrandicon();
        $uid = isset($member_item['_id']) ? $member_item['_id'] : uniqid();
        for ($i = 1; $i <=5; $i++ ){
            $icon_key = 's_icon_' . $i;
            $link_key = 's_link_' . $i;
            $att_uid = 'member_'.$uid . '_social_' . $i;
            if(!empty($member_item[$icon_key])){
                $icon_value = $member_item[$icon_key];
                $icon_name = $icon_lists[$icon_value];
                if ( !empty($member_item[$link_key]) && ! empty( $member_item[$link_key]['url'] ) ) {
                    $this->add_link_attributes( $att_uid, $member_item[$link_key] );
                    $this->add_render_attribute( $att_uid, 'title', esc_attr($icon_name) );
                }
                $this->add_render_attribute( $att_uid, 'class', sprintf('social-%1$s %1$s', strtolower($icon_name)) );
                $html .= sprintf('<a %1$s><i class="%2$s"></i></a>', $this->get_render_attribute_string( $att_uid ), $icon_value);
            }
        }
        if(!empty($html)){
            $html = '<div class="item--social member-social">'.$html.'</div>';
        }
        return $html;
    }

}
