<?php
namespace KitifyThemeBuilder\Modules\Woocommerce\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base_Products_Renderer extends \WC_Shortcode_Products {

    protected $settings = [];

    private static $has_init = false;

		const DEFAULT_COLUMNS_AND_ROWS = 4;

		protected function get_limit(){
			$settings = $this->settings;
			$rows = ! empty( $settings['rows'] ) ? $settings['rows'] : self::DEFAULT_COLUMNS_AND_ROWS;
			$columns = ! empty( $settings['columns'] ) ? $settings['columns'] : self::DEFAULT_COLUMNS_AND_ROWS;

			return intval( $columns * $rows );
		}
	/**
	 * Override original `get_content` that returns an HTML wrapper even if no results found.
	 *
	 * @return string Products HTML
	 */
	public function get_content() {

		$layout = !empty($this->settings['layout']) ? $this->settings['layout'] : 'grid';
		$preset = !empty($this->settings[ $layout.'_style']) ? $this->settings[ $layout.'_style'] : '1';

		$classes = [
            'products',
            'ul_products',
            'kitify-products__list',
            'products-' . $layout,
            'products-' . $layout . '-' . $preset,
        ];
        if(!empty($this->settings['enable_carousel']) && filter_var($this->settings['enable_carousel'], FILTER_VALIDATE_BOOLEAN)){
            $classes[] = 'swiper-wrapper';
        }
        else{
            $classes[] = 'col-row';
        }

		$content = parent::get_content();

        $content = str_replace( '<ul class="products', '<ul class="'.esc_attr(join(' ', $classes)) , $content );

		return $content;
	}

    /**
     * Get wrapper classes.
     *
     * @since  3.2.0
     * @param  int $columns Number of columns.
     * @return array
     */
    protected function get_wrapper_classes( $columns ) {
        $classes = array( 'woocommerce' );

        $classes[] = $this->attributes['class'];

        if(!empty($this->settings['unique_id'])){
            $classes[] = 'kitify_wc_widget_' . $this->settings['unique_id'];
        }
        if( $this->type === 'current_query' ){
            $classes[] = 'kitify_wc_widget_current_query';
        }

        return $classes;
    }

    protected function override_hook_to_init(){
        add_action('kitify/products/before_render', [ $this, 'override_hook' ] );
        add_action( "woocommerce_shortcode_before_{$this->type}_loop", [ $this, 'setup_before_loop' ]);
    }

