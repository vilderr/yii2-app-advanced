(function ($, w, d) {
    "use strict";

    var BTheme = {
        touch: "ontouchstart" in window,
        plugins: {
            $rdNavbar: !1,
            $preloader: !1,
            $topMenu: !1,
            $smartFilter: !1,
            $products: !1
        },
        inputGroupSize: 'input-group',
        layouts: {
            0: {layout: 'mobile'},
            576: {layout: 'tablet'},
            992: {layout: 'desktop', inputGroupSize: 'input-group-lg'}
        },
        init: function () {
            this.$win = $(w);
            this.$doc = $(d);
            this.touch = 'ontouchstart' in w;

            this.$doc.ready($.proxy(this.onReady, this));
            this.$win
                .on('load', $.proxy(this.onLoad, this))
                .on('resize.theme', $.proxy(this.resize, this));
        },
        onReady: function () {
            this.onReadyInit();

            this.plugins.$rdNavbar.length && this.plugins.$rdNavbar.Navbar({});
            this.plugins.$topMenu.length && this.topMenu.init(this);
            this.plugins.$smartFilter.length && this.initSmartFilter();
        },
        onReadyInit: function () {
            this.$body = $('body');
            this.plugins.$preloader = $('#preloader-cover');
            this.plugins.$rdNavbar = $('.b-navbar');
            this.plugins.$topMenu = $('.top-category-menu');
            this.plugins.$smartFilter = $('#product-filter');
            this.plugins.$products = $('#product-list');
            this.$win.trigger('resize.theme');
        },
        onLoad: function () {
            setTimeout($.proxy(function () {
                var p = this.plugins.$preloader;

                p && p.remove();
            }, this), 1);
        },
        resize: function () {
            var c = this.getLayout();
            var s = this.getOption('inputGroupSize');
            var to = null;
            var toh = 0;

            if (this.currentLayout !== c) {
                var a, b;
                for (a in this.layouts) {
                    b = a;
                    if (this.layouts[b]['layout'] !== c) this.$body.removeClass(this.layouts[b]['layout']);
                }

                this.$body.addClass(c) && (this.currentLayout = c);
            }

            this.$body.find('.input-group.resizeble').each(function () {
                var e = $(this);
                s === 'input-group-lg' ? (
                    e.hasClass('input-group-lg') ? void 0 : e.addClass('input-group-lg')
                ) : e.removeClass('input-group-lg');
            });

            this.plugins.$products && (to = this.plugins.$products.find('.thumb-outer'));
            to && to.each(function () {
                if ($(this).outerHeight() > toh) {
                    toh = $(this).outerHeight();
                }
            });

            to && toh > 0 && to.each(function () {
                $(this).css('height', toh);
            });
        },
        initTopMenu: function () {
            var self = this;
            var toggleBtn = this.plugins.$topMenu.closest('body').find('#nav-toggle-wrap');

            /*
             var activeChild = 0;
             var childs = this.plugins.$topMenu.children('li');
             var wrapper = this.plugins.$topMenu.closest('#main-menu');
             var outer = this.plugins.$topMenu.closest('.outer');
             var toggleBtn = this.plugins.$topMenu.closest('body').find('#nav-toggle-wrap');
             this.$win.on('resize.topmenu', $.proxy(this.topMenuResize, this, childs));

             toggleBtn.on('click', function () {
             $(this).add(wrapper).add(self.$body).toggleClass('active');
             self.topMenuResize(childs);
             });

             wrapper.siblings('.bg').add(wrapper.find('.close-menu')).on('click', function () {
             toggleBtn.add(wrapper).removeClass('active');
             });

             childs.each(function (i) {
             $(this).hasClass('active') && (activeChild = i);
             $(this).on('mouseenter', function () {
             $(this).addClass('active').siblings('li').removeClass('active');
             });
             });

             $(childs[activeChild]).addClass('active');

             outer.on('mouseleave', function () {
             childs.each(function () {
             $(this).removeClass('active');
             });

             $(childs[activeChild]).addClass('active');
             });
             */
        },
        topMenu: {
            $toggleBtn: $('#nav-toggle-wrap'),
            init: function (theme) {
                this.$element = theme.plugins.$topMenu;
                this.$wrapper = this.$element.closest('#main-menu-wrapper');
                this.$outer = this.$element.closest('.outer');
                this.$header = this.$outer.find('.menu-header');
                this.$wrap = this.$outer.find('.menu-wrap');
                this.$firstLevels = this.$element.children('li');

                theme.$win.on('resize.topmenu', $.proxy(this.resize, this, theme));
                this.$toggleBtn.on('click', $.proxy(this.switchToggle, this, theme));
                this.$wrapper.siblings('.bg').add(this.$header.children('.close-menu')).on('click', $.proxy(this.switchToggle, this, theme));

                var activeChild = 0;
                var self = this;


                this.$firstLevels.each(function (i) {
                    var t = $(this);
                    t.hasClass('active') && (activeChild = i);
                    t.on('mouseenter', function () {
                        $(this).addClass('active').siblings('li').removeClass('active');
                    });
                });

                $(this.$firstLevels[activeChild]).addClass('active');

                this.$outer.on('mouseleave', function () {
                   self.$firstLevels.removeClass('active');
                   $(self.$firstLevels[activeChild]).addClass('active');
                });
            },
            switchToggle: function (theme) {
                if (theme.$body.hasClass('active')) {
                    this.$toggleBtn.add(theme.$body).add(this.$wrapper).removeClass('active');
                }
                else {
                    this.$toggleBtn.add(theme.$body).add(this.$wrapper).addClass('active');
                    this.resize(theme);
                }
            },
            resize: function (theme) {
                this.$wrap.css({'height': 'auto'});

                var h = 0;
                var ow = this.$outer.outerWidth();
                var oh = this.$outer.outerHeight();
                var ew = this.$element.outerWidth();
                var eh = this.$element.outerHeight();
                var mhh = this.$header.outerHeight();
                var ww = this.$wrap.innerWidth();
                var wh = this.$wrap.outerHeight();

                if (theme.$body.hasClass('mobile'))
                {
                    wh = oh - mhh;
                    this.$wrap.css({'height': oh - mhh});
                }

                var self = this;

                this.$firstLevels.each(function () {
                    var c = $(this).children('.subtree');
                    c.css('height', 'auto');

                    if (c.length) {
                        var coh = c.outerHeight();
                        var ul = c.children('ul');
                        coh > h && (h = coh);

                        c.css({'width': ow - ww, 'height': wh, 'overflow-y': 'scroll'});
                        var cih = c.height();
                    }
                });

                h < wh ? h = wh : void 0;

                this.$wrapper.css({'height': theme.$body.hasClass('mobile') ? '100%' : h});
                !theme.$body.hasClass('mobile') ? this.$wrap.css({'height': h}) : void 0;
            }
        },
        topMenuResize: function (childs) {
            var self = this;
            var o = this.plugins.$topMenu.closest('.outer');
            var ow = $(o).outerWidth();
            var mw = this.plugins.$topMenu.outerWidth();
            var mh = this.plugins.$topMenu.outerHeight();
            var h = 0;

            childs.each(function (i) {
                var c = $(this).children('ul');
                c.css({'width': ow - mw, 'left': mw});

                if (c.length && c.outerHeight() > mh) {
                    h = c.outerHeight();
                }

                if (self.getLayout() === 'mobile' && (c.outerHeight() + 46) > (mh - 46)) {
                    c.css({'overflow-x': 'hidden', 'overflow-y': 'scroll', height: mh - 46});
                }
            });

            self.getLayout() !== 'mobile' && h && o.css('height', h);
        },
        initSmartFilter: function () {
            this.plugins.$smartFilter.find('.section').each($.proxy(this.initFilterSection));
        },
        initFilterSection: function () {
            var s = $(this), u = s.find('.wrapper ul'), l = $(u).children('li');
            if (l.length >= 20) {
                var h = 0
                for (var i = 0; i < l.length; i++) {

                    var v = $(l[i]);
                    h += v.outerHeight();
                    if (i === 19) {
                        break;
                    }
                }

                $(u).slimScroll({
                    height: h,
                    railVisible: true,
                    alwaysVisible: true,
                    railColor: '#f7f7f7',
                    size: '3px',
                    color: '#26aae1',
                    opacity: 1
                });
            }
        },
        getLayout: function () {
            var a, b;
            for (a in this.layouts)a <= this.$win.innerWidth() && (b = a);
            return this.layouts[b]['layout'];
        },
        getOption: function (option) {
            var a, b;
            for (a in this.layouts)a <= this.$win.innerWidth() && (b = a);

            return this.layouts[b][option] ? this.layouts[b][option] : this[option]
        }
    };

    BTheme.init();
})(window.jQuery, window, document);

var topSliderOpt = {
    slideMargin:10,
    slideWidth:180,
    nextText: '<span class="mdi mdi-chevron-right"></span>',
    prevText: '<span class="mdi mdi-chevron-left"></span>',
};