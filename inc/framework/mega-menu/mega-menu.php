<?php
/**
 * Mega Menu module
 *
 * Version: 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('Kitify_MegaMenu') ){
    class Kitify_MegaMenu {
        /**
         * A reference to an instance of this class.
         *
         * @since  1.0.0
         * @access private
         * @var    object
         */
        private static $instance = null;

        /**
         * Module version.
         *
         * @var string
         */
        protected $version = '1.0.0';

        /**
         * Module directory path.
         *
         * @since 1.0.0
         * @access protected
         * @var srting.
         */
        protected $path;

        /**
         * Module directory URL.
         *
         * @since 1.0.0
         * @access protected
         * @var srting.
         */
        protected $url;

        /**
         * Menu settings page
         *
         * @var string
         */
        protected $meta_key = 'kitify_menu_settings';

        public function __construct( $args = array() ) {

            $this->path = $args['path'];
            $this->url  = $args['url'];

            add_action( 'elementor/init',                   [$this, 'add_support']  );
            add_action( 'elementor/document/after_save',    [$this, 'save_posts']   );
            add_filter( 'pre_get_posts',                     [$this, 'pre_get_posts'], 10 );
            add_filter( 'elementor/document/urls/wp_preview',[$this, 'add_preview'], 10, 2 );
            add_filter( 'wp_insert_post_data',               [$this, 'revision_autosave'], 10 );

            add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ], 99 );
            add_action( 'admin_footer', array( $this, 'print_menu_settings_vue_template' ), 10 );

            add_action( 'wp_ajax_kitify_get_nav_item_settings', array( $this, 'get_nav_item_settings' ) );
            add_action( 'wp_ajax_kitify_save_nav_item_settings', array( $this, 'save_nav_item_settings' ) );
        }

        public function admin_scripts(){

            $screen = get_current_screen();

            if ( 'nav-menus' !== $screen->base ) {
                return;
            }

            $module_data = kitify()->module_loader->get_included_module_data( 'cherry-x-vue-ui.php' );
            $ui          = new CX_Vue_UI( $module_data );

            $ui->enqueue_assets();

            wp_enqueue_style(
                'kitify-menu-css',
                $this->url . 'assets/css/menu-admin.css',
                null,
                $this->version
            );

            wp_enqueue_script(
                'kitify-menu-js',
                $this->url . 'assets/js/menu-admin.js',
                array( 'cx-vue-ui' ),
                $this->version,
                true
            );

            wp_localize_script(
                'kitify-menu-js',
                'KitifyMenuConfig',
                apply_filters( 'kitify/module/menu/admin/nav-settings-config', array(
                    'labels'        => array(
                        'itemTriggerLabel' => __( 'MegaMenu', 'kitify' ),
                    ),
                    'editURL'       => add_query_arg(
                        array(
                            'kitify-open-editor' => 1,
                            'item'            => '%id%',
                            'menu'            => '%menuid%',
                        ),
                        esc_url( admin_url( '/' ) )
                    ),
                    'controlData'      => $this->default_nav_item_controls_data(),
                    'iconsFetchJson'   => kitify()->plugin_url( 'inc/framework/elementor-extension/assets/fonts/NovaIcons.json' ),
                ) )
            );

        }

        public function add_support(){
            add_post_type_support( 'nav_menu_item', 'elementor' );
        }

        public function pre_get_posts( $query ){
            if ( is_admin() || ! $query->is_main_query() ) {
                return;
            }
            if((isset($_GET['elementor-preview']) || isset($_GET['preview_id'])) && current_user_can('edit_theme_options') ){
                $current_id = 0;
                if(isset($_GET['elementor-preview'])){
                    $current_id = absint($_GET['elementor-preview']);
                }
                if(isset($_GET['preview_id'])){
                    $current_id = absint($_GET['preview_id']);
                }
                if( 'nav_menu_item' == get_post_type($current_id) ) {
                    $query->set('post_type', 'nav_menu_item');
                }
            }
        }

        public function add_preview( $url, $instance ){
            if(empty($url)){
                $main_post_id = $instance->get_main_id();
                $preview_link = set_url_scheme( get_permalink( $main_post_id ) );
                $preview_link = add_query_arg(
                    [
                        'preview_id' => $main_post_id,
                        'preview_nonce' => wp_create_nonce( 'post_preview_' . $main_post_id ),
                        'preview' => 'true'
                    ],
                    $preview_link
                );
                $url = $preview_link;
            }
            return $url;
        }

        public function save_posts( $instance ){
            $post = $instance->get_post();
            $post_id = $instance->get_main_id();
            $old_content = $post->post_content;

            if($post->post_type == 'nav_menu_item'){
                wp_update_post([
                    'ID' => $post_id,
                    'post_content' => $old_content,
                ]);
            }
        }

        public function revision_autosave( $data ){
            if(strpos($data['post_content'], '<!-- Created With Elementor -->') !== false ){
                $data['post_content'] = '<!-- Created With Elementor --><!-- ' . current_time('timestamp') . ' -->';
            }
            return $data;
        }

        public function default_nav_item_controls_data(){
            return [
                'menu_type' => array(
                    'value'   => 'default',
                    'options' => array(
                        array(
                            'label' => esc_html__( 'Default', 'kitify' ),
                            'value' => 'default',
                        ),
                        array(
                            'label' => esc_html__( 'Mega', 'kitify' ),
                            'value' => 'mega',
                        )
                    ),
                ),
                'menu_icon_type' => array(
                    'value'   => 'icon',
                    'options' => array(
                        array(
                            'label' => esc_html__( 'Icon', 'kitify' ),
                            'value' => 'icon',
                        ),
                        array(
                            'label' => esc_html__( 'Svg', 'kitify' ),
                            'value' => 'svg',
                        )
                    ),
                ),
                'menu_icon' => array(
                    'value' => '',
                ),
                'menu_svg' => array(
                    'value' => '',
                ),
                'icon_color' => array(
                    'value' => '',
                ),
                'icon_size' => array(
                    'value' => '',
                ),
                'menu_badge' => array(
                    'value' => '',
                ),
                'badge_color' => array(
                    'value' => '',
                ),
                'badge_bg_color' => array(
                    'value' => '',
                ),
                'hide_item_text' => array(
                    'value' => '',
                ),
                'force_full_width' => array(
                    'value' => '',
                ),
                'menu_max_width' => array(
                    'value' => '',
                ),
            ];
        }

        /**
         * Print tabs templates
         *
         * @return void
         */
        public function print_menu_settings_vue_template() {

            $screen = get_current_screen();

            if ( 'nav-menus' !== $screen->base ) {
                return;
            }

            include kitify()->get_template( 'admin-templates/menu/menu-settings-nav.php' );
        }

        /**
         * [get_nav_item_settings description]
         * @return [type] [description]
         */
        public function get_nav_item_settings() {

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array(
                    'message' => esc_html__( 'You are not allowed to do this', 'kitify' ),
                ) );
            }

            $data = isset( $_POST['data'] ) ? $_POST['data'] : false;

            if ( ! $data ) {
                wp_send_json_error( array(
                    'message' => esc_html__( 'Incorrect input data', 'kitify' ),
                ) );
            }

            $default_settings = array();

            foreach ( $this->default_nav_item_controls_data() as $key => $value ) {
                $default_settings[ $key ] = $value['value'];
            }

            $current_settings = $this->get_item_settings( absint( $data['itemId'] ) );

            $current_settings = wp_parse_args( $current_settings, $default_settings );

            wp_send_json_success( array(
                'message'  => esc_html__( 'Success!', 'kitify' ),
                'settings' => $current_settings,
            ) );
        }

        /**
         * [save_nav_item_settings description]
         * @return [type] [description]
         */
        public function save_nav_item_settings() {

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array(
                    'message' => esc_html__( 'You are not allowed to do this', 'kitify' ),
                ) );
            }

            $data = isset( $_POST['data'] ) ? $_POST['data'] : false;

            if ( ! $data ) {
                wp_send_json_error( array(
                    'message' => esc_html__( 'Incorrect input data', 'kitify' ),
                ) );
            }

            $item_id = $data['itemId'];
            $settings = $data['itemSettings'];

            $sanitized_settings = array();

            foreach ( $settings as $key => $value ) {
                $sanitized_settings[ $key ] = $this->sanitize_field( $key, $value );
            }

            $current_settings = $this->get_item_settings( $item_id );

            $new_settings = array_merge( $current_settings, $sanitized_settings );

            $this->set_item_settings( $item_id, $new_settings );

            do_action( 'kitify/module/menu/item-settings/save' );

            wp_send_json_success( array(
                'message' => esc_html__( 'Item settings have been saved', 'kitify' ),
            ) );
        }

        /**
         * Returns menu item settings
         *
         * @param  [type] $id [description]
         * @return [type]     [description]
         */
        public function get_item_settings( $id ) {
            $settings = get_post_meta( $id, $this->meta_key, true );

            return ! empty( $settings ) ? $settings : array();
        }

        /**
         * Update menu item settings
         *
         * @param integer $id       [description]
         * @param array   $settings [description]
         */
        public function set_item_settings( $id = 0, $settings = array() ) {
            update_post_meta( $id, $this->meta_key, $settings );
        }

        /**
         * Sanitize field
         *
         * @param  [type] $key   [description]
         * @param  [type] $value [description]
         * @return [type]        [description]
         */
        public function sanitize_field( $key, $value ) {

            $specific_callbacks = apply_filters( 'kitify/module/menu/nav-item-settings/sanitize-callbacks', array(
                'icon_size'    => 'absint',
                'menu_badge'   => 'wp_kses_post',
            ) );

            $callback = isset( $specific_callbacks[ $key ] ) ? $specific_callbacks[ $key ] : false;

            if ( ! $callback ) {
                return $value;
            }

            return call_user_func( $callback, $value );
        }

        /**
         * Returns the instance.
         *
         * @since  1.0.0
         * @param  array $args
         * @access public
         * @return object
         */
        public static function get_instance( array $args = array() ) {
            // If the single instance hasn't been set, set it now.
            if ( null == self::$instance ) {
                self::$instance = new self( $args );
            }

            return self::$instance;
        }
    }
}