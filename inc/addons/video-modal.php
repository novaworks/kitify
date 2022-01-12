<?php
/**
 * Class: Kitify_Video_Modal
 * Name: Video Modal
 * Slug: kitify-video-modal
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Video_Modal extends Kitify_Base {

  protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(),
        kitify()->plugin_url('assets/css/addons/video-modal.css'),
        array('fancybox' ),
        kitify()->get_version()
      );
      wp_register_script(
        $this->get_name(),
        kitify()->plugin_url( 'assets/js/addons/video-modal.js' ),
        array('fancybox' ),
        kitify()->get_version(),
        true
      );
      $this->add_script_depends( 'fancybox' );
      $this->add_script_depends( $this->get_name() );

      $this->add_style_depends( 'fancybox' );
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
    return 'kitify-video-modal';
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
    return esc_html__( 'Kitify Video Modal', 'kitify' );
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
  		return 'eicon-video-playlist';
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
           'kitify/video-modal/css-scheme',
           array(
               'icon_play'   => '.kitify-video-modal .video-modal-btn',
               'icon_play_hover'   => '.kitify-video-modal .video-modal-btn:hover',
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
           echo '<div class="kitify-video-modal"><a href="'.$video_url['url'].'" class="js-video-modal video-modal-btn">'.$play_icon.'</a></div>';
         }
      }
  }
