<?php
/**
 * Class: Kitify_Woo_Categories
 * Name: Products
 * Slug: kitify-woo-categories
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

class Kitify_Woo_Categories extends Kitify_Base {
  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/woo-categories.css'), ['kitify-base'], kitify()->get_version());
      $this->add_style_depends( $this->get_name() );
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
      return 'eicon-product-categories';
  }
  protected function register_controls() {
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
          '{{WRAPPER}} .kitify-woo-categories .kitify-category__title-wrap .kitify-count' => '{{VALUE}}',
        ),
      )
    );
    $this->add_control(
      'cat_title_position',
      array(
        'label'        => __( 'Title/Count Position', 'kitify' ),
        'type'         => Controls_Manager::SELECT,
        'default'      => 'default',
        'options'      => array(
          'default'     => __( 'Default', 'kitify' ),
          'below-image' => __( 'Below Image', 'kitify' ),
        ),
        'prefix_class' => 'kitify-woo-cat-title-pos-',
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
      'display_cat_desc',
      array(
        'label'        => __( 'Display Category Description', 'kitify' ),
        'type'         => Controls_Manager::SWITCHER,
        'default'      => '',
        'label_on'     => 'Yes',
        'label_off'    => 'No',
        'return_value' => 'yes',
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

				$this->_load_template( $this->_get_global_template( 'loop-cat-item' ) );
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
