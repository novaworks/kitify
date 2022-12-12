<?php
/**
 * Plugin Name:       Kitify by Novaworks
 * Description:       A perfect plugin for Elementor
 * Version:           1.0.4.5
 * Author:            Novaworks
 * Author URI:        https://kitify.app
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kitify
 * Domain Path:       /languages
 *
 * Elementor tested up to: 3.3.2
 * Elementor Pro tested up to: 3.3
 *
 * @package kitify
 * @author  Novaworks
 * @license GPL-2.0+
 * @copyright  2021, Novaworks
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if(!function_exists('Kitify')){
    class Kitify {
        /**
         * A reference to an instance of this class.
         *
         * @since  1.0.0
         * @access private
         * @var    object
         */
        private static $instance = null;

        /**
         * Holder for base plugin URL
         *
         * @since  1.0.0
         * @access private
         * @var    string
         */
        private $plugin_url = null;

        /**
         * Holder for base plugin path
         *
         * @since  1.0.0
         * @access private
         * @var    string
         */
        private $plugin_path = null;

        /**
         * Plugin version
         *
         * @var string
         */
        private $version = '1.0.4.5';

        /**
         * Framework component
         *
         * @since  1.0.0
         * @access public
         * @var    object
         */
        public $module_loader = null;

        /**
         * @var \KitifyThemeBuilder\Modules\Modules_Manager $modules_manager
         */
        public $modules_manager;

        /**
         * @since  2.0.0
         * @access public
         * @var \KitifyExtensions\Manager $extensions_manager
         */
        public $extensions_manager;

	    /**
	     * @var Kitify_Ajax_Manager $ajax_manager;
	     */
        public $ajax_manager;

        /**
         * Holder for current Customizer module instance.
         *
         * @since 1.0.0
         * @var   CX_Customizer
         */
        public $customizer = null;


        /**
         * Sets up needed actions/filters for the plugin to initialize.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function __construct() {

            spl_autoload_register( [ $this, 'autoload' ] );

            // Load the CX Loader.
            add_action( 'after_setup_theme', array( $this, 'module_loader' ), -20 );

            // load includes
            add_action( 'after_setup_theme', array( $this, 'includes' ), 4 );

            // init customizer
            add_action( 'after_setup_theme', array( $this, 'init_customizer' ), 6 );

            // Internationalize the text strings used.
            add_action( 'init', array( $this, 'lang' ), -999 );

            // Load files.
            add_action( 'init', array( $this, 'init' ), -999 );

            // Dashboard Init
            add_action( 'init', array( $this, 'dashboard_init' ), -999 );

            // Add body class
            add_filter('body_class', array( $this, 'body_class' ), 0);

            add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );

            add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue'] );

            add_action('elementor/element/after_section_end', array( $this, 'add_size_units' ), 10, 2);

            add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], 0 );

            // Register activation and deactivation hook.
            register_activation_hook( __FILE__, array( $this, 'activation' ) );
            register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

            $this->ajax_manager = new Kitify_Ajax_Manager();

            require_once $this->plugin_path( 'inc/kitify-typekit-fonts/kitify-typekit-fonts.php' );
            require_once $this->plugin_path( 'inc/framework/customizer/controls/responsive/responsive.php' );
        }

        /**
         * Load the theme modules.
         *
         * @since  1.0.0
         */
        public function module_loader() {

            require $this->plugin_path( 'inc/framework/loader.php' );

            $this->module_loader = new Kitify_CX_Loader(
                array(
                    $this->plugin_path( 'inc/framework/vue-ui/cherry-x-vue-ui.php' ),
                    $this->plugin_path( 'inc/framework/db-updater/cx-db-updater.php' ),
                    $this->plugin_path( 'inc/framework/dashboard/dashboard.php' ),
                    $this->plugin_path( 'inc/framework/interface-builder/interface-builder.php' ),
                    $this->plugin_path( 'inc/framework/post-meta/post-meta.php' ),
                    $this->plugin_path( 'inc/framework/customizer/customizer.php' ),
                    $this->plugin_path( 'inc/framework/fonts-manager/fonts-manager.php' ),
                    $this->plugin_path( 'inc/class-breadcrumbs.php' ),
                    $this->plugin_path( 'inc/framework/mega-menu/mega-menu.php' ),

                )
            );

            // Enable support for Post Formats
            add_theme_support( 'post-formats', array( 'standard', 'video', 'gallery', 'audio', 'quote', 'link' ) );
        }

        /**
         * Load the theme includes
         */
        public function includes(){
            require_once $this->plugin_path( 'inc/class-post-meta.php' );
        }

        /**
         * Returns plugin version
         *
         * @return string
         */
        public function get_version() {
            if(defined('NOVA_DEBUG') && NOVA_DEBUG){
                return time();
            }
            return $this->version;
        }

        /**
         * Manually init required modules.
         *
         * @return void
         */
        public function init() {
            if ( ! $this->has_elementor() ) {
                add_action( 'admin_notices', array( $this, 'required_plugins_notice' ) );
                return;
            }

            $this->load_files();

            kitify_integration()->init();
            kitify_svg_manager()->init();

            //Init Rest Api
            new \Kitify\Rest_Api();

            if ( is_admin() ) {

                //Init Settings Manager
                new \Kitify\Settings();
                // include DB upgrader
                require $this->plugin_path( 'inc/class-db-upgrader.php' );
                // Init DB upgrader
                new Kitify_DB_Upgrader();
            }
            $this->extensions_manager = new KitifyExtensions\Manager();
            do_action( 'kitify/init', $this );
        }

        /**
         * [dashboard_init description]
         * @return [type] [description]
         */
        public function dashboard_init() {

            if ( is_admin() ) {

                $kitify_dashboard_module_data = $this->module_loader->get_included_module_data( 'dashboard.php' );

                $kitify_dashboard = \Kitify_Dashboard\Dashboard::get_instance();

                $kitify_dashboard->init( array(
                    'path'           => $kitify_dashboard_module_data['path'],
                    'url'            => $kitify_dashboard_module_data['url'],
                    'cx_ui_instance' => array( $this, 'dashboard_ui_instance_init' ),
                    'plugin_data'    => array(
                        'slug'    => 'kitify',
                        'file'    => 'nova-element-kit/nova-element-kit.php',
                        'version' => $this->get_version(),
                        'plugin_links' => array(
                            array(
                                'label'  => esc_html__( 'Go to settings', 'kitify' ),
                                'url'    => add_query_arg( array( 'page' => 'kitify-dashboard-settings-page', 'subpage' => 'kitify-general-settings' ), admin_url( 'admin.php' ) ),
                                'target' => '_self',
                            ),
                        ),
                    ),
                ) );
            }
        }

        /**
         * [dashboard_ui_instance_init description]
         * @return [type] [description]
         */
        public function dashboard_ui_instance_init() {
            $cx_ui_module_data = $this->module_loader->get_included_module_data( 'cherry-x-vue-ui.php' );

            return new CX_Vue_UI( $cx_ui_module_data );
        }

        /**
         * Show recommended plugins notice.
         *
         * @return void
         */
        public function required_plugins_notice() {
            $screen = get_current_screen();

            if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
                return;
            }

            $plugin = 'elementor/elementor.php';

            $installed_plugins      = get_plugins();
            $is_elementor_installed = isset( $installed_plugins[ $plugin ] );

            if ( $is_elementor_installed ) {
                if ( ! current_user_can( 'activate_plugins' ) ) {
                    return;
                }

                $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

                $message = sprintf( '<p>%s</p>', esc_html__( 'Kitify requires Elementor to be activated.', 'kitify' ) );
                $message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $activation_url, esc_html__( 'Activate Elementor Now', 'kitify' ) );
            }
            else {
                if ( ! current_user_can( 'install_plugins' ) ) {
                    return;
                }

                $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

                $message = sprintf( '<p>%s</p>', esc_html__( 'Kitify requires Elementor to be installed.', 'kitify' ) );
                $message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, esc_html__( 'Install Elementor Now', 'kitify' ) );
            }

            printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
        }

        /**
         * Check if theme has elementor
         *
         * @return boolean
         */
        public function has_elementor() {
            return defined( 'ELEMENTOR_VERSION' );
        }

        /**
         * Check if theme has elementor
         *
         * @return boolean
         */
        public function has_elementor_pro() {
            return defined( 'ELEMENTOR_PRO_VERSION' );
        }

        /**
         * Returns Elementor instance
         *
         * @return object
         */
        public function elementor() {
            return \Elementor\Plugin::$instance;
        }

        /**
         * Load required files.
         *
         * @return void
         */
        public function load_files() {

            require_once $this->plugin_path( 'inc/class-helper.php' );
            require_once $this->plugin_path( 'inc/class-integration.php' );
            require_once $this->plugin_path( 'inc/class-settings.php' );
            require_once $this->plugin_path( 'inc/class-tools.php' );
            require_once $this->plugin_path( 'inc/settings/manager.php' );
            require_once $this->plugin_path( 'inc/class-svg-manager.php' );

            require_once $this->plugin_path( 'inc/rest-api/template-helper.php' );
            require_once $this->plugin_path( 'inc/rest-api/rest-api.php' );
            require_once $this->plugin_path( 'inc/rest-api/endpoints/base.php' );
            require_once $this->plugin_path( 'inc/rest-api/endpoints/elementor-template.php' );
            require_once $this->plugin_path( 'inc/rest-api/endpoints/elementor-widget.php' );
            require_once $this->plugin_path( 'inc/rest-api/endpoints/plugin-settings.php' );
            require_once $this->plugin_path( 'inc/rest-api/endpoints/get-menu-items.php' );

            require_once $this->plugin_path( 'inc/integrate/override.php' );

        }

        /**
         * Returns path to file or dir inside plugin folder
         *
         * @param  string $path Path inside plugin dir.
         * @return string
         */
        public function plugin_path( $path = null ) {

            if ( ! $this->plugin_path ) {
                $this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
            }

            return $this->plugin_path . $path;
        }
        /**
         * Returns url to file or dir inside plugin folder
         *
         * @param  string $path Path inside plugin dir.
         * @return string
         */
        public function plugin_url( $path = null ) {

            if ( ! $this->plugin_url ) {
                $this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
            }

            return $this->plugin_url . $path;
        }

        /**
         * Loads the translation files.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function lang() {
            load_plugin_textdomain( 'kitify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }

        /**
         * Get the template path.
         *
         * @return string
         */
        public function template_path() {
            return apply_filters( 'kitify/template-path', 'kitify/' );
        }

        /**
         * Returns path to template file.
         *
         * @return string|bool
         */
        public function get_template( $name = null ) {

            $template = locate_template( $this->template_path() . $name );

            if ( ! $template ) {
                $template = $this->plugin_path( 'templates/' . $name );
            }

            if ( file_exists( $template ) ) {
                return $template;
            } else {
                return false;
            }
        }
        /**
         * Do some stuff on plugin activation
         *
         * @since  1.0.0
         * @return void
         */
        public function activation() {
          $typekit = new Custom_Typekit_Fonts();
          $adobe_font_id = apply_filters('kitify/adobe_fonts/id','');
          if (get_option('kitify-typekit-fonts') == '' && $adobe_font_id !='') {
            $option                                = array();
            $option['custom-typekit-font-id']      = sanitize_text_field( $adobe_font_id );
            $option['custom-typekit-font-details'] = $typekit->get_custom_typekit_details( $adobe_font_id );

            if ( empty( $option['custom-typekit-font-details'] ) ) {
              return;
            }
            update_option( 'kitify-typekit-fonts', $option );
          }
        }

        /**
         * Do some stuff on plugin activation
         *
         * @since  1.0.0
         * @return void
         */
        public function deactivation() {
        }

        /**
         *
         * Add custom css class into body tag
         *
         * @param $classes
         * @return array
         */
        public function body_class( $classes ){
            if(is_rtl()){
                $classes[] = 'rtl';
            }
            else{
                $classes[] = 'ltr';
            }
            return $classes;
        }

        public function on_elementor_init() {
            $this->modules_manager = new \KitifyThemeBuilder\Modules\Modules_Manager();
        }

        public function admin_enqueue(){
            wp_enqueue_script(
                'kitify-admin',
                $this->plugin_url('assets/js/kitify-admin.js'),
                array( 'jquery' ),
                $this->get_version()
            );
        }

        public static function get_instance() {
            // If the single instance hasn't been set, set it now.
            if ( null == self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function autoload( $class ) {

        	$mappings = [
        		'Kitify_Ajax_Manager' => 'inc/modules/ajax/manager.php',
        		'KitifyThemeBuilder_AdminApp' => 'inc/modules/admin-app/admin-app.php',
	        ];

        	if( array_key_exists( $class, $mappings ) ){
		        if ( ! class_exists( $class ) ) {
			        $filename = $this->plugin_path($mappings[$class]);
			        if ( is_readable( $filename ) ) {
				        include( $filename );
			        }
		        }
		        return;
	        }

            if ( 0 === strpos( $class, 'KitifyExtensions' ) ) {
                if ( ! class_exists( $class ) ) {
                    $filename_extends = strtolower(
                        preg_replace(
                            [ '/^' . 'KitifyExtensions' . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
                            [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
                            $class
                        )
                    );
                    $filename_extends = $this->plugin_path('inc/extensions/' . $filename_extends . '.php');

                    if ( is_readable( $filename_extends ) ) {
                        include( $filename_extends );
                    }
                }
            }


            if ( 0 !== strpos( $class, 'KitifyThemeBuilder' ) ) {
                return;
            }

            if ( ! class_exists( $class ) ) {

                $filename = strtolower(
                    preg_replace(
                        [ '/^' . 'KitifyThemeBuilder' . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
                        [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
                        $class
                    )
                );

                $filename = $this->plugin_path('inc/' . $filename . '.php');

                if ( is_readable( $filename ) ) {
                    include( $filename );
                }
            }

        }

        public function add_size_units( $controls_stack, $section_id ){
            if($section_id === 'section_advanced'){
                $controls_stack->update_responsive_control(
                    'margin',
                    [
                        'size_units' => [ 'px', 'em', '%', 'rem', 'vw', 'vh' ]
                    ]
                );
                $controls_stack->update_responsive_control(
                    'padding',
                    [
                        'size_units' => [ 'px', 'em', '%', 'rem', 'vw', 'vh' ]
                    ]
                );
            }

            if($section_id === '_section_style'){
                $controls_stack->update_responsive_control(
                    '_margin',
                    [
                        'size_units' => [ 'px', 'em', '%', 'rem', 'vw', 'vh' ]
                    ]
                );
                $controls_stack->update_responsive_control(
                    '_padding',
                    [
                        'size_units' => [ 'px', 'em', '%', 'rem', 'vw', 'vh' ]
                    ]
                );
            }
        }

        public function get_theme_support( $prop = '', $default = null ) {
            $theme_support = get_theme_support( 'novaworks' );
            $theme_support = is_array( $theme_support ) ? $theme_support[0] : false;

            if ( ! $theme_support ) {
                return $default;
            }

            if ( $prop ) {
                $prop_stack = explode( '::', $prop );
                $prop_key   = array_shift( $prop_stack );

                if ( isset( $theme_support[ $prop_key ] ) ) {
                    $value = $theme_support[ $prop_key ];

                    if ( count( $prop_stack ) ) {
                        foreach ( $prop_stack as $prop_key ) {
                            if ( is_array( $value ) && isset( $value[ $prop_key ] ) ) {
                                $value = $value[ $prop_key ];
                            } else {
                                $value = $default;
                                break;
                            }
                        }
                    }
                } else {
                    $value = $default;
                }

                return $value;
            }

            return $theme_support;
        }

        public function init_customizer(){

            // Init CX_Customizer
            $customizer_options = [
                'prefix'         => 'kitify',
                'path'          => $this->plugin_path( 'inc/framework/customizer/' ),
                'capability'    => 'edit_theme_options',
                'type'          => 'theme_mod',
                'fonts_manager' => new \CX_Fonts_Manager( ['prefix' => 'kitify'] ),
                'options'       => []
            ];

            $this->customizer = new \CX_Customizer( apply_filters('kitify/theme/customizer/options', $customizer_options) );
        }
        public function plugins_loaded(){
          if( $this->has_elementor() && ($typography = $this->plugin_path('includes/integrate/typography.php')) && file_exists( $typography )){
              require_once $typography;
          }
          if( $this->has_elementor() && !$this->has_elementor_pro() ){
              new KitifyThemeBuilder_AdminApp();
          }
        }

    }
}

if(!function_exists('kitify')){
    /**
     * Returns instance of the plugin class.
     *
     * @since  1.0.0
     * @return object
     */
    function kitify(){
        return Kitify::get_instance();
    }
}

kitify();
