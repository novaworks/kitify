<?php
namespace KitifyThemeBuilder\Modules\DynamicTags\Pods\Tags;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pods_Date extends Pods_Base {

	public function get_name() {
		return 'pods-date';
	}

	public function get_title() {
		return esc_html__( 'Pods', 'kitify' ) . ' ' . esc_html__( 'Date Field', 'kitify' );
	}

	public function render() {
		$field_data = $this->get_field();
		$field = $field_data['field'];
		$value = empty( $field_data['value'] ) ? '' : $field_data['value'];

		if ( $field && ! empty( $field['type'] ) && in_array( $field['type'], [ 'date', 'datetime' ] ) ) {

			$format = $this->get_settings( 'format' );

			$timestamp = strtotime( $value );

			if ( 'human' === $format ) {
				$value = human_time_diff( $timestamp );
			} else {
				switch ( $format ) {
					case 'default':
						$date_format = get_option( 'date_format' );
						break;
					case 'custom':
						$date_format = $this->get_settings( 'custom_format' );
						break;
					default:
						$date_format = $format;
						break;
				}

				$value = gmdate( $date_format, $timestamp );
			}
		}
		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function register_controls() {
		parent::register_controls();

		$this->add_control(
			'format',
			[
				'label' => esc_html__( 'Format', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'kitify' ),
					'F j, Y' => gmdate( 'F j, Y' ),
					'Y-m-d' => gmdate( 'Y-m-d' ),
					'm/d/Y' => gmdate( 'm/d/Y' ),
					'd/m/Y' => gmdate( 'd/m/Y' ),
					'human' => esc_html__( 'Human Readable', 'kitify' ),
					'custom' => esc_html__( 'Custom', 'kitify' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => esc_html__( 'Custom Format', 'kitify' ),
				'default' => '',
				'description' => sprintf( '<a href="https://go.elementor.com/wordpress-date-time/" target="_blank">%s</a>', esc_html__( 'Documentation on date and time formatting', 'kitify' ) ),
				'condition' => [
					'format' => 'custom',
				],
			]
		);
	}

	protected function get_supported_fields() {
		return [
			'datetime',
			'date',
		];
	}
}
