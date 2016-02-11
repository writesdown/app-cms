<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use codezeen\yii2\tinymce\TinyMce;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\PostComment */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="post-comment-form">
    <?= $form->field($model, 'author')->textInput([
        'placeholder' => $model->getAttributeLabel('author'),
    ]) ?>

    <?= $form->field($model, 'email')->textInput([
        'maxlength' => 100,
        'placeholder' => $model->getAttributeLabel('email'),
    ]) ?>

    <?= $form->field($model, 'url')->textInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('url'),
    ]) ?>

    <?= $form->field($model, 'content', ["template" => "{input}\n{error}"])->widget(
        TinyMce::className(),
        [
            'compressorRoute' => 'editor/compressor',
            'settings' => [
                'menubar' => false,
                'skin_url' => Url::base(true) . '/editor/skins/writesdown',
                'toolbar_items_size' => 'medium',
                'toolbar' => "bold | italic | strikethrough | underline | link | image | bullist | numlist",
            ],
            'options' => [
                'id' => 'postcomment-content',
                'style' => 'height:200px;',
            ],
        ]
    ) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatuses()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Update'), ['class' => 'btn btn-flat btn-primary']) ?>

    </div>
</div>
