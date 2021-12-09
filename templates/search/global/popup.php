<?php
/**
 * Popup template
 */
?>
<div class="kitify-search__popup-trigger">
  <?php include $this->_get_global_template( 'popup-trigger' ); ?>
  <div id="headerSearchModal" class="full-search-reveal">
    <button id="btn-close-search-modal" class="close-button close-headerSearchModal" aria-label="Close menu" type="button" data-close>
      <svg class="vedbo-close-canvas">
        <use xlink:href="#vedbo-close-canvas"></use>
      </svg>
    </button>
    <div class="site-search full-screen">
      <div class="header-search">

          <?php if ( NOVA_WOOCOMMERCE_IS_ACTIVE ) : ?>
              <?php do_action('nova_ajax_search_form'); ?>
          <?php else: ?>
              <?php get_search_form(); ?>
          <?php endif; ?>
      </div>
    </div>
  </div>

</div>
