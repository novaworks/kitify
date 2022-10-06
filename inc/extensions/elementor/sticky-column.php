<?php

namespace KitifyExtensions\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Controls_Manager;

class Sticky_Column {

    public $columns_data = array();
    public $toggle_columns = array();
    public $sticky_columns = array();
    private $has_sticky = false;

    public function __construct() {

        add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'after_column_section_layout' ), 10, 2 );
        add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'after_column_section_layout' ), 10, 2 );
        add_action( 'elementor/frontend/column/before_render', array( $this, 'column_before_render' ) );
        add_action( 'elementor/frontend/container/before_render', array( $this, 'column_before_render' ) );
        add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
    }
    /**
     * After column_layout callback
     *
     * @param  object $obj
     * @param  array $args
     * @return void
     */
    public function after_column_section_layout( $stack, $args ) {

      if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints')) {
        $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
        $breakpoints_list   = array();

        foreach ($active_breakpoints as $key => $value) {
          $breakpoints_list[$key] = $value->get_label();
        }

        $breakpoints_list['desktop'] = 'Desktop';
        $breakpoints_list            = array_reverse($breakpoints_list);
      } else {
        $breakpoints_list = array(
          'desktop' => 'Desktop',
          'laptop'  => 'Laptop',
          'tablet'  => 'Tablet',
          'mobile'  => 'Mobile',
        );
      }

      $stack->start_controls_section(
        'column_kitify',
        array(
          'label' => esc_html__( 'Kitify Column', 'kitify' ),
          'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
        )
      );

      $stack->add_control(
        'kitify_column_sticky',
        array(
          'label'        => esc_html__( 'Sticky Column', 'kitify' ),
          'type'         => \Elementor\Controls_Manager::SWITCHER,
          'label_on'     => esc_html__( 'Yes', 'kitify' ),
          'label_off'    => esc_html__( 'No', 'kitify' ),
          'return_value' => 'true',
          'default'      => 'false',
        )
      );

      $stack->add_control(
        'kitify_top_spacing',
        array(
          'label'   => esc_html__( 'Top Spacing', 'kitify' ),
          'type'    => \Elementor\Controls_Manager::NUMBER,
          'default' => 50,
          'min'     => 0,
          'max'     => 500,
          'step'    => 1,
          'condition' => array(
            'kitify_column_sticky' => 'true',
          ),
        )
      );

      $stack->add_control(
        'kitify_bottom_spacing',
        array(
          'label'   => esc_html__( 'Bottom Spacing', 'kitify' ),
          'type'    => \Elementor\Controls_Manager::NUMBER,
          'default' => 50,
          'min'     => 0,
          'max'     => 500,
          'step'    => 1,
          'condition' => array(
            'kitify_column_sticky' => 'true',
          ),
        )
      );

      $stack->add_control(
        'kitify_column_sticky_on',
        array(
          'label'    => __( 'Sticky On', 'kitify' ),
          'type'     => \Elementor\Controls_Manager::SELECT2,
          'multiple' => true,
          'label_block' => 'true',
          'default' => array(
            'desktop',
            'laptop',
          ),
          'options' => $breakpoints_list,
          'condition' => array(
            'kitify_column_sticky' => 'true',
          ),
          'render_type'        => 'none',
        )
      );

      $stack->end_controls_section();
    }
    /**
     * [column_before_render description]
     * @param  [type] $element [description]
     * @return [type]          [description]
     */
    public function column_before_render( $element ) {
      $data     = $element->get_data();
      $settings = $data['settings'];

      if ( isset( $settings['kitify_column_sticky'] ) ) {
        $active_breakpoints = kitify_helper()->get_active_breakpoints();
        $column_settings = array(
          'id'            => $data['id'],
          'sticky'        => filter_var( $settings['kitify_column_sticky'], FILTER_VALIDATE_BOOLEAN ),
          'topSpacing'    => isset( $settings['kitify_top_spacing'] ) ? $settings['kitify_top_spacing'] : 50,
          'bottomSpacing' => isset( $settings['kitify_bottom_spacing'] ) ? $settings['kitify_bottom_spacing'] : 50,
          'stickyOn'      => isset( $settings['kitify_column_sticky_on'] ) ? $settings['kitify_column_sticky_on'] : array( 'desktop', 'laptop', 'tablet' ),
        );

        if ( filter_var( $settings['kitify_column_sticky'], FILTER_VALIDATE_BOOLEAN ) ) {

          $element->add_render_attribute( '_wrapper', array(
            'class'         => 'kitify-sticky-column',
            'data-kitify-settings' => json_encode( $column_settings ),
          ) );

          $this->sticky_columns[] = $data['id'];
        }

        $this->columns_data[ $data['id'] ] = $column_settings;
      }
    }
    /**
     * [enqueue_scripts description]
     *
     * @return void
     */
    public function enqueue_scripts() {

      if ( ! empty( $this->sticky_columns ) ) {
        wp_enqueue_script(
          'kitify-resize-sensor',
          kitify()->plugin_url( 'assets/js/lib/resize-sensor/ResizeSensor.min.js' ),
          array( 'jquery' ),
          '1.7.0',
          true
        );

        wp_enqueue_script(
          'kitify-sticky-sidebar',
          kitify()->plugin_url( 'assets/js/lib/sticky-sidebar/sticky-sidebar.min.js' ),
          array( 'jquery', 'kitify-resize-sensor', 'imagesloaded' ),
          '3.3.1',
          true
        );
        wp_enqueue_script(
          'kitify-stickycolumn-frontend',
          kitify()->plugin_url( 'assets/js/addons/sticky-column.js' ),
          array('kitify-sticky-sidebar' ),
          kitify()->get_version(),
          true
        );
        wp_localize_script( 'kitify-stickycolumn-frontend', 'StickyColumnSettings', array(
          'elements_data' => $this->columns_data,
        ) );
      }
      if ( ! empty( $this->toggle_columns ) ) {
        wp_enqueue_script(
          'kitify-tooggle-columns-frontend',
          kitify()->plugin_url( 'assets/js/addons/toggle-column.js' ),
          array('elementor-frontend' ),
          kitify()->get_version(),
          true
        );
      }
    }
}
