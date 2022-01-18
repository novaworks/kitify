<?php
$enable_carousel    = filter_var( $this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN );

$post_classes = ['kitify-product-categories__item'];

if( $enable_carousel ){
    $post_classes[] = 'swiper-slide';
}
else{
    $post_classes[] = kitify_helper()->col_new_classes('columns', $this->get_settings_for_display());
}
?>
<div class="<?php echo esc_attr(join(' ', $post_classes)) ?>">
  <div class="kitify-product-categories__item--inner">
    <div class="cat-image">
      <div class="cat-count">
        <span>20</span>
      </div>
      <a href="#"><img src="https://wpbingosite.com/wordpress/ruper/wp-content/uploads/2021/10/categories-1.jpg" alt=""></a>
    </div>
    <div class="cat-name">
      <a href="#"><h2>Dresses</h2></a>
    </div>
  </div>
</div>
