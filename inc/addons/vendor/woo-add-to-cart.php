<?php

/**
 * Class: Kitify_Woo_Add_To_Cart
 * Name: Add To Cart
 * Slug: kitify-addtocart
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Cart Widget
 */
class Kitify_Woo_Add_To_Cart extends Widget_Button {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $this->enqueue_addon_resources();
    }

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-woocommerce' );
        $this->add_script_depends('kitify-base' );
    }

    public function get_name() {
        return 'kitify-addtocart';
    }

    public function get_categories() {
        return [ 'kitify-woocommerce' ];
    }

    public function get_title() {
        return 'Kitify ' . esc_html__( 'Custom Add To Cart', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-woo-add-to-cart';
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' ];
    }

    public function on_export( $element ) {
        unset( $element['settings']['product_id'] );

        return $element;
    }

    public function unescape_html( $safe_text, $text ) {
        return $text;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_product',
            [
                'label' => esc_html__( 'Product', 'kitify' ),
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' =>  esc_html__( 'Product', 'kitify' ),
                'type' => 'kitify-query',
                'options' => [],
                'label_block' => true,
                'autocomplete' => [
                    'object' => 'post',
                    'query' => [
                        'post_type' => [ 'product' ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'show_quantity',
            [
                'label' =>  esc_html__( 'Show Quantity', 'kitify' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' =>  esc_html__( 'Hide', 'kitify' ),
                'label_on' =>  esc_html__( 'Show', 'kitify' ),
                'description' =>  esc_html__( 'Please note that switching on this option will disable some of the design controls.', 'kitify' ),
            ]
        );

        $this->add_control(
            'quantity',
            [
                'label' =>  esc_html__( 'Quantity', 'kitify' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'condition' => [
                    'show_quantity' => '',
                ],
            ]
        );

        $this->end_controls_section();

        parent::register_controls();

        $this->update_control(
            'link',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->update_control(
            'text',
            [
                'default' =>  esc_html__( 'Add to Cart', 'kitify' ),
                'placeholder' =>  esc_html__( 'Add to Cart', 'kitify' ),
            ]
        );

        $this->update_control(
            'selected_icon',
            [
                'default' => [
                    'value' => 'novaicon-shopping-cart-1',
                    'library' => 'novaicon',
                ],
            ]
        );

        $this->update_control(
            'size',
            [
                'condition' => [
                    'show_quantity' => '',
                ],
            ]
        );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['product_id'] ) ) {
            $product_id = $settings['product_id'];
        }
        elseif ( wp_doing_ajax() ) {
            $product_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
        }
        else {
            $product_id = get_queried_object_id();
        }

        global $product;
        $product = wc_get_product( $product_id );

        if ( 'yes' === $settings['show_quantity'] ) {
            $this->render_form_button( $product );
        } else {
            $this->render_ajax_button( $product );
        }
    }

    /**
     * @param \WC_Product $product
     */
    private function render_ajax_button( $product ) {
        $settings = $this->get_settings_for_display();

        if ( $product ) {
            if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
                $product_type = $product->get_type();
            } else {
                $product_type = $product->product_type;
            }

            $class = implode( ' ', array_filter( [
                'product_type_' . $product_type,
                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
            ] ) );

            $this->add_render_attribute( 'button',
                [
                    'rel' => 'nofollow',
                    'href' => $product->add_to_cart_url(),
                    'data-quantity' => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
                    'data-product_id' => $product->get_id(),
                    'class' => $class,
                ]
            );

        } elseif ( current_user_can( 'manage_options' ) ) {
            $settings['text'] =  esc_html__( 'Please set a valid product', 'kitify' );
            $this->set_settings( $settings );
        }

        parent::render();
    }

    private function render_form_button( $product ) {
        if ( ! $product && current_user_can( 'manage_options' ) ) {
            echo  esc_html__( 'Please set a valid product', 'kitify' );

            return;
        }

        $text_callback = function() {
            ob_start();
            $this->render_text();

            return ob_get_clean();
        };

        add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
        add_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
        add_filter( 'esc_html', [ $this, 'unescape_html' ], 10, 2 );

        ob_start();
        woocommerce_template_single_add_to_cart();
        $form = ob_get_clean();
        $form = str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button elementor-button', $form );
        echo $form;

        remove_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
        remove_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
        remove_filter( 'esc_html', [ $this, 'unescape_html' ] );
    }

    protected function content_template() {}

}
