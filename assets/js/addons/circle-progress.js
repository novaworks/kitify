(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/kitify-circle-progress.default', function ($scope) {
          var $progress = $scope.find( '.circle-progress' );

    			if ( ! $progress.length ) {
    				return;
    			}

    			var $value              = $progress.find( '.circle-progress__value' ),
    				$meter              = $progress.find( '.circle-progress__meter' ),
    				percent             = parseInt( $value.data( 'value' ) ),
    				progress            = percent / 100,
    				duration            = $scope.find( '.circle-progress-wrap' ).data( 'duration' ),
    				currentDeviceMode   = elementorFrontend.getCurrentDeviceMode(),
    				isAnimatedCircle    = false,
    				breakpoints         = KitifyTools.getElementorElementSettings( $scope ),
    				breakpointsSettings = [],
    				activeBreakpoints   = window.elementorFrontend.config.responsive.activeBreakpoints;

    			breakpointsSettings['desktop'] = [];

    			var breakpointSize        = breakpoints['circle_size']['size'] ? breakpoints['circle_size']['size'] : $progress[0].getAttribute( 'width' ),

    				breakpointStrokeValue = breakpoints['value_stroke']['size'] ? breakpoints['value_stroke']['size'] : $progress[0].getElementsByClassName( 'circle-progress__value' )[0].getAttribute( 'stroke-width' ),

    				breakpointStrokeBg    = breakpoints['bg_stroke']['size'] ? breakpoints['bg_stroke']['size'] : $progress[0].getElementsByClassName( 'circle-progress__meter' )[0].getAttribute( 'stroke-width' );

    			breakpointSizes( 'desktop', breakpointSize, breakpointStrokeValue, breakpointStrokeBg );

    			Object.keys( activeBreakpoints ).reverse().forEach( function( breakpointName, index ) {

    				if ( 'widescreen' === breakpointName ){
    					var breakpointSize        = breakpoints['circle_size_' + breakpointName]['size'] ? breakpoints['circle_size_' + breakpointName]['size'] : breakpoints['circle_size']['size'],

    						breakpointStrokeValue = breakpoints['value_stroke_' + breakpointName]['size'] ? breakpoints['value_stroke_' + breakpointName]['size'] : breakpoints['value_stroke']['size'],

    						breakpointStrokeBg    = breakpoints['bg_stroke_' + breakpointName]['size'] ? breakpoints['bg_stroke_' + breakpointName]['size'] : breakpoints['bg_stroke']['size'];

    					breakpointsSettings[breakpointName] = [];

    					breakpointSizes( breakpointName, breakpointSize, breakpointStrokeValue, breakpointStrokeBg );
    				} else {
    					var breakpointSize        = breakpoints['circle_size_' + breakpointName]['size'] ? breakpoints['circle_size_' + breakpointName]['size'] : $progress[0].getAttribute( 'width' ),

    						breakpointStrokeValue = breakpoints['value_stroke_' + breakpointName]['size'] ? breakpoints['value_stroke_' + breakpointName]['size'] : $progress[0].getElementsByClassName( 'circle-progress__value' )[0].getAttribute( 'stroke-width' ),

    						breakpointStrokeBg    = breakpoints['bg_stroke_' + breakpointName]['size'] ? breakpoints['bg_stroke_' + breakpointName]['size'] : $progress[0].getElementsByClassName( 'circle-progress__meter' )[0].getAttribute( 'stroke-width' );

    					breakpointsSettings[breakpointName] = [];

    					breakpointSizes( breakpointName, breakpointSize, breakpointStrokeValue, breakpointStrokeBg );
    				}
    			} );

    			updateSvgSizes( breakpointsSettings[currentDeviceMode]['size'],
    								breakpointsSettings[currentDeviceMode]['viewBox'],
    								breakpointsSettings[currentDeviceMode]['center'],
    								breakpointsSettings[currentDeviceMode]['radius'],
    								breakpointsSettings[currentDeviceMode]['valStroke'],
    								breakpointsSettings[currentDeviceMode]['bgStroke'],
    								breakpointsSettings[currentDeviceMode]['circumference']
    			);

    			elementorFrontend.waypoint( $scope, function() {

    				// animate counter
    				var $number = $scope.find( '.circle-counter__number' ),
    					data = $number.data();

    				var decimalDigits = data.toValue.toString().match( /\.(.*)/ );

    				if ( decimalDigits ) {
    					data.rounding = decimalDigits[1].length;
    				}

    				data.duration = duration;

    				$number.numerator( data );

    				// animate progress
    				var circumference = parseInt( $progress.data( 'circumference' ) ),
    					dashoffset    = circumference * (1 - progress);

    				$value.css({
    					'transitionDuration': duration + 'ms',
    					'strokeDashoffset': dashoffset
    				});

    				isAnimatedCircle = true;

    			} );

    			$( window ).on( 'resize.kitifyCircleProgress orientationchange.kitifyCircleProgress', KitifyTools.debounce( 50, function() {
    				currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

    				if ( breakpointsSettings[currentDeviceMode] ) {
    					updateSvgSizes( breakpointsSettings[currentDeviceMode]['size'],
    										breakpointsSettings[currentDeviceMode]['viewBox'],
    										breakpointsSettings[currentDeviceMode]['center'],
    										breakpointsSettings[currentDeviceMode]['radius'],
    										breakpointsSettings[currentDeviceMode]['valStroke'],
    										breakpointsSettings[currentDeviceMode]['bgStroke'],
    										breakpointsSettings[currentDeviceMode]['circumference']
    					);
    				}
    			} ) );

    			function breakpointSizes( breakpointName, breakpointSize, breakpointStrokeValue, breakpointStrokeBg) {
    				var max,
    					radius;

    				breakpointsSettings[breakpointName]['size']          = breakpointSize;
    				breakpointsSettings[breakpointName]['viewBox']       = `0 0 ${breakpointSize} ${breakpointSize}`;
    				breakpointsSettings[breakpointName]['center']        = breakpointSize / 2;
    				radius                                               = breakpointSize / 2;
    				max                                                  = ( breakpointStrokeValue >= breakpointStrokeBg ) ? breakpointStrokeValue : breakpointStrokeBg;
    				breakpointsSettings[breakpointName]['radius']        = radius - ( max / 2 );
    				breakpointsSettings[breakpointName]['circumference'] = 2 * Math.PI * breakpointsSettings[breakpointName]['radius'];
    				breakpointsSettings[breakpointName]['valStroke']     = breakpointStrokeValue;
    				breakpointsSettings[breakpointName]['bgStroke']      = breakpointStrokeBg;
    			}

    			function updateSvgSizes( size, viewBox, center, radius, valStroke, bgStroke, circumference ) {
    				var dashoffset = circumference * (1 - progress);

    				$progress.attr( {
    					'width': size,
    					'height': size,
    					'data-radius': radius,
    					'data-circumference': circumference
    				} );

    				$progress[0].setAttribute( 'viewBox', viewBox );

    				$meter.attr( {
    					'cx': center,
    					'cy': center,
    					'r': radius,
    					'stroke-width': bgStroke
    				} );

    				if ( isAnimatedCircle ) {
    					$value.css( {
    						'transitionDuration': ''
    					} );
    				}

    				$value.attr( {
    					'cx': center,
    					'cy': center,
    					'r': radius,
    					'stroke-width': valStroke
    				} );

    				$value.css( {
    					'strokeDasharray': circumference,
    					'strokeDashoffset': isAnimatedCircle ? dashoffset : circumference
    				} );
    			}
        });
    });


}(jQuery));
