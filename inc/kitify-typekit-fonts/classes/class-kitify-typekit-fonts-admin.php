<?php
/**
 * Bsf Custom Fonts Admin Ui
 *
 * @since  1.0.0
 * @package Bsf_Custom_Fonts
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Custom_Typekit_Fonts_Admin' ) ) :

	/**
	 * Custom_Typekit_Fonts_Admin
	 */
	class Custom_Typekit_Fonts_Admin {

		/**
		 * Instance of Custom_Typekit_Fonts_Admin
		 *
		 * @since  1.0.0
		 * @var (Object) Custom_Typekit_Fonts_Admin
		 */
		private static $instance = null;

		/**
		 * Parent Menu Slug
		 *
		 * @since  1.0.0
		 * @var (string) $parent_menu_slug
		 */
		protected $parent_menu_slug = 'themes.php';

		/**
		 * Instance of Custom_Typekit_Fonts_Admin.
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
			add_action( 'admin_menu', array( $this, 'register_custom_fonts_menu' ), 101 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_notices', array( $this, 'set_kitify_typekit_fonts_notice' ) );

		}

		/**
		 * Set admin notice
		 *
		 * @since 1.0.0
		 */
		public function set_kitify_typekit_fonts_notice() {

			// Notice for the Kitify Typekit Fonts action.
			if ( isset( $_POST['kitify-typekit-fonts-nonce'] ) && wp_verify_nonce( $_POST['kitify-typekit-fonts-nonce'], 'kitify-typekit-fonts' ) ) {

				if ( isset( $_POST['kitify-typekit-fonts-submitted'] ) ) {
					if ( sanitize_text_field( $_POST['kitify-typekit-fonts-submitted'] ) == 'submitted' && current_user_can( 'manage_options' ) ) {
						?>

						<?php
						// Kit ID is not valid.
						if ( isset( $_POST['custom-typekit-id-notice'] ) && $_POST['custom-typekit-id-notice'] ) {
							?>
							<div class="notice notice-error is-dismissible">
								<p><?php esc_html_e( 'Please enter the valid Project ID to get the kit details.', 'kitify-typekit-fonts' ); ?></p>
							</div>
							<?php
						} elseif ( isset( $_POST['custom-typekit-empty-notice'] ) && $_POST['custom-typekit-empty-notice'] ) {
							?>
							<div class="notice notice-warning is-dismissible">
								<p><?php esc_html_e( 'This Kit is empty. Please add some fonts in it.', 'kitify-typekit-fonts' ); ?></p>
							</div>
							<?php } else { ?>
							<div class="notice notice-success is-dismissible">
								<p><?php esc_html_e( 'Kitify Typekit Fonts settings have been successfully saved.', 'kitify-typekit-fonts' ); ?></p>
							</div>
							<?php
							}
					}
				}
			}
		}

		/**
		 * Register custom font menu
		 *
		 * @since 1.0.0
		 */
		public function register_custom_fonts_menu() {

			$title = apply_filters( 'kitify_typekit_fonts_menu_title', __( 'Adobe Fonts', 'kitify-typekit-fonts' ) );

			add_submenu_page(
				'kitify-dashboard-settings-page',
				$title,
				$title,
				'edit_theme_options',
				'kitify-typekit-fonts',
				array( $this, 'typekit_options_page' )
			);
		}

		/**
		 * Typekit Custom Fonts Setting page
		 *
		 * @since 1.0.0
		 */
		public function typekit_options_page() {

			require_once KITIFY_TYPEKIT_FONTS_DIR . 'templates/kitify-typekit-fonts-options.php';
		}

		/**
		 * Enqueue Admin Scripts
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {

			if ( 'kitify_page_kitify-typekit-fonts' !== get_current_screen()->id ) {
				return;
			}

			wp_enqueue_style( 'kitify-typekit-fonts-css', KITIFY_TYPEKIT_FONTS_URI . 'assets/css/kitify-typekit-fonts.css', array(), kitify()->get_version() );
			wp_enqueue_script( 'kitify-typekit-fonts-js', KITIFY_TYPEKIT_FONTS_URI . 'assets/js/kitify-typekit-fonts.js', array( 'jquery-ui-tooltip' ), kitify()->get_version(), false );

		}

	}


	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Custom_Typekit_Fonts_Admin::get_instance();

endif;
