<div class="kitify-creative-banners__images">
    <?php
    $items = $this->get_settings_for_display('items');
    $id_int = substr( $this->get_id_int(), 0, 3 );
    if($items){
      foreach ($items as $index => $item) {
        $item_count = $index + 1;
        $item_id = $id_int . $item_count;
        $item_image = $item['item_image'];
        $active = '';
        if($item_count == 1) {
          $active = 'active';
        }
        echo sprintf('<div data-item-id="%1$s" data-background-image="%2$s" class="kitify-creative-banners__image nova-lazyload-image %3$s">%4$s</div>', $item_id,$item_image['url'], $active, $this->_get_banner_image( $item_image ));
      }
    }
     ?>
</div>
