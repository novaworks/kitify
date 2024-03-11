<?php
/**
 * Class: Kitify_Search_Box
 * Name: Search Box
 * Slug: kitify-search-box
 */
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Search_Box extends Kitify_Base {
    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/search-box.css'), ['kitify-base'], kitify()->get_version());
        wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/search-box.js'), ['elementor-frontend'], kitify()->get_version(), true);
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

    public function get_name() {
		return 'kitify-search-box';
	}
    public function get_widget_title() {
		return esc_html__( 'Search Box', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-search';
	}
    protected function register_controls() {
        $search_for = apply_filters(
            'kitify/search-box/style/search_for',
            array(
                'all' => esc_html__( 'Search for everything', 'kitify' ),
                'product' => esc_html__( 'Search for products', 'kitify' ),
                'post' => esc_html__( 'Search for posts', 'kitify' ),
                'adaptive' => esc_html__( 'Search for adaptive', 'kitify' )
            )
        );
        $this->_start_controls_section(
            'section_search_general_settings',
            array(
                'label' => esc_html__( 'General Settings', 'kitify' ),
            )
        );
        $this->_add_advanced_icon_control(
            'search_submit_icon',
            array(
                'label'     => esc_html__( 'Submit Button Icon', 'kitify' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => false,
                'file'        => '',
                'skin'        => 'inline',
                'default'     => 'novaicon-zoom-1',
                'fa5_default' => array(
                    'value'   => 'novaicon-zoom-1',
                    'library' => 'novaicon',
                ),
            )
        );
        $this->_add_control(
            'search_placeholder',
            array(
                'label'   => esc_html__( 'Search Placeholder', 'kitify' ),
                'default' => esc_html__( 'What’s are you looking for...', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
            )
        );
        $this->add_control(
            'search_for',
            array(
                'label'     => esc_html__( 'Search For', 'kitify' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'product',
                'options'   => $search_for
            )
        );
        $this->_add_control(
            'show_category',
            array(
                'label' => esc_html__('Seach by Category', 'kitify'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'kitify'),
                'label_off' => esc_html__('No', 'kitify'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );
        $this->_add_control(
            'all_cat_text',
            array(
                'label'   => esc_html__( 'Default Category Text', 'kitify' ),
                'default' => esc_html__( 'All Categories', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'condition' => array(
                    'show_category' => 'true',
                ),
            )
        );
        $this->_add_control(
            'product_cat',
            array(
                'label'   => esc_html__( 'Product Categories', 'kitify' ),
                'type'    => Controls_Manager::TEXTAREA,
                'description' => esc_html__('Enter category names, separate by commas. Leave empty to get all categories.', 'kitify'),
                'condition' => array(
                    'show_category' => 'true',
                ),
            )
        );
        $this->_add_control(
            'show_top_cat',
            array(
                'label' => esc_html__('Show Top Categories', 'kitify'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'kitify'),
                'label_off' => esc_html__('No', 'kitify'),
                'description' => esc_html__('Display first level categories only. This option does not work if you enter category names above.', 'kitify'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => array(
                    'show_category' => 'true',
                ),
            )
        );
        $this->_add_control(
            'show_empty_cat',
            array(
                'label' => esc_html__('Show empty categories', 'kitify'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'kitify'),
                'label_off' => esc_html__('No', 'kitify'),
                'description' => esc_html__('Show all categories including empty categories.', 'kitify'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => array(
                    'show_category' => 'true',
                ),
            )
        );
        $this->_add_control(
            'ajax_search',
            array(
                'label' => esc_html__('AJAX Search', 'kitify'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'kitify'),
                'label_off' => esc_html__('No', 'kitify'),
                'description' => esc_html__('Check this option to enable AJAX search in the header', 'kitify'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => array(
                    'search_for' => 'product',
                ),
            )
        );
        $this->_add_control(
            'ajax_search_count',
            array(
                'label' => __( 'AJAX Product Numbers', 'kitify' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'condition' => [
                    'ajax_search' => 'true',
                ],
            )
        );
        $this->_add_control(
            'trending_search',
            array(
                'label' => esc_html__('Trending Searches', 'kitify'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'kitify'),
                'label_off' => esc_html__('No', 'kitify'),
                'description' => esc_html__('Display a list of links bellow the search field', 'kitify'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );
        $repeater = new Repeater();
        $repeater->add_control(
            'item_text',
            [
                    'label' => __( 'Text', 'kitify' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Category #1', 'kitify' ),
                    'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'item_url',
            [
                    'label' => __( 'Url', 'kitify' ),
                    'type' => Controls_Manager::URL,
                    'dynamic' => array( 'active' => true ),
            ]
        );
        $this->add_control(
            'item_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_text'  => esc_html__( 'T-shirt', 'kitify' ),
                        'item_url'     => '#',
                    ),
                    array(
                        'item_text'  => esc_html__( 'Jacket', 'kitify' ),
                        'item_url'     => '#',
                    )
                ),
                'title_field' => '{{{ item_text }}}',
                'condition' => [
                    'trending_search' => 'true',
                ],
            )
        );
        $this->end_controls_section();
    }
	/**
	 * Display trending searches links.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @return void
	 */
	public function trendings() {
        $settings = $this->get_settings();
		if( ! isset( $settings['trending_search'] ) ) {
			return;
		}

		if ( ! $settings['trending_search'] ) {
			return;
		}

		$items = $this->get_settings_for_display('item_list');

		if ( empty( $items ) ) {
			return;
		}

		$post_type 		   = $settings['search_for'];

		?>
		<div class="header-search__trending header-search__trending--outside">
			<div class="header-search__trending-label"><?php esc_html_e( 'Trending Searches', 'kitify' ); ?></div>

			<ul class="header-search__trending-links">
				<?php
				foreach ( $items as $trending_search ) {
					$url = $trending_search['item_url']['url'];

					if ( ! $url ) {
						$query = array( 's' => $trending_search['item_text'] );

						if ( $post_type ) {
							$query['post_type'] = $post_type;
						}

						$url = add_query_arg( $query, home_url( '/' ) );
					}
					printf(
						'<li><a href="%s">%s</a></li>',
						esc_url( $url ),
						esc_html( $trending_search['item_text'] )
					);
				}
				?>
			</ul>
		</div>
		<?php
	}
    	/**
	 * Get category items.
	 *
	 * @since 1.0.0
	 *
	 * @param string $label
	 * @return void
	 */
	public function categories_items( $label ) {
        $settings = $this->get_settings();
        
		if( ! $settings['search_for'] ) {
			return;
		}

		if ( $settings['search_for'] == 'adaptive' ) {
			$type = 'product';
			$taxonomy = 'product_cat';
		}else {
			$type = $settings['search_for'];
			$taxonomy = ( $settings['search_for'] === 'product' ) ? 'product_cat' : 'category';
		}

		$cats = $settings['product_cat'];
		$hide_empty = $settings['show_empty_cat'] ? false : true;

		$args = array(
			'taxonomy' => $taxonomy,
			'hide_empty' => $hide_empty,
		);

		if ( $settings['show_top_cat'] ) {
			$args['parent'] = 0;
		}

		if ( is_numeric( $cats ) ) {
			$args['number'] = $cats;
		} elseif ( ! empty( $cats ) ) {
			$args['name'] = explode( ',', $cats );
			$args['orderby'] = 'include';
			unset( $args['parent'] );
		}

		$terms = get_terms( $args );
		if( is_wp_error( $terms ) || empty( $terms ) ) {
			return;
		}
		$terms[]['all_categories'] = array (
			'slug' => '0',
			'name' => $label
		);
		$rows = ceil((count($terms))/3);
		if ( count($terms) % 3 == 0 ) {
			$rows = $rows+1;
		}
		$term_html = [];

		if ( $terms && ! is_wp_error( $terms ) ) :
		?>
			<div class="header-search__categories">
				<div class="header-search__categories-title"><span><?php echo esc_html__( 'Select Categories', 'kitify' ); ?></span>icon_close</div>
				<ul class="header-search__categories-container" <?php echo sprintf('style="--mt-header-search-cats-rows: %s"', esc_attr( $rows ) ); ?>>
					<?php
						foreach ( $terms as $term ) :
							if ( !empty($term->slug) ) {
								$term_html[] = '<li><a href="' . get_term_link( $term->slug, $taxonomy ) . '" data-slug="'.esc_attr( $term->slug ).'">'.esc_html( $term->name ).'</a></li>';
							} else {
								$term_html[] = '<li><a href="#" class="active" data-slug="0">' . $label . '</a></li>';
							}
						endforeach;

						echo implode( '', $term_html );
					?>
				</ul>
			</div>
		<?php
		endif;
	}
    /**
	 * Get search type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type
	 * @return string
	 */
	public function type ( $type = 'post' ) {
        $settings = $this->get_settings();
		if ( $settings['search_for'] !== 'adaptive' ) {
			$type = $settings['search_for'];
		} else {
			if( Helper::is_blog() || is_singular('post') ) {
				$type = 'post';
			} else {
				$type = 'product';
			}
		}

		if( kitify_helper()->is_blog() || is_singular('post') ) {
			$type = 'post';
		}

		return $type;
	}
	public function add_js () {
        $settings = $this->get_settings();
        $search_js_data = array(
            'ajax_url'             				=> class_exists( 'WC_AJAX' ) ? \WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
            'header_search_type' 				=> $settings['search_for'],
            'header_ajax_search'   				=> intval( $settings['ajax_search'] ),
            'header_search_number' 				=> $settings['ajax_search_count'],
            'post_type' 	     				=> $this->type(),
        );
        wp_localize_script(
            'kitify-search-box', 'SearchBoxData', $search_js_data
        );
	}
    protected function render() {
        add_action( 'wp_enqueue_scripts', array( $this, 'add_js' ) );
		$this->_context = 'render';
		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

}