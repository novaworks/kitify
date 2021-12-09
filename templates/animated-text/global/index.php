<?php
/**
 * Animated text list template
 */
$settings = $this->get_settings_for_display();
$data_settings = $this->generate_setting_json();

$classes[] = 'kitify-animated-text';
$classes[] = 'kitify-animated-text--effect-' . $settings['animation_effect'];

?>
<div class="<?php echo implode( ' ', $classes ); ?>" <?php echo $data_settings; ?>>
	<?php $this->_glob_inc_if( 'before-text', array( 'before_text_content' ) ); ?>
	<?php $this->_get_global_looped_template( 'animated-text', 'animated_text_list' ); ?>
	<?php $this->_glob_inc_if( 'after-text', array( 'after_text_content' ) ); ?>
</div>
