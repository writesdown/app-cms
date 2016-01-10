(function ($) {
    "use strict";

    $('.widget-activation-form').on('submit', function (e) {
        var loc = $(this).find('.widget-widget_location');
        var parent = $('#widget-space-' + loc.val());
        e.preventDefault();
        e.stopImmediatePropagation();
        $.ajax({
            url: $(this).data('url'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                parent.find('.widget-order').append(response);
                parent.removeClass('collapsed-box');
                $.AdminLTE.boxWidget.activate()
            }
        })
    });

    $(document).on('submit', '.widget-activated-form', function (e) {
        var _boxtitle = $(this).find('.box-title');
        var _title = _boxtitle.html();
        var _loading = '<i class="fa fa-spinner fa-pulse"></i>';
        e.preventDefault();
        e.stopImmediatePropagation();
        $.ajax({
            url: $(this).data('url'),
            type: "POST",
            beforeSent: _boxtitle.html(_loading),
            data: $(this).serialize(),
            success: function () {
                _boxtitle.html(_title)
            }
        })
    });

    $(document).on('click', '.ajax-delete-widget-btn', function () {
        var _this = $(this);
        $.ajax({
            url: _this.data('url'),
            type: "POST",
            success: function () {
                _this.closest('.box').remove();
            }
        })
    });

    $('.widget-order').sortable({
        update: function () {
            var _ids = [{}];
            $(this).find('.widget-activated-form').each(function () {
                _ids.push($(this).data('id'));
            });
            $(this).closest('.widget-space').find('.widget-order-field').val(JSON.stringify(_ids));
        }
    });

    $('.widget-order-form').on('submit', function (e) {
        var _ids = [{}],
            _this = $(this);

        e.preventDefault();
        e.stopImmediatePropagation();

        if ($(this).find('.widget-order-field').val() !== '') {
            _ids = $.parseJSON($(this).find('.widget-order-field').val());
        }

        $.ajax({
            url: $(this).data('url'),
            data: {ids: _ids, _csrf: yii.getCsrfToken()},
            type: "POST",
            beforeSent: _this.find('.btn').html('<i class="fa fa-spinner fa-pulse"></i> ' + _this.find('.btn').html()),
            success: function () {
                _this.find('.fa-spinner').remove();
            }
        })
    });

}(jQuery));