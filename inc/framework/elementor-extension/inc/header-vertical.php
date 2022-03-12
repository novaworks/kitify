<?php
namespace Kitify_Extension\Modules;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Controls_Manager;

class Header_Vertical {
    public function __construct() {
        add_action('elementor/theme/before_do_header', [ $this, 'add_open_wrap' ], 0 );
        add_action('wp_footer', [ $this, 'add_close_wrap' ], -1001 );
        add_action('elementor/element/header/document_settings/before_section_end', [ $this, 'register_control_settings' ]);

        add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'add_disable_relative_controls' ]);
        add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'add_transparency_controls' ]);
        add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'add_sticky_style_controls' ]);
    }

    public function add_sticky_style_controls( $stack ){

      $stack->start_controls_section('section_sticky_style', [
          'label' => esc_html__('KITIFY Sticky Style', 'kitify'),
          'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
      ]);
      $stack->add_group_control(
          \Elementor\Group_Control_Box_Shadow::get_type(),
          [
              'name' => 'sticky_style_box_shadow',
              'label' => esc_html__( 'Box Shadow', 'kitify' ),
              'selector' => '{{WRAPPER}}.elementor-top-section.elementor-sticky--effects',
              'selector' => '{{WRAPPER}}.elementor-inner-section.elementor-sticky--effects',
          ]
      );
      $stack->end_controls_section();
    }

    public function add_disable_relative_controls( $stack ){
      $stack->start_controls_section('section_disable_relative', [
          'label' => esc_html__('KITIFY Disable Relative Section', 'kitify'),
          'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
      ]);
      $stack->add_control(
          'kitify_section_disable_relative',
          [
              'label' => __( 'Disable relative section ?', 'kitify' ),
              'type' => Controls_Manager::SWITCHER,
              'return_value' => 'yes',
              'prefix_class' => 'kitify--disable-relative-section-',
          ]
      );
      $stack->end_controls_section();
    }

    public function add_transparency_controls( $stack ){
        $stack->start_controls_section('section_transparency_style', [
            'label' => esc_html__('KITIFY Transparency Style', 'kitify'),
            'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]);
        $stack->add_control(
            'kitify_section_transparency_enable',
            [
                'label' => __( 'Enable transparency style ?', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'prefix_class' => 'kitify--transparency-',
            ]
        );
        $stack->add_control(
            'kitify_section_transparency_note',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'Note: This option may not work properly in some cases', 'kitify' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'condition' => array(
                    'kitify_section_transparency_enable' => 'yes',
                ),
            ]
        );
        $stack->add_control(
            'kitify_section_bg_color',
            array(
                'label' => esc_html__( 'Section Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--kitify-section-bg-color: {{VALUE}}',
                ),
                'condition' => array(
                    'kitify_section_transparency_enable' => 'yes',
                ),
            )
        );
        $stack->add_control(
            'kitify_section_text_color',
            array(
                'label' => esc_html__( 'Section Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--kitify-section-text-color: {{VALUE}}',
                ),
                'condition' => array(
                    'kitify_section_transparency_enable' => 'yes',
                ),
            )
        );
        $stack->add_control(
            'kitify_section_link_color',
            array(
                'label' => esc_html__( 'Section Link Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--kitify-section-link-color: {{VALUE}}',
                ),
                'condition' => array(
                    'kitify_section_transparency_enable' => 'yes',
                ),
            )
        );
        $stack->add_control(
            'kitify_section_link_hover_color',
            array(
                'label' => esc_html__( 'Section Hover Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--kitify-section-link-hover-color: {{VALUE}}',
                ),
                'condition' => array(
                    'kitify_section_transparency_enable' => 'yes',
                ),
            )
        );
        $stack->end_controls_section();
    }

    public function register_control_settings( $stack ){
        $stack->add_control(
            'kitify_header_vertical',
            [
                'label' => __( 'Vertical Header Layout ?', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes'
            ]
        );
        $stack->add_responsive_control(
            'kitify_header_vertical_width',
            array(
                'label'      => esc_html__( 'Header Width', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'vw', 'vh', '%' ),
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => array(
                    '{{WRAPPER}}kitify.kitify--is-vheader' => '--kitify-vheader-width: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'kitify_header_vertical' => 'yes',
                ),
            )
        );

        $stack->add_control(
            'kitify_header_vertical_alignment',
            array(
                'label'   => esc_html__( 'Header Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'condition' => array(
                    'kitify_header_vertical' => 'yes',
                ),
            )
        );
        $stack->add_control(
            'kitify_header_vertical_disable_on',
            array(
                'label'   => esc_html__( 'Disable Vertical Header On', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'tablet',
                'options' => kitify_helper()->get_active_breakpoints(false, true),
                'condition' => array(
                    'kitify_header_vertical' => 'yes',
                ),
            )
        );
    }

    public function add_open_wrap(){
        global $kitify_site_wrapper_open;

        if(kitify()->has_elementor_pro()){
            $documents_by_conditions = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( 'header' );
        }
        else{
            $documents_by_conditions = \KitifyThemeBuilder\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( 'header' );
        }

        $document_id = key($documents_by_conditions);
        $settings = get_post_meta( $document_id, '_elementor_page_settings', true );
        $kitify_site_wrapper_open = true;
        $classes = ['kitify-site-wrapper'];
        $classes[] = 'elementor-' . $document_id . 'kitify';
        if(isset($settings['kitify_header_vertical']) && filter_var($settings['kitify_header_vertical'], FILTER_VALIDATE_BOOLEAN)){
            $alignment = !empty($settings['kitify_header_vertical_alignment']) ? $settings['kitify_header_vertical_alignment'] : 'left';
            $disable_on = !empty($settings['kitify_header_vertical_disable_on']) ? $settings['kitify_header_vertical_disable_on'] : 'tablet';
            if($alignment !== 'right'){
                $alignment = 'left';
            }
            $classes[] = 'kitify--is-vheader';
            $classes[] = 'kitify-vheader-p' . $alignment;
            $classes[] = 'kitify-vheader--hide' . $disable_on;
            wp_enqueue_script('kitify-header-vertical');
            wp_enqueue_style('kitify-base');
        }
        echo sprintf('<div class="%s">', esc_attr(join(' ', $classes)));
    }

    public function add_close_wrap(){
        global $kitify_site_wrapper_open;
        if( $kitify_site_wrapper_open ){
            echo '</div><!-- .kitify-site-wrapper -->';
        }
        $kitify_site_wrapper_open = false;
    }
}

new Header_Vertical();
