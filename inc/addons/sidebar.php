<?php
/**
 * Class: Kitify_Sidebar
 * Name: Sidebar
 * Slug: kitify-sidebar
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

class Kitify_Sidebar extends Kitify_Base {

  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/toggle-sidebar.css'),[], kitify()->get_version());
      wp_register_script(
        $this->get_name(),
        kitify()->plugin_url( 'assets/js/addons/toggle-sidebar.js' ),
        array('elementor-frontend' ),
        kitify()->get_version(),
        true
      );
      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends( $this->get_name() );
  }
  /**
   * Get widget name.
   *
   * Retrieve sidebar widget name.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget name.
   */
  public function get_name() {
    return 'kitify-sidebar';
  }
  /**
   * Get widget title.
   *
   * Retrieve sidebar widget title.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget title.
   */
  public function get_title() {
    return esc_html__( 'Kitify Sidebar', 'kitify' );
  }
  /**
  	 * Get widget icon.
  	 *
  	 * Retrieve sidebar widget icon.
  	 *
  	 * @since 1.0.0
  	 * @access public
  	 *
  	 * @return string Widget icon.
  	 */
  	public function get_icon() {
  		return 'kitify-icon-sidebar';
  	}

  	/**
  	 * Get widget keywords.
  	 *
  	 * Retrieve the list of keywords the widget belongs to.
  	 *
  	 * @since 2.1.0
  	 * @access public
  	 *
  	 * @return array Widget keywords.
  	 */
  	public function get_keywords() {
  		return [ 'sidebar', 'widget' ];
  	}

  	/**
  	 * Register sidebar widget controls.
  	 *
  	 * Adds different input fields to allow the user to change and customize the widget settings.
  	 *
  	 * @since 3.1.0
  	 * @access protected
  	 */
  	protected function register_controls() {
  		global $wp_registered_sidebars;

  		$options = [];

      $sidebar_style = apply_filters(
          'kitify/sidebar/style/sidebar_style',
          array(
              '1' => esc_html__( 'Default', 'kitify' )
          )
      );

  		if ( ! $wp_registered_sidebars ) {
  			$options[''] = esc_html__( 'No sidebars were found', 'kitify' );
  		} else {
  			$options[''] = esc_html__( 'Choose Sidebar', 'kitify' );

  			foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
  				$options[ $sidebar_id ] = $sidebar['name'];
  			}
  		}

  		$default_key = array_keys( $options );
  		$default_key = array_shift( $default_key );

  		$this->start_controls_section(
  			'section_sidebar',
  			[
  				'label' => esc_html__( 'Sidebar', 'kitify' ),
  			]
  		);

  		$this->add_control(
  			'sidebar',
  			[
  				'label' => esc_html__( 'Choose Sidebar', 'kitify' ),
  				'type' => Controls_Manager::SELECT,
  				'default' => $default_key,
  				'options' => $options,
  			]
  		);
      $this->add_control(
        'hr',
        array(
          'type' => \Elementor\Controls_Manager::DIVIDER,
        )
      );
      $this->add_control(
        'sidebar_toggle',
        array(
          'label'        => esc_html__( 'Sidebar Toggle', 'kitify' ),
          'type'         => \Elementor\Controls_Manager::SWITCHER,
          'label_on'     => esc_html__( 'Yes', 'kitify' ),
          'label_off'    => esc_html__( 'No', 'kitify' ),
          'return_value' => 'true',
          'default'      => 'false',
        )
      );
      $this->add_control(
        'sidebar_toggle_on',
        array(
          'label'        => esc_html__( 'Toggle on custom breakpoint', 'kitify' ),
          'type'         => \Elementor\Controls_Manager::SWITCHER,
          'label_on'     => esc_html__( 'Yes', 'kitify' ),
          'label_off'    => esc_html__( 'No', 'kitify' ),
          'return_value' => 'true',
          'default'      => 'true',
          'condition' => array(
            'sidebar_toggle' => 'true',
          ),
        )
      );

      $this->add_control(
          'sidebar_toggle_breakpoint',
          array(
              'label' => esc_html__( 'Breakpoint', 'kitify' ),
              'type'  => \Elementor\Controls_Manager::SELECT,
              'default' => 'tablet',
              'options' => kitify_helper()->get_active_breakpoints(false, true),
              'condition' => array(
                'sidebar_toggle_on' => 'true',
                'sidebar_toggle' => 'true',
              ),
          )
      );
      $this->end_controls_section();

      $css_scheme = apply_filters(
        'kitify/sidebar/css-scheme',
        array(
          'widget_title'         => '.widget .widget-title',
        )
      );
      $this->_start_controls_section(
          'section_sidebar_layout',
          array(
              'label'      => esc_html__( 'Sidebar layout', 'kitify' ),
              'tab'        => Controls_Manager::TAB_STYLE,
              'show_label' => false,
          )
      );
      $this->add_control(
          'sidebar_style',
          array(
              'label'     => esc_html__( 'Sidebar Layout', 'kitify' ),
              'type'      => Controls_Manager::SELECT,
              'default'   => '1',
              'options'   => $sidebar_style
          )
      );
      $this->end_controls_section();

      $this->_start_controls_section(
          'section_widget_title_style',
          array(
              'label'      => esc_html__( 'Widget Title', 'kitify' ),
              'tab'        => Controls_Manager::TAB_STYLE,
              'show_label' => false,
          )
      );
      $this->_add_control(
        'widget_title_color',
        array(
          'label'     => esc_html__( 'Title Color', 'kitify' ),
          'type'      => Controls_Manager::COLOR,
          'selectors' => array(
            '{{WRAPPER}} ' . $css_scheme['widget_title'] => 'color: {{VALUE}}',
          ),
        ),
        10
      );
      $this->_add_group_control(
          Group_Control_Typography::get_type(),
          array(
              'name'     => 'widget_title_typography',
              'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
              'selector' => '{{WRAPPER}}  ' . $css_scheme['widget_title'],
              'separator' => 'before',
          ),
          20
      );

  		$this->end_controls_section();
  	}

  	/**
  	 * Render sidebar widget output on the frontend.
  	 *
  	 * Written in PHP and used to generate the final HTML.
  	 *
  	 * @since 1.0.0
  	 * @access protected
  	 */
  	protected function render() {
      $settings = $this->get_settings_for_display();
  		$sidebar = $settings[ 'sidebar' ];
      $active_breakpoints = kitify_helper()->get_active_breakpoints();

  		if ( empty( $sidebar ) ) {
  			return;
  		}
      $this->add_render_attribute( 'sidebar-wrapper', 'class', 'kitify-sidebar' );
      $this->add_render_attribute( 'sidebar-wrapper', 'class', 'kitify-sidebar-layout_0'.$settings['sidebar_style'] );
      if ( filter_var( $settings['sidebar_toggle'], FILTER_VALIDATE_BOOLEAN ) ) {
        
        add_action('kitify/products/toolbar/filter', [ $this, 'filter_button' ] );
        $breakpoint = isset($settings['sidebar_toggle_breakpoint']) ? $settings['sidebar_toggle_breakpoint'] : 'tablet';

        $breakpoint_value = 7680;
        if( filter_var( $settings['sidebar_toggle_on'], FILTER_VALIDATE_BOOLEAN ) ) {
          $breakpoint_value = 1024;
          if(isset($active_breakpoints[$breakpoint])){
              $breakpoint_value = $active_breakpoints[$breakpoint];
          }
        }
        $this->add_render_attribute( 'sidebar-wrapper', 'class', 'kitify-toggle-sidebar');
        $this->add_render_attribute( 'sidebar-wrapper', 'data-breakpoint', esc_attr($breakpoint_value) );
      }

      echo '<div ' . $this->get_render_attribute_string( 'sidebar-wrapper' ) . '>';
        echo '<div class="kitify-toggle-sidebar__overlay js-column-toggle"></div>';
        echo '<div class="kitify-toggle-sidebar__container">';
        echo '<a class="kitify-toggle-sidebar__toggle js-column-toggle" href="javascript:void(0)"></a>';
          echo '<div class="toggle-column-btn__wrap"><a class="toggle-column-btn js-column-toggle" href="javascript:void(0)"></a></div>';
          echo '<div class="kitify-toggle-sidebar__inner nova_box_ps">';
          dynamic_sidebar( $sidebar );
          echo '</div>';
        echo '</div>';
      echo '</div>';
  	}
    public function filter_button() {
      $settings = $this->get_settings_for_display();
      $active_breakpoints = kitify_helper()->get_active_breakpoints();
      $breakpoint = isset($settings['sidebar_toggle_breakpoint']) ? $settings['sidebar_toggle_breakpoint'] : 'tablet';
      $breakpoint_value = 7680;
      if( filter_var( $settings['sidebar_toggle_on'], FILTER_VALIDATE_BOOLEAN ) ) {
        $breakpoint_value = 1024;
        if(isset($active_breakpoints[$breakpoint])){
            $breakpoint_value = $active_breakpoints[$breakpoint];
        }
      }
      $this->add_render_attribute( 'btn-wrapper', 'class', 'nova-product-filter');
      $this->add_render_attribute( 'btn-wrapper', 'data-breakpoint', esc_attr($breakpoint_value) );
      echo '<div ' . $this->get_render_attribute_string( 'btn-wrapper' ) . '>';
        echo '<button class="js-column-toggle">';
          echo '<span class="icon-filter"><i class="inova ic-options"></i></span>';
          echo '<span class="title-filter">'.esc_html__( 'Filters','kitify' ).'</span>';
        echo '</button>';
      echo '</div>';
    }
  	/**
  	 * Render sidebar widget output in the editor.
  	 *
  	 * Written as a Backbone JavaScript template and used to generate the live preview.
  	 *
  	 * @since 2.9.0
  	 * @access protected
  	 */
  	protected function content_template() {}

  	/**
  	 * Render sidebar widget as plain content.
  	 *
  	 * Override the default render behavior, don't render sidebar content.
  	 *
  	 * @since 1.0.0
  	 * @access public
  	 */
  	public function render_plain_content() {}
  }
