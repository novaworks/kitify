<?php
namespace KitifyThemeBuilder\Modules\Woocommerce;

use Elementor\Core\Documents_Manager;
use Elementor\Settings;
use KitifyThemeBuilder\Modules\ThemeBuilder\Classes\Conditions_Manager;
use KitifyThemeBuilder\Modules\Woocommerce\Conditions\Woocommerce;
use KitifyThemeBuilder\Modules\Woocommerce\Documents\Product;
use KitifyThemeBuilder\Modules\Woocommerce\Documents\Product_Post;
use KitifyThemeBuilder\Modules\Woocommerce\Documents\Product_Archive;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends \Elementor\Core\Base\Module {

    const WOOCOMMERCE_GROUP = 'woocommerce';

	protected $docs_types = [];

	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public static function is_product_search() {
		return is_search() && 'product' === get_query_var( 'post_type' );
	}

	public function get_name() {
		return 'woocommerce';
	}

    public function register_tags() {
        $tags = [
            'Product_Gallery',
            'Product_Image',
            'Product_Price',
            'Product_Rating',
            'Product_Sale',
            'Product_Short_Description',
            'Product_SKU',
            'Product_Stock',
            'Product_Terms',
            'Product_Title',
            'Category_Image',
        ];

        /** @var \Elementor\Core\DynamicTags\Manager $module */
        $module = kitify()->elementor()->dynamic_tags;

        $module->register_group( self::WOOCOMMERCE_GROUP, [
            'title' => esc_html__( 'WooCommerce', 'kitify' ),
        ] );

        foreach ( $tags as $tag ) {
            $tag = '\KitifyThemeBuilder\\Modules\\Woocommerce\\tags\\' . $tag;

            $module->register( new $tag() );
        }
    }

	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	/**
	 * @param Conditions_Manager $conditions_manager
	 */
	public function register_conditions( $conditions_manager ) {
		$woocommerce_condition = new Woocommerce();

		$conditions_manager->get_condition( 'general' )->register_sub_condition( $woocommerce_condition );
	}

	/**
	 * @param Documents_Manager $documents_manager
	 */
	public function register_documents( $documents_manager ) {
		$this->docs_types = [
			'product-post' => Product_Post::get_class_full_name(),
			'product' => Product::get_class_full_name(),
			'product-archive' => Product_Archive::get_class_full_name(),
		];

		foreach ( $this->docs_types as $type => $class_name ) {
			$documents_manager->register_document_type( $type, $class_name );
		}
	}


	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
			WC()->cart = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	public function localized_settings_frontend( $settings ) {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( $has_cart ) {
			$settings['menu_cart'] = [
				'cart_page_url' => wc_get_cart_url(),
				'checkout_page_url' => wc_get_checkout_url(),
			];
		}
		return $settings;
	}

	public function theme_template_include( $need_override_location, $location ) {
		if ( is_product() && 'single' === $location ) {
			$need_override_location = true;
		}

		return $need_override_location;
	}


	/**
	 * WooCommerce/WordPress widget(s), some of the widgets have css classes that used by final selectors.
	 * before this filter, all those widgets were warped by `.elementor-widget-container` without chain original widget
	 * classes, now they will be warped by div with the original css classes.
	 *
	 * @param array $default_widget_args
	 * @param \Elementor\Widget_WordPress $widget
	 *
	 * @return array $default_widget_args
	 */
	public function woocommerce_wordpress_widget_css_class( $default_widget_args, $widget ) {
		$widget_instance = $widget->get_widget_instance();

		if ( ! empty( $widget_instance->widget_cssclass ) ) {
			$default_widget_args['before_widget'] .= '<div class="' . $widget_instance->widget_cssclass . '">';
			$default_widget_args['after_widget'] .= '</div>';
		}

		return $default_widget_args;
	}

	public function woocommerce_product_loop_start( $output ){

	    $extra = wc_get_loop_prop('kitify_loop_before', '');

	    return $extra . $output;
    }

    public function woocommerce_product_loop_end( $output ){
        $extra = wc_get_loop_prop('kitify_loop_after', '');

        return $output . $extra;
    }

    /**
     * @param $classes
     * @param $product \WC_Product
     * @return array
     */
    public function add_css_class_to_product_item_class( $classes, $product ){
        $has_filter = wc_get_loop_prop('kitify_has_masonry_filter');
        $kitify_loop_item_classes = wc_get_loop_prop('kitify_loop_item_classes', []);
        if($has_filter){
            $classes = array_merge($classes, kitify_helper()->get_post_terms($product->get_id(), 'id'));
        }
        if(!empty($kitify_loop_item_classes)){
            $classes = array_merge($classes, $kitify_loop_item_classes);
        }
        return $classes;
    }

    public function add_css_class_to_product_cat_item( $classes ){
        $kitify_loop_item_classes = wc_get_loop_prop('kitify_loop_item_classes', []);
        if(!empty($kitify_loop_item_classes)){
            $classes = array_merge($classes, $kitify_loop_item_classes);
        }
        return $classes;
    }

    public function add_quickview_resource(){
        global $product;
        if (function_exists('is_product') && isset($_GET['product_quickview']) && is_product()) {
            ?>
            <script type="text/javascript" src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/flexslider/jquery.flexslider.min.js' ?>"></script>
            <?php
            if ($product->get_type() == 'variable') {
                wp_print_scripts('underscore');
                wc_get_template('single-product/add-to-cart/variation.php');
                ?>
                <script type="text/javascript">
                    /* <![CDATA[ */
                    var _wpUtilSettings = <?php echo wp_json_encode(array(
                        'ajax' => array('url' => admin_url('admin-ajax.php', 'relative'))
                    ));?>;
                    var wc_add_to_cart_variation_params = <?php
                        $params = array(
                            'wc_ajax_url'                      => \WC_AJAX::get_endpoint( '%%endpoint%%' ),
                            'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'woocommerce' ),
                            'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'woocommerce' ),
                            'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' ),
                        );
                        echo wp_json_encode($params); ?>;
                    /* ]]> */
                </script>
                <script type="text/javascript" src="<?php echo esc_url(includes_url('js/wp-util.min.js')) ?>"></script>
                <script type="text/javascript" src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/frontend/add-to-cart-variation.min.js' ?>"></script>
                <?php
            }
            ?>
            <script type="text/javascript">
                /* <![CDATA[ */
                var wc_single_product_params = <?php echo wp_json_encode(array(
                    'i18n_required_rating_text' => esc_attr__('Please select a rating', 'woocommerce'),
                    'review_rating_required' => get_option('woocommerce_review_rating_required'),
                    'flexslider' => apply_filters('woocommerce_single_product_carousel_options', array(
                        'rtl' => is_rtl(),
                        'animation' => 'slide',
                        'smoothHeight' => false,
                        'directionNav' => true,
                        'controlNav' => '',
                        'slideshow' => false,
                        'animationSpeed' => 500,
                        'animationLoop' => false, // Breaks photoswipe pagination if true.
                    )),
                    'zoom_enabled' => 0,
                    'photoswipe_enabled' => 0,
                    'flexslider_enabled' => 1,
                ));?>;
                /* ]]> */
            </script>
            <script type="text/javascript" src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/frontend/single-product.min.js' ?>"></script>
            <?php
        }
    }

	public function __construct() {
		parent::__construct();

        if(!kitify()->has_elementor_pro()){
            add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'maybe_init_cart' ] );
            add_action( 'elementor/dynamic_tags/register', [ $this, 'register_tags' ] );
            add_action( 'elementor/documents/register', [ $this, 'register_documents' ] );
            add_action( 'elementor/theme/register_conditions', [ $this, 'register_conditions' ] );
            add_filter( 'elementor/theme/need_override_location', [ $this, 'theme_template_include' ], 10, 2 );
            add_filter( 'elementor/frontend/localize_settings', [ $this, 'localized_settings_frontend' ] );
            // On Editor - Register WooCommerce frontend hooks before the Editor init.
            // Priority = 5, in order to allow plugins remove/add their wc hooks on init.
            if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
                add_action( 'init', [ $this, 'register_wc_hooks' ], 5 );
            }
        }

		add_filter( 'elementor/widgets/wordpress/widget_args', [ $this, 'woocommerce_wordpress_widget_css_class' ], 10, 2 );
        add_filter( 'woocommerce_product_loop_start', [ $this, 'woocommerce_product_loop_start' ], -1001 );
        add_filter( 'woocommerce_product_loop_end', [ $this, 'woocommerce_product_loop_end' ], 1001  );
        add_filter( 'woocommerce_post_class', [ $this, 'add_css_class_to_product_item_class' ], 1001, 2 );
        add_filter( 'product_cat_class', [ $this, 'add_css_class_to_product_cat_item' ], 1001, 1 );
        add_action('woocommerce_after_single_product', [ $this, 'add_quickview_resource' ] );
        add_action( 'woocommerce_shortcode_current_query_loop_no_results', 'wc_no_products_found');

	}
}
