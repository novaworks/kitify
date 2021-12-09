(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/kitify-google-maps.default', function ($scope) {
            var $container = $scope.find('.kitify-gmap'),
                map,
                init,
                pins;

            if (!window.google || !$container.length) {
                return;
            }

            if( $container.hasClass('kitify-gmap-message') ){
                return;
            }

            init = $container.data('init');
            pins = $container.data('pins');
            map = new google.maps.Map($container[0], init);

            if (pins) {
                $.each(pins, function (index, pin) {

                    var marker,
                        infowindow,
                        pinData = {
                            position: pin.position,
                            map: map
                        };

                    if ('' !== pin.image) {
                        pinData.icon = pin.image;
                    }

                    marker = new google.maps.Marker(pinData);

                    if ('' !== pin.desc) {
                        infowindow = new google.maps.InfoWindow({
                            content: pin.desc,
                            disableAutoPan: true
                        });
                    }

                    marker.addListener('click', function () {
                        infowindow.setOptions({disableAutoPan: false});
                        infowindow.open(map, marker);
                    });

                    google.maps.event.addListener(infowindow, 'domready', function () {
                        var iwOuter = $('.gm-style-iw');
                        iwOuter.prev().addClass('gm-style-iw-prev').parent().parent().parent().parent().addClass('gm-parent-iw');
                    });

                    if ('visible' === pin.state && '' !== pin.desc) {
                        infowindow.open(map, marker);
                    }

                });
            }

            map.addListener('tilt_changed', function () {
                if ($container.find('.gm-style-pbc').length) {
                    $container.find('.gm-style-pbc').next().addClass('gm-parent-iw');
                }
            });
        });
    });

}(jQuery));