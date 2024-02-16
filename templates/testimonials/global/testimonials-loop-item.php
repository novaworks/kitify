<?php
/**
 * Testimonials item template
 */


$preset = $this->get_settings( 'preset' );
$full_width = $this->get_settings( 'enable_image_full_width' );
$product_id = $this->_loop_item( array( 'product_id' ), '%s' );
$item_image = $this->_loop_item( array( 'item_image', 'url' ), '%s' );
$item_image = apply_filters('kitify_wp_get_attachment_image_url', $item_image);

$item_sign = $this->_loop_item( array( 'item_sign', 'url' ), '%s' );
$item_sign = apply_filters('kitify_wp_get_attachment_image_url', $item_sign);

$post_classes = ['kitify-testimonials__item'];
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
	<div class="kitify-testimonials__item-inner">
		<div class="kitify-testimonials__content"><?php
            if(!empty($item_image)){
                echo '<div class="kitify-testimonials__figure">';
                do_action('kitify/testimonials/output/before_image', $preset);
                if($full_width) {
                  echo sprintf('<span class="kitify-testimonials__tag-img"><img alt="Kitify Testimonials" src="%1$s" /></span>', $item_image );
                }else {
                  echo sprintf('<span class="kitify-testimonials__tag-img"><span style="background-image: url(\'%1$s\')"></span></span>', $item_image );
                }
                if(!empty($item_sign)){
                    echo sprintf('<span class="kitify-testimonials__tag-sign"><img alt="Kitify Testimonials Signature" src="%1$s" /></span>', $item_sign );
                }
                do_action('kitify/testimonials/output/after_image', $preset);
                echo '</div>';
            }
            echo $this->_loop_item( array( 'item_title' ), '<div class="kitify-testimonials__title"><div>%s</div></div>' );
            echo $this->_loop_item( array( 'item_comment' ), '<div class="kitify-testimonials__comment"><div>%s</div></div>' );
            echo '<div class="kitify-testimonials__infomation">';
            echo $this->_loop_item( array( 'item_name' ), '<div class="kitify-testimonials__name"><span>%s</span></div>' );
            echo $this->_loop_item( array( 'item_position' ), '<div class="kitify-testimonials__position"><span>%s</span></div>' );
            echo '</div>';

            if($this->get_settings('replace_star')){
                ?>
                <div class="kitify-testimonials__rating has-replace"><span class="star-rating"><?php
                if(has_action('kitify/testimonials/output/star_rating')){
                    do_action('kitify/testimonials/output/star_rating', $preset);
                }else{
                    echo '<svg width="84" height="84" viewBox="0 0 84 84" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.0625 72.1875C16.9382 72.1862 16.8147 72.1685 16.695 72.135C16.4224 72.0555 16.1829 71.8897 16.0125 71.6625C15.8421 71.4353 15.75 71.159 15.75 70.875V45.3666H1.3125C0.964403 45.3666 0.630564 45.2283 0.384422 44.9821C0.138281 44.736 0 44.4022 0 44.0541V13.125C0 12.7769 0.138281 12.4431 0.384422 12.1969C0.630564 11.9508 0.964403 11.8125 1.3125 11.8125H34.125C34.4731 11.8125 34.8069 11.9508 35.0531 12.1969C35.2992 12.4431 35.4375 12.7769 35.4375 13.125V44.0278C35.4376 44.2764 35.367 44.5199 35.2341 44.73L18.1716 71.5772C18.0532 71.7642 17.8894 71.9182 17.6955 72.0249C17.5016 72.1316 17.2838 72.1875 17.0625 72.1875ZM2.625 42.7153H17.0625C17.4106 42.7153 17.7444 42.8536 17.9906 43.0997C18.2367 43.3459 18.375 43.6797 18.375 44.0278V66.3403L32.8125 43.6406V14.4375H2.625V42.7153Z" fill="currentColor"/>
                    <path d="M65.625 72.1875C65.5007 72.1862 65.3772 72.1685 65.2575 72.135C64.9849 72.0555 64.7454 71.8897 64.575 71.6625C64.4046 71.4353 64.3125 71.159 64.3125 70.875V45.3666H49.875C49.5269 45.3666 49.1931 45.2283 48.9469 44.9821C48.7008 44.736 48.5625 44.4022 48.5625 44.0541V13.125C48.5625 12.7769 48.7008 12.4431 48.9469 12.1969C49.1931 11.9508 49.5269 11.8125 49.875 11.8125H82.6875C83.0356 11.8125 83.3694 11.9508 83.6156 12.1969C83.8617 12.4431 84 12.7769 84 13.125V44.0278C84.0001 44.2764 83.9295 44.5199 83.7966 44.73L66.7341 71.5772C66.6157 71.7642 66.4519 71.9182 66.258 72.0249C66.0641 72.1316 65.8463 72.1875 65.625 72.1875ZM51.1875 42.7153H65.625C65.9731 42.7153 66.3069 42.8536 66.5531 43.0997C66.7992 43.3459 66.9375 43.6797 66.9375 44.0278V66.3403L81.375 43.6406V14.4375H51.1875V42.7153Z" fill="currentColor"/>
                    </svg>';
                }
                ?></span></div>
                <?php
            }
            else{
                $item_rating = $this->_loop_item( array( 'item_rating' ), '%d' );
                if(absint($item_rating)> 0){
                    $percentage =  (absint($item_rating) * 10) . '%';
                    echo '<div class="kitify-testimonials__rating"><span class="star-rating"><span style="width: '.$percentage.'"></span></span></div>';
                }
            }
		?></div>
        <?php
        if( kitify()->get_theme_support('kitify-woo::product-testimonials') ){
            if( function_exists('wc_get_product') && $product_id && $preset == 'product'):
            $product_obj = wc_get_product($product_id);
            if($product_obj){
                $tpl = '<div class="kitify-testimonials__product">%1$s<div class="kitify-testimonials__product_content">%2$s%3$s </div></div>';
                $product_image = $product_obj->get_image();
                $product_title = sprintf('<a class="product_item--title" href="%1$s">%2$s</a>', esc_url($product_obj->get_permalink()), $product_obj->get_title());
                $product_action = sprintf('<a class="product_item--action elementor-button" href="%1$s">%2$s</a>', esc_url($product_obj->get_permalink()), '<svg class="lumilux-addtocart"><use xlink:href="#lumilux-addtocart" xmlns:xlink="http://www.w3.org/1999/xlink"></use></svg>');
                $product_content = sprintf( $tpl, $product_image, $product_title, $product_action);
            }
            echo '<div class="kitify-testimonials__product-infomation">';
            echo $product_content;
            echo '</div>';
            endif;
        }
        ?>
	</div>
</div>
