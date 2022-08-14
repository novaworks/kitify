<?php
namespace KitifyThemeBuilder\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use KitifyThemeBuilder\Modules\DynamicTags\Tags\Base\Data_Tag;
use KitifyThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Author_URL extends Data_Tag {

	public function get_name() {
		return 'author-url';
	}

	public function get_group() {
		return Module::AUTHOR_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_title() {
		return esc_html__( 'Author URL', 'kitify' );
	}

	public function get_panel_template_setting_key() {
		return 'url';
	}

	public function get_value( array $options = [] ) {
		$value = '';

		if ( 'archive' === $this->get_settings( 'url' ) ) {
			global $authordata;

			if ( $authordata ) {
				$value = get_author_posts_url( $authordata->ID, $authordata->user_nicename );
			}
		} else {
			$value = get_the_author_meta( 'url' );
		}

		return $value;
	}

	protected function register_controls() {
		$this->add_control(
			'url',
			[
				'label' => esc_html__( 'URL', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'archive',
				'options' => [
					'archive' => esc_html__( 'Author Archive', 'kitify' ),
					'website' => esc_html__( 'Author Website', 'kitify' ),
				],
			]
		);
	}
}
