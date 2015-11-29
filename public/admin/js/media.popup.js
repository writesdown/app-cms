(function ($) {
    "use strict";

    /* DEFINE VARIABLES */
    var fu = $("#media-upload"),            // FOR JQUERY FILE UPLOAD
        fc = $("#file-container"),          // FILE CONTAINER DISPLAY
        md = $("#media-detail"),            // FOR DETAIL ITEM
        mf = $("#media-form"),              // FOR FORM OF THE SELECTED ITEM
        ad = $('#address'),
        me = {"files": []},                 // JSON OBJECT OF MEDIA ITEM
        se = {};                            // JSON OBJECT OF SELECTED ITEM

    /* FILE UPLOAD CONFIGURATION */
    fu.fileupload({
        url: fu.data("url"),
        dropZone: $(".dropzone"),
        autoUpload: true,
        filesContainer: "#file-container",
        prependFiles: true
    });

    fu.fileupload("option", "redirect", window.location.href.replace(/\/[^\/]*$/, "/cors/result.html?%s"));
    fu.addClass("fileupload-processing");

    /* DRAG AND DROP */
    $(document).bind("dragover", function (e) {
        var dropZone = $(".dropzone"), foundDropzone, timeout = window.dropZoneTimeout;

        if (!timeout)
            dropZone.addClass("in");
        else
            clearTimeout(timeout);

        var found = false, node = e.target;

        do {
            if ($(node).hasClass("dropzone")) {
                found = true;
                foundDropzone = $(node);
                break;
            }
            node = node.parentNode;
        } while (node != null);

        dropZone.removeClass("in hover");

        if (found) {
            foundDropzone.addClass("hover");
        }

        window.dropZoneTimeout = setTimeout(function () {
            window.dropZoneTimeout = null;
            dropZone.removeClass("in hover");
        }, 100);
    });

    /* ADD NEW UPLOADED FILE TO MEDIA JSON */
    fu.bind("fileuploaddone", function (e, data) {
        $.each(data.result, function (index, file) {
            me.files[me.files.length] = file[0];
        });
    });

    /* GET MEDIA DATA THAT APPEAR ON MEDIA WITHOUT FILTERING */
    $.ajax({
        url: ad.data('json-url'),
        dataType: "json",
        success: function (response) {
            me = response;
            $.ajax({
                url: ad.data('pagination-url'),
                success: function (response) {
                    $('#media-pagination').html(response);
                }
            });
           fc.html(tmpl("template-download", response));
        }
    });

    /* SIDEBAR NAVIGATION */
    $(document).on('click', '.media-popup-nav', function (e) {
        e.preventDefault();
        var $this = $(this);

        $this.closest("ul").find("li").removeClass("active")
        $this.parent("li").addClass("active");

        if ($this.hasClass('all')) {
            $('.pagination-item').removeAttr('data-post_id');
        }

        else {
            $('.pagination-item').attr('data-post_id', $(this).data("post_id"));
        }

        $.ajax({
            url: ad.data('json-url'),
            data: {post_id: $this.data('post_id')},
            dataType: "json",
            success: function (response) {
                me = response;
                $.ajax({
                    url: ad.data('pagination-url'),
                    data: {post_id: $this.data('post_id')},
                    success: function (response) {
                        var mp = $(".media-pagination");
                        mp.html(response);
                    }
                });
               fc.html(tmpl("template-download", response));
            }
        });
    });

    /* PAGINATION CLICK */
    $(document).on('click', '.pagination-item', function (e) {
        e.preventDefault();

        var $this = $(this),
            p1 = $(this).data('page'),
            p2 = p1 + 1;

        $.ajax({
            url: $this.attr('href'),
            data: {post_id: $this.data('post_id')},
            dataType: "json",
            success: function (response) {
                me = response;
                $.ajax({
                    url: ad.data('pagination-url'),
                    data: {post_id: $this.data('post_id'), page: p2, 'per-page': $this.data('per-page')},
                    success: function (response) {
                        var mp = $(".media-pagination");
                        mp.html(response);
                    }
                });
               fc.html(tmpl("template-download", response));
            }
        });
    });

    /* SHOW DETAIL ITEM */
    fc.selectable({
        filter: "li",
        tolerance: "fit",
        selected: function (event, ui) {
            $.each(me.files, function (i, file) {

                if ($(ui.selected).data('id') === file.id) {
                    md.html(tmpl('template-media-detail', file));
                    mf.html(tmpl('template-media-form', file));
                    se[$(ui.selected).data("id")] = $("#media-form-inner").serializeObject();
                }

            });
        },
        unselected: function (event, ui) {
            delete se[$(ui.unselected).data('id')];
        }
    });

    /* UPDATE SELECTED */
    $(document).on("blur", "#media-form-inner [id^='media-']", function () {
        var parent = $(this).parents('#media-form-inner'),
            id = parent.data("id");
        se[id] = parent.serializeObject();
    });

    /* UPDATE TITLE, EXCERPT, CONTENT OF MEDIA VIA AJAX CALL */
    $(document).on("blur", "#media-media_title, #media-media_excerpt, #media-media_content", function () {
        var mfi = $(this).closest('#media-form-inner');
        $.ajax({
            url: mfi.data("update-url"),
            type: "POST",
            data: {
                id: mfi.data("id"),
                attribute: $(this).data('attr'),
                attribute_value: $(this).val(),
                _csrf: yii.getCsrfToken()
            },
            success: function(response){
                console.log(response);
            }
        });
    });

    /* UPDATE LINK TO */
    $(document).on('change', '#media-media_link_to', function () {
        var link_value = $('#media-media_link_to_value');
        if ($(this).val() === 'none') {
            link_value.val('');
            link_value.attr('readonly', true);
        }
        else if ($(this).val() === 'custom') {
            link_value.val('http://');
            link_value.attr('readonly', false);
        }
        else {
            link_value.val($(this).val());
        }
    });


    /* DELETE MEDIA ITEM ON MEDIA POP UP */
    $(document).on("click", '#delete-media', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var $this = $(this);

        if (confirm($this.data('confirm'))) {
            $.ajax({
                url: $this.data('url'),
                type: "POST",
                success: function (data) {
                    $('.media-item[data-id="' + $this.data('id') + '"]').closest('li').remove();
                    md.html('');
                    mf.html('');
                    delete se[$this.data('id')];
                }
            });
        }

    });

    /* MEDIA FILTER SUBMIT */
    $(document).on("submit", "#media-filter", function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var $this = $(this),
            data  = $(this).serialize();
        $.ajax({
            url: ad.data('json-url'),
            data: data,
            dataType: "json",
            success: function(response){
                me = response;
                $.ajax({
                    url: ad.data('pagination-url'),
                    data: data,
                    success: function (response) {
                        var mp = $(".media-pagination");
                        mp.html(response);
                    }
                });
                fc.html(tmpl("template-download", me));
            }
        });
    });

    /* INSERT INTO TINY MCE */
    $(document).on("click", "#insert-media", function (e) {
        e.preventDefault();
        if(top.tinymce !== undefined){
            $.ajax({
                url: $(this).data('insert-url'),
                data: {media: se, _csrf: yii.getCsrfToken()},
                type: 'POST',
                success: function(response){
                    top.tinymce.activeEditor.execCommand("mceInsertContent", false, response);
                    top.tinymce.activeEditor.windowManager.close();
                }
            });
        }else{
            $.ajax({
                url: $(this).data('insert-url'),
                data: {media: se, _csrf: yii.getCsrfToken()},
                type: 'POST',
                success: function(response){
                    alert(response);
                }
            });
        }
    });

})(jQuery);