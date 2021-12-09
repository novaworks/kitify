<?php
/**
 * Timeline list template
 */

$settings = $this->get_settings_for_display();

$classes_list[] = 'kitify-vtimeline';
$classes_list[] = 'kitify-vtimeline--align-' . $settings['horizontal_alignment'];
$classes_list[] = 'kitify-vtimeline--align-' . $settings['vertical_alignment'];
$image_in_meta = filter_var($this->get_settings_for_display('image_in_meta'), FILTER_VALIDATE_BOOLEAN);
if($image_in_meta){
    $classes_list[] = 'kitify-vtimeline--imageinmeta';
}
$classes = implode( ' ', $classes_list );
?>
<div class="<?php echo $classes ?>">
	<div class="kitify-vtimeline__line"><div class="kitify-vtimeline__line-progress"></div></div>
	<?php $this->_get_global_looped_template( 'timeline', 'cards_list' ); ?>
</div>