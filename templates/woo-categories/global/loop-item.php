<?php
/**
 * Product categories template
 */

$item_image = $this->_loop_item( array( 'item_image', 'url' ), '%s' );
$item_image = apply_filters('kitify_wp_get_attachment_image_url', $item_image);

$post_classes = ['kitify-product-categories__item'];
$el_class = $this->_loop_item( array( 'el_class' ), '%s' );
$el_class = $this->_loop_item( array( '_id' ), 'elementor-repeater-item-%s' );
if(!empty($el_class)){
    $post_classes[] = $el_class;
}
if(filter_var( $this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN )){
    $post_classes[] = 'swiper-slide';
}
else{
    $post_classes[] = kitify_helper()->col_new_classes('columns', $this->get_settings_for_display());
}

?>
<div class="<?php echo esc_attr(join(' ', $post_classes)); ?>">

</div>
