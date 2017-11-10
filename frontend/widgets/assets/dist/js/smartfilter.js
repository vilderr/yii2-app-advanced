(function ($) {
    "use strict";

    var SmartFilter = {
        init: function () {
            this.$wrapper = $('#product-filter');
            this.$sections = this.$wrapper.find('ul.sections');

            var self = this;
            this.$sections.find('.section--name').each(function () {
                $(this).on('click', $.proxy(self.expandSection, self, this));
            });
        },
        expandSection: function (e) {
            var $this = $(e);
            var $parent = $this.parent();

            $parent.toggleClass('active');
        }
    };

    SmartFilter.init();
})(jQuery);