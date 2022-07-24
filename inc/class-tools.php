<?php
/**
 * Cherry addons tools class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kitify_Tools' ) ) {

	/**
	 * Define Kitify_Tools class
	 */
	class Kitify_Tools {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   Kitify_Tools
		 */
		private static $instance = null;

		/**
		 * Returns columns classes string
		 *
		 * @param  array $columns Columns classes array
		 * @return string
		 */
		public function col_classes( $columns = array() ) {

			$columns = wp_parse_args( $columns, array(
				'desk' => 1,
				'tab'  => 1,
				'mob'  => 1,
			) );

			$classes = array();

			foreach ( $columns as $device => $cols ) {
				if ( ! empty( $cols ) ) {
					$classes[] = sprintf( 'col-%1$s-%2$s', $device, $cols );
				}
			}

			return implode( ' ' , $classes );
		}

		/**
		 * Returns disable columns gap nad rows gap classes string
		 *
		 * @param  string $use_cols_gap [description]
		 * @param  string $use_rows_gap [description]
		 * @return string
		 */
		public function gap_classes( $use_cols_gap = 'yes', $use_rows_gap = 'yes' ) {

			$result = array();

			foreach ( array( 'cols' => $use_cols_gap, 'rows' => $use_rows_gap ) as $element => $value ) {
				if ( 'yes' !== $value ) {
					$result[] = sprintf( 'disable-%s-gap', $element );
				}
			}

			return implode( ' ', $result );

		}

		/**
		 * Returns image size array in slug => name format
		 *
		 * @return  array
		 */
		public function get_image_sizes() {

			global $_wp_additional_image_sizes;

			$sizes  = get_intermediate_image_sizes();
			$result = array();

			foreach ( $sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
				} else {
					$result[ $size ] = sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					);
				}
			}

			return array_merge( array( 'full' => esc_html__( 'Full', 'kitify' ), ), $result );
		}

		/**
		 * Get categories list.
		 *
		 * @return array
		 */
		public function get_categories() {

			$categories = get_categories();

			if ( empty( $categories ) || ! is_array( $categories ) ) {
				return array();
			}

			return wp_list_pluck( $categories, 'name', 'term_id' );

		}

		/**
		 * Returns icons data list.
		 *
		 * @return array
		 */
		public function get_theme_icons_data() {

			$default = array(
				'icons'  => false,
				'format' => 'fa %s',
				'file'   => false,
			);

			/**
			 * Filter default icon data before useing
			 *
			 * @var array
			 */
			$icon_data = apply_filters( 'kitify/controls/icon/data', $default );
			$icon_data = array_merge( $default, $icon_data );

			return $icon_data;
		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function orderby_arr() {
			return array(
				'none'          => esc_html__( 'None', 'kitify' ),
				'ID'            => esc_html__( 'ID', 'kitify' ),
				'author'        => esc_html__( 'Author', 'kitify' ),
				'title'         => esc_html__( 'Title', 'kitify' ),
				'name'          => esc_html__( 'Name (slug)', 'kitify' ),
				'date'          => esc_html__( 'Date', 'kitify' ),
				'modified'      => esc_html__( 'Modified', 'kitify' ),
				'rand'          => esc_html__( 'Rand', 'kitify' ),
				'comment_count' => esc_html__( 'Comment Count', 'kitify' ),
				'menu_order'    => esc_html__( 'Menu Order', 'kitify' ),
			);
		}

		/**
		 * Returns allowed order fields for options
		 *
		 * @return array
		 */
		public function order_arr() {

			return array(
				'desc' => esc_html__( 'Descending', 'kitify' ),
				'asc'  => esc_html__( 'Ascending', 'kitify' ),
			);

		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function verrtical_align_attr() {
			return array(
				'baseline'    => esc_html__( 'Baseline', 'kitify' ),
				'top'         => esc_html__( 'Top', 'kitify' ),
				'middle'      => esc_html__( 'Middle', 'kitify' ),
				'bottom'      => esc_html__( 'Bottom', 'kitify' ),
				'sub'         => esc_html__( 'Sub', 'kitify' ),
				'super'       => esc_html__( 'Super', 'kitify' ),
				'text-top'    => esc_html__( 'Text Top', 'kitify' ),
				'text-bottom' => esc_html__( 'Text Bottom', 'kitify' ),
			);
		}

		/**
		 * Returns array with numbers in $index => $name format for numeric selects
		 *
		 * @param  integer $to Max numbers
		 * @return array
		 */
		public function get_select_range( $to = 10 ) {
			$range = range( 1, $to );
			return array_combine( $range, $range );
		}

		/**
		 * Returns badge placeholder URL
		 *
		 * @return void
		 */
		public function get_badge_placeholder() {
			return kitify()->plugin_url( 'assets/images/placeholder-badge.svg' );
		}

		/**
		 * Rturns image tag or raw SVG
		 *
		 * @param  string $url  image URL.
		 * @param  array  $attr [description]
		 * @return string
		 */
		public function get_image_by_url( $url = null, $attr = array() ) {

			$url = esc_url( $url );

			if ( empty( $url ) ) {
				return;
			}

			$ext  = pathinfo( $url, PATHINFO_EXTENSION );
			$attr = array_merge( array( 'alt' => '' ), $attr );

			if ( 'svg' !== $ext ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			$base_url = site_url( '/' );
			$svg_path = str_replace( $base_url, ABSPATH, $url );
			$key      = md5( $svg_path );
			$svg      = get_transient( $key );

			if ( ! $svg ) {
				$svg = file_get_contents( $svg_path );
			}

			if ( ! $svg ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			set_transient( $key, $svg, DAY_IN_SECONDS );

			unset( $attr['alt'] );

			return sprintf( '<div%2$s>%1$s</div>', $svg, $this->get_attr_string( $attr ) );
		}

		/**
		 * Return attributes string from attributes array.
		 *
		 * @param  array  $attr Attributes string.
		 * @return string
		 */
		public function get_attr_string( $attr = array() ) {

			if ( empty( $attr ) || ! is_array( $attr ) ) {
				return;
			}

			$result = '';

			foreach ( $attr as $key => $value ) {
				$result .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}

			return $result;
		}

		/**
		 * Returns carousel arrow
		 *
		 * @param  array $classes Arrow additional classes list.
		 * @return string
		 */
		public function get_carousel_arrow( $classes ) {

			$format = apply_filters( 'kitify/carousel/arrows_format', '<i class="%s kitify-arrow"></i>', $classes );

			return sprintf( $format, implode( ' ', $classes ) );
		}

		/**
		 * Get post types options list
		 *
		 * @return array
		 */
		public function get_post_types() {

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$deprecated = apply_filters(
				'kitify/post-types-list/deprecated',
				array( 'attachment', 'elementor_library' )
			);

			$result = array();

			if ( empty( $post_types ) ) {
				return $result;
			}

			foreach ( $post_types as $slug => $post_type ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $post_type->label;

			}

			return $result;

		}

		/**
		 * Return available arrows list
		 * @return array
		 */
		public function get_available_prev_arrows_list() {

			return apply_filters(
				'kitify/carousel/available_arrows/prev',
				array(
					'fa fa-angle-left'          => __( 'Angle', 'kitify' ),
					'fa fa-chevron-left'        => __( 'Chevron', 'kitify' ),
					'fa fa-angle-double-left'   => __( 'Angle Double', 'kitify' ),
					'fa fa-arrow-left'          => __( 'Arrow', 'kitify' ),
					'fa fa-caret-left'          => __( 'Caret', 'kitify' ),
					'fa fa-long-arrow-alt-left' => __( 'Long Arrow', 'kitify' ),
					'fa fa-arrow-circle-left'   => __( 'Arrow Circle', 'kitify' ),
					'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'kitify' ),
					'fa fa-caret-square-left'   => __( 'Caret Square', 'kitify' ),
				)
			);

		}

		/**
		 * Return available arrows list
		 * @return array
		 */
		public function get_available_next_arrows_list() {

			return apply_filters(
				'kitify/carousel/available_arrows/next',
				array(
					'fa fa-angle-right'          => __( 'Angle', 'kitify' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'kitify' ),
					'fa fa-angle-double-right'   => __( 'Angle Double', 'kitify' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'kitify' ),
					'fa fa-caret-right'          => __( 'Caret', 'kitify' ),
					'fa fa-long-arrow-alt-right'     => __( 'Long Arrow', 'kitify' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'kitify' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'kitify' ),
					'fa fa-caret-square-right'   => __( 'Caret Square', 'kitify' ),
				)
			);

		}

		/**
		 * Return available arrows list
		 * @return array
		 */
		public function get_available_title_html_tags() {

			return array(
				'h1'   => esc_html__( 'H1', 'kitify' ),
				'h2'   => esc_html__( 'H2', 'kitify' ),
				'h3'   => esc_html__( 'H3', 'kitify' ),
				'h4'   => esc_html__( 'H4', 'kitify' ),
				'h5'   => esc_html__( 'H5', 'kitify' ),
				'h6'   => esc_html__( 'H6', 'kitify' ),
				'div'  => esc_html__( 'div', 'kitify' ),
				'span' => esc_html__( 'span', 'kitify' ),
				'p'    => esc_html__( 'p', 'kitify' ),
			);

		}

		/**
		 * Get post taxonomies for options.
		 *
		 * @return array
		 */
		public function get_taxonomies_for_options() {

			$args = array(
				'public' => true,
			);

			$taxonomies = get_taxonomies( $args, 'objects', 'and' );

			return wp_list_pluck( $taxonomies, 'label', 'name' );
		}

		/**
		 * Get elementor templates list for options.
		 *
		 * @return array
		 */
		public function get_elementor_templates_options() {
			$templates = kitify()->elementor()->templates_manager->get_source( 'local' )->get_items();

			$options = array(
				'0' => '— ' . esc_html__( 'Select', 'kitify' ) . ' —',
			);

			foreach ( $templates as $template ) {
				$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}

			return $options;
		}

		/**
		 * Is script debug.
		 *
		 * @return bool
		 */
		public function is_script_debug() {
			return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		}

		/**
		 * Is FA5 migration.
		 *
		 * @return bool
		 */
		public function is_fa5_migration() {

			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) && Elementor\Icons_Manager::is_migration_allowed() ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if is valid timestamp
		 *
		 * @param  int|string $timestamp
		 * @return boolean
		 */
		public function is_valid_timestamp( $timestamp ) {
			return ( ( string ) ( int ) $timestamp === $timestamp ) && ( $timestamp <= PHP_INT_MAX ) && ( $timestamp >= ~PHP_INT_MAX );
		}

		public function validate_html_tag( $tag ) {
			$allowed_tags = array(
				'article',
				'aside',
				'div',
				'footer',
				'h1',
				'h2',
				'h3',
				'h4',
				'h5',
				'h6',
				'header',
				'main',
				'nav',
				'p',
				'section',
				'span',
			);

			return in_array( strtolower( $tag ), $allowed_tags ) ? $tag : 'div';
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return Kitify_Tools
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Kitify_Tools
 *
 * @return Kitify_Tools
 */
function kitify_tools() {
	return Kitify_Tools::get_instance();
}
