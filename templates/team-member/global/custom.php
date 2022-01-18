<?php
/**
 * Team Member template
 */

$preset             = $this->get_settings_for_display('preset');
$layout             = $this->get_settings_for_display('layout_type');
$enable_carousel    = filter_var( $this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN );
$enable_custom_image_height    = filter_var( $this->get_settings_for_display('enable_custom_image_height'), FILTER_VALIDATE_BOOLEAN );


$this->add_render_attribute( 'main-container', 'id', 'tm_' . $this->get_id() );

$this->add_render_attribute( 'main-container', 'class', array(
	'kitify-team-member',
	'layout-type-' . $layout,
	'preset-' . $preset,
) );

$this->add_render_attribute( 'list-container', 'class', array(
    'kitify-team-member__list'
) );

if( $enable_custom_image_height ) {
    $this->add_render_attribute( 'list-container', 'class', array(
        'active-object-fit'
    ) );
}

$this->add_render_attribute( 'list-container', 'data-item_selector', array(
    '.kitify-team-member__item'
) );

$this->add_render_attribute( 'list-wrapper', 'class', 'kitify-team-member__list_wrapper');

$is_carousel = false;

if('grid' == $layout && !$enable_carousel){
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
            <?php
            $items = $this->get_settings_for_display('items');

            $title_tag          = $this->get_settings_for_display('title_html_tag');
            $show_role          = filter_var( $this->get_settings_for_display('show_role'), FILTER_VALIDATE_BOOLEAN );
            $show_social        = filter_var( $this->get_settings_for_display('show_social'), FILTER_VALIDATE_BOOLEAN );
            $show_excerpt       = filter_var( $this->get_settings_for_display('show_excerpt'), FILTER_VALIDATE_BOOLEAN );
            $excerpt_length     = absint( $this->get_settings_for_display('excerpt_length') );

            if($items){
                foreach ($items as $index => $item) {

                    $member_link        = !empty($item['link']) ? $item['link'] : [];
                    $member_name        = !empty($item['name']) ? $item['name'] : '';
                    $member_image       = !empty($item['image']) ? $item['image'] : [];
                    $member_role        = !empty($item['role']) ? $item['role'] : '';
                    $member_description = !empty($item['description']) ? $item['description'] : '';

                    $link_key = 'member_link_' . $index;

                    $social_html = $this->_get_member_social($item);
                    $post_classes = ['kitify-team-member__item'];
                    if($is_carousel){
                        $post_classes[] = 'swiper-slide';
                    }
                    else{
                        $post_classes[] = kitify_helper()->col_new_classes('columns', $this->get_settings_for_display());
                    }
                    ?>
                    <div class="<?php echo esc_attr(join(' ', $post_classes)) ?>">
                        <div class="kitify-team-member__inner-box">
                            <div class="kitify-team-member__inner">
                                <div class="kitify-team-member__image_wrap">
                                    <?php
                                    $this->add_render_attribute( $link_key, 'class', 'kitify-team-member__link' );
                                    $tag_link = 'div';
                                    if ( ! empty( $member_link['url'] ) ) {
                                        $tag_link = 'a';
                                        $this->add_link_attributes( $link_key, $member_link );
                                        $this->add_render_attribute( $link_key, 'title', esc_attr($member_name) );
                                    }
                                    echo sprintf('<%1$s %2$s>%3$s</%1$s>', $tag_link, $this->get_render_attribute_string( $link_key ), $this->_get_member_image( $member_image ));
                                    if(in_array($preset, array('type-1', 'type-2', 'type-3')) && $show_social && !empty($social_html)){
                                        echo '<div class="kitify-team-member__cover"><div class="kitify-team-member__socials">' . $social_html . '</div></div>';
                                    }
                                    ?>
                                </div>
                                <div class="kitify-team-member__content">
                                        <?php

                                        $this->remove_render_attribute($link_key, 'class');
                                        echo sprintf(
                                            '<%1$s class="kitify-team-member__name"><a %2$s>%3$s</a></%1$s>',
                                            esc_attr($title_tag),
                                            $this->get_render_attribute_string( $link_key ),
                                            esc_html($member_name)
                                        );

                                        if(!empty($member_role) && $show_role){
                                            echo sprintf('<div class="kitify-team-member__position"><span>%s</span></div>', esc_html($member_role));
                                        }

                                        if($excerpt_length > 0){
                                            echo sprintf(
                                                '<p class="kitify-team-member__desc">%1$s</p>',
                                                wp_trim_words($member_description, $excerpt_length)
                                            );
                                        }

                                        if(!in_array($preset, array('type-1', 'type-2', 'type-3')) && $show_social && !empty($social_html)){
                                            echo '<div class="kitify-team-member__socials">' . $social_html . '</div>';
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
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
