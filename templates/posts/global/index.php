<?php
/**
 * Posts template
 */

$settings           = $this->get_settings_for_display();

$preset             = $this->get_settings_for_display('preset');
$layout             = $this->get_settings_for_display('layout_type');
$enable_carousel    = filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN);
$enable_masonry     = filter_var($this->get_settings_for_display('enable_masonry'), FILTER_VALIDATE_BOOLEAN);
$query_post_type    = $this->get_settings_for_display('query_post_type');

$floating_counter = $this->get_settings_for_display('floating_counter');
$floating_counter_as = $this->get_settings_for_display('floating_counter_as');

$this->add_render_attribute( 'main-container', 'id', 'novapost_' . $this->get_id() );

$this->add_render_attribute( 'main-container', 'class', array(
	'kitify-posts',
	'layout-type-' . $layout,
	'preset-' . $preset,
    'querycpt--' . (!empty($query_post_type) ? $query_post_type : 'default')
) );

if(filter_var($floating_counter, FILTER_VALIDATE_BOOLEAN)){
    $this->add_render_attribute( 'main-container', 'class', 'enable--counter' );
    if(filter_var($floating_counter_as, FILTER_VALIDATE_BOOLEAN)){
        $this->add_render_attribute( 'main-container', 'class', 'enable--counter-as-icon' );
    }
}

$this->add_render_attribute( 'main-container', 'data-item_selector', '.kitify-posts__item' );

if(false !== strpos($preset, 'grid')){
    $this->add_render_attribute( 'main-container', 'class', 'kitify-posts--grid' );
}
else{
    $this->add_render_attribute( 'main-container', 'class', 'kitify-posts--list' );
}

$this->add_render_attribute( 'list-container', 'class', 'kitify-posts__list' );

if('grid' == $layout && !$enable_carousel){
    $this->add_render_attribute( 'list-container', 'class', 'col-row' );
}

$this->add_render_attribute( 'list-wrapper', 'class', 'kitify-posts__list_wrapper');

$is_carousel = false;

$masonry_attr = '';

if($enable_masonry){
    $this->add_render_attribute( 'main-container', 'class', 'kitify-masonry-wrapper' );
    $masonry_attr = $this->get_masonry_options('.kitify-posts__item', '.kitify-posts__list');
}
else{
    if($enable_carousel){
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
}

$the_query = $this->the_query();

?>

<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?> <?php echo $this->render_variable($masonry_attr); ?>><?php

    if($the_query->have_posts()){
        if($is_carousel){
            echo '<div class="kitify-carousel-inner">';
        }

        if( $enable_masonry ){
          $this->render_masonry_filters('#novapost_'.$this->get_id().' .kitify-posts__list');
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'list-wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'list-container' ); ?>>
            <?php

            while ($the_query->have_posts()){

                $the_query->the_post();

                $this->_load_template( $this->_get_global_template( 'loop-item' ) );

                $this->item_counter++;
                $this->_processed_index++;
            }
            ?>
            </div>
        </div>
    <?php
        if($is_carousel){
            echo '</div>';
        }

        if ($enable_carousel && !$enable_masonry ) {
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

        if( $this->get_settings_for_display('paginate') == 'yes' ){

            if( $this->get_settings_for_display('loadmore_text') ) {
                $load_more_text = $this->get_settings_for_display('loadmore_text');
            }
            else{
                $load_more_text = esc_html__('Load More', 'kitify');
            }

            $nav_classes = array('post-pagination', 'kitify-pagination', 'clearfix', 'kitify-ajax-pagination');

            if( $this->get_settings_for_display('paginate_as_loadmore') == 'yes') {
                $nav_classes[] = 'active-loadmore';
            }

            $paginated = ! $the_query->get( 'no_found_rows' );

            $p_total_pages = $paginated ? (int) $the_query->max_num_pages : 1;
            $p_current_page = $paginated ? (int) max( 1, $the_query->get( 'paged', 1 ) ) : 1;

            $paged_key = 'post-page' . esc_attr($this->get_id());

            if( $query_post_type == 'current_query'){
                $paged_key = 'paged';
            }

            $p_base = add_query_arg(null, null, false);
            $p_base = esc_url_raw( add_query_arg( $paged_key, '%#%', $p_base ) );
            $p_format = '?'.$paged_key.'=%#%';

            if( $p_total_pages == $p_current_page ) {
                $nav_classes[] = 'nothingtoshow';
            }

            $pagination_args = array(
                'total'        => $p_total_pages,
                'type'         => 'list',
                'prev_text'    => __( '&laquo;', 'kitify' ),
                'next_text'    => __( '&raquo;', 'kitify' ),
                'end_size'     => 3,
                'mid_size'     => 3
            );

            if($query_post_type != 'current_query'){
                $pagination_args['base']    = $p_base;
                $pagination_args['format']  = $p_format;
                $pagination_args['current'] = max( 1, $p_current_page );
            }

            ?>
            <nav class="<?php echo join(' ', $nav_classes) ?>" data-parent-container="#novapost_<?php echo $this->get_id() ?>" data-container="#novapost_<?php echo $this->get_id() ?> .kitify-posts__list" data-item-selector=".kitify-posts__item" data-ajax_request_id="<?php echo $paged_key ?>">
                <div class="kitify-ajax-loading-outer"><span class="kitify-css-loader"></span></div>
                <div class="kitify-post__loadmore_ajax kitify-pagination_ajax_loadmore">
                    <a href="javascript:;"><span><?php echo esc_html($load_more_text); ?></span></a>
                </div>
                <?php
                echo paginate_links( apply_filters( 'kitify/posts/pagination_args', $pagination_args, 'post' ) );
                ?>
            </nav>
            <?php
        }
    ?>

    <?php
        $this->item_counter = 0;
        $this->_processed_index = 0;
    }
    ?>
</div>
