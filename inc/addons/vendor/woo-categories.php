<?php
/**
 * Class: Kitify_Woo_Categories
 * Name: Products
 * Slug: kitify-woo-categories
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('WPINC')) {
    die;
}

class Kitify_Woo_Categories extends Kitify_Base {
  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/woo-categories.css'), ['kitify-base'], kitify()->get_version());
      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends( 'kitify-base' );
  }
  public function get_name() {
      return 'kitify-woo-categories';
  }

  public function get_categories() {
      return [ 'kitify-woocommerce' ];
  }

  protected function get_widget_title() {
      return esc_html__( 'Product Categories', 'kitify' );
  }

  public function get_icon() {
      return 'kitify-icon-post-terms';
  }
  protected function register_controls() {
    $preset_type = apply_filters(
        'kitify/woo-categories/control/preset',
        array(
            'default' => esc_html__( 'Default', 'kitify' ),
        )
    );
    $datasource = apply_filters(
        'kitify/woo-categories/control/data-source',
        array(
            'product_cat' => __( 'Product Categories', 'kitify' ),
            'custom' => __( 'Custom', 'kitify' ),
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
            'default'   => 'product_cat',
            'options'   => $datasource
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
            'dynamic' => array( 'active' => true )
        )
    );
    $repeater->add_control(
        'item_title',
        [
            'label' => __( 'Title', 'kitify' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Category #1', 'kitify' ),
            'dynamic' => array( 'active' => true ),
        ]
    );
    $repeater->add_control(
        'count_text',
        [
            'label' => __( 'Count text', 'kitify' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( '12 items', 'kitify' ),
            'dynamic' => array( 'active' => true ),
        ]
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
        'item_link',
        [
            'label' => __( 'Link', 'kitify' ),
            'type' => Controls_Manager::URL,
            'placeholder' => __( 'https://your-link.com', 'kitify' ),
        ]
    );
    $repeater->add_control(
        'item_link_text',
        [
            'label' => __( 'Text Link', 'kitify' ),
            'type' => Controls_Manager::TEXT,
            'dynamic' => array( 'active' => true ),
        ]
    );
    $this->add_control(
        'items',
        [
            'label' => __( 'Custom Category List', 'kitify' ),
            'type' => Controls_Manager::REPEATER,
            'show_label' => true,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ item_title }}}',
            'default' => [
                [
                    'item_title' => __( 'Category #1', 'kitify' ),
                    'count_text' => __( '12 items', 'kitify' ),
                    'item_link'  => '#',
                ],
                [
                    'item_title' => __( 'Category #2', 'kitify' ),
                    'count_text' => __( '12 items', 'kitify' ),
                    'item_link'  => '#',
                ],
                [
                    'item_title' => __( 'Category #3', 'kitify' ),
                    'count_text' => __( '12 items', 'kitify' ),
                    'item_link'  => '#',
                ],
                [
                    'item_title' => __( 'Category #4', 'kitify' ),
                    'count_text' => __( '12 items', 'kitify' ),
                    'item_link'  => '#',
                ]
            ],
            'condition' => [
                'data_source' => 'custom'
            ]
        ]
    );
    $this->end_controls_section();

    $this->start_controls_section(
      'section_general_field',
      array(
        'label' => __( 'General', 'kitify' ),
        'tab'   => Controls_Manager::TAB_CONTENT,
      )
    );
    $this->add_responsive_control(
        'columns',
        [
            'label' => esc_html__( 'Columns', 'kitify' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 10,
            'default' => 4,
            'render_type' => 'template'
        ]
    );
    $this->add_control(
      'cats_count',
      array(
        'label'     => __( 'Categories Count', 'kitify' ),
        'type'      => Controls_Manager::NUMBER,
        'default'   => '8',
        'condition' => [
            'data_source' => 'product_cat'
        ],
      )
    );
    $this->add_control(
      'cat_hide_count',
      array(
        'label'                => __( 'Hide Count', 'kitify' ),
        'type'                 => Controls_Manager::SWITCHER,
        'label_on'             => __( 'Yes', 'kitify' ),
        'label_off'            => __( 'No', 'kitify' ),
        'return_value'         => 'yes',
        'default'              => 'no',
        'selectors_dictionary' => array(
          'yes' => 'display: none',
        ),
        'condition' => [
            'data_source' => 'product_cat'
        ],
        'selectors'            => array(
          '{{WRAPPER}} .kitify-product-categories .kitify-product-categories__item .cat-count' => '{{VALUE}}',
        ),
      )
    );
    $this->_add_control(
        'cat_count_label',
        array(
            'label' => esc_html__('Count Label', 'kitify'),
            'type' => Controls_Manager::TEXT,
            'separator' => 'before',
            'label_block' => true,
            'condition' => [
                'data_source' => 'product_cat'
            ]
        )
    );
    $this->add_control(
        'preset',
        array(
            'label'   => esc_html__( 'Preset', 'kitify' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'default',
            'options' => $preset_type,
            'separator' => 'before',
        )
    );
    $this->end_controls_section();
    $this->start_controls_section(
        'section_button',
        array(
            'label' => esc_html__( 'Button', 'kitify' ),
            'condition' => [
                'data_source' => 'custom'
            ]
        )
    );
    $this->_add_advanced_icon_control(
        'button_icon',
        array(
            'label'       => esc_html__( 'Icon', 'kitify' ),
            'type'        => Controls_Manager::ICON,
            'label_block' => false,
            'skin'        => 'inline',
            'file'        => '',
        )
    );
    $this->end_controls_section();

    $this->start_controls_section(
      'section_filter_field',
      array(
        'label' => __( 'Filters', 'kitify' ),
        'tab'   => Controls_Manager::TAB_CONTENT,
        'condition' => [
            'data_source' => 'product_cat'
        ]
      )
    );
    $this->add_control(
      'category_filter_rule',
      array(
        'label'   => __( 'Category Filter Rule', 'kitify' ),
        'type'    => Controls_Manager::SELECT,
        'default' => 'all',
        'options' => array(
          'all'     => __( 'Show All', 'kitify' ),
          'top'     => __( 'Only Top Level', 'kitify' ),
          'include' => __( 'Match These Categories', 'kitify' ),
          'exclude' => __( 'Exclude These Categories', 'kitify' ),
        ),
      )
    );
    $this->add_control(
      'category_filter',
      array(
        'label'       => __( 'Category Filter', 'kitify' ),
        'type'        => Controls_Manager::SELECT2,
        'multiple'    => true,
        'label_block' => true,
        'default'     => '',
        'options'     => $this->get_product_categories(),
        'condition'   => array(
          'category_filter_rule' => array( 'include', 'exclude' ),
        ),
      )
    );
    $this->add_control(
      'display_empty_cat',
      array(
        'label'        => __( 'Display Empty Categories', 'kitify' ),
        'type'         => Controls_Manager::SWITCHER,
        'default'      => '',
        'label_on'     => 'Yes',
        'label_off'    => 'No',
        'return_value' => 'yes',
      )
    );
    $this->add_control(
      'orderby',
      array(
        'label'   => __( 'Order by', 'kitify' ),
        'type'    => Controls_Manager::SELECT,
        'default' => 'name',
        'options' => array(
          'name'       => __( 'Name', 'kitify' ),
          'slug'       => __( 'Slug', 'kitify' ),
          'desc'       => __( 'Description', 'kitify' ),
          'count'      => __( 'Count', 'kitify' ),
          'menu_order' => __( 'Menu Order', 'kitify' ),
        ),
      )
    );

    $this->add_control(
      'order',
      array(
        'label'   => __( 'Order', 'kitify' ),
        'type'    => Controls_Manager::SELECT,
        'default' => 'desc',
        'options' => array(
          'desc' => __( 'Descending', 'kitify' ),
          'asc'  => __( 'Ascending', 'kitify' ),
        ),
      )
    );
    $this->end_controls_section();
    $this->register_carousel_section( [], 'columns');
    $this->register_style_category_controls();

  }
  /**
 * Register Category Content Controls.
 *
 * @since 0.0.1
 * @access protected
 */
