<?php
/**
 * SVG circle template
 */

$settings   = $this->get_settings_for_display();
$size       = is_array( $settings['circle_size'] ) ? $settings['circle_size']['size'] : $settings['circle_size'];
$radius     = $size / 2;
$center     = $radius;
$viewbox    = sprintf( '0 0 %1$s %1$s', $size );
$val_stroke = is_array( $settings['value_stroke'] ) ? $settings['value_stroke']['size'] : $settings['value_stroke'];
$bg_stroke  = is_array( $settings['bg_stroke'] ) ? $settings['bg_stroke']['size'] : $settings['bg_stroke'];

// Fix radius relative to stroke
$max    = ( $val_stroke >= $bg_stroke ) ? $val_stroke : $bg_stroke;
$radius = $radius - ( $max / 2 );

$value = 0;

if ( 'percent' === $settings['values_type'] ) {
	$value = 100 <= $settings['percent_value']['size'] ? 100 : $settings['percent_value']['size'];
} elseif ( 0 !== absint( $settings['absolute_value_max'] ) ) {
	$curr  = $settings['absolute_value_curr'];
	$max   = $settings['absolute_value_max'];
	$value = round( ( ( absint( $curr ) * 100 ) / absint( $max ) ), 0 );
}

$circumference = 2 * M_PI * $radius;

$meter_stroke = ( 'color' === $settings['bg_stroke_type'] ) ? $settings['val_bg_color'] : 'url(#circle-progress-meter-gradient-' . $this->get_id() . ')';
$value_stroke = ( 'color' === $settings['val_stroke_type'] ) ? $settings['val_stroke_color'] : 'url(#circle-progress-value-gradient-' . $this->get_id() . ')';

$val_bg_gradient_angle     = ! empty( $settings['val_bg_gradient_angle'] ) ? $settings['val_bg_gradient_angle'] : 0;
$val_stroke_gradient_angle = ! empty( $settings['val_stroke_gradient_angle'] ) ? $settings['val_stroke_gradient_angle'] : 0;

?>
<svg class="circle-progress" width="<?php echo $size; ?>" height="<?php echo $size; ?>" viewBox="<?php echo $viewbox; ?>" data-radius="<?php echo $radius; ?>" data-circumference="<?php echo $circumference; ?>">
	<linearGradient id="circle-progress-meter-gradient-<?php echo $this->get_id(); ?>" gradientUnits="objectBoundingBox" gradientTransform="rotate(<?php echo $val_bg_gradient_angle; ?> 0.5 0.5)" x1="-0.25" y1="0.5" x2="1.25" y2="0.5">
		<stop class="circle-progress-meter-gradient-a" offset="0%" stop-color="<?php echo $settings['val_bg_gradient_color_a']; ?>"/>
		<stop class="circle-progress-meter-gradient-b" offset="100%" stop-color="<?php echo $settings['val_bg_gradient_color_b']; ?>"/>
	</linearGradient>
	<linearGradient id="circle-progress-value-gradient-<?php echo $this->get_id(); ?>" gradientUnits="objectBoundingBox" gradientTransform="rotate(<?php echo $val_stroke_gradient_angle; ?> 0.5 0.5)" x1="-0.25" y1="0.5" x2="1.25" y2="0.5">
		<stop class="circle-progress-value-gradient-a" offset="0%" stop-color="<?php echo $settings['val_stroke_gradient_color_a']; ?>"/>
		<stop class="circle-progress-value-gradient-b" offset="100%" stop-color="<?php echo $settings['val_stroke_gradient_color_b']; ?>"/>
	</linearGradient>
	<circle
		class="circle-progress__meter"
		cx="<?php echo $center; ?>"
		cy="<?php echo $center; ?>"
		r="<?php echo $radius; ?>"
		stroke="<?php echo $meter_stroke; ?>"
		stroke-width="<?php echo $bg_stroke; ?>"
		fill="none"
	/>
	<circle
		class="circle-progress__value"
		cx="<?php echo $center; ?>"
		cy="<?php echo $center; ?>"
		r="<?php echo $radius; ?>"
		stroke="<?php echo $value_stroke; ?>"
		stroke-width="<?php echo $val_stroke; ?>"
		data-value="<?php echo $value; ?>"
		style="stroke-dasharray: <?php echo $circumference; ?>; stroke-dashoffset: <?php echo $circumference; ?>;"
		fill="none"
	/>
</svg>
