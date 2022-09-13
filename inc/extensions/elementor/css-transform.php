<?php

namespace KitifyExtensions\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CSS_Transform {

    public function __construct() {
        add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'init_module']);
        add_action('elementor/frontend/after_register_styles', [ $this, 'enqueue_scripts']);
    }

    public function enqueue_scripts(){
        wp_enqueue_style( 'kitify-css-transform', kitify()->plugin_url('assets/css/addons/css-transform.css'), [], kitify()->get_version());
    }

    public function init_module( $element ){
        $element->start_controls_section('_section_kitify_css_transform', [
            'label' => __('KITIFY CSS Transform', 'kitify'),
            'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]);
        $element->add_control('kitify_transform_fx', [
            'label' => __('Enable', 'kitify'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'prefix_class' => 'kitify-css-transform-',
        ]);
        $element->start_controls_tabs('_tabs_kitify_transform', [
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_controls_tab('_tabs_kitify_transform_normal', [
            'label' => __('Normal', 'kitify'),
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->add_control('kitify_transform_fx_translate_toggle', [
            'label' => __('Translate', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_responsive_control('kitify_transform_fx_translate_x', [
            'label' => __('Translate X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_translate_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-translate-x: {{SIZE}}px;'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_translate_y', [
            'label' => __('Translate Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_translate_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-translate-y: {{SIZE}}px;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('kitify_transform_fx_rotate_toggle', [
            'label' => __('Rotate', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_control('kitify_transform_fx_rotate_mode', [
            'label' => __('Mode', 'kitify'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'compact' => [
                    'title' => __('Compact', 'kitify'),
                    'icon' => 'eicon-plus-circle',
                ],
                'loose' => [
                    'title' => __('Loose', 'kitify'),
                    'icon' => 'eicon-minus-circle',
                ],
            ],
            'default' => 'loose',
            'toggle' => false
        ]);
        $element->add_control('kitify_transform_fx_rotate_hr', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
        $element->add_responsive_control('kitify_transform_fx_rotate_x', [
            'label' => __('Rotate X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_rotate_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
                'kitify_transform_fx_rotate_mode' => 'loose'
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-rotate-x: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_rotate_y', [
            'label' => __('Rotate Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_rotate_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
                'kitify_transform_fx_rotate_mode' => 'loose'
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-rotate-y: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_rotate_z', [
            'label' => __('Rotate (Z)', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_rotate_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-rotate-z: {{SIZE}}deg;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('kitify_transform_fx_scale_toggle', [
            'label' => __('Scale', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_control('kitify_transform_fx_scale_mode', [
            'label' => __('Mode', 'kitify'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'compact' => [
                    'title' => __('Compact', 'kitify'),
                    'icon' => 'eicon-plus-circle',
                ],
                'loose' => [
                    'title' => __('Loose', 'kitify'),
                    'icon' => 'eicon-minus-circle',
                ],
            ],
            'default' => 'loose',
            'toggle' => false
        ]);
        $element->add_control('kitify_transform_fx_scale_hr', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
        $element->add_responsive_control('kitify_transform_fx_scale_x', [
            'label' => __('Scale (X)', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'condition' => [
                'kitify_transform_fx_scale_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-scale-x: {{SIZE}}; --kitify-tfx-scale-y: {{SIZE}};'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_scale_y', [
            'label' => __('Scale Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'condition' => [
                'kitify_transform_fx_scale_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
                'kitify_transform_fx_scale_mode' => 'loose',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-scale-y: {{SIZE}};'
            ],
        ]);
        $element->end_popover();
        $element->add_control('kitify_transform_fx_skew_toggle', [
            'label' => __('Skew', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_responsive_control('kitify_transform_fx_skew_x', [
            'label' => __('Skew X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_skew_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-skew-x: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_skew_y', [
            'label' => __('Skew Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_skew_toggle' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-skew-y: {{SIZE}}deg;'
            ],
        ]);
        $element->end_popover();
        $element->end_controls_tab();
        $element->start_controls_tab('_tabs_kitify_transform_hover', [
            'label' => __('Hover', 'kitify'),
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->add_control('kitify_transform_fx_translate_toggle_hover', [
            'label' => __('Translate', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_responsive_control('kitify_transform_fx_translate_x_hover', [
            'label' => __('Translate X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_translate_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-translate-x-hover: {{SIZE}}px;'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_translate_y_hover', [
            'label' => __('Translate Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_translate_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-translate-y-hover: {{SIZE}}px;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('kitify_transform_fx_rotate_toggle_hover', [
            'label' => __('Rotate', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_control('kitify_transform_fx_rotate_mode_hover', [
            'label' => __('Mode', 'kitify'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'compact' => [
                    'title' => __('Compact', 'kitify'),
                    'icon' => 'eicon-plus-circle',
                ],
                'loose' => [
                    'title' => __('Loose', 'kitify'),
                    'icon' => 'eicon-minus-circle',
                ],
            ],
            'default' => 'loose',
            'toggle' => false
        ]);
        $element->add_control('kitify_transform_fx_rotate_hr_hover', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
        $element->add_responsive_control('kitify_transform_fx_rotate_x_hover', [
            'label' => __('Rotate X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_rotate_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
                'kitify_transform_fx_rotate_mode_hover' => 'loose'
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-rotate-x-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_rotate_y_hover', [
            'label' => __('Rotate Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_rotate_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
                'kitify_transform_fx_rotate_mode_hover' => 'loose'
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-rotate-y-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_rotate_z_hover', [
            'label' => __('Rotate (Z)', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_rotate_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-rotate-z-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('kitify_transform_fx_scale_toggle_hover', [
            'label' => __('Scale', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_control('kitify_transform_fx_scale_mode_hover', [
            'label' => __('Mode', 'kitify'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'compact' => [
                    'title' => __('Compact', 'kitify'),
                    'icon' => 'eicon-plus-circle',
                ],
                'loose' => [
                    'title' => __('Loose', 'kitify'),
                    'icon' => 'eicon-minus-circle',
                ],
            ],
            'default' => 'loose',
            'toggle' => false
        ]);
        $element->add_control('kitify_transform_fx_scale_hr_hover', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
        $element->add_responsive_control('kitify_transform_fx_scale_x_hover', [
            'label' => __('Scale (X)', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'condition' => [
                'kitify_transform_fx_scale_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-scale-x-hover: {{SIZE}}; --kitify-tfx-scale-y-hover: {{SIZE}};'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_scale_y_hover', [
            'label' => __('Scale Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'condition' => [
                'kitify_transform_fx_scale_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
                'kitify_transform_fx_scale_mode_hover' => 'loose',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-scale-y-hover: {{SIZE}};'
            ],
        ]);
        $element->end_popover();
        $element->add_control('kitify_transform_fx_skew_toggle_hover', [
            'label' => __('Skew', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_responsive_control('kitify_transform_fx_skew_x_hover', [
            'label' => __('Skew X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_skew_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-skew-x-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('kitify_transform_fx_skew_y_hover', [
            'label' => __('Skew Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'kitify_transform_fx_skew_toggle_hover' => 'yes',
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-skew-y-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('kitify_transform_fx_transition_duration', [
            'label' => __('Transition Duration', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'separator' => 'before',
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 3,
                    'step' => .1,
                ]
            ],
            'condition' => [
                'kitify_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--kitify-tfx-transition-duration: {{SIZE}}s;'
            ],
        ]);
        $element->end_controls_tab();
        $element->end_controls_tabs();
        $element->end_controls_section();
    }
}