    public function setup_before_loop(){
        $layout = !empty($this->settings['layout']) ? $this->settings['layout'] : 'grid';
        $preset = !empty($this->settings[ $layout.'_style']) ? $this->settings[ $layout.'_style'] : '1';

        $allow_extra_filters = false;

        if( ( !empty($this->settings['allow_order']) or !empty($this->settings['show_result_count']) ) && ( $this->settings['allow_order'] === 'yes' or $this->settings['show_result_count'] === 'yes' ) ) {
            $allow_extra_filters = true;
        }
        wc_set_loop_prop('kitify_loop_allow_extra_filters', $allow_extra_filters );

				$enable_category = false;
				if(!empty($this->settings['enable_p_category']) && filter_var( $this->settings['enable_p_category'], FILTER_VALIDATE_BOOLEAN )){
						$enable_category = true;
				}

        $enable_carousel = false;
        if(!empty($this->settings['enable_carousel']) && filter_var($this->settings['enable_carousel'], FILTER_VALIDATE_BOOLEAN)){
            $enable_carousel = true;
        }

        $before = '';

        if(!empty($this->settings['heading'])){
            $html_tag = !empty($this->settings['html_tag']) ? $this->settings['html_tag'] : 'div';
            $html_tag = kitify_helper()->validate_html_tag($html_tag);
            $before .= sprintf('<div class="clear"></div><%1$s class="kitify-heading"><span>%2$s</span></%1$s>', $html_tag, $this->settings['heading']);
        }

        $container_attributes = [];
        $container_classes = ['kitify-products'];
        $wrapper_classes = ['kitify-products__list_wrapper'];
        $loop_item_classes = [];
        $loop_item_classes[] = 'kitify-product';
        $loop_item_classes[] = 'product_item';

        if($enable_carousel){
            $container_classes[] = 'kitify-carousel';
            $carousel_settings = [];
            if(!empty($this->settings['kitify_extra_settings']['carousel_settings'])){
                $carousel_settings = $this->settings['kitify_extra_settings']['carousel_settings'];
            }
            $container_attributes[] = 'data-slider_options="'. esc_attr( json_encode($carousel_settings) ) .'"';
            $container_attributes[] = 'dir="'. (is_rtl() ? 'rtl' : 'ltr') .'"';
            $loop_item_classes[] = 'swiper-slide';
        }
        elseif(!empty($this->settings['kitify_extra_settings']['masonry_settings'])){
            $container_classes[] = 'kitify-masonry-wrapper';
            $container_attributes[] = $this->settings['kitify_extra_settings']['masonry_settings'];
        }
        if(!$enable_carousel){
            $loop_item_classes[] = kitify_helper()->col_new_classes('columns', $this->settings);
        }

        if( $this->type === 'current_query' ){
            $container_attributes[] = 'data-widget_current_query="yes"';
        }

        $before .= '<div class="'.esc_attr( join(' ', $container_classes) ).'" '. join(' ', $container_attributes) .'>';
        if($enable_carousel){
            $before .= '<div class="kitify-carousel-inner">';
            $wrapper_classes[] = 'swiper-container';
        }

        wc_set_loop_prop('kitify_loop_item_classes', $loop_item_classes );

        $has_masonry_filter = false;

        if(!empty($this->settings['kitify_extra_settings']['masonry_filter'])){
            $before .= $this->settings['kitify_extra_settings']['masonry_filter'];

            $has_masonry_filter = true;
        }

        $before .= '<div class="'.esc_attr(join(' ', $wrapper_classes)).'">';

        wc_set_loop_prop('kitify_loop_before', $before );
        wc_set_loop_prop('kitify_has_masonry_filter', $has_masonry_filter );

        $after = '</div>';
        if($enable_carousel){
            $after .= '</div>';
            if(!empty($this->settings['kitify_extra_settings']['carousel_dot_html'])){
                $after .= $this->settings['kitify_extra_settings']['carousel_dot_html'];
            }
            if(!empty($this->settings['kitify_extra_settings']['carousel_arrow_html'])){
                $after .= $this->settings['kitify_extra_settings']['carousel_arrow_html'];
            }
            if(!empty($this->settings['kitify_extra_settings']['carousel_scrollbar_html'])){
                $after .= $this->settings['kitify_extra_settings']['carousel_scrollbar_html'];
            }
        }
        $after .= '</div>';

        wc_set_loop_prop('kitify_loop_after', $after );

        wc_set_loop_prop('kitify_layout', $layout);
        wc_set_loop_prop('kitify_preset', $preset);
        wc_set_loop_prop('kitify_type', $this->type );
        wc_set_loop_prop('grid_style', $this->settings['grid_style'] );
        wc_set_loop_prop('kitify_enable_carousel', $enable_carousel );

        $item_html_tag = !empty($this->settings['item_html_tag']) ? $this->settings['item_html_tag'] : 'h2';
        wc_set_loop_prop('kitify_item_html_tag', $item_html_tag );

        $image_size = 'woocommerce_thumbnail';
        $enable_alt_image = false;
				$enable_stock_progress_bar = false;
        $enable_custom_image_size = !empty($this->settings['enable_custom_image_size']) && filter_var($this->settings['enable_custom_image_size'], FILTER_VALIDATE_BOOLEAN);

        if($enable_custom_image_size && !empty($this->settings['image_size'])){
            $image_size = $this->settings['image_size'];
        }
        if(!empty($this->settings['enable_alt_image']) && filter_var( $this->settings['enable_alt_image'], FILTER_VALIDATE_BOOLEAN )){
            $enable_alt_image = true;
        }
        if(!empty($this->settings['enable_stock_progress_bar']) && filter_var( $this->settings['enable_stock_progress_bar'], FILTER_VALIDATE_BOOLEAN )){
            $enable_stock_progress_bar = true;
        }


        wc_set_loop_prop('kitify_enable_alt_image', $enable_alt_image );
        wc_set_loop_prop('kitify_enable_stock_progress_bar', $enable_stock_progress_bar );
        wc_set_loop_prop('kitify_enable_product_cat', $enable_category );
        wc_set_loop_prop('kitify_image_size', $image_size );

    }

