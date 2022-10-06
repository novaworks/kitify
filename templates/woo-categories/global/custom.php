<?php
/**
 * Product categories template
 */

 $this->add_render_attribute( 'main-container', 'class', 'kitify-product-categories' );
 $this->add_render_attribute( 'main-container', 'class', 'custom-style-'.$this->get_settings_for_display('preset') );
 $this->add_render_attribute( 'list-wrapper', 'class', 'kitify-product-categories__wrapper' );
 $this->add_render_attribute( 'list-container', 'class', 'kitify-product-categories__list' );

 $is_carousel = false;
 $this->add_render_attribute( 'list-container', 'data-item_selector', array(
     '.kitify-product-categories__item'
 ) );
 if(filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN)){
     $slider_options = $this->get_advanced_carousel_options('columns');
     if(!empty($slider_options)){

         $is_carousel = true;

         $this->add_render_attribute( 'main-container', 'data-slider_options', json_encode($slider_options) );
         $this->add_render_attribute( 'main-container', 'dir', is_rtl() ? 'rtl' : 'ltr' );
         $this->add_render_attribute( 'list-wrapper', 'class', 'swiper-container');
         $this->add_render_attribute( 'list-container', 'class', 'swiper-wrapper' );
         $this->add_render_attribute( 'main-container', 'class', 'kitify-carousel' );

         $carousel_id = $this->get_settings_for_display('carousel_id');
         if(empty($carousel_id)){
             $carousel_id = 'kitify_carousel_' . $this->get_id();
         }
         $this->add_render_attribute( 'list-wrapper', 'id', $carousel_id );

     }
 } else {
     $this->add_render_attribute( 'list-container', 'class', 'col-row' );
 }
?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
  <?php
  if($is_carousel){
      echo '<div class="kitify-carousel-inner">';
  }
  ?>
  <div <?php echo $this->get_render_attribute_string( 'list-wrapper' ); ?>>
      <div <?php echo $this->get_render_attribute_string( 'list-container' ); ?>>
        <?php
					$items = $this->get_settings_for_display('items');
					$button_icon = $this->_get_icon( 'button_icon', '<span class="kitify-custom-categories__button-icon">%s</span>' );
					$enable_carousel    = filter_var( $this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN );
          $title_html_tag = $this->get_settings_for_display('category_name_tag');

					$post_classes = ['kitify-custom-categories__item'];
					if( $enable_carousel ){
					    $post_classes[] = 'swiper-slide';
					}else{
              $post_classes[] = kitify_helper()->col_new_classes('columns', $this->get_settings_for_display());
          }
					if($items){
						foreach ($items as $index => $item) {
							$item_title        = !empty($item['item_title']) ? $item['item_title'] : '';
							$item_count        = !empty($item['count_text']) ? $item['count_text'] : '';
							$item_desc         = !empty($item['item_desc']) ? $item['item_desc'] : '';
              $item_link         = !empty($item['item_link']) ? $item['item_link'] : '';
              $button_label      = !empty($item['item_link']) ? $item['item_link_text'] : '';
              $item_image = $item['item_image'];
							?>
							<div class="<?php echo esc_attr(join(' ', $post_classes)) ?>">
							  <div class="kitify-custom-categories__item-inner">
                  <div class="kitify-custom-categories__thumb">
                    <?php echo sprintf('<a href="%1$s" class="kitify-custom-categories__link cat_overlay"></a>', $item_link['url']); ?>
                    <?php echo sprintf('<div class="kitify-custom-categories__image-wrap">%1$s</div>', $this->_get_cat_image( $item_image )); ?>
                    <div class="kitify-custom-categories__button-wrap">
                      <?php
                      if (!empty($button_icon) || !empty($button_label) ){
                        echo sprintf('<a href="%1$s" class="button kitify-custom-categories__button">%2$s %3$s</a>', $item_link['url'], $button_icon, $button_label);
                      }
                      ?>
                    </div>
                  </div>
                  <div class="kitify-custom-categories__content-wrap">
                    <?php echo sprintf('<%1$s class="kitify-custom-categories__title">%2$s</%1$s>',  esc_attr($title_html_tag), $item_title); ?>
                    <?php echo sprintf('<span class="kitify-custom-categories__count">%1$s</span>', $item_count); ?>
                    <?php echo sprintf('<span class="kitify-custom-categories__desc">%1$s</span>', $item_desc); ?>
                  </div>
								</div>
              </div>
							<?php
						}
					}
				?>
      </div>
  </div>
  <?php
  if($is_carousel){
      echo '</div>';
      if (filter_var($this->get_settings_for_display('carousel_dots'), FILTER_VALIDATE_BOOLEAN)) {
          echo '<div class="kitify-carousel__dots kitify-carousel__dots_'.$this->get_id().' swiper-pagination"></div>';
      }
      if (filter_var($this->get_settings_for_display('carousel_arrows'), FILTER_VALIDATE_BOOLEAN)) {
          echo sprintf('<div class="kitify-carousel__prev-arrow-%s kitify-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon('carousel_prev_arrow', '%s', '', false));
          echo sprintf('<div class="kitify-carousel__next-arrow-%s kitify-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon('carousel_next_arrow', '%s', '', false));
      }
      if (filter_var($this->get_settings_for_display('carousel_scrollbar'), FILTER_VALIDATE_BOOLEAN)) {
          echo '<div class="kitify-carousel__scrollbar swiper-scrollbar"></div>';
      }
  }
  ?>
</div>
