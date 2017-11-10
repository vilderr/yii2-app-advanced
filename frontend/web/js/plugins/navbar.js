(function ($, w, d) {
    "use strict";

    var defaults = {
        layout: 'navbar-static'
    }, touch = "ontouchstart" in window, options, f = {
        sticky: !1,
        init: function (element) {
            this.$element = $(element);
            this.$win = $(w);
            this.$doc = $(d);
            this.applyHandlers(this);
        },
        applyHandlers: function (self) {

            this.$win.on('resize.navbar', $.proxy(this.resizeIcons, this))
                .on('resize.navbar', $.proxy(this.switchClasses, this))
                .trigger('resize.navbar');
        },
        resizeIcons: function () {
            this.$element.find('.bar').each(function () {
                var e = $(this), h = e.outerHeight();

                e.css('padding-left', h + 5).children('i').css('font-size', h).end().children('.icon').css('width', h);
            });
        }
    };

    $.fn.Navbar = function (options) {
        options = $.extend(!0, defaults, options);

        f.init(this);
    };
})(window.jQuery, window, document);