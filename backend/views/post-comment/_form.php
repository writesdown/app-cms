<?php
/**
 * @file      _form.php.
 * @date      6/4/2015
 * @time      6:29 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use codezeen\yii2\tinymce\TinyMce;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PostComment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-comment-form">

    <?= $form->field($model, 'comment_author')->textInput(['placeholder' => $model->getAttributeLabel('comment_author')]) ?>

    <?= $form->field($model, 'comment_author_email')->textInput(['maxlength' => 100, 'placeholder' => $model->getAttributeLabel('comment_author_email')]) ?>

    <?= $form->field($model, 'comment_author_url')->textInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('comment_author_url')]) ?>

    <?= $form->field($model, 'comment_content', ["template" => "{input}\n{error}"])->widget(
        TinyMce::className(),
        [
            'compressorRoute' => 'helper/tiny-mce-compressor',
            'settings'        => [
                'menubar'            => false,
                'skin_url'           => Yii::$app->urlManager->baseUrl . '/editor-skins/writesdown',
                'toolbar_items_size' => 'medium',
                'toolbar'            => "bold | italic | strikethrough | underline | link | image | bullist | numlist",
            ],
            'options'         => [
                'style' => 'height:200px;'
            ],
        ]
    ) ?>

    <?= $form->field($model, 'comment_approved')->dropDownList($model->getCommentApproved()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Update'), ['class' => 'btn btn-flat btn-primary']) ?>
    </div>

</div>
