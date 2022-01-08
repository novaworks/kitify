<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Novaworks Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kitify_SVG_Manager' ) ) {

	/**
	 * Define Kitify_SVG_Manager class
	 */
	class Kitify_SVG_Manager {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			$svg_enabled = kitify_settings()->get_option( 'svg-uploads', 'enabled' );

			if( kitify()->get_theme_support('elementor::custom-fonts') ){
                add_filter( 'wp_check_filetype_and_ext', [ $this , 'update_font_mime_types' ], 10, 4 );
                add_filter( 'upload_mimes', [ $this , 'allowed_mime_fonts' ] );
                add_filter( 'elementor/fonts/additional_fonts', [ $this, 'add_custom_fonts' ] );
                add_filter( 'elementor/fonts/groups', [ $this, 'add_custom_fonts_group' ] );
                add_filter( 'kitify/theme/customizer/fonts_list', [ $this, 'add_custom_fonts_to_customizer' ] );
                add_action('wp_enqueue_scripts', [ $this, 'render_custom_fonts' ]);
            }

			if ( 'enabled' !== $svg_enabled ) {
				return;
			}

			add_filter( 'upload_mimes', array( $this, 'allow_svg' ) );
			add_action( 'admin_head', array( $this, 'fix_svg_thumb_display' ) );
			add_filter( 'wp_generate_attachment_metadata', array( $this, 'generate_svg_media_files_metadata' ), 10, 2 );
			add_filter( 'wp_prepare_attachment_for_js', array( $this, 'wp_prepare_attachment_for_js' ), 10, 3 );
		}

		/**
		 * Allow SVG images uploading
		 *
		 * @return array
		 */
		public function allow_svg( $mimes ) {
			$mimes['svg'] = 'image/svg+xml';
			return $mimes;
		}

		/**
		 * Fix thumbnails display
		 *
		 * @return void
		 */
		public function fix_svg_thumb_display() {
			?>
			<style type="text/css">
				td.media-icon img[src$=".svg"],
				img[src$=".svg"].attachment-post-thumbnail,
				td .media-icon img[src*='.svg'] {
					width: 100% !important;
					height: auto !important;
				}
			</style>
			<?php
		}

		/**
		 * Generate SVG metadata
		 *
		 * @return string
		 */
		function generate_svg_media_files_metadata( $metadata, $attachment_id ){
			if( get_post_mime_type( $attachment_id ) == 'image/svg+xml' ){
				$svg_path = get_attached_file( $attachment_id );
				$dimensions = $this->svg_dimensions( $svg_path );
				$metadata['width'] = $dimensions->width;
				$metadata['height'] = $dimensions->height;
			}
			return $metadata;
		}

		/**
		 * Prepares an attachment post object for JS
		 *
		 * @return array
		 */
		public function wp_prepare_attachment_for_js( $response, $attachment, $meta ){
			if( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ){
				$svg_path = get_attached_file( $attachment->ID );
				if( ! file_exists( $svg_path ) ){
					$svg_path = $response['url'];
				}
				$dimensions = $this->svg_dimensions( $svg_path );
				$response['sizes'] = array(
					'full' => array(
						'url' => $response['url'],
						'width' => $dimensions->width,
						'height' => $dimensions->height,
						'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait'
					)
				);
			}
			return $response;
		}

		/**
		 * Get the width and height of the SVG
		 *
		 * @return object
		 */
		public function svg_dimensions( $svg ){
			$svg = function_exists( 'simplexml_load_file' ) ? simplexml_load_file( $svg ) : null;
			$width = 0;
			$height = 0;
			if( $svg ){
				$attributes = $svg->attributes();
				if( isset( $attributes->width, $attributes->height ) ){
					$width = floatval( $attributes->width );
					$height = floatval( $attributes->height );
				}elseif( isset( $attributes->viewBox ) ){
					$sizes = explode( " ", $attributes->viewBox );
					if( isset( $sizes[2], $sizes[3] ) ){
						$width = floatval( $sizes[2] );
						$height = floatval( $sizes[3] );
					}
				}
			}
			return (object)array( 'width' => $width, 'height' => $height );
		}

		public function get_fonts_ext(){
            return [
                'woff' => 'font/woff|application/font-woff|application/x-font-woff|application/octet-stream',
                'woff2' => 'font/woff2|application/octet-stream|font/x-woff2',
                'ttf' => 'application/x-font-ttf|application/octet-stream|font/ttf',
                'svg' => 'image/svg+xml|application/octet-stream|image/x-svg+xml',
                'eot' => 'application/vnd.ms-fontobject|application/octet-stream|application/x-vnd.ms-fontobject',
            ];
        }

        public function allowed_mime_fonts( $mine_types ){
            if ( current_user_can( 'manage_options' )  ) {
                foreach ( $this->get_fonts_ext() as $type => $mine ) {
                    if ( ! isset( $mine_types[ $type ] ) ) {
                        $mine_types[ $type ] = $mine;
                    }
                }
            }
            return $mine_types;
        }

        public function update_font_mime_types( $data, $file, $filename, $mimes ){
            if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
                return $data;
            }

            $registered_file_types = $this->get_fonts_ext();
            $filetype = wp_check_filetype( $filename, $mimes );

            if ( ! isset( $registered_file_types[ $filetype['ext'] ] ) ) {
                return $data;
            }
            // Fix incorrect file mime type
            $filetype['type'] = explode( '|', $filetype['type'] )[0];

            return [
                'ext' => $filetype['ext'],
                'type' => $filetype['type'],
                'proper_filename' => $data['proper_filename'],
            ];
        }

        public function add_custom_fonts( $fonts ){
            $custom_fonts = kitify_settings()->get('custom_fonts', []);
            if(!empty($custom_fonts)){
                foreach ($custom_fonts as $custom_font){
                    if(!empty($custom_font['name']) && !empty($custom_font['title']) && !isset($fonts[$custom_font['title']])){
                        $fonts[$custom_font['title']] = 'custom';
                    }
                }
            }
            return $fonts;
        }

        public function add_custom_fonts_group( $groups ){
            if(!isset($groups['custom'])){
                $groups = array_merge(['custom' => __( 'Custom Fonts', 'kitify' )], $groups);
            }
            return $groups;
        }

        public function add_custom_fonts_to_customizer( $fonts ){
            $custom_fonts = kitify_settings()->get('custom_fonts', []);
            if(!empty($custom_fonts)){
                foreach ($custom_fonts as $custom_font){
                    if(!empty($custom_font['name']) && !empty($custom_font['title']) && !isset($fonts[$custom_font['name']])){
                        $fonts[$custom_font['name']] = $custom_font['title'];
                    }
                }
            }
		    return $fonts;
        }

        public function render_custom_fonts(){
            $need_enqueues = [];
            $raw_css = [];
            $custom_fonts = kitify_settings()->get('custom_fonts', []);
            if(!empty($custom_fonts)){
                foreach ($custom_fonts as $custom_font){
                    if(!empty($custom_font['name'])){
                        $font_family = $custom_font['name'];
                        $font_type = isset($custom_font['type']) ? $custom_font['type'] : 'upload';
                        if($font_type == 'upload'){
                            $font_variations = !empty($custom_font['variations']) ? $custom_font['variations'] : [];
                            foreach ($font_variations as $variation){
                                $src = [];
                                foreach ( [ 'woff2', 'woff', 'ttf', 'svg' ] as $type ) {
                                    if ( !empty( $variation[ $type ] ) ) {
                                        $tmp_url = $variation[ $type ];
                                        if ( 'svg' === $type ) {
                                            $tmp_url .= '#' . str_replace( ' ', '', $font_family );
                                        }
                                        $tmp_src = 'url(\'' . esc_attr( $tmp_url  ) . '\') ';
                                        switch ( $type ) {
                                            case 'woff':
                                            case 'woff2':
                                            case 'svg':
                                                $tmp_src .= 'format(\'' . $type . '\')';
                                                break;
                                            case 'ttf':
                                                $tmp_src .= 'format(\'truetype\')';
                                                break;
                                        }
                                        $src[] = $tmp_src;
                                    }
                                }
                                if(!empty($src)){
                                    $font_face = '@font-face {' . PHP_EOL;
                                    $font_face .= "\tfont-family: " . $font_family . ";" . PHP_EOL;
                                    if(!empty($variation['style'] )){
                                        $font_face .= "\tfont-style: " . $variation['style'] . ';' . PHP_EOL;
                                    }
                                    if(!empty($variation['weight'] )){
                                        $font_face .= "\tfont-weight: " . $variation['weight'] . ';' . PHP_EOL;
                                    }
                                    $font_face .= "\tfont-display: " . apply_filters( 'kitify/theme/custom_fonts/font_display', 'auto', $font_family, $variation ) . ';' . PHP_EOL;
                                    $font_face .= "\tsrc: " . implode( ',' . PHP_EOL . "\t\t", $src ) . ';' . PHP_EOL . '}';
                                    $raw_css[] = $font_face;
                                }
                            }
                        }
                        else{
                            $need_enqueues[$custom_font['title']] = isset($custom_font['url']) ? $custom_font['url'] : '';
                        }
                    }
                }
            }
            if(!empty($raw_css)){
                $raw_css_output = implode('' . PHP_EOL , $raw_css);
                wp_add_inline_style('elementor-frontend', $raw_css_output);
            }
            if(!empty($need_enqueues)){
                foreach ($need_enqueues as $font_family => $font_url){
                    if(!empty($font_url)){
                        wp_enqueue_style( 'kitify-custom-font-' . sanitize_key($font_family), esc_url_raw($font_url) , array(), null );
                    }
                }
            }
        }

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Kitify_SVG_Manager
 *
 * @return object
 */
function kitify_svg_manager() {
	return Kitify_SVG_Manager::get_instance();
}
