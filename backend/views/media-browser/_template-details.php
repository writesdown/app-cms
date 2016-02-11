<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */
?>
<script id="template-details" type="text/x-tmpl">
    <h3><?= Yii::t('writesdown', 'Media Details') ?></h3>
    <div class="row">
        <div class="col-xs-6">
            <img alt="{%=o.title%}" src="{%=o.icon_url%}">
        </div>
        <div class="col-xs-6" style="padding-left:0">
            <h4 class="media-heading">{%=o.filename%}</h4>
            <div class="date">{%=o.date_formatted%}</div>
            <div class="file-size">{%=o.readable_size%}</div>
            <a class="text-danger delete-media" href="#" data-url="{%=o.delete_url%}" data-id="{%=o.id%}"
               data-confirm="<?= Yii::t('writesdown', 'Are you sure want to delete this item?') ?>">
               <i class="glyphicon glyphicon-trash"></i> <?= Yii::t('writesdown', 'Delete') ?>
            </a>
        </div>
    </div>
</script>
