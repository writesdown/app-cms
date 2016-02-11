<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Url;

/* @var $model \common\models\Media */
?>
<script id="template-form" type="text/x-tmpl">
    <form class="media-form-inner form-horizontal" action='<?= Url::to(['/site/forbidden']) ?>' data-method="post"
          data-id="{%=o.id%}">
        <input type="hidden" value="{%=o.id%}" name="id">
        <input type="hidden" value="{%=o.type%}" name="type">
        <div class="form-group">
            <label for="media-url}" class="col-sm-4 control-label"><?= Yii::t('writesdown', 'URL') ?></label>
            <div class="col-sm-8">
                <input id="media-url" type="text" class="form-control input-sm" placeholder="url"
                       value="<?= $model->getUploadUrl() ?>{%=o.versions.full.url%}" readonly="true" name="url">
            </div>
        </div>
        <div class="form-group">
            <label for="media-title" class="col-sm-4 control-label"><?= Yii::t('writesdown', 'Title') ?></label>
            <div class="col-sm-8">
                <input id="media-title" type="text" class="form-control input-sm" data-ajax-update="title"
                       placeholder="Title" value="{%=o.title%}" name="title">
            </div>
        </div>
        <div class="form-group">
            <label for="media-excerpt" class="col-sm-4 control-label"><?= Yii::t('writesdown', 'Caption') ?></label>
            <div class="col-sm-8">
                <textarea id="media-excerpt" class="form-control input-sm" data-ajax-update="excerpt"
                          placeholder="Caption" name="excerpt">{%=o.excerpt%}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="media-content" class="col-sm-4 control-label"><?= Yii::t('writesdown', 'Description') ?></label>
            <div class="col-sm-8">
                <textarea id="media-content" class="form-control input-sm" data-ajax-update="content"
                          placeholder="Descrption" name="content">{%=o.content%}</textarea>
            </div>
        </div>
        <h4><?= Yii::t('writesdown', 'Media Display Settings') ?></h4>
        {% if (o.type === 'image') { %}
            <div class="form-group">
                <label for="media-align" class="col-sm-4 control-label">
                    <?= Yii::t('writesdown', 'Alignment') ?>
                </label>
                <div class="col-sm-8">
                    <select id="media-align" class="form-control input-sm" name="align">
                        <option value="align-left"><?= Yii::t('writesdown', 'Left') ?></option>
                        <option value="align-center"><?= Yii::t('writesdown', 'Center') ?></option>
                        <option value="align-right"><?= Yii::t('writesdown', 'Right') ?></option>
                        <option value="align-none"><?= Yii::t('writesdown', 'None') ?></option>
                    </select>
                </div>
            </div>
        {% } %}
        <div class="form-group">
            <label for="media-link-to"" class="col-sm-4 control-label">
                <?= Yii::t('writesdown', 'Link to') ?>
            </label>
            <div class="col-sm-8">
                <select id="media-link-to" class="form-control input-sm" name="link_to">
                    <option value="{%=o.view_url%}"><?= Yii::t('writesdown', 'Media') ?></option>
                    <option value="<?= $model->getUploadUrl() ?>{%=o.versions.full.url%}">File</option>
                    {% if (o.type === 'image') { %}
                        <option value="custom"><?= Yii::t('writesdown', 'Custom URL') ?></option>
                        <option value="none"><?= Yii::t('writesdown', 'None') ?></option>
                    {% } %}
                </select>
                <input id="media-link-value" type="text" class="form-control input-sm" placeholder="Link to"
                       value="{%=o.view_url%}" style="margin-top: 2px;" readonly="true" name="link_value">
            </div>
        </div>
        {% if (o.type === 'image') { %}
            <div class="form-group">
                <label for="media-version" class="col-sm-4 control-label"><?= Yii::t('writesdown', 'Size') ?></label>
                <div class="col-sm-8">
                    <select id="media-version" class="form-control input-sm" name="version">
                       {% $.each(o.versions, function(version,value) { %}
                            <option value="{%=version%}">{%=version%}-{%=value.width%}x{%=value.height%}</option>
                       {% }); %}
                    </select>
                </div>
            </div>
        {% } %}
    </form>
</script>
