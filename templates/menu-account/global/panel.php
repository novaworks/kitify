<?php if ( NOVA_WOOCOMMERCE_IS_ACTIVE ) : ?>
  <?php if (!is_user_logged_in() ) : ?>
    <div class="kitify-offcanvas login-canvas site-canvas-menu off-canvas position-right" id="AcccountCanvas_<?php echo $this->get_id()?>" data-off-canvas data-transition="overlap">
      <div class="kitify-offcanvas__content nova_box_ps">
        <?php wc_get_template( 'myaccount/form-login.php', array( 'is_popup' => true ) ); ?>
      </div>
      <button class="close-button" aria-label="Close menu" type="button" data-close>
        <svg class="nova-close-canvas">
          <use xlink:href="#nova-close-canvas"></use>
        </svg>
      </button>
    </div>
  <?php endif; ?>
<?php endif; ?>
