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
/* @var $model common\models\search\Media */
/* @var $form yii\widgets\ActiveForm */
?>

<div id="media-search" class="collapse media-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'post_title') ?>

            <?= $form->field($model, 'title') ?>

            <?= $form->field($model, 'slug') ?>

            <?= $form->field($model, 'excerpt') ?>

            <?= $form->field($model, 'content') ?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'password') ?>

            <?= $form->field($model, 'date') ?>

            <?= $form->field($model, 'modified') ?>

            <?= $form->field($model, 'mime_type') ?>

            <?= $form->field($model, 'comment_status')->dropDownList($model->getCommentStatuses(), [
                'prompt' => '',
            ]) ?>

            <?= $form->field($model, 'comment_count') ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn-flat btn btn-primary']) ?>

        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn-flat btn btn-default']) ?>

        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa fa-level-up']), [
            'class' => 'index-search-button btn btn-flat btn-default',
            'data-toggle' => 'collapse',
            'data-target' => '#media-search',
        ]) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
