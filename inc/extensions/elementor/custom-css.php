<?php

namespace KitifyExtensions\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Custom_CSS {

	public function __construct() {
		add_action('elementor/element/after_section_end', [ $this, 'add_css_control' ], 10, 2);
		add_action('elementor/element/parse_css', [ $this, 'parse_css_widget' ], 10, 2);
		add_action('elementor/css-file/post/parse', [ $this, 'parse_css_document' ], 10, 1);
		add_action( 'elementor/element/after_section_end', [ $this, 'register_attribute_controls' ], 10, 2 );
		add_action( 'elementor/element/after_add_attributes', [ $this, 'render_attributes' ] );
	}

	public function add_css_control( $controls_stack, $section_id ){

		if ( 'section_custom_css_pro' !== $section_id || defined('ELEMENTOR_PRO_VERSION') || defined('LASTUDIO_VERSION')) {
			return;
		}
		$old_section = \Elementor\Plugin::instance()->controls_manager->get_control_from_stack( $controls_stack->get_unique_name(), 'section_custom_css_pro' );
		\Elementor\Plugin::instance()->controls_manager->remove_control_from_stack( $controls_stack->get_unique_name(), [ 'section_custom_css_pro', 'custom_css_pro' ] );

		$controls_stack->start_controls_section(
			'section_custom_css',
			[
				'label' => __( 'Custom CSS', 'kitify' ),
				'tab' => $old_section['tab'],
			]
		);

		$controls_stack->add_control(
			'custom_css',
			[
				'type' => \Elementor\Controls_Manager::CODE,
				'label' => __( 'Add your own custom CSS here', 'kitify' ),
				'language' => 'css',
				'description' => __( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'kitify' ),
				'render_type' => 'ui',
				'separator' => 'none'
			]
		);

		$controls_stack->end_controls_section();
	}

	public function parse_css_widget( $post_css, $element ){
		if(defined('ELEMENTOR_PRO_VERSION') || defined('LASTUDIO_VERSION')){
			return;
		}
		if ( $post_css instanceof \Elementor\Core\DynamicTags\Dynamic_CSS) {
			return;
		}
		$element_settings = $element->get_settings();

		if ( empty( $element_settings['custom_css'] ) ) {
			return;
		}

		$css = trim( $element_settings['custom_css'] );

		if ( empty( $css ) ) {
			return;
		}
		$css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

		// Add a css comment
		$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $css );
	}

	public function parse_css_document( $post_css ){
		if(defined('ELEMENTOR_PRO_VERSION') || defined('LASTUDIO_VERSION')){
			return;
		}

		$document = \Elementor\Plugin::instance()->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'custom_css' );

		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	public function register_attribute_controls( $element, $section_id ){
		if ( ! $element instanceof \Elementor\Element_Base ) {
			return;
		}

		if ( 'section_custom_attributes_pro' !== $section_id || defined('ELEMENTOR_PRO_VERSION') ) {
			return;
		}

		$old_section = \Elementor\Plugin::instance()->controls_manager->get_control_from_stack( $element->get_unique_name(), 'section_custom_attributes_pro' );
		\Elementor\Plugin::instance()->controls_manager->remove_control_from_stack( $element->get_unique_name(), [ 'section_custom_attributes_pro', 'custom_attributes_pro' ] );

		$element->start_controls_section(
			'_section_attributes',
			[
				'label' => __( 'Attributes', 'kitify' ),
				'tab' => $old_section['tab'],
			]
		);

		$element->add_control(
			'_attributes',
			[
				'label' => __( 'Custom Attributes', 'kitify' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'key|value', 'kitify' ),
				'description' => sprintf( __( 'Set custom attributes for the wrapper element. Each attribute in a separate line. Separate attribute key from the value using %s character.', 'kitify' ), '<code>|</code>' ),
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$element->end_controls_section();
	}

	public function render_attributes( $element ) {
		$settings = $element->get_settings_for_display();

		if ( ! empty( $settings['_attributes'] ) ) {
			$attributes = \Elementor\Utils::parse_custom_attributes( $settings['_attributes'], "\n" );

			$black_list = [ 'id', 'class', 'data-id', 'data-settings', 'data-element_type', 'data-widget_type', 'data-model-cid' ];

			foreach ( $attributes as $attribute => $value ) {
				if ( ! in_array( $attribute, $black_list, true ) ) {
					$element->add_render_attribute( '_wrapper', $attribute, $value );
				}
			}
		}

	}

}
