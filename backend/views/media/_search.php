<?php
/**
 * @file      _search.php.
 * @date      6/4/2015
 * @time      5:42 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\Media */
/* @var $form yii\widgets\ActiveForm */
?>

<div id="media-search" class="collapse media-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-sm-6">

            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'post_title') ?>

            <?= $form->field($model, 'media_title') ?>

            <?= $form->field($model, 'media_slug') ?>

            <?= $form->field($model, 'media_excerpt') ?>

            <?= $form->field($model, 'media_content') ?>

        </div>
        <div class="col-sm-6">

            <?= $form->field($model, 'media_password') ?>

            <?= $form->field($model, 'media_date') ?>

            <?= $form->field($model, 'media_modified') ?>

            <?= $form->field($model, 'media_mime_type') ?>

            <?= $form->field($model, 'media_comment_status')->dropDownList($model->getCommentStatus(), ['prompt' => '']) ?>

            <?= $form->field($model, 'media_comment_count') ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn-flat btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn-flat btn btn-default']) ?>
        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa fa-level-up']), ['class' => 'index-search-button btn btn-flat btn-default', "data-toggle" => "collapse", "data-target" => "#media-search"]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>