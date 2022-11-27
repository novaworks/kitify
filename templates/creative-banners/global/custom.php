<?php
/**
 * Product categories template
 */

 $this->add_render_attribute( 'main-container', 'class', 'kitify-creative-banners' );
 $this->add_render_attribute( 'main-container', 'class', 'custom-style-'.$this->get_settings_for_display('preset') );
 $this->add_render_attribute( 'list-wrapper', 'class', 'kitify-creative-banners__wrapper' );
?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
  <div <?php echo $this->get_render_attribute_string( 'list-wrapper' ); ?>>
    <?php $this->_load_template( $this->_get_global_template( 'links' ) );?>
    <?php $this->_load_template( $this->_get_global_template( 'images' ) );?>
  </div>
</div>
