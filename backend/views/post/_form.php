<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use backend\widgets\MediaModal;
use codezeen\yii2\tinymce\TinyMce;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="post-form">
    <?= $form->field($model, 'title', ['template' => '{input}{error}'])->textInput([
        'placeholder' => $model->getAttributeLabel('title'),
    ]) ?>

    <?= $form->field($model, 'slug', [
        'template' => '<span class="input-group-addon">' . $model->getAttributeLabel('slug') . '</span>{input}',
        'options' => [
            'class' => 'input-group form-group input-group-sm',
        ],
    ])->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('slug')]) ?>

    <?php if (Yii::$app->user->can('author')): ?>
        <div class="form-group">
            <?= MediaModal::widget([
                'post' => $model->isNewRecord ? null : $model->id,
                'editor' => true,
                'multiple' => true,
                'buttonOptions' => [
                    'class' => ['btn btn-sm btn-default btn-flat'],
                ],
            ]) ?>
        </div>
    <?php endif ?>

    <?= $form->field($model, 'content', ["template" => "{input}\n{error}"])->widget(
        TinyMce::className(),
        [
            'compressorRoute' => 'editor/compressor',
            'settings' => [
                'menubar' => false,
                'skin_url' => Url::base(true) . '/editor/skins/writesdown',
                'toolbar_items_size' => 'medium',
                'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter '
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
                'relative_urls' => false,
                'remove_script_host' => false,
            ],
            'options' => [
                'id' => 'post-content',
                'style' => 'height:400px;',
            ],
        ]
    ) ?>

</div>
