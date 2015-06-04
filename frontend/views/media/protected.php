<?php
/**
 * @file    protected.php.
 * @date    6/4/2015
 * @time    11:30 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $media common\models\Media */

$this->title = $media->media_title;
if ($media->mediaPost) {
    $this->params['breadcrumbs'][] = ['label' => $media->mediaPost->post_title, 'url' => $media->mediaPost->url];
}
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
    'name'    => 'robots',
    'content' => 'noindex, nofollow'
]);
?>

<div class="post-protected">
    <?php $form = ActiveForm::begin(); ?>

    <p><?= Yii::t('writesdown', 'The media is protected, therefore, please type the right password to view the media.'); ?></p>

    <div class="form-group field-posttype-post_type_name required">
        <?= Html::label(Yii::t('writesdown', 'Password'), 'post-post_password', ['class' => 'control-label']); ?>
        <?= Html::passwordInput('password', null, ['class' => 'form-control', 'id' => 'post-post_password']); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Submit The Password'), ['class' => 'btn btn-flat btn-primary']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>