if (Omeka == typeof undefined) {
    var Omeka = {};
}

Omeka.MOASUrlStuff = {};

(function ($) {

    Omeka.MOASUrlStuff.generateSlug = function (target) {
        $.ajax({
            url: '/admin/nice-urls/create',
            method: "POST",
            data: {
                title: $('#Elements-50-0-text').val()
            },
            success: function (response) {
                $("input[name='" +target+"']").val(response.slug);
            }
        });
    };

    Omeka.MOASUrlStuff.checkSlug = function (elem) {
        elem = $(elem);
        $.ajax({
            url: "/admin/nice-urls/check",
            method: "POST",
            data: {
                slug: elem.val()
            },
            success: function (response) {
                if (response.exists) {
                    var errorID = elem.attr('id').split('-', 3).join('-')+'-error';
                    $('#' + errorID).removeClass('visually-hidden')
                } else {
                    $('#' + errorID).addClass('visually-hidden')
                }
            }
        })
    };

})(jQuery);