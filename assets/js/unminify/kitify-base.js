(function ($, elementor) {
    "use strict";
    var getElementSettings = function( $element ) {
      var elementSettings = {},
        modelCID 		= $element.data( 'model-cid' );

      if ( isEditMode && modelCID ) {
        var settings     = elementorFrontend.config.elements.data[ modelCID ],
          settingsKeys = elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

        jQuery.each( settings.getActiveControls(), function( controlKey ) {
          if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
            elementSettings[ controlKey ] = settings.attributes[ controlKey ];
          }
        } );
      } else {
        elementSettings = $element.data('settings') || {};
      }

      return elementSettings;
    };
    var isEditMode = false;

    function getHeaderHeight(){
        var _height = 0;
        var $stickySection = $('.elementor-location-header .elementor-top-section[data-settings*="sticky_on"]');
        if($stickySection.length){
            _height = $stickySection.innerHeight();
        }
        return _height;
    }

    function checkHeaderHeight(){
        document.documentElement.style.setProperty('--kitify-header-height', getHeaderHeight() + 'px');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.body.classList.add('kitify--js-ready');
        checkHeaderHeight();
        if(!Kitify.isPageSpeed()){
        Kitify.localCache.validCache(false);
        Kitify.ajaxTemplateHelper.init();
        $('.col-row').each(function (){
            if($(this).closest('[data-kitify_ajax_loadtemplate]').length == 0){
                $(this).trigger('kitify/LazyloadSequenceEffects');
            }
        })
      }
    });
    $(window).on('load', function (){
        $('.template-loaded[data-kitify_ajax_loadtemplate="true"] .col-row').trigger('kitify/LazyloadSequenceEffects');
    });

    $(document).on('kitify/LazyloadSequenceEffects', '.col-row, .swiper-container', function (e){
        var $this = $(this);
        if( $this.hasClass('swiper-container') ){
            Kitify.LazyLoad( $this, {rootMargin: '0px'} ).observe();
        }
        else{
            Kitify.LazyLoad( $('>*', $this), {rootMargin: '0px'} ).observe();
        }
    });
    $(window).on('load resize', checkHeaderHeight);

    $(document).on('kitify/woocommerce/single/init_product_slider', function (e, slider) {
        slider.controlNav.eq(slider.animatingTo).closest('li').get(0).scrollIntoView({
            inline: "center",
            block: "nearest",
            behavior: "smooth"
        });
        slider.viewport.closest('.woocommerce-product-gallery').css('--singleproduct-thumbs-height', slider.viewport.height() + 'px');
    });

    var Kitify = {
        log: function (...data){
            if(window.KitifySettings.devMode === 'true'){
                console.log(...data);
            }
        },
        addedScripts: {},
        addedStyles: {},
        addedAssetsPromises: [],
        carouselAsFor: [],
        localCache: {
            cache_key: typeof KitifySettings.themeName !== "undefined" ? KitifySettings.themeName : 'kitify',
            /**
             * timeout for cache in seconds, default 5 mins
             * @type {number}
             */
            timeout: typeof KitifySettings.cache_ttl !== "undefined" && parseInt(KitifySettings.cache_ttl) > 0 ? parseInt(KitifySettings.cache_ttl) : (60 * 5),
            timeout2: 60 * 10,
            /**
             * @type {{_: number, data: {}}}
             **/
            data:{},
            remove: function (url) {
                delete Kitify.localCache.data[url];
            },
            exist: function (url) {
                return !!Kitify.localCache.data[url] && ((Date.now() - Kitify.localCache.data[url]._) / 1000 < Kitify.localCache.timeout2);
            },
            get: function (url) {
                //Kitify.log('Get cache for ' + url);
                return Kitify.localCache.data[url].data;
            },
            set: function (url, cachedData, callback) {
                Kitify.localCache.remove(url);
                Kitify.localCache.data[url] = {
                    _: Date.now(),
                    data: cachedData
                };
                if ("function" == typeof callback && "number" != typeof callback.nodeType) {
                    callback(cachedData)
                }
            },
            hashCode: function (s){
                var hash = 0;
                s = s.toString();
                if (s.length == 0) return hash;

                for (var i = 0; i < s.length; i++) {
                    var char = s.charCodeAt(i);
                    hash = (hash << 5) - hash + char;
                    hash = hash & hash; // Convert to 32bit integer
                }

                return Math.abs(hash);
            },
            validCache: function ( force ){
                var expiry = typeof KitifySettings.local_ttl !== "undefined" && parseInt(KitifySettings.local_ttl) > 0 ? parseInt(KitifySettings.local_ttl) : 60 * 30; // 30 mins
                var cacheKey = Kitify.localCache.cache_key + '_cache_timeout' + Kitify.localCache.hashCode(KitifySettings.homeURL);
                try{
                    var whenCached = localStorage.getItem(cacheKey);
                    if (whenCached !== null || force) {
                        var age = (Date.now() - whenCached) / 1000;
                        if (age > expiry || force) {
                            Object.keys(localStorage).forEach(function (key) {
                                if (key.indexOf(Kitify.localCache.cache_key) === 0) {
                                    localStorage.removeItem(key);
                                }
                            });
                            localStorage.setItem(cacheKey, Date.now());
                        }
                    } else {
                        localStorage.setItem(cacheKey, Date.now());
                    }
                }
                catch (ex) {
                    Kitify.log(ex);
                }
            }
        },
        isPageSpeed: function () {
            return (typeof navigator !== "undefined" && (/(lighthouse|gtmetrix)/i.test(navigator.userAgent.toLocaleLowerCase()) || /mozilla\/5\.0 \(x11; linux x86_64\)/i.test(navigator.userAgent.toLocaleLowerCase())));
        },
        addQueryArg: function (url, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = url.indexOf('?') !== -1 ? "&" : "?";

            if (url.match(re)) {
                return url.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return url + separator + key + "=" + value;
            }
        },
        getUrlParameter: function (name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        },
        parseQueryString: function (query) {
            var urlparts = query.split("?");
            var query_string = {};

            if (urlparts.length >= 2) {
                var vars = urlparts[1].split("&");

                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    var key = decodeURIComponent(pair[0]);
                    var value = decodeURIComponent(pair[1]); // If first entry with this name

                    if (typeof query_string[key] === "undefined") {
                        query_string[key] = decodeURIComponent(value); // If second entry with this name
                    } else if (typeof query_string[key] === "string") {
                        var arr = [query_string[key], decodeURIComponent(value)];
                        query_string[key] = arr; // If third or later entry with this name
                    } else {
                        query_string[key].push(decodeURIComponent(value));
                    }
                }
            }

            return query_string;
        },
        removeURLParameter: function (url, parameter) {
            var urlparts = url.split('?');

            if (urlparts.length >= 2) {
                var prefix = encodeURIComponent(parameter) + '=';
                var pars = urlparts[1].split(/[&;]/g); //reverse iteration as may be destructive

                for (var i = pars.length; i-- > 0;) {
                    //idiom for string.startsWith
                    if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                        pars.splice(i, 1);
                    }
                }

                url = urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
                return url;
            } else {
                return url;
            }
        },
        initCarousel: function ($scope) {

            var $carousel = $scope.find('.kitify-carousel').first();

            if ($carousel.length == 0) {
                return;
            }

            if ($carousel.hasClass('inited')) {
                return;
            }

            $carousel.addClass('inited');

            var elementSettings = $carousel.data('slider_options'),
                slidesToShow = parseInt(elementSettings.slidesToShow.desktop) || 1,
                elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints,
                carousel_id = elementSettings.uniqueID;

            var swiperOptions = {
                slidesPerView: slidesToShow,
                loop: elementSettings.infinite,
                speed: elementSettings.speed,
                handleElementorBreakpoints: true,
                slidesPerColumn: elementSettings.rows.desktop,
                slidesPerGroup: elementSettings.slidesToScroll.desktop || 1
            }

            swiperOptions.breakpoints = {};

            var lastBreakpointSlidesToShowValue = 1;
            var defaultLGDevicesSlidesCount = 1;
            Object.keys(elementorBreakpoints).reverse().forEach(function (breakpointName) {
                // Tablet has a specific default `slides_to_show`.
                var defaultSlidesToShow = 'tablet' === breakpointName ? defaultLGDevicesSlidesCount : lastBreakpointSlidesToShowValue;
                swiperOptions.breakpoints[elementorBreakpoints[breakpointName].value] = {
                    slidesPerView: +elementSettings.slidesToShow[breakpointName] || defaultSlidesToShow,
                    slidesPerGroup: +elementSettings.slidesToScroll[breakpointName] || 1,
                    slidesPerColumn: +elementSettings.rows[breakpointName] || 1,
                };
                lastBreakpointSlidesToShowValue = +elementSettings.slidesToShow[breakpointName] || defaultSlidesToShow;
            });

            if (elementSettings.autoplay) {
                swiperOptions.autoplay = {
                    delay: (elementSettings.effect == 'slide' && elementSettings.infiniteEffect ? 10 : elementSettings.autoplaySpeed),
                    disableOnInteraction: elementSettings.pauseOnInteraction,
                    pauseOnMouseEnter: elementSettings.pauseOnHover,
                    reverseDirection: elementSettings.reverseDirection || false,
                };
                if(elementSettings.effect == 'slide' && elementSettings.infiniteEffect){
                    $carousel.addClass('kitify--linear-effect');
                }
            }
            if (elementSettings.centerMode) {
                swiperOptions.centerInsufficientSlides = true;
                swiperOptions.centeredSlides = true;
                swiperOptions.centeredSlidesBounds = false;
            }

            switch (elementSettings.effect) {
                case 'fade':
                    if (slidesToShow == 1) {
                        swiperOptions.effect = elementSettings.effect;
                        swiperOptions.fadeEffect = {
                            crossFade: true
                        };
                    }
                    break;

                case 'coverflow':
                    swiperOptions.effect = 'coverflow';
                    swiperOptions.grabCursor = true;
                    swiperOptions.centeredSlides = true;
                    swiperOptions.slidesPerView = 2;
                    swiperOptions.coverflowEffect = {
                        rotate: 50,
                        stretch: 0,
                        depth: 100,
                        modifier: 1,
                        slideShadows: true
                    };
                    swiperOptions.coverflowEffect = $.extend( {}, {
                        rotate: 0,
                        stretch: 100,
                        depth: 100,
                        modifier: 2.6,
                        slideShadows : true
                    }, elementSettings.coverflowEffect )
                    break;

                case 'cube':
                    swiperOptions.effect = 'cube';
                    swiperOptions.grabCursor = true;
                    swiperOptions.cubeEffect = {
                        shadow: true,
                        slideShadows: true,
                        shadowOffset: 20,
                        shadowScale: 0.94,
                    }
                    swiperOptions.slidesPerView = 1;
                    swiperOptions.slidesPerGroup = 1;
                    break;

                case 'flip':
                    swiperOptions.effect = 'flip';
                    swiperOptions.grabCursor = true;
                    swiperOptions.slidesPerView = 1;
                    swiperOptions.slidesPerGroup = 1;
                    break;

                case 'slide':
                    swiperOptions.effect = 'slide';
                    swiperOptions.grabCursor = true;
                    break;
            }

            if (elementSettings.arrows) {
                swiperOptions.navigation = {
                    prevEl: elementSettings.prevArrow,
                    nextEl: elementSettings.nextArrow
                };
            }
            if (elementSettings.dots) {
                swiperOptions.pagination = {
                    el: elementSettings.dotsElm || '.kitify-carousel__dots',
                    type: swiperOptions.dotType || 'bullets',
                    clickable: true
                };
                if (elementSettings.dotType == 'bullets') {
                    swiperOptions.pagination.dynamicBullets = true;
                }
                if (elementSettings.dotType == 'custom') {
                    swiperOptions.pagination.renderBullet = function (index, className) {
                        return '<span class="' + className + '">' + (index + 1) + "</span>";
                    }
                }
            }

            var enableScrollbar = elementSettings.scrollbar || false;

            if (!enableScrollbar) {
                swiperOptions.scrollbar = false;
            } else {
                swiperOptions.scrollbar = {
                    el: '.kitify-carousel__scrollbar',
                    draggable: true
                }
            }

            var _has_slidechange_effect = false,
                _slide_change_effect_in = elementSettings.content_effect_in || 'fadeInUp',
                _slide_change_effect_out = elementSettings.content_effect_out || 'fadeOutDown';

            if (elementSettings.content_selector !== undefined && $carousel.find(elementSettings.content_selector).length > 0) {
                _has_slidechange_effect = true;
            }

            if ($carousel.closest('.no-slide-animation').length || $carousel.closest('.slide-no-animation').length) {
                _has_slidechange_effect = false;
            }

            if (elementSettings.direction) {
                swiperOptions.direction = elementSettings.direction;
            }
            if (elementSettings.autoHeight) {
                swiperOptions.autoHeight = elementSettings.autoHeight
            }
            swiperOptions.watchSlidesProgress = true;
            swiperOptions.watchSlidesVisibility = true;


            function findAsForObj(value, key) {
                var _found = [];
                for (var i = 0; i < Kitify.carouselAsFor.length; i++) {
                    if (Kitify.carouselAsFor[i][key] == value) {
                        Kitify.carouselAsFor[i]['index'] = i;
                        _found.push(Kitify.carouselAsFor[i]);
                        break;
                    }
                }
                return _found;
            }

            function makeLoadedAsFor(value, key) {
                var obj = findAsForObj(value, key);
                if (obj.length) {
                    obj[0][key + '_init'] = true;
                }
                return obj;
            }

            if (typeof elementSettings.asFor !== "undefined" && elementSettings.asFor != '' && elementSettings.asFor != '#' && $('#' + elementSettings.asFor).length) {
                var _thumb_swiper = $('#' + elementSettings.asFor).data('swiper');

                if (null === _thumb_swiper || "undefined" === _thumb_swiper) {
                    swiperOptions.thumbs = {
                        swiper: _thumb_swiper,
                    }
                } else {
                    Kitify.carouselAsFor.push({
                        main: carousel_id,
                        thumb: elementSettings.asFor,
                        main_init: false,
                        thumb_init: false
                    });
                }
            }

            swiperOptions.slideToClickedSlide = true;

            var $swiperContainer = $scope.find('.swiper-container');

            var Swiper = elementorFrontend.utils.swiper;

            function initSlideContentAnimation( needwaiting ){
                var $sliderContents = $carousel.find('.swiper-slide-active .kitify-template-wrapper .elementor-invisible[data-settings*="_animation"]');

                $sliderContents.each(function () {
                    var _settings = $(this).data('settings'),
                        animation = elementorFrontend.getCurrentDeviceSetting(_settings, '_animation'),
                        animationDelay = _settings._animation_delay || 0,
                        $element = $(this);
                    if ('none' === animation) {
                        $element.removeClass('elementor-invisible');
                    } else {
                        setTimeout(function () {
                            $element.removeClass('elementor-invisible').addClass('animated ' + animation);
                        }, animationDelay);
                    }
                });

                if (_has_slidechange_effect) {
                    $carousel.find('.swiper-slide:not(.swiper-slide-visible) ' + elementSettings.content_selector).addClass('no-effect-class').removeClass(_slide_change_effect_in).addClass(_slide_change_effect_out);
                    $carousel.find('.swiper-slide-visible ' + elementSettings.content_selector).removeClass('no-effect-class').removeClass(_slide_change_effect_out).addClass(_slide_change_effect_in);
                }

                if(needwaiting){
                    setTimeout(function (){
                        var $InActiveSliderContents = $carousel.find('.swiper-slide:not(.swiper-slide-visible) .kitify-template-wrapper [data-settings*="_animation"]');
                        $InActiveSliderContents.each(function () {
                            var _settings = $(this).data('settings'),
                                animation = elementorFrontend.getCurrentDeviceSetting(_settings, '_animation');
                            if ('none' === animation) {
                                $(this).removeClass('animated').addClass('elementor-invisible');
                            }
                            else {
                                $(this).removeClass('animated ' + animation).addClass('elementor-invisible');
                            }
                        });
                    }, 1000);
                }
                else{
                    var $InActiveSliderContents = $carousel.find('.swiper-slide:not(.swiper-slide-visible) .kitify-template-wrapper [data-settings*="_animation"]');
                    $InActiveSliderContents.each(function () {
                        var _settings = $(this).data('settings'),
                            animation = elementorFrontend.getCurrentDeviceSetting(_settings, '_animation');
                        if ('none' === animation) {
                            $(this).removeClass('animated').addClass('elementor-invisible');
                        }
                        else {
                            $(this).removeClass('animated ' + animation).addClass('elementor-invisible');
                        }
                    });
                }
            }

            new Swiper($swiperContainer, swiperOptions).then(function (SwiperInstance) {

                if(elementSettings.autoplay && typeof SwiperInstance.autoplay !== "undefined" && typeof SwiperInstance.autoplay.onMouseEnter === "undefined"){
                    $swiperContainer.on('mouseenter', function (){
                        SwiperInstance.autoplay.stop();
                    }).on('mouseleave', function (){
                        SwiperInstance.autoplay.start();
                    });
                }

                $swiperContainer.data('swiper', SwiperInstance);

                $swiperContainer.find('.elementor-top-section').trigger('kitify/section/calculate-container-width');

                initSlideContentAnimation(true);

                var ob1 = makeLoadedAsFor(carousel_id, 'thumb');
                var ob2 = makeLoadedAsFor(carousel_id, 'main');

                if (ob1.length && ob1[0].main_init && ob1[0].thumb_init) {
                    var _main_swiper = $('#' + ob1[0].main).data('swiper');
                    _main_swiper.thumbs.swiper = $('#' + ob1[0].thumb).data('swiper');
                    _main_swiper.thumbs.init();
                }
                if (ob2.length && ob2[0].main_init && ob2[0].thumb_init) {
                    var _main_swiper = $('#' + ob2[0].main).data('swiper');
                    _main_swiper.thumbs.swiper = $('#' + ob2[0].thumb).data('swiper');
                    _main_swiper.thumbs.init();
                }

                if (_has_slidechange_effect) {
                    $carousel.find(elementSettings.content_selector).addClass('animated no-effect-class');
                    $carousel.find('.swiper-slide-visible ' + elementSettings.content_selector).removeClass('no-effect-class').addClass(_slide_change_effect_in);
                }

                SwiperInstance.on('slideChange', function () {

                    if ($swiperContainer.hasClass(this.params.thumbs.thumbsContainerClass)) {
                        this.clickedIndex = this.activeIndex;
                        this.clickedSlide = this.slides[this.clickedIndex];
                        this.emit('tap');
                    }
                });

                SwiperInstance.on('slideChangeTransitionEnd', function (){
                    initSlideContentAnimation(false);
                });

                $(document).trigger('kitify/carousel/init_success', { swiperContainer: $swiperContainer });
            });

        },
        initMasonry: function ($scope) {
            var $container = $scope.find('.kitify-masonry-wrapper').first();

            if ($container.length == 0) {
                return;
            }

            var $list_wrap = $scope.find($container.data('kitifymasonry_wrap')),
                itemSelector = $container.data('kitifymasonry_itemselector'),
                $advanceSettings = $container.data('kitifymasonry_layouts') || false,
                $itemsList = $scope.find(itemSelector),
                $masonryInstance,
                _configs;

            if ($list_wrap.length) {

                if ($advanceSettings !== false) {
                    $(document).trigger('kitify/masonry/calculate-item-sizes', [$container, false]);
                    $(window).on('resize', function () {
                        $(document).trigger('kitify/masonry/calculate-item-sizes', [$container, true]);
                    });
                    _configs = {
                        itemSelector: itemSelector,
                        percentPosition: true,
                        masonry: {
                            columnWidth: 1,
                            gutter: 0,
                        },
                    }
                } else {
                    _configs = {
                        itemSelector: itemSelector,
                        percentPosition: true,
                    }
                }

                $masonryInstance = $list_wrap.isotope(_configs);

                $('img', $itemsList).imagesLoaded().progress(function (instance, image) {
                    var $image = $(image.img),
                        $parentItem = $image.closest(itemSelector);
                    $parentItem.addClass('item-loaded');
                    if ($masonryInstance) {
                        $masonryInstance.isotope('layout');
                    }
                });
            }
        },
        initCustomHandlers: function () {
            $(document)
                .on('click', '.kitify .kitify-pagination_ajax_loadmore a', function (e){
                    e.preventDefault();
                    if ($('body').hasClass('elementor-editor-active')) {
                        return false;
                    }
                    var $kitWrap,$parentContainer, $container, ajaxType, $parentNav, widgetId, itemSelector;
                    $parentNav = $(this).closest('.kitify-pagination');
                    $kitWrap = $(this).closest('.kitify');
                    widgetId = $kitWrap.data('id');

                    if ($parentNav.hasClass('doing-ajax')) {
                        return false;
                    }

                    ajaxType = 'load_widget';
                    if($kitWrap.find('div[data-widget_current_query="yes"]').length > 0){
                        ajaxType = 'load_fullpage';
                    }

                    if($kitWrap.hasClass('elementor-kitify-wooproducts')){
                        $container = $kitWrap.find('.kitify-products__list');
                        $parentContainer = $kitWrap.find('.kitify-products');
                        itemSelector = '.kitify-product.product_item';
                    }
                    else{
                        $container = $($parentNav.data('container'));
                        $parentContainer = $($parentNav.data('parent-container'));
                        itemSelector = $parentNav.data('item-selector');
                    }

                    if ($('a.next', $parentNav).length) {
                        $parentNav.addClass('doing-ajax');
                        $parentContainer.addClass('doing-ajax');

                        var success_func = function (response) {
                            var $data = $(response).find('.elementor-element-' + widgetId + ' ' + itemSelector);

                            if ($parentContainer.find('.kitify-carousel').length > 0) {
                                var swiper = $parentContainer.find('.kitify-carousel').get(0).swiper;
                                swiper.appendSlide($data);
                            }
                            else if ($container.data('isotope')) {
                                $container.append($data);
                                $container.isotope('insert', $data);
                                $(document).trigger('kitify/masonry/calculate-item-sizes', [$parentContainer, true]);

                                $('img', $data).imagesLoaded().progress(function (instance, image) {
                                    var $image = $(image.img),
                                        $parentItem = $image.closest(itemSelector);
                                    $parentItem.addClass('item-loaded');
                                    $container.isotope('layout');
                                });
                            }
                            else {
                                $data.addClass('fadeIn animated').appendTo($container);
                            }

                            $parentContainer.removeClass('doing-ajax');
                            $parentNav.removeClass('doing-ajax kitify-ajax-load-first');

                            if ($(response).find( '.elementor-element-' + widgetId + ' .kitify-ajax-pagination').length) {
                                var $new_pagination = $(response).find( '.elementor-element-' + widgetId + ' .kitify-ajax-pagination');
                                $parentNav.replaceWith($new_pagination);
                                $parentNav = $new_pagination;
                            } else {
                                $parentNav.addClass('nothingtoshow');
                            }
                            $('body').trigger('jetpack-lazy-images-load');
                            $(document).trigger('kitify/ajax-loadmore/success', {
                                parentContainer: $parentContainer,
                                contentHolder: $container,
                                pagination: $parentNav
                            });
                        };

                        var url_request = $('a.next', $parentNav).get(0).href.replace(/^\//, '');
                        url_request = Kitify.removeURLParameter(url_request, '_');

                        var ajaxOpts = {
                            url: url_request,
                            type: "GET",
                            cache: true,
                            dataType: 'html',
                            success: function (res) {
                                success_func(res);
                            }
                        };
                        $.ajax(ajaxOpts);
                    }

                })
                .on('click', '.kitify .kitify-ajax-pagination .page-numbers a', function (e){
                    e.preventDefault();
                    if ($('body').hasClass('elementor-editor-active')) {
                        return false;
                    }
                    var $kitWrap,$parentContainer, $container, ajaxType, $parentNav, widgetId, itemSelector, templateId, pagedKey;
                    $parentNav = $(this).closest('.kitify-pagination');
                    $kitWrap = $(this).closest('.kitify');
                    widgetId = $kitWrap.data('id');

                    if ($parentNav.hasClass('doing-ajax')) {
                        return false;
                    }

                    templateId = $kitWrap.closest('.elementor[data-elementor-settings][data-elementor-id]').data('elementor-id');

                    if($kitWrap.hasClass('elementor-kitify-wooproducts')){
                        $container = $kitWrap.find('.kitify-products__list');
                        $parentContainer = $kitWrap.find('.kitify-products');
                        itemSelector = '.kitify-product.product_item';
                        var tmpClass = $parentContainer.closest('.woocommerce').attr('class').match(/\bkitify_wc_widget_([^\s]*)/);
                        if (tmpClass !== null && tmpClass[1]) {
                            pagedKey = 'product-page-' + tmpClass[1];
                        }
                        else{
                            pagedKey = 'paged';
                        }
                    }
                    else{
                        $container = $($parentNav.data('container'));
                        $parentContainer = $($parentNav.data('parent-container'));
                        itemSelector = $parentNav.data('item-selector');
                        pagedKey = $parentNav.data('ajax_request_id');
                    }

                    ajaxType = 'load_widget';
                    if($kitWrap.find('div[data-widget_current_query="yes"]').length > 0){
                        ajaxType = 'load_fullpage';
                        pagedKey = 'paged';
                    }

                    $parentNav.addClass('doing-ajax');
                    $parentContainer.addClass('doing-ajax');

                    var success_func = function (res, israw) {

                        var $response;

                        if(israw){
                            var jsoncontent = JSON.parse(res);
                            var contentraw = jsoncontent['template_content'];
                            $response = $('<div></div>').html(contentraw);
                        }
                        else{
                            $response = $(res);
                        }

                        var $data = $response.find('.elementor-element-' + widgetId + ' ' + itemSelector);

                        if ($parentContainer.find('.kitify-carousel').length > 0) {
                            var swiper = $parentContainer.find('.kitify-carousel').get(0).swiper;
                            swiper.removeAllSlides();
                            swiper.appendSlide($data);
                        }
                        else if ($container.data('isotope')) {
                            $container.isotope('remove', $container.isotope('getItemElements'));
                            $container.isotope('insert', $data);
                            $(document).trigger('kitify/masonry/calculate-item-sizes', [$parentContainer, true]);

                            $('img', $data).imagesLoaded().progress(function (instance, image) {
                                var $image = $(image.img),
                                    $parentItem = $image.closest(itemSelector);
                                $parentItem.addClass('item-loaded');
                                $container.isotope('layout');
                            });
                        }
                        else {
                            $data.addClass('fadeIn animated').appendTo($container.empty());
                        }

                        $parentContainer.removeClass('doing-ajax');
                        $parentNav.removeClass('doing-ajax kitify-ajax-load-first');

                        if ($response.find( '.elementor-element-' + widgetId + ' .kitify-ajax-pagination').length) {
                            var $new_pagination = $response.find( '.elementor-element-' + widgetId + ' .kitify-ajax-pagination');
                            $parentNav.replaceWith($new_pagination);
                            $parentNav = $new_pagination;
                        }
                        else {
                            $parentNav.addClass('nothingtoshow');
                        }

                        if($response.find( '.elementor-element-' + widgetId + ' .woocommerce-result-count').length && $kitWrap.find('.woocommerce-result-count').length){
                            $kitWrap.find('.woocommerce-result-count').replaceWith($response.find( '.elementor-element-' + widgetId + ' .woocommerce-result-count'));
                        }

                        $('html,body').animate({
                            'scrollTop': $parentContainer.offset().top - getHeaderHeight() - 50
                        }, 400);

                        $('body').trigger('jetpack-lazy-images-load');
                        $(document).trigger('kitify/ajax-pagination/success', {
                            parentContainer: $parentContainer,
                            contentHolder: $container,
                            pagination: $parentNav
                        });
                    };

                    var url_request = e.target.href.replace(/^\//, '');

                    if( ajaxType == 'load_widget' ){
                        var _tmpURL = url_request;
                        url_request = Kitify.addQueryArg(KitifySettings.widgetApiUrl, 'template_id', templateId);
                        url_request = Kitify.addQueryArg(url_request, 'widget_id', widgetId);
                        url_request = Kitify.addQueryArg(url_request, 'dev', KitifySettings.devMode);
                        url_request = Kitify.addQueryArg(url_request, pagedKey, Kitify.getUrlParameter(pagedKey, _tmpURL));
                        url_request = Kitify.addQueryArg(url_request, 'kitifypagedkey', pagedKey);
                    }

                    url_request = Kitify.removeURLParameter(url_request, '_');

                    var ajaxOpts = {
                        url: url_request,
                        type: "GET",
                        cache: true,
                        dataType: 'html',
                        ajax_request_id: Kitify.getUrlParameter(pagedKey, url_request),
                        success: function (res) {
                            if(ajaxType == 'load_widget'){
                                success_func(res, true);
                            }
                            else{
                                success_func(res, false);
                            }
                        }
                    };

                    $.ajax(ajaxOpts)

                })
                .on('click', '[data-kitify-element-link]', function (e) {
                    var $wrapper = $(this),
                        data = $wrapper.data('kitify-element-link'),
                        id = $wrapper.data('id'),
                        anchor = document.createElement('a'),
                        anchorReal;

                    anchor.id = 'nova-wrapper-link-' + id;
                    anchor.href = data.url;
                    anchor.target = data.is_external ? '_blank' : '_self';
                    anchor.rel = data.nofollow ? 'nofollow noreferer' : '';
                    anchor.style.display = 'none';

                    document.body.appendChild(anchor);

                    anchorReal = document.getElementById(anchor.id);
                    anchorReal.click();
                    anchorReal.remove();
                })
                .on('click', '.kitify-masonry_filter .kitify-masonry_filter-item', function (e){
                    e.preventDefault();
                    var $wrap = $(this).closest('.kitify-masonry_filter'),
                        $isotopeInstance = $($wrap.data('kitifymasonry_container')),
                        _filter = $(this).data('filter');
                    if (_filter != '*'){
                        _filter = '.' + _filter;
                    }
                    if ($isotopeInstance.data('isotope')) {
                        $(this).addClass('active').siblings('.kitify-masonry_filter-item').removeClass('active');
                        $isotopeInstance.isotope({
                            filter: _filter
                        });
                    }
                })
                .on('kitify/masonry/calculate-item-sizes', function (e, $isotope_container, need_relayout) {
                    var masonrySettings = $isotope_container.data('kitifymasonry_layouts') || false,
                        $isotopeInstance = $isotope_container.find($isotope_container.data('kitifymasonry_wrap'));

                    if (masonrySettings !== false) {
                        var win_w = $(window).width(),
                            selector = $isotope_container.data('kitifymasonry_itemselector');

                        if (win_w > 1023) {
                            $isotope_container.addClass('cover-img-bg');

                            var _base_w = masonrySettings.item_width,
                                _base_h = masonrySettings.item_height,
                                _container_width_base = masonrySettings.container_width,
                                _container_width = $isotope_container.width(),
                                item_per_page = Math.round(_container_width_base / _base_w),
                                itemwidth = Math.floor(_container_width / item_per_page),
                                margin = parseInt($isotope_container.data('kitifymasonry_itemmargin') || 0),
                                dimension = (_base_h ? parseFloat(_base_w / _base_h) : 1),
                                layout_mapping = masonrySettings.layout || [{w: 1, h: 1}];

                            var _idx = 0,
                                _idx2 = 0;

                            $(selector, $isotope_container).each(function () {
                                $(this)
                                    .css({
                                        'width': Math.floor(itemwidth * (layout_mapping[_idx]['w']) - (margin / 2)),
                                        'height': _base_h ? Math.floor(itemwidth / dimension * (layout_mapping[_idx]['h'])) : 'auto'
                                    })
                                    .addClass('kitify-disable-cols-style');
                                    // .attr('data-kitifymansory--item_setting', JSON.stringify({
                                    //     'index': _idx2 + '_' + _idx,
                                    //     'itemwidth': itemwidth,
                                    //     'layout': layout_mapping[_idx]
                                    // }));
                                _idx++;
                                if (_idx == layout_mapping.length) {
                                    _idx2++;
                                    _idx = 0;
                                }
                            });
                        } else {
                            $isotope_container.removeClass('kitify-masonry--cover-bg');
                            $(selector, $isotope_container).css({
                                'width': '',
                                'height': ''
                            }).removeClass('kitify-disable-cols-style');
                        }
                    }
                    if (need_relayout) {
                        if ($isotopeInstance.data('isotope')) {
                            $isotopeInstance.isotope('layout');
                        }
                    }
                })
                .on('keyup', function (e) {
                    if(e.keyCode == 27){
                        $('.kitify-cart').removeClass('kitify-cart-open');
                        $('.kitify-hamburger-panel').removeClass('open-state');
                        $('html').removeClass('kitify-hamburger-panel-visible');
                    }
                })
                .on('kitify/section/calculate-container-width', '.elementor-top-section', function (e){
                    var $scope = $(this);
                    var $child_container = $scope.find('>.elementor-container');
                    $child_container.css('--kitify-section-width', $child_container.width() + 'px');
                    $(window).on('resize', function (){
                        $child_container.css('--kitify-section-width', $child_container.width() + 'px');
                    });
                })
                .on('click', function (e){
                    if( $(e.target).closest('.kitify-cart').length == 0 ) {
                        $('.kitify-cart').removeClass('kitify-cart-open');
                    }
                })
        },
        ImageScrollHandler: function($scope) {
        var elementSettings  = getElementSettings( $scope ),
			      scrollElement    = $scope.find('.kitify-image-scroll-container'),
            scrollOverlay    = scrollElement.find('.kitify-image-scroll-overlay'),
            scrollVertical   = scrollElement.find('.kitify-image-scroll-vertical'),
            imageScroll      = scrollElement.find('.kitify-image-scroll-image img'),
            direction        = elementSettings.direction_type,
            reverse			 = elementSettings.reverse,
            trigger			 = elementSettings.trigger_type,
            transformOffset  = null;

        function startTransform() {
            imageScroll.css('transform', (direction === 'vertical' ? 'translateY' : 'translateX') + '( -' +  transformOffset + 'px)');
        }

        function endTransform() {
            imageScroll.css('transform', (direction === 'vertical' ? 'translateY' : 'translateX') + '(0px)');
        }

        function setTransform() {
            if( direction === 'vertical' ) {
                transformOffset = imageScroll.height() - scrollElement.height();
            } else {
                transformOffset = imageScroll.width() - scrollElement.width();
            }
        }

        if ( trigger === 'scroll' ) {
            scrollElement.addClass('kitify-container-scroll');
            if ( direction === 'vertical' ) {
                scrollVertical.addClass('kitify-image-scroll-ver');
            } else {
                scrollElement.imagesLoaded(function() {
                  scrollOverlay.css( { 'width': imageScroll.width(), 'height': imageScroll.height() } );
                });
            }
        } else {
            if ( reverse === 'yes' ) {
                scrollElement.imagesLoaded(function() {
                    scrollElement.addClass('kitify-container-scroll-instant');
                    setTransform();
                    startTransform();
                });
            }
            if ( direction === 'vertical' ) {
                scrollVertical.removeClass('kitify-image-scroll-ver');
            }
            scrollElement.mouseenter(function() {
                scrollElement.removeClass('kitify-container-scroll-instant');
                setTransform();
                reverse === 'yes' ? endTransform() : startTransform();
            });

            scrollElement.mouseleave(function() {
                reverse === 'yes' ? startTransform() : endTransform();
            });
        }
    },
        isEditMode: function () {
            return Boolean(elementorFrontend.isEditMode());
        },
        mobileAndTabletCheck: function () {
            return ( (('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0)) && (window.innerWidth < 1400) )
        },
        loadStyle: function (style, uri) {

            if (Kitify.addedStyles.hasOwnProperty(style) && Kitify.addedStyles[style] === uri) {
                return style;
            }

            if (!uri) {
                return;
            }

            Kitify.addedStyles[style] = uri;

            return new Promise(function (resolve, reject) {
                var tag = document.createElement('link');

                tag.id = style + '-css';
                tag.rel = 'stylesheet';
                tag.href = uri;
                tag.type = 'text/css';
                tag.media = 'all';
                tag.onload = function () {
                    resolve(style);
                };
                tag.onerror = function () {
                    reject(`Can not load css file "${uri}"`);
                }

                document.head.appendChild(tag);
            });
        },
        loadScriptAsync: function (script, uri, callback, async) {
            if (Kitify.addedScripts.hasOwnProperty(script)) {
                return script;
            }
            if (!uri) {
                return;
            }
            Kitify.addedScripts[script] = uri;
            return new Promise(function (resolve, reject) {
                var tag = document.createElement('script');

                tag.src = uri;
                tag.id = script + '-js';
                tag.async = async;
                tag.onload = function () {
                    resolve(script);
                    if ("function" == typeof callback && "number" != typeof callback.nodeType) {
                        callback();
                    }
                };

                tag.onerror = function () {
                    reject(`Can not load javascript file "${uri}"`);
                    if ("function" == typeof callback && "number" != typeof callback.nodeType) {
                        callback();
                    }
                }

                document.head.appendChild(tag);
            });
        },
        detectWidgetsNotInHeader: function (){
            var itemDetected = ['.elementor-widget-icon-list', '.main-color', '.elementor-icon', '.elementor-heading-title', '.elementor-widget-text-editor', '.elementor-widget-divider', '.elementor-icon-list-item', '.elementor-social-icon', '.elementor-button', '.kitify-nav-wrap', '.kitify-nav', '.menu-item-link-depth-0'];
            itemDetected.forEach(function ( _item ){
                if($(_item).each(function (){
                    if( $(this).closest('.kitify-nav__sub').length ){
                        $(this).addClass('ignore-docs-style-yes');
                    }
                }));
            });


            $('.elementor-widget-icon-list .elementor-icon-list-item').each(function (){
                var $child_a = $('>a', $(this)),
                    _href = $child_a.attr('href');
                if($(this).closest('.kitify-nav__sub').length && $(this).closest('.menu-item.need-check-active').length){
                    if(window.location.href == _href){
                        $(this).addClass('current-menu-item')
                    }
                }
            })
        },
        LazyLoad: function (){
            var selector = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
            var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
            var _defaultConfig$option = $.extend({}, {
                    rootMargin: '50px',
                    threshold: 0,
                    load: function load(element) {
                        var base_src = element.getAttribute('data-src') || element.getAttribute('data-lazy') || element.getAttribute('data-lazy-src') || element.getAttribute('data-lazy-original'),
                            base_srcset = element.getAttribute('data-src') || element.getAttribute('data-lazy-srcset'),
                            base_sizes = element.getAttribute('data-sizes') || element.getAttribute('data-lazy-sizes');
                        if (base_src) {
                            element.src = base_src;
                        }
                        if (base_srcset) {
                            element.srcset = base_srcset;
                        }
                        if (base_sizes) {
                            element.sizes = base_sizes;
                        }
                        if (element.getAttribute('data-background-image')) {
                            element.style.backgroundImage = 'url("' + element.getAttribute('data-background-image') + '")';
                        }
                        element.setAttribute('data-element-loaded', true);
                        if (element.classList.contains('jetpack-lazy-image')) {
                            element.classList.add('jetpack-lazy-image--handled');
                        }
                    },
                    complete: function (elm) {}
                }, options),
                rootMargin = _defaultConfig$option.rootMargin,
                threshold = _defaultConfig$option.threshold,
                load = _defaultConfig$option.load,
                complete = _defaultConfig$option.complete; // // If initialized, then disconnect the observer

            var _target_cache = false,
                _counter = 0;

            function onIntersection(load) {
                return function (entries, observer) {
                    entries.forEach(function (entry) {
                        if(entry.isIntersecting){
                            if(_counter > 7){
                                _counter = 0;
                            }
                            if(_target_cache !== entry.target.offsetTop){
                                _counter = 0;
                                _target_cache = entry.target.offsetTop;
                            }
                            else{
                                _counter++;
                            }
                            observer.unobserve(entry.target);
                            entry.target.style.setProperty('--effect-delay', _counter);
                            load(entry.target);
                        }
                    });
                };
            }

            var observer = void 0;

            if ("IntersectionObserver" in window) {
                observer = new IntersectionObserver(onIntersection(load), {
                    rootMargin: rootMargin,
                    threshold: threshold
                });
            }
            return {
                observe: function observe() {
                    if(!selector){
                        return;
                    }
                    for (var i = 0; i < selector.length; i++) {
                        if(selector[i].getAttribute('data-element-loaded') === 'true'){
                            continue;
                        }
                        if (observer) {
                            observer.observe(selector[i]);
                            continue;
                        }
                        load(selector[i]);
                    }
                    complete(selector);
                }
            };
        },
        elementorFrontendInit: function ($container, reinit_global_trigger) {
            if( typeof window.elementorFrontend.hooks === "undefined"){
                return;
            }
            Kitify.detectWidgetsNotInHeader();
            if(reinit_global_trigger){
                $(window).trigger('elementor/frontend/init');
            }
            $container.removeClass('need-reinit-js');
            $container.find('[data-element_type]').each(function () {
                var $this = $(this),
                    elementType = $this.data('element_type');

                if (!elementType) {
                    return;
                }

                try {
                    if ('widget' === elementType) {
                        elementType = $this.data('widget_type');
                        window.elementorFrontend.hooks.doAction('frontend/element_ready/widget', $this, $);
                    }

                    window.elementorFrontend.hooks.doAction('frontend/element_ready/global', $this, $);
                    window.elementorFrontend.hooks.doAction('frontend/element_ready/' + elementType, $this, $);

                } catch (err) {
                    Kitify.log(err);
                    $this.remove();
                    return false;
                }
            });
        },
        initAnimationsHandlers: function ($selector) {
            $selector.find('[data-element_type]').each(function () {
                var $this = $(this),
                    elementType = $this.data('element_type');

                if (!elementType) {
                    return;
                }

                window.elementorFrontend.hooks.doAction('frontend/element_ready/global', $this, $);
            });
        },
        hamburgerPanel: function ($scope) {

            var wid = $scope.data('id'),
                _wid_tpl_id = $scope.find('.kitify-hamburger-panel__content').attr('data-template-id'),
                _need_add_remove = true,
                $wContent = $scope.find('>.elementor-widget-container').clone();

            if( !!$scope.data('hamburgerTemplateId') && ( _wid_tpl_id == $scope.data('hamburgerTemplateId') ) ){
                _need_add_remove = false;
            }
            else{
                $scope.data('hamburgerTemplateId', _wid_tpl_id);
            }

            if ($('.kitify-site-wrapper > .elementor-location-header >.kitify-burger-wrapall').length == 0) {
                $('<div/>').addClass('kitify-burger-wrapall').appendTo($('.kitify-site-wrapper > .elementor-location-header'));
            }

            var $burger_wrap_all = $('.kitify-burger-wrapall');

            if ( _need_add_remove && $('.elementor-element-' + wid, $burger_wrap_all).length) {
                $('.elementor-element-' + wid, $burger_wrap_all).remove();
            }

            var $new_scope = $scope;

            if($scope.closest('.elementor-location-header').length){
                if(_need_add_remove){
                    $('<div/>').addClass('elementor-element elementor-element-' + wid).append($wContent).appendTo($burger_wrap_all);
                }
                $('.kitify.elementor-element-' + wid + ' .kitify-hamburger-panel__instance').remove();
                $new_scope = $('.elementor-element-' + wid, $burger_wrap_all);
                $('.kitify-hamburger-panel__toggle', $new_scope).remove();
            }

            var $panel = $('.kitify-hamburger-panel', $new_scope),
                $toggleButton = $('.kitify-hamburger-panel__toggle', $scope),
                $instance = $('.kitify-hamburger-panel__instance', $new_scope),
                $cover = $('.kitify-hamburger-panel__cover', $new_scope),
                $inner = $('.kitify-hamburger-panel__inner', $new_scope),
                $closeButton = $('.kitify-hamburger-panel__close-button', $new_scope),
                $html = $('html'),
                settings = $panel.data('settings') || {},
                $panelInstance = $('.elementor-element-' + wid + ' .kitify-hamburger-panel');

            if (!settings['ajaxTemplate']) {
                Kitify.elementorFrontendInit($inner, false);
            }

            $toggleButton.on('click', function (e) {
                e.preventDefault();
                if (!$panel.hasClass('open-state')) {
                    $panelInstance.addClass('open-state');
                    $html.addClass('kitify-hamburger-panel-visible');
                    Kitify.initAnimationsHandlers($inner);
                } else {
                    $panelInstance.removeClass('open-state');
                    $html.removeClass('kitify-hamburger-panel-visible');
                }
            });
            $closeButton.on('click', function (e) {
                e.preventDefault();
                if (!$panel.hasClass('open-state')) {
                    $panelInstance.addClass('open-state');
                    $html.addClass('kitify-hamburger-panel-visible');
                    Kitify.initAnimationsHandlers($inner);
                }
                else {
                    $panelInstance.removeClass('open-state');
                    $html.removeClass('kitify-hamburger-panel-visible');
                }
            });

            $(document).on('click.kitifyHamburgerPanel', function (event) {

                if (($(event.target).closest('.kitify-hamburger-panel__toggle').length || $(event.target).closest('.kitify-hamburger-panel__instance').length)
                    && !$(event.target).closest('.kitify-hamburger-panel__cover').length
                ) {
                    return;
                }

                if (!$panel.hasClass('open-state')) {
                    return;
                }

                $('.elementor-element-' + wid + ' .kitify-hamburger-panel').removeClass('open-state');

                if (!$(event.target).closest('.kitify-hamburger-panel__toggle').length) {
                    $html.removeClass('kitify-hamburger-panel-visible');
                }

                event.stopPropagation();
            });
        },
        wooCard: function ($scope) {
            if (window.KitifyEditor && window.KitifyEditor.activeSection) {
                let section = window.KitifyEditor.activeSection,
                    isCart = -1 !== ['cart_list_style', 'cart_list_items_style', 'cart_buttons_style'].indexOf(section);

                $('.widget_shopping_cart_content').empty();
                $(document.body).trigger('wc_fragment_refresh');
            }

            var $target = $('.kitify-cart', $scope),
                $toggle = $('.kitify-cart__heading-link', $target),
                settings = $target.data('settings'),
                firstMouseEvent = true;

            switch (settings['triggerType']) {
                case 'hover':
                    hoverType();
                    break;
                case 'click':
                    clickType();
                    break;
            }

            $target.on('click', '.kitify-cart__close-button', function (event) {
                if (!$target.hasClass('kitify-cart-open-proccess')) {
                    $target.toggleClass('kitify-cart-open');
                }
            });

            function hoverType() {
                var scrollOffset = 0;
                if ('ontouchend' in window || 'ontouchstart' in window) {
                    $target.on('touchstart', function (event) {
                        scrollOffset = $(window).scrollTop();
                    });

                    $target.on('touchend', function (event) {

                        if (scrollOffset !== $(window).scrollTop()) {
                            return false;
                        }

                        var $this = $(this);

                        if ($this.hasClass('kitify-cart-open-proccess')) {
                            return;
                        }

                        setTimeout(function () {
                            $this.toggleClass('kitify-cart-open');
                        }, 10);
                    });

                    $(document).on('touchend', function (event) {

                        if ($(event.target).closest($target).length) {
                            return;
                        }

                        if ($target.hasClass('kitify-cart-open-proccess')) {
                            return;
                        }

                        if (!$target.hasClass('kitify-cart-open')) {
                            return;
                        }

                        $target.removeClass('kitify-cart-open');
                    });
                } else {

                    $target.on('mouseenter mouseleave', function (event) {

                        if (firstMouseEvent && 'mouseleave' === event.type) {
                            return;
                        }

                        if (firstMouseEvent && 'mouseenter' === event.type) {
                            firstMouseEvent = false;
                        }

                        if (!$(this).hasClass('kitify-cart-open-proccess')) {
                            $(this).toggleClass('kitify-cart-open');
                        }
                    });
                }
            }

            function clickType() {
                $toggle.on('click', function (event) {
                    event.preventDefault();

                    if (!$target.hasClass('kitify-cart-open-proccess')) {
                        $target.toggleClass('kitify-cart-open');
                    }
                });
            }
        },
        wooGallery: function ($scope) {
            if (Kitify.isEditMode()) {
                $('.woocommerce-product-gallery', $scope).wc_product_gallery();
            }

            var centerdots_cb = function () {
                if ($scope.find('.flex-viewport').length) {
                    $scope.find('.woocommerce-product-gallery').css('--singleproduct-thumbs-height', $scope.find('.flex-viewport').height() + 'px');
                    if ($scope.find('.woocommerce-product-gallery__trigger').length) {
                        $scope.find('.woocommerce-product-gallery__trigger').appendTo($scope.find('.flex-viewport'));
                    }
                    if ($('.la-custom-badge', $scope).length) {
                        $('.la-custom-badge', $scope).prependTo($scope.find('.flex-viewport'));
                    }
                    if ($('.woocommerce-product-gallery__actions', $scope).length) {
                        $('.woocommerce-product-gallery__actions', $scope).prependTo($scope.find('.flex-viewport'));
                    }
                }

                var $nav = $scope.find('.flex-direction-nav');
                if ($nav.length && $scope.find('.flex-viewport').length) {
                    $nav.appendTo($scope.find('.flex-viewport'))
                }

                var $thumbs = $scope.find('.flex-control-thumbs').get(0);
                if (typeof $thumbs === "undefined" || $scope.find('.kitify-product-images').hasClass('layout-type-wc')) {
                    return;
                }

                var pos = {top: 0, left: 0, x: 0, y: 0};
                var mouseDownHandler = function (e) {
                    $thumbs.style.cursor = 'grabbing';
                    $thumbs.style.userSelect = 'none';

                    pos = {
                        left: $thumbs.scrollLeft,
                        top: $thumbs.scrollTop,
                        // Get the current mouse position
                        x: e.clientX,
                        y: e.clientY,
                    };

                    document.addEventListener('mousemove', mouseMoveHandler);
                    document.addEventListener('mouseup', mouseUpHandler);
                };

                var mouseMoveHandler = function (e) {
                    // How far the mouse has been moved
                    const dx = e.clientX - pos.x;
                    const dy = e.clientY - pos.y;

                    // Scroll the element
                    $thumbs.scrollTop = pos.top - dy;
                    $thumbs.scrollLeft = pos.left - dx;
                };

                var mouseUpHandler = function () {
                    $thumbs.style.cursor = 'grab';
                    $thumbs.style.removeProperty('user-select');

                    document.removeEventListener('mousemove', mouseMoveHandler);
                    document.removeEventListener('mouseup', mouseUpHandler);
                };
                // Attach the handler
                $thumbs.addEventListener('mousedown', mouseDownHandler);
            }
            setTimeout(centerdots_cb, 300);

            function flexdestroy($els) {
                $els.each(function () {
                    var $el = jQuery(this);
                    var $elClean = $el.clone();

                    $elClean.find('.flex-viewport').children().unwrap();
                    $elClean.find('img.zoomImg, .woocommerce-product-gallery__trigger').remove();
                    $elClean
                        .removeClass('flexslider')
                        .find('.clone, .flex-direction-nav, .flex-control-nav')
                        .remove()
                        .end()
                        .find('*').removeAttr('style').removeClass(function (index, css) {
                        // If element is SVG css has an Object inside (?)
                        if (typeof css === 'string') {
                            return (css.match(/\bflex\S+/g) || []).join(' ');
                        }
                    });
                    $elClean.insertBefore($el);
                    $el.remove();
                });

            }

            if ($scope.find('.kitify-product-images').hasClass('layout-type-5') || $scope.find('.kitify-product-images').hasClass('layout-type-6')) {
                flexdestroy($scope.find('.kitify-product-images'));
            }


            var $gallery_target = $scope.find('.woocommerce-product-gallery');

            var data_columns = parseInt($gallery_target.data('columns'));
            if($scope.find('.kitify-product-images').hasClass('layout-type-4')){
                data_columns = parseInt($gallery_target.closest('.elementor-kitify-wooproduct-images').css('--singleproduct-image-column'));
            }

            if ($gallery_target.find('.woocommerce-product-gallery__image').length <= data_columns) {
                $gallery_target.addClass('center-thumb');
                if($scope.find('.kitify-product-images').hasClass('layout-type-4')){
                    flexdestroy($scope.find('.kitify-product-images'));
                    $gallery_target = $scope.find('.woocommerce-product-gallery');
                }
            }
            if( $scope.find('.kitify-product-images').hasClass('layout-type-5') || $scope.find('.kitify-product-images').hasClass('layout-type-6') ){
                $scope.find('.woocommerce-product-gallery__image a').attr('data-elementor-open-lightbox', 'yes');
                $scope.find('.woocommerce-product-gallery__image a').attr('data-elementor-lightbox-slideshow', $scope.data('id'));
            }
            else{
                $scope.find('.woocommerce-product-gallery__image a').attr('data-elementor-open-lightbox', 'no');
            }
            $scope.find('.woocommerce-product-gallery__image').each(function (){
                if( $(this).find('.zoominner').length == 0 ){
                    $(this).wrapInner('<div class="zoomouter"><div class="zoominner"></div></div>');
                }
            })
            var initZoom = function (zoomTarget) {

                var zoom_enabled = $.isFunction($.fn.zoom) && wc_single_product_params.zoom_enabled;
                if (!zoom_enabled) {
                    return;
                }
                var galleryWidth = $gallery_target.width(),
                    zoomEnabled = false,
                    zoom_options;

                if($scope.find('.kitify-product-images').hasClass('layout-type-4')){
                    galleryWidth = $(zoomTarget).width()
                }

                $(zoomTarget).each(function (index, target) {
                    var image = $(target).find('img');

                    if (image.data('large_image_width') > galleryWidth) {
                        zoomEnabled = true;
                        return false;
                    }
                });

                // But only zoom if the img is larger than its container.
                if (zoomEnabled) {

                    try {
                        zoom_options = $.extend({
                            touch: false
                        }, wc_single_product_params.zoom_options);
                    } catch (ex) {
                        zoom_options = {
                            touch: false
                        };
                    }

                    if ('ontouchstart' in document.documentElement) {
                        zoom_options.on = 'click';
                    }

                    zoomTarget.trigger('zoom.destroy');
                    zoomTarget.zoom(zoom_options);

                }
            }

            initZoom($gallery_target.find('.woocommerce-product-gallery__image .zoominner'));
        },
        wooTabs: function ($scope) {
            var $tabs = $scope.find('.wc-tabs-wrapper').first();
            if ($tabs) {
                $tabs.wrapInner('<div class="kitify-wc-tabs--content"></div>');
                $tabs.find('.wc-tabs').wrapAll('<div class="kitify-wc-tabs--controls"></div>');
                $tabs.find('.kitify-wc-tabs--controls').prependTo($tabs);
                $tabs.find('.wc-tab').wrapInner('<div class="tab-content"></div>');
                $tabs.find('.wc-tab').each(function () {
                    var _html = $('#' + $(this).attr('aria-labelledby')).html();
                    $(this).prepend('<div class="wc-tab-title">' + _html + '</div>');
                });
                $('.wc-tab-title a', $tabs).wrapInner('<span></span>');
                $('.wc-tab-title a', $tabs).on('click', function (e) {
                    e.preventDefault();
                    $tabs.find('.wc-tabs').find('li[aria-controls="' + $(this).attr('href').replace('#', '') + '"]').toggleClass('active').siblings().removeClass('active');
                    $(this).closest('.wc-tab').toggleClass('active').siblings().removeClass('active');
                });
                $('.wc-tabs li a', $tabs).on('click', function (e) {
                    var $wrapper = $(this).closest('.wc-tabs-wrapper, .woocommerce-tabs');
                    $wrapper.find($(this).attr('href')).addClass('active').siblings().removeClass('active');
                });
                $('.wc-tabs li', $tabs).removeClass('active');
                $('.wc-tab-title a', $tabs).first().trigger('click');
            }
        },
        SearchAnimate: function ($scope) {
          var $target = $scope.find('#js_header_search_modal');
          if (!$target.length) {
              return;
          }
          $("#js_header_search_modal").animatedModal({
      			animatedIn: 'slideInDown',
      			animatedOut: 'slideOutUp',
      			beforeOpen: function() {
      				window.setTimeout(function () {
      								$(".header-search").addClass('animate');
      				 }, 300);
      				 window.setTimeout(function () {
      								 $(".header-search").addClass('animate-line');
      					}, 1000);
      			},
      		});
        },
        animatedBoxHandler: function ($scope) {

            var $target = $scope.find('.kitify-animated-box'),
                toogleEvents = 'mouseenter mouseleave',
                scrollOffset = $(window).scrollTop(),
                firstMouseEvent = true;

            if (!$target.length) {
                return;
            }

            if ('ontouchend' in window || 'ontouchstart' in window) {
                $target.on('touchstart', function (event) {
                    scrollOffset = $(window).scrollTop();
                });

                $target.on('touchend', function (event) {

                    if (scrollOffset !== $(window).scrollTop()) {
                        return false;
                    }

                    if (!$(this).hasClass('flipped-stop')) {
                        $(this).toggleClass('flipped');
                    }
                });

            } else {
                $target.on(toogleEvents, function (event) {

                    if (firstMouseEvent && 'mouseleave' === event.type) {
                        return;
                    }

                    if (firstMouseEvent && 'mouseenter' === event.type) {
                        firstMouseEvent = false;
                    }

                    if (!$(this).hasClass('flipped-stop')) {
                        $(this).toggleClass('flipped');
                    }
                });
            }
        },

        ajaxTemplateHelper: {

            need_reinit_js : false,

            template_processed : {},

            template_processed_count : 0,

            template_loaded : [],

            total_template : 0,

            processInsertData: function ($el, templateContent, template_id){


                Kitify.ajaxTemplateHelper.template_processed_count++;

                if (templateContent) {
                    $el.html(templateContent);
                    if($el.find('div[data-kitify_ajax_loadtemplate]:not(.template-loaded,.is-loading)').length){
                        Kitify.log('found template in ajax content');
                        Kitify.ajaxTemplateHelper.init();
                    }
                }

                if(Kitify.ajaxTemplateHelper.template_processed_count >= Kitify.ajaxTemplateHelper.total_template && Kitify.ajaxTemplateHelper.need_reinit_js){
                    Kitify.ajaxTemplateHelper.need_reinit_js = false;
                    Promise.all(Kitify.addedAssetsPromises).then(function (value) {
                        // $(window).trigger('elementor/frontend/init');
                        Kitify.elementorFrontendInit($('.need-reinit-js[data-kitify_ajax_loadtemplate="true"]'), false);
                        $('.elementor-motion-effects-element').trigger('resize');
                        $('body').trigger('jetpack-lazy-images-load');
                        //Kitify.log('Kitify.addedAssetsPromises --- FINISHED');

                    }, function (reason){

                        Kitify.log(`An error occurred while insert the asset resources, however we still need to insert content. Reason detail: "${reason}"`);
                        // $(window).trigger('elementor/frontend/init');
                        Kitify.elementorFrontendInit($('.need-reinit-js[data-kitify_ajax_loadtemplate="true"]'), false);
                        $('.elementor-motion-effects-element').trigger('resize');
                        $('body').trigger('jetpack-lazy-images-load');
                        Kitify.log('Kitify.addedAssetsPromises --- ERROR');
                    });
                }

                $(document).trigger('kitify/ajax-load-template/after', {
                    target_id: template_id,
                    contentHolder: $el,
                    parentContainer: $el,
                    response: templateContent
                });
            },
            templateRenderCallback: function ( response, template_id ){
                var templateContent = response['template_content'],
                    templateScripts = response['template_scripts'],
                    templateStyles = response['template_styles'],
                    template_metadata = response['template_metadata'];

                for (var scriptHandler in templateScripts) {
                    if($( '#' + scriptHandler + '-js').length == 0) {
                        Kitify.addedAssetsPromises.push(Kitify.loadScriptAsync(scriptHandler, templateScripts[scriptHandler], '', true));
                    }
                }

                for (var styleHandler in templateStyles) {
                    if($( '#' + styleHandler + '-css').length == 0) {
                        Kitify.addedAssetsPromises.push(Kitify.loadStyle(styleHandler, templateStyles[styleHandler]));
                    }
                }
                document.querySelectorAll('body:not(.elementor-editor-active) div[data-kitify_ajax_loadtemplate][data-cache-id="' + template_id + '"]:not(.template-loaded)').forEach(function (elm) {
                    elm.classList.remove('is-loading');
                    elm.classList.add('template-loaded');
                    elm.classList.add('need-reinit-js');
                    Kitify.ajaxTemplateHelper.processInsertData($(elm), templateContent, template_id);
                });

                var wpbar = document.querySelectorAll('#wp-admin-bar-elementor_edit_page ul');

                if (wpbar && typeof template_metadata['title'] !== "undefined") {
                    setTimeout(function () {
                        var _tid = 'wp-admin-bar-elementor_edit_doc_'+template_metadata['id'];
                        if($('#'+_tid).length == 0){
                            $('<li id="'+_tid+'" class="elementor-general-section"><a class="ab-item" title="'+template_metadata['title']+'" data-title="'+template_metadata['title']+'" href="' + template_metadata['href'] + '"><span class="elementor-edit-link-title">' + template_metadata['title'] + '</span><span class="elementor-edit-link-type">' + template_metadata['sub_title'] + '</span></a></li>').prependTo($(wpbar));
                        }
                    }, 2000);
                }
            },
            init: function (){
                if(KitifySettings.isElementorAdmin){
                    /** do not run if current context is editor **/
                    return;
                }
                Kitify.ajaxTemplateHelper.need_reinit_js = false;
                Kitify.ajaxTemplateHelper.template_loaded = [];
                Kitify.ajaxTemplateHelper.template_processed_count = 0;
                Kitify.ajaxTemplateHelper.total_template = 0;
                Kitify.ajaxTemplateHelper.template_processed = {};

                var templates = document.querySelectorAll('body:not(.elementor-editor-active) div[data-kitify_ajax_loadtemplate]:not(.template-loaded)');
                if (templates.length) {
                    var template_ids = [];
                    var template_exist_ids = [];
                    templates.forEach(function (el) {
                        if (!el.classList.contains('is-loading') && !el.classList.contains('template-loaded')) {
                            el.classList.add('is-loading');
                            var _cache_key = el.getAttribute('data-template-id');
                            if (!template_ids.includes(_cache_key)) {
                                var exits_nodes = document.querySelectorAll('.elementor.elementor-'+_cache_key+'[data-elementor-type]:not([data-elementor-title])');
                                if(exits_nodes.length == 0){
                                    template_ids.push(_cache_key);
                                }
                                else{
                                    template_exist_ids.push(_cache_key);
                                }
                            }
                            el.setAttribute('data-cache-id', _cache_key);
                        }
                    });

                    var arr_ids = [], _idx1 = 0, _idx2 = 0, _bk = 6;

                    var ajaxCalling = function (template_ids){

                        var _ajax_data_sending = {
                            'action': 'kitify_ajax',
                            '_nonce': window.KitifySettings.ajaxNonce,
                            'actions': JSON.stringify({
                                'elementor_template' : {
                                    'action': 'elementor_template',
                                    'data': {
                                        'template_ids': template_ids,
                                        'current_url': window.location.href,
                                        'current_url_no_search': window.location.href.replace(window.location.search, ''),
                                        'dev': window.KitifySettings.devMode
                                    }
                                }
                            })
                        };
                        if(KitifySettings.useFrontAjax == 'true'){
                            _ajax_data_sending['kitify-ajax'] = 'yes';
                            delete _ajax_data_sending['action'];
                        }
                        $.ajax({
                            type: KitifySettings.useFrontAjax == 'true' ? 'GET' : 'POST',
                            url:  KitifySettings.useFrontAjax == 'true' ? window.location.href : window.KitifySettings.ajaxUrl,
                            dataType: 'json',
                            data: _ajax_data_sending,
                            success: function (resp, textStatus, jqXHR) {
                                var responses = resp.data.responses.elementor_template.data;
                                $.each( responses, function( templateId, response ) {
                                    var cached_key = 'kitifyTpl_' + templateId;
                                    var browserCacheKey = Kitify.localCache.cache_key + '_' + Kitify.localCache.hashCode(templateId);
                                    Kitify.localCache.set(cached_key, response);
                                    Kitify.ajaxTemplateHelper.templateRenderCallback(response, templateId);
                                    try{
                                        //Kitify.log('setup browser cache for ' + browserCacheKey);
                                        localStorage.setItem(browserCacheKey, JSON.stringify(response));
                                        localStorage.setItem(browserCacheKey + ':ts', Date.now());
                                    }
                                    catch (ajax_ex1){
                                        Kitify.log('Cannot setup browser cache', ajax_ex1);
                                    }
                                });
                            }
                        });
                    }

                    template_exist_ids.forEach(function (templateId){
                        var exist_tpl = document.querySelector('.elementor.elementor-'+templateId+'[data-elementor-type]');
                        Kitify.ajaxTemplateHelper.need_reinit_js = true;
                        Kitify.ajaxTemplateHelper.templateRenderCallback({
                            'template_content' : exist_tpl.outerHTML,
                            'template_scripts' : [],
                            'template_styles' : [],
                            'template_metadata' : {},
                        }, templateId);
                    });

                    template_ids.forEach(function (templateId){
                        var cached_key = 'kitifyTpl_' + templateId;
                        var cached_key2 = 'kitifyTplExist_' + templateId;

                        if(Kitify.localCache.exist(cached_key2)){
                            if(Kitify.localCache.exist(cached_key)){
                                Kitify.ajaxTemplateHelper.need_reinit_js = true;
                                Kitify.ajaxTemplateHelper.templateRenderCallback(Kitify.localCache.get(cached_key), templateId);
                            }
                            return;
                        }
                        Kitify.localCache.set(cached_key2, 'yes');

                        if(Kitify.localCache.exist(cached_key)){
                            Kitify.ajaxTemplateHelper.need_reinit_js = true;
                            Kitify.ajaxTemplateHelper.templateRenderCallback(Kitify.localCache.get(cached_key), templateId);
                        }
                        else{

                            $(document).trigger('kitify/ajax-load-template/before', {
                                target_id: templateId
                            });

                            var browserCacheKey = Kitify.localCache.cache_key + '_' + Kitify.localCache.hashCode(templateId);
                            var expiry = Kitify.localCache.timeout;
                            try{
                                var browserCached = localStorage.getItem(browserCacheKey);
                                var browserWhenCached = localStorage.getItem(browserCacheKey + ':ts');

                                if (browserCached !== null && browserWhenCached !== null) {
                                    var age = (Date.now() - browserWhenCached) / 1000;
                                    if (age < expiry) {
                                        //Kitify.log(`render from cache for ID: ${templateId} | Cache Key: ${browserCacheKey}`);
                                        Kitify.ajaxTemplateHelper.need_reinit_js = true;
                                        Kitify.ajaxTemplateHelper.templateRenderCallback(JSON.parse(browserCached), templateId);
                                        return;
                                    }
                                    else {
                                        //Kitify.log(`clear browser cache key for ID: ${templateId} | Cache Key: ${browserCacheKey}`);
                                        // We need to clean up this old key
                                        localStorage.removeItem(browserCacheKey);
                                        localStorage.removeItem(browserCacheKey + ':ts');
                                    }
                                }
                                //Kitify.log('run ajaxCalling() for ' + templateId);
                                _idx1++;
                                if(_idx1 > _bk){
                                    _idx1 = 0;
                                    _idx2++;
                                }
                                if( "undefined" == typeof arr_ids[_idx2] ) {
                                    arr_ids[_idx2] = [];
                                }
                                arr_ids[_idx2].push(templateId);
                                Kitify.ajaxTemplateHelper.template_loaded.push(templateId);
                            }
                            catch (ajax_ex) {
                                Kitify.log('Cannot setup browser cache ajaxCalling() for ' + templateId);
                                _idx1++;
                                if(_idx1 == _bk){
                                    _idx1 = 0;
                                    _idx2++;
                                }
                                if( "undefined" == typeof arr_ids[_idx2] ) {
                                    arr_ids[_idx2] = [];
                                }
                                arr_ids[_idx2].push(templateId);
                                Kitify.ajaxTemplateHelper.template_loaded.push(templateId);
                            }
                        }

                    });

                    Kitify.ajaxTemplateHelper.total_template = templates.length;

                    if(arr_ids.length){
                        Kitify.ajaxTemplateHelper.need_reinit_js = true;
                        arr_ids.forEach(function (arr_id){
                            ajaxCalling(arr_id);
                        });
                    }
                }
            }
        },
    }

    $(window).on('elementor/frontend/init', function () {

        elementor.hooks.addAction('frontend/element_ready/kitify-advanced-carousel.default', function ($scope) {
            Kitify.initCarousel($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-slides.default', function ($scope) {
            Kitify.initCarousel($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-posts.default', function ($scope) {
            Kitify.initCarousel($scope);
            Kitify.initMasonry($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-portfolio.default', function ($scope) {
            Kitify.initCarousel($scope);
            Kitify.initMasonry($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-images-layout.default', function ($scope) {
            Kitify.initCarousel($scope);
            Kitify.initMasonry($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-team-member.default', function ($scope) {
            Kitify.initCarousel($scope);
            Kitify.initMasonry($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-testimonials.default', function ($scope) {
            Kitify.initCarousel($scope);
            Kitify.initMasonry($scope);
        });
        elementor.hooks.addAction('frontend/element_ready/kitify-banner-list.default', function ($scope) {
            Kitify.initCarousel($scope);
            Kitify.initMasonry($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-instagram-feed.default', function ($scope) {
            Kitify.initCarousel($scope);
            Kitify.initMasonry($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-wooproduct-datatabs.default', function ($scope) {
            $scope.foundation();
        });
        elementor.hooks.addAction('frontend/element_ready/kitify-woo-categories.default', function ($scope) {
            Kitify.initCarousel($scope);
        });
        elementor.hooks.addAction('frontend/element_ready/kitify-scroll-image.default', function ($scope) {
            Kitify.ImageScrollHandler($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-hamburger-panel.default', function ($scope) {
            Kitify.hamburgerPanel($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-menucart.default', function ($scope) {
            Kitify.wooCard($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-animated-box.default', function ($scope) {
            Kitify.animatedBoxHandler($scope);
        });
        elementor.hooks.addAction('frontend/element_ready/kitify-search.default', function ($scope) {
            Kitify.SearchAnimate($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-wooproducts.default', function ($scope) {
            Kitify.initCarousel($scope);
            Kitify.initMasonry($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-wooproduct-images.default', function ($scope) {
            Kitify.wooGallery($scope);
        });

        elementor.hooks.addAction('frontend/element_ready/kitify-wooproduct-datatabs.default', function ($scope) {
            Kitify.wooTabs($scope);
        });
        elementor.hooks.addAction('frontend/element_ready/kitify-image-comparison.default', function ($scope) {
          var $target              = $scope.find( '.kitify-image-comparison__instance' ),
    				instance             = null,
    				imageComparisonItems = $( '.kitify-image-comparison__container', $target ),
    				settings             = $target.data( 'settings' ),
    				elementId            = $scope.data( 'id' );

    			if ( ! $target.length ) {
    				return;
    			}

    			window.juxtapose.scanPage( '.kitify-juxtapose' );


    			Kitify.initCarousel( $scope );
        });
        $(document).on('kitify/activetabs', function (e, $tabContent){
            $('.col-row', $tabContent).each(function (){
                $(this).addClass('hello')
                $(this).trigger('kitify/LazyloadSequenceEffects');
            })
        });
        elementor.hooks.addAction('frontend/element_ready/section', function ($scope) {
            if( $scope.hasClass('elementor-top-section') ) {
                $scope.trigger('kitify/section/calculate-container-width');
            }
        });

        Kitify.initCustomHandlers();

    });

    window.Kitify = Kitify;

    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (options.cache) {
            //Here is our identifier for the cache. Maybe have a better, safer ID (it depends on the object string representation here) ?
            // on $.ajax call we could also set an ID in originalOptions
            var id = Kitify.removeURLParameter(originalOptions.url, '_') + ("undefined" !== typeof originalOptions.ajax_request_id ? JSON.stringify(originalOptions.ajax_request_id) : "undefined" !== typeof originalOptions.data ? JSON.stringify(originalOptions.data) : '');
            id = Kitify.localCache.hashCode(id.replace(/null$/g, ''));
            options.cache = false;

            options.beforeSend = function () {
                if (!Kitify.localCache.exist(id)) {
                    jqXHR.promise().done(function (data, textStatus) {
                        Kitify.localCache.set(id, data);
                    });
                }
                return true;
            };
        }
    });
    $.ajaxTransport("+*", function (options, originalOptions, jqXHR) {
        //same here, careful because options.url has already been through jQuery processing
        var id = Kitify.removeURLParameter(originalOptions.url, '_') + ("undefined" !== typeof originalOptions.ajax_request_id ? JSON.stringify(originalOptions.ajax_request_id) : "undefined" !== typeof originalOptions.data ? JSON.stringify(originalOptions.data) : '');
        options.cache = false;
        id = Kitify.localCache.hashCode(id.replace(/null$/g, ''));
        if (Kitify.localCache.exist(id)) {
            return {
                send: function (headers, completeCallback) {
                    setTimeout(function () {
                        completeCallback(200, "OK", [Kitify.localCache.get(id)]);
                    }, 50);
                },
                abort: function () {
                    /* abort code, nothing needed here I guess... */
                }
            };
        }
    });

}(jQuery, window.elementorFrontend));
