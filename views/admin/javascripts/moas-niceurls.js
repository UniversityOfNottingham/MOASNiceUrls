if (!Omeka) {
    var Omeka = {};
}

Omeka.MOASUrlStuff = {};

(function ($) {

    Omeka.MOASUrlStuff.$title = $('#Elements-50-0-text');

    $('document').ready(function () {
        $('.js-moas-slug-generate').click(function() {
            Omeka.MOASUrlStuff.generateSlug();
        })
    })

    Omeka.MOASUrlStuff.generateSlug = function () {
        var title = $('#Elements-50-0-text').val();
        $('.js-moas-slug-input').val(title);
    };
})(jQuery);