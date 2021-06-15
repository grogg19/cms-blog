/*!-----------------------------------------------------------------
    Name: AmDesk - Help Center HTML template for your digital products
    Version: 1.0.1
    Author: dexad, nK
    Website: https://nkdev.info/
    Purchase: https://themeforest.net/user/_nk/portfolio
    Support: https://nk.ticksy.com/
    License: You must have a valid license purchased only from ThemeForest (the above link) in order to legally use the theme for your project.
    Copyright 2019.
-------------------------------------------------------------------*/
/******/ (function(modules) { // webpackBootstrap
    /******/ 	// The module cache
    /******/ 	var installedModules = {};
    /******/
    /******/ 	// The require function
    /******/ 	function __webpack_require__(moduleId) {
        /******/
        /******/
        /******/ 		// Check if module is in cache
        /******/ 		if(installedModules[moduleId]) {
            /******/ 			return installedModules[moduleId].exports;
            /******/ 		}
        /******/ 		// Create a new module (and put it into the cache)
        /******/ 		var module = installedModules[moduleId] = {
            /******/ 			i: moduleId,
            /******/ 			l: false,
            /******/ 			exports: {}
            /******/ 		};
        /******/
        /******/ 		// Execute the module function
        /******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ 		// Flag the module as loaded
        /******/ 		module.l = true;
        /******/
        /******/ 		// Return the exports of the module
        /******/ 		return module.exports;
        /******/ 	}
    /******/
    /******/
    /******/ 	// expose the modules object (__webpack_modules__)
    /******/ 	__webpack_require__.m = modules;
    /******/
    /******/ 	// expose the module cache
    /******/ 	__webpack_require__.c = installedModules;
    /******/
    /******/ 	// define getter function for harmony exports
    /******/ 	__webpack_require__.d = function(exports, name, getter) {
        /******/ 		if(!__webpack_require__.o(exports, name)) {
            /******/ 			Object.defineProperty(exports, name, {
                /******/ 				configurable: false,
                /******/ 				enumerable: true,
                /******/ 				get: getter
                /******/ 			});
            /******/ 		}
        /******/ 	};
    /******/
    /******/ 	// getDefaultExport function for compatibility with non-harmony modules
    /******/ 	__webpack_require__.n = function(module) {
        /******/ 		var getter = module && module.__esModule ?
            /******/ 			function getDefault() { return module['default']; } :
            /******/ 			function getModuleExports() { return module; };
        /******/ 		__webpack_require__.d(getter, 'a', getter);
        /******/ 		return getter;
        /******/ 	};
    /******/
    /******/ 	// Object.prototype.hasOwnProperty.call
    /******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
    /******/
    /******/ 	// __webpack_public_path__
    /******/ 	__webpack_require__.p = "";
    /******/
    /******/ 	// Load entry module and return exports
    /******/ 	return __webpack_require__(__webpack_require__.s = 4);
    /******/ })
