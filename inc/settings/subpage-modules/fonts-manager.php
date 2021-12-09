<?php
namespace Kitify_Dashboard\Settings;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Kitify_Dashboard\Base\Page_Module as Page_Module_Base;
use Kitify_Dashboard\Dashboard as Dashboard;

class Fonts_Manager extends Page_Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'kitify-fonts-manager';
	}

	/**
	 * [get_subpage_slug description]
	 * @return [type] [description]
	 */
	public function get_parent_slug() {
		return 'settings-page';
	}

	/**
	 * [get_page_name description]
	 * @return [type] [description]
	 */
	public function get_page_name() {
		return esc_html__( 'Fonts Manager', 'kitify' );
	}

	/**
	 * [get_category description]
	 * @return [type] [description]
	 */
	public function get_category() {
		return 'kitify-settings';
	}

	/**
	 * [get_page_link description]
	 * @return [type] [description]
	 */
	public function get_page_link() {
		return Dashboard::get_instance()->get_dashboard_page_url( $this->get_parent_slug(), $this->get_page_slug() );
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_style(
			'kitify-admin-css',
			kitify()->plugin_url( 'assets/css/kitify-admin.css' ),
			false,
			kitify()->get_version()
		);

        wp_enqueue_script(
            'kitify-admin-font-variations',
            kitify()->plugin_url( 'assets/js/admin/fonts-manager.js' ),
            array( 'cx-vue-ui' ),
            kitify()->get_version(),
            true
        );

		wp_enqueue_script(
			'kitify-admin-script',
			kitify()->plugin_url( 'assets/js/kitify-admin-vue-components.js' ),
			array( 'kitify-admin-font-variations' ),
			kitify()->get_version(),
			true
		);

		wp_localize_script(
			'kitify-admin-script',
			'KitifySettingsConfig',
			apply_filters( 'kitify/admin/settings-page/localized-config', kitify_settings()->generate_frontend_config_data() )
		);

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $page = false, $subpage = false ) {

		$config['pageModule'] = $this->get_parent_slug();
		$config['subPageModule'] = $this->get_page_slug();

		return $config;
	}

	/**
	 * [page_templates description]
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $page = false, $subpage = false ) {

		$templates['kitify-fonts-manager'] = kitify()->plugin_path( 'templates/admin-templates/fonts-manager.php' );

		return $templates;
	}

    public function create() {
        add_action( 'admin_footer', array( __CLASS__, 'add_variations_template' ) );
    }

    /**
     * Adds template for meta fields component
     */
    public static function add_variations_template() {
        ob_start();
        include kitify()->plugin_path( 'templates/admin-templates/fonts-manager/repeat.php' );
        $content = ob_get_clean();
        printf( '<script type="text/x-template" id="kitify-x-tmpl-fonts-manager">%s</script>', $content );
    }
}