protected function register_style_category_controls() {
  $this->start_controls_section(
      'section_cat_style',
      [
          'label' => esc_html__( 'Column', 'kitify' ),
          'tab' => Controls_Manager::TAB_STYLE,
      ]
  );
  $this->add_responsive_control(
      'column_padding',
      array(
          'label'       => esc_html__( 'Column Padding', 'kitify' ),
          'type'        => Controls_Manager::DIMENSIONS,
          'size_units'  => array( 'px' ),
          'selectors'   => array(
              '{{WRAPPER}} .kitify-product-categories__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
              '{{WRAPPER}} .kitify-custom-categories__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ),
      )
  );
  $this->end_controls_section();
  $this->start_controls_section(
    'section_design_cat_box',
    array(
      'label' => __( 'Box', 'kitify' ),
      'tab'   => Controls_Manager::TAB_STYLE,
    )
  );
  $this->add_control(
    'cat_content_alignment',
    array(
      'label'        => __( 'Alignment', 'kitify' ),
      'type'         => Controls_Manager::CHOOSE,
      'label_block'  => false,
      'options' => array(
          'flex-start' => array(
              'title' => esc_html__( 'Start', 'kitify' ),
              'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
          ),
          'center' => array(
              'title' => esc_html__( 'Center', 'kitify' ),
              'icon'  => 'eicon-h-align-center',
          ),
          'flex-end' => array(
              'title' => esc_html__( 'End', 'kitify' ),
              'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
          ),
      ),
      'default'      => 'center',
      'selectors' => array(
        '{{WRAPPER}} .kitify-custom-categories__content-wrap' => 'align-items: {{VALUE}};',
      ),
    )
  );
  $this->add_responsive_control(
    'cat_content_padding',
    array(
      'label'      => __( 'Padding', 'kitify' ),
      'type'       => Controls_Manager::DIMENSIONS,
      'size_units' => array( 'px', 'em', '%' ),
      'selectors'  => array(
        '{{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        '{{WRAPPER}} .kitify-custom-categories__content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ),
    )
  );
  $this->end_controls_section();
  $this->start_controls_section(
    'section_design_cat_image',
    array(
      'label' => __( 'Image', 'kitify' ),
      'tab'   => Controls_Manager::TAB_STYLE,
    )
  );
  $this->add_responsive_control(
      'cat_image_border_radius',
      array(
          'label'      => __( 'Border Radius', 'kitify' ),
          'type'       => Controls_Manager::DIMENSIONS,
          'size_units' => array( 'px', '%' ),
          'selectors'  => array(
              '{{WRAPPER}} .kitify-product-categories .kitify-custom-categories__item .kitify-custom-categories__image-wrap:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
              '{{WRAPPER}} .kitify-product-categories .kitify-custom-categories__item .kitify-custom-categories__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ),
      )
  );
  $this->end_controls_section();
  $this->start_controls_section(
    'section_design_cat_title',
    array(
      'label' => __( 'Title', 'kitify' ),
      'tab'   => Controls_Manager::TAB_STYLE,
    )
  );

  $this->add_control(
    'category_name_tag',
    array(
      'label'   => __( 'HTML Tag', 'kitify' ),
      'type'    => Controls_Manager::SELECT,
      'options' => array(
        'h1' => __( 'H1', 'kitify' ),
        'h2' => __( 'H2', 'kitify' ),
        'h3' => __( 'H3', 'kitify' ),
        'h4' => __( 'H4', 'kitify' ),
        'h5' => __( 'H5', 'kitify' ),
        'h6' => __( 'H6', 'kitify' ),
      ),
      'default' => 'h2',
    )
  );
  $this->add_group_control(
    Group_Control_Typography::get_type(),
    array(
      'name'     => 'cat_content_title_typography',
      'label'    => __( 'Title', 'kitify' ),
      'selector' => '{{WRAPPER}} .kitify-woo-categories li.product .woocommerce-loop-category__title,{{WRAPPER}} .kitify-custom-categories__item-inner .kitify-custom-categories__content-wrap .kitify-custom-categories__title',
    )
  );
  $this->add_control(
    'cat_content_title_color',
    array(
      'label'     => __( 'Color', 'kitify' ),
      'type'      => Controls_Manager::COLOR,
      'selectors' => array(
        '{{WRAPPER}} .kitify-woo-categories li.product .woocommerce-loop-category__title,{{WRAPPER}} .kitify-custom-categories__item-inner .kitify-custom-categories__content-wrap .kitify-custom-categories__title' => 'color: {{VALUE}};',
      ),
    )
  );
  $this->add_responsive_control(
    'cat_content_title_margin',
    array(
      'label'      => __( 'Margin', 'kitify' ),
      'type'       => Controls_Manager::DIMENSIONS,
      'size_units' => array( 'px', 'em', '%' ),
      'selectors'  => array(
        '{{WRAPPER}} .kitify-woo-categories li.product .woocommerce-loop-category__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        '{{WRAPPER}} .kitify-custom-categories__item-inner .kitify-custom-categories__content-wrap .kitify-custom-categories__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ),
    )
  );
  $this->end_controls_section();
  $this->start_controls_section(
    'section_design_cat_count',
    array(
      'label' => __( 'Count text', 'kitify' ),
      'tab'   => Controls_Manager::TAB_STYLE,
    )
  );
  $this->_add_group_control(
      Group_Control_Typography::get_type(),
      [
          'name' => 'count_typography',
          'selector'  => '{{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap .kitify-count,{{WRAPPER}} .kitify-custom-categories__count',
      ]
  );
  $this->add_control(
    'cat_content_count_color',
    array(
      'label'     => __( 'Color', 'kitify' ),
      'type'      => Controls_Manager::COLOR,
      'selectors'  => array(
        '{{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap .kitify-count' => 'color: {{VALUE}};',
        '{{WRAPPER}} .kitify-custom-categories__count' => 'color: {{VALUE}};',
      ),
    )
  );
  $this->add_responsive_control(
    'cat_content_count_padding',
    array(
      'label'      => __( 'Padding', 'kitify' ),
      'type'       => Controls_Manager::DIMENSIONS,
      'size_units' => array( 'px', 'em', '%' ),
      'selectors'  => array(
        '{{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap .kitify-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        '{{WRAPPER}} .kitify-custom-categories__count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ),
    )
  );
  $this->end_controls_section();
  $this->start_controls_section(
    'section_design_cat_desc',
    array(
      'label' => __( 'Description', 'kitify' ),
      'tab'   => Controls_Manager::TAB_STYLE,
    )
  );
  $this->add_group_control(
    Group_Control_Typography::get_type(),
    array(
      'name'     => 'cat_content_desc_typography',
      'label'    => __( 'Title', 'kitify' ),
      'selector' => '{{WRAPPER}} .kitify-woo-categories li.product .woocommerce-loop-category__desc,{{WRAPPER}} .kitify-custom-categories__item-inner .kitify-custom-categories__content-wrap .kitify-custom-categories__desc',
    )
  );
  $this->add_control(
    'cat_content_desc_color',
    array(
      'label'     => __( 'Color', 'kitify' ),
      'type'      => Controls_Manager::COLOR,
      'selectors' => array(
        '{{WRAPPER}} .kitify-woo-categories li.product .woocommerce-loop-category__desc,{{WRAPPER}} .kitify-custom-categories__item-inner .kitify-custom-categories__content-wrap .kitify-custom-categories__desc' => 'color: {{VALUE}};',
      ),
    )
  );
  $this->add_responsive_control(
    'cat_content_desc_margin',
    array(
      'label'      => __( 'Margin', 'kitify' ),
      'type'       => Controls_Manager::DIMENSIONS,
      'size_units' => array( 'px', 'em', '%' ),
      'selectors'  => array(
        '{{WRAPPER}} .kitify-woo-categories li.product .woocommerce-loop-category__desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        '{{WRAPPER}} .kitify-custom-categories__item-inner .kitify-custom-categories__content-wrap .kitify-custom-categories__desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ),
    )
  );
  $this->end_controls_section();
  $this->start_controls_section(
    'section_design_cat_button',
    array(
      'label' => __( 'Button', 'kitify' ),
      'tab'   => Controls_Manager::TAB_STYLE,
    )
  );
  $this->add_control(
    'cat_content_button_alignment',
    array(
      'label'        => __( 'Alignment', 'kitify' ),
      'type'         => Controls_Manager::CHOOSE,
      'label_block'  => false,
      'options' => array(
          'flex-start' => array(
              'title' => esc_html__( 'Start', 'kitify' ),
              'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
          ),
          'center' => array(
              'title' => esc_html__( 'Center', 'kitify' ),
              'icon'  => 'eicon-h-align-center',
          ),
          'flex-end' => array(
              'title' => esc_html__( 'End', 'kitify' ),
              'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
          ),
      ),
      'default'      => 'center',
      'selectors' => array(
        '{{WRAPPER}} .kitify-custom-categories__item-inner .kitify-custom-categories__button-wrap' => 'align-items: {{VALUE}};',
      ),
    )
  );
  $this->add_responsive_control(
    'cat_button_spacing',
    array(
      'label'      => __( 'Wrap Padding', 'kitify' ),
      'type'       => Controls_Manager::DIMENSIONS,
      'size_units' => array( 'px', 'em', '%' ),
      'selectors'  => array(
        '{{WRAPPER}} .kitify-custom-categories__item-inner .kitify-custom-categories__button-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ),
      'separator'    => 'after',
    )
  );
  $this->_add_control(
      'button_icon_align',
      [
          'label' => __( 'Icon Position', 'kitify' ),
          'type' => Controls_Manager::SELECT,
          'default' => 'left',
          'options' => [
              'left' => __( 'Before', 'kitify' ),
              'right' => __( 'After', 'kitify' ),
          ],
          'prefix_class' => 'icon-align-',
      ]
  );
  $this->_add_responsive_control(
      'button_icon_size',
      [
          'label' => __( 'Icon Size', 'kitify' ),
          'type' => Controls_Manager::SLIDER,
          'size_units' => [ 'px', '%', 'em' ],
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button .kitify-custom-categories__button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
          ],
      ]
  );
  $this->_add_responsive_control(
      'button_icon_indent',
      [
          'label' => __( 'Icon Spacing', 'kitify' ),
          'type' => Controls_Manager::DIMENSIONS,
          'size_units' => [ 'px', '%', 'em' ],
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button .kitify-custom-categories__button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
          'separator'    => 'after',
      ]
  );
  $this->_add_responsive_control(
      'button_text_padding',
      [
          'label' => __( 'Padding', 'kitify' ),
          'type' => Controls_Manager::DIMENSIONS,
          'size_units' => [ 'px', 'em', '%' ],
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
      ]
  );
  $this->_add_group_control(
      Group_Control_Typography::get_type(),
      [
          'name' => 'button_typography',
          'selector' => '{{WRAPPER}} a.kitify-custom-categories__button',
      ]
  );
  $this->_start_controls_tabs( 'tabs_button_style' );
  $this->_start_controls_tab(
      'tab_button_normal',
      [
          'label' => __( 'Normal', 'kitify' ),
      ]
  );
  $this->_add_group_control(
      Group_Control_Background::get_type(),
      [
          'name' => 'background',
          'label' => __( 'Background', 'kitify' ),
          'types' => [ 'classic', 'gradient' ],
          'exclude' => [ 'image' ],
          'selector' => '{{WRAPPER}} a.kitify-custom-categories__button',
          'fields_options' => [
              'background' => [
                  'default' => 'classic',
              ]
          ],
          'separator'    => 'after',
      ]
  );
  $this->_add_control(
      'button_text_color',
      [
          'label' => __( 'Text Color', 'kitify' ),
          'type' => Controls_Manager::COLOR,
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button' => 'color: {{VALUE}};',
          ],
      ]
  );
  $this->_add_control(
      'button_icon_color',
      [
          'label' => __( 'Icon Color', 'kitify' ),
          'type' => Controls_Manager::COLOR,
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button .kitify-custom-categories__button-icon' => 'color: {{VALUE}};',
          ],
      ]
  );
  $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
          'name' => 'button_border',
          'selector' => '{{WRAPPER}} a.kitify-custom-categories__button',
          'separator' => 'before',
      ]
  );

  $this->_add_control(
      'button_border_radius',
      [
          'label' => __( 'Border Radius', 'kitify' ),
          'type' => Controls_Manager::DIMENSIONS,
          'size_units' => [ 'px', '%', 'em' ],
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
      ]
  );
  $this->_add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
          'name' => 'button_shadow',
          'selector' => '{{WRAPPER}} a.kitify-custom-categories__button',
      ]
  );
  $this->_end_controls_tab();

  $this->_start_controls_tab(
      'tab_button_hover',
      [
          'label' => __( 'Hover', 'kitify' ),
      ]
  );
  $this->_add_group_control(
      Group_Control_Background::get_type(),
      [
          'name' => 'background_hover',
          'label' => __( 'Background', 'kitify' ),
          'types' => [ 'classic', 'gradient' ],
          'exclude' => [ 'image' ],
          'selector' => '{{WRAPPER}} a.kitify-custom-categories__button:hover',
          'fields_options' => [
              'background' => [
                  'default' => 'classic',
              ]
          ],
          'separator'    => 'after',
      ]
  );
  $this->_add_control(
      'button_text_color_hover',
      [
          'label' => __( 'Text Color', 'kitify' ),
          'type' => Controls_Manager::COLOR,
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button:hover' => 'color: {{VALUE}};',
          ],
      ]
  );
  $this->_add_control(
      'button_icon_color_hover',
      [
          'label' => __( 'Icon Color', 'kitify' ),
          'type' => Controls_Manager::COLOR,
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button:hover .kitify-custom-categories__button-icon' => 'color: {{VALUE}};',
          ],
      ]
  );
  $this->_add_group_control(
      Group_Control_Border::get_type(),
      [
          'name' => 'button_border_hover',
          'selector' => '{{WRAPPER}} a.kitify-custom-categories__button:hover',
          'separator' => 'before',
      ]
  );

  $this->_add_control(
      'button_border_radius_hover',
      [
          'label' => __( 'Border Radius', 'kitify' ),
          'type' => Controls_Manager::DIMENSIONS,
          'size_units' => [ 'px', '%', 'em' ],
          'selectors' => [
              '{{WRAPPER}} a.kitify-custom-categories__button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
      ]
  );
  $this->_add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
          'name' => 'button_shadow_hover',
          'selector' => '{{WRAPPER}} a.kitify-custom-categories__button:hover',
      ]
  );
  $this->_end_controls_tab();

  $this->_end_controls_tabs();
  $this->end_controls_section();
  $this->register_carousel_arrows_dots_style_section( [ 'enable_carousel' => 'yes' ] );
}
  /**
   * Get WooCommerce Product Categories.
   *
   * @since 1.0.0
   * @access protected
   */
  protected function get_product_categories() {

    $product_cat = array();

    $cat_args = array(
      'orderby'    => 'name',
      'order'      => 'asc',
      'hide_empty' => false,
    );

    $product_categories = get_terms( 'product_cat', $cat_args );

    if ( ! empty( $product_categories ) ) {

      foreach ( $product_categories as $key => $category ) {

        $product_cat[ $category->term_id ] = $category->name;
      }
    }

    return $product_cat;
  }
  /**
	 * List all product categories.
	 *
	 * @return string
	 */
	public function query_product_categories() {

		$settings    = $this->get_settings();
		$include_ids = array();
		$exclude_ids = array();
		$woo_cat_slider;

    $woo_cat_slider = $settings['cats_count'];

		$atts = array(
			'limit'   => ( $woo_cat_slider ) ? $woo_cat_slider : '-1',
			'columns' => ( $settings['columns'] ) ? $settings['columns'] : '4',
			'parent'  => '',
		);

		if ( 'top' === $settings['category_filter_rule'] ) {
			$atts['parent'] = 0;
		} elseif ( 'include' === $settings['category_filter_rule'] && is_array( $settings['category_filter'] ) ) {
			$include_ids = array_filter( array_map( 'trim', $settings['category_filter'] ) );
		} elseif ( 'exclude' === $settings['category_filter_rule'] && is_array( $settings['category_filter'] ) ) {
			$exclude_ids = array_filter( array_map( 'trim', $settings['category_filter'] ) );
		}

		$hide_empty = ( 'yes' === $settings['display_empty_cat'] ) ? 0 : 1;

		// Get terms and workaround WP bug with parents/pad counts.
		$args = array(
			'orderby'    => ( $settings['orderby'] ) ? $settings['orderby'] : 'name',
			'order'      => ( $settings['order'] ) ? $settings['order'] : 'ASC',
			'hide_empty' => $hide_empty,
			'pad_counts' => true,
			'child_of'   => $atts['parent'],
			'include'    => $include_ids,
			'exclude'    => $exclude_ids,
		);
    ob_start();
		$product_categories = get_terms( 'product_cat', $args );

		if ( '' !== $atts['parent'] ) {
			$product_categories = wp_list_filter(
				$product_categories,
				array(
					'parent' => $atts['parent'],
				)
			);
		}

		if ( $hide_empty ) {
			foreach ( $product_categories as $key => $category ) {
				if ( 0 === $category->count ) {
					unset( $product_categories[ $key ] );
				}
			}
		}

		$atts['limit'] = intval( $atts['limit'] );

		if ( $atts['limit'] > 0 ) {
			$product_categories = array_slice( $product_categories, 0, $atts['limit'] );
		}

		if ( $product_categories ) {
			foreach ( $product_categories as $category ) {
        include kitify()->plugin_path( 'templates/woo-categories/global/loop-cat-item.php' );
			}

		}

    $inner_content = ob_get_clean();

		return $inner_content;
	}

  protected function render() {
    $this->_context = 'render';
    $data_source = $this->get_settings_for_display('data_source');
    if($data_source == 'custom'){
        $this->_open_wrap();
        include $this->_get_global_template( 'custom' );
        $this->_close_wrap();
    }else {
      $this->_open_wrap();
      include $this->_get_global_template( 'index' );
      $this->_close_wrap();
    }
  }
  public function _get_cat_image( $image_item ) {

      $item_settings = [];
      $item_settings['item_image'] = $image_item;

      if(empty( $item_settings['item_image']['url'] )){
          return;
      }

      $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

      $class = 'kitify-custom-categories__image-instance wp-post-image';

      $img_html = str_replace('class="', 'class="' . $class . ' ', $img_html);

      return sprintf('<figure class="figure__object_fit kitify-custom-categories__image">%1$s</figure>', $img_html);
  }
}
