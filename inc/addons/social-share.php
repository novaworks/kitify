<?php
/**
 * Class: Kitify_Social_Share
 * Name: Social Share
 * Slug: kitify-social-share
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Social_Share extends Kitify_Base {

    private static $networks = [
        'facebook' => [
            'title' => 'Facebook',
            'has_counter' => true,
        ],
        'twitter' => [
            'title' => 'Twitter',
        ],
        'google' => [
            'title' => 'Google+',
            'has_counter' => true,
        ],
        'linkedin' => [
            'title' => 'LinkedIn',
            'has_counter' => true,
        ],
        'pinterest' => [
            'title' => 'Pinterest',
            'has_counter' => true,
        ],
        'reddit' => [
            'title' => 'Reddit',
            'has_counter' => true,
        ],
        'vk' => [
            'title' => 'VK',
            'has_counter' => true,
        ],
        'odnoklassniki' => [
            'title' => 'OK',
            'has_counter' => true,
        ],
        'tumblr' => [
            'title' => 'Tumblr',
        ],
        'digg' => [
            'title' => 'Digg',
        ],
        'skype' => [
            'title' => 'Skype',
        ],
        'stumbleupon' => [
            'title' => 'StumbleUpon',
            'has_counter' => true,
        ],
        'mix' => [
            'title' => 'Mix',
        ],
        'telegram' => [
            'title' => 'Telegram',
        ],
        'pocket' => [
            'title' => 'Pocket',
            'has_counter' => true,
        ],
        'xing' => [
            'title' => 'XING',
            'has_counter' => true,
        ],
        'whatsapp' => [
            'title' => 'WhatsApp',
        ],
        'email' => [
            'title' => 'Email',
        ],
        'print' => [
            'title' => 'Print',
        ],
    ];

    private static $networks_class_dictionary = [
        'google' => 'fa fa-google-plus',
        'pocket' => 'fa fa-get-pocket',
        'email' => 'novaicon-mail',
    ];

    private static $networks_icon_mapping = [
        'pinterest' => 'novaicon-b-pinterest',
        'facebook' => 'novaicon-b-facebook',
        'twitter' => 'novaicon-b-twitter',
        'linkedin' => 'novaicon-b-linkedin',
        'reddit' => 'novaicon-b-reddit',
        'vk' => 'novaicon-b-vkontakte',
        'tumblr' => 'novaicon-b-tumblr',
        'skype' => 'novaicon-b-skype',
        'telegram' => 'novaicon-b-telegram',
        'whatsapp' => 'novaicon-b-whatsapp',
        'email' => 'novaicon-mail',
        'google' => 'fab fa-google-plus-g',
        'pocket' => 'fab fa-get-pocket',
        'print' => 'fas fa-print',
    ];

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/social-share.css'), null, kitify()->get_version());
        wp_register_script(  $this->get_name() , kitify()->plugin_url('assets/js/addons/social-share.js') , [ 'share-link', 'elementor-frontend' ],  kitify()->get_version() , true );

        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

	public function get_name() {
		return 'kitify-social-share';
	}

	public function get_widget_title() {
		return esc_html__( 'Social Share', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-share';
	}

    public function get_keywords() {
        return [ 'sharing', 'social', 'icon', 'button', 'like' ];
    }

    private static function get_network_class( $network_name ) {
        $prefix = 'fa ';
        if ( Icons_Manager::is_migration_allowed() ) {
            if ( isset( self::$networks_icon_mapping[ $network_name ] ) ) {
                return self::$networks_icon_mapping[ $network_name ];
            }
            $prefix = 'fab ';
        }
        if ( isset( self::$networks_class_dictionary[ $network_name ] ) ) {
            return self::$networks_class_dictionary[ $network_name ];
        }

        return $prefix . 'fa-' . $network_name;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_buttons_content',
            [
                'label' => __( 'Share Buttons', 'kitify'),
            ]
        );

        $repeater = new Repeater();

        $networks = self::$networks;

        $networks_names = array_keys( $networks );

        $repeater->add_control(
            'button',
            [
                'label' => __( 'Network', 'kitify'),
                'type' => Controls_Manager::SELECT,
                'options' => array_reduce( $networks_names, function( $options, $network_name ) use ( $networks ) {
                    $options[ $network_name ] = $networks[ $network_name ]['title'];

                    return $options;
                }, [] ),
                'default' => 'facebook',
            ]
        );

        $repeater->add_control(
            'text',
            [
                'label' => __( 'Custom Label', 'kitify'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'share_buttons',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'button' => 'facebook',
                    ],
                    [
                        'button' => 'twitter',
                    ],
                    [
                        'button' => 'linkedin',
                    ],
                ],
                'title_field' => '{{{ button }}}'
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => __( 'View', 'kitify'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'icon-text' => 'Icon & Text',
                    'icon' => 'Icon',
                    'text' => 'Text',
                ],
                'default' => 'icon-text',
                'separator' => 'before',
                'prefix_class' => 'elementor-share-buttons--view-',
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'show_label',
            [
                'label' => __( 'Label', 'kitify'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'kitify'),
                'label_off' => __( 'Hide', 'kitify'),
                'default' => 'yes',
                'condition' => [
                    'view' => 'icon-text',
                ],
            ]
        );

        $this->add_control(
            'skin',
            [
                'label' => __( 'Skin', 'kitify'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'gradient' => __( 'Gradient', 'kitify'),
                    'minimal' => __( 'Minimal', 'kitify'),
                    'framed' => __( 'Framed', 'kitify'),
                    'boxed' => __( 'Boxed Icon', 'kitify'),
                    'flat' => __( 'Flat', 'kitify'),
                ],
                'default' => 'gradient',
                'prefix_class' => 'elementor-share-buttons--skin-',
            ]
        );

        $this->add_control(
            'shape',
            [
                'label' => __( 'Shape', 'kitify'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'square' => __( 'Square', 'kitify'),
                    'rounded' => __( 'Rounded', 'kitify'),
                    'circle' => __( 'Circle', 'kitify'),
                ],
                'default' => 'square',
                'prefix_class' => 'elementor-share-buttons--shape-',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', 'kitify'),
                'type' => Controls_Manager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => 'Auto',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'prefix_class' => 'elementor-grid%s-',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __( 'Alignment', 'kitify'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'kitify'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'kitify'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'kitify'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justify', 'kitify'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor-share-buttons%s--align-',
                'condition' => [
                    'columns' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--alignment: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'share_url_type',
            [
                'label' => __( 'Target URL', 'kitify'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'current_page' => __( 'Current Page', 'kitify'),
                    'custom' => __( 'Custom', 'kitify'),
                ],
                'default' => 'current_page',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'share_url',
            [
                'label' => __( 'Link', 'kitify'),
                'type' => Controls_Manager::URL,
                'options' => false,
                'placeholder' => __( 'https://your-link.com', 'kitify'),
                'condition' => [
                    'share_url_type' => 'custom',
                ],
                'show_label' => false,
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_buttons_style',
            [
                'label' => __( 'Share Buttons', 'kitify'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => __( 'Columns Gap', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--grid-side-margin: {{SIZE}}{{UNIT}}; --grid-column-gap: {{SIZE}}{{UNIT}}; --grid-row-gap: {{SIZE}}{{UNIT}}',
                    '(tablet) {{WRAPPER}}' => '--grid-side-margin: {{SIZE}}{{UNIT}}; --grid-column-gap: {{SIZE}}{{UNIT}}',
                    '(mobile) {{WRAPPER}}' => '--grid-side-margin: {{SIZE}}{{UNIT}}; --grid-column-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => __( 'Rows Gap', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}; --grid-bottom-margin: {{SIZE}}{{UNIT}}',
                    '(tablet) {{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}; --grid-bottom-margin: {{SIZE}}{{UNIT}}',
                    '(mobile) {{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}; --grid-bottom-margin: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_size',
            [
                'label' => __( 'Button Size', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.5,
                        'max' => 2,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-share-btn' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Icon Size', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0.5,
                        'max' => 4,
                        'step' => 0.1,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'em',
                ],
                'tablet_default' => [
                    'unit' => 'em',
                ],
                'mobile_default' => [
                    'unit' => 'em',
                ],
                'size_units' => [ 'em', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-share-btn__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'view!' => 'text',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_height',
            [
                'label' => __( 'Button Height', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 1,
                        'max' => 7,
                        'step' => 0.1,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'em',
                ],
                'tablet_default' => [
                    'unit' => 'em',
                ],
                'mobile_default' => [
                    'unit' => 'em',
                ],
                'size_units' => [ 'em', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-share-btn' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_size',
            [
                'label' => __( 'Border Size', 'kitify'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default' => [
                    'size' => 2,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                    'em' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-share-btn' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'skin' => [ 'framed', 'boxed' ],
                ],
            ]
        );

        $this->add_control(
            'color_source',
            [
                'label' => __( 'Color', 'kitify'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'official' => __( 'Official', 'kitify'),
                    'custom' => __( 'Custom', 'kitify'),
                ],
                'default' => 'official',
                'prefix_class' => 'elementor-share-buttons--color-',
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs(
            'tabs_button_style',
            [
                'condition' => [
                    'color_source' => 'custom',
                ],
            ]
        );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'kitify'),
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __( 'Primary Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.elementor-share-buttons--skin-flat .elementor-share-btn,
					 {{WRAPPER}}.elementor-share-buttons--skin-gradient .elementor-share-btn,
					 {{WRAPPER}}.elementor-share-buttons--skin-boxed .elementor-share-btn .elementor-share-btn__icon,
					 {{WRAPPER}}.elementor-share-buttons--skin-minimal .elementor-share-btn .elementor-share-btn__icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}.elementor-share-buttons--skin-framed .elementor-share-btn,
					 {{WRAPPER}}.elementor-share-buttons--skin-minimal .elementor-share-btn,
					 {{WRAPPER}}.elementor-share-buttons--skin-boxed .elementor-share-btn' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __( 'Secondary Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-share-buttons--skin-flat .elementor-share-btn__icon,
					 {{WRAPPER}}.elementor-share-buttons--skin-flat .elementor-share-btn__text,
					 {{WRAPPER}}.elementor-share-buttons--skin-gradient .elementor-share-btn__icon,
					 {{WRAPPER}}.elementor-share-buttons--skin-gradient .elementor-share-btn__text,
					 {{WRAPPER}}.elementor-share-buttons--skin-boxed .elementor-share-btn__icon,
					 {{WRAPPER}}.elementor-share-buttons--skin-minimal .elementor-share-btn__icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}}.elementor-share-buttons--skin-framed .elementor-share-btn' => 'border-color: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'kitify'),
            ]
        );

        $this->add_control(
            'primary_color_hover',
            [
                'label' => __( 'Primary Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-share-buttons--skin-flat .elementor-share-btn:hover,
					 {{WRAPPER}}.elementor-share-buttons--skin-gradient .elementor-share-btn:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}.elementor-share-buttons--skin-framed .elementor-share-btn:hover,
					 {{WRAPPER}}.elementor-share-buttons--skin-minimal .elementor-share-btn:hover,
					 {{WRAPPER}}.elementor-share-buttons--skin-boxed .elementor-share-btn:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                    '{{WRAPPER}}.elementor-share-buttons--skin-boxed .elementor-share-btn:hover .elementor-share-btn__icon,
					 {{WRAPPER}}.elementor-share-buttons--skin-minimal .elementor-share-btn:hover .elementor-share-btn__icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'secondary_color_hover',
            [
                'label' => __( 'Secondary Color', 'kitify'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-share-buttons--skin-flat .elementor-share-btn:hover .elementor-share-btn__icon,
					 {{WRAPPER}}.elementor-share-buttons--skin-flat .elementor-share-btn:hover .elementor-share-btn__text,
					 {{WRAPPER}}.elementor-share-buttons--skin-gradient .elementor-share-btn:hover .elementor-share-btn__icon,
					 {{WRAPPER}}.elementor-share-buttons--skin-gradient .elementor-share-btn:hover .elementor-share-btn__text,
					 {{WRAPPER}}.elementor-share-buttons--skin-boxed .elementor-share-btn:hover .elementor-share-btn__icon,
					 {{WRAPPER}}.elementor-share-buttons--skin-minimal .elementor-share-btn:hover .elementor-share-btn__icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}}.elementor-share-buttons--skin-framed .elementor-share-btn:hover' => 'border-color: {{VALUE}}'
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .elementor-share-btn__title',
                'exclude' => [ 'line_height' ],
            ]
        );

        $this->add_control(
            'text_padding',
            [
                'label' => __( 'Text Padding', 'kitify'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'view' => 'text',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_active_settings();

        if ( empty( $settings['share_buttons'] ) ) {
            return;
        }
        $fas_depends = [
            'google',
            'odnoklassniki',
            'digg',
            'stumbleupon',
            'mix',
            'pocket',
            'xing',
            'print',
        ];

        $button_classes = 'elementor-share-btn';

        $show_text = 'text' === $settings['view'] || 'yes' === $settings['show_label'];
        ?>
        <div class="elementor-grid">
            <?php
            $networks_data = self::$networks;

            foreach ( $settings['share_buttons'] as $button ) {
                $network_name = $button['button'];

                // A deprecated network.
                if ( ! isset( $networks_data[ $network_name ] ) ) {
                    continue;
                }

                $social_network_class = ' elementor-share-btn_' . $network_name;
                ?>
                <div class="elementor-grid-item">
                    <div class="<?php echo esc_attr( $button_classes . $social_network_class ); ?>">
                        <?php if ( 'icon' === $settings['view'] || 'icon-text' === $settings['view'] ) :

//                        if( in_array($network_name, $fas_depends) ){
//                            $this->add_style_depends('elementor-icons-fa-solid');
//                            $this->add_style_depends('elementor-icons-fa-brands');
//                        }
                        ?>
                            <span class="elementor-share-btn__icon">
								<i class="<?php echo self::get_network_class( $network_name ); ?>" aria-hidden="true"></i>
								<span class="elementor-screen-only"><?php echo sprintf( __( 'Share on %s', 'kitify'), $network_name ); ?></span>
							</span>
                        <?php endif; ?>
                        <?php if ( $show_text ) : ?>
                            <div class="elementor-share-btn__text">
                                <?php if ( 'yes' === $settings['show_label'] || 'text' === $settings['view'] ) : ?>
                                    <span class="elementor-share-btn__title">
										<?php echo $button['text'] ? $button['text'] : $networks_data[ $network_name ]['title']; ?>
									</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

}
