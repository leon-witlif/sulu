/*
 * This file is part of Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

define(function() {

    'use strict';

    var constants = {
            scrollContainerSelector: '.content-column > .wrapper .page',
            fixedClass: 'fixed',
            scrollMarginTop: 90
        },

        /**
         * Handles the scroll event to fix or unfix the given element.
         */
        scrollHandler = function($el, scrollTop) {
            if (scrollTop > constants.scrollMarginTop) {
                $el.addClass(constants.fixedClass);
            } else {
                $el.removeClass(constants.fixedClass);
            }
        };

    return function(app) {
        /**
         * Provides functions to enable or disable the sticky toolbar.
         *
         * @type {{enable, disable}}
         */
        app.sandbox.stickyToolbar = {
            enable: function($el) {
                app.sandbox.dom.on(constants.scrollContainerSelector, 'scroll.sticky-toolbar', function() {
                    scrollHandler($el, app.sandbox.dom.scrollTop(constants.scrollContainerSelector));
                });
            },

            disable: function() {
                app.sandbox.dom.off(constants.scrollContainerSelector, 'scroll.sticky-toolbar');
            },

            reset: function($el) {
                $el.removeClass(constants.fixedClass);
            }
        };
    };
});
