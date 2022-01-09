<?php
/**
 * Class: Kitify_VideoModal
 * Name: Sidebar
 * Slug: kitify-video-modal
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

class Kitify_VideoModal extends Kitify_Base {

  protected function enqueue_addon_resources(){
      wp_register_script(
        $this->get_name(),
        kitify()->plugin_url( 'assets/js/addons/video-modal.js' ),
        array('fancybox' ),
        kitify()->get_version(),
        true
      );
      $this->add_script_depends( 'fancybox' );
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
    return 'kitify-video-modal';
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
    return esc_html__( 'Kitify Video Modal', 'kitify' );
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
  		return 'eicon-video-playlist';
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
  		return [ 'video', 'widget' ];
  	}

  	/**
  	 * Register sidebar widget controls.
  	 *
  	 * Adds different input fields to allow the user to change and customize the widget settings.
  	 *
  	 * @since 3.1.0
  	 * @access protected
  	 */
     protected function _register_controls() {
       $css_scheme = apply_filters(
           'kitify/video-modal/css-scheme',
           array(
               'icon_play'   => '.nova-video-modal .video-modal-btn',
               'icon_play_hover'   => '.nova-video-modal .video-modal-btn:hover',
           )
       );
       $this->start_controls_section(
           'section_video_content',
           array(
               'label'      => esc_html__( 'Video', 'kitify' ),
               'tab'        => Controls_Manager::TAB_CONTENT,
               'show_label' => false,
           )
       );
       $this->add_control(
           'video_url',
           array(
               'label'   => esc_html__( 'Video Url', 'kitify' ),
               'type'    => Controls_Manager::URL,
               'dynamic' => array( 'active' => true ),
           )
       );

       $this->add_control(
           'play_icon',
           array(
               'label'       => esc_html__( 'Play Icon', 'kitify' ),
               'type' => Controls_Manager::ICONS,
               'fa4compatibility' => 'icon',
               'default' => [
                   'value' => 'fas fa-play',
                   'library' => 'fa-solid',
               ]
           )
       );

       $this->end_controls_section();

       $this->start_controls_section(
           'section_icon_style',
           array(
               'label'      => esc_html__( 'Icon Play', 'kitify' ),
               'tab'        => Controls_Manager::TAB_STYLE,
               'show_label' => false,
           )
       );
       $this->add_control(
           'icon_play_color',
           array(
               'label' => esc_html__( 'Icon Color', 'kitify' ),
               'type' => Controls_Manager::COLOR,
               'selectors' => array(
                   '{{WRAPPER}} ' . $css_scheme['icon_play'] => 'color: {{VALUE}}',
               ),
           )
       );
       $this->add_control(
           'icon_play_hover_color',
           array(
               'label' => esc_html__( 'Icon Hover Color', 'kitify' ),
               'type' => Controls_Manager::COLOR,
               'selectors' => array(
                   '{{WRAPPER}} ' . $css_scheme['icon_play_hover']  => 'color: {{VALUE}}',
               ),
           )
       );
       $this->add_control(
           'button_icon_size',
           array(
               'label' => esc_html__( 'Icon Size', 'kitify' ),
               'type' => Controls_Manager::SLIDER,
               'range' => array(
                   'px' => array(
                       'min' => 7,
                       'max' => 90,
                   ),
               ),
               'selectors' => array(
                   '{{WRAPPER}} ' . $css_scheme['icon_play'] => 'font-size: {{SIZE}}{{UNIT}};',
               ),
           )
       );
       $this->end_controls_section();
     }
     /**
      * [render description]
      * @return [type] [description]
      */
      protected function render() {
          $this->__context 	= 'render';
  				$video_url 				= $this->get_settings_for_display( 'video_url' );
  				$video_icon 				= $this->get_settings_for_display( 'play_icon' );
         ob_start();
         Icons_Manager::render_icon($video_icon,[ 'aria-hidden' => 'true' ]);
         $play_icon = ob_get_clean();
         if($video_url['url']) {
           echo '<div class="nova-video-modal"><a href="'.$video_url['url'].'" class="js-video-modal video-modal-btn">'.$play_icon.'</a></div>';
         }
      }
  }
