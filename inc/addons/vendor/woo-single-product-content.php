<?php

/**
 * Class: Kitify_Woo_Single_Product_Content
 * Name: Product Content
 * Slug: kitify-wooproduct-content
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Woo_Single_Product_Content extends Kitify_Post_Content {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function show_in_panel() {
        return true;
    }

    public function get_name() {
        return 'kitify-wooproduct-content';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }

    public function get_keywords() {
        return [ 'content', 'post', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Content', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-woocommerce-pages';
    }

    protected function register_controls() {
        parent::register_controls();
    }

}
