"use strict";

var Admin = function () {
};

Admin.prototype = {
    constructor: Admin
};

Admin.options = {
    sidebar: true,
    sidebarToggleSelector: ".sidebar-toggle-button",
    screenSizes: {
        xs: 480,
        sm: 768,
        md: 992,
        lg: 1200
    },
    animationSpeed: 500
};

Admin.layout = {
    activate: function () {
        var _this = this;

        _this.fix();
        $('body, html, .wrapper').css('height', 'auto');
        $(window, ".wrapper").resize(function () {
            _this.fix();
        });
    },
    fix: function () {
        var footer_height = $('.main-footer').outerHeight() || 0;
        var neg = $('.main-header').outerHeight() + footer_height;
        var window_height = $(window).height();
        var sidebar_height = $(".sidebar").height() || 0;

        if ($("body").hasClass("fixed")) {
            $(".content-wrapper").css('min-height', window_height - footer_height);
        } else {
            var postSetWidth;

            if (window_height >= sidebar_height) {
                $(".content-wrapper").css('min-height', window_height - neg);
            } else {
                $(".content-wrapper").css('min-height', sidebar_height);
            }
        }
    }
};

Admin.sidebar = {
    activate: function (toggleBtn) {
        var screenSizes = Admin.options.screenSizes;

        $(document).on('click', toggleBtn, function (e) {
            e.preventDefault();

            $(toggleBtn).toggleClass('active');

            if ($(window).width() > (screenSizes.sm - 1)) {
                if ($("body").hasClass('sidebar-collapse')) {
                    $("body").removeClass('sidebar-collapse').trigger('expanded.pushMenu');
                } else {
                    $("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
                }
            }
            else {
                if ($("body").hasClass('sidebar-open')) {
                    $("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');
                } else {
                    $("body").addClass('sidebar-open').trigger('expanded.pushMenu');
                }
            }
        });

        $(".content-wrapper").click(function () {
            //Enable hide menu when clicking on the content-wrapper on small screens
            if ($(window).width() <= (screenSizes.sm - 1) && $("body").hasClass("sidebar-open")) {
                $("body").removeClass('sidebar-open');
            }
        });

        if ($('body').hasClass('fixed') && $('body').hasClass('sidebar-mini')) {
            this.expandOnHover();
        }
    },
    expandOnHover: function () {
        var _this = this;
        var screenWidth = Admin.options.screenSizes.sm - 1;
        //Expand sidebar on hover
        $('.main-sidebar').hover(function () {
            if ($('body').hasClass('sidebar-mini')
                && $("body").hasClass('sidebar-collapse')
                && $(window).width() > screenWidth) {
                _this.expand();
            }
        }, function () {
            if ($('body').hasClass('sidebar-mini')
                && $('body').hasClass('sidebar-expanded-on-hover')
                && $(window).width() > screenWidth) {
                _this.collapse();
            }
        });
    },
    expand: function () {
        $("body").removeClass('sidebar-collapse').addClass('sidebar-expanded-on-hover');
    },
    collapse: function () {
        if ($('body').hasClass('sidebar-expanded-on-hover')) {
            $('body').removeClass('sidebar-expanded-on-hover').addClass('sidebar-collapse');
        }
    }
};

Admin.makeSlug = function (selectorsFrom, selectorTo, replacement, callback) {
    var valueFrom = $(selectorsFrom).val();

    if (valueFrom.length) {
        $.ajax({
            'url': '/admin/response/make-slug',
            'type': 'GET',
            'cache': false,
            'dataType': 'json',
            'data': {
                'str': valueFrom,
                'replace_space': !!replacement.space ? replacement.space : '-',
                'replace_other': !!replacement.other ? replacement.other : '-'
            }
        }).done(function (data) {
            var $field = $(selectorTo);

            if (typeof $field.attr('maxlength') !== typeof undefined) {
                data = data.substr(0, $field.attr('maxlength'));
            }

            $field.val(data);
        }).fail(function (jqXHR, textStatus) {
            //console.log(jqXHR.responseText);
        });
    }
};

$(function () {
    var o = Admin.options;
    Admin.layout.activate();
    if (o.sidebar) {
        Admin.sidebar.activate(o.sidebarToggleSelector);
    }
});