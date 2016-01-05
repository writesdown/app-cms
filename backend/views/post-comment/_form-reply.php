<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use codezeen\yii2\tinymce\TinyMce;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model common\models\PostComment */
?>
<div class="post-comment-form">
    <?= $form->field($model, 'comment_content', ["template" => "{input}\n{error}"])->widget(
        TinyMce::className(),
        [
            'compressorRoute' => 'helper/tiny-mce-compressor',
            'settings'        => [
                'menubar'            => false,
                'skin_url'           => Url::base(true) . '/editor-skins/writesdown',
                'toolbar_items_size' => 'medium',
                'toolbar'            => "bold | italic | strikethrough | underline | link | image | bullist | numlist",
            ],
            'options'         => [
                'id'    => 'postcomment-comment_content',
                'style' => 'height:200px;',
            ],
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Reply'), ['class' => 'btn-flat btn btn-primary']) ?>

    </div>
</div>
