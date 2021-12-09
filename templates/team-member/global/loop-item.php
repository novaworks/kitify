<?php
/**
 * team-member loop start template
 */

$thumbnail_size = $this->get_settings_for_display('thumb_size');
$excerpt_length = absint($this->get_settings_for_display('excerpt_length'));

$thumb_src = '';
$thumb_width = $thumb_height = 0;
$thumb_css_class = '';
$thumb_css_style = '';
if(has_post_thumbnail()){
    if($thumbnail_obj = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumbnail_size )){
        list( $thumb_src, $thumb_width, $thumb_height ) = $thumbnail_obj;
        if( $thumb_width > 0 && $thumb_height > 0 ) {
            $thumb_css_style .= 'padding-bottom:' . round( ($thumb_height/$thumb_width) * 100, 2 ) . '%;';
        }
    }
}

$use_lazy_load = true;
if($use_lazy_load){
    $thumb_css_class .= ' nova-lazyload-image';
}
else{
    $thumb_css_style .= sprintf("background-image: url('%s');", esc_url($thumb_src));
}

$post_link = get_the_permalink();

?>
<div class="nova-team-member__item loop__item grid-item">
    <div class="nova-team-member__inner-box">
        <div class="nova-team-member__inner">
            <div class="nova-team-member__image">
                <div class="loop__item__thumbnail--bkg<?php echo esc_attr($thumb_css_class); ?>" data-background-image="<?php if(!empty($thumb_src)){ echo esc_url($thumb_src); }?>" style="<?php echo esc_attr($thumb_css_style); ?>">
                    <a href="<?php echo esc_url($post_link); ?>" title="<?php the_title_attribute(); ?>" class="loop__item__thumbnail--linkoverlay"><span class="hidden"><?php the_title(); ?></span></a>
                    <?php the_post_thumbnail($thumbnail_size, array('alt' => esc_attr(get_the_title()))); ?>
                </div>
            </div>
            <div class="nova-team-member__content">
                <h3 class="nova-team-member__name"><a href="<?php echo esc_url($post_link); ?>"><?php the_title();?></a></h3>
                <?php
                if($excerpt_length > 0){
                    echo sprintf(
                        '<p class="nova-team-member__desc">%1$s</p>',
                        kitify_helper()->get_excerpt(intval( $excerpt_length ))
                    );
                }
                ?>
            </div>
        </div>
    </div>
</div>