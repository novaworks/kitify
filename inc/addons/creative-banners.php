<?php
/**
 * Class: Kitify_Creative_Banners
 * Name: Creative Banners
 * Slug: kitify-creative-banners
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

class Kitify_Creative_Banners extends Kitify_Base {
  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/creative-banners.css'), ['kitify-base'], kitify()->get_version());
      wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/creative-banners.js'), ['kitify-base'], kitify()->get_version(), true);
      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends( $this->get_name() );
  }
  public function get_name() {
      return 'kitify-creative-banners';
  }

  protected function get_widget_title() {
      return esc_html__( 'Creative Banners', 'kitify' );
  }

  public function get_icon() {
      return 'kitify-icon-banner-list';
  }
  protected function register_controls() {
    $preset_type = apply_filters(
        'kitify/creative-banners/control/preset',
        array(
            'default' => esc_html__( 'Default', 'kitify' ),
        )
    );
    $datasource = apply_filters(
        'kitify/creative-banners/control/data-source',
        array(
            'product_cat' => __( 'Product Categories', 'kitify' ),
            'custom' => __( 'Custom Banners', 'kitify' ),
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
        'item_link',
        [
            'label' => __( 'Link', 'kitify' ),
            'type' => Controls_Manager::URL,
            'placeholder' => __( 'https://your-link.com', 'kitify' ),
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
                    'item_link'  => '#',
                ],
                [
                    'item_title' => __( 'Category #2', 'kitify' ),
                    'item_link'  => '#',
                ],
                [
                    'item_title' => __( 'Category #3', 'kitify' ),
                    'item_link'  => '#',
                ],
                [
                    'item_title' => __( 'Category #4', 'kitify' ),
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
    'section_design_cat_image',
    array(
      'label' => __( 'Image', 'kitify' ),
      'tab'   => Controls_Manager::TAB_STYLE,
    )
  );
  $this->add_responsive_control(
      'image_width',
      [
          'label' => __( 'Image Width', 'kitify' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
              'unit' => '%',
          ],
          'tablet_default' => [
              'unit' => '%',
          ],
          'mobile_default' => [
              'unit' => '%',
          ],
          'size_units' => [ '%', 'px', 'vw' ],
          'range' => [
              '%' => [
                  'min' => 1,
                  'max' => 100,
              ],
              'px' => [
                  'min' => 1,
                  'max' => 2000,
              ],
              'vw' => [
                  'min' => 1,
                  'max' => 100,
              ],
          ],
          'selectors' => [
              '{{WRAPPER}} .kitify-creative-banners .kitify-creative-banners__images' => 'max-width: {{SIZE}}{{UNIT}};',
          ],
      ]
  );
  $this->add_responsive_control(
      'image_heigth',
      [
          'label' => __( 'Image Height', 'kitify' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
              'unit' => '%',
          ],
          'tablet_default' => [
              'unit' => '%',
          ],
          'mobile_default' => [
              'unit' => '%',
          ],
          'size_units' => [ '%', 'px', 'vw' ],
          'range' => [
              '%' => [
                  'min' => 1,
                  'max' => 100,
              ],
              'px' => [
                  'min' => 1,
                  'max' => 2000,
              ],
              'vw' => [
                  'min' => 1,
                  'max' => 100,
              ],
          ],
          'selectors' => [
              '{{WRAPPER}} .kitify-creative-banners .kitify-creative-banners__images' => 'height: {{SIZE}}{{UNIT}};',
          ],
      ]
  );
  $this->add_responsive_control(
      'image_top_postion',
      [
          'label' => __( 'Image Top Postion (%)', 'kitify' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
              'unit' => '%',
          ],
          'tablet_default' => [
              'unit' => '%',
          ],
          'mobile_default' => [
              'unit' => '%',
          ],
          'size_units' => [ '%'],
          'range' => [
              '%' => [
                  'min' => 1,
                  'max' => 100,
              ],
          ],
          'selectors' => [
              '{{WRAPPER}} .kitify-creative-banners .kitify-creative-banners__images' => 'top: {{SIZE}}{{UNIT}};',
          ],
      ]
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
      'selector' => '{{WRAPPER}} .kitify-creative-banners__links .b-title',
    )
  );
  $this->add_control(
    'cat_content_title_color',
    array(
      'label'     => __( 'Color', 'kitify' ),
      'type'      => Controls_Manager::COLOR,
      'selectors' => array(
        '{{WRAPPER}} .kitify-creative-banners__links .b-title' => 'color: {{VALUE}};',
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
        '{{WRAPPER}} .kitify-creative-banners__links .b-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
      ),
    )
  );
  $this->add_responsive_control(
    'cat_content_title_padding',
    array(
      'label'      => __( 'Padding', 'kitify' ),
      'type'       => Controls_Manager::DIMENSIONS,
      'size_units' => array( 'px', 'em', '%' ),
      'selectors'  => array(
        '{{WRAPPER}} .kitify-creative-banners__links .b-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
          'selector'  => '{{WRAPPER}} .kitify-creative-banners__links li:before',
      ]
  );
  $this->add_control(
    'cat_content_count_color',
    array(
      'label'     => __( 'Color', 'kitify' ),
      'type'      => Controls_Manager::COLOR,
      'selectors'  => array(
        '{{WRAPPER}} .kitify-creative-banners__links li:before' => 'color: {{VALUE}};',
      ),
    )
  );
  $this->end_controls_section();
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
        include kitify()->plugin_path( 'templates/creative-banners/global/loop-cat-item.php' );
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
    }
  }
  public function _get_banner_image( $image_item ) {

      $item_settings = [];
      $item_settings['item_image'] = $image_item;

      if(empty( $item_settings['item_image']['url'] )){
          return;
      }

      $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );
      return $img_html;
  }
}
