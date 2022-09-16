<?php
/**
 * Loop start template
 */
$options   = $this->get_advanced_carousel_options();
$title_tag = $this->get_settings_for_display( 'title_html_tag' );
$title_tag = kitify_helper()->validate_html_tag( $title_tag );
$dir       = is_rtl() ? 'rtl' : 'ltr';
$equal_cols = $this->get_settings_for_display( 'equal_height_cols' );
$cols_class = ( 'true' === $equal_cols ) ? ' kitify-equal-cols' : '';

$carousel_id = $this->get_settings_for_display('carousel_id');
if(empty($carousel_id)){
    $carousel_id = 'kitify_carousel_' . $this->get_id();
}

?><div class="kitify-carousel<?php echo esc_attr($cols_class); ?>" data-slider_options="<?php echo htmlspecialchars( json_encode( $options ) ); ?>" dir="<?php echo $dir; ?>">
    <div class="kitify-carousel-inner"><div class="swiper-container" id="<?php echo esc_attr($carousel_id); ?>"><div class="swiper-wrapper">
