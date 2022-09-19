<?php
	/**
	 * Loop item template
	 */

	$img = $this->get_advanced_carousel_img( 'kitify-carousel__item-img' );

	$item_settings = $this->_processed_item;

	$content_type = ! empty( $item_settings['item_content_type'] ) ? $item_settings['item_content_type'] : 'default';

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

    $btn_icon =  $this->_btn_icon('<span class="btn-icon">%s</span>');

?><div class="kitify-carousel__item swiper-slide">
	<div class="kitify-carousel__item-inner">
        <?php if(empty($img) && $content_type == 'template') :?>
            <div class="kitify-template-wrapper"><?php echo $this->_loop_item_template_content();?></div>
        <?php else: ?>
        <?php
		if ( $img ) {
            printf('<a class="%1$s" %2$s>%3$s</a>', 'kitify-carousel__item-link', $a_link_attribute, $img);
		}
		echo '<div class="kitify-carousel__content">';
			switch ( $content_type ) {
				case 'default':

					echo $this->_loop_icon( '<div class="kitify-carousel__item-icon"><div class="kitify-icon-inner">%s</div></div>' );

					$title  = $this->_loop_item( array( 'item_title' ) );
					$text   = $this->_loop_item( array( 'item_text' ), '<div class="kitify-carousel__item-text">%s</div>' );
                    $button = '';
                    if(!empty($a_link_attribute) && ( !empty($item_settings['item_button_text']) || !empty($btn_icon) )){
                        $button = sprintf('<a class="elementor-button elementor-size-md kitify-carousel__item-button" %1$s><span>%2$s</span>%3$s</a>', $a_link_attribute, $item_settings['item_button_text'], $btn_icon);
                    }

					$title_format = '<%1$s class="kitify-carousel__item-title">%2$s</%1$s>';

					if ( $link_title && $settings['item_link_type'] === 'link'  ) {
						$title_format = '<%1$s class="kitify-carousel__item-title"><a %3$s>%2$s</a></%1$s>';
					}

					if ( $title ) {
						echo sprintf( $title_format, $title_tag, $title, $a_link_attribute );
					}
                    echo $text;
                    echo $button;
					break;
				case 'template':
					echo $this->_loop_item_template_content();
					break;
			}
		echo '</div>';?>
    <?php endif; ?>
    </div>
</div>
