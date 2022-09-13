<?php

namespace KitifyExtensions\Elementor;
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
class Motion_Effects
{
  public function __construct() {
      if (!defined('ELEMENTOR_PRO_VERSION')) {
          add_action('elementor/element/section/section_effects/after_section_start', [
              $this,
              'init_module'
          ]);
          add_action('elementor/element/column/section_effects/after_section_start', [
              $this,
              'init_module'
          ]);
          add_action('elementor/element/common/section_effects/after_section_start', [
              $this,
              'init_module'
          ]);
          add_action('elementor/element/section/section_background/before_section_end', [
              $this,
              'init_module_in_background'
          ]);
          add_action('elementor/element/column/section_style/before_section_end', [
              $this,
              'init_module_in_background'
          ]);
          add_action('elementor/element/section/section_effects/after_section_start', [
              $this,
              'init_sticky'
          ]);
          add_action('elementor/element/common/section_effects/after_section_start', [
              $this,
              'init_sticky'
          ]);
          add_action('elementor/frontend/after_register_styles', [
              $this,
              'register_enqueue_scripts'
          ]);
          add_action('elementor/preview/enqueue_scripts', [
              $this,
              'enqueue_preview_scripts'
          ]);
          add_action('elementor/frontend/before_render', [
              $this,
              'enqueue_in_widget'
          ]);
          add_action('elementor/controls/controls_registered', array(
              $this,
              'register_controls'
          ));

        add_action('elementor/element/container/section_effects/after_section_start', [
          $this,
          'init_module'
        ]);
        add_action('elementor/element/container/section_effects/after_section_start', [
          $this,
          'init_sticky'
        ]);
        add_action('elementor/element/container/section_background/before_section_end', [
          $this,
          'init_module_in_background'
        ]);

          add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
      }
  }

    public function register_enqueue_scripts() {
        wp_register_style('kitify-motion-fx', kitify()->plugin_url('assets/css/addons/motion-fx.css'), [], kitify()->get_version());
        wp_register_script('kitify-sticky-js', kitify()->plugin_url('assets/js/lib/jquery.sticky.min.js'), ['jquery'], kitify()->get_version(), true);
        wp_register_script('kitify-motion-fx', kitify()->plugin_url('assets/js/addons/motion-fx.js'), [
            'elementor-frontend-modules',
            'kitify-sticky-js'
        ], kitify()->get_version(), true);
    }
    public function enqueue_frontend_scripts(){
        if ( $this->is_assets_loader_exist() ) {
            $this->register_assets();
        }
    }
    private function register_assets() {
      $assets = $this->get_assets();

      if ( $assets ) {
          kitify()->elementor()->assets_loader->add_assets( $assets );
      }
    }
    private function get_assets() {
        return [
            'scripts' => [
                'e-sticky' => [
                    'src' => kitify()->plugin_url('assets/js/lib/jquery.sticky.min.js'),
                    'version' => kitify()->get_version(true),
                    'dependencies' => [
                        'jquery',
                    ],
                ],
            ],
        ];
    }
  private function is_assets_loader_exist() {
      return ! ! kitify()->elementor()->assets_loader;
  }
    public function enqueue_preview_scripts() {
        wp_enqueue_style('kitify-motion-fx');
        wp_enqueue_script('kitify-motion-fx');
    }

    public function enqueue_in_widget($element) {
        $motion_groups = [
            'motion_fx_motion_fx_mouse',
            'motion_fx_motion_fx_scrolling',
            'sticky',
            'background_motion_fx_motion_fx_scrolling',
            'background_motion_fx_motion_fx_mouse',
        ];
        $need_enqueue_motion = false;
        foreach ($motion_groups as $group_key) {
            $group_value = $element->get_settings_for_display($group_key);
            if (!empty($group_value) && ($group_value == 'yes' || $group_value == 'top' || $group_value == 'bottom')) {
                $need_enqueue_motion = true;
            }
        }
        if ($need_enqueue_motion) {
            $element->add_script_depends('kitify-motion-fx');
            $element->add_style_depends('kitify-motion-fx');
        }
    }

    public function register_controls($controls_manager) {
        $controls_manager->add_group_control(\KitifyExtensions\Elementor\Controls\Group_Control_Motion_Fx::get_type(), new \KitifyExtensions\Elementor\Controls\Group_Control_Motion_Fx());
    }

    public function init_module($element) {
        $exclude = [];
        $selector = '{{WRAPPER}}';
        if ($element instanceof \Elementor\Element_Section) {
            $exclude[] = 'motion_fx_mouse';
        }
        elseif ($element instanceof \Elementor\Element_Column) {
            $selector .= ' > .elementor-column-wrap';
        }
        else {
            $selector .= ' > .elementor-widget-container';
        }
        $element->add_group_control('motion_fx', [
            'name' => 'motion_fx',
            'selector' => $selector,
            'exclude' => $exclude,
        ]);
    }

    public function init_module_in_background($element) {
        $element->start_injection([
            'of' => 'background_bg_width_mobile',
        ]);
        $element->add_group_control('motion_fx', [
            'name' => 'background_motion_fx',
            'exclude' => [
                'rotateZ_effect',
                'tilt_effect',
                'transform_origin_x',
                'transform_origin_y',
            ],
        ]);
        $options = [
            'separator' => 'before',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            [
                                'name' => 'background_background',
                                'value' => 'classic',
                            ],
                            [
                                'name' => 'background_image[url]',
                                'operator' => '!==',
                                'value' => '',
                            ],
                        ],
                    ],
                    [
                        'terms' => [
                            [
                                'name' => 'background_background',
                                'value' => 'gradient',
                            ],
                            [
                                'name' => 'background_color',
                                'operator' => '!==',
                                'value' => '',
                            ],
                            [
                                'name' => 'background_color_b',
                                'operator' => '!==',
                                'value' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $element->update_control('background_motion_fx_motion_fx_scrolling', $options);
        $element->update_control('background_motion_fx_motion_fx_mouse', $options);
        $element->end_injection();
    }

    public function init_sticky($element) {
        $element->add_control('sticky', [
                'label' => __('Sticky', 'kitify'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __('None', 'kitify'),
                    'top' => __('Top', 'kitify'),
                    'bottom' => __('Bottom', 'kitify'),
                ],
                'separator' => 'before',
                'render_type' => 'none',
                'frontend_available' => true,
            ]);


        $activeBreakpoints = array_merge(
            [
                'desktop' => __('Desktop', 'kitify'),
            ],
            kitify_helper()->get_active_breakpoints(false,true)
        );

        $element->add_control('sticky_on', [
                'label' => __('Sticky On', 'kitify'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => 'true',
                'default' => array_keys($activeBreakpoints),
                'options' => $activeBreakpoints,
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]);
        $element->add_control('sticky_offset', [
                'label' => __('Offset', 'kitify'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 500,
                'required' => true,
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]);
        $element->add_control('sticky_effects_offset', [
                'label' => __('Effects Offset', 'kitify'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 1000,
                'required' => true,
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]);
        /*		if ( $element instanceof \Elementor\Widget_Base ) { */
        $element->add_control('sticky_parent', [
                'label' => __('Stay In Column', 'kitify'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]);
        /*		} */
        $element->add_control('sticky_divider', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
    }
}
