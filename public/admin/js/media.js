(function ($) {
    'use strict';

    var fu= $('#media-upload');

    fu.fileupload({
        url: fu.data('url'),
        dropZone: $('.dropzone'),
        autoUpload: true,
        filesContainer: ".file-container"
    });

    fu.fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    fu.addClass('fileupload-processing');

    $(document).bind('dragover', function (e) {
        var dropZone = $('.dropzone'),
            foundDropzone,
            timeout = window.dropZoneTimeout;
        if (!timeout) {
            dropZone.addClass('in');
        }
        else {
            clearTimeout(timeout);
        }
        var found = false,
            node = e.target;

        do {

            if ($(node).hasClass('dropzone')) {
                found = true;
                foundDropzone = $(node);
                break;
            }

            node = node.parentNode;

        } while (node != null);

        dropZone.removeClass('in hover');

        if (found) {
            foundDropzone.addClass('hover');
        }

        window.dropZoneTimeout = setTimeout(function () {
            window.dropZoneTimeout = null;
            dropZone.removeClass('in hover');
        }, 100);
    });
})(jQuery);
