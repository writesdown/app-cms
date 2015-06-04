<?php
/**
 * @file    _search.php.
 * @date    6/4/2015
 * @time    6:29 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $postType common\models\search\PostType */
/* @var $model common\models\search\PostComment */
?>

<div id="post-comment-search" class="collapse post-comment-search">

    <?php $form = ActiveForm::begin([
        'action' => [
            'index',
            'post_type' => $postType->id,
            'post_id'   => isset($post) ? $post->id : null
        ],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-sm-6">

            <?= $form->field($model, 'post_title') ?>

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
        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-level-up']), ['class' => 'index-search-button btn btn-flat btn-default', "data-toggle" => "collapse", "data-target" => "#post-comment-search"]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
