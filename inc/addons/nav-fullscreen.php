<?php
/**
 * Class: Kitify_Nav_Fullscreen
 * Name: Fullscreen Menu
 * Slug: kitify-nav-fullscreen
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Nav_Fullscreen extends Kitify_Base {

  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(),
        kitify()->plugin_url('assets/css/addons/nav-fullscreen.css'),
        '',
        kitify()->get_version()
      );
      wp_register_script(
        $this->get_name(),
        kitify()->plugin_url( 'assets/js/addons/nav-fullscreenl.js' ),
        '',
        kitify()->get_version(),
        true
      );
      $this->add_script_depends( $this->get_name() );
      $this->add_style_depends( $this->get_name() );
  }
  /**
   * Get widget name.
   *
   * Retrieve video modal widget name.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget name.
   */
  public function get_name() {
    return 'kitify-nav-fullscreen';
  }
  /**
   * Get widget title.
   *
   * Retrieve video modal widget title.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget title.
   */
  public function get_title() {
    return esc_html__( 'Kitify Fullscreen Menu', 'kitify' );
  }

  /**
  	 * Get widget icon.
  	 *
  	 * Retrieve video modal widget icon.
  	 *
  	 * @since 1.0.0
  	 * @access public
  	 *
  	 * @return string Widget icon.
  	 */
     public function get_icon() {
         return 'kitify-icon-nav-menu';
     }

  	/**
  	 * Register video modal widget controls.
  	 *
  	 * Adds different input fields to allow the user to change and customize the widget settings.
  	 *
  	 * @since 1.0.0
  	 * @access protected
  	 */
     protected function register_controls() {
       $css_scheme = apply_filters(
           'kitify/nav-fullscreen/css-scheme',
           array(
               'icon_play'   => '.kitify-video-modal .video-modal-btn',
               'icon_play_hover'   => '.kitify-video-modal .video-modal-btn:hover',
           )
       );
       $this->start_controls_section(
           'section_menu',
           array(
               'label'      => esc_html__( 'Menu', 'kitify' ),
               'tab'        => Controls_Manager::TAB_CONTENT,
               'show_label' => false,
           )
       );

       $this->add_control(
           'menu_icon',
           array(
               'label'       => esc_html__( 'Menu Icon', 'kitify' ),
               'type' => Controls_Manager::ICONS,
               'fa4compatibility' => 'icon',
               'default' => [
                   'value' => 'dlicon ui-2_menu-34',
                   'library' => 'dlicon',
               ]
           )
       );

       $this->end_controls_section();

     }
     /**
      * [render description]
      * @return [type] [description]
      */
      protected function render() {
      }
  }
