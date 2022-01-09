(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/kitify-video-modal.default', function ($scope) {
          $('.js-video-modal').fancybox({
            youtube : {
              controls : 0,
              showinfo : 0
          },
          vimeo : {
              color : 'f00'
          }
        	});
        });
    });

}(jQuery));
