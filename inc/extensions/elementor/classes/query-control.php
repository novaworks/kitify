<?php


namespace KitifyExtensions\Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Query_Control {

    public static $displayed_ids = [];

    protected static $_instances = [];

    const QUERY_CONTROL_ID = 'query';
    const AUTOCOMPLETE_ERROR_CODE = 'QueryControlAutocomplete';
    const GET_TITLES_ERROR_CODE = 'QueryControlGetTitles';

    // Supported objects for query:
    const QUERY_OBJECT_POST = 'post';
    const QUERY_OBJECT_TAX = 'tax';
    const QUERY_OBJECT_AUTHOR = 'author';
    const QUERY_OBJECT_USER = 'user';
    const QUERY_OBJECT_LIBRARY_TEMPLATE = 'library_template';
    const QUERY_OBJECT_ATTACHMENT = 'attachment';

    // Objects that are manipulated by js (not sent in AJAX):
    const QUERY_OBJECT_CPT_TAX = 'cpt_tax';
    const QUERY_OBJECT_JS = 'js';

    private static $supported_objects_for_query = [
        self::QUERY_OBJECT_POST,
        self::QUERY_OBJECT_TAX,
        self::QUERY_OBJECT_AUTHOR,
        self::QUERY_OBJECT_USER,
        self::QUERY_OBJECT_LIBRARY_TEMPLATE,
        self::QUERY_OBJECT_ATTACHMENT,
    ];

    /**
     * @return static
     */
    public static function get_instance() {
        if ( empty( self::$_instances ) ) {
            self::$_instances = new self();
        }
        return self::$_instances;
    }

    public static function add_to_avoid_list( $ids ) {
        self::$displayed_ids = array_merge( self::$displayed_ids, $ids );
    }

    public static function get_avoid_list_ids() {
        return self::$displayed_ids;
    }

