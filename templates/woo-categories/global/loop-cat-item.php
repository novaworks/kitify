<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$enable_carousel    = filter_var( $this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN );
$cat_count_label =  $this->get_settings_for_display('cat_count_label');

$post_classes = ['kitify-product-categories__item'];

if( $enable_carousel ){
    $post_classes[] = 'swiper-slide';
}
else{
    $post_classes[] = kitify_helper()->col_new_classes('columns', $this->get_settings_for_display());
}
$cat_thumb_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
$cat_thumb_url = wp_get_attachment_image_src( $cat_thumb_id, 'woocommerce_thumbnail' );
?>
<div class="<?php echo esc_attr(join(' ', $post_classes)) ?>">
  <div class="kitify-product-categories__item--inner">
		<div class="cat-count">
			<span class="count"><?php echo $category->count ?></span>
			<?php if($cat_count_label):?>
				<span class="count-items"><?php echo esc_html($cat_count_label) ?></span>
			<?php endif;?>
		</div>
		<?php echo sprintf('<%1$s %2$s>%3$s</%1$s>', $tag_link, $this->get_render_attribute_string( $link_key ), $this->_get_member_image( $member_image )); ?>
    <div class="cat-name">
      <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
        <h2><?php echo $category->name ?></h2>
      </a>
    </div>
  </div>
</div>
