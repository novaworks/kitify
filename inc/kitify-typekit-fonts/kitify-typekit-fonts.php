<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Set constants.
 */
define( 'KITIFY_TYPEKIT_FONTS', true );
define( 'KITIFY_TYPEKIT_FONTS_FILE', __FILE__ );
define( 'KITIFY_TYPEKIT_FONTS_BASE', plugin_basename( KITIFY_TYPEKIT_FONTS_FILE ) );
define( 'KITIFY_TYPEKIT_FONTS_DIR', plugin_dir_path( KITIFY_TYPEKIT_FONTS_FILE ) );
define( 'KITIFY_TYPEKIT_FONTS_URI', plugins_url( '/', KITIFY_TYPEKIT_FONTS_FILE ) );
/**
 * Custom Fonts
 */
require_once KITIFY_TYPEKIT_FONTS_DIR . 'classes/class-kitify-typekit-fonts.php';
