<?php
/**
 * Progress Bar template
 */

$progress_type = $this->get_settings_for_display('progress_type');
$percent = $this->get_settings_for_display('percent');

$this->add_render_attribute( 'main-container', 'class', array(
	'kitify-progress-bar',
	'kitify-progress-bar-' . $progress_type,
) );

$this->add_render_attribute( 'main-container', 'data-percent', $percent );
$this->add_render_attribute( 'main-container', 'data-type', $progress_type );

?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
	<?php
    $this->get_type_template( $progress_type );
    ?>
</div>
