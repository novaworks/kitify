<?php
namespace Kitify;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Controller class
 */
class Settings {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$subpage_modules description]
	 * @var array
	 */
	public $subpage_modules = array();

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	// Here initialize our namespace and resource name.
	public function __construct() {

	    $subpage_modules = [
            'kitify-general-settings' => array(
                'class' => '\\Kitify_Dashboard\\Settings\\General',
                'args'  => array(),
            ),
            'kitify-integrations-settings' => array(
                'class' => '\\Kitify_Dashboard\\Settings\\Integrations',
                'args'  => array(),
            ),
            'kitify-avaliable-addons' => array(
                'class' => '\\Kitify_Dashboard\\Settings\\Avaliable_Addons',
                'args'  => array(),
            ),
            'kitify-license' => array(
                'class' => '\\Kitify_Dashboard\\Settings\\License',
                'args'  => array(),
            ),
        ];

        if( kitify()->get_theme_support('elementor::custom-fonts') ) {
            $subpage_modules['kitify-fonts-manager'] = array(
                'class' => '\\Kitify_Dashboard\\Settings\\Fonts_Manager',
                'args'  => array(),
            );
        }

		$this->subpage_modules = apply_filters( 'kitify/settings/registered-subpage-modules', $subpage_modules );

		add_action( 'init', array( $this, 'register_settings_category' ), 10 );

		add_action( 'init', array( $this, 'init_plugin_subpage_modules' ), 10 );
	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function register_settings_category() {

	    $branding_label = apply_filters('kitify/branding/name', esc_html__( 'Kitify', 'kitify' ));

		\Kitify_Dashboard\Dashboard::get_instance()->module_manager->register_module_category( array(
			'name'     => $branding_label,
			'slug'     => 'kitify-settings',
			'priority' => 1
		) );
	}

	/**
	 * [init_plugin_subpage_modules description]
	 * @return [type] [description]
	 */
	public function init_plugin_subpage_modules() {
		require kitify()->plugin_path( 'inc/settings/subpage-modules/general.php' );
		require kitify()->plugin_path( 'inc/settings/subpage-modules/integrations.php' );
		require kitify()->plugin_path( 'inc/settings/subpage-modules/avaliable-addons.php' );

        if( kitify()->get_theme_support('elementor::custom-fonts') ) {
            require kitify()->plugin_path('inc/settings/subpage-modules/fonts-manager.php');
        }
		foreach ( $this->subpage_modules as $subpage => $subpage_data ) {
			\Kitify_Dashboard\Dashboard::get_instance()->module_manager->register_subpage_module( $subpage, $subpage_data );
		}
	}

}
