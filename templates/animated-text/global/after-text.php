<?php
/**
 * Animated after text template
 */
$settings = $this->get_settings_for_display();
?>
<div class="kitify-animated-text__after-text">
	<?php
		echo $this->str_to_spanned_html( $settings['after_text_content'], 'word' );
	?>
</div>
