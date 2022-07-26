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
      'section_filter_field',
      array(
        'label' => __( 'Filters', 'kitify' ),
        'tab'   => Controls_Manager::TAB_CONTENT,
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
              '{{WRAPPER}} ' => '--kitify-carousel-item-top-space: {{TOP}}{{UNIT}}; --kitify-carousel-item-right-space: {{RIGHT}}{{UNIT}};--kitify-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-carousel-item-left-space: {{LEFT}}{{UNIT}};--kitify-gcol-top-space: {{TOP}}{{UNIT}}; --kitify-gcol-right-space: {{RIGHT}}{{UNIT}};--kitify-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-gcol-left-space: {{LEFT}}{{UNIT}};',
          ),
      )
  );
  $this->end_controls_section();

  $this->start_controls_section(
    'section_design_cat_content',
    array(
      'label' => __( 'Category Content', 'kitify' ),
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
        'prefix_class' => 'kitify-woo-cat--align-',
        'separator'    => 'after',
      )
    );

    $this->start_controls_tabs( 'cat_content_tabs_style' );

      $this->start_controls_tab(
        'cat_content_normal',
        array(
          'label' => __( 'Normal', 'kitify' ),
        )
      );

        $this->add_control(
          'cat_content_color',
          array(
            'label'     => __( 'Color', 'kitify' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
              '{{WRAPPER}} .kitify-woo-categories li.product .woocommerce-loop-category__title, {{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap .kitify-count' => 'color: {{VALUE}};',
            ),
          )
        );
        $this->add_control(
          'cat_content_background_color',
          array(
            'label'     => __( 'Background Color', 'kitify' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
              '{{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap' => 'background-color: {{VALUE}};',
            ),
          )
        );

      $this->end_controls_tab();

      $this->start_controls_tab(
        'cat_content_hover',
        array(
          'label' => __( 'Hover', 'kitify' ),
        )
      );

        $this->add_control(
          'cat_content_hover_color',
          array(
            'label'     => __( 'Text Hover Color', 'kitify' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
              '{{WRAPPER}} .kitify-woo-categories li.product-category > a:hover .woocommerce-loop-category__title, {{WRAPPER}} .kitify-woo-categories li.product-category > a:hover .kitify-category__title-wrap .kitify-count' => 'color: {{VALUE}};',
            ),
          )
        );

        $this->add_control(
          'cat_content_background_hover_color',
          array(
            'label'     => __( 'Background Hover Color', 'kitify' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
              '{{WRAPPER}} .kitify-woo-categories li.product-category > a:hover .kitify-category__title-wrap' => 'background-color: {{VALUE}};',
            ),
          )
        );

      $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->add_control(
      'cat_content_padding',
      array(
        'label'      => __( 'Padding', 'kitify' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px', 'em', '%' ),
        'default'    => array(
          'top'      => '10',
          'right'    => '',
          'bottom'   => '10',
          'left'     => '',
          'isLinked' => false,
        ),
        'selectors'  => array(
          '{{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        'separator'  => 'before',
      )
    );

    $this->add_control(
      'cat_content_typography',
      array(
        'label'     => __( 'Typography', 'kitify' ),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
      )
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      array(
        'name'     => 'cat_content_title_typography',
        'label'    => __( 'Title', 'kitify' ),
        'selector' => '{{WRAPPER}} .kitify-woo-categories li.product .woocommerce-loop-category__title',
      )
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      array(
        'name'      => 'cat_content_count_typography',
        'label'     => __( 'Count', 'kitify' ),
        'selector'  => '{{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap .kitify-count',
        'global'    => array(
          'default' => Global_Typography::TYPOGRAPHY_ACCENT,
        ),
        'separator' => 'after',
        'condition' => array(
          'products_layout_type' => 'grid',
          'cat_hide_count!'      => 'yes',
        ),
      )
    );

  $this->add_group_control(
    Group_Control_Typography::get_type(),
    array(
      'name'      => 'cat_slider_content_count_typography',
      'label'     => __( 'Count', 'kitify' ),
      'selector'  => '{{WRAPPER}} .kitify-woo-categories li.product .kitify-category__title-wrap .kitify-count',
      'global'    => array(
        'default' => Global_Typography::TYPOGRAPHY_ACCENT,
      ),
      'separator' => 'after',
      'condition' => array(
        'products_layout_type'  => 'slider',
        'cat_slide_hide_count!' => 'yes',
      ),
    )
  );

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

    $this->_open_wrap();
    include $this->_get_global_template( 'index' );
    $this->_close_wrap();
  }
}
