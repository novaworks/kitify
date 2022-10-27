<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Novaworks Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kitify_Settings' ) ) {

	/**
	 * Define Kitify_Settings class
	 */
	class Kitify_Settings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * [$key description]
		 * @var string
		 */
		public $key = 'kitify-settings';

		/**
		 * Access Token transient option key
		 *
		 * @var string
		 */
		private $insta_updated_access_token_key = 'kitify_instagram_updated_access_token';

		/**
		 * [$builder description]
		 * @var null
		 */
		public $builder  = null;

		/**
		 * [$settings description]
		 * @var null
		 */
		public $settings = null;

		/**
		 * Available Widgets array
		 *
		 * @var array
		 */
		public $avaliable_widgets = [];

		/**
		 * [$default_avaliable_extensions description]
		 * @var [type]
		 */
		public $default_avaliable_extensions = [
			'motion_effects'        => 'true',
			'floating_effects'      => 'true',
			'css_transform'         => 'true',
			'wrapper_link'          => 'false',
			'element_visibility'    => 'true',
			'custom_css'            => 'true',
			'sticky_column'    			=> 'true',
		];

		/**
		 * [$settings_page_config description]
		 * @var [type]
		 */
		public $settings_page_config = [];

		/**
		 * Available Widgets Slugs
		 *
		 * @var array
		 */
		public $avaliable_widgets_slugs = [];

		/**
		 * Init page
		 */
		public function init() {

			foreach ( glob( kitify()->plugin_path( 'inc/addons/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class' => 'Class', 'name' => 'Name', 'slug' => 'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->avaliable_widgets[ $slug ] = $data['name'];
				$this->avaliable_widgets_slugs[]  = $data['slug'];
			}

			// Refresh Instagram Access Token
			add_action( 'admin_init', array( $this, 'refresh_instagram_access_token' ) );
		}

		/**
		 * [generate_frontend_config_data description]
		 * @return [type] [description]
		 */
		public function generate_frontend_config_data() {

			$default_active_widgets = [];
            $avaliable_widgets = [];

			foreach ( $this->avaliable_widgets as $slug => $name ) {

				$avaliable_widgets[] = [
					'label' => $name,
					'value' => $slug,
				];

				$default_active_widgets[ $slug ] = 'true';
			}

			$active_widgets = $this->get( 'avaliable_widgets', $default_active_widgets );

			$avaliable_extensions = [
				[
					'label' => esc_html__( 'Motion Effects Extension', 'kitify' ),
					'value' => 'motion_effects',
				],
                [
					'label' => esc_html__( 'Floating Effects Extension', 'kitify' ),
					'value' => 'floating_effects',
				],
                [
					'label' => esc_html__( 'CSS Transform Extension', 'kitify' ),
					'value' => 'css_transform',
				],
                [
					'label' => esc_html__( 'Wrapper Links', 'kitify' ),
					'value' => 'wrapper_link',
				],
                [
					'label' => esc_html__( 'Element Visibility Logic', 'kitify' ),
					'value' => 'element_visibility',
				],
                [
					'label' => esc_html__( 'Custom CSS', 'kitify' ),
					'value' => 'custom_css',
				],
        [
					'label' => esc_html__( 'Portfolio Content Type', 'kitify' ),
					'value' => 'portfolio_content_type',
				],
				[
					'label' => esc_html__( 'Sticky Column', 'kitify' ),
					'value' => 'sticky_column',
				],
			];

			$active_extensions = $this->get( 'avaliable_extensions', $this->default_avaliable_extensions );

			$rest_api_url = apply_filters( 'kitify/rest/frontend/url', get_rest_url() );

            $breadcrumbs_taxonomy_options = [];

            $post_types = get_post_types( array( 'public' => true ), 'objects' );

            if ( is_array( $post_types ) && ! empty( $post_types ) ) {

                foreach ( $post_types as $post_type ) {
                    $taxonomies = get_object_taxonomies( $post_type->name, 'objects' );

                    if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {

                        $options = [
                            [
                                'label' => esc_html__( 'None', 'kitify' ),
                                'value' => '',
                            ]
                        ];

                        foreach ( $taxonomies as $tax ) {

                            if ( ! $tax->public ) {
                                continue;
                            }

                            $options[] = [
                                'label' => $tax->labels->singular_name,
                                'value' => $tax->name,
                            ];
                        }

                        $breadcrumbs_taxonomy_options[ 'breadcrumbs_taxonomy_' . $post_type->name ] = array(
                            'value'   => $this->get( 'breadcrumbs_taxonomy_' . $post_type->name, ( 'post' === $post_type->name ) ? 'category' : '' ),
                            'options' => $options,
                        );
                    }
                }
            }

            $settingsData = [
                'svg-uploads'             => [
                    'value' => $this->get( 'svg-uploads', 'enabled' ),
                ],
                'kitify_templates'           => [
                    'value' => $this->get( 'kitify_templates', 'enabled' ),
                ],
                'gmap_api_key'                 => [
                    'value' => $this->get( 'gmap_api_key', '' ),
                ],
                'gmap_backend_api_key'                 => [
                    'value' => $this->get( 'gmap_backend_api_key', '' ),
                ],
                'disable_gmap_api_js'          => [
                    'value' => $this->get( 'disable_gmap_api_js', false ),
                ],
                'mailchimp-api-key'       => [
                    'value' => $this->get( 'mailchimp-api-key', '' ),
                ],
                'mailchimp-list-id'       => [
                    'value' => $this->get( 'mailchimp-list-id', '' ),
                ],
                'mailchimp-double-opt-in' => [
                    'value' => $this->get( 'mailchimp-double-opt-in', false ),
                ],
                'insta_access_token'      => [
                    'value' => $this->get( 'insta_access_token', '' ),
                ],
                'insta_business_access_token' => [
                    'value' => $this->get( 'insta_business_access_token', '' ),
                ],
                'insta_business_user_id' => [
                    'value' => $this->get( 'insta_business_user_id', '' ),
                ],
                'avaliable_widgets'       => [
                    'value'   => $active_widgets,
                    'options' => $avaliable_widgets,
                ],
                'avaliable_extensions'    => [
                    'value'   => $active_extensions,
                    'options' => $avaliable_extensions,
                ],
                'single_post_template' => [
                    'value'   => $this->get( 'single_post_template', 'default' ),
                    'options' => $this->prepare_options_list( $this->get_single_post_templates() ),
                ],
                'single_page_template' => [
                    'value'   => $this->get( 'single_page_template', 'default' ),
                    'options' => $this->prepare_options_list( $this->get_single_page_templates() ),
                ],
                'custom_fonts'          => [
                    'i18n'    => [
                        'new_font'                  => esc_html__('New Font', 'kitify'),
                        'new_variation'             => esc_html__('New Variation', 'kitify'),
                        'add_new_font'              => esc_html__('Add New Font', 'kitify'),
                        'add_new_font_variation'    => esc_html__('Add New Variation', 'kitify'),
                    ],
                    'value'   => $this->get( 'custom_fonts', [] )
                ],
								'head_code' => [
										'value' => $this->get( 'head_code', '' ),
								],
								'custom_css' => [
										'value' => $this->get( 'custom_css', '' ),
								],
								'footer_code' => [
										'value' => $this->get( 'footer_code', '' ),
								],
            ];

            $settingsData = array_merge($settingsData, $breadcrumbs_taxonomy_options);

			$this->settings_page_config = [
				'messages' => [
					'saveSuccess' => esc_html__( 'Saved', 'kitify' ),
					'saveError'   => esc_html__( 'Error', 'kitify' ),
				],
				'settingsApiUrl' => $rest_api_url . 'kitify-api/v1/plugin-settings',
				'settingsData' => $settingsData
			];

			return $this->settings_page_config;
		}

		/**
		 * Return settings page URL
		 *
		 * @param  string $subpage
		 * @return string
		 */
		public function get_settings_page_link( $subpage = 'general' ) {

			return add_query_arg(
				array(
					'page'    => 'kitify-dashboard-settings-page',
					'subpage' => 'kitify-' . $subpage . '-settings',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		/**
		 * [get description]
		 * @param  [type]  $setting [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get( $setting, $default = false ) {

			return $this->get_option( $setting, $default );

		}

		/**
		 * [get description]
		 * @param  [type]  $setting [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get_option( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

		}

		/**
		 * Refresh Instagram Access Token
		 *
		 * @return void
		 */
		public function refresh_instagram_access_token() {
			$access_token = $this->get( 'insta_access_token' );
			$access_token = trim( $access_token );

			if ( empty( $access_token ) ) {
				return;
			}

			$updated = get_transient( $this->insta_updated_access_token_key );

			if ( ! empty( $updated ) ) {
				return;
			}

			$url = add_query_arg(
				array(
					'grant_type'   => 'ig_refresh_token',
					'access_token' => $access_token,
				),
				'https://graph.instagram.com/refresh_access_token'
			);

			$response = wp_remote_get( $url );

			if ( ! $response || is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				set_transient( $this->insta_updated_access_token_key, 'error', DAY_IN_SECONDS );
				return;
			}

			$body = wp_remote_retrieve_body( $response );

			if ( ! $body ) {
				set_transient( $this->insta_updated_access_token_key, 'error', DAY_IN_SECONDS );
				return;
			}

			$body = json_decode( $body, true );

			if ( empty( $body['access_token'] ) || empty( $body['expires_in'] ) ) {
				set_transient( $this->insta_updated_access_token_key, 'error', DAY_IN_SECONDS );
				return;
			}

			set_transient( $this->insta_updated_access_token_key, 'updated', 30 * DAY_IN_SECONDS );
		}

        /**
         * Get single post templates.
         *
         * @return array
         */
        public function get_single_post_templates() {
            $default_template = array( 'default' => apply_filters( 'default_page_template_title', esc_html__( 'Default Template', 'kitify' ) ) );

            if ( ! function_exists( 'get_page_templates' ) ) {
                return $default_template;
            }

            $post_templates = get_page_templates( null, 'post' );

            ksort( $post_templates );

            $templates = array_combine(
                array_values( $post_templates ),
                array_keys( $post_templates )
            );

            $templates = array_merge( $default_template, $templates );

            return $templates;
        }

        /**
         * Get single page templates.
         *
         * @return array
         */
        public function get_single_page_templates() {
            $default_template = array( 'default' => apply_filters( 'default_page_template_title', esc_html__( 'Default Template', 'kitify' ) ) );

            if ( ! function_exists( 'get_page_templates' ) ) {
                return $default_template;
            }

            $post_templates = get_page_templates( null );

            ksort( $post_templates );

            $templates = array_combine(
                array_values( $post_templates ),
                array_keys( $post_templates )
            );

            $templates = array_merge( $default_template, $templates );

            return $templates;
        }

        /**
         * Prepare options list
         *
         * @param  array $options
         * @return array
         */
        public function prepare_options_list( $options = array() ) {

            $result = array();

            foreach ( $options as $slug => $label ) {
                $result[] = array(
                    'value' => $slug,
                    'label' => $label,
                );
            }

            return $result;
        }

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
		public function is_combine_js_css(){
			return false;
		}
	}
}

/**
 * Returns instance of Kitify_Settings
 *
 * @return object
 */
function kitify_settings() {
	return Kitify_Settings::get_instance();
}

kitify_settings()->init();
