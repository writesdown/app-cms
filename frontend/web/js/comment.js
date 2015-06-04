(function($){
    "use strict";
    $(document).on("click", ".comment-reply-link", function(e){
        e.preventDefault();
        var cf = $('#respond'),
            cp = $('.comment-parent-field'),
            cr = $('#cancel-reply');
        cf.find(cp).val($(this).data('id'));
        cr.show();
        $(this).closest('.comment').append(cf);
    });
    $(document).on("click","#cancel-reply", function(e){
        e.preventDefault();
        var cf = $('#respond'),
            cp = $('.comment-parent-field'),
            cv = $('#comment-view');
        cf.find(cp).val(0);
        $(this).hide();
        cv.append(cf);
    })
})(jQuery);