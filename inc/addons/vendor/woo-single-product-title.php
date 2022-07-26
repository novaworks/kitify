<?php

/**
 * Class: Kitify_Woo_Single_Product_Title
 * Name: Product Title
 * Slug: kitify-wooproduct-title
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Widget
 */
class Kitify_Woo_Single_Product_Title extends Kitify_Post_Title {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-wooproduct-title';
    }

    public function get_categories() {
        return [ 'kitify-woo-product' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'title', 'heading', 'product' ];
    }

    public function get_widget_title() {
        return esc_html__( 'Product Title', 'kitify' );
    }

    public function get_icon() {
      return 'kitify-icon-archive-title';
    }

    protected function register_controls() {
        parent::register_controls();


        $this->update_control(
            'html_tag',
            [
                'default' => 'h1',
            ]
        );
    }

}
