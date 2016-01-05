<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $media common\models\Media */

$this->title = Html::encode($media->media_title . ' - ' . Option::get('sitetitle'));

if ($media->mediaPost) {
    $this->params['breadcrumbs'][] = [
        'label' => Html::encode($media->mediaPost->post_title),
        'url'   => $media->mediaPost->url,
    ];
}

$this->params['breadcrumbs'][] = Html::encode($media->media_title);
?>
<div class="single media-protected">
    <article class="hentry">
        <header class="entry-header page-header">
            <h1 class="entry-title"><?= Html::encode($media->media_title) ?></h1>

        </header>
        <div class="entry-content">
            <?php $form = ActiveForm::begin() ?>

            <p>
                <?= Yii::t(
                    'writesdown',
                    '{media_title} is protected, please submit the right password to view the Media.',
                    ['media_title' => Html::encode($media->media_title)]
                ) ?>

            </p>
            <div class="form-group field-media-media_password required">
                <?= Html::label(
                    Yii::t('writesdown', 'Password'),
                    'media-media_password',
                    ['class' => 'control-label']
                ) ?>

                <?= Html::passwordInput('password', null, [
                    'class' => 'form-control',
                    'id'    => 'media-media_password',
                ]) ?>

            </div>
            <div class="form-group">
                <?= Html::submitButton(
                    Yii::t('writesdown', 'Submit Password'),
                    ['class' => 'btn btn-flat btn-primary']
                ) ?>

            </div>
            <?php ActiveForm::end() ?>

        </div>
    </article>
</div>
