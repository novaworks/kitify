<?php
/**
 * Bsf Custom Fonts Admin Ui
 *
 * @since  1.0.0
 * @package Bsf_Custom_Fonts
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Custom_Typekit_Fonts_Render' ) ) :

	/**
	 * Custom_Typekit_Fonts_Render
	 */
	class Custom_Typekit_Fonts_Render {

		const TYPEKIT_EMBED_BASE = 'https://use.typekit.net/%s.css';

		/**
		 * Instance of Custom_Typekit_Fonts_Render
		 *
		 * @since  1.0.0
		 * @var (Object) Custom_Typekit_Fonts_Render
		 */
		private static $instance = null;

		/**
		 * Member Varible
		 *
		 * @var string $font_css
		 */
		protected $font_css;

		/**
		 * Font base.
		 *
		 * This is used in case of Elementor's Font param
		 *
		 * @since  1.0.4
		 * @var string
		 */
		private static $font_base = 'kitify-typekit-fonts';

		/**
		 * Instance of Bsf_Custom_Fonts_Admin.
		 *
		 * @since  1.0.0
		 *
		 * @return object Class object.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'typekit_embed_css' ) );
			// Elementor page builder.
			add_filter( 'elementor/fonts/groups', array( $this, 'elementor_group' ) );
			add_filter( 'elementor/fonts/additional_fonts', array( $this, 'add_elementor_fonts' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'typekit_embed_css' ) );
		}

		/**
		 * Add Custom Font group to elementor font list.
		 *
		 * Group name "Custom" is added as the first element in the array.
		 *
		 * @since  1.0.4
		 * @param  Array $font_groups default font groups in elementor.
		 * @return Array              Modified font groups with newly added font group.
		 */
		public function elementor_group( $font_groups ) {
			$new_group[ self::$font_base ] = __( 'Typekit Fonts', 'kitify-typekit-fonts' );
			$font_groups                   = $new_group + $font_groups;
			return $font_groups;
		}

		/**
		 * Add Custom Fonts to the Elementor Page builder's font param.
		 *
		 * @since  1.0.4
		 * @param Array $fonts Custom Font's array.
		 */
		public function add_elementor_fonts( $fonts ) {

			$kit_list     = get_option( 'kitify-typekit-fonts' );
			if( ! empty($kit_list) ) {
					$all_fonts    = $kit_list['custom-typekit-font-details'];
			}
			$custom_fonts = array();
			if ( ! empty( $all_fonts ) ) {
				foreach ( $all_fonts as $font_family_name => $fonts_url ) {
					$font_slug                 = isset( $fonts_url['slug'] ) ? $fonts_url['slug'] : '';
					$font_css                  = isset( $fonts_url['css_names'][0] ) ? $fonts_url['css_names'][0] : $font_slug;
					$custom_fonts[ $font_css ] = self::$font_base;
				}
			}

			return array_merge( $fonts, $custom_fonts );
		}
		/**
		 * Add Custom Fonts to the Elementor Page builder's font param.
		 *
		 * @since  1.0.4
		 * @param Array $fonts Custom Font's array.
		 */
		public function add_kirki_fonts( ) {

			$kit_list     = get_option( 'kitify-typekit-fonts' );
			$all_fonts    = $kit_list['custom-typekit-font-details'];
			$custom_fonts = array();
			if ( ! empty( $all_fonts ) ) {
				foreach ( $all_fonts as $font_family_name => $fonts_url ) {
					$font_slug                 = isset( $fonts_url['slug'] ) ? $fonts_url['slug'] : '';
					$font_css                  = isset( $fonts_url['css_names'][0] ) ? $fonts_url['css_names'][0] : $font_slug;
					$custom_fonts[ $font_css ] = self::$font_base;
				}
			}
			$fonts["system-font"] = array(
			"label" => "Cerebri Sans",
			"stack" => "Cerebri Sans",
			);
			return $fonts;
			return array_merge( $fonts, $custom_fonts );
		}
		/**
		 * Enqueue Typekit CSS.
		 *
		 * @return void
		 */
		public function typekit_embed_css() {

			if ( false !== $this->get_typekit_embed_url() ) {
				wp_enqueue_style( 'custom-typekit-css', $this->get_typekit_embed_url(), array(), kitify()->get_version() );
			}

		}

		/**
		 * Get Typekit CSS embed URL
		 *
		 * @return String|Boolean If Kit ID is available the URL for typekit embed is returned.
		 */
		private function get_typekit_embed_url() {
			$kit_info = get_option( 'kitify-typekit-fonts' );
			if ( empty( $kit_info['custom-typekit-font-details'] ) ) {
				return false;
			}

			return sprintf( self::TYPEKIT_EMBED_BASE, $kit_info['custom-typekit-font-id'] );
		}
	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Custom_Typekit_Fonts_Render::get_instance();

endif;
