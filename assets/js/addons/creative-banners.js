(function ($) {

	"use strict";

	var KitifyCreativeBanners = function ($el){
		var $scope = $el;

		function onHover() {
				var images = $scope.find('.kitify-creative-banners__images'),
						links = $scope.find('.kitify-creative-banners__links');

				$scope.on("mouseover", "li", function() {
						var item_id = $(this).data("item-id"),
								image = images.find('.kitify-creative-banners__image[data-item-id="' + item_id + '"]');

								images.find(".kitify-creative-banners__image").removeClass("active");
								image.addClass("active");
				})
		};
		onHover();
	}

	$(window).on('elementor/frontend/init', function () {
		window.elementorFrontend.hooks.addAction('frontend/element_ready/kitify-creative-banners.default', function ($scope) {
			KitifyCreativeBanners( $scope );
		});
	});

}(jQuery));
