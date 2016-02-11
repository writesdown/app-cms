<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
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
        'action' => ['index', 'media' => isset($media) ? $media->id : null],
        'method' => 'get',
    ]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'media_title') ?>

            <?= $form->field($model, 'author') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'url') ?>

            <?= $form->field($model, 'ip') ?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'date') ?>

            <?= $form->field($model, 'content') ?>

            <?= $form->field($model, 'status')->dropDownList($model->getStatuses(), ['prompt' => '']) ?>

            <?= $form->field($model, 'agent') ?>

            <?= $form->field($model, 'parent') ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn btn-flat btn-primary']) ?>

        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn btn-flat btn-default']) ?>

        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-level-up']), [
            'class' => 'index-search-button btn btn-flat btn-default',
            'data-toggle' => 'collapse',
            'data-target' => '#media-comment-search',
        ]) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
