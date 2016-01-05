<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('writesdown', 'Comment Option') ?></h3>

        <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?= $form->field($model, 'post_comment_status', [
            'options'  => ['class' => 'checkbox'],
            'template' => "{input}",
        ])->checkbox([
            'label'   => Yii::t('writesdown', 'Allow comments on this post'),
            'checked' => true,
            'value'   => 'open',
            'uncheck' => 'close',
        ]) ?>

        <?php if (!$model->isNewRecord): ?>
            <?= Html::a(Yii::t('writesdown', '{commentCount} {commentWord} on this post', [
                'commentCount' => $model->post_comment_count,
                'commentWord'  => $model->post_comment_count > 1
                    ? Yii::t('writesdown', 'Comments')
                    : Yii::t('writesdown', 'Comment'),
            ]), [
                '/post-comment/index/',
                'post_type' => $model->postType->id,
                'post_id'   => $model->id,
            ]) ?>
        <?php endif ?>

    </div>
</div>
