<div class="kitify-creative-banners__links">
  <ul>
    <?php
    $items = $this->get_settings_for_display('items');
    $id_int = substr( $this->get_id_int(), 0, 3 );
    $title_html_tag = $this->get_settings_for_display('category_name_tag');
    if($items){
      foreach ($items as $index => $item) {
        $item_count = $index + 1;
        $item_id = $id_int . $item_count;
        $item_title        = !empty($item['item_title']) ? $item['item_title'] : '';
        $item_link         = !empty($item['item_link']) ? $item['item_link'] : '#';
        echo '<li data-item-id='.$item_id.'>';
        echo sprintf('<a href="%1$s"><%2$s class="b-title">%3$s</%2$s></a>', $item_link['url'], $title_html_tag, $item_title);
        echo '</li>';
      }
    }
     ?>
  </ul>
</div>
