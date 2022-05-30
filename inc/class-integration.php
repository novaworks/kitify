<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kitify_Integration' ) ) {

	/**
	 * Define Kitify_Integration class
	 */
	class Kitify_Integration {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Check if processing elementor widget
		 *
		 * @var boolean
		 */
		private $is_elementor_ajax = false;

        public $sys_messages = [];

		/**
		 * Initalize integration hooks
		 *
		 * @return void
		 */
		public function init() {

			add_action( 'elementor/init', array( $this, 'register_category' ) );

			add_action( 'elementor/widgets/register', array( $this, 'register_addons' ), 10 );

			add_action( 'elementor/widgets/register', array( $this, 'register_vendor_addons' ), 20 );

			add_action( 'elementor/controls/controls_registered', array( $this, 'rewrite_controls' ), 10 );

			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );

			add_action( 'wp_ajax_elementor_render_widget', array( $this, 'set_elementor_ajax' ), 10, -1 );

            add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );

            add_action( 'elementor/editor/after_enqueue_styles',   array( $this, 'editor_styles' ) );

            add_filter( 'elementor/controls/animations/additional_animations', array( $this, 'register_custom_animation' ) );

            // WPML compatibility
            if ( defined( 'WPML_ST_VERSION' ) ) {
                add_filter( 'kitify/themecore/get_location_templates/template_id', array( $this, 'set_wpml_translated_location_id' ) );
            }

            // Polylang compatibility
            if ( class_exists( 'Polylang' ) ) {
                add_filter( 'kitify/themecore/get_location_templates/template_id', array( $this, 'set_pll_translated_location_id' ) );
            }

            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragments' ) );

            add_action( 'init', array( $this, 'register_handler' ) );
            add_action( 'init', array( $this, 'login_handler' ) );

            add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );

            $this->sys_messages = apply_filters( 'kitify/popups_sys_messages', array(
                'invalid_mail'                => esc_html__( 'Please, provide valid mail', 'kitify' ),
                'mailchimp'                   => esc_html__( 'Please, set up MailChimp API key and List ID', 'kitify' ),
                'internal'                    => esc_html__( 'Internal error. Please, try again later', 'kitify' ),
                'server_error'                => esc_html__( 'Server error. Please, try again later', 'kitify' ),
                'invalid_nonce'               => esc_html__( 'Invalid nonce. Please, try again later', 'kitify' ),
                'subscribe_success'           => esc_html__( 'Success', 'kitify' ),
            ) );

            add_action( 'wp_ajax_kitify_elementor_subscribe_form_ajax', [ $this, 'subscribe_form_ajax' ] );
            add_action( 'wp_ajax_nopriv_kitify_elementor_subscribe_form_ajax', [ $this, 'subscribe_form_ajax' ] );

			// Init Elementor Extension module
			$ext_module_data = kitify()->module_loader->get_included_module_data( 'elementor-extension.php' );

            Kitify_Extension\Module::get_instance(
				array(
					'path' => $ext_module_data['path'],
					'url'  => $ext_module_data['url'],
				)
			);

            // Set default single post template
            add_filter( 'get_post_metadata', array( $this, 'override_single_post_template' ), 10, 4 );
            add_filter( 'get_post_metadata', array( $this, 'override_single_page_template' ), 10, 4 );

            // Init Mega Menu module
            if( kitify()->get_theme_support('elementor::mega-menu') ){
                $mega_menu = kitify()->module_loader->get_included_module_data( 'mega-menu.php' );
                Kitify_MegaMenu::get_instance([
                    'path' => $mega_menu['path'],
                    'url'  => $mega_menu['url'],
                ]);
            }

            add_action( 'init', [ $this, 'register_portfolio_content_type' ] );
		}

		/**
		 * Set $this->is_elementor_ajax to true on Elementor AJAX processing
		 *
		 * @return  void
		 */
		public function set_elementor_ajax() {
			$this->is_elementor_ajax = true;
		}

		/**
		 * Check if we currently in Elementor mode
		 *
		 * @return void
		 */
		public function in_elementor() {

            $result = false;

            if ( wp_doing_ajax() ) {
                $result = ( isset( $_REQUEST['action'] ) && 'elementor_ajax' === $_REQUEST['action'] );
            } elseif ( Elementor\Plugin::instance()->editor->is_edit_mode()
                && 'wp_enqueue_scripts' === current_filter() ) {
                $result = true;
            } elseif ( Elementor\Plugin::instance()->preview->is_preview_mode() && 'wp_enqueue_scripts' === current_filter() ) {
                $result = true;
            }

			/**
			 * Allow to filter result before return
			 *
			 * @var bool $result
			 */
			return apply_filters( 'kitify/in-elementor', $result );
		}

		/**
		 * Register plugin addons
		 *
		 * @param  object $widgets_manager Elementor widgets manager instance.
		 * @return void
		 */
		public function register_addons( $widgets_manager ) {

			$avaliable_widgets = kitify_settings()->get( 'avaliable_widgets' );

			require kitify()->plugin_path( 'inc/base/class-widget-base.php' );

			foreach ( glob( kitify()->plugin_path( 'inc/addons/' ) . '*.php' ) as $file ) {
				$slug = basename( $file, '.php' );

				$enabled = isset( $avaliable_widgets[ $slug ] ) ? $avaliable_widgets[ $slug ] : false;

				if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $avaliable_widgets ) {
					$this->register_addon( $file, $widgets_manager );
				}
			}
		}

		/**
		 * Register vendor addons
		 *
		 * @param  object $widgets_manager Elementor widgets manager instance.
		 * @return void
		 */
		public function register_vendor_addons( $widgets_manager ) {

            $woo_conditional = array(
                'cb'  => 'class_exists',
                'arg' => 'WooCommerce',
            );

            $allowed_vendors = apply_filters(
                'kitify/allowed-vendor-widgets',
                array(
                    'woo_products' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-products.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_categories' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-categories.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_menu_cart' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-menu-cart.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'nova_menu_cart' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/nova-menu-cart.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_menu_account ' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-menu-account.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_pages' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-pages.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_add_to_cart' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-add-to-cart.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_title' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-title.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_images' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-images.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_price' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-price.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_addtocart' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-addtocart.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_rating' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-rating.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_stock' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-stock.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_meta' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-meta.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_shortdescription' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-shortdescription.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_content' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-content.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_datatabs' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-datatabs.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'woo_single_product_additional_information' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/woo-single-product-additional-information.php'
                        ),
                        'conditional' => $woo_conditional,
                    ),
                    'contact_form7' => array(
                        'file' => kitify()->plugin_path(
                            'inc/addons/vendor/contact-form7.php'
                        ),
                        'conditional' => [
                            'cb'  => 'class_exists',
                            'arg' => 'WPCF7',
                        ],
                    ),
                )
            );

            foreach ( $allowed_vendors as $vendor ) {
                if ( is_callable( $vendor['conditional']['cb'] )
                    && true === call_user_func( $vendor['conditional']['cb'], $vendor['conditional']['arg'] ) ) {
                    $this->register_addon( $vendor['file'], $widgets_manager );
                }
            }

		}

		/**
		 * Rewrite core controls.
		 *
		 * @param  object $controls_manager Controls manager instance.
		 * @return void
		 */
		public function rewrite_controls( $controls_manager ) {

		}

		/**
		 * Add new controls.
		 *
		 * @param  object $controls_manager Controls manager instance.
		 * @return void
		 */
		public function add_controls( $controls_manager ) {

		}

		/**
		 * Include control file by class name.
		 *
		 * @param  [type] $class_name [description]
		 * @return [type]             [description]
		 */
		public function include_control( $class_name, $grouped = false ) {

			$filename = sprintf(
				'inc/controls/%2$sclass-%1$s.php',
				str_replace( '_', '-', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( kitify()->plugin_path( $filename ) ) ) {
				return false;
			}

			require kitify()->plugin_path( $filename );

			return true;
		}

		/**
		 * Register addon by file name
		 *
		 * @param  string $file            File name.
		 * @param  object $widgets_manager Widgets manager instance.
		 * @return void
		 */
		public function register_addon( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
            $class = 'Kitify_' . $class;
			$class = sprintf( 'Elementor\%s', $class );

			require_once $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register_widget_type( new $class );
			}
		}

		/**
		 * Register cherry category for elementor if not exists
		 *
		 * @return void
		 */
		public function register_category() {

            Elementor\Plugin::instance()->elements_manager->add_category(
                'kitify-builder',
                array(
                    'title' => esc_html__( 'Kitify Builder', 'kitify' ),
                    'icon'  => 'font',
                )
            );

            Elementor\Plugin::instance()->elements_manager->add_category(
				'kitify',
				array(
					'title' => esc_html__( 'Kitify', 'kitify' ),
					'icon'  => 'font',
				)
			);

            Elementor\Plugin::instance()->elements_manager->add_category(
				'kitify-woocommerce',
				array(
					'title' => esc_html__( 'Kitify WooCommerce', 'kitify' ),
					'icon'  => 'font',
				)
			);

            Elementor\Plugin::instance()->elements_manager->add_category(
				'kitify-woo-product',
				array(
					'title' => esc_html__( 'Kitify Product', 'kitify' ),
					'icon'  => 'font',
				)
			);

		}

        /**
         * Enqueue plugin scripts only with elementor scripts
         *
         * @return void
         */
        public function editor_scripts() {

            wp_enqueue_script(
                'kitify-editor',
                kitify()->plugin_url( 'assets/js/kitify-editor.js' ),
                array( 'jquery' ),
                kitify()->get_version(),
                true
            );
        }

        /**
         * Enqueue editor styles
         *
         * @return void
         */
        public function editor_styles() {

            wp_enqueue_style(
                'kitify-editor',
                kitify()->plugin_url( 'assets/css/kitify-editor.css' ),
                array(),
                kitify()->get_version()
            );

        }

        public function frontend_enqueue(){

            wp_register_style( 'kitify-base', kitify()->plugin_url('assets/css/kitify-base.css'), [], kitify()->get_version());
            wp_register_script(  'kitify-base' , kitify()->plugin_url('assets/js/kitify-base.js') , [ 'elementor-frontend' ],  kitify()->get_version() , true );
            wp_register_script(  'kitify-header-vertical' , kitify()->plugin_url('assets/js/addons/header-sidebar.js') , [ 'elementor-frontend' ],  kitify()->get_version() , true );

            wp_register_script(  'jquery-isotope' , kitify()->plugin_url('assets/js/lib/isotope.pkgd.min.js') , ['imagesloaded'],  kitify()->get_version() , true );

            $polyfill_data = apply_filters('kitify/filter/js_polyfill_data', [
                'kitify-polyfill-resizeobserver' => [
                    'condition' => '\'ResizeObserver\' in window',
                    'src'       => kitify()->plugin_url( 'assets/js/lib/polyfill-resizeobserver.min.js' ),
                    'version'   => '1.5.0',
                ],
            ]);

            $polyfill_inline = kitify_helper()->get_polyfill_inline( $polyfill_data );

            wp_add_inline_script('kitify-header-vertical', $polyfill_inline, 'before');

            wp_register_style( 'kitify-woocommerce', kitify()->plugin_url('assets/css/kitify-woocommerce.css'), [], kitify()->get_version());

            $rest_api_url = apply_filters( 'kitify/rest/frontend/url', get_rest_url() );

            $template_cache = true;
            $devMode = true;

            wp_localize_script('kitify-base', 'KitifySettings', [
                'templateApiUrl' => $rest_api_url . 'kitify-api/v1/elementor-template',
                'widgetApiUrl'   => $rest_api_url . 'kitify-api/v1/elementor-widget',
                'homeURL'        => esc_url(home_url('/')),
                'ajaxurl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
                'isMobile'       => filter_var( wp_is_mobile(), FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false',
                'devMode'        => defined('WP_DEBUG') && WP_DEBUG ? 'true' : 'false',
                'cache_ttl'      => apply_filters('kitify/cache-management/time-to-life', !$template_cache ? 30 : (60 * 5)),
                'local_ttl'      => apply_filters('kitify/cache-management/local-time-to-life', !$template_cache ? 30 : (60 * 60 * 24)),
                'themeName'      => get_template(),
                'i18n'           => [ ]
            ]);
            if( apply_filters( 'kitify/allow_override_elementor_device', true ) ){
                wp_add_inline_style('elementor-frontend', $this->set_device_name_for_custom_bkp_by_css());
            }
            if(!kitify()->get_theme_support('kitify')){
                wp_add_inline_script('wc-single-product', $this->product_image_flexslider_vars(), 'before');
            }

            wp_add_inline_style('elementor-frontend', $this->add_new_animation_css());

            $subscribe_obj = [
                'action' => 'kitify_elementor_subscribe_form_ajax',
                'nonce' => wp_create_nonce('kitify_elementor_subscribe_form_ajax'),
                'type' => 'POST',
                'data_type' => 'json',
                'is_public' => 'true',
                'ajax_url' => admin_url('admin-ajax.php'),
                'sys_messages' => $this->sys_messages
            ];
            wp_localize_script( 'elementor-frontend', 'kitifySubscribeConfig', $subscribe_obj );
            wp_localize_script( 'kitify-subscribe-form', 'kitifySubscribeConfig', $subscribe_obj );
        }

        /**
         * Set WPML translated location.
         *
         * @param $post_id
         *
         * @return mixed|void
         */
        public function set_wpml_translated_location_id( $post_id ) {
            $location_type = get_post_type( $post_id );

            return apply_filters( 'wpml_object_id', $post_id, $location_type, true );
        }

        /**
         * set_pll_translated_location_id
         *
         * @param $post_id
         *
         * @return false|int|null
         */
        public function set_pll_translated_location_id( $post_id ) {

            if ( function_exists( 'pll_get_post' ) ) {

                $translation_post_id = pll_get_post( $post_id );

                if ( null === $translation_post_id ) {
                    // the current language is not defined yet
                    return $post_id;
                } elseif ( false === $translation_post_id ) {
                    //no translation yet
                    return $post_id;
                } elseif ( $translation_post_id > 0 ) {
                    // return translated post id
                    return $translation_post_id;
                }
            }

            return $post_id;
        }

        /**
         * Cart link fragments
         *
         * @return array
         */
        public function cart_link_fragments( $fragments ) {

            global $woocommerce;

            $kitify_fragments = apply_filters( 'kitify/handlers/cart-fragments', array(
                '.kitify-cart__total-val' => 'menucart/global/cart-totals.php',
                '.kitify-cart__count-val' => 'menucart/global/cart-count.php',
            ) );

            foreach ( $kitify_fragments as $selector => $template ) {
                ob_start();
                include kitify()->get_template( $template );
                $fragments[ $selector ] = ob_get_clean();
            }

            return $fragments;

        }


        /**
         * Login form handler.
         *
         * @return void
         */
        public function login_handler() {

            if ( ! isset( $_POST['kitify_login'] ) ) {
                return;
            }

            try {

                if ( empty( $_POST['log'] ) ) {

                    $error = sprintf(
                        '<strong>%1$s</strong>: %2$s',
                        __( 'ERROR', 'kitify' ),
                        __( 'The username field is empty.', 'kitify' )
                    );

                    throw new Exception( $error );

                }

                $signon = wp_signon();

                if ( is_wp_error( $signon ) ) {
                    throw new Exception( $signon->get_error_message() );
                }

                $redirect = isset( $_POST['redirect_to'] )
                    ? esc_url( $_POST['redirect_to'] )
                    : esc_url( home_url( '/' ) );

                wp_redirect( $redirect );
                exit;

            } catch ( Exception $e ) {
                wp_cache_set( 'kitify-login-messages', $e->getMessage() );
            }

        }

        /**
         * Registration handler
         *
         * @return void
         */
        public function register_handler() {

            if ( ! isset( $_POST['kitify-register-nonce'] ) ) {
                return;
            }

            if ( ! wp_verify_nonce( $_POST['kitify-register-nonce'], 'kitify-register' ) ) {
                return;
            }

            try {

                $username           = isset( $_POST['username'] ) ? $_POST['username'] : '';
                $password           = isset( $_POST['password'] ) ? $_POST['password'] : '';
                $email              = isset( $_POST['email'] ) ? $_POST['email'] : '';
                $confirm_password   = isset( $_POST['kitify_confirm_password'] ) ? $_POST['kitify_confirm_password'] : '';
                $confirmed_password = isset( $_POST['password-confirm'] ) ? $_POST['password-confirm'] : '';
                $confirm_password   = filter_var( $confirm_password, FILTER_VALIDATE_BOOLEAN );

                if ( $confirm_password && $password !== $confirmed_password ) {
                    throw new Exception( esc_html__( 'Entered passwords don\'t match', 'kitify' ) );
                }

                $validation_error = new WP_Error();

                $user = $this->create_user( $username, sanitize_email( $email ), $password );

                if ( is_wp_error( $user ) ) {
                    throw new Exception( $user->get_error_message() );
                }

                global $current_user;
                $current_user = get_user_by( 'id', $user );
                wp_set_auth_cookie( $user, true );

                if ( ! empty( $_POST['kitify_redirect'] ) ) {
                    $redirect = wp_sanitize_redirect( $_POST['kitify_redirect'] );
                } else {
                    $redirect = $_POST['_wp_http_referer'];
                }

                wp_redirect( $redirect );
                exit;

            } catch ( Exception $e ) {
                wp_cache_set( 'kitify-register-messages', $e->getMessage() );
            }

        }

        /**
         * Create new user function
         *
         * @param  [type] $username [description]
         * @param  [type] $email    [description]
         * @param  [type] $password [description]
         * @return [type]           [description]
         */
        public function create_user( $username, $email, $password ) {

            // Check username
            if ( empty( $username ) || ! validate_username( $username ) ) {
                return new WP_Error(
                    'registration-error-invalid-username',
                    __( 'Please enter a valid account username.', 'kitify' )
                );
            }

            if ( username_exists( $username ) ) {
                return new WP_Error(
                    'registration-error-username-exists',
                    __( 'An account is already registered with that username. Please choose another.', 'kitify' )
                );
            }

            // Check the email address.
            if ( empty( $email ) || ! is_email( $email ) ) {
                return new WP_Error(
                    'registration-error-invalid-email',
                    __( 'Please provide a valid email address.', 'kitify' )
                );
            }

            if ( email_exists( $email ) ) {
                return new WP_Error(
                    'registration-error-email-exists',
                    __( 'An account is already registered with your email address. Please log in.', 'kitify' )
                );
            }

            // Check password
            if ( empty( $password ) ) {
                return new WP_Error(
                    'registration-error-missing-password',
                    __( 'Please enter an account password.', 'kitify' )
                );
            }

            $custom_error = apply_filters( 'kitify_register_form_custom_error', null );

            if ( is_wp_error( $custom_error ) ){
                return $custom_error;
            }

            $new_user_data = array(
                'user_login' => $username,
                'user_pass'  => $password,
                'user_email' => $email,
            );

            $user_id = wp_insert_user( $new_user_data );

            if ( is_wp_error( $user_id ) ) {
                return new WP_Error(
                    'registration-error',
                    '<strong>' . __( 'Error:', 'kitify' ) . '</strong> ' . __( 'Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'kitify' )
                );
            }

            return $user_id;

        }

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function set_device_name_for_custom_bkp_by_css(){
		    $breakpoints = kitify_helper()->get_active_breakpoints();
			asort($breakpoints);
		    $css = '';
		    $vheader_css = '.kitify-vheader--hide__DEVICE__.kitify--is-vheader{position:relative}.kitify-vheader--hide__DEVICE__.kitify--is-vheader.kitify-vheader-pleft{padding-left:var(--kitify-vheader-width)}.kitify-vheader--hide__DEVICE__.kitify--is-vheader.kitify-vheader-pright{padding-right:var(--kitify-vheader-width)}.kitify-vheader--hide__DEVICE__.kitify--is-vheader > .elementor-location-header.elementor-edit-area{position:static}.kitify-vheader--hide__DEVICE__.kitify--is-vheader > .elementor-location-header > .elementor-section-wrap > .elementor-top-section:first-child{position:absolute;top:0;bottom:0;width:var(--kitify-vheader-width);height:auto;z-index:3;min-height:calc(100vh - 32px)}.kitify-vheader--hide__DEVICE__.kitify--is-vheader.kitify-vheader-pleft > .elementor-location-header > .elementor-section-wrap > .elementor-top-section:first-child{left:0}.kitify-vheader--hide__DEVICE__.kitify--is-vheader.kitify-vheader-pright > .elementor-location-header > .elementor-section-wrap > .elementor-top-section:first-child{right:0}.kitify-vheader--hide__DEVICE__.kitify--is-vheader > .elementor-location-header > .elementor-section-wrap > .elementor-top-section:first-child > .elementor-container{flex-flow:row wrap;height:auto;position:sticky;top:var(--kitify-adminbar-height);left:0;min-height:calc(100vh - 32px)}.kitify-vheader--hide__DEVICE__.kitify--is-vheader > .elementor-location-header > .elementor-section-wrap > .elementor-top-section:first-child > .elementor-container > .elementor-column{width:100%}';
		    $grid_mapping = [
		        'laptop'        => 'desk',
		        'tablet'        => 'lap',
		        'mobile_extra'  => 'tab',
		        'tabletportrait'=> 'tab',
            'mobile'        => 'tabp'
            ];

            $grid_mapping2 = [
                'mob',
                'tabp',
                'tab',
                'lap',
                'desk'
            ];

            if(!isset($breakpoints['laptop'])){
                $grid_mapping['tablet'] = 'desk';
            }
            if(!isset($breakpoints['mobile_extra']) && !isset($breakpoints['tabletportrait'])){
                $grid_mapping['mobile'] = 'tab';
            }

            $tmpgrid = [];
            foreach ($grid_mapping2 as $v){
                for ( $j = 1; $j <= 10; $j++ ){
                    $tmpgrid[] = sprintf('.col-%1$s-%2$s', $v, $j);
                }
            }

			foreach ($breakpoints as $device_name => $device_value){
				if(in_array($device_name, ['tablet', 'mobile_extra', 'mobile'])){
					$css .= '@media(min-width:'.($device_value+1).'px){'.str_replace('__DEVICE__', $device_name, $vheader_css).'}';
				}
			}

            $css .= join(',', $tmpgrid) . '{position:relative;min-height:1px;padding:10px;box-sizing:border-box;width:100%}';

            for ( $j = 1; $j <= 10; $j++ ){
                $css .= sprintf('.col-%1$s-%2$s{flex:0 0 calc(%3$s);max-width:calc(%3$s)}', 'mob', $j, '100%/' . $j);
            }

			foreach ($breakpoints as $device_name => $device_value){
				if( array_key_exists($device_name, $grid_mapping) ){
					$css .= '@media(min-width:'.($device_value+1).'px){';
					for ( $j = 1; $j <= 10; $j++ ){
						$css .= sprintf('.col-%1$s-%2$s{flex:0 0 calc(%3$s);max-width:calc(%3$s)}', $grid_mapping[$device_name], $j, '100%/' . $j);
					}
					$css .= '}';
				}
			}

			arsort($breakpoints);

			$column_css = '.elementor-column.kitify-col-width-auto-__DEVICE__{width:auto!important}.elementor-column.kitify-col-width-auto-__DEVICE__.kitify-col-align-left{margin-right:auto}.elementor-column.kitify-col-width-auto-__DEVICE__.kitify-col-align-right{margin-left:auto}.elementor-column.kitify-col-width-auto-__DEVICE__.kitify-col-align-center{margin-left:auto;margin-right:auto}';
			$widget_align_desktop_css = '[data-elementor-device-mode=desktop] .kitify-widget-align-left{margin-right:auto!important}[data-elementor-device-mode=desktop] .kitify-widget-align-right{margin-left:auto!important}[data-elementor-device-mode=desktop] .kitify-widget-align-center{margin-left:auto!important;margin-right:auto!important}';
			$widget_align_css = '[data-elementor-device-mode=__DEVICE__] .kitify-widget-align-__DEVICE__-left{margin-right:auto!important}[data-elementor-device-mode=__DEVICE__] .kitify-widget-align-__DEVICE__-right{margin-left:auto!important}[data-elementor-device-mode=__DEVICE__] .kitify-widget-align-__DEVICE__-center{margin-left:auto!important;margin-right:auto!important}';

			$css .= $widget_align_desktop_css;
			foreach ($breakpoints as $device_name => $device_value){
				$css .= str_replace('__DEVICE__', $device_name, $widget_align_css);
				$css .= sprintf('@media(max-width: %1$spx){%2$s}', $device_value, str_replace('__DEVICE__', $device_name, $column_css));
			}

		    return $css;
        }

        public function product_image_flexslider_vars(){
            return "try{ wc_single_product_params.flexslider.directionNav=true; wc_single_product_params.flexslider.before = function(slider){ jQuery(document).trigger('kitify/woocommerce/single/init_product_slider', [slider]); } }catch(ex){}";
        }

        public function register_custom_animation( $animations ){
            $new_animation = [
                'kitifyShortFadeInDown' => 'Short Fade In Down',
                'kitifyShortFadeInUp' => 'Short Fade In Up',
                'kitifyShortFadeInLeft' => 'Short Fade In Left',
                'kitifyShortFadeInRight' => 'Short Fade In Right',
            ];
            $animations['Kitify'] = $new_animation;
		    return $animations;
        }

        public function add_new_animation_css(){
            return '@keyframes kitifyShortFadeInDown{from{opacity:0;transform:translate3d(0,-50px,0)}to{opacity:1;transform:none}}.kitifyShortFadeInDown{animation-name:kitifyShortFadeInDown}@keyframes kitifyShortFadeInUp{from{opacity:0;transform:translate3d(0,50px,0)}to{opacity:1;transform:none}}.kitifyShortFadeInUp{animation-name:kitifyShortFadeInUp}@keyframes kitifyShortFadeInLeft{from{opacity:0;transform:translate3d(-50px,0,0)}to{opacity:1;transform:none}}.kitifyShortFadeInLeft{animation-name:kitifyShortFadeInLeft}@keyframes kitifyShortFadeInRight{from{opacity:0;transform:translate3d(50px,0,0)}to{opacity:1;transform:none}}.kitifyShortFadeInRight{animation-name:kitifyShortFadeInRight}';
        }

        public function subscribe_form_ajax() {

            if ( ! wp_verify_nonce( isset($_POST['nonce']) ? $_POST['nonce'] : false, 'kitify_elementor_subscribe_form_ajax' ) ) {
                $response = array(
                    'message' => $this->sys_messages['invalid_nonce'],
                    'type'    => 'error-notice',
                ) ;

                wp_send_json( $response );
            }

            $data = ( ! empty( $_POST['data'] ) ) ? $_POST['data'] : false;

            if ( ! $data ) {
                wp_send_json_error( array( 'type' => 'error', 'message' => $this->sys_messages['server_error'] ) );
            }

            $api_key = apply_filters('kitify/mailchimp/api', kitify_settings()->get_option('mailchimp-api-key'));
            $list_id = apply_filters('kitify/mailchimp/list_id', kitify_settings()->get_option('mailchimp-list-id'));
            $double_opt = apply_filters('kitify/mailchimp/double_opt_in', kitify_settings()->get_option('mailchimp-double-opt-in'));

            $double_opt_in = filter_var( $double_opt, FILTER_VALIDATE_BOOLEAN );

            if ( ! $api_key ) {
                wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
            }

            if ( isset( $data['use_target_list_id'] ) &&
                filter_var( $data['use_target_list_id'], FILTER_VALIDATE_BOOLEAN ) &&
                ! empty( $data['target_list_id'] )
            ) {
                $list_id = $data['target_list_id'];
            }

            if ( ! $list_id ) {
                wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
            }

            $mail = $data['email'];

            if ( empty( $mail ) || ! is_email( $mail ) ) {
                wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['invalid_mail'] ) );
            }

            $args = [
                'email_address' => $mail,
                'status'        => $double_opt_in ? 'pending' : 'subscribed',
            ];

            if ( ! empty( $data['additional'] ) ) {

                $additional = $data['additional'];

                foreach ( $additional as $key => $value ) {
                    $merge_fields[ strtoupper( $key ) ] = $value;
                }

                $args['merge_fields'] = $merge_fields;

            }

            $response = $this->api_call( $api_key, $list_id, $args );

            if ( false === $response ) {
                wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
            }

            $response = json_decode( $response, true );

            if ( empty( $response ) ) {
                wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['internal'] ) );
            }

            if ( isset( $response['status'] ) && 'error' == $response['status'] ) {
                wp_send_json( array( 'type' => 'error', 'message' => esc_html( $response['error'] ) ) );
            }

            wp_send_json( array( 'type' => 'success', 'message' => $this->sys_messages['subscribe_success'] ) );
        }

        /**
         * Make remote request to mailchimp API
         *
         * @param  string $method API method to call.
         * @param  array  $args   API call arguments.
         * @return array|bool
         */
        private function api_call( $api_key, $list_id, $args = [] ) {

            $key_data = explode( '-', $api_key );

            if ( empty( $key_data ) || ! isset( $key_data[1] ) ) {
                return false;
            }

            $api_server = sprintf( 'https://%s.api.mailchimp.com/3.0/', $key_data[1] );

            $url = esc_url( trailingslashit( $api_server . 'lists/' . $list_id . '/members/' ) );

            $data = json_encode( $args );

            $request_args = [
                'method'      => 'POST',
                'timeout'     => 20,
                'headers'     => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'apikey ' . $api_key
                ],
                'body'        => $data,
            ];

            $request = wp_remote_post( $url, $request_args );

            return wp_remote_retrieve_body( $request );
        }

        public function override_single_post_template( $value, $post_id, $meta_key, $single ){

            if ( '_wp_page_template' !== $meta_key ) {
                return $value;
            }

            if ( is_admin() ) {
                return $value;
            }

            if ( ! is_singular( 'post' ) ) {
                return $value;
            }

            remove_filter( 'get_post_metadata', array( $this, 'override_single_post_template' ), 10 );

            $current_template = get_post_meta( $post_id, '_wp_page_template', true );

            add_filter( 'get_post_metadata', array( $this, 'override_single_post_template' ), 10, 4 );

            if ( '' !== $current_template && 'default' !== $current_template ) {
                return $value;
            }

            $global_post_template = kitify_settings()->get_option( 'single_post_template', 'default' );

            if ( empty( $global_post_template ) || 'default' === $global_post_template ) {
                return $value;
            }

            return $global_post_template;
        }


        public function override_single_page_template( $value, $post_id, $meta_key, $single ){

            if ( '_wp_page_template' !== $meta_key ) {
                return $value;
            }

            if ( is_admin() ) {
                return $value;
            }

            if ( ! is_singular( 'page' ) ) {
                return $value;
            }

            remove_filter( 'get_post_metadata', array( $this, 'override_single_page_template' ), 10 );

            $current_template = get_post_meta( $post_id, '_wp_page_template', true );

            add_filter( 'get_post_metadata', array( $this, 'override_single_page_template' ), 10, 4 );

            if ( '' !== $current_template && 'default' !== $current_template ) {
                return $value;
            }

            $global_post_template = kitify_settings()->get_option( 'single_page_template', 'default' );

            if ( empty( $global_post_template ) || 'default' === $global_post_template ) {
                return $value;
            }

            return $global_post_template;
        }

		public function register_portfolio_content_type(){
			$avaliable_extension = kitify_settings()->get('avaliable_extensions', []);
			if(!empty($avaliable_extension['portfolio_content_type']) && filter_var($avaliable_extension['portfolio_content_type'], FILTER_VALIDATE_BOOLEAN)){
				register_post_type( 'nova_portfolio', apply_filters('kitify/admin/portoflio/args', [
					'label'                 => esc_html__( 'Portfolio', 'kitify' ),
					'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
					'taxonomies'            => [ 'post_tag' ],
					'menu_icon'             => 'dashicons-portfolio',
					'public'                => true,
					'menu_position'         => 8,
					'can_export'            => true,
					'has_archive'           => true,
					'exclude_from_search'   => false,
					'rewrite'               => array( 'slug' => 'portfolio' )
				]));
				register_taxonomy( 'nova_portfolio_category', 'nova_portfolio', apply_filters('kitify/admin/portoflio_cat/args', [
					'hierarchical'      => true,
					'show_in_nav_menus' => true,
					'labels'            => array(
						'name'          => esc_html__( 'Portfolio Categories', 'kitify' ),
						'singular_name' => esc_html__( 'Portfolio Category', 'kitify' )
					),
					'query_var'         => true,
					'show_admin_column' => true,
					'rewrite'           => array('slug' => 'portfolio-category')
				]));
			}
		}

	}

}

/**
 * Returns instance of Kitify_Integration
 *
 * @return object
 */
function kitify_integration() {
	return Kitify_Integration::get_instance();
}
