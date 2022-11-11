/******/
(function (modules) { // webpackBootstrap
    /******/ 	// The module cache
    /******/
    var installedModules = {};
    /******/
    /******/ 	// The require function
    /******/
    function __webpack_require__(moduleId) {
        /******/
        /******/ 		// Check if module is in cache
        /******/
        if (installedModules[moduleId]) {
            /******/
            return installedModules[moduleId].exports;
            /******/
        }
        /******/ 		// Create a new module (and put it into the cache)
        /******/
        var module = installedModules[moduleId] = {
            /******/            i: moduleId,
            /******/            l: false,
            /******/            exports: {}
            /******/
        };
        /******/
        /******/ 		// Execute the module function
        /******/
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ 		// Flag the module as loaded
        /******/
        module.l = true;
        /******/
        /******/ 		// Return the exports of the module
        /******/
        return module.exports;
        /******/
    }

    /******/
    /******/
    /******/ 	// expose the modules object (__webpack_modules__)
    /******/
    __webpack_require__.m = modules;
    /******/
    /******/ 	// expose the module cache
    /******/
    __webpack_require__.c = installedModules;
    /******/
    /******/ 	// define getter function for harmony exports
    /******/
    __webpack_require__.d = function (exports, name, getter) {
        /******/
        if (!__webpack_require__.o(exports, name)) {
            /******/
            Object.defineProperty(exports, name, {enumerable: true, get: getter});
            /******/
        }
        /******/
    };
    /******/
    /******/ 	// define __esModule on exports
    /******/
    __webpack_require__.r = function (exports) {
        /******/
        if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
            /******/
            Object.defineProperty(exports, Symbol.toStringTag, {value: 'Module'});
            /******/
        }
        /******/
        Object.defineProperty(exports, '__esModule', {value: true});
        /******/
    };
    /******/
    /******/ 	// create a fake namespace object
    /******/ 	// mode & 1: value is a module id, require it
    /******/ 	// mode & 2: merge all properties of value into the ns
    /******/ 	// mode & 4: return value when already ns object
    /******/ 	// mode & 8|1: behave like require
    /******/
    __webpack_require__.t = function (value, mode) {
        /******/
        if (mode & 1) value = __webpack_require__(value);
        /******/
        if (mode & 8) return value;
        /******/
        if ((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
        /******/
        var ns = Object.create(null);
        /******/
        __webpack_require__.r(ns);
        /******/
        Object.defineProperty(ns, 'default', {enumerable: true, value: value});
        /******/
        if (mode & 2 && typeof value != 'string') for (var key in value) __webpack_require__.d(ns, key, function (key) {
            return value[key];
        }.bind(null, key));
        /******/
        return ns;
        /******/
    };
    /******/
    /******/ 	// getDefaultExport function for compatibility with non-harmony modules
    /******/
    __webpack_require__.n = function (module) {
        /******/
        var getter = module && module.__esModule ?
            /******/            function getDefault() {
                return module['default'];
            } :
            /******/            function getModuleExports() {
                return module;
            };
        /******/
        __webpack_require__.d(getter, 'a', getter);
        /******/
        return getter;
        /******/
    };
    /******/
    /******/ 	// Object.prototype.hasOwnProperty.call
    /******/
    __webpack_require__.o = function (object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };
    /******/
    /******/ 	// __webpack_public_path__
    /******/
    __webpack_require__.p = "";
    /******/
    /******/
    /******/ 	// Load entry module and return exports
    /******/
    return __webpack_require__(__webpack_require__.s = 70);
    /******/
})
    /************************************************************************/
    /******/([
    /* 0 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var _class = function (_elementorModules$Mod) {
            _inherits(_class, _elementorModules$Mod);

            function _class(settings, document) {
                _classCallCheck(this, _class);

                var _this = _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).call(this, settings));

                _this.document = document;
                return _this;
            }

            _createClass(_class, [{
                key: 'getTimingSetting',
                value: function getTimingSetting(settingKey) {
                    return this.getSettings(this.getName() + '_' + settingKey);
                }
            }]);

            return _class;
        }(elementorModules.Module);

        exports.default = _class;

        /***/
    }),
    /* 1 */,
    /* 2 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var _class = function (_elementorModules$Mod) {
            _inherits(_class, _elementorModules$Mod);

            function _class(settings, callback) {
                _classCallCheck(this, _class);

                var _this = _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).call(this, settings));

                _this.callback = callback;
                return _this;
            }

            _createClass(_class, [{
                key: 'getTriggerSetting',
                value: function getTriggerSetting(settingKey) {
                    return this.getSettings(this.getName() + '_' + settingKey);
                }
            }]);

            return _class;
        }(elementorModules.Module);

        exports.default = _class;

        /***/
    }),
    /* 3 */,
    /* 4 */,
    /* 5 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        var _get = function get(object, property, receiver) {
            if (object === null) object = Function.prototype;
            var desc = Object.getOwnPropertyDescriptor(object, property);
            if (desc === undefined) {
                var parent = Object.getPrototypeOf(object);
                if (parent === null) {
                    return undefined;
                } else {
                    return get(parent, property, receiver);
                }
            } else if ("value" in desc) {
                return desc.value;
            } else {
                var getter = desc.get;
                if (getter === undefined) {
                    return undefined;
                }
                return getter.call(receiver);
            }
        };

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var _class = function (_elementorModules$Vie) {
            _inherits(_class, _elementorModules$Vie);

            function _class() {
                _classCallCheck(this, _class);

                return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
            }

            _createClass(_class, [{
                key: '__construct',
                value: function __construct(options) {
                    var _this2 = this;

                    this.motionFX = options.motionFX;

                    this.runImmediately = this.run;

                    this.run = function () {
                        _this2.animationFrameRequest = requestAnimationFrame(_this2.run.bind(_this2));

                        if ('page' === _this2.motionFX.getSettings('range')) {
                            _this2.runImmediately();

                            return;
                        }

                        var dimensions = _this2.motionFX.getSettings('dimensions'),
                            elementTopWindowPoint = dimensions.elementTop - pageYOffset,
                            elementEntrancePoint = elementTopWindowPoint - innerHeight,
                            elementExitPoint = elementTopWindowPoint + dimensions.elementHeight;

                        if (elementEntrancePoint <= 0 && elementExitPoint >= 0) {
                            _this2.runImmediately();
                        }
                    };
                }
            }, {
                key: 'runCallback',
                value: function runCallback() {
                    var callback = this.getSettings('callback');

                    callback.apply(undefined, arguments);
                }
            }, {
                key: 'destroy',
                value: function destroy() {
                    cancelAnimationFrame(this.animationFrameRequest);
                }
            }, {
                key: 'onInit',
                value: function onInit() {
                    _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'onInit', this).call(this);

                    this.run();
                }
            }]);

            return _class;
        }(elementorModules.ViewModule);

        exports.default = _class;

        /***/
    }),
    /* 6 */,
    /* 7 */,
    /* 8 */,
    /* 9 */,
    /* 10 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        var StickyHandler = elementorModules.frontend.handlers.Base.extend({

            bindEvents: function bindEvents() {
                elementorFrontend.addListenerOnce(this.getUniqueHandlerID() + 'sticky', 'resize', this.run);
            },

            unbindEvents: function unbindEvents() {
                elementorFrontend.removeListeners(this.getUniqueHandlerID() + 'sticky', 'resize', this.run);
            },

            isActiveSticky: function isActiveSticky() {
                return undefined !== this.$element.data('sticky');
            },

            activate: function activate() {
                var elementSettings = this.getElementSettings(),
                    stickyOptions = {
                        to: elementSettings.sticky,
                        offset: elementSettings.sticky_offset,
                        effectsOffset: elementSettings.sticky_effects_offset,
                        classes: {
                            sticky: 'elementor-sticky',
                            stickyActive: 'elementor-sticky--active elementor-section--handles-inside',
                            stickyEffects: 'elementor-sticky--effects',
                            spacer: 'elementor-sticky__spacer'
                        }
                    },
                    $wpAdminBar = elementorFrontend.elements.$wpAdminBar;

                if (elementSettings.sticky_parent) {
                    stickyOptions.parent = '.elementor-location-archive, .e-root-container, .elementor-widget-wrap';
                }

                if ($wpAdminBar.length && 'top' === elementSettings.sticky && 'fixed' === $wpAdminBar.css('position')) {
                    stickyOptions.offset += $wpAdminBar.height();
                }
                this.$element.sticky(stickyOptions);
            },

            deactivate: function deactivate() {
                if (!this.isActiveSticky()) {
                    return;
                }

                this.$element.sticky('destroy');
            },

            run: function run(refresh) {
                if (!this.getElementSettings('sticky')) {
                    this.deactivate();

                    return;
                }

                var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
                    activeDevices = this.getElementSettings('sticky_on');

                if (-1 !== activeDevices.indexOf(currentDeviceMode)) {
                    if (true === refresh) {
                        this.reactivate();
                    } else if (!this.isActiveSticky()) {
                        this.activate();
                    }
                } else {
                    this.deactivate();
                }
            },

            reactivate: function reactivate() {
                this.deactivate();

                this.activate();
            },

            onElementChange: function onElementChange(settingKey) {
                if (-1 !== ['sticky', 'sticky_on'].indexOf(settingKey)) {
                    this.run(true);
                }

                if (-1 !== ['sticky_offset', 'sticky_effects_offset', 'sticky_parent'].indexOf(settingKey)) {
                    this.reactivate();
                }
            },

            onInit: function onInit() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

                this.run();
            },

            onDestroy: function onDestroy() {
                elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);

                this.deactivate();
            }

        });

        module.exports = function ($scope) {
            new StickyHandler({$element: $scope});
        };

        /***/
    }),
    /* 11 */,
    /* 12 */,
    /* 13 */,
    /* 14 */,
    /* 15 */,
    /* 16 */,
    /* 17 */,
    /* 18 */,
    /* 19 */,
    /* 20 */,
    /* 21 */,
    /* 22 */,
    /* 23 */,
    /* 24 */,
    /* 25 */,
    /* 26 */,
    /* 27 */,
    /* 28 */,
    /* 29 */,
    /* 30 */,
    /* 31 */,
    /* 32 */,
    /* 33 */,
    /* 34 */,
    /* 35 */,
    /* 36 */,
    /* 37 */,
    /* 38 */,
    /* 39 */,
    /* 40 */,
    /* 41 */,
    /* 42 */,
    /* 43 */,
    /* 44 */,
    /* 45 */,
    /* 46 */,
    /* 47 */,
    /* 48 */,
    /* 49 */,
    /* 50 */,
    /* 51 */,
    /* 52 */,
    /* 53 */,
    /* 54 */,
    /* 55 */,
    /* 56 */,
    /* 57 */,
    /* 58 */,
    /* 59 */,
    /* 60 */,
    /* 61 */,
    /* 62 */,
    /* 63 */,
    /* 64 */,
    /* 65 */,
    /* 66 */,
    /* 67 */,
    /* 68 */,
    /* 69 */,
    /* 70 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        var _get = function get(object, property, receiver) {
            if (object === null) object = Function.prototype;
            var desc = Object.getOwnPropertyDescriptor(object, property);
            if (desc === undefined) {
                var parent = Object.getPrototypeOf(object);
                if (parent === null) {
                    return undefined;
                } else {
                    return get(parent, property, receiver);
                }
            } else if ("value" in desc) {
                return desc.value;
            } else {
                var getter = desc.get;
                if (getter === undefined) {
                    return undefined;
                }
                return getter.call(receiver);
            }
        };

        var _frontend5 = __webpack_require__(71);

        var _frontend6 = _interopRequireDefault(_frontend5);

        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {default: obj};
        }

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var ElementorMotionFXFrontend = function (_elementorModules$Vie) {
            _inherits(ElementorMotionFXFrontend, _elementorModules$Vie);

            function ElementorMotionFXFrontend() {
                _classCallCheck(this, ElementorMotionFXFrontend);

                return _possibleConstructorReturn(this, (ElementorMotionFXFrontend.__proto__ || Object.getPrototypeOf(ElementorMotionFXFrontend)).apply(this, arguments));
            }

            _createClass(ElementorMotionFXFrontend, [{
                key: 'onInit',
                value: function onInit() {
                    _get(ElementorMotionFXFrontend.prototype.__proto__ || Object.getPrototypeOf(ElementorMotionFXFrontend.prototype), 'onInit', this).call(this);

                    this.modules = {};
                }
            }, {
                key: 'bindEvents',
                value: function bindEvents() {
                    jQuery(window).on('elementor/frontend/init', this.onElementorFrontendInit.bind(this));
                }
            }, {
                key: 'initModules',
                value: function initModules() {
                    var _this2 = this;

                    var handlers = {
                        motionFX: _frontend6.default,
                        sticky: __webpack_require__(77)
                    };

                    jQuery.each(handlers, function (moduleName, ModuleClass) {
                        _this2.modules[moduleName] = new ModuleClass(jQuery);
                    });
                }
            }, {
                key: 'onElementorFrontendInit',
                value: function onElementorFrontendInit() {
                    this.initModules();
                }
            }]);

            return ElementorMotionFXFrontend;
        }(elementorModules.ViewModule);

        window.elementorMotionFXFrontend = new ElementorMotionFXFrontend();

        /***/
    }),
    /* 71 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _handler = __webpack_require__(72);

        var _handler2 = _interopRequireDefault(_handler);

        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {default: obj};
        }

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var _class = function (_elementorModules$Mod) {
            _inherits(_class, _elementorModules$Mod);

            function _class() {
                _classCallCheck(this, _class);

                var _this = _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).call(this));

                elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($element) {
                    elementorFrontend.elementsHandler.addHandler(_handler2.default, {$element: $element});
                });
                return _this;
            }

            return _class;
        }(elementorModules.Module);

        exports.default = _class;

        /***/
    }),
    /* 72 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
            return typeof obj;
        } : function (obj) {
            return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
        };

        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        var _get = function get(object, property, receiver) {
            if (object === null) object = Function.prototype;
            var desc = Object.getOwnPropertyDescriptor(object, property);
            if (desc === undefined) {
                var parent = Object.getPrototypeOf(object);
                if (parent === null) {
                    return undefined;
                } else {
                    return get(parent, property, receiver);
                }
            } else if ("value" in desc) {
                return desc.value;
            } else {
                var getter = desc.get;
                if (getter === undefined) {
                    return undefined;
                }
                return getter.call(receiver);
            }
        };

        var _motionFx = __webpack_require__(73);

        var _motionFx2 = _interopRequireDefault(_motionFx);

        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {default: obj};
        }

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var _class = function (_elementorModules$fro) {
            _inherits(_class, _elementorModules$fro);

            function _class() {
                _classCallCheck(this, _class);

                return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
            }

            _createClass(_class, [{
                key: '__construct',
                value: function __construct() {
                    var _get2;

                    for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
                        args[_key] = arguments[_key];
                    }

                    (_get2 = _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), '__construct', this)).call.apply(_get2, [this].concat(args));

                    this.toggle = elementorFrontend.debounce(this.toggle, 200);
                }
            }, {
                key: 'bindEvents',
                value: function bindEvents() {
                    elementorFrontend.elements.$window.on('resize', this.toggle);
                }
            }, {
                key: 'unbindEvents',
                value: function unbindEvents() {
                    elementorFrontend.elements.$window.off('resize', this.toggle);
                }
            }, {
                key: 'initEffects',
                value: function initEffects() {
                    this.effects = {
                        translateY: {
                            interaction: 'scroll',
                            actions: ['translateY']
                        },
                        translateX: {
                            interaction: 'scroll',
                            actions: ['translateX']
                        },
                        rotateZ: {
                            interaction: 'scroll',
                            actions: ['rotateZ']
                        },
                        scale: {
                            interaction: 'scroll',
                            actions: ['scale']
                        },
                        opacity: {
                            interaction: 'scroll',
                            actions: ['opacity']
                        },
                        blur: {
                            interaction: 'scroll',
                            actions: ['blur']
                        },
                        mouseTrack: {
                            interaction: 'mouseMove',
                            actions: ['translateXY']
                        },
                        tilt: {
                            interaction: 'mouseMove',
                            actions: ['tilt']
                        }
                    };
                }
            }, {
                key: 'prepareOptions',
                value: function prepareOptions(name) {
                    var _this2 = this;

                    var elementSettings = this.getElementSettings(),
                        type = 'motion_fx' === name ? 'element' : 'background',
                        interactions = {};

                    jQuery.each(elementSettings, function (key, value) {
                        var keyRegex = new RegExp('^' + name + '_(.+?)_effect'),
                            keyMatches = key.match(keyRegex);

                        if (!keyMatches || !value) {
                            return;
                        }

                        var options = {},
                            effectName = keyMatches[1];

                        jQuery.each(elementSettings, function (subKey, subValue) {
                            var subKeyRegex = new RegExp(name + '_' + effectName + '_(.+)'),
                                subKeyMatches = subKey.match(subKeyRegex);

                            if (!subKeyMatches) {
                                return;
                            }

                            var subFieldName = subKeyMatches[1];

                            if ('effect' === subFieldName) {
                                return;
                            }

                            if ('object' === (typeof subValue === 'undefined' ? 'undefined' : _typeof(subValue))) {
                                subValue = Object.keys(subValue.sizes).length ? subValue.sizes : subValue.size;
                            }

                            options[subKeyMatches[1]] = subValue;
                        });

                        var effect = _this2.effects[effectName],
                            interactionName = effect.interaction;

                        if (!interactions[interactionName]) {
                            interactions[interactionName] = {};
                        }

                        effect.actions.forEach(function (action) {
                            return interactions[interactionName][action] = options;
                        });
                    });

                    var $element = this.$element,
                        $dimensionsElement = void 0;

                    var elementType = this.getElementType();

                    if ('element' === type && 'section' !== elementType) {
                        $dimensionsElement = $element;
                        var childElementSelector;

                        if ('column' === elementType) {
                            childElementSelector = elementorFrontend.config.legacyMode.elementWrappers ? '.elementor-column-wrap' : '.elementor-widget-wrap';
                        } else {
                            childElementSelector = '.elementor-widget-container';
                        }

                        $element = $element.find('> ' + childElementSelector);
                    }

                    var options = {
                        type: type,
                        interactions: interactions,
                        $element: $element,
                        $dimensionsElement: $dimensionsElement,
                        refreshDimensions: this.isEdit,
                        range: elementSettings[name + '_range'],
                        classes: {
                            element: 'elementor-motion-effects-element',
                            parent: 'elementor-motion-effects-parent',
                            backgroundType: 'elementor-motion-effects-element-type-background',
                            container: 'elementor-motion-effects-container',
                            layer: 'elementor-motion-effects-layer',
                            perspective: 'elementor-motion-effects-perspective'
                        }
                    };

                    if (!options.range && 'fixed' === this.getCurrentDeviceSetting('_position')) {
                        options.range = 'page';
                    }

                    if ('fixed' === this.getCurrentDeviceSetting('_position')) {
                        options.isFixedPosition = true;
                    }

                    if ('background' === type && 'column' === this.getElementType()) {
                        options.addBackgroundLayerTo = ' > .elementor-element-populated';
                    }

                    return options;
                }
            }, {
                key: 'activate',
                value: function activate(name) {
                    var options = this.prepareOptions(name);

                    if (jQuery.isEmptyObject(options.interactions)) {
                        return;
                    }

                    this[name] = new _motionFx2.default(options);
                }
            }, {
                key: 'deactivate',
                value: function deactivate(name) {
                    if (this[name]) {
                        this[name].destroy();

                        delete this[name];
                    }
                }
            }, {
                key: 'toggle',
                value: function toggle() {
                    var _this3 = this;

                    var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
                        elementSettings = this.getElementSettings();

                    ['motion_fx', 'background_motion_fx'].forEach(function (name) {
                        var devices = elementSettings[name + '_devices'],
                            isCurrentModeActive = !devices || -1 !== devices.indexOf(currentDeviceMode);

                        if (isCurrentModeActive && (elementSettings[name + '_motion_fx_scrolling'] || elementSettings[name + '_motion_fx_mouse'])) {
                            if (_this3[name]) {
                                _this3.refreshInstance(name);
                            } else {
                                _this3.activate(name);
                            }
                        } else {
                            _this3.deactivate(name);
                        }
                    });
                }
            }, {
                key: 'refreshInstance',
                value: function refreshInstance(instanceName) {
                    var instance = this[instanceName];

                    if (!instance) {
                        return;
                    }

                    var preparedOptions = this.prepareOptions(instanceName);

                    instance.setSettings(preparedOptions);

                    instance.refresh();
                }
            }, {
                key: 'onInit',
                value: function onInit() {
                    _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'onInit', this).call(this);

                    this.initEffects();

                    this.toggle();
                }
            }, {
                key: 'onElementChange',
                value: function onElementChange(propertyName) {
                    var _this4 = this;

                    if (/motion_fx_((scrolling)|(mouse)|(devices))$/.test(propertyName)) {
                        this.toggle();

                        return;
                    }

                    var propertyMatches = propertyName.match('.*?motion_fx');

                    if (propertyMatches) {
                        var instanceName = propertyMatches[0];

                        this.refreshInstance(instanceName);

                        if (!this[instanceName]) {
                            this.activate(instanceName);
                        }
                    }

                    if (/^_position/.test(propertyName)) {
                        ['motion_fx', 'background_motion_fx'].forEach(function (instanceName) {
                            _this4.refreshInstance(instanceName);
                        });
                    }
                }
            }, {
                key: 'onDestroy',
                value: function onDestroy() {
                    var _this5 = this;

                    _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'onDestroy', this).call(this);

                    ['motion_fx', 'background_motion_fx'].forEach(function (name) {
                        _this5.deactivate(name);
                    });
                }
            }]);

            return _class;
        }(elementorModules.frontend.handlers.Base);

        exports.default = _class;

        /***/
    }),
    /* 73 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        var _get = function get(object, property, receiver) {
            if (object === null) object = Function.prototype;
            var desc = Object.getOwnPropertyDescriptor(object, property);
            if (desc === undefined) {
                var parent = Object.getPrototypeOf(object);
                if (parent === null) {
                    return undefined;
                } else {
                    return get(parent, property, receiver);
                }
            } else if ("value" in desc) {
                return desc.value;
            } else {
                var getter = desc.get;
                if (getter === undefined) {
                    return undefined;
                }
                return getter.call(receiver);
            }
        };

        var _scroll = __webpack_require__(74);

        var _scroll2 = _interopRequireDefault(_scroll);

        var _mouseMove = __webpack_require__(75);

        var _mouseMove2 = _interopRequireDefault(_mouseMove);

        var _actions2 = __webpack_require__(76);

        var _actions3 = _interopRequireDefault(_actions2);

        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {default: obj};
        }

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var _class = function (_elementorModules$Vie) {
            _inherits(_class, _elementorModules$Vie);

            function _class() {
                _classCallCheck(this, _class);

                return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
            }

            _createClass(_class, [{
                key: 'getDefaultSettings',
                value: function getDefaultSettings() {
                    return {
                        type: 'element',
                        $element: null,
                        $dimensionsElement: null,
                        addBackgroundLayerTo: null,
                        interactions: {},
                        refreshDimensions: false,
                        range: 'viewport',
                        classes: {
                            element: 'motion-fx-element',
                            parent: 'motion-fx-parent',
                            backgroundType: 'motion-fx-element-type-background',
                            container: 'motion-fx-container',
                            layer: 'motion-fx-layer',
                            perspective: 'motion-fx-perspective'
                        }
                    };
                }
            }, {
                key: 'bindEvents',
                value: function bindEvents() {
                    this.onWindowResize = this.onWindowResize.bind(this);

                    elementorFrontend.elements.$window.on('resize', this.onWindowResize);
                }
            }, {
                key: 'unbindEvents',
                value: function unbindEvents() {
                    elementorFrontend.elements.$window.off('resize', this.onWindowResize);
                }
            }, {
                key: 'addBackgroundLayer',
                value: function addBackgroundLayer() {
                    var settings = this.getSettings();

                    this.elements.$motionFXContainer = jQuery('<div>', {class: settings.classes.container});

                    this.elements.$motionFXLayer = jQuery('<div>', {class: settings.classes.layer});

                    this.updateBackgroundLayerSize();

                    this.elements.$motionFXContainer.prepend(this.elements.$motionFXLayer);

                    var $addBackgroundLayerTo = settings.addBackgroundLayerTo ? this.$element.find(settings.addBackgroundLayerTo) : this.$element;

                    $addBackgroundLayerTo.prepend(this.elements.$motionFXContainer);
                }
            }, {
                key: 'removeBackgroundLayer',
                value: function removeBackgroundLayer() {
                    this.elements.$motionFXContainer.remove();
                }
            }, {
                key: 'updateBackgroundLayerSize',
                value: function updateBackgroundLayerSize() {
                    var settings = this.getSettings(),
                        speed = {
                            x: 0,
                            y: 0
                        },
                        mouseInteraction = settings.interactions.mouseMove,
                        scrollInteraction = settings.interactions.scroll;

                    if (mouseInteraction && mouseInteraction.translateXY) {
                        speed.x = mouseInteraction.translateXY.speed * 10;
                        speed.y = mouseInteraction.translateXY.speed * 10;
                    }

                    if (scrollInteraction) {
                        if (scrollInteraction.translateX) {
                            speed.x = scrollInteraction.translateX.speed * 10;
                        }

                        if (scrollInteraction.translateY) {
                            speed.y = scrollInteraction.translateY.speed * 10;
                        }
                    }

                    this.elements.$motionFXLayer.css({
                        width: 100 + speed.x + '%',
                        height: 100 + speed.y + '%'
                    });
                }
            }, {
                key: 'defineDimensions',
                value: function defineDimensions() {
                    var $dimensionsElement = this.getSettings('$dimensionsElement') || this.$element,
                        elementOffset = $dimensionsElement.offset();

                    var dimensions = {
                        elementHeight: $dimensionsElement.outerHeight(),
                        elementWidth: $dimensionsElement.outerWidth(),
                        elementTop: elementOffset.top,
                        elementLeft: elementOffset.left
                    };

                    dimensions.elementRange = dimensions.elementHeight + innerHeight;

                    this.setSettings('dimensions', dimensions);

                    if ('background' === this.getSettings('type')) {
                        this.defineBackgroundLayerDimensions();
                    }
                }
            }, {
                key: 'defineBackgroundLayerDimensions',
                value: function defineBackgroundLayerDimensions() {
                    var dimensions = this.getSettings('dimensions');

                    dimensions.layerHeight = this.elements.$motionFXLayer.height();
                    dimensions.layerWidth = this.elements.$motionFXLayer.width();
                    dimensions.movableX = dimensions.layerWidth - dimensions.elementWidth;
                    dimensions.movableY = dimensions.layerHeight - dimensions.elementHeight;

                    this.setSettings('dimensions', dimensions);
                }
            }, {
                key: 'initInteractionsTypes',
                value: function initInteractionsTypes() {
                    this.interactionsTypes = {
                        scroll: _scroll2.default,
                        mouseMove: _mouseMove2.default
                    };
                }
            }, {
                key: 'prepareSpecialActions',
                value: function prepareSpecialActions() {
                    var settings = this.getSettings(),
                        hasTiltEffect = !!(settings.interactions.mouseMove && settings.interactions.mouseMove.tilt);

                    this.elements.$parent.toggleClass(settings.classes.perspective, hasTiltEffect);
                }
            }, {
                key: 'cleanSpecialActions',
                value: function cleanSpecialActions() {
                    var settings = this.getSettings();

                    this.elements.$parent.removeClass(settings.classes.perspective);
                }
            }, {
                key: 'runInteractions',
                value: function runInteractions() {
                    var _this2 = this;

                    var settings = this.getSettings();

                    this.prepareSpecialActions();

                    jQuery.each(settings.interactions, function (interactionName, actions) {
                        _this2.interactions[interactionName] = new _this2.interactionsTypes[interactionName]({
                            motionFX: _this2,
                            callback: function callback() {
                                for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
                                    args[_key] = arguments[_key];
                                }

                                jQuery.each(actions, function (actionName, actionData) {
                                    var _actions;

                                    return (_actions = _this2.actions).runAction.apply(_actions, [actionName, actionData].concat(args));
                                });
                            }
                        });

                        _this2.interactions[interactionName].runImmediately();
                    });
                }
            }, {
                key: 'destroyInteractions',
                value: function destroyInteractions() {
                    this.cleanSpecialActions();

                    jQuery.each(this.interactions, function (interactionName, interaction) {
                        return interaction.destroy();
                    });

                    this.interactions = {};
                }
            }, {
                key: 'refresh',
                value: function refresh() {
                    this.actions.setSettings(this.getSettings());

                    if ('background' === this.getSettings('type')) {
                        this.updateBackgroundLayerSize();

                        this.defineBackgroundLayerDimensions();
                    }

                    this.actions.refresh();

                    this.destroyInteractions();

                    this.runInteractions();
                }
            }, {
                key: 'destroy',
                value: function destroy() {
                    this.destroyInteractions();

                    this.actions.refresh();

                    var settings = this.getSettings();

                    this.$element.removeClass(settings.classes.element);

                    this.elements.$parent.removeClass(settings.classes.parent);

                    if ('background' === settings.type) {
                        this.$element.removeClass(settings.classes.backgroundType);

                        this.removeBackgroundLayer();
                    }
                }
            }, {
                key: 'onInit',
                value: function onInit() {
                    _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'onInit', this).call(this);

                    var settings = this.getSettings();

                    this.$element = settings.$element;

                    this.elements.$parent = this.$element.parent();

                    this.$element.addClass(settings.classes.element);

                    this.elements.$parent = this.$element.parent();

                    this.elements.$parent.addClass(settings.classes.parent);

                    if ('background' === settings.type) {
                        this.$element.addClass(settings.classes.backgroundType);

                        this.addBackgroundLayer();
                    }

                    this.defineDimensions();

                    settings.$targetElement = 'element' === settings.type ? this.$element : this.elements.$motionFXLayer;

                    this.interactions = {};

                    this.actions = new _actions3.default(settings);

                    this.initInteractionsTypes();

                    this.runInteractions();
                }
            }, {
                key: 'onWindowResize',
                value: function onWindowResize() {
                    this.defineDimensions();
                }
            }]);

            return _class;
        }(elementorModules.ViewModule);

        exports.default = _class;

        /***/
    }),
    /* 74 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        var _base = __webpack_require__(5);

        var _base2 = _interopRequireDefault(_base);

        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {default: obj};
        }

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var _class = function (_BaseInteraction) {
            _inherits(_class, _BaseInteraction);

            function _class() {
                _classCallCheck(this, _class);

                return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
            }

            _createClass(_class, [{
                key: "run",
                value: function run() {
                    if (pageYOffset === this.windowScrollTop) {
                        return false;
                    }

                    this.onScrollMovement();
                    this.windowScrollTop = pageYOffset;
                }
            }, {
                key: "onScrollMovement",
                value: function onScrollMovement() {
                    this.updateMotionFxDimensions();
                    this.updateAnimation();
                }
            }, {
                key: "updateMotionFxDimensions",
                value: function updateMotionFxDimensions() {
                    var motionFXSettings = this.motionFX.getSettings();

                    if (motionFXSettings.refreshDimensions) {
                        this.motionFX.defineDimensions();
                    }
                }
            }, {
                key: "updateAnimation",
                value: function updateAnimation() {
                    var passedRangePercents;

                    var motionFXSettings = this.motionFX.getSettings();

                    if ('page' === this.motionFX.getSettings('range')) {
                        passedRangePercents = document.documentElement.scrollTop / (document.body.scrollHeight - innerHeight) * 100;
                    }
                    else if (this.motionFX.getSettings('isFixedPosition')) {
                        passedRangePercents = elementorModules.utils.Scroll.getPageScrollPercentage({}, window.innerHeight);
                    }
                    else {
                        var dimensions = motionFXSettings.dimensions,
                            elementTopWindowPoint = dimensions.elementTop - pageYOffset,
                            elementEntrancePoint = elementTopWindowPoint - innerHeight;

                        passedRangePercents = 100 / dimensions.elementRange * (elementEntrancePoint * -1);
                    }
                    this.runCallback(passedRangePercents);
                }
            }]);

            return _class;
        }(_base2.default);

        exports.default = _class;

        /***/
    }),
    /* 75 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        var _get = function get(object, property, receiver) {
            if (object === null) object = Function.prototype;
            var desc = Object.getOwnPropertyDescriptor(object, property);
            if (desc === undefined) {
                var parent = Object.getPrototypeOf(object);
                if (parent === null) {
                    return undefined;
                } else {
                    return get(parent, property, receiver);
                }
            } else if ("value" in desc) {
                return desc.value;
            } else {
                var getter = desc.get;
                if (getter === undefined) {
                    return undefined;
                }
                return getter.call(receiver);
            }
        };

        var _base = __webpack_require__(5);

        var _base2 = _interopRequireDefault(_base);

        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {default: obj};
        }

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var MouseMoveInteraction = function (_BaseInteraction) {
            _inherits(MouseMoveInteraction, _BaseInteraction);

            function MouseMoveInteraction() {
                _classCallCheck(this, MouseMoveInteraction);

                return _possibleConstructorReturn(this, (MouseMoveInteraction.__proto__ || Object.getPrototypeOf(MouseMoveInteraction)).apply(this, arguments));
            }

            _createClass(MouseMoveInteraction, [{
                key: 'bindEvents',
                value: function bindEvents() {
                    if (!MouseMoveInteraction.mouseTracked) {
                        elementorFrontend.elements.$window.on('mousemove', MouseMoveInteraction.updateMousePosition);

                        MouseMoveInteraction.mouseTracked = true;
                    }
                }
            }, {
                key: 'run',
                value: function run() {
                    var mousePosition = MouseMoveInteraction.mousePosition,
                        oldMousePosition = this.oldMousePosition;

                    if (oldMousePosition.x === mousePosition.x && oldMousePosition.y === mousePosition.y) {
                        return;
                    }

                    this.oldMousePosition = {
                        x: mousePosition.x,
                        y: mousePosition.y
                    };

                    var passedPercentsX = 100 / innerWidth * mousePosition.x,
                        passedPercentsY = 100 / innerHeight * mousePosition.y;

                    this.runCallback(passedPercentsX, passedPercentsY);
                }
            }, {
                key: 'onInit',
                value: function onInit() {
                    this.oldMousePosition = {};

                    _get(MouseMoveInteraction.prototype.__proto__ || Object.getPrototypeOf(MouseMoveInteraction.prototype), 'onInit', this).call(this);
                }
            }]);

            return MouseMoveInteraction;
        }(_base2.default);

        exports.default = MouseMoveInteraction;


        MouseMoveInteraction.mousePosition = {};

        MouseMoveInteraction.updateMousePosition = function (event) {
            MouseMoveInteraction.mousePosition = {
                x: event.clientX,
                y: event.clientY
            };
        };

        /***/
    }),
    /* 76 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor;
            };
        }();

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _possibleConstructorReturn(self, call) {
            if (!self) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return call && (typeof call === "object" || typeof call === "function") ? call : self;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {
                constructor: {
                    value: subClass,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });
            if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
        }

        var _class = function (_elementorModules$Mod) {
            _inherits(_class, _elementorModules$Mod);

            function _class() {
                _classCallCheck(this, _class);

                return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
            }

            _createClass(_class, [{
                key: 'getMovePointFromPassedPercents',
                value: function getMovePointFromPassedPercents(movableRange, passedPercents) {
                    var movePoint = passedPercents / movableRange * 100;

                    return +movePoint.toFixed(2);
                }
            }, {
                key: 'getEffectValueFromMovePoint',
                value: function getEffectValueFromMovePoint(range, movePoint) {
                    return range * movePoint / 100;
                }
            }, {
                key: 'getStep',
                value: function getStep(passedPercents, options) {
                    if ('element' === this.getSettings('type')) {
                        return this.getElementStep(passedPercents, options);
                    }

                    return this.getBackgroundStep(passedPercents, options);
                }
            }, {
                key: 'getElementStep',
                value: function getElementStep(passedPercents, options) {
                    return -(passedPercents - 50) * options.speed;
                }
            }, {
                key: 'getBackgroundStep',
                value: function getBackgroundStep(passedPercents, options) {
                    var movableRange = this.getSettings('dimensions.movable' + options.axis.toUpperCase());

                    return -this.getEffectValueFromMovePoint(movableRange, passedPercents);
                }
            }, {
                key: 'getDirectionMovePoint',
                value: function getDirectionMovePoint(passedPercents, direction, range) {
                    var movePoint = void 0;

                    if (passedPercents < range.start) {
                        if ('out-in' === direction) {
                            movePoint = 0;
                        } else if ('in-out' === direction) {
                            movePoint = 100;
                        } else {
                            movePoint = this.getMovePointFromPassedPercents(range.start, passedPercents);

                            if ('in-out-in' === direction) {
                                movePoint = 100 - movePoint;
                            }
                        }
                    } else if (passedPercents < range.end) {
                        if ('in-out-in' === direction) {
                            movePoint = 0;
                        } else if ('out-in-out' === direction) {
                            movePoint = 100;
                        } else {
                            movePoint = this.getMovePointFromPassedPercents(range.end - range.start, passedPercents - range.start);

                            if ('in-out' === direction) {
                                movePoint = 100 - movePoint;
                            }
                        }
                    } else if ('in-out' === direction) {
                        movePoint = 0;
                    } else if ('out-in' === direction) {
                        movePoint = 100;
                    } else {
                        movePoint = this.getMovePointFromPassedPercents(100 - range.end, 100 - passedPercents);

                        if ('in-out-in' === direction) {
                            movePoint = 100 - movePoint;
                        }
                    }

                    return movePoint;
                }
            }, {
                key: 'translateX',
                value: function translateX(actionData, passedPercents) {
                    actionData.axis = 'x';
                    actionData.unit = 'px';

                    this.transform('translateX', passedPercents, actionData);
                }
            }, {
                key: 'translateY',
                value: function translateY(actionData, passedPercents) {
                    actionData.axis = 'y';
                    actionData.unit = 'px';

                    this.transform('translateY', passedPercents, actionData);
                }
            }, {
                key: 'translateXY',
                value: function translateXY(actionData, passedPercentsX, passedPercentsY) {
                    this.translateX(actionData, passedPercentsX);

                    this.translateY(actionData, passedPercentsY);
                }
            }, {
                key: 'tilt',
                value: function tilt(actionData, passedPercentsX, passedPercentsY) {
                    var options = {
                        speed: actionData.speed / 10,
                        direction: actionData.direction
                    };

                    this.rotateX(options, passedPercentsY);

                    this.rotateY(options, 100 - passedPercentsX);
                }
            }, {
                key: 'rotateX',
                value: function rotateX(actionData, passedPercents) {
                    actionData.axis = 'x';
                    actionData.unit = 'deg';

                    this.transform('rotateX', passedPercents, actionData);
                }
            }, {
                key: 'rotateY',
                value: function rotateY(actionData, passedPercents) {
                    actionData.axis = 'y';
                    actionData.unit = 'deg';

                    this.transform('rotateY', passedPercents, actionData);
                }
            }, {
                key: 'rotateZ',
                value: function rotateZ(actionData, passedPercents) {
                    actionData.unit = 'deg';

                    this.transform('rotateZ', passedPercents, actionData);
                }
            }, {
                key: 'scale',
                value: function scale(actionData, passedPercents) {
                    var movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range);

                    this.updateRulePart('transform', 'scale', 1 + actionData.speed * movePoint / 1000);
                }
            }, {
                key: 'transform',
                value: function transform(action, passedPercents, actionData) {
                    if (actionData.direction) {
                        passedPercents = 100 - passedPercents;
                    }

                    this.updateRulePart('transform', action, this.getStep(passedPercents, actionData) + actionData.unit);
                }
            }, {
                key: 'opacity',
                value: function opacity(actionData, passedPercents) {
                    var movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range),
                        level = actionData.level / 10,
                        opacity = 1 - level + this.getEffectValueFromMovePoint(level, movePoint);

                    this.$element.css('opacity', opacity);
                }
            }, {
                key: 'blur',
                value: function blur(actionData, passedPercents) {
                    var movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range),
                        blur = actionData.level - this.getEffectValueFromMovePoint(actionData.level, movePoint);

                    this.updateRulePart('filter', 'blur', blur + 'px');
                }
            }, {
                key: 'updateRulePart',
                value: function updateRulePart(ruleName, key, value) {
                    if (!this.rulesVariables[ruleName]) {
                        this.rulesVariables[ruleName] = {};
                    }

                    if (!this.rulesVariables[ruleName][key]) {
                        this.rulesVariables[ruleName][key] = true;

                        this.updateRule(ruleName);
                    }

                    var cssVarKey = '--' + key;

                    this.$element[0].style.setProperty(cssVarKey, value);
                }
            }, {
                key: 'updateRule',
                value: function updateRule(ruleName) {
                    var value = '';

                    jQuery.each(this.rulesVariables[ruleName], function (variableKey) {
                        value += variableKey + '(var(--' + variableKey + '))';
                    });

                    this.$element.css(ruleName, value);
                }
            }, {
                key: 'runAction',
                value: function runAction(actionName, actionData, passedPercents) {
                    if (actionData.affectedRange) {
                        if (actionData.affectedRange.start > passedPercents) {
                            passedPercents = actionData.affectedRange.start;
                        }

                        if (actionData.affectedRange.end < passedPercents) {
                            passedPercents = actionData.affectedRange.end;
                        }
                    }

                    for (var _len = arguments.length, args = Array(_len > 3 ? _len - 3 : 0), _key = 3; _key < _len; _key++) {
                        args[_key - 3] = arguments[_key];
                    }

                    this[actionName].apply(this, [actionData, passedPercents].concat(args));
                }
            }, {
                key: 'refresh',
                value: function refresh() {
                    this.rulesVariables = {};

                    this.$element.css({
                        transform: '',
                        filter: '',
                        opacity: ''
                    });
                }
            }, {
                key: 'onInit',
                value: function onInit() {
                    this.$element = this.getSettings('$targetElement');

                    this.refresh();
                }
            }]);

            return _class;
        }(elementorModules.Module);

        exports.default = _class;

        /***/
    }),
    /* 77 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";

        module.exports = function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', __webpack_require__(10));
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', __webpack_require__(10));
            elementorFrontend.hooks.addAction('frontend/element_ready/container', __webpack_require__(10));
        };

        /***/
    })
    /******/]);
