<?php

namespace KitifyExtensions\Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Floating_Effects {
    public function __construct() {
        add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'init_module']);
        add_action('elementor/frontend/after_register_styles', [ $this, 'register_enqueue_scripts']);
        add_action('elementor/preview/enqueue_scripts', [ $this, 'enqueue_preview_scripts']);
        add_action('elementor/frontend/before_render', [ $this, 'enqueue_in_widget']);
    }

    public function register_enqueue_scripts(){
        wp_register_script( 'kitify-anime-js', kitify()->plugin_url('assets/js/lib/anime.min.js'), [], kitify()->get_version(), true);
        wp_register_script( 'kitify-floating-effects', kitify()->plugin_url('assets/js/addons/floating-effects.min.js'), ['kitify-anime-js', 'elementor-frontend'], kitify()->get_version(), true);
    }

    public function enqueue_preview_scripts(){
        wp_enqueue_script('kitify-floating-effects');
    }

    public function enqueue_in_widget( $element ) {
        if ( 'yes' == $element->get_settings_for_display( 'kitify_floating_fx' ) ) {
            $element->add_script_depends('kitify-floating-effects');
        }
    }

    public function init_module( $element ){
        $element->start_controls_section('_section_kitify_floating_effects', [
            'label' => __('KITIFY Floating Effects', 'kitify'),
            'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]);
        $element->add_control(
            'kitify_floating_msg',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'raw' => __( 'This option will not work if Motion Scrolling Effects or Motion Mouse Effects is enabled', 'elementor' ),
            ]
        );
        $element->add_control('kitify_floating_fx', [
            'label' => __('Enable', 'kitify'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_translate_toggle', [
            'label' => __('Translate', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'frontend_available' => true,
            'condition' => [
                'kitify_floating_fx' => 'yes',
            ]
        ]);
        $element->start_popover();
        $element->add_control('kitify_floating_fx_translate_x', [
            'label' => __('Translate X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => [
                'sizes' => [
                    'from' => 0,
                    'to' => 5,
                ],
                'unit' => 'px',
            ],
            'range' => [
                'px' => [
                    'min' => -200,
                    'max' => 200,
                ]
            ],
            'labels' => [
                __('From', 'kitify'),
                __('To', 'kitify'),
            ],
            'scales' => 1,
            'handles' => 'range',
            'condition' => [
                'kitify_floating_fx_translate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_translate_y', [
            'label' => __('Translate Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => [
                'sizes' => [
                    'from' => 0,
                    'to' => 5,
                ],
                'unit' => 'px',
            ],
            'range' => [
                'px' => [
                    'min' => -200,
                    'max' => 200,
                ]
            ],
            'labels' => [
                __('From', 'kitify'),
                __('To', 'kitify'),
            ],
            'scales' => 1,
            'handles' => 'range',
            'condition' => [
                'kitify_floating_fx_translate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_translate_duration', [
            'label' => __('Duration', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 10000,
                    'step' => 100
                ]
            ],
            'default' => [
                'size' => 1000,
            ],
            'condition' => [
                'kitify_floating_fx_translate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_translate_delay', [
            'label' => __('Delay', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5000,
                    'step' => 100
                ]
            ],
            'condition' => [
                'kitify_floating_fx_translate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->end_popover();
        $element->add_control('kitify_floating_fx_rotate_toggle', [
            'label' => __('Rotate', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'frontend_available' => true,
            'condition' => [
                'kitify_floating_fx' => 'yes',
            ]
        ]);
        $element->start_popover();
        $element->add_control('kitify_floating_fx_rotate_x', [
            'label' => __('Rotate X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => [
                'sizes' => [
                    'from' => 0,
                    'to' => 45,
                ],
                'unit' => 'px',
            ],
            'range' => [
                'px' => [
                    'min' => -360,
                    'max' => 360,
                    'step' => 1,
                ]
            ],
            'labels' => [
                __('From', 'kitify'),
                __('To', 'kitify'),
            ],
            'scales' => 1,
            'handles' => 'range',
            'condition' => [
                'kitify_floating_fx_rotate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_rotate_y', [
            'label' => __('Rotate Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => [
                'sizes' => [
                    'from' => 0,
                    'to' => 45,
                ],
                'unit' => 'px',
            ],
            'range' => [
                'px' => [
                    'min' => -360,
                    'max' => 360,
                    'step' => 1,
                ]
            ],
            'labels' => [
                __('From', 'kitify'),
                __('To', 'kitify'),
            ],
            'scales' => 1,
            'handles' => 'range',
            'condition' => [
                'kitify_floating_fx_rotate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_rotate_z', [
            'label' => __('Rotate Z', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => [
                'sizes' => [
                    'from' => 0,
                    'to' => 45,
                ],
                'unit' => 'px',
            ],
            'range' => [
                'px' => [
                    'min' => -360,
                    'max' => 360,
                    'step' => 1,
                ]
            ],
            'labels' => [
                __('From', 'kitify'),
                __('To', 'kitify'),
            ],
            'scales' => 1,
            'handles' => 'range',
            'condition' => [
                'kitify_floating_fx_rotate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_rotate_duration', [
            'label' => __('Duration', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 10000,
                    'step' => 100
                ]
            ],
            'default' => [
                'size' => 1000,
            ],
            'condition' => [
                'kitify_floating_fx_rotate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_rotate_delay', [
            'label' => __('Delay', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5000,
                    'step' => 100
                ]
            ],
            'condition' => [
                'kitify_floating_fx_rotate_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->end_popover();
        $element->add_control('kitify_floating_fx_scale_toggle', [
            'label' => __('Scale', 'kitify'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'frontend_available' => true,
            'condition' => [
                'kitify_floating_fx' => 'yes',
            ]
        ]);
        $element->start_popover();
        $element->add_control('kitify_floating_fx_scale_x', [
            'label' => __('Scale X', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => [
                'sizes' => [
                    'from' => 1,
                    'to' => 1.2,
                ],
                'unit' => 'px',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'labels' => [
                __('From', 'kitify'),
                __('To', 'kitify'),
            ],
            'scales' => 1,
            'handles' => 'range',
            'condition' => [
                'kitify_floating_fx_scale_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_scale_y', [
            'label' => __('Scale Y', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => [
                'sizes' => [
                    'from' => 1,
                    'to' => 1.2,
                ],
                'unit' => 'px',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'labels' => [
                __('From', 'kitify'),
                __('To', 'kitify'),
            ],
            'scales' => 1,
            'handles' => 'range',
            'condition' => [
                'kitify_floating_fx_scale_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_scale_duration', [
            'label' => __('Duration', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 10000,
                    'step' => 100
                ]
            ],
            'default' => [
                'size' => 1000,
            ],
            'condition' => [
                'kitify_floating_fx_scale_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->add_control('kitify_floating_fx_scale_delay', [
            'label' => __('Delay', 'kitify'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5000,
                    'step' => 100
                ]
            ],
            'condition' => [
                'kitify_floating_fx_scale_toggle' => 'yes',
                'kitify_floating_fx' => 'yes',
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ]);
        $element->end_popover();
        $element->end_controls_section();
    }
}
