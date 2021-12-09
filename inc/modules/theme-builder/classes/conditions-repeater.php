<?php
namespace KitifyThemeBuilder\Modules\ThemeBuilder\Classes;

use Elementor\Control_Repeater;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Conditions_Repeater extends Control_Repeater {

	const CONTROL_TYPE = 'conditions_repeater';

	public function get_type() {
		return self::CONTROL_TYPE;
	}

	protected function get_default_settings() {
		return array_merge( parent::get_default_settings(), [
			'render_type' => 'none',
			'fields' => [
				[
					'name' => 'type',
					'type' => Controls_Manager::SELECT,
					'default' => 'include',
					'options' => [
						'include' => __( 'Include', 'kitify' ),
						'exclude' => __( 'Exclude', 'kitify' ),
					],
				],
				[
					'name' => 'name',
					'type' => Controls_Manager::SELECT,
					'default' => 'general',
					'groups' => [
						[
							'label' => __( 'General', 'kitify' ),
							'options' => [],
						],
					],
				],
				[
					'name' => 'sub_name',
					'type' => Controls_Manager::SELECT,
					'options' => [
						'' => __( 'All', 'kitify' ),
					],
					'conditions' => [
						'terms' => [
							[
								'name' => 'name',
								'operator' => '!==',
								'value' => '',
							],
						],
					],
				],
				[
					'name' => 'sub_id',
					'type' => Controls_Manager::SELECT,
					'options' => [
						'' => __( 'All', 'kitify' ),
					],
					'conditions' => [
						'terms' => [
							[
								'name' => 'sub_name',
								'operator' => '!==',
								'value' => '',
							],
						],
					],
				],
			],
		] );
	}
}
