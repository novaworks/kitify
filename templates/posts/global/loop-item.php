<?php
/**
 * Posts loop start template
 */

$preset = $this->get_settings_for_display('preset');

$show_image     = $this->get_settings_for_display('show_image');
$show_title     = $this->get_settings_for_display('show_title');
$show_more      = $this->get_settings_for_display('show_more');
$show_excerpt   = $this->get_settings_for_display('show_excerpt');
$excerpt_length   = $this->get_settings_for_display('excerpt_length');
$title_html_tag = $this->get_settings_for_display('title_html_tag');

$floating_date = $this->get_settings_for_display('floating_date');
$floating_date_style = $this->get_settings_for_display('floating_date_style');
$floating_category = $this->get_settings_for_display('floating_category');
$floating_postformat = $this->get_settings_for_display('floating_postformat');
$floating_counter = $this->get_settings_for_display('floating_counter');
$floating_counter_as = $this->get_settings_for_display('floating_counter_as');


$meta1_pos = $this->get_settings_for_display('meta_position1');
$meta2_pos = $this->get_settings_for_display('meta_position2');

$post_classes = ['kitify-posts__item'];
if( $show_image == 'yes' && has_post_thumbnail() ) {
    $post_classes[] = 'has-post-thumbnail';
}

if(filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN)){
    $post_classes[] = 'swiper-slide';
}
else{
    $post_classes[] = kitify_helper()->col_new_classes('columns', $this->get_settings_for_display());
}

$post_classes = array_merge($post_classes, kitify_helper()->get_post_terms(get_the_ID(), 'id'));

$post_format = get_post_format();
if(empty($post_format)){
    $post_format = 'standard';
}

?>
<div class="<?php echo esc_attr(join(' ', $post_classes)) ?>">
    <div class="kitify-posts__outer-box">
        <div class="kitify-posts__inner-box"><?php
        if( $show_image == 'yes' && has_post_thumbnail() ) { ?>
            <div class="post-thumbnail kitify-posts__thumbnail">
                <a href="<?php the_permalink(); ?>" class="kitify-posts__thumbnail-link"><?php
                    the_post_thumbnail($this->get_settings_for_display( 'thumb_size' ), array(
                        'class' => 'kitify-posts__thumbnail-img wp-post-image nova-lazyload-image'
                    ))
                    ?></a>
                <?php if(filter_var($floating_date, FILTER_VALIDATE_BOOLEAN)): ?>
                <div class="kitify-posts__floating_date kitify-posts__floating_date--<?php echo esc_attr($floating_date_style);?>">
                    <div class="kitify-posts__floating_date-inner"><strong><?php echo get_the_date( 'd' );?></strong><span><?php echo get_the_date( 'M' );?></span></div>
                </div>
                <?php endif; ?>
                <?php if(filter_var($floating_category, FILTER_VALIDATE_BOOLEAN)): ?>
                <div class="kitify-posts__floating_category">
                    <div class="kitify-posts__floating_category-inner"><?php echo get_the_category_list(' ') ?></div>
                </div>
                <?php endif; ?>

                <?php if(filter_var($floating_postformat, FILTER_VALIDATE_BOOLEAN)): ?>
                <div class="kitify-posts__floating_postformat kitify-posts__floating_postformat-<?php echo $post_format ?>"><?php echo $this->render_post_format_icon($post_format) ?></div>
                <?php endif; ?>
            </div>
        <?php }

        echo '<div class="kitify-posts__inner-content">';

            if(filter_var($floating_counter, FILTER_VALIDATE_BOOLEAN)){
                echo '<div class="kitify-floating-counter">';
                if(filter_var($floating_counter_as, FILTER_VALIDATE_BOOLEAN)){
                    echo $this->_get_icon('counter_icon', '<span class="kitify-floating-counter--icon">%s</span>');
                }
                else{
                    echo '<span class="kitify-floating-counter--number"></span>';
                }
                echo '</div>';
            }

            echo '<div class="kitify-posts__inner-content-inner">';

        if($meta1_pos == 'before_title'){
            $this->_load_template( $this->_get_global_template( 'loop-meta1' ) );
        }
        if($meta2_pos == 'before_title'){
            $this->_load_template( $this->_get_global_template( 'loop-meta2' ) );
        }

        if($show_title == 'yes'){
            $title_length = -1;
            $title_ending = $this->get_settings_for_display( 'title_trimmed_ending_text' );

            if ( filter_var( $this->get_settings_for_display( 'title_trimmed' ), FILTER_VALIDATE_BOOLEAN ) ) {
                $title_length = $this->get_settings_for_display( 'title_length' );
            }

            $title = get_the_title();
            if($title_length > 0){
                $title = wp_trim_words( $title, $title_length, $title_ending );
            }

            echo sprintf(
                '<%1$s class="kitify-posts__title"><a href="%2$s" title="%3$s" rel="bookmark">%4$s</a></%1$s>',
                esc_attr($title_html_tag),
                esc_url(get_the_permalink()),
                esc_html(get_the_title()),
                esc_html($title)
            );
        }

        if($meta1_pos == 'after_title'){
            $this->_load_template( $this->_get_global_template( 'loop-meta1' ) );
        }
        if($meta2_pos == 'after_title'){
            $this->_load_template( $this->_get_global_template( 'loop-meta2' ) );
        }

        if($show_excerpt){
            $excerpt_length = absint($excerpt_length);
            if($excerpt_length > 0){
                echo sprintf(
                    '<div class="kitify-posts__excerpt entry-excerpt">%1$s</div>',
                    wp_trim_words(get_the_excerpt(), $excerpt_length)
                );
            }
        }

        if($meta1_pos == 'after_content'){
            $this->_load_template( $this->_get_global_template( 'loop-meta1' ) );
        }
        if($meta2_pos == 'after_content'){
            $this->_load_template( $this->_get_global_template( 'loop-meta2' ) );
        }


        if($show_more == 'yes'){
            echo sprintf(
                '<div class="kitify-posts__more-wrap kitify-btn-more-wrap"><a href="%2$s" class="elementor-button kitify-posts__btn-more kitify-btn-more" title="%3$s" rel="bookmark"><span class="btn__text">%1$s</span>%4$s</a></div>',
                $this->get_settings_for_display( 'more_text' ),
                esc_url(get_the_permalink()),
                esc_html(get_the_title()),
                $this->_get_icon('more_icon', '<span class="kitify-btn-more-icon">%s</span>')
            );
        }

        if($meta1_pos == 'after_button'){
            $this->_load_template( $this->_get_global_template( 'loop-meta1' ) );
        }
        if($meta2_pos == 'after_button'){
            $this->_load_template( $this->_get_global_template( 'loop-meta2' ) );
        }

        echo '</div>';
        echo '</div>';

    ?></div>
    </div>
</div>