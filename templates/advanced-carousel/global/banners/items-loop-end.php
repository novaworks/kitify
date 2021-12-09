<?php
/**
 * Loop end template
 */
?></div></div></div><?php
if ( filter_var(  $this->get_settings_for_display( 'carousel_dots' ), FILTER_VALIDATE_BOOLEAN ) ) {
    echo '<div class="kitify-carousel__dots kitify-carousel__dots_'.$this->get_id().' swiper-pagination"></div>';
}
if ( filter_var(  $this->get_settings_for_display( 'carousel_arrows' ), FILTER_VALIDATE_BOOLEAN ) ) {
    echo sprintf( '<div class="kitify-carousel__prev-arrow-%s kitify-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'carousel_prev_arrow', '%s', '', false ) );
    echo sprintf( '<div class="kitify-carousel__next-arrow-%s kitify-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'carousel_next_arrow', '%s', '', false ) );
}
if ( filter_var(  $this->get_settings_for_display( 'carousel_scrollbar' ), FILTER_VALIDATE_BOOLEAN ) ) {
    echo '<div class="kitify-carousel__scrollbar swiper-scrollbar"></div>';
}
?></div>