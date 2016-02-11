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
/* @var $form yii\widgets\ActiveForm */
/* @var $postType common\models\PostType */
/* @var $model common\models\search\Post */
/* @var $user string */

?>

<div id="post-search" class="post-search collapse">
    <?php $form = ActiveForm::begin([
        'action' => isset($user)
            ? ['index', 'type' => $postType->id, 'user' => $user]
            : ['index', 'type' => $postType->id],
        'method' => 'get',
    ]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'id') ?>

            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'title') ?>

            <?= $form->field($model, 'slug') ?>

            <?= $form->field($model, 'excerpt') ?>

            <?= $form->field($model, 'content') ?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'modified') ?>

            <?= $form->field($model, 'status')->dropDownList($model->getPostStatuses(), ['prompt' => '']) ?>

            <?= $form->field($model, 'password') ?>

            <?= $form->field($model, 'date') ?>

            <?= $form->field($model, 'comment_status')->dropDownList(
                $model->getCommentStatuses(),
                ['prompt' => '']
            ) ?>

            <?= $form->field($model, 'comment_count') ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn btn-flat btn-primary']) ?>

        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn btn-flat btn-default']) ?>

        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa fa-level-up']), [
            'class' => 'index-search-button btn btn-flat btn-default',
            'data-toggle' => 'collapse',
            'data-target' => '#post-search',
        ]) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