/************************************************************************/
/******/ ([
    /* 0 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        /*------------------------------------------------------------------

          Utility

        -------------------------------------------------------------------*/
        var $ = jQuery;
        var tween = window.TweenMax;
        var isIOs = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        var isMobile = /Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/g.test(navigator.userAgent || navigator.vendor || window.opera);
        var isFireFox = typeof InstallTrigger !== 'undefined';
        var isTouch = 'ontouchstart' in window || window.DocumentTouch && document instanceof DocumentTouch;

// add 'is-mobile' or 'is-desktop' classname to html tag
        $('html').addClass(isMobile ? 'is-mobile' : 'is-desktop');

        /**
         * window size
         */
        var $wnd = $(window);
        var $doc = $(document);
        var $body = $('body');
        var wndW = 0;
        var wndH = 0;
        var docH = 0;
        function getWndSize() {
            exports.wndW = wndW = $wnd.width();
            exports.wndH = wndH = $wnd.height();
            exports.docH = docH = $doc.height();
        }
        getWndSize();
        $wnd.on('resize load orientationchange', getWndSize);

        /**
         * Debounce resize
         */
        var resizeArr = [];
        var resizeTimeout = void 0;
        function debounceResized() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function () {
                if (resizeArr.length) {
                    for (var k = 0; k < resizeArr.length; k++) {
                        resizeArr[k]();
                    }
                }
            }, 50);
        }
        $wnd.on('ready load resize orientationchange', debounceResized);
        debounceResized();

        function debounceResize(func) {
            if (typeof func === 'function') {
                resizeArr.push(func);
            } else {
                window.dispatchEvent(new Event('resize'));
            }
        }

        /**
         * Throttle scroll
         * thanks: https://jsfiddle.net/mariusc23/s6mLJ/31/
         */
        var hideOnScrollList = [];
        var didScroll = void 0;
        var lastST = 0;

        $wnd.on('scroll load resize orientationchange', function () {
            if (hideOnScrollList.length) {
                didScroll = true;
            }
        });

        function hasScrolled() {
            var ST = $wnd.scrollTop();

            var type = ''; // [up, down, end, start]

            if (ST > lastST) {
                type = 'down';
            } else if (ST < lastST) {
                type = 'up';
            } else {
                type = 'none';
            }

            if (ST === 0) {
                type = 'start';
            } else if (ST >= docH - wndH) {
                type = 'end';
            }

            hideOnScrollList.forEach(function (item) {
                if (typeof item === 'function') {
                    item(type, ST, lastST, $wnd);
                }
            });

            lastST = ST;
        }

        setInterval(function () {
            if (didScroll) {
                didScroll = false;
                window.requestAnimationFrame(hasScrolled);
            }
        }, 250);

        function throttleScroll(callback) {
            hideOnScrollList.push(callback);
        }

        /**
         * Body Overflow
         * Thanks https://jsfiddle.net/mariusc23/s6mLJ/31/
         * Usage:
         *    // enable
         *    bodyOverflow(1);
         *
         *    // disable
         *    bodyOverflow(0);
         */
        var bodyOverflowEnabled = void 0;
        var isBodyOverflowing = void 0;
        var scrollbarWidth = void 0;
        var originalBodyPadding = void 0;
        var $headerContent = $('.dx-header > *');
        function isBodyOverflowed() {
            return bodyOverflowEnabled;
        }
        function bodyGetScrollbarWidth() {
            // thx d.walsh
            var scrollDiv = document.createElement('div');
            scrollDiv.className = 'dx-body-scrollbar-measure';
            $body[0].appendChild(scrollDiv);
            var result = scrollDiv.offsetWidth - scrollDiv.clientWidth;
            $body[0].removeChild(scrollDiv);
            return result;
        }
        function bodyCheckScrollbar() {
            var fullWindowWidth = window.innerWidth;
            if (!fullWindowWidth) {
                // workaround for missing window.innerWidth in IE8
                var documentElementRect = document.documentElement.getBoundingClientRect();
                fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left);
            }
            isBodyOverflowing = $body[0].clientWidth < fullWindowWidth;
            scrollbarWidth = bodyGetScrollbarWidth();
        }
        function bodySetScrollbar() {
            if (typeof originalBodyPadding === 'undefined') {
                originalBodyPadding = $body.css('padding-right') || '';
            }

            if (isBodyOverflowing) {
                $body.add($headerContent).css('paddingRight', scrollbarWidth + 'px');
            }
        }
        function bodyResetScrollbar() {
            $body.css('paddingRight', originalBodyPadding);
            $headerContent.css('paddingRight', '');
        }
        function bodyOverflow(enable) {
            if (enable && !bodyOverflowEnabled) {
                bodyOverflowEnabled = 1;
                bodyCheckScrollbar();
                bodySetScrollbar();
                $body.css('overflow', 'hidden');
            } else if (!enable && bodyOverflowEnabled) {
                bodyOverflowEnabled = 0;
                $body.css('overflow', '');
                bodyResetScrollbar();
            }
        }

        /**
         * In Viewport checker
         * return visible percent from 0 to 1
         */
        function isInViewport($item, returnRect) {
            var rect = $item[0].getBoundingClientRect();
            var result = 1;

            if (rect.right <= 0 || rect.left >= wndW) {
                result = 0;
            } else if (rect.bottom < 0 && rect.top <= wndH) {
                result = 0;
            } else {
                var beforeTopEnd = Math.max(0, rect.height + rect.top);
                var beforeBottomEnd = Math.max(0, rect.height - (rect.top + rect.height - wndH));
                var afterTop = Math.max(0, -rect.top);
                var beforeBottom = Math.max(0, rect.top + rect.height - wndH);
                if (rect.height < wndH) {
                    result = 1 - (afterTop || beforeBottom) / rect.height;
                } else if (beforeTopEnd <= wndH) {
                    result = beforeTopEnd / wndH;
                } else if (beforeBottomEnd <= wndH) {
                    result = beforeBottomEnd / wndH;
                }
                result = result < 0 ? 0 : result;
            }
            if (returnRect) {
                return [result, rect];
            }
            return result;
        }

        /**
         * Scroll To
         */
        function scrollTo($to, callback) {
            var scrollPos = false;
            var speed = this.options.scrollToAnchorSpeed / 1000;

            if ($to === 'top') {
                scrollPos = 0;
            } else if ($to === 'bottom') {
                scrollPos = Math.max(0, docH - wndH);
            } else if (typeof $to === 'number') {
                scrollPos = $to;
            } else {
                scrollPos = $to.offset ? $to.offset().top : false;
            }

            if (scrollPos !== false && $wnd.scrollTop() !== scrollPos) {
                tween.to($wnd, speed, {
                    scrollTo: {
                        y: scrollPos,

                        // disable autokill on iOs (buggy scrolling)
                        autoKill: !isIOs
                    },
                    ease: Power2.easeOut,
                    overwrite: 5
                });
                if (callback) {
                    tween.delayedCall(speed, callback);
                }
            } else if (typeof callback === 'function') {
                callback();
            }
        }

        exports.$ = $;
        exports.tween = tween;
        exports.isIOs = isIOs;
        exports.isMobile = isMobile;
        exports.isFireFox = isFireFox;
        exports.isTouch = isTouch;
        exports.$wnd = $wnd;
        exports.$doc = $doc;
        exports.$body = $body;
        exports.wndW = wndW;
        exports.wndH = wndH;
        exports.docH = docH;
        exports.debounceResize = debounceResize;
        exports.throttleScroll = throttleScroll;
        exports.bodyOverflow = bodyOverflow;
        exports.isBodyOverflowed = isBodyOverflowed;
        exports.isInViewport = isInViewport;
        exports.scrollTo = scrollTo;

        /***/ }),
    /* 1 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        /*------------------------------------------------------------------

          Theme Options

        -------------------------------------------------------------------*/
        var options = {
            parallaxSpeed: 0.8,
            scrollToAnchorSpeed: 700,

            templates: {
                btnLoaded: 'All shown',

                instagram: '<div class="col-4">\n                <a href="{{link}}" target="_blank">\n                    <img src="{{image}}" alt="{{caption}}" class="dx-img-stretch">\n                </a>\n            </div>',
                instagramLoadingText: 'Loading...',
                instagramFailText: 'Failed to fetch data',
                instagramApiPath: 'php/instagram/instagram.php',

                twitter: '<div class="dx-widget-twitter">\n                <div class="dx-widget-text">\n                {{tweet}}\n                </div>\n                <div class="dx-widget-twitter-date">\n                    <span>{{date}}</span>\n                </div>\n            </div>',
                twitterLoadingText: 'Loading...',
                twitterFailText: 'Failed to fetch data',
                twitterApiPath: 'php/twitter/tweet.php'
            }
        };

        exports.options = options;

        /***/ }),
    /* 2 */,
    /* 3 */,
    /* 4 */
    /***/ (function(module, exports, __webpack_require__) {

        module.exports = __webpack_require__(5);


        /***/ }),
    /* 5 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

        /* Plugins */


        var _options = __webpack_require__(1);

        var _utility = __webpack_require__(0);

        var _setOptions2 = __webpack_require__(6);

        var _initNavbar2 = __webpack_require__(7);

        var _initDropdown2 = __webpack_require__(8);

        var _initBtnLoad2 = __webpack_require__(9);

        var _initForm2 = __webpack_require__(10);

        var _initTwitter2 = __webpack_require__(11);

        var _initPluginStickySidebar2 = __webpack_require__(12);

        var _initPluginCleave2 = __webpack_require__(13);

        var _initPluginImagesLoaded2 = __webpack_require__(14);

        var _initPluginIsotope2 = __webpack_require__(15);

        var _initPluginJarallax2 = __webpack_require__(16);

        var _initPluginSwiper2 = __webpack_require__(17);

        var _initPluginOFI2 = __webpack_require__(18);

        var _initPluginSelectize2 = __webpack_require__(19);

        var _initPluginQuill2 = __webpack_require__(20);

        var _initPluginDropzone2 = __webpack_require__(21);

        function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

        /*------------------------------------------------------------------

          Amdesk Class

        -------------------------------------------------------------------*/
        var AMDESK = function () {
            function AMDESK() {
                _classCallCheck(this, AMDESK);

                this.options = _options.options;
            }

            _createClass(AMDESK, [{
                key: 'init',
                value: function init() {
                    // prt:sc:dm
                    this.initNavbar();
                    this.initDropdown();
                    this.initBtnLoad();
                    this.initForm();
                    this.initTwitter();
                    this.initPluginJarallax();
                    this.initPluginStickySidebar();
                    this.initPluginCleave();
                    this.initPluginImagesLoaded();
                    this.initPluginIsotope();
                    this.initPluginSwiper();
                    this.initPluginOFI();
                    this.initPluginSelectize();
                    this.initPluginQuill();
                    this.initPluginDropzone();

                    return this;
                }
            }, {
                key: 'setOptions',
                value: function setOptions(newOpts) {
                    return _setOptions2.setOptions.call(this, newOpts);
                }
            }, {
                key: 'debounceResize',
                value: function debounceResize(func) {
                    return _utility.debounceResize.call(this, func);
                }
            }, {
                key: 'throttleScroll',
                value: function throttleScroll(callback) {
                    return _utility.throttleScroll.call(this, callback);
                }
            }, {
                key: 'bodyOverflow',
                value: function bodyOverflow(type) {
                    return _utility.bodyOverflow.call(this, type);
                }
            }, {
                key: 'isInViewport',
                value: function isInViewport($item, returnRect) {
                    return _utility.isInViewport.call(this, $item, returnRect);
                }
            }, {
                key: 'scrollTo',
                value: function scrollTo($to, callback) {
                    return _utility.scrollTo.call(this, $to, callback);
                }
            }, {
                key: 'initNavbar',
                value: function initNavbar() {
                    return _initNavbar2.initNavbar.call(this);
                }
            }, {
                key: 'initDropdown',
                value: function initDropdown() {
                    return _initDropdown2.initDropdown.call(this);
                }
            }, {
                key: 'initBtnLoad',
                value: function initBtnLoad() {
                    return _initBtnLoad2.initBtnLoad.call(this);
                }
            }, {
                key: 'initForm',
                value: function initForm() {
                    return _initForm2.initForm.call(this);
                }
            }, {
                key: 'initTwitter',
                value: function initTwitter() {
                    return _initTwitter2.initTwitter.call(this);
                }
            }, {
                key: 'initPluginStickySidebar',
                value: function initPluginStickySidebar() {
                    return _initPluginStickySidebar2.initPluginStickySidebar.call(this);
                }
            }, {
                key: 'initPluginCleave',
                value: function initPluginCleave() {
                    return _initPluginCleave2.initPluginCleave.call(this);
                }
            }, {
                key: 'initPluginImagesLoaded',
                value: function initPluginImagesLoaded() {
                    return _initPluginImagesLoaded2.initPluginImagesLoaded.call(this);
                }
            }, {
                key: 'initPluginIsotope',
                value: function initPluginIsotope() {
                    return _initPluginIsotope2.initPluginIsotope.call(this);
                }
            }, {
                key: 'initPluginJarallax',
                value: function initPluginJarallax($context) {
                    return _initPluginJarallax2.initPluginJarallax.call(this, $context);
                }
            }, {
                key: 'initPluginSwiper',
                value: function initPluginSwiper() {
                    return _initPluginSwiper2.initPluginSwiper.call(this);
                }
            }, {
                key: 'initPluginOFI',
                value: function initPluginOFI() {
                    return _initPluginOFI2.initPluginOFI.call(this);
                }
            }, {
                key: 'initPluginSelectize',
                value: function initPluginSelectize() {
                    return _initPluginSelectize2.initPluginSelectize.call(this);
                }
            }, {
                key: 'initPluginQuill',
                value: function initPluginQuill() {
                    return _initPluginQuill2.initPluginQuill.call(this);
                }
            }, {
                key: 'initPluginDropzone',
                value: function initPluginDropzone() {
                    return _initPluginDropzone2.initPluginDropzone.call(this);
                }
            }]);

            return AMDESK;
        }();

        /*------------------------------------------------------------------

          Init Amdesk

        -------------------------------------------------------------------*/


        window.Amdesk = new AMDESK();

        /***/ }),
    /* 6 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.setOptions = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Set Custom Options

        -------------------------------------------------------------------*/
        function setOptions(newOpts) {
            var self = this;

            var optsTemplates = _utility.$.extend({}, this.options.templates, newOpts && newOpts.templates || {});

            self.options = _utility.$.extend({}, self.options, newOpts);
            self.options.templates = optsTemplates;
        }

        exports.setOptions = setOptions;

        /***/ }),
    /* 7 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initNavbar = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Navbar

        -------------------------------------------------------------------*/
        function initNavbar() {
            var self = this;
            var $navbar = (0, _utility.$)('.dx-navbar-top');

            // Fixed open modal
            var navbarWidth = 0;

            (0, _utility.debounceResize)(function () {
                navbarWidth = $navbar.innerWidth();
            });
            (0, _utility.$)(document).on('beforeLoad.fb', function () {
                $navbar.width(navbarWidth);
            });
            (0, _utility.$)(document).on('afterClose.fb', function () {
                $navbar.width('');
            });

            // Sticky
            var navbarTop = $navbar.length ? $navbar.offset().top : 0;
            // fake hidden navbar to prevent page jumping on stick
            var $navbarFake = (0, _utility.$)('<div>').hide();
            function onScrollNav() {
                var stickyOn = _utility.$wnd.scrollTop() >= navbarTop;

                if (stickyOn) {
                    $navbar.addClass('dx-navbar-fixed');
                    $navbarFake.show();
                } else {
                    $navbar.removeClass('dx-navbar-fixed');
                    $navbarFake.hide();
                }
            }
            if ($navbar.hasClass('dx-navbar-sticky')) {
                _utility.$wnd.on('scroll resize', onScrollNav);
                onScrollNav();

                $navbar.after($navbarFake);
                $navbarFake.height($navbar.innerHeight());

                self.debounceResize(function () {
                    $navbarFake.height($navbar.innerHeight());
                });
            }

            // hide / show
            // add / remove solid color
            var $autohideNav = $navbar.filter('.dx-navbar-autohide');
            self.throttleScroll(function (type, scroll) {
                var start = 400;
                var hideClass = 'dx-onscroll-hide';
                var showClass = 'dx-onscroll-show';

                // hide / show
                if (type === 'down' && scroll > start) {
                    $autohideNav.removeClass(showClass).addClass(hideClass);
                } else if (type === 'up' || type === 'end' || type === 'start') {
                    $autohideNav.removeClass(hideClass).addClass(showClass);
                }
            });

            // Scroll
            if ($navbar.hasClass('dx-navbar-fixed') || $navbar.hasClass('dx-navbar-sticky')) {
                (0, _utility.throttleScroll)(function (type, scroll) {
                    if (scroll > 200) {
                        $navbar.addClass('dx-navbar-scroll');
                    } else {
                        $navbar.removeClass('dx-navbar-scroll');
                    }
                });
            }

            // Dropdown
            $navbar.find('.dx-navbar-dropdown').each(function () {
                var $thisDropdown = (0, _utility.$)(this);

                // Dropdown Position
                (0, _utility.debounceResize)(function () {
                    var dropdownLeft = $thisDropdown.offset().left;
                    var dropdownRight = _utility.wndW - (dropdownLeft + $thisDropdown.innerWidth());
                    var dropdownItem = $thisDropdown.closest('.dx-drop-item');

                    var dropdownDrop = $thisDropdown.find('.dx-navbar-dropdown');

                    if (!dropdownDrop.length) {
                        return;
                    }

                    var dropdownDropWidth = dropdownDrop.innerWidth();
                    var dropdownDropLeft = dropdownDrop.offset().left;
                    var dropdownDropRight = _utility.wndW - (dropdownDropLeft + dropdownDropWidth);
                    var dropdownDropItem = dropdownDrop.closest('.dx-drop-item');

                    if (dropdownRight < 0) {
                        $thisDropdown.css({ right: 0, left: 'auto' }).addClass('dx-navbar-dropdown-left');
                        dropdownItem.addClass('dx-drop-item-left');
                    }
                    if (dropdownLeft < dropdownRight) {
                        $thisDropdown.removeAttr('style').removeClass('dx-navbar-dropdown-left');
                        dropdownItem.removeClass('dx-drop-item-left');
                    }
                    if (dropdownDropRight < 0 && dropdownDropLeft >= dropdownDropWidth) {
                        dropdownDrop.removeAttr('style').addClass('dx-navbar-dropdown-left');
                        dropdownDropItem.addClass('dx-drop-item-left');
                    } else {
                        dropdownDrop.removeAttr('style').removeClass('dx-navbar-dropdown-left').addClass('dx-navbar-dropdown-bot');
                        dropdownDropItem.removeClass('dx-drop-item-left').addClass('dx-drop-item-bot');
                    }
                    if (dropdownDropLeft < 0 && dropdownDropRight >= dropdownDropWidth) {
                        dropdownDrop.removeAttr('style').removeClass('dx-navbar-dropdown-left');
                        dropdownDropItem.removeClass('dx-drop-item-left');
                    } else {
                        dropdownDrop.removeAttr('style').removeClass('dx-navbar-dropdown-left').addClass('dx-navbar-dropdown-bot');
                        dropdownDropItem.removeClass('dx-drop-item-left').addClass('dx-drop-item-bot');
                    }
                    if (dropdownDropRight >= dropdownDropWidth) {
                        dropdownDrop.removeAttr('style').removeClass('dx-navbar-dropdown-bot');
                        dropdownDropItem.removeClass('dx-drop-item-bot');
                    }
                });

                // Dropdown Triangle
                if ($navbar.hasClass('dx-navbar-dropdown-triangle')) {
                    $thisDropdown.append('<span class="dx-navbar-dropdown-triangle"></span>');

                    $navbar.find('.dx-drop-item > .dx-navbar-dropdown:first > .dx-navbar-dropdown-triangle:first').each(function () {
                        var $thisTriangle = (0, _utility.$)(this);
                        var dropLink = $thisTriangle.closest('.dx-drop-item').find('> a');

                        (0, _utility.debounceResize)(function () {
                            $thisTriangle.offset({ left: dropLink.offset().left });
                        });
                    });
                }
            });

            // Fullscreen Navbar
            var $navbarFull = (0, _utility.$)('.dx-navbar-fullscreen');
            if ($navbarFull.length) {
                var burger = $navbar.find('.dx-navbar-burger');
                var burgerFull = $navbarFull.find('.dx-navbar-burger');
                var dropItem = $navbarFull.find('.dx-drop-item');

                // Position Burger (navbar-fullscreen)
                (0, _utility.debounceResize)(function () {
                    burgerFull.css({ position: 'absolute', top: burger.offset().top - $navbar.offset().top, left: burger.offset().left });
                });

                // Click on burger navbar
                burger.on('click', function () {
                    burger.add(burgerFull).addClass('active');
                    $navbarFull.addClass('dx-navbar-fullscreen-open');
                    $navbarFull.removeClass('dx-navbar-fullscreen-closed');
                    $navbarFull.css({ 'z-index': 1000 });
                    (0, _utility.bodyOverflow)(1);
                });

                // Click on burger navbar-fullscreen
                burgerFull.on('click', function () {
                    burger.add(burgerFull).removeClass('active');
                    $navbarFull.removeClass('dx-navbar-fullscreen-open');
                    $navbarFull.addClass('dx-navbar-fullscreen-closed');
                    $navbarFull.find('.show').removeClass('show').innerHeight('');
                    $navbarFull.one('transitionend webkitTransitionEnd oTransitionEnd', function () {
                        $navbarFull.css({ 'z-index': -1000 });
                    });
                    (0, _utility.bodyOverflow)(0);
                });

                // Click on Esc
                (0, _utility.$)(document).on('keydown', function (e) {
                    if (e.keyCode === 27 && $navbarFull.hasClass('dx-navbar-fullscreen-open')) {
                        burger.add(burgerFull).removeClass('active');
                        $navbarFull.removeClass('dx-navbar-fullscreen-open');
                        $navbarFull.addClass('dx-navbar-fullscreen-closed');
                        (0, _utility.bodyOverflow)(0);
                    }
                });

                // Dropdown Collapse
                dropItem.each(function () {
                    var $thisItem = (0, _utility.$)(this);
                    var dropItemLink = $thisItem.find('> a');
                    $thisItem.find('.dx-navbar-dropdown').addClass('collapse');

                    dropItemLink.on('click', function (e) {
                        e.preventDefault();
                        var $dropdown = (0, _utility.$)(this).next('.dx-navbar-dropdown');
                        var dropdownChild = $dropdown.find('.dx-navbar-dropdown');
                        var dropdownHeight = $dropdown.innerHeight();
                        var dropdownSiblings = $thisItem.siblings().find('.show');
                        var dropdownSiblingsHeight = dropdownSiblings.innerHeight();

                        if (!$dropdown.hasClass('show')) {
                            $dropdown.removeClass('collapse').addClass('collapsing').innerHeight(dropdownHeight);

                            $dropdown.on('transitionend webkitTransitionEnd oTransitionEnd', function () {
                                $dropdown.addClass('show');
                                $dropdown.off('transitionend webkitTransitionEnd oTransitionEnd');
                            });
                        } else {
                            $dropdown.innerHeight(dropdownHeight).addClass('collapsing').innerHeight(0);
                            $dropdown.removeClass('collapse').removeClass('show');
                            dropdownChild.innerHeight(dropdownHeight).addClass('collapsing').innerHeight(0);
                            dropdownChild.removeClass('collapse').removeClass('show');
                        }
                        if (dropdownSiblings.hasClass('show')) {
                            dropdownSiblings.innerHeight(dropdownSiblingsHeight).addClass('collapsing').innerHeight(0);
                            dropdownSiblings.removeClass('collapse').removeClass('show');
                        }
                        $dropdown.one('transitionend webkitTransitionEnd oTransitionEnd', function () {
                            $dropdown.removeClass('collapsing').addClass('collapse').innerHeight('');
                            dropdownChild.removeClass('collapsing').addClass('collapse').innerHeight('');
                            dropdownSiblings.removeClass('collapsing').addClass('collapse').innerHeight('');
                        });
                    });
                });
            }
        }

        exports.initNavbar = initNavbar;

        /***/ }),
    /* 8 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initDropdown = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Dropdown

        -------------------------------------------------------------------*/
        function initDropdown() {
            (0, _utility.$)('.dx-dropdown').each(function () {
                var $this = (0, _utility.$)(this);
                var $container = (0, _utility.$)('.dx-main .container:first');
                var dropMenu = $this.find('.dropdown-menu');

                (0, _utility.debounceResize)(function () {
                    var containerRight = _utility.wndW - ($container.innerWidth() + $container.offset().left - parseInt($container.css('padding-right'), 10));
                    var dropMenuRight = _utility.wndW - (dropMenu.innerWidth() + dropMenu.offset().left);
                    var dropMenuNegative = containerRight - dropMenuRight;

                    if (containerRight > dropMenuRight) {
                        $this.on('show.bs.dropdown', function () {
                            dropMenu.css({ display: 'block', 'margin-left': -dropMenuNegative });
                        });
                    }
                    $this.on('hidden.bs.dropdown', function () {
                        dropMenu.css({ display: 'block' });
                    });
                });
            });
        }

        exports.initDropdown = initDropdown;

        /***/ }),
    /* 9 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initBtnLoad = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Btn Load

        -------------------------------------------------------------------*/
        function initBtnLoad() {
            var self = this;
            (0, _utility.$)('.dx-btn-load').on('click', function (e) {
                var $this = (0, _utility.$)(this);
                var $btnAttr = $this.attr('data-btn-loaded');

                e.preventDefault();
                $this.addClass('dx-btn-loading');

                setTimeout(function () {
                    $this.removeClass('dx-btn-loading');

                    if ($btnAttr) {
                        $this.text($btnAttr);
                    }
                    //$this.addClass('dx-btn-loaded');
                    if(!$this.hasClass('dx-btn-loaded')){
                        loadMorePostsButton();
                    }
                }, 2000);
            });
        }

        exports.initBtnLoad = initBtnLoad;

        /***/ }),
    /* 10 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initForm = undefined;

        var _utility = __webpack_require__(0);

        /* Portfolio */
        function initForm() {
            // Quantity
            (0, _utility.$)('.dx-form-quantity-input').each(function () {
                var $this = (0, _utility.$)(this);
                var minus = $this.parent().find('.dx-form-quantity-minus');
                var plus = $this.parent().find('.dx-form-quantity-plus');
                var min = parseInt($this.attr('min'), 10);
                var max = parseInt($this.attr('max'), 10);

                $this.on('input', function () {
                    var val = this.value;

                    if (val !== '') {
                        if (val > max) {
                            val = max;
                        } else if (val < min) {
                            val = min;
                        }
                    }
                    this.value = val;
                });

                minus.on('click', function (e) {
                    e.preventDefault();
                    var count = parseInt($this.val(), 10) - 1;
                    count = count < min ? min : count;
                    $this.val(count);
                    $this.change();
                });
                plus.on('click', function (e) {
                    e.preventDefault();
                    var count = parseInt($this.val(), 10) + 1;
                    count = count > max ? max : count;
                    $this.val(count);
                    $this.change();
                });
            });
        }

        exports.initForm = initForm;

        /***/ }),
    /* 11 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initTwitter = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Twitter

        -------------------------------------------------------------------*/
        function initTwitter() {
            var self = this;
            var $twtFeeds = (0, _utility.$)('.dx-twitter-list');
            if (!$twtFeeds.length || !self.options.templates.twitter) {
                return;
            }

            /**
             * Templating a tweet using '{{ }}' braces
             * @param  {Object} data Tweet details are passed
             * @return {String}      Templated string
             */
            function templating(data, temp) {
                var tempVariables = ['date', 'tweet', 'avatar', 'url', 'retweeted', 'screen_name', 'user_name'];

                for (var i = 0, len = tempVariables.length; i < len; i++) {
                    temp = temp.replace(new RegExp('{{' + tempVariables[i] + '}}', 'gi'), data[tempVariables[i]]);
                }

                return temp;
            }

            $twtFeeds.each(function () {
                var $this = (0, _utility.$)(this);
                var options = {
                    username: $this.attr('data-twitter-user-name') || null,
                    list: null,
                    hashtag: $this.attr('data-twitter-hashtag') || null,
                    count: $this.attr('data-twitter-count') || 2,
                    hideReplies: $this.attr('data-twitter-hide-replies') === 'true',
                    template: $this.attr('data-twitter-template') || self.options.templates.twitter,
                    loadingText: self.options.templates.twitterLoadingText,
                    failText: self.options.templates.twitterFailText,
                    apiPath: self.options.templates.twitterApiPath
                };

                // stop if running in file protocol
                if (window.location.protocol === 'file:') {
                    $this.html(options.failText);
                    // eslint-disable-next-line
                    console.error('You should run you website on webserver with PHP to get working Twitter');
                    return;
                }

                // Set loading
                $this.html('<span>' + options.loadingText + '</span>');

                // Fetch tweets
                _utility.$.getJSON(options.apiPath, {
                    username: options.username,
                    list: options.list,
                    hashtag: options.hashtag,
                    count: options.count,
                    exclude_replies: options.hideReplies
                }, function (twt) {
                    $this.html('');

                    for (var i = 0; i < options.count; i++) {
                        var tweet = false;
                        if (twt[i]) {
                            tweet = twt[i];
                        } else if (twt.statuses && twt.statuses[i]) {
                            tweet = twt.statuses[i];
                        } else {
                            break;
                        }

                        var tempData = {
                            user_name: tweet.user.name,
                            date: tweet.date_formatted,
                            tweet: tweet.text_entitled,
                            avatar: '<img src="' + tweet.user.profile_image_url + '" />',
                            url: 'https://twitter.com/' + tweet.user.screen_name + '/status/' + tweet.id_str,
                            retweeted: tweet.retweeted,
                            screen_name: tweet.user.screen_name
                        };

                        $this.append(templating(tempData, options.template));
                    }

                    // resize window
                    self.debounceResize();
                }).fail(function (a) {
                    $this.html(options.failText);
                    _utility.$.error(a.responseText);
                });
            });
        }

        exports.initTwitter = initTwitter;

        /***/ }),
    /* 12 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initPluginStickySidebar = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Plugin Sticky Sidebar

        -------------------------------------------------------------------*/
        function initPluginStickySidebar() {
            if (typeof _utility.$.fn.stick_in_parent === 'undefined') {
                return;
            }
            (0, _utility.$)('.dx-sticky').each(function () {
                var $this = (0, _utility.$)(this);
                var row = $this.closest('.row');
                var offsetTop = parseInt($this.attr('data-sticky-offsetTop'), 10);
                var offsetBot = parseInt($this.attr('data-sticky-offsetBot'), 10);

                if (offsetBot) {
                    $this.parent().css({ 'margin-bottom': -offsetBot });
                    $this.css({ 'margin-bottom': offsetBot });
                }

                if (row.find('img').length) {
                    row.imagesLoaded().progress(function () {
                        $this.stick_in_parent({
                            offset_top: offsetTop
                        });
                    });
                } else {
                    $this.stick_in_parent({
                        offset_top: offsetTop
                    });
                }

                (0, _utility.$)(document).on('shown.bs.collapse hidden.bs.collapse', function () {
                    (0, _utility.$)(document.body).trigger('sticky_kit:recalc');
                });
            });
        }

        exports.initPluginStickySidebar = initPluginStickySidebar;

        /***/ }),
    /* 13 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        /* Cleave */
        function initPluginCleave() {
            if (typeof Cleave === 'undefined') {
                return;
            }
            if ($('.dx-card-number').length) {
                // eslint-disable-next-line
                var cleave = new Cleave('.dx-card-number', {
                    creditCard: true
                });
            }
        }

        exports.initPluginCleave = initPluginCleave;

        /***/ }),
    /* 14 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initPluginImagesLoaded = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Plugin ImagesLoaded

        -------------------------------------------------------------------*/
        function initPluginImagesLoaded() {
            if (typeof _utility.$.fn.imagesLoaded === 'undefined') {
                return;
            }
            (0, _utility.$)('.dx-isotope-grid').imagesLoaded().progress(function () {
                (0, _utility.$)('.dx-isotope-grid').isotope('layout');
            });
        }

        exports.initPluginImagesLoaded = initPluginImagesLoaded;

        /***/ }),
    /* 15 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initPluginIsotope = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Plugin Isotope

        -------------------------------------------------------------------*/
        function initPluginIsotope() {
            if (typeof _utility.$.fn.isotope === 'undefined') {
                return;
            }

            (0, _utility.$)('.dx-isotope-grid').isotope({
                itemSelector: '.dx-isotope-grid-item',
                layoutMode: 'masonry'
            });

            (0, _utility.$)('.dx-isotope-filter').on('click', 'li', function () {
                var $this = (0, _utility.$)(this);
                // bind filter button click
                var $filterValue = $this.attr('data-filter');

                $this.closest('.dx-isotope-container').find('.dx-isotope-grid').isotope({ filter: $filterValue });

                // change is-checked class on buttons
                $this.siblings('.active').removeClass('active');
                $this.addClass('active');
            });
        }

        exports.initPluginIsotope = initPluginIsotope;

        /***/ }),
    /* 16 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initPluginJarallax = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Jarallax

        -------------------------------------------------------------------*/
        function initPluginJarallax() {
            if (typeof _utility.$.fn.jarallax === 'undefined') {
                return;
            }

            // primary parallax
            (0, _utility.$)('.bg-image-parallax').jarallax({
                speed: this.options.parallaxSpeed
            });
        }

        exports.initPluginJarallax = initPluginJarallax;

        /***/ }),
    /* 17 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initPluginSwiper = undefined;

        var _utility = __webpack_require__(0);

        /* Swiper */
        function initPluginSwiper() {
            if (typeof Swiper === 'undefined') {
                return;
            }
            (0, _utility.$)('.swiper-container').each(function () {
                var $this = (0, _utility.$)(this);
                var swiperSlides = parseInt($this.attr('data-swiper-slides'), 10);
                var swiperSpeed = parseInt($this.attr('data-swiper-speed'), 10);
                var swiperPlay = parseInt($this.attr('data-swiper-autoPlay'), 10);
                var swiperSpace = parseInt($this.attr('data-swiper-space'), 10);
                var swiperSlidesAuto = $this.attr('data-swiper-slidesAuto');
                var swiperArrowsClone = $this.attr('data-swiper-arrows-clone');
                var swiperGrabCursor = $this.attr('data-swiper-grabCursor');
                var swiperLazy = $this.attr('data-swiper-lazy');
                var swiperBreakpoints = $this.attr('data-swiper-breakpoints');
                var swiperArrows = $this.attr('data-swiper-arrows');
                var swiperPagination = $this.attr('data-swiper-pagination');
                var swiperPaginationDynamic = $this.attr('data-swiper-pagination-dynamic');
                var swiperPaginationScrollbar = $this.attr('data-swiper-pagination-scrollbar');
                var swiperHeight = $this.attr('data-swiper-autoHeight');
                var swiperFree = $this.attr('data-swiper-freeMode');
                var swiperLoop = $this.attr('data-swiper-loop');
                var conf = {};

                if (swiperSpeed) {
                    conf.speed = swiperSpeed;
                }
                if (swiperSpace) {
                    conf.spaceBetween = swiperSpace;
                }
                if (swiperPlay) {
                    conf.autoplay = { delay: swiperPlay, disableOnInteraction: false };
                }
                if (swiperSlides) {
                    conf.slidesPerView = swiperSlides;
                }
                if (swiperSlidesAuto === 'true') {
                    conf.slidesPerView = 'auto';
                    conf.centeredSlides = true;
                }
                if (swiperArrows === 'true') {
                    conf.navigation = { prevEl: '.swiper-button-prev', nextEl: '.swiper-button-next' };
                }
                if (swiperGrabCursor === 'true') {
                    conf.grabCursor = true;
                }
                if (swiperLazy === 'true') {
                    conf.lazy = true;
                }
                if (swiperPagination === 'true') {
                    conf.pagination = { el: '.swiper-pagination', type: 'bullets', clickable: true };
                }
                if (swiperPaginationDynamic === 'true') {
                    conf.pagination = { el: '.swiper-pagination', dynamicBullets: true, clickable: true };
                }
                if (swiperPaginationScrollbar === 'true') {
                    conf.scrollbar = { el: '.swiper-scrollbar', hide: true };
                }
                if (swiperHeight === 'true') {
                    conf.autoHeight = swiperHeight;
                }
                if (swiperFree === 'true') {
                    conf.freeMode = swiperFree;
                }
                if (swiperLoop) {
                    conf.loop = swiperLoop;
                }
                if (swiperArrowsClone === 'true') {
                    var $prev = $this.find('.swiper-button-prev');
                    var $next = $this.find('.swiper-button-next');
                    var $arrowsClone = $this.closest('.dx-box, .dx-box-1, .dx-box-2, .dx-box-3, .dx-box-4, .dx-box-5').find('.dx-slider-arrows-clone');

                    $prev.add($next).clone().appendTo($arrowsClone);

                    $arrowsClone.find('.swiper-button-prev').on('click', function () {
                        $prev.click();
                    });
                    $arrowsClone.find('.swiper-button-next').on('click', function () {
                        $next.click();
                    });
                }
                if (swiperBreakpoints === 'true' && swiperSlides) {
                    if (swiperSlides === 2) {
                        var numberOfPoints = 3;
                        var points = [768, 992, 1200];
                        var breaks = {};

                        while (swiperSlides > 0 && numberOfPoints > 0) {
                            breaks[points[numberOfPoints - 1]] = { slidesPerView: swiperSlides };

                            swiperSlides--;
                            numberOfPoints--;
                        }
                        conf.breakpoints = breaks;
                    } else {
                        var _numberOfPoints = 5;
                        var _points = [576, 768, 992, 1200, 1920];
                        var _breaks = {};

                        while (swiperSlides > 0 && _numberOfPoints > 0) {
                            _breaks[_points[_numberOfPoints - 1]] = { slidesPerView: swiperSlides };

                            swiperSlides--;
                            _numberOfPoints--;
                        }
                        conf.breakpoints = _breaks;
                    }
                }

                // eslint-disable-next-line
                new window.Swiper(this, conf);
            });
        }

        exports.initPluginSwiper = initPluginSwiper;

        /***/ }),
    /* 18 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });

        /* Object-Fit-Image */
        function initPluginOFI() {
            if (typeof window.objectFitImages !== 'undefined') {
                window.objectFitImages();
            }
        }

        exports.initPluginOFI = initPluginOFI;

        /***/ }),
    /* 19 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initPluginSelectize = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Plugin Selectize

        -------------------------------------------------------------------*/
        function initPluginSelectize() {
            if (typeof _utility.$.fn.selectpicker !== 'undefined' && (0, _utility.$)('.dx-select-ticket').length) {
                (0, _utility.$)('.dx-select-ticket').selectpicker();
            }
        }

        exports.initPluginSelectize = initPluginSelectize;

        /***/ }),
    /* 20 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initPluginQuill = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Plugin Quill

        -------------------------------------------------------------------*/
        function initPluginQuill() {
            if (typeof Quill === 'undefined') {
                return;
            }
            var editorQuill = (0, _utility.$)('.dx-editor');
            if (editorQuill.length) {
                editorQuill.each(function () {
                    var $this = (0, _utility.$)(this);
                    $this.css({ 'min-height': parseInt($this.attr('data-editor-height'), 10), 'max-height': parseInt($this.attr('data-editor-maxHeight'), 10) });
                });
                //var toolbarOptions = [['bold', 'italic', 'underline', 'strike'], ['clean'], [{ list: 'ordered' }, { list: 'bullet' }], ['link']];
                var toolbarOptions = [
                    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                    ['blockquote', 'code-block'],

                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                    [{ 'direction': 'rtl' }],                         // text direction

                    [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                    [{ 'font': [] }],
                    [{ 'align': [] }],

                    ['clean']
                ]
                // eslint-disable-next-line
                var quill = new Quill('.dx-editor', {
                    modules: {
                        toolbar: toolbarOptions
                    },
                    theme: 'snow'
                });
            }
        }

        exports.initPluginQuill = initPluginQuill;

        /***/ }),
    /* 21 */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        exports.initPluginDropzone = undefined;

        var _utility = __webpack_require__(0);

        /*------------------------------------------------------------------

          Init Plugin Dropzone

        -------------------------------------------------------------------*/
        function initPluginDropzone() {
            if (typeof Dropzone === 'undefined') {
                return;
            }
            (0, _utility.$)('.dx-dropzone').each(function () {
                var $this = (0, _utility.$)(this);
                var attachment = $this.next('.dx-dropzone-attachment').find('.dx-dropzone-attachment-add');
                var dropzoneAction = $this.attr('data-dropzone-action');
                var dropzoneMaxFiles = parseInt($this.attr('data-dropzone-maxFiles'), 10);
                var dropzoneMaxMB = parseInt($this.attr('data-dropzone-maxMB'), 10);

                // eslint-disable-next-line
                Dropzone.autoDiscover = false;
                var myDropzone = new Dropzone('.dx-dropzone', {
                    uploadMultiple: true,
                    url: dropzoneAction,
                    maxFiles: dropzoneMaxFiles,
                    maxFilesize: dropzoneMaxMB,
                    parallelUploads: 3,
                    addRemoveLinks: true,
                    paramName: "files",
                    createImageThumbnails: true,
                    thumbnailWidth: 120,
                    thumbnailHeight: 120,
                    thumbnailMethod: "crop",
                    resizeMethod: "crop",

                    dictFileTooBig: "Файл слишком большой ({{filesize}}mb). Максимально возможно {{maxFilesize}}mb",
                    dictInvalidFileType: "Неверный тип файла",
                    dictCancelUpload: "Отменить",
                    dictRemoveFile: "Удалить",
                    dictMaxFilesExceeded: "Разрешено загружать максиум {{maxFiles}} файлов",
                    dictDefaultMessage: "Перетащите сюда файлы для загрузки",

                    error: async (file, e) => {
                        let data = {
                            message: e,
                        };

                        await getToast("warning", data);
                    },

                    init: async(e) => {

                        var postId = document.querySelector('#idPost');
                        var _token = document.querySelector('[name = _token]');

                        const form = document.querySelector('.dx-dropzone');
                        const formData = new FormData(form);

                        if(postId == null) {
                            formData.append("_token",  _token.value );
                        } else {
                            formData.append("postId", postId.value );
                            formData.append("_token",  _token.value );
                        }

                        let response = await fetch('/admin/blog/posts/img/get', {
                            method: "POST",
                            body:   formData,
                            dataType: "json"
                        });

                        let result = await response.json();

                        if(result.files != '') {
                            result.files.forEach(function(image) {
                                var mockFile = { name: image.fileName, size: image.fileSize };
                                myDropzone.displayExistingFile(mockFile, image.path);

                            });
                        }

                    }

                });


                attachment.on('click', function () {
                    $this.click();
                });

                if ($this.add(':not(.active)')) {
                    (0, _utility.$)(document).on('dragover', function () {
                        $this.addClass('active');
                    });
                }

                myDropzone.on('addedfile', async (file) => {
                    //alert(file.name);
                });


                myDropzone.on('removedfile', async (file) => {


                    $this.removeClass('active');

                    const _token = document.querySelector('[name = _token]');
                    const form = document.querySelector('.dx-dropzone');

                    const formData = new FormData(form);

                    if(file.hasOwnProperty('upload')) {
                        formData.append("imgToDelete", file.upload.filename );
                    } else {
                        formData.append("imgToDelete", file.name );
                    }

                    formData.append("_token", _token.value );

                    let response = await fetch(myDropzone.options.url, {
                        method: 'POST',
                        body: formData
                    });

                });

                myDropzone.on("successmultiple", function(file, serverResponse) {

                    // Called after the file successfully uploaded

                    //myDropzone.removeAllFiles();


                });

                myDropzone.on("completemultiple", async(e) => {
                    $(".dz-preview").remove();

                    var postId = document.querySelector('#idPost');
                    var _token = document.querySelector('[name = _token]');

                    const form = document.querySelector('.dx-dropzone');
                    const formData = new FormData(form);

                    if(postId == null) {
                        formData.append("_token",  _token.value );
                    } else {
                        formData.append("postId", postId.value );
                        formData.append("_token",  _token.value );
                    }

                    let response = await fetch('/admin/blog/posts/img/get', {
                        method: "POST",
                        body:   formData,
                        dataType: "json"
                    });

                    let result = await response.json();

                    if(result.files != '') {
                        result.files.forEach(function(image) {
                            var mockFile = { name: image.fileName, size: image.fileSize };
                            myDropzone.displayExistingFile(mockFile, image.path);

                        });
                    }

                });

            });
        }



        exports.initPluginDropzone = initPluginDropzone;

        /***/ })
    /******/ ]);