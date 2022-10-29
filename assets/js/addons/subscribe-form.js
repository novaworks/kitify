(function ($) {

    "use strict";


    /**
     * Ajax Handlers
     */
    var KitifyHandlerUtils = {
        /**
         * Rendering notice message
         *
         * @param  {String} type    Message type
         * @param  {String} message Message content
         * @return {Void}
         */
        noticeCreate: function (type, message, isPublicPage) {
            var notice,
                rightDelta = 0,
                timeoutId,
                isPublic = isPublicPage || false;

            if (!message || 'true' === isPublic) {
                return false;
            }

            notice = $('<div class="kitify-handler-notice ' + type + '"><span class="dashicons"></span><div class="inner">' + message + '</div></div>');

            $('body').prepend(notice);
            reposition();
            rightDelta = -1 * (notice.outerWidth(true) + 10);
            notice.css({'right': rightDelta});

            timeoutId = setTimeout(function () {
                notice.css({'right': 10}).addClass('show-state');
            }, 100);
            timeoutId = setTimeout(function () {
                rightDelta = -1 * (notice.outerWidth(true) + 10);
                notice.css({right: rightDelta}).removeClass('show-state');
            }, 4000);
            timeoutId = setTimeout(function () {
                notice.remove();
                clearTimeout(timeoutId);
            }, 4500);

            function reposition() {
                var topDelta = 100;

                $('.kitify-handler-notice').each(function () {
                    $(this).css({top: topDelta});
                    topDelta += $(this).outerHeight(true);
                });
            }
        },

        /**
         * Serialize form into
         *
         * @return {Object}
         */
        serializeObject: function (form) {

            var self = this,
                json = {},
                pushCounters = {},
                patterns = {
                    'validate': /^[a-zA-Z][a-zA-Z0-9_-]*(?:\[(?:\d*|[a-zA-Z0-9_-]+)\])*$/,
                    'key': /[a-zA-Z0-9_-]+|(?=\[\])/g,
                    'push': /^$/,
                    'fixed': /^\d+$/,
                    'named': /^[a-zA-Z0-9_-]+$/
                };

            this.build = function (base, key, value) {
                base[key] = value;

                return base;
            };

            this.push_counter = function (key) {
                if (undefined === pushCounters[key]) {
                    pushCounters[key] = 0;
                }

                return pushCounters[key]++;
            };

            $.each(form.serializeArray(), function () {
                var k, keys, merge, reverseKey;

                // Skip invalid keys
                if (!patterns.validate.test(this.name)) {
                    return;
                }

                keys = this.name.match(patterns.key);
                merge = this.value;
                reverseKey = this.name;

                while (undefined !== (k = keys.pop())) {

                    // Adjust reverseKey
                    reverseKey = reverseKey.replace(new RegExp('\\[' + k + '\\]$'), '');

                    // Push
                    if (k.match(patterns.push)) {
                        merge = self.build([], self.push_counter(reverseKey), merge);
                    } else if (k.match(patterns.fixed)) {
                        merge = self.build([], k, merge);
                    } else if (k.match(patterns.named)) {
                        merge = self.build({}, k, merge);
                    }
                }

                json = $.extend(true, json, merge);
            });

            return json;
        }
    };

    var KitifyAjaxHandler = function (options) {
        /**
         * General default settings
         *
         * @type {Object}
         */
        var self = this,
            settings = {
                'handlerId': '',
                'cache': false,
                'processData': true,
                'url': '',
                'async': false,
                'beforeSendCallback': function () { },
                'errorCallback': function () { },
                'successCallback': function () { },
                'completeCallback': function () { }
            };

        /**
         * Checking options, settings and options merging
         *
         */
        if (options) {
            $.extend(settings, options);
        }

        /**
         * Set handler settings from localized global variable
         *
         * @type {Object}
         */
        self.handlerSettings = window.kitifySubscribeConfig || {};

        /**
         * Ajax request instance
         *
         * @type {Object}
         */
        self.ajaxRequest = null;

        /**
         * Ajax processing state
         *
         * @type {Boolean}
         */
        self.ajaxProcessing = false;

        /**
         * Set ajax request data
         *
         * @type {Object}
         */
        self.data = {
            'action': self.handlerSettings.action,
            'nonce': self.handlerSettings.nonce
        };

        /**
         * Check ajax url is empty
         */
        if ('' === settings.url) {
            // Check public request
            settings.url = self.handlerSettings.ajax_url;
        }

        /**
         * Init ajax request
         *
         * @return {Void}
         */
        self.send = function () {
            if (self.ajaxProcessing) {
                KitifyHandlerUtils.noticeCreate('error-notice', self.handlerSettings.sys_messages.wait_processing, self.handlerSettings.is_public);
            }
            self.ajaxProcessing = true;
            self.ajaxRequest = $.ajax({
                type: self.handlerSettings.type,
                url: settings.url,
                data: self.data,
                cache: settings.cache,
                dataType: self.handlerSettings.data_type,
                processData: settings.processData,
                beforeSend: function (jqXHR, ajaxSettings) {
                    if (null !== self.ajaxRequest && !settings.async) {
                        self.ajaxRequest.abort();
                    }

                    if (settings.beforeSendCallback && 'function' === typeof (settings.beforeSendCallback)) {
                        settings.beforeSendCallback(jqXHR, ajaxSettings);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(document).trigger({
                        type: 'kitify-ajax-handler-error',
                        jqXHR: jqXHR,
                        textStatus: textStatus,
                        errorThrown: errorThrown
                    });

                    if (settings.errorCallback && 'function' === typeof (settings.errorCallback)) {
                        settings.errorCallback(jqXHR, textStatus, errorThrown);
                    }
                },
                success: function (data, textStatus, jqXHR) {
                    self.ajaxProcessing = false;

                    $(document).trigger({
                        type: 'kitify-ajax-handler-success',
                        response: data,
                        jqXHR: jqXHR,
                        textStatus: textStatus
                    });

                    if (settings.successCallback && 'function' === typeof (settings.successCallback)) {
                        settings.successCallback(data, textStatus, jqXHR);
                    }

                    //KitifyHandlerUtils.noticeCreate(data.type, data.message, self.handlerSettings.is_public);
                },
                complete: function (jqXHR, textStatus) {
                    $(document).trigger({
                        type: 'kitify-ajax-handler-complete',
                        jqXHR: jqXHR,
                        textStatus: textStatus
                    });

                    if (settings.completeCallback && 'function' === typeof (settings.completeCallback)) {
                        settings.completeCallback(jqXHR, textStatus);
                    }
                }

            });
        };

        /**
         * Send data ajax request
         *
         * @param  {Object} data User data
         * @return {Void}
         */
        self.sendData = function (data) {
            var sendData = data || {};
            self.data = {
                'action': 'kitify_ajax',
                '_nonce': self.handlerSettings.nonce,
                'actions': JSON.stringify({
                    'newsletter_subscribe' : {
                        'action': 'newsletter_subscribe',
                        'data': sendData
                    }
                }),
            };
            self.send();
        };

        /**
         * Send form serialized data
         * @param  {String} formId Form selector
         * @return {Void}
         */
        self.sendFormData = function (formId) {
            var form = $(formId),
                data;

            data = KitifyHandlerUtils.serializeObject(form);

            self.sendData(data);
        };
    };

    var KitifySubscribeForm = function( $scope ) {
        var $target = $scope.find('.kitify-subscribe-form'),
            scoreId = $scope.data('id'),
            settings = $target.data('settings'),
            subscribeFormAjaxId = 'kitify_elementor_subscribe_form_ajax',
            $subscribeForm = $('.kitify-subscribe-form__form', $target),
            $fields = $('.kitify-subscribe-form__fields', $target),
            $mailField = $('.kitify-subscribe-form__mail-field', $target),
            $inputData = $mailField.data('instance-data'),
            $submitButton = $('.kitify-subscribe-form__submit', $target),
            $subscribeFormMessage = $('.kitify-subscribe-form__message', $target),
            timeout = null,
            invalidMailMessage = window.kitifySubscribeConfig.sys_messages.invalid_mail;

        var kitifySubscribeFormAjax = new KitifyAjaxHandler({
            handlerId: subscribeFormAjaxId,

            errorCallback: function (jqXHR, textStatus, errorThrown){
                var message = window.kitifySubscribeConfig.sys_messages.invalid_nonce,
                    responceClass = 'kitify-subscribe-form--response-error';

                $submitButton.removeClass('loading');

                $target.addClass(responceClass);

                $('span', $subscribeFormMessage).html(message);
                $subscribeFormMessage.css({'visibility': 'visible'});

                timeout = setTimeout(function () {
                    $subscribeFormMessage.css({'visibility': 'hidden'});
                    $target.removeClass(responceClass);
                }, 20000);
            },
            successCallback: function ( response ) {
                var successType, message, responceClass;
                if(response.success){
                    if(response.data.responses.newsletter_subscribe.success){
                        message = response.data.responses.newsletter_subscribe.data.message;
                        successType = response.data.responses.newsletter_subscribe.data.type;
                    }
                    else{
                        message = response.data.responses.newsletter_subscribe.data;
                        successType = 'error';
                    }
                }
                else {
                    successType = 'error';
                    message = response.responses.error.data;
                }

                responceClass = 'kitify-subscribe-form--response-' + successType;

                $submitButton.removeClass('loading');

                $target.removeClass('kitify-subscribe-form--response-error');
                $target.addClass(responceClass);

                $('span', $subscribeFormMessage).html(message);
                $subscribeFormMessage.css({'visibility': 'visible'});

                timeout = setTimeout(function () {
                    $subscribeFormMessage.css({'visibility': 'hidden'});
                    $target.removeClass(responceClass);
                }, 20000);

                if (settings['redirect']) {
                    window.location.href = settings['redirect_url'];
                }

                $(window).trigger({
                    type: 'kitify/subscribe',
                    elementId: scoreId,
                    successType: successType,
                    inputData: $inputData
                });
            }
        });

        $mailField.on('focus', function () {
            $mailField.removeClass('mail-invalid');
        });

        $(document).keydown(function (event) {

            if (13 === event.keyCode && $mailField.is(':focus')) {
                subscribeHandle();

                return false;
            }
        });

        $submitButton.on('click', function () {
            subscribeHandle();
            return false;
        });

        function validateEmail (email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            return re.test(email);
        }

        function subscribeHandle() {
            var inputValue = $mailField.val(),
                sendData = {
                    'email': inputValue,
                    'use_target_list_id': settings['use_target_list_id'] || false,
                    'target_list_id': settings['target_list_id'] || '',
                    'data': $inputData
                },
                serializeArray = $subscribeForm.serializeArray(),
                additionalFields = {};

            if (validateEmail(inputValue)) {

                $.each(serializeArray, function (key, fieldData) {

                    if ('email' === fieldData.name) {
                        sendData[fieldData.name] = fieldData.value;
                    } else {
                        additionalFields[fieldData.name] = fieldData.value;
                    }
                });

                sendData['additional'] = additionalFields;

                kitifySubscribeFormAjax.sendData(sendData);

                $submitButton.addClass('loading');
            }
            else {
                $mailField.addClass('mail-invalid');

                $target.addClass('kitify-subscribe-form--response-error');
                $('span', $subscribeFormMessage).html(invalidMailMessage);
                $subscribeFormMessage.css({'visibility': 'visible'});

                timeout = setTimeout(function () {
                    $target.removeClass('kitify-subscribe-form--response-error');
                    $subscribeFormMessage.css({'visibility': 'hidden'});
                    $mailField.removeClass('mail-invalid');
                }, 20000);
            }
        }
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kitify-subscribe-form.default', function ($scope) {
            KitifySubscribeForm($scope);
        });
    });

}(jQuery));