    public function override_hook(){

        add_filter( 'woocommerce_pagination_args', [ $this, 'woocommerce_pagination_args' ], 1001  );
        if( !self::$has_init ){
            self::$has_init = true;
            $this->override_loop_hook();
        }
    }

    public function woocommerce_pagination_args( $args ){
        if( $this->type == 'products' && !empty($this->settings['unique_id']) ){
            $page_key = 'product-page-' . $this->settings['unique_id'];
            $args['base'] = esc_url_raw( add_query_arg( $page_key, '%#%', false ) );
            $args['format'] = '?'.$page_key.'=%#%';
        }
        return $args;
    }

    private function override_loop_hook(){
        if( ! kitify()->get_theme_support('kitify-woo::product-loop') ){

						add_action('woocommerce_before_shop_loop',  'kitify_setup_toolbar' , -999 );
						add_action('woocommerce_before_shop_loop',  'kitify_add_toolbar_open' , 15 );
						add_action('woocommerce_before_shop_loop',  'kitify_add_toolbar_close' , 35 );
						add_action( 'nova_woocommerce_catalog_ordering', 'kitify_add_grid_list_display', 35, 0 );

            add_action('kitify/products/action/shop_loop_item_button', 'woocommerce_template_loop_add_to_cart', 10);

						add_action('kitify/products/action/shop_loop_item_footer', 'woocommerce_template_loop_add_to_cart', 10);

            remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
            remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
						remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
						remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

            add_action( 'woocommerce_before_shop_loop_item', [ $this, 'loop_item_open' ], -1001 );
            add_action( 'woocommerce_after_shop_loop_item', [ $this, 'loop_item_close' ], 1001 );

            add_action('woocommerce_before_shop_loop_item_title', [ $this, 'loop_item_thumbnail_open' ], -1001 );
            add_action('woocommerce_before_shop_loop_item_title', [ $this, 'loop_item_thumbnail_close' ], 1001 );

            add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', -101 );
            add_action('woocommerce_before_shop_loop_item_title', [ $this, 'add_product_thumbnails_to_loop' ], 15 );
            add_action('woocommerce_before_shop_loop_item_title', [ $this, 'loop_item_thumbnail_overlay' ], 100 );
            add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 101 );

						remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
						add_action( 'kitify/products/action/shop_loop_item_after_title', 'woocommerce_template_loop_price', 10);

            add_action('woocommerce_shop_loop_item_title', [ $this, 'loop_item_info_open' ], -101 );
            add_action('kitify/products/action/shop_loop_item_title', [ $this, 'loop_item_add_product_title' ], 10 );
            add_action('kitify/products/action/shop_loop_item_title', [ $this, 'add_product_loop_category' ], 15 );
            add_action('woocommerce_after_shop_loop_item', [ $this, 'loop_item_info_close' ], 101 );

						if( 1 == $this->settings['grid_style'] && !empty($this->settings['enable_p_rating']) ) {
							add_action( 'kitify/products/action/shop_loop_item_after_title', 'woocommerce_template_loop_rating', 15);
						}
						if( 2 == $this->settings['grid_style']) {
							add_action('woocommerce_after_shop_loop_item', [ $this, 'loop_item_footer_open' ], 150 );
							add_action('woocommerce_after_shop_loop_item', [ $this, 'loop_item_footer_close' ], 160 );
							add_action('woocommerce_after_shop_loop_item', [ $this, 'loop_item_hover_box' ], 2000 );
							remove_action('kitify/products/action/shop_loop_item_button', 'woocommerce_template_loop_add_to_cart', 10);
							if( !empty($this->settings['enable_p_rating']) ) {
								add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 15);
							}
						}
						if( !empty($this->settings['enable_p_summary']) ){
								add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'loop_item_short_description' ], 20);
						}

            remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
            add_action( 'woocommerce_after_shop_loop', [ $this, 'woocommerce_pagination' ], 10 );
        }else{
					do_action( 'kitify/wooproduct/loop_action' );
				}
    }
    public function loop_item_open(){
        echo '<div class="product-item">';
    }
    public function loop_item_close(){
        echo '</div>';
    }
    public function loop_item_thumbnail_open(){
        echo '<div class="product-item__thumbnail">';
				echo '<a class="product-item-link" href="'.get_the_permalink().'"></a>';
				echo '<div class="product-item__description--actions">';
				do_action('kitify/products/action/shop_loop_item_action');
				echo '</div>';
				do_action('kitify/products/action/shop_loop_item_button');
				echo '<div class="product-item__badges">';
				do_action( 'woocommerce_product_badges' );
				echo '</div>';
        echo '<div class="product-item__thumbnail-placeholder">';
    }
    public function loop_item_thumbnail_close(){
            echo '</div>';
        echo '</div>';
    }
    public function loop_item_thumbnail_overlay(){
        echo '<div class="product-item__thumbnail_overlay"></div>';
    }

    public function loop_item_info_open(){
        echo '<div class="product-item__description">';
            echo '<div class="product-item__description--info">';
						echo '<div class="info-left">';
						do_action('kitify/products/action/shop_loop_item_title');
						echo '</div>';
						echo '<div class="info-right">';
						do_action('kitify/products/action/shop_loop_item_after_title');
						echo '</div>';

    }
    public function loop_item_info_close(){
            echo '</div>';
        echo '</div>';
    }

		public function loop_item_short_description() {
			global $product;
			echo '<div class="product-short-description">';
			echo $product->get_short_description();
			echo '</div>';
		}

    public function loop_item_footer_open(){
    	echo '<div class="product-item-footer">';
			do_action( 'kitify/products/action/shop_loop_item_footer' );
    }
		public function loop_item_footer_close(){
			echo '</div>';
		}
		public function loop_item_hover_box(){
			echo '<div class="product-item-hover"></div>';
		}

    public function loop_item_add_product_title(){
        $html_tag = wc_get_loop_prop('kitify_item_html_tag', 'h2');
        $html_tag = kitify_helper()->validate_html_tag($html_tag);
        the_title( sprintf( '<a href="%1s" class="title"><%2$s class="woocommerce-loop-product__title">', esc_url( get_the_permalink() ), $html_tag ), sprintf('</%1$s></a>', $html_tag) );
    }

    public function add_product_thumbnails_to_loop(){
        $image_size = wc_get_loop_prop('kitify_image_size', 'woocommerce_thumbnail');
        $enable_alt_image = wc_get_loop_prop('kitify_enable_alt_image', false);

        global $product;

        $output = woocommerce_get_product_thumbnail( $image_size );
        if($enable_alt_image){
            $gallery_image_ids = $product->get_gallery_image_ids();
            if(!empty($gallery_image_ids[0])){
                $image_url = wp_get_attachment_image_url($gallery_image_ids[0], $image_size);
								$output .= sprintf('<span class="product_second_image" style="background-image: url(\'%1$s\')"></span>', esc_url( $image_url ));
            }
        }
        echo $output;
    }
		public function add_product_loop_category() {
			global $product;
			$enable_category = wc_get_loop_prop('kitify_enable_product_cat', false);

			if($enable_category) {
				$categories = explode(', ', wc_get_product_category_list( $product->get_id() ) );
				$categories = array_filter( $categories );
				$i = 0;
				if ( !empty( $categories ) ) :
					echo '<div class="product-item__category">';
					foreach ( $categories as $category ):
						if ( $i < 1 ) {
						echo preg_replace('/(<a)(.+\/a>)/i', '${1} class="content-product-cat" ${2}', $category);
						}
						$i++;
					endforeach;
					echo '</div>';
				endif;
			}
		}

    public function woocommerce_pagination(){
        ob_start();
        woocommerce_pagination();
        $output = ob_get_clean();

        $output = str_replace('woocommerce-pagination', 'woocommerce-pagination kitify-pagination clearfix kitify-ajax-pagination', $output);

        echo $output;
    }

}
