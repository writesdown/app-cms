<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */
?>
<script id="template-download" type="text/x-tmpl">
{% if (o.files) { %}
    {% for (var i=0, file; file=o.files[i]; i++) { %}
        {% if (file.icon_url) { %}
            <li class="media-item" data-id={%=file.id%} id=media-{%=file.id%}>
                <div class="item">
                    <img src="{%=file.icon_url%}">
                    <span class="description"><span>{%=file.title%}</span></span>
                    <span class="fa selected-check"></span>
                </div>
            </li>
        {% } %}
    {% } %}
{% } %}
</script>
