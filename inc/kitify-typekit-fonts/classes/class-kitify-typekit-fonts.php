<?php
/**
 * Typekit Custom fonts - Init
 *
 * @package Custom_Typekit_Fonts
 */

if ( ! class_exists( 'Custom_Typekit_Fonts' ) ) {

	/**
	 * Typekit Custom fonts
	 *
	 * @since 1.0.0
	 */
	class Custom_Typekit_Fonts {

		/**
		 * Member Varible
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {
			require_once KITIFY_TYPEKIT_FONTS_DIR . 'classes/class-kitify-typekit-fonts-admin.php';
			require_once KITIFY_TYPEKIT_FONTS_DIR . 'classes/class-kitify-typekit-fonts-render.php';

			add_action( 'init', array( $this, 'options_setting' ) );

			$this->load_files();
		}

		/**
		 * Update the custom-typekit-font option
		 *
		 * @since 1.0.0
		 */
		public function options_setting() {

			if ( isset( $_POST['kitify-typekit-fonts-nonce'] ) && wp_verify_nonce( $_POST['kitify-typekit-fonts-nonce'], 'kitify-typekit-fonts' ) ) {

				if ( isset( $_POST['kitify-typekit-fonts-submitted'] ) ) {
					if ( sanitize_text_field( $_POST['kitify-typekit-fonts-submitted'] ) == 'submitted' ) {

						$option                                = array();
						$option['custom-typekit-font-id']      = sanitize_text_field( $_POST['custom-typekit-font-id'] );
						$option['custom-typekit-font-details'] = $this->get_custom_typekit_details( $option['custom-typekit-font-id'] );

						if ( empty( $option['custom-typekit-font-details'] ) ) {
							$_POST['custom-typekit-empty-notice'] = true;
						}

						update_option( 'kitify-typekit-fonts', $option );

					}
				}
			}
		}

		/**
		 * Get the Kit details usign wp_remote_get.
		 *
		 * @since 1.0.0
		 *
		 * @param string $kit_id Typekit ID.
		 */
		public function get_custom_typekit_details( $kit_id ) {

			$typekit_info = array();
			$typekit_uri  = 'https://typekit.com/api/v1/json/kits/' . $kit_id . '/published';
			$response     = wp_remote_get(
				$typekit_uri,
				array(
					'timeout' => '30',
				)
			);

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				$_POST['custom-typekit-id-notice'] = true;
				return $typekit_info;
			}

			$data     = json_decode( wp_remote_retrieve_body( $response ), true );
			$families = $data['kit']['families'];

			foreach ( $families as $family ) {

				$family_name = str_replace( ' ', '-', $family['name'] );

				$typekit_info[ $family_name ] = array(
					'family'   => $family_name,
					'fallback' => str_replace( '"', '', $family['css_stack'] ),
					'weights'  => array(),
				);

				foreach ( $family['variations'] as $variation ) {

					$variations = str_split( $variation );

					switch ( $variations[0] ) {
						case 'n':
							$style = 'normal';
							break;
						default:
							$style = 'normal';
							break;
					}

					$weight = $variations[1] . '00';

					if ( ! in_array( $weight, $typekit_info[ $family_name ]['weights'] ) ) {
						$typekit_info[ $family_name ]['weights'][] = $weight;
					}
				}

				$typekit_info[ $family_name ]['slug']      = $family['slug'];
				$typekit_info[ $family_name ]['css_names'] = $family['css_names'];
			}

			return $typekit_info;
		}

		/**
		 * Loads classes and includes.
		 *
		 * @since 1.0.2
		 * @return void
		 */
		private function load_files() {

			/* Classes */

			require_once KITIFY_TYPEKIT_FONTS_DIR . 'classes/class-typekit-fonts-white-label.php';
		}

	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Custom_Typekit_Fonts::get_instance();
}
