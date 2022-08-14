<?php
namespace KitifyThemeBuilder\Modules\Woocommerce\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Products_Renderer extends Base_Products_Renderer {

	const QUERY_CONTROL_NAME = 'query'; //Constraint: the class that uses the renderer, must use the same name

    public static $displayed_ids = [];

	public function __construct( $settings = [], $type = 'products' ) {

        $this->settings = $settings;
        $this->type = $type;
        $this->attributes = $this->parse_attributes( [
            'columns' => $settings['columns'],
            'rows' => $settings['rows'],
            'paginate' => $settings['paginate'],
            'cache' => false,
        ] );
        $this->query_args = $this->parse_query_args();

        $this->override_hook_to_init();
	}

	/**
	 * Override the original `get_query_results`
	 * with modifications that:
	 * 1. Remove `pre_get_posts` action if `is_added_product_filter`.
	 *
	 * @return bool|mixed|object
	 */

    protected function override_query_results(){

        $is_filtered = false;

        if(isset($this->settings['is_filter_container'])){
            $is_filtered = wc_string_to_bool( $this->settings['is_filter_container'] ) === true;
        }

        if( !empty($this->settings['query_post_type']) && in_array( $this->settings['query_post_type'], ['related', 'upsells'] )){
            $is_filtered = false;
        }

        if($is_filtered){
            $cache = false;
        }
        else{
            $transient_name    = $this->get_transient_name();
            $transient_version = \WC_Cache_Helper::get_transient_version( 'product_query' );
            $cache             = wc_string_to_bool( $this->attributes['cache'] ) === true;
        }

        $transient_value   = $cache ? get_transient( $transient_name ) : false;

        if ( isset( $transient_value['value'], $transient_value['version'] ) && $transient_value['version'] === $transient_version ) {
            $results = $transient_value['value'];
        }
        else {

            $query = new \WP_Query( $this->get_query_args() );

            $paginated = ! $query->get( 'no_found_rows' );

            $posts = $query->posts;

            if($is_filtered){
                WC()->query->product_query($query);
                $posts = $query->get_posts();
            }

            $results = (object) array(
                'ids'          => wp_parse_id_list( $posts ),
                'total'        => $paginated ? (int) $query->found_posts : count( $posts ),
                'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
                'per_page'     => (int) $query->get( 'posts_per_page' ),
                'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
            );

            if ( $cache ) {
                $transient_value = array(
                    'version' => $transient_version,
                    'value'   => $results,
                );
                set_transient( $transient_name, $transient_value, DAY_IN_SECONDS * 30 );
            }
        }

        // Remove ordering query arguments which may have been added by get_catalog_ordering_args.
        WC()->query->remove_ordering_args();

        /**
         * Filter shortcode products query results.
         *
         * @since 4.0.0
         * @param \stdClass $results Query results.
         * @param \WC_Shortcode_Products $this WC_Shortcode_Products instance.
         */
        return apply_filters( 'woocommerce_shortcode_products_query_results', $results, $this );
    }

	protected function get_query_results() {

        $prefix = self::QUERY_CONTROL_NAME . '_';

        if ( 'upsells' === $this->settings[ $prefix . 'post_type' ] || 'related' === $this->settings[ $prefix . 'post_type' ] ) {
            $query_args = $this->get_query_args();
            if(empty($query_args['post__in'])){
                return false;
            }
        }

//		$results = parent::get_query_results();
		$results = $this->override_query_results();

        if ( $results && $results->ids ) {
            self::add_to_avoid_list($results->ids);
        }

		return $results;
	}

