<?php
/**
 * @file      _search.php.
 * @date      6/4/2015
 * @time      6:00 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MediaComment */
/* @var $form yii\widgets\ActiveForm */
/* @var $media common\models\Media|null */
?>

<div id="media-comment-search" class="collapse media-comment-search">

    <?php $form = ActiveForm::begin([
        'action' => [
            'index',
            'media_id' => isset($media) ? $media->id : null
        ],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-sm-6">

            <?= $form->field($model, 'media_title') ?>

            <?= $form->field($model, 'comment_author') ?>

            <?= $form->field($model, 'comment_author_email') ?>

            <?= $form->field($model, 'comment_author_url') ?>

            <?= $form->field($model, 'comment_author_ip') ?>

        </div>
        <div class="col-sm-6">

            <?= $form->field($model, 'comment_date') ?>

            <?= $form->field($model, 'comment_content') ?>

            <?= $form->field($model, 'comment_approved')->dropDownList($model->getCommentApproved(), ['prompt' => '']) ?>

            <?= $form->field($model, 'comment_agent') ?>

            <?= $form->field($model, 'comment_parent') ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn btn-flat btn-primary']) ?>
        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn btn-flat btn-default']) ?>
        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-level-up']), ['class' => 'index-search-button btn btn-flat btn-default', "data-toggle" => "collapse", "data-target" => "#media-comment-search"]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
