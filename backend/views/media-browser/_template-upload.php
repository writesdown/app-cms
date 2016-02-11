<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */
?>
<script id="template-upload" type="text/x-tmpl">
{% if (o.files) { %}
    {% for (var i=0, file; file=o.files[i]; i++) { %}
        <li class="fade media-item">
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
        </li>
    {% } %}
{% } %}
</script>