    public function get_name() {
        return 'kitify-query-control';
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function ajax_posts_filter_autocomplete( array $data ) {

        if(!empty($data['autocomplete'])){
            return $this->ajax_posts_filter_autocomplete_new($data);
        }

        if ( empty( $data['filter_type'] ) || empty( $data['q'] ) ) {
            throw new \Exception( 'Bad Request' );
        }

        $results = [];

        switch ( $data['filter_type'] ) {
            case 'taxonomy':
                $query_params = [
                    'taxonomy' => $this->extract_post_type( $data ),
                    'search' => $data['q'],
                    'hide_empty' => false,
                ];

                $terms = get_terms( $query_params );

                global $wp_taxonomies;

                foreach ( $terms as $term ) {
                    $term_name = $this->get_term_name_with_parents( $term );
                    if ( ! empty( $data['include_type'] ) && isset( $wp_taxonomies[ $term->taxonomy ] ) ) {

                        $text = $wp_taxonomies[ $term->taxonomy ]->labels->name . ': ' . $term_name;
                    }
                    else {
                        $text = $term_name;
                    }

                    $results[] = [
                        'id' => $term->term_taxonomy_id,
                        'text' => $text,
                    ];
                }

                break;

            case 'by_id':
            case 'post':
                $query_params = [
                    'post_type' => $this->extract_post_type( $data ),
                    's' => $data['q'],
                    'posts_per_page' => -1,
                ];

                if ( 'attachment' === $query_params['post_type'] ) {
                    $query_params['post_status'] = 'inherit';
                }

                if(absint($data['q']) > 0){
                    unset($query_params['s']);
                    $query_params['p'] = absint($data['q']);
                }

                $query = new \WP_Query( $query_params );

                foreach ( $query->posts as $post ) {
                    $post_type_obj = get_post_type_object( $post->post_type );
                    if ( ! empty( $data['include_type'] ) ) {
                        $text = $post_type_obj->labels->name . ': ' . $post->post_title;
                    } else {
                        $text = ( $post_type_obj->hierarchical ) ? $this->get_post_name_with_parents( $post ) : $post->post_title;
                    }

                    $results[] = [
                        'id' => $post->ID,
                        'text' => $text,
                    ];
                }
                break;

            case 'author':
                $query_params = [
                    'who' => 'authors',
                    'has_published_posts' => true,
                    'fields' => [
                        'ID',
                        'display_name',
                    ],
                    'search' => '*' . $data['q'] . '*',
                    'search_columns' => [
                        'user_login',
                        'user_nicename',
                    ],
                ];

                $user_query = new \WP_User_Query( $query_params );

                foreach ( $user_query->get_results() as $author ) {
                    $results[] = [
                        'id' => $author->ID,
                        'text' => $author->display_name,
                    ];
                }
                break;
            default:
                $results = apply_filters( 'kitify/query_control/get_autocomplete/' . $data['filter_type'], [], $data );
        }

        return [
            'results' => $results,
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */

    private function ajax_posts_filter_autocomplete_new( $data ){
        $query_data = $this->autocomplete_query_data( $data );
        if ( is_wp_error( $query_data ) ) {
            /** @var \WP_Error $query_data */
            throw new \Exception( $query_data->get_error_code() . ':' . $query_data->get_error_message() );
        }

        $results = [];
        $display = $query_data['display'];
        $query_args = $query_data['query'];

        switch ( $query_data['object'] ) {
            case self::QUERY_OBJECT_TAX:
                $by_field = ! empty( $query_data['by_field'] ) ? $query_data['by_field'] : 'term_taxonomy_id';
                $terms = get_terms( $query_args );
                if ( is_wp_error( $terms ) ) {
                    break;
                }
                foreach ( $terms as $term ) {
                    if ( apply_filters( "elementor/query/get_autocomplete/tax/{$display}", true, $term, $data ) ) {
                        $results[] = [
                            'id' => $term->{$by_field},
                            'text' => $this->get_term_name( $term, $display, $data ),
                        ];
                    }
                }
                break;
            case self::QUERY_OBJECT_ATTACHMENT:
            case self::QUERY_OBJECT_POST:

                if( !empty($query_args['s']) && strcmp(absint($query_args['s']), trim($query_args['s'])) === 0 ){
                    $query_args['p'] = $query_args['s'];
                    unset($query_args['s']);
                }

                if(!empty($query_args['s'])){
                    $query_args['s_title'] = $query_args['s'];
                }

                add_filter( 'posts_where', array( __CLASS__, 'force_search_by_title' ), 10, 2 );
                $query = new \WP_Query( $query_args );
                remove_filter( 'posts_where', array( __CLASS__, 'force_search_by_title' ), 10 );

                foreach ( $query->posts as $post ) {
                    if ( apply_filters( "elementor/query/get_autocomplete/custom/{$display}", true, $post, $data ) ) {
                        $text = $this->format_post_for_display( $post, $display, $data );
                        $results[] = [
                            'id' => $post->ID,
                            'text' => $text,
                        ];
                    }
                }
                break;
            case self::QUERY_OBJECT_LIBRARY_TEMPLATE:
                $query = new \WP_Query( $query_args );

                foreach ( $query->posts as $post ) {
                    $document = kitify()->elementor()->documents->get( $post->ID );
                    if ( $document ) {
                        $text = esc_html( $post->post_title ) . ' (' . $document->get_post_type_title() . ')';
                        $results[] = [
                            'id' => $post->ID,
                            'text' => $text,
                        ];
                    }
                }
                break;
            case self::QUERY_OBJECT_USER:
            case self::QUERY_OBJECT_AUTHOR:
                $user_query = new \WP_User_Query( $query_args );

                foreach ( $user_query->get_results() as $user ) {
                    if ( apply_filters( "elementor/query/get_autocomplete/user/{$display}", true, $user, $data ) ) {
                        $results[] = [
                            'id' => $user->ID,
                            'text' => $this->format_user_for_display( $user, $display, $data ),
                        ];
                    }
                }
                break;
            default:
                $results = apply_filters( 'elementor/query/get_autocomplete/' . $query_data['filter_type'], $results, $data );
        }

        return [
            'results' => $results,
        ];
    }

    public function ajax_posts_control_value_titles( $request ) {

        if(!empty($request['get_titles'])){
            return $this->ajax_posts_control_value_titles_new($request);
        }

        $ids = (array) $request['id'];

        $results = [];

        switch ( $request['filter_type'] ) {
            case 'taxonomy':
                $terms = get_terms(
                    [
                        'term_taxonomy_id' => $ids,
                        'hide_empty' => false,
                    ]
                );

                global $wp_taxonomies;
                foreach ( $terms as $term ) {
                    $term_name = $this->get_term_name_with_parents( $term );
                    if ( ! empty( $request['include_type'] ) ) {
                        $text = $wp_taxonomies[ $term->taxonomy ]->labels->name . ': ' . $term_name;
                    } else {
                        $text = $term_name;
                    }
                    $results[ $term->term_taxonomy_id ] = $text;
                }
                break;

            case 'by_id':
            case 'post':

                $post_type_args = $this->extract_post_type( $request );
                if(empty($post_type_args)){
                    $post_type_args = 'any';
                }

                $query = new \WP_Query(
                    [
                        'post_type' => $post_type_args,
                        'post__in' => $ids,
                        'posts_per_page' => -1,
                    ]
                );

                foreach ( $query->posts as $post ) {
                    $results[ $post->ID ] = $post->post_title;
                }
                break;

            case 'author':
                $query_params = [
                    'who' => 'authors',
                    'has_published_posts' => true,
                    'fields' => [
                        'ID',
                        'display_name',
                    ],
                    'include' => $ids,
                ];

                $user_query = new \WP_User_Query( $query_params );

                foreach ( $user_query->get_results() as $author ) {
                    $results[ $author->ID ] = $author->display_name;
                }
                break;
            default:
                $results = apply_filters( 'kitify/query_control/get_value_titles/' . $request['filter_type'], [], $request );
        }

        return $results;
    }

    private function ajax_posts_control_value_titles_new( $request ){
        $query_data = $this->get_titles_query_data( $request );
        if ( is_wp_error( $query_data ) ) {
            return [];
        }
        $display = $query_data['display'];
        $query_args = $query_data['query'];

        $results = [];
        switch ( $query_data['object'] ) {
            case self::QUERY_OBJECT_TAX:
                $by_field = ! empty( $query_data['by_field'] ) ? $query_data['by_field'] : 'term_taxonomy_id';
                $terms = get_terms( $query_args );

                if ( is_wp_error( $terms ) ) {
                    break;
                }
                foreach ( $terms as $term ) {
                    if ( apply_filters( "elementor/query/get_value_titles/tax/{$display}", true, $term, $request ) ) {
                        $results[ $term->{$by_field} ] = $this->get_term_name( $term, $display, $request, 'get_value_titles' );
                    }
                }
                break;

            case self::QUERY_OBJECT_ATTACHMENT:
            case self::QUERY_OBJECT_POST:
                $query = new \WP_Query( $query_args );

                foreach ( $query->posts as $post ) {
                    if ( apply_filters( "elementor/query/get_value_titles/custom/{$display}", true, $post, $request ) ) {
                        $results[ $post->ID ] = $this->format_post_for_display( $post, $display, $request, 'get_value_titles' );
                    }
                }
                break;
            case self::QUERY_OBJECT_LIBRARY_TEMPLATE:
                $query = new \WP_Query( $query_args );

                foreach ( $query->posts as $post ) {
                    $document = kitify()->elementor()->documents->get( $post->ID );
                    if ( $document ) {
                        $results[ $post->ID ] = esc_html( $post->post_title ) . ' (' . $document->get_post_type_title() . ')';
                    }
                }
                break;
            case self::QUERY_OBJECT_AUTHOR:
            case self::QUERY_OBJECT_USER:
                $user_query = new \WP_User_Query( $query_args );

                foreach ( $user_query->get_results() as $user ) {
                    if ( apply_filters( "elementor/query/get_value_titles/user/{$display}", true, $user, $request ) ) {
                        $results[ $user->ID ] = $this->format_user_for_display( $user, $display, $request, 'get_value_titles' );
                    }
                }
                break;
            default:
                $results = apply_filters( "elementor/query/get_value_titles/{$query_data['filter_type']}", $results, $request );
        }

        return $results;
    }

    private function get_titles_query_data( $data ) {
        if ( empty( $data['get_titles'] ) || empty( $data['id'] ) || empty( $data['get_titles']['object'] ) ) {
            return new \WP_Error( self::GET_TITLES_ERROR_CODE, 'Empty or incomplete data' );
        }

        $get_titles = $data['get_titles'];
        if ( empty( $get_titles['query'] ) ) {
            $get_titles['query'] = [];
        }

        if ( in_array( $get_titles['object'], self::$supported_objects_for_query, true ) ) {
            $method_name = 'get_titles_query_for_' . $get_titles['object'];
            $query = $this->$method_name( $data );
            if ( is_wp_error( $query ) ) {
                return $query;
            }
            $get_titles['query'] = $query;
        }

        if ( empty( $get_titles['display'] ) ) {
            $get_titles['display'] = 'minimal';
        }

        return $get_titles;
    }

    private function get_titles_query_for_post( $data ) {
        $query = isset($data['get_titles']['query']) ? $data['get_titles']['query'] : [];
        if ( empty( $query['post_type'] ) ) {
            $query['post_type'] = 'any';
        }
        $query['posts_per_page'] = -1;
        $query['post__in'] = (array) $data['id'];

        return $query;
    }

    private function get_titles_query_for_attachment( $data ) {
        $query = $this->get_titles_query_for_post( $data );
        $query['post_type'] = 'attachment';
        $query['post_status'] = 'inherit';

        return $query;
    }

    private function get_titles_query_for_tax( $data ) {
        $by_field = empty( $data['get_titles']['by_field'] ) ? 'term_taxonomy_id' : $data['get_titles']['by_field'];
        return [
            $by_field => (array) $data['id'],
            'hide_empty' => false,
        ];
    }


    private function get_titles_query_for_library_template( $data ) {
        $query = isset($data['get_titles']['query']) ? $data['get_titles']['query'] : [];

        $query['post_type'] = \Elementor\TemplateLibrary\Source_Local::CPT;
        $query['orderby'] = 'meta_value';
        $query['order'] = 'ASC';

        if ( empty( $query['posts_per_page'] ) ) {
            $query['posts_per_page'] = -1;
        }

        return $query;
    }

    private function get_titles_query_for_author( $data ) {
        $query = $this->get_titles_query_for_user( $data );
        $query['who'] = 'authors';
        $query['has_published_posts'] = true;
        return $query;
    }

    private function get_titles_query_for_user( $data ) {
        if(!empty($data['get_titles']['query'])){
            return $data['get_titles']['query'];
        }
        $query = [
            'fields' => [
                'ID',
                'display_name',
            ],
            'include' => (array) $data['id'],
        ];
        if ( isset($data['get_titles']['display'] ) && 'detailed' === $data['get_titles']['display'] ) {
            $query['fields'][] = 'user_email';
        }
        return $query;
    }

    private function extract_post_type( $data ) {

        if ( ! empty( $data['query'] ) && ! empty( $data['query']['post_type'] ) ) {
            return $data['query']['post_type'];
        }

        return isset($data['object_type']) ? $data['object_type'] : array();
    }

    /**
     * get_term_name_with_parents
     * @param \WP_Term $term
     * @param int $max
     *
     * @return string
     */
    private function get_term_name_with_parents( \WP_Term $term, $max = 3 ) {
        if ( 0 === $term->parent ) {
            return $term->name;
        }
        $separator = is_rtl() ? ' < ' : ' > ';
        $test_term = $term;
        $names = [];
        while ( $test_term->parent > 0 ) {
            $test_term = get_term_by( 'term_taxonomy_id', $test_term->parent );
            if ( is_wp_error($test_term) || !$test_term ) {
                break;
            }
            $names[] = $test_term->name;
        }

        $names = array_reverse( $names );
        if ( count( $names ) < ( $max ) ) {
            return implode( $separator, $names ) . $separator . $term->name;
        }

        $name_string = '';
        for ( $i = 0; $i < ( $max - 1 ); $i++ ) {
            $name_string .= $names[ $i ] . $separator;
        }
        return $name_string . '...' . $separator . $term->name;
    }

    /**
     * get post name with parents
     * @param \WP_Post $post
     * @param int $max
     *
     * @return string
     */
    private function get_post_name_with_parents( $post, $max = 3 ) {
        if ( 0 === $post->post_parent ) {
            return $post->post_title;
        }
        $separator = is_rtl() ? ' < ' : ' > ';
        $test_post = $post;
        $names = [];
        while ( $test_post->post_parent > 0 ) {
            $test_post = get_post( $test_post->post_parent );
            if ( ! $test_post ) {
                break;
            }
            $names[] = $test_post->post_title;
        }

        $names = array_reverse( $names );
        if ( count( $names ) < ( $max ) ) {
            return implode( $separator, $names ) . $separator . $post->post_title;
        }

        $name_string = '';
        for ( $i = 0; $i < ( $max - 1 ); $i++ ) {
            $name_string .= $names[ $i ] . $separator;
        }
        return $name_string . '...' . $separator . $post->post_title;
    }

    /**
     * @param string $name
     * @param array $query_args
     * @param array $fallback_args
     *
     * @return \WP_Query
     */
    public function get_query( $widget, $name, $query_args = [], $fallback_args = [] ) {
        $prefix = $name . '_';
        $post_type = $widget->get_settings( $prefix . 'post_type' );
        if ( 'related' === $post_type ) {
            $the_query = new Related_Query( $widget, $name, $query_args, $fallback_args );
        }
        else {
            $the_query = new Post_Query( $widget, $name, $query_args );
        }
        return $the_query->get_query();
    }

    private function autocomplete_query_data( $data ) {
        if ( empty( $data['autocomplete'] ) || empty( $data['q'] ) || empty( $data['autocomplete']['object'] ) ) {
            return new \WP_Error( self::AUTOCOMPLETE_ERROR_CODE, 'Empty or incomplete data' );
        }

        $autocomplete = $data['autocomplete'];

        if ( in_array( $autocomplete['object'], self::$supported_objects_for_query, true ) ) {
            $method_name = 'autocomplete_query_for_' . $autocomplete['object'];
            if ( empty( $autocomplete['display'] ) ) {
                $autocomplete['display'] = 'minimal';
                $data['autocomplete'] = $autocomplete;
            }

            if(!method_exists($this, $method_name)){
                return new \WP_Error( self::AUTOCOMPLETE_ERROR_CODE, 'Empty or incomplete data' );
            }
            $query = $this->$method_name( $data );
            if ( is_wp_error( $query ) ) {
                return $query;
            }
            $autocomplete['query'] = $query;
        }

        return $autocomplete;
    }

    private function autocomplete_query_for_post( $data ) {
        if ( ! isset( $data['autocomplete']['query'] ) ) {
            return new \WP_Error( self::AUTOCOMPLETE_ERROR_CODE, 'Missing autocomplete[`query`] data' );
        }
        $query = $data['autocomplete']['query'];
        if ( empty( $query['post_type'] ) ) {
            $query['post_type'] = 'any';
        }
        $query['posts_per_page'] = -1;
        $query['s'] = $data['q'];

        return $query;
    }

    private function autocomplete_query_for_library_template( $data ) {
        $query = $data['autocomplete']['query'];

        $query['post_type'] = \Elementor\TemplateLibrary\Source_Local::CPT;
        $query['orderby'] = 'meta_value';
        $query['order'] = 'ASC';

        if ( empty( $query['posts_per_page'] ) ) {
            $query['posts_per_page'] = -1;
        }
        $query['s'] = $data['q'];

        return $query;
    }

    private function autocomplete_query_for_attachment( $data ) {
        $query = $this->autocomplete_query_for_post( $data );
        if ( is_wp_error( $query ) ) {
            return $query;
        }
        $query['post_type'] = 'attachment';
        $query['post_status'] = 'inherit';

        return $query;
    }

    private function autocomplete_query_for_tax( $data ) {
        $query = $data['autocomplete']['query'];

        if ( empty( $query['taxonomy'] ) && ! empty( $query['post_type'] ) ) {
            $query['taxonomy'] = get_object_taxonomies( $query['post_type'] );
        }
        $query['search'] = $data['q'];
        $query['hide_empty'] = false;
        return $query;
    }

    private function autocomplete_query_for_author( $data ) {
        $query = $this->autocomplete_query_for_user( $data );
        if ( is_wp_error( $query ) ) {
            return $query;
        }
        $query['who'] = 'authors';
        return $query;
    }

    private function autocomplete_query_for_user( $data ) {

        if(!empty($data['autocomplete']['query'])){
            return $data['autocomplete']['query'];
        }

        $query = [
            'fields' => [
                'ID',
                'display_name',
            ],
            'search' => '*' . $data['q'] . '*',
            'search_columns' => [
                'user_login',
                'user_nicename',
            ],
        ];
        if ( isset($data['autocomplete']['display'] ) && 'detailed' === $data['autocomplete']['display'] ) {
            $query['fields'][] = 'user_email';
        }
        return $query;
    }

    /**
     * @param \WP_Post $post
     * @param string $display
     * @param array $data
     * @param string $filter_name
     *
     * @return mixed|string|void
     */
    private function format_post_for_display( $post, $display, $data, $filter_name = 'get_autocomplete' ) {
        $post_type_obj = get_post_type_object( $post->post_type );
        switch ( $display ) {
            case 'minimal':
                $text = ( $post_type_obj->hierarchical ) ? $this->get_post_name_with_parents( $post ) : $post->post_title;
                break;
            case 'detailed':
                $text = $post_type_obj->labels->name . ': ' . ( $post_type_obj->hierarchical ) ? $this->get_post_name_with_parents( $post ) : $post->post_title;
                break;
            default:
                $text = apply_filters( "elementor/query/{$filter_name}/display/{$display}", $post->post_title, $post->ID, $data );
                break;
        }

        return esc_html( $text );
    }

    /**
     * @param \WP_User $user
     * @param string $display
     * @param array $data
     * @param string $filter_name
     *
     * @return string
     */
    private function format_user_for_display( $user, $display, $data, $filter_name = 'get_autocomplete' ) {
        switch ( $display ) {
            case 'minimal':
                $text = $user->display_name;
                break;
            case 'detailed':
                $text = sprintf( '%s (%s)', $user->display_name, $user->user_email );
                break;
            default:
                $text = apply_filters( "elementor/query/{$filter_name}/display/{$display}", $user, $data );
                break;
        }

        return $text;
    }

    private function get_term_name( $term, $display, $request, $filter_name = 'get_autocomplete' ) {
        global $wp_taxonomies;
        $term_name = $this->get_term_name_with_parents( $term );
        switch ( $display ) {
            case 'detailed':
                $text = $wp_taxonomies[ $term->taxonomy ]->labels->name . ': ' . $term_name;
                break;
            case 'minimal':
                $text = $term_name;
                break;
            default:
                $text = apply_filters( "elementor/query/{$filter_name}/display/{$display}", $term_name, $request );
                break;
        }
        return $text;
    }

    public static function force_search_by_title( $where, $query ) {

        $args = $query->query;

        if ( ! isset( $args['s_title'] ) ) {
            return $where;
        } else {
            global $wpdb;

            $searh = esc_sql( $wpdb->esc_like( $args['s_title'] ) );
            $where .= " AND {$wpdb->posts}.post_title LIKE '%$searh%'";

        }

        return $where;
    }
}
