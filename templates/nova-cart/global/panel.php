<div class="kitify-offcanvas minicart-canvas site-canvas-menu off-canvas position-right" id="MiniCartCanvas_<?php echo $this->get_id()?>" data-off-canvas data-transition="overlap">
  <h2 class="title"><?php echo esc_html__( 'Shopping Cart', 'robeto' );?><span class="nova_js_count_bag_item_canvas count-item-canvas"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span></h2>
  <div class="add_ajax_loading">
    <span></span>
  </div>
  <?php if ( class_exists( 'WC_Widget_Cart' ) ) { the_widget( 'WC_Widget_Cart' ); } ?>
  <button class="close-button" aria-label="Close menu" type="button" data-close>
    <svg class="nova-close-canvas">
      <use xlink:href="#nova-close-canvas"></use>
    </svg>
  </button>
</div>
