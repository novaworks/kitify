(function ($) {

    "use strict";

    var KitifyAnimatedText = function ($selector, settings) {
        var self = this,
            $instance = $selector,
            $animatedTextContainer = $('.kitify-animated-text__animated-text', $instance),
            $animatedTextList = $('.kitify-animated-text__animated-text-item', $animatedTextContainer),
            timeOut = null,
            defaultSettings = {
                effect: 'fx1',
                delay: 3000,
                start: 0
            },
            settings = $.extend(defaultSettings, settings || {}),
            currentIndex = 0,
            animationDelay = settings.delay,
            animationStart = settings.start;

        /**
         * Avaliable Effects
         */
        self.avaliableEffects = {
            'fx1': {
                in: {
                    duration: 1000,
                    delay: function (el, index) {
                        return 75 + index * 100;
                    },
                    easing: 'easeOutElastic',
                    elasticity: 650,
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    translateY: ['100%', '0%']
                },
                out: {
                    duration: 300,
                    delay: function (el, index) {
                        return index * 40;
                    },
                    easing: 'easeInOutExpo',
                    opacity: 0,
                    translateY: '-100%'
                }
            },
            'fx2': {
                in: {
                    duration: 800,
                    delay: function (el, index) {
                        return index * 50;
                    },
                    easing: 'easeOutElastic',
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    translateY: function (el, index) {
                        return index % 2 === 0 ? ['-80%', '0%'] : ['80%', '0%'];
                    }
                },
                out: {
                    duration: 300,
                    delay: function (el, index) {
                        return index * 20;
                    },
                    easing: 'easeOutExpo',
                    opacity: 0,
                    translateY: function (el, index) {
                        return index % 2 === 0 ? '80%' : '-80%';
                    }
                }
            },
            'fx3': {
                in: {
                    duration: 700,
                    delay: function (el, index) {
                        return (el.parentNode.children.length - index - 1) * 80;
                    },
                    easing: 'easeOutElastic',
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    translateY: function (el, index) {
                        return index % 2 === 0 ? ['-80%', '0%'] : ['80%', '0%'];
                    },
                    rotateZ: [90, 0]
                },
                out: {
                    duration: 300,
                    delay: function (el, index) {
                        return (el.parentNode.children.length - index - 1) * 50;
                    },
                    easing: 'easeOutExpo',
                    opacity: 0,
                    translateY: function (el, index) {
                        return index % 2 === 0 ? '80%' : '-80%';
                    },
                    rotateZ: function (el, index) {
                        return index % 2 === 0 ? -25 : 25;
                    }
                }
            },
            'fx4': {
                in: {
                    duration: 700,
                    delay: function (el, index) {
                        return 550 + index * 50;
                    },
                    easing: 'easeOutQuint',
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    translateY: ['-150%', '0%'],
                    rotateY: [180, 0]
                },
                out: {
                    duration: 200,
                    delay: function (el, index) {
                        return index * 30;
                    },
                    easing: 'easeInQuint',
                    opacity: {
                        value: 0,
                        easing: 'linear',
                    },
                    translateY: '100%',
                    rotateY: -180
                }
            },
            'fx5': {
                in: {
                    duration: 250,
                    delay: function (el, index) {
                        return 200 + index * 25;
                    },
                    easing: 'easeOutCubic',
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    translateY: ['-50%', '0%']
                },
                out: {
                    duration: 250,
                    delay: function (el, index) {
                        return index * 25;
                    },
                    easing: 'easeOutCubic',
                    opacity: 0,
                    translateY: '50%'
                }
            },
            'fx6': {
                in: {
                    duration: 400,
                    delay: function (el, index) {
                        return index * 50;
                    },
                    easing: 'easeOutSine',
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    rotateY: [-90, 0]
                },
                out: {
                    duration: 200,
                    delay: function (el, index) {
                        return index * 50;
                    },
                    easing: 'easeOutSine',
                    opacity: 0,
                    rotateY: 45
                }
            },
            'fx7': {
                in: {
                    duration: 1000,
                    delay: function (el, index) {
                        return 100 + index * 30;
                    },
                    easing: 'easeOutElastic',
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    rotateZ: function (el, index) {
                        return [anime.random(20, 40), 0];
                    }
                },
                out: {
                    duration: 300,
                    opacity: {
                        value: [1, 0],
                        easing: 'easeOutExpo',
                    }
                }
            },
            'fx8': {
                in: {
                    duration: 400,
                    delay: function (el, index) {
                        return 200 + index * 20;
                    },
                    easing: 'easeOutExpo',
                    opacity: 1,
                    rotateY: [-90, 0],
                    translateY: ['50%', '0%']
                },
                out: {
                    duration: 250,
                    delay: function (el, index) {
                        return index * 20;
                    },
                    easing: 'easeOutExpo',
                    opacity: 0,
                    rotateY: 90
                }
            },
            'fx9': {
                in: {
                    duration: 400,
                    delay: function (el, index) {
                        return 200 + index * 30;
                    },
                    easing: 'easeOutExpo',
                    opacity: 1,
                    rotateX: [90, 0]
                },
                out: {
                    duration: 250,
                    delay: function (el, index) {
                        return index * 30;
                    },
                    easing: 'easeOutExpo',
                    opacity: 0,
                    rotateX: -90
                }
            },
            'fx10': {
                in: {
                    duration: 400,
                    delay: function (el, index) {
                        return 100 + index * 50;
                    },
                    easing: 'easeOutExpo',
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    rotateX: [110, 0]
                },
                out: {
                    duration: 250,
                    delay: function (el, index) {
                        return index * 50;
                    },
                    easing: 'easeOutExpo',
                    opacity: 0,
                    rotateX: -110
                }
            },
            'fx11': {
                in: {
                    duration: function (el, index) {
                        return anime.random(800, 1000);
                    },
                    delay: function (el, index) {
                        return anime.random(100, 300);
                    },
                    easing: 'easeOutExpo',
                    opacity: {
                        value: [0, 1],
                        easing: 'easeOutExpo',
                    },
                    translateY: ['-150%', '0%'],
                    rotateZ: function (el, index) {
                        return [anime.random(-50, 50), 0];
                    }
                },
                out: {
                    duration: function (el, index) {
                        return anime.random(200, 300);
                    },
                    delay: function (el, index) {
                        return anime.random(0, 80);
                    },
                    easing: 'easeInQuart',
                    opacity: 0,
                    translateY: '50%',
                    rotateZ: function (el, index) {
                        return anime.random(-50, 50);
                    }
                }
            },
            'fx12': {
                in: {
                    elasticity: false,
                    duration: 1,
                    delay: function (el, index) {
                        var delay = index * 100 + anime.random(50, 100);

                        return delay;
                    },
                    width: [0, function (el, i) {
                        return $(el).width();
                    }]
                },
                out: {
                    duration: 1,
                    delay: function (el, index) {
                        return (el.parentNode.children.length - index - 1) * 20;
                    },
                    easing: 'linear',
                    width: {
                        value: 0
                    }
                }
            }
        };

        self.textChange = function () {
            var currentDelay = animationDelay,
                $prevText = $animatedTextList.eq(currentIndex),
                $nextText;

            if (currentIndex < $animatedTextList.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }

            $nextText = $animatedTextList.eq(currentIndex);

            self.hideText($prevText, settings.effect, null, function (anime) {
                $prevText.toggleClass('visible');

                var currentDelay = animationDelay;

                if (timeOut) {
                    clearTimeout(timeOut);
                }

                self.showText(
                    $nextText,
                    settings.effect,
                    function () {
                        $nextText.toggleClass('active');
                        $prevText.toggleClass('active');

                        $nextText.toggleClass('visible');
                    },
                    function () {
                        timeOut = setTimeout(function () {
                            self.textChange();
                        }, currentDelay);
                    }
                );

            });
        };

        self.showText = function ($selector, effect, beginCallback, completeCallback) {
            var targets = [];

            $('span.kitify-animated-span', $selector).each(function () {
                $(this).css({
                    'width': 'auto',
                    'opacity': 1,
                    'WebkitTransform': '',
                    'transform': ''
                });
                targets.push(this);
            });

            self.animateText(targets, 'in', effect, beginCallback, completeCallback);
        };

        self.hideText = function ($selector, effect, beginCallback, completeCallback) {
            var targets = [];

            $('span.kitify-animated-span', $selector).each(function () {
                targets.push(this);
            });

            self.animateText(targets, 'out', effect, beginCallback, completeCallback);
        };

        self.animateText = function (targets, direction, effect, beginCallback, completeCallback) {
            var effectSettings = self.avaliableEffects[effect] || {},
                animationOptions = effectSettings[direction],
                animeInstance = null;

            animationOptions.targets = targets;

            animationOptions.begin = beginCallback;
            animationOptions.complete = completeCallback;

            animeInstance = anime(animationOptions);
        };

        self.init = function () {
            var $text = $animatedTextList.eq(currentIndex);

            setTimeout(function () {
                self.showText(
                    $text,
                    settings.effect,
                    null,
                    function () {
                        var currentDelay = animationDelay;
                        if (timeOut) {
                            clearTimeout(timeOut);
                        }
                        timeOut = setTimeout(function () {
                            self.textChange();
                        }, currentDelay);
                    }
                );
            }, animationStart);
        };
    }

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/kitify-animated-text.default', function ($scope) {
            var $target = $scope.find('.kitify-animated-text'),
                instance = null,
                settings = {};

            if (!$target.length) {
                return;
            }

            settings = $target.data('settings');
            instance = new KitifyAnimatedText($target, settings);
            instance.init();
        });
    });

}(jQuery));