	protected function parse_query_args() {
		$prefix = self::QUERY_CONTROL_NAME . '_';
		$settings = $this->settings;

		$query_args = [
			'post_type' => 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows' => false === wc_string_to_bool( $this->attributes['paginate'] ),
			'orderby' => $settings[ $prefix . 'orderby' ],
			'order' => strtoupper( $settings[ $prefix . 'order' ] ),
		];

		$query_args['meta_query'] = WC()->query->get_meta_query();
		$query_args['tax_query'] = [];

		$front_page = is_front_page();

		if ( 'yes' === $settings['paginate'] && ( isset($settings['allow_order']) && 'yes' === $settings['allow_order']) && ! $front_page ) {
			$ordering_args = WC()->query->get_catalog_ordering_args();
		} else {
			$ordering_args = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
		}

		$query_args['orderby'] = $ordering_args['orderby'];
		$query_args['order'] = $ordering_args['order'];
		if ( $ordering_args['meta_key'] ) {
			$query_args['meta_key'] = $ordering_args['meta_key'];
		}

		// Visibility.
		$this->set_visibility_query_args( $query_args );

		//Featured.
		$this->set_featured_query_args( $query_args );

		//Sale.
		$this->set_sale_products_query_args( $query_args );

		// IDs.
		$this->set_ids_query_args( $query_args );

		// Set specific types query args.
		if ( method_exists( $this, "set_{$this->type}_query_args" ) ) {
			$this->{"set_{$this->type}_query_args"}( $query_args );
		}

        // Attributes.
        $this->set_attributes_query_args( $query_args );

		// Categories & Tags
		$this->set_terms_query_args( $query_args );

		//Exclude.
		$this->set_exclude_query_args( $query_args );

        //Avoid Duplicates
        $this->set_avoid_duplicates( $query_args );

        // Set Related Query;
        $this->set_related_query_args( $query_args );

        // Set UpSell Query;
        $this->set_upsells_query_args( $query_args );

		if ( wc_string_to_bool($settings['paginate']) ) {

		    $page_key = 'product-page';

		    if(!empty($this->settings['unique_id'])){
                $page_key .= '-' . $this->settings['unique_id'];
            }

			$page = absint( empty( $_REQUEST[$page_key] ) ? 1 : $_REQUEST[$page_key] );

			if ( $page > 1 ) {
				$query_args['paged'] = $page;
                $this->attributes['page'] = $page;
			}

            if( kitify()->get_theme_support('elementor::product-grid-v2') ){
                remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
                remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
            }
            else{
                if ( 'yes' !== $settings['allow_order'] || $front_page ) {
                    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
                }

                if ( 'yes' !== $settings['show_result_count'] ) {
                    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
                }
            }
		}
		// fallback to the widget's default settings in case settings was left empty:
		$query_args['posts_per_page'] = $this->get_limit();

		$query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );

		// Always query only IDs.
		$query_args['fields'] = 'ids';

