<?php

use Elementor\Core\Base\App as BaseApp;
use KitifyThemeBuilder\Modules\AdminApp\Modules\SiteEditor\Module as SiteEditor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class KitifyThemeBuilder_AdminApp extends BaseApp
{
    /**
     * Get module name.
     *
     * Retrieve the module name.
     *
     * @return string Module name.
     * @since 3.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'app-pro';
    }

    public function init()
    {
        $this->enqueue_assets();
    }

    public function set_menu_url()
    {
        kitify()->elementor()->app->set_settings('menu_url', kitify()->elementor()->app->get_base_url() . '#/site-editor');
    }

    protected function get_init_settings()
    {
        return [
            'baseUrl' => $this->get_assets_base_url(),
        ];
    }

    protected function get_assets_base_url()
    {
        return kitify()->plugin_url('/inc/');
    }

    private function enqueue_assets()
    {
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'ELEMENTOR_TESTS' ) && ELEMENTOR_TESTS ) ? '' : '.min';
//        $suffix = '';
        wp_enqueue_style(
            'kitify-elementor-app',
            kitify()->plugin_url("inc/modules/admin-app/assets/app{$suffix}.css"),
            [
                'elementor-app',
                'select2',
            ],
            kitify()->get_version(true)
        );

        wp_enqueue_script(
            'kitify-elementor-app',
            kitify()->plugin_url("inc/modules/admin-app/assets/app{$suffix}.js"),
            [
                'wp-i18n',
                'elementor-app-packages',
                'elementor-common',
                'select2',
            ],
            kitify()->get_version(true),
            true
        );

        wp_set_script_translations('kitify-elementor-app', 'kitify');
    }

    private function enqueue_config()
    {
        // If script didn't loaded, config is still relevant, enqueue without a file.
        if (!wp_script_is('kitify-elementor-app')) {
            wp_register_script('kitify-elementor-app', false, [], kitify()->get_version(true));
            wp_enqueue_script('kitify-elementor-app');
        }

        $this->print_config('kitify-elementor-app');
    }

    public function __construct()
    {
        $this->add_component('site-editor', new SiteEditor());

        add_action('elementor/app/init', [$this, 'init']);

        add_action('elementor/common/after_register_scripts', function () {
            $this->enqueue_config();
        });

        add_action('elementor/init', [$this, 'set_menu_url']);
    }
}
