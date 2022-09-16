<?php
	/**
	 * Loop item template
	 */
	
	$target = $this->_loop_item( array( 'item_link_target' ), ' target="%s"' );
	$rel = $this->_loop_item( array( 'item_link_rel' ), ' rel="%s"' );
	
	$item_settings = $this->_processed_item;
	
	$content_type = ! empty( $item_settings['item_content_type'] ) ? $item_settings['item_content_type'] : 'default';

	$img = $this->get_advanced_carousel_img( 'kitify-banner__img' );
	$lightbox = 'data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . $this->get_id() . '"';
	$settings = $this->get_settings_for_display();

    $a_link_attribute = '';

    if($content_type !== 'template'){
        if ( $settings['item_link_type'] === 'link' ) {
            if(!empty($item_settings['item_link']['url'])){
                $this->_add_link_attributes( 'readmore_btn_' . $this->_processed_index, $item_settings['item_link'] );
                $a_link_attribute = $this->get_render_attribute_string('readmore_btn_' . $this->_processed_index);
            }
        }
        else{
            $a_link_attribute = sprintf('href="%1$s" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="%2$s"', $item_settings['item_image']['url'], $this->get_id());
        }
    }

?>
<div class="kitify-carousel__item swiper-slide">
	<div class="kitify-carousel__item-inner">
    <?php if(empty($img) && $content_type == 'template') :?>
        <div class="kitify-template-wrapper"><?php echo $this->_loop_item_template_content();?></div>
    <?php else: ?>
    <figure class="kitify-banner kitify-effect-<?php echo esc_attr( $this->get_settings_for_display( 'animation_effect' ) ); ?>"><?php
        printf('<a class="%1$s" %2$s>', 'kitify-banner__link', $a_link_attribute);
        echo '<div class="kitify-banner__overlay"></div>';
        echo $img;
        echo '<figcaption class="kitify-banner__content">';
            echo '<div class="kitify-banner__content-wrap">';
                echo $this->_loop_item( array( 'item_title' ), '<' . $title_tag . ' class="kitify-banner__title">%s</' . $title_tag . '>' );
                echo $this->_loop_item( array( 'item_text' ), '<div class="kitify-banner__text">%s</div>' );
                if(!empty($item_settings['item_button_text'])){
                    echo sprintf('<button role="button" class="elementor-button elementor-size-md">%1$s</button>', $item_settings['item_button_text']);
                }
            echo '</div>';
        echo '</figcaption>';
        printf( '</a>' );
	?></figure>
    <?php endif; ?>
	</div>
</div>