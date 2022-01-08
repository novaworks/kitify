(function ($) {

	"use strict";

	var KitifyCountDownTimer = function ($el){
		var $scope = $el;
		var timeInterval,
			$coutdown = $scope.find('[data-due-date]'),
			endTime = new Date($coutdown.data('due-date') * 1000),
			elements = {
				days: $coutdown.find('[data-value="days"]'),
				hours: $coutdown.find('[data-value="hours"]'),
				minutes: $coutdown.find('[data-value="minutes"]'),
				seconds: $coutdown.find('[data-value="seconds"]')
			};

		function splitNum( num ){
			var num = num.toString(),
				arr = [],
				result = '';

			if (1 === num.length) {
				num = 0 + num;
			}

			arr = num.match(/\d{1}/g);
			$.each(arr, function (index, val) {
				result += '<span class="kitify-countdown-timer__digit">' + val + '</span>';
			});
			return result;
		}

		function getTimeRemaining( endTime ){
			var timeRemaining = endTime - new Date(),
				seconds = Math.floor(timeRemaining / 1000 % 60),
				minutes = Math.floor(timeRemaining / 1000 / 60 % 60),
				hours = Math.floor(timeRemaining / (1000 * 60 * 60) % 24),
				days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));

			if (days < 0 || hours < 0 || minutes < 0) {
				seconds = minutes = hours = days = 0;
			}

			return {
				total: timeRemaining,
				parts: {
					days: splitNum(days),
					hours: splitNum(hours),
					minutes: splitNum(minutes),
					seconds: splitNum(seconds)
				}
			};
		}

		function updateClock(){
			var timeRemaining = getTimeRemaining(endTime);
			$.each(timeRemaining.parts, function (timePart) {
				var $element = elements[timePart];

				if ($element.length) {
					$element.html(this);
				}
			});

			if (timeRemaining.total <= 0) {
				clearInterval(timeInterval);
			}
		}

		function initClock(){
			updateClock();
			timeInterval = setInterval(updateClock, 1000);
		}

		initClock();
	}

	$(window).on('elementor/frontend/init', function () {
		window.elementorFrontend.hooks.addAction('frontend/element_ready/kitify-countdown-timer.default', function ($scope) {
			KitifyCountDownTimer( $scope );
		});
	});

}(jQuery));