<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use codezeen\yii2\tinymce\TinyMce;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Media */
/* @var $form yii\widgets\ActiveForm */
/* @var $metadata [] */
?>
<div class="media-form">

    <?= $form->field($model, 'title', [
        'template' => '{input}{error}',
    ])->textInput(['placeholder' => $model->getAttributeLabel('title')]) ?>

    <?= $form->field($model, 'slug', [
        'template' => '<span class="input-group-addon">' . $model->getAttributeLabel('slug') . '</span>{input}',
        'options' => ['class' => 'input-group form-group input-group-sm'],
    ])->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('slug')]) ?>

    <!-- MEDIA PREVIEW BEGIN -->
    <div class="form-group">

        <?php if (preg_match('/^image\//', $model->mime_type)): ?>
            <?= $model->getThumbnail('full', ['class' => 'thumbnail']) ?>
        <?php elseif (preg_match('/^video\//', $model->mime_type)): ?>
            <video controls class="full-width">
                <source src="<?= $model->getUploadUrl() . $metadata['versions']['full']['url'] ?>"
                        type="<?= $model->mime_type ?>">
                <?= Yii::t('writesdown', 'Your browser does not support HTML5 video.') ?>
            </video>
        <?php elseif (preg_match('/^audio\//', $model->mime_type)): ?>
            <audio controls class="full-width">
                <source src="<?= $model->getUploadUrl() . $metadata['versions']['full']['url'] ?>"
                        type="<?= $model->mime_type ?>">
                <?= Yii::t('writesdown', 'Your browser does not support HTML5 audio.') ?>
            </audio>
        <?php else: ?>
            <?= Html::textInput('media-file-name', $model->getUploadUrl() . $metadata['versions']['full']['url'], [
                'class' => 'form-control input-sm',
                'readonly' => 'readonly',
            ]) ?>
        <?php endif ?>

    </div>
    <!-- MEDIA PREVIEW END -->

    <?= $form->field($model, 'excerpt')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->widget(
        TinyMce::className(),
        [
            'compressorRoute' => 'editor/compressor',
            'settings' => [
                'menubar' => false,
                'skin_url' => Url::base(true) . '/editor/skins/writesdown',
                'toolbar_items_size' => 'medium',
                'toolbar' => 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter '
                    . 'alignright alignjustify | bullist numlist outdent indent | link image | code fullscreen',
                'formats' => [
                    'alignleft' => [
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
                        'classes' => 'align-left',
                    ],
                    'aligncenter' => [
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
                        'classes' => 'align-center',
                    ],
                    'alignright' => [
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
                        'classes' => 'align-right',
                    ],
                ],
            ],
            'options' => [
                'style' => 'height:250px;',
            ],
        ]
    ) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'post_id')->widget(SelectizeDropDownList::className(), [
        'items' => $model->mediaPost ? [$model->mediaPost->id => $model->mediaPost->title] : [],
        'value' => $model->post_id,
        'clientOptions' => [
            'valueField' => 'id',
            'labelField' => 'title',
            'searchField' => 'title',
            'load' => new JsExpression('function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: "' . Url::to(['/post/ajax-search']) . '",
                        type: "POST",
                        dataType: "json",
                        data: {
                            title: query,
                            _csrf: yii.getCsrfToken()
                        },
                        error: function() {
                            callback();
                        },
                        success: function(response) {
                            callback(response);
                        }
                    });
                }'
            ),
        ],
    ]) ?>

    <?= $form->field($model, 'comment_status')->checkbox([
        'label' => Yii::t('writesdown', 'Allow comments on this media'),
        'checked' => true,
        'value' => 'open',
        'uncheck' => 'close',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Update'), ['class' => 'btn btn-flat btn-primary']) ?>
    </div>
</div>
