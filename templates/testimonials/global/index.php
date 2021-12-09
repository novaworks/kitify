<?php
/**
 * Testimonials template
 */

$preset             = $this->get_settings_for_display('preset');
$enable_carousel    = filter_var( $this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN );


$this->add_render_attribute( 'main-container', 'id', 'tt_' . $this->get_id() );

$this->add_render_attribute( 'main-container', 'class', array(
    'kitify-testimonials',
    'preset-' . $preset,
) );

$this->add_render_attribute( 'list-container', 'class', array(
    'kitify-testimonials__list'
) );

$this->add_render_attribute( 'list-container', 'data-item_selector', array(
    '.kitify-testimonials__item'
) );

$this->add_render_attribute( 'list-wrapper', 'class', 'kitify-testimonials__list_wrapper');

$is_carousel = false;

if ( filter_var( $this->get_settings_for_display( 'use_comment_corner' ), FILTER_VALIDATE_BOOLEAN ) ) {
    $this->add_render_attribute('list-container', 'class', 'kitify-testimonials--comment-corner');
}

if(!$enable_carousel){
    $this->add_render_attribute( 'list-container', 'class', 'col-row' );
}
if( $enable_carousel ){
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
        $this->add_render_attribute( 'list-container', 'id', $carousel_id );
    }
}

?>

<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
    <?php
    if($is_carousel){
        echo '<div class="kitify-carousel-inner">';
    }
    ?>
    <div <?php echo $this->get_render_attribute_string( 'list-wrapper' ); ?>>
        <div <?php echo $this->get_render_attribute_string('list-container'); ?>>
            <?php $this->_get_global_looped_template( 'testimonials', 'item_list' ); ?>
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