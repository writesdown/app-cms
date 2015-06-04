<?php
/**
 * @file      _form.php.
 * @date      6/4/2015
 * @time      5:41 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use codezeen\yii2\tinymce\TinyMce;
use dosamigos\selectize\SelectizeDropDownList;

/* @var $this yii\web\View */
/* @var $model common\models\Media */
/* @var $form yii\widgets\ActiveForm */
/* @var $metadata [] */
?>

<div class="media-form">

    <?= $form->field($model, 'media_title', ['template' => '{input}{error}'])->textInput(['placeholder' => $model->getAttributeLabel('media_title')]) ?>

    <?= $form->field($model, 'media_slug', [
        'template' => '<span class="input-group-addon">' . $model->getAttributeLabel('media_slug') . '</span>{input}',
        'options'  => [
            'class' => 'input-group form-group input-group-sm'
        ],
    ])->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('media_slug')]) ?>

    <div class="form-group">
        <?php
        if (preg_match('/^image\//', $model->media_mime_type)) {
            echo Html::img($model->uploadUrl . $metadata['media_versions']['full']['url'], [
                'width'  => $metadata['media_versions']['full']['width'],
                'height' => $metadata['media_versions']['full']['height'],
                'class'  => 'thumbnail'
            ]);
        } else if (preg_match('/^video\//', $model->media_mime_type)) {
            echo '<video controls class="full-width">';
            echo '<source src="' . $model->uploadUrl . $metadata['media_versions']['full']['url'] . '" type="' . $model->media_mime_type . '">';
            echo Yii::t('writesdown', 'Your browser does not support HTML5 video.');
            echo '</video>';
        } else if (preg_match('/^audio\//', $model->media_mime_type)) {
            echo '<audio controls class="full-width">';
            echo '<source src="' . $model->uploadUrl . $metadata['media_versions']['full']['url'] . '" type="' . $model->media_mime_type . '">';
            echo Yii::t('writesdown', 'Your browser does not support HTML5 audio.');
            echo '</video>';
        } else {
            echo Html::textInput('media-file-name', $model->uploadUrl . $metadata['media_versions']['full']['url'], ['class' => 'form-control input-sm', 'readonly' => 'readonly']);
        }
        ?>

    </div>

    <?= $form->field($model, 'media_excerpt')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'media_content')->widget(
        TinyMce::className(),
        [
            'compressorRoute' => 'helper/tiny-mce-compressor',
            'settings'        => [
                'menubar'            => false,
                'skin_url'           => Yii::$app->urlManager->baseUrl . '/editor-skins/writesdown',
                'toolbar_items_size' => 'medium',
                'toolbar'            => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code fullscreen",
                'formats'            => [
                    'alignleft'   => ['selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'align-left'],
                    'aligncenter' => ['selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'align-center'],
                    'alignright'  => ['selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'align-right'],
                    'alignfull'   => ['selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'align-full']
                ]
            ],
            'options'         => [
                'style' => 'height:250px;'
            ],
        ]
    ) ?>

    <?= $form->field($model, 'media_password')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'media_post_id')->widget(SelectizeDropDownList::className(), [
        'items'         => $model->mediaPost ? [$model->mediaPost->id => $model->mediaPost->post_title] : [],
        'value'         => $model->media_post_id,
        'clientOptions' => [
            'valueField'  => 'id',
            'labelField'  => 'post_title',
            'searchField' => 'post_title',
            'load'        => new JsExpression('function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                url: "' . Url::to(['/post/ajax-search']) . '",
                    type: "POST",
                    dataType: "json",
                    data: {
                        post_title: query,
                        _csrf: yii.getCsrfToken()
                    },
                    error: function() {
                        callback();
                    },
                    success: function(response) {
                        callback(response);
                    }
                });
            }')
        ]
    ]) ?>

    <?= $form->field($model, 'media_comment_status')->checkbox([
        'label'   => Yii::t('writesdown', 'Allow comments on this media'),
        'checked' => true,
        'value'   => 'open',
        'uncheck' => 'close'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Update'), ['class' => 'btn btn-flat btn-primary']) ?>
    </div>

</div>