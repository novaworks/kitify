<?php
/**
 * Class: Kitify_Logo
 * Name: Logo
 * Slug: kitify-logo
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Logo extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-base' );
    }

	public function get_name() {
		return 'kitify-logo';
	}

	public function get_widget_title() {
		return esc_html__( 'Logo', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-logo';
	}

    public function get_categories() {
        return [ 'kitify-builder' ];
    }

	protected function register_controls() {

        $this->_start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'kitify' ),
            )
        );

        $this->_add_control(
            'logo_type',
            array(
                'type'    => 'select',
                'label'   => esc_html__( 'Logo Type', 'kitify' ),
                'default' => 'text',
                'options' => array(
                    'text'  => esc_html__( 'Text', 'kitify' ),
                    'image' => esc_html__( 'Image', 'kitify' ),
                    'both'  => esc_html__( 'Both Text and Image', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'logo_image',
            array(
                'label'     => esc_html__( 'Logo Image', 'kitify' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => array(
                    'logo_type!' => 'text',
                ),
            )
        );

        $this->_add_control(
            'logo_image_4l',
            array(
                'label'     => esc_html__( 'Transparency Logo Image', 'kitify' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => array(
                    'logo_type!' => 'text',
                ),
            )
        );

        $this->_add_control(
            'logo_text_from',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Logo Text From', 'kitify' ),
                'default'    => 'site_name',
                'options'    => array(
                    'site_name' => esc_html__( 'Site Name', 'kitify' ),
                    'custom'    => esc_html__( 'Custom', 'kitify' ),
                ),
                'condition' => array(
                    'logo_type!' => 'image',
                ),
            )
        );

        $this->_add_control(
            'logo_text',
            array(
                'label'     => esc_html__( 'Custom Logo Text', 'kitify' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => array(
                    'logo_text_from' => 'custom',
                    'logo_type!'     => 'image',
                ),
            )
        );
        $this->_add_responsive_control(
            'kitify_widget_align',
            array(
                'label'   => esc_html__( 'Widget Align', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none'      => esc_html__( 'Inherit', 'kitify' ),
                    'left'      => esc_html__( 'Left', 'kitify' ),
                    'center'    => esc_html__( 'Center', 'kitify' ),
                    'right'     => esc_html__( 'Right', 'kitify' ),
                ),
                'prefix_class' => 'kitify-widget-align%s-',
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'kitify' ),
            )
        );

        $this->_add_control(
            'linked_logo',
            array(
                'label'        => esc_html__( 'Linked Logo', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->_add_control(
            'remove_link_on_front',
            array(
                'label'        => esc_html__( 'Remove Link on Front Page', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->_add_control(
            'logo_display',
            array(
                'type'        => 'select',
                'label'       => esc_html__( 'Display Logo Image and Text', 'kitify' ),
                'label_block' => true,
                'default'     => 'block',
                'options'     => array(
                    'inline' => esc_html__( 'Inline', 'kitify' ),
                    'block'  => esc_html__( 'Text Below Image', 'kitify' ),
                ),
                'condition' => array(
                    'logo_type' => 'both',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'logo_style',
            array(
                'label'      => esc_html__( 'Logo', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'logo_type'   => ['image', 'both']
                ]
            )
        );

        $this->add_responsive_control(
            'logo_width',
            [
                'label' => __( 'Logo Width', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-logo__link' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'logo_alignment',
            array(
                'label'   => esc_html__( 'Logo Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Start', 'kitify' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'End', 'kitify' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-logo' => 'justify-content: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'vertical_logo_alignment',
            array(
                'label'       => esc_html__( 'Image and Text Vertical Alignment', 'kitify' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'label_block' => true,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'kitify' ),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Middle', 'kitify' ),
                        'icon' => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'kitify' ),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                    'baseline' => array(
                        'title' => esc_html__( 'Baseline', 'kitify' ),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-logo__link' => 'align-items: {{VALUE}}',
                ),
                'condition' => array(
                    'logo_type'    => 'both',
                    'logo_display' => 'inline',
                ),
            ),
            25
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'text_logo_style',
            array(
                'label'      => esc_html__( 'Text', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'logo_type'   => ['text', 'both']
                ]
            )
        );

        $this->_add_control(
            'text_logo_color',
            array(
                'label'     => esc_html__( 'Color', 'kitify' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-logo-lightext' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_logo_typography',
                'selector' => '{{WRAPPER}} .kitify-logo-lightext',
            ),
            50
        );

        $this->_add_control(
            'text_logo_gap',
            array(
                'label'      => esc_html__( 'Gap', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'default'    => array(
                    'size' => 5,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .kitify-logo-display-block .kitify-logo__img'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .kitify-logo-display-inline .kitify-logo__img' => 'margin-right: {{SIZE}}{{UNIT}}',
                ),
                'condition'  => array(
                    'logo_type' => 'both',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'text_logo_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-logo-lightext' => 'text-align: {{VALUE}}',
                ),
                'condition' => array(
                    'logo_type'    => 'both',
                    'logo_display' => 'block',
                ),
            ),
            50
        );

        $this->_end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

    /**
     * Check if logo is linked
     * @return [type] [description]
     */
    public function _is_linked() {

        $settings = $this->get_settings();

        if ( empty( $settings['linked_logo'] ) ) {
            return false;
        }

        if ( 'true' === $settings['remove_link_on_front'] && is_front_page() ) {
            return false;
        }

        return true;

    }

    /**
     * Returns logo text
     *
     * @return string Text logo HTML markup.
     */
    public function _get_logo_text() {

        $settings    = $this->get_settings();
        $type        = isset( $settings['logo_type'] ) ? esc_attr( $settings['logo_type'] ) : 'text';
        $text_from   = isset( $settings['logo_text_from'] ) ? esc_attr( $settings['logo_text_from'] ) : 'site_name';
        $custom_text = isset( $settings['logo_text'] ) ? wp_kses_post( $settings['logo_text'] ) : '';

        if ( 'image' === $type ) {
            return;
        }

        if ( 'site_name' === $text_from ) {
            $text = get_bloginfo( 'name' );
        } else {
            $text = $custom_text;
        }

        $format = apply_filters(
            'kitify/logo/text-foramt',
            '<div class="kitify-logo-lightext">%s</div>'
        );

        return sprintf( $format, $text );
    }

    /**
     * Returns logo classes string
     *
     * @return string
     */
    public function _get_logo_classes() {

        $settings = $this->get_settings();

        $classes = array(
            'kitify-logo',
            'kitify-logo-type-' . $settings['logo_type'],
            'kitify-logo-display-' . $settings['logo_display'],
        );

        return implode( ' ', $classes );
    }

    /**
     * Returns logo image
     *
     * @return string Image logo HTML markup.
     */
    public function _get_logo_image() {

        $settings = $this->get_settings();
        $type     = isset( $settings['logo_type'] ) ? esc_attr( $settings['logo_type'] ) : 'text';
        $image    = isset( $settings['logo_image'] ) ? $settings['logo_image'] : false;
        $image_4l = isset( $settings['logo_image_4l'] ) ? $settings['logo_image_4l'] : false;

        if ( 'text' === $type || ! $image ) {
            return;
        }

        $image_src    = $this->_get_logo_image_src( $image );
        $image_4l_src = $this->_get_logo_image_src( $image_4l );

        $image_src = apply_filters('kitify/logo/attr/src', $image_src);
        $image_4l_src = apply_filters('kitify/logo/attr/src4l', $image_4l_src);

        if ( empty( $image_src ) && empty( $image_4l_src ) ) {
            return;
        }

        if(empty($image_4l_src)){
            $image_4l_src = $image_src;
        }

        $format = apply_filters(
            'kitify/logo/image-format',
            '<img src="%1$s" class="kitify-logo__img kitify-logo-default" alt="%2$s"%3$s>'
        );
        $format2 = apply_filters(
            'kitify/logo/image-format2',
            '<img src="%1$s" class="kitify-logo__img kitify-logo-light" alt="%2$s"%3$s>'
        );

        $image_data = ! empty( $image['id'] ) ? wp_get_attachment_image_src( $image['id'], 'full' ) : array();
        $width      = isset( $image_data[1] ) ? $image_data[1] : false;
        $height     = isset( $image_data[2] ) ? $image_data[2] : false;

        $width      = apply_filters('kitify/logo/attr/width', $width);
        $height      = apply_filters('kitify/logo/attr/height', $height);

        $attrs = sprintf(
            '%1$s%2$s%3$s',
            $width ? ' width="' . $width . '"' : '',
            $height ? ' height="' . $height . '"' : '',
            ''
        );

        $logo1 = sprintf( $format, esc_url( $image_src ), get_bloginfo( 'name' ), $attrs );
        $logo2 = sprintf( $format2, esc_url( $image_4l_src ), get_bloginfo( 'name' ), $attrs );

        return $logo1 . $logo2;
    }

    public function _get_logo_image_src( $args = array() ) {

        if ( ! empty( $args['id'] ) ) {
            $img_data = wp_get_attachment_image_src( $args['id'], 'full' );

            return ! empty( $img_data[0] ) ? $img_data[0] : false;
        }

        if ( ! empty( $args['url'] ) ) {
            return $args['url'];
        }

        return false;
    }

}