		return $query_args;
	}

	protected function set_ids_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';

		switch ( $this->settings[ $prefix . 'post_type' ] ) {
			case 'by_id':
				$post__in = $this->settings[ $prefix . 'posts_ids' ];
				break;
			case 'sale':
				$post__in = wc_get_product_ids_on_sale();
				break;
		}

		if ( ! empty( $post__in ) ) {
			$query_args['post__in'] = $post__in;
			remove_action( 'pre_get_posts', [ wc()->query, 'product_query' ] );
		}
	}

	private function set_terms_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';

		$query_type = $this->settings[ $prefix . 'post_type' ];

		if ( 'by_id' === $query_type || 'current_query' === $query_type ) {
			return;
		}

		if ( empty( $this->settings[ $prefix . 'include' ] ) || empty( $this->settings[ $prefix . 'include_term_ids' ] ) || ! in_array( 'terms', $this->settings[ $prefix . 'include' ], true ) ) {
			return;
		}

		$terms = [];
		foreach ( $this->settings[ $prefix . 'include_term_ids' ] as $id ) {
			$term_data = get_term_by( 'term_taxonomy_id', $id );
			if($term_data){
				$taxonomy = $term_data->taxonomy;
				$terms[ $taxonomy ][] = $id;
			}
		}
		$tax_query = [];

		foreach ( $terms as $taxonomy => $ids ) {
			$query = [
				'taxonomy' => $taxonomy,
				'field' => 'term_taxonomy_id',
				'terms' => $ids,
			];

			$tax_query[] = $query;
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = array_merge( $query_args['tax_query'], $tax_query );
		}
	}

	protected function set_featured_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';
		if ( 'featured' === $this->settings[ $prefix . 'post_type' ] ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();

			$query_args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => [ $product_visibility_term_ids['featured'] ],
			];
		}
	}

	protected function set_sale_products_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';
		if ( 'sale' === $this->settings[ $prefix . 'post_type' ] ) {
			parent::set_sale_products_query_args( $query_args );
		}
	}

	protected function set_exclude_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';

		if ( empty( $this->settings[ $prefix . 'exclude' ] ) ) {
			return;
		}
		$post__not_in = [];
		if ( in_array( 'current_post', $this->settings[ $prefix . 'exclude' ] ) ) {
			if ( is_singular() ) {
				$post__not_in[] = get_queried_object_id();
			}
		}

		if ( in_array( 'manual_selection', $this->settings[ $prefix . 'exclude' ] ) && ! empty( $this->settings[ $prefix . 'exclude_ids' ] ) ) {
			$post__not_in = array_merge( $post__not_in, $this->settings[ $prefix . 'exclude_ids' ] );
		}

		$query_args['post__not_in'] = empty( $query_args['post__not_in'] ) ? $post__not_in : array_merge( $query_args['post__not_in'], $post__not_in );

		/**
		 * WC populates `post__in` with the ids of the products that are on sale.
		 * Since WP_Query ignores `post__not_in` once `post__in` exists, the ids are filtered manually, using `array_diff`.
		 */
		if ( 'sale' === $this->settings[ $prefix . 'post_type' ] ) {
			$query_args['post__in'] = array_diff( $query_args['post__in'], $query_args['post__not_in'] );
		}

		if ( in_array( 'terms', $this->settings[ $prefix . 'exclude' ] ) && ! empty( $this->settings[ $prefix . 'exclude_term_ids' ] ) ) {
			$terms = [];
			foreach ( $this->settings[ $prefix . 'exclude_term_ids' ] as $to_exclude ) {
				$term_data = get_term_by( 'term_taxonomy_id', $to_exclude );
				$terms[ $term_data->taxonomy ][] = $to_exclude;
			}
			$tax_query = [];
			foreach ( $terms as $taxonomy => $ids ) {
				$tax_query[] = [
					'taxonomy' => $taxonomy,
					'field' => 'term_id',
					'terms' => $ids,
					'operator' => 'NOT IN',
				];
			}
			if ( empty( $query_args['tax_query'] ) ) {
				$query_args['tax_query'] = $tax_query;
			} else {
				$query_args['tax_query']['relation'] = 'AND';
				$query_args['tax_query'][] = $tax_query;
			}
		}
	}

	protected function set_related_query_args( &$query_args ){
        $prefix = self::QUERY_CONTROL_NAME . '_';
        if ( 'related' === $this->settings[ $prefix . 'post_type' ] ) {
            global $product;

            $product = wc_get_product();

            if ( ! $product ) {
                return;
            }

            $related_ids = wc_get_related_products( $product->get_id(),  $this->get_limit() , $product->get_upsell_ids() );

            $query_args['post__in'] = $related_ids;
        }
    }

	protected function set_upsells_query_args( &$query_args ){
        $prefix = self::QUERY_CONTROL_NAME . '_';
        if ( 'upsells' === $this->settings[ $prefix . 'post_type' ] ) {
            global $product;

            $product = wc_get_product();

            if ( ! $product ) {
                return;
            }

            $upsell_ids = $product->get_upsell_ids();

            $query_args['post__in'] = $upsell_ids;
        }
    }

    protected function set_avoid_duplicates( &$query_args ) {

        $prefix = self::QUERY_CONTROL_NAME . '_';

        if( ! empty( $this->settings[ $prefix . 'avoid_duplicates' ] ) ){
            $product__not_in = isset( $query_args['post__not_in'] ) ? $query_args['post__not_in'] : [];
            $product__not_in = array_merge( $product__not_in, self::get_avoid_list_ids() );
            $query_args['post__not_in'] = $product__not_in;
        }
    }

    public static function add_to_avoid_list( $ids ) {
        self::$displayed_ids = array_merge( self::$displayed_ids, $ids );
    }

    public static function get_avoid_list_ids() {
        return self::$displayed_ids;
    }

    /**
     * Set attributes query args.
     *
     * @since 2.0.0
     * @param array $query_args Query args.
     */
    protected function set_attributes_query_args( &$query_args ) {

        // Layered nav filters on terms.
        foreach ( \WC_Query::get_layered_nav_chosen_attributes() as $taxonomy => $data ) {
            $query_args['tax_query'][] = array(
                'taxonomy'         => $taxonomy,
                'field'            => 'slug',
                'terms'            => $data['terms'],
                'operator'         => 'and' === $data['query_type'] ? 'AND' : 'IN',
                'include_children' => false,
            );
        }
    }
}
