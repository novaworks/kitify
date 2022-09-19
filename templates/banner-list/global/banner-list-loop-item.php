<?php
/**
 * Images list item template
 */

$_processed_item = $this->_processed_item;

$col_class = [];
$col_class[] = 'elementor-repeater-item-' . $_processed_item['_id'];
$col_class[] = 'kitify-bannerlist__item';
$el_class = $this->_loop_item( array( 'el_class' ), '%s' );
if(!empty($el_class)){
    $col_class[] = $el_class;
}

$enable_carousel    = filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN);

if($enable_carousel){
    $col_class[] = 'swiper-slide';
}

$col_class[] = kitify_helper()->col_new_classes('columns', $this->get_settings_for_display());

$link_tag = 'a';
$btn_tag = 'a';

$link_instance = 'link_' . $this->_processed_index;
$btn_instance = 'btn_' . $this->_processed_index;

if(!empty($_processed_item['link']['url'])){
    if($_processed_item['link_click'] == 'button'){
        $this->_add_link_attributes( $btn_instance, $_processed_item['link'] );
    }
    else{
        $this->_add_link_attributes( $link_instance, $_processed_item['link'] );
    }
}

$this->add_render_attribute( $btn_instance, 'class', array(
    'elementor-button kitify-bannerlist__btn'
) );

$this->add_render_attribute( $link_instance, 'class', array(
    'kitify-bannerlist__link'
) );

if($_processed_item['link_click'] == 'button'){
    $link_tag = 'div';
}
else{
    $btn_tag = 'span';
}

$item_instance = 'item-instance-' . $this->_processed_index;

$this->add_render_attribute( $item_instance, 'class', $col_class );

$btn_icon =  $this->_get_icon_setting( $this->get_settings_for_display('selected_btn_icon'), '<span class="btn-icon">%s</span>' );

?>
<div <?php echo $this->get_render_attribute_string( $item_instance ); ?>>
	<div class="kitify-bannerlist__inner">
		<<?php echo $link_tag; ?> <?php $this->print_render_attribute_string($link_instance); ?>>
			<div class="kitify-bannerlist__image"><?php
                echo $this->get_loop_image_item();
				?>
			</div>
			<div class="kitify-bannerlist__content">
                <div class="kitify-bannerlist__content-inner"><?php
                echo $this->_loop_item( array( 'subtitle' ), '<div class="kitify-bannerlist__subtitle">%s</div>' );
                echo $this->_loop_item( array( 'title' ), '<div class="kitify-bannerlist__title">%s</div>' );
                echo $this->_loop_item( array( 'description' ), '<div class="kitify-bannerlist__desc">%s</div>' );
                echo $this->_loop_item( array( 'subdescription' ), '<div class="kitify-bannerlist__subdesc">%s</div>' );
                if(!empty($_processed_item['button_text']) || !empty($btn_icon)){
                    echo sprintf('<%1$s %2$s>%3$s%4$s</%1$s>', $btn_tag, $this->get_render_attribute_string($btn_instance), $_processed_item['button_text'], $btn_icon);
                }
            ?></div>
            </div>
		</<?php echo $link_tag; ?>>
	</div>
</div>