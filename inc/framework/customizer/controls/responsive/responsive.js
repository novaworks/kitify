/*global jQuery, _ */
(function (exports, $, win) {
    var api = exports.customize;
    var __ = exports.i18n.__;
    win.kitifycustomizer = {};
    var kitifycustomizer = win.kitifycustomizer;
    var CSSViewer = win.kitifycustomizer.CSSViewer;
    var checkWork = {
        '=': function _(a, b) {
            // eslint-disable-next-line eqeqeq
            return a == b;
        },
        '!=': function _(a, b) {
            // eslint-disable-next-line eqeqeq
            return a != b;
        },
        '==': function _(a, b) {
            return a === b;
        },
        '!==': function _(a, b) {
            return a !== b;
        },
        '>': function _(a, b) {
            return a > b;
        }
    };
    CSSViewer = api.Class.extend({
        rules: {},
        sheets: {
            desktop: null,
            laptop: null,
            tablet: null,
            mobile_extra: null,
            mobile: null
        },
        initialize: function initialize(elWin, elDoc) {
            var reinit = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
            this.win = elWin;
            this.doc = elDoc;

            if (reinit) {
                this.rules = {};
                this.sheets = {
                    desktop: null,
                    laptop: null,
                    tablet: null,
                    mobile_extra: null,
                    mobile: null
                };
            }

            this.initStyleTag();
        },
        initStyleTag: function initStyleTag() {
            this.style = this.doc.createElement('style');
            this.styleLaptop = this.doc.createElement('style');
            this.styleTablet = this.doc.createElement('style');
            this.styleMobileExtra = this.doc.createElement('style');
            this.styleMobile = this.doc.createElement('style');
            this.style.id = 'kitifycustomizer-desktop';
            this.styleLaptop.id = 'kitifycustomizer-laptop';
            this.styleTablet.id = 'kitifycustomizer-tablet';
            this.styleMobileExtra.id = 'kitifycustomizer-mobile_extra';
            this.styleMobile.id = 'kitifycustomizer-mobile';
            this.styleLaptop.media = '(max-width: 1600px)';
            this.styleTablet.media = '(max-width: 1280px)';
            this.styleMobileExtra.media = '(max-width: 881px)';
            this.styleMobile.media = '(max-width: 575.98px)';
            this.doc.head.appendChild(this.style);
            this.doc.head.appendChild(this.styleLaptop);
            this.doc.head.appendChild(this.styleTablet);
            this.doc.head.appendChild(this.styleMobileExtra);
            this.doc.head.appendChild(this.styleMobile);
            this.sheets = {
                desktop: this.style.sheet,
                laptop: this.styleLaptop.sheet,
                tablet: this.styleTablet.sheet,
                mobile_extra: this.styleMobileExtra.sheet,
                mobile: this.styleMobile.sheet
            };
        },
        addRule: function addRule(sheet, device, selector) {
            if (this.rules[device] === undefined) {
                this.rules[device] = {};
            }
            this.rules[device][selector] = sheet.cssRules.length;
            sheet.insertRule(selector + '{}', sheet.cssRules.length);
            return this.rules[device][selector];
        },
        getRuleIndex: function getRuleIndex(device, selector) {
            if (this.rules[device]) {
                return this.rules[device][selector] || null;
            }

            return null;
        },
        getRule: function getRule(sheet, ruleIndex) {
            return sheet.cssRules.item(ruleIndex);
        },
        addProperty: function addProperty(device, selector, property, value) {
            var isImportant = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : false;
            var sheet = this.sheets[device];

            if (sheet === undefined) {
                return;
            }

            var ruleIndex = this.getRuleIndex(device, selector);

            if (ruleIndex === null) {
                ruleIndex = this.addRule(sheet, device, selector);
            }

            var rule = this.getRule(sheet, ruleIndex);
            var style = rule.style;
            var important = '';

            if (isImportant) {
                important = 'important';
            }

            var oldValue = style[property];

            if (oldValue !== value) {
                style.setProperty(property, value, important);
            }
        },
        watch: function watch(control, cssItems) {
            var _this = this;

            if (control.params.responsive) {
                Object.keys(this.sheets).forEach(function (device) {
                    if (control.settings[device] !== undefined) {
                        var settingObj = control.settings[device];
                        var initValue = settingObj.get();

                        if (control.processCSSValue !== undefined) {
                            control.processCSSValue(function (processedValue) {
                                _this.processCSS(device, processedValue, cssItems);
                            }, initValue, settingObj, device);
                        } else {
                            _this.processCSS(device, initValue, cssItems);
                        }

                        settingObj.bind(function (newValue) {
                            if (control.processCSSValue !== undefined) {
                                control.processCSSValue(function (processedValue) {
                                    _this.processCSS(device, processedValue, cssItems);
                                }, newValue, settingObj, device);
                            } else {
                                _this.processCSS(device, newValue, cssItems);
                            }
                        });
                    }
                });
            }
            else if (control.settings.default !== undefined) {
                var settingObj = control.settings.default;
                var initValue = settingObj.get();

                if (control.processCSSValue !== undefined) {
                    control.processCSSValue(function (processedValue) {
                        _this.processCSS('desktop', processedValue, cssItems);
                    }, initValue, settingObj, 'desktop');
                } else {
                    this.processCSS('desktop', initValue, cssItems);
                }

                settingObj.bind(function (newValue) {
                    if (control.processCSSValue !== undefined) {
                        newValue = control.processCSSValue(function (processedValue) {
                            _this.processCSS('desktop', processedValue, cssItems);
                        }, newValue, settingObj, 'desktop');
                    } else {
                        _this.processCSS('desktop', newValue, cssItems);
                    }
                });
            }
        },
        processCSS: function processCSS(device, value, cssItems) {
            var _this2 = this;

            if (cssItems.length) {
                cssItems.forEach(function (cssItem) {
                    if (cssItem.conditions !== undefined) {
                        cssItem.conditions.forEach(function (condition) {
                            var check = _this2.checkWork[condition.type](value, condition.check);

                            if (check) {
                                if (cssItem.replace !== undefined && cssItem.property !== undefined) {
                                    var newValue = _.isEmpty(condition.value.toString()) ? 'initial' : cssItem.replace.replace(/\{\{(?:\s+)?value(?:\s+)?\}\}/g, condition.value);

                                    _this2.addProperty(device, cssItem.selector, cssItem.property, newValue);
                                } else if (cssItem.property !== undefined) {
                                    _this2.addProperty(device, cssItem.selector, cssItem.property, condition.value);
                                }
                            }
                        });
                    } else if (cssItem.replace !== undefined && cssItem.replace !== '' && cssItem.property !== undefined) {
                        var newValue = _.isEmpty(value.toString()) ? 'initial' : cssItem.replace.replace(/\{\{(?:\s+)?value(?:\s+)?\}\}/g, value);

                        _this2.addProperty(device, cssItem.selector, cssItem.property, newValue);
                    } else if (cssItem.property !== undefined) {
                        _this2.addProperty(device, cssItem.selector, cssItem.property, value);
                    }
                });
            }
        },
        checkWork: checkWork
    });
    api.bind('ready', function () {
        api.previewer.bind('ready', function () {
            var iframe = api.previewer.preview.iframe.length ? api.previewer.preview.iframe[0] : {};
            kitifycustomizer.css = new CSSViewer(iframe.contentWindow, iframe.contentDocument, kitifycustomizer.css !== undefined);
            api.trigger('cmlycssready');
        });
    });
    kitifycustomizer.Control = api.Control.extend({
        ready: function ready() {
            var _this3 = this;

            this.readyInput();
            this.description();
            this.checkDepends();

            if (this.params.responsive) {
                this.responsive();
            }

            api.bind('cmlycssready', function () {
                _this3.css();
            });
        },
        readyInput: function readyInput() {
            var control = this;
            control.container.on('change keyup paste', 'input', function () {
                control.setValue($(this).val());
            });
        },
        setValue: function setValue(value) {
            if (this.params.responsive) {
                var device = api.previewedDevice !== undefined ? api.previewedDevice.get() : 'desktop';
                this.settings[device].set(value);
            } else {
                this.setting.set(value);
            }
        },
        description: function description() {
            var control = this;
            var btn = control.container.find('.kitifycustomizer-control-wrap .kitifycustomizer-toggle-desc');
            var desc = control.container.find('.kitifycustomizer-control-wrap .customize-control-description');
            btn.on('click', function (e) {
                e.preventDefault();
                btn.toggleClass('is-active');
                desc.toggleClass('is-active');
                desc.slideToggle('fast');
            });
        },
        responsive: function responsive() {
            var _this4 = this;

            var responsiveContainer = this.container.find('.kitifycustomizer-control-responsive');
            responsiveContainer.find('button').on('click', function (e) {
                if (responsiveContainer.hasClass('kitifycustomizer-expanded')) {
                    if ($(e.currentTarget).hasClass('is-active')) {
                        responsiveContainer.removeClass('kitifycustomizer-expanded');
                    } else {
                        api.previewedDevice.set($(e.currentTarget).data('device'));
                    }
                } else {
                    responsiveContainer.addClass('kitifycustomizer-expanded');
                }
            });
            api.bind('ready', function () {
                api.previewedDevice.bind(function (newDevice) {
                    responsiveContainer.find('button.is-active').removeClass('is-active');
                    responsiveContainer.find('button.kitifycustomizer-device-' + newDevice).addClass('is-active');

                    if (_this4.params.responsive) {
                        _this4.setting = _this4.settings[newDevice];

                        _this4.resetValue(_this4.setting.get());

                        _this4.onChangeDevice(newDevice);
                    }
                });
            });
        },
        resetValue: function resetValue(newValue) {
            this.container.find('input').val(newValue);
        },
        onChangeDevice: function onChangeDevice(newDevice) {
            return newDevice;
        },
        checkDepends: function checkDepends(noBind) {
            var control = this;
            var depends = control.params.depends;
            var showIt = control.showIt();
            var needBind = noBind ? false : true;

            if (depends) {
                showIt.set(control.dependsWork(depends, needBind));
            }
        },
        showIt: function showIt() {
            var control = this;
            return {
                value: true,
                set: function set(value) {
                    this.value = value;

                    if (value) {
                        control.container.removeClass('deactivate');
                    } else {
                        control.container.addClass('deactivate');
                    }
                },
                get: function get() {
                    return this.value;
                }
            };
        },
        dependsWork: function dependsWork(depends, needBind) {
            var control = this,
                items = depends.items,
                relation = depends.relation || '&',
                values = [];

            _.each(items, function (item) {
                if (!item.items) {
                    var settingObject = api.value(item.id);
                    values.push(control.checkWork[item.check](settingObject.get(), item.value));

                    if (needBind) {
                        settingObject.bind(function () {
                            control.checkDepends(true);
                        });
                    }
                }
            });

            if (values.length > 1) {
                return control.relationWork[relation](values);
            }

            return values[0];
        },
        checkWork: checkWork,
        relationWork: {
            '&': function _(a) {
                return a.every(function (b) {
                    return b === true;
                });
            }
        },
        css: function css() {

            var cssItems = this.params.css;

            if (!cssItems.length || this.setting.transport === 'refresh') {
                return;
            }
            if (kitifycustomizer.css !== undefined) {
                kitifycustomizer.css.watch(this, cssItems);
            }
        },
        getNotificationsContainerElement: function getNotificationsContainerElement() {
            var control = this;
            var notificationsContainer;
            notificationsContainer = control.container.find('.customize-control-notifications-container:first');

            if (notificationsContainer.length) {
                return notificationsContainer;
            }

            notificationsContainer = $('<div class="customize-control-notifications-container"></div>');

            if (control.container.find('.kitifycustomizer-control-wrap').hasClass('kitifycustomizer-inline-control')) {
                control.container.find('.kitifycustomizer-control-input').after(notificationsContainer);
            } else {
                control.container.find('.kitifycustomizer-control-title').after(notificationsContainer);
            }

            return notificationsContainer;
        }
    });
    kitifycustomizer.NumberControl = kitifycustomizer.Control.extend({
        getIntValue: function getIntValue() {
            var theInput = this.container.find('.kitifycustomizer-input-number > .kitifycustomizer-number-input');
            var theValue = parseFloat(theInput.val());
            return theValue;
        },
        getUnit: function getUnit() {
            var theInput = this.container.find('.kitifycustomizer-input-number > .kitifycustomizer-number-input');
            var theValue = theInput.val();
            var theUnit = this.getUnitByValue(theValue);

            if (!this.params.no_unit && theUnit === '') {
                theUnit = this.params.unit;
            }

            return theUnit;
        },
        getUnitByValue: function getUnitByValue(unitValue) {
            if (unitValue === undefined && unitValue === '' && typeof unitValue !== 'string') {
                return '';
            }

            var numberValue = parseFloat(unitValue);
            var theUnit = unitValue.replace(numberValue, '');
            return theUnit;
        },
        work: function work() {
            var workType = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'plus';
            var number = this.getIntValue();
            var _this$params = this.params,
                min = _this$params.min,
                max = _this$params.max,
                step = _this$params.step;
            min = min === undefined ? 0 : min;
            max = max === undefined ? 100 : max;
            step = step === undefined ? 1 : step;
            min = parseFloat(min);
            max = parseFloat(max);
            step = parseFloat(step);
            var newNumber = number;

            if (isNaN(newNumber)) {
                newNumber = 0;
            }

            if (workType === 'plus') {
                if (newNumber >= max) {
                    return;
                }

                newNumber = newNumber + step;
            } else {
                if (newNumber <= min) {
                    return;
                }

                newNumber = newNumber - step;
            }

            newNumber = parseFloat(newNumber.toFixed(2));
            var unit = this.getUnit();
            newNumber += unit;
            this.setNumberValue(newNumber);
        },
        up: function up() {
            this.work();
        },
        down: function down() {
            this.work('minus');
        },
        setNumberValue: function setNumberValue(newValue) {
            var theInput = this.container.find('.kitifycustomizer-input-number > .kitifycustomizer-number-input');
            var isValidUnit = this.checkValidUnit(newValue);

            if (isValidUnit !== -1 && isValidUnit === false) {
                this.notifications.add(new api.Notification('invalid_unit', {
                    message: __('Invalid Unit', 'kitifycustomizer'),
                    type: 'error'
                }));
            } else {
                this.notifications.remove('invalid_unit');
            }

            theInput.val(newValue);
            this.setValue(newValue);
        },
        readyInput: function readyInput() {
            var _this5 = this;

            this.container.on('change', '.kitifycustomizer-number-input', function (e) {
                _this5.setNumberValue(e.target.value);
            });
            this.container.on('keydown', '.kitifycustomizer-number-input', function (e) {
                if (_this5.timer) {
                    clearInterval(_this5.timer);
                    delete _this5.timer;
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();

                    _this5.up();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();

                    _this5.down();
                }
            });
            this.container.on('click', '.kitifycustomizer-number-input-up', function () {
                _this5.up();
            });
            this.container.on('click', '.kitifycustomizer-number-input-down', function () {
                _this5.down();
            });

            if (this.extendReadyInput !== undefined) {
                this.extendReadyInput();
            }
        },
        checkValidUnit: function checkValidUnit(newValue) {
            if (this.params.no_unit || !Array.isArray(this.params.units) || newValue === undefined || newValue === '') {
                return -1;
            }

            var theUnit = this.getUnitByValue(newValue);
            return this.params.units.indexOf(theUnit) !== -1;
        }
    });
})(wp, jQuery, window);

/*global kitifycustomizer */
(function (api, kitifycz) {
    api.customize.controlConstructor.kitify_responsive = kitifycz.NumberControl.extend({});
})(wp, kitifycustomizer);