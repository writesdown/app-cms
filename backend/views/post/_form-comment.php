<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
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
            <?php if (!$model->isNewRecord) {
                echo Html::a(
                    Yii::t(
                        'writesdown', '{n, plural,=0{# comments} =1{# Comment} other{# Comments}}',
                        ['n' => $model->comment_count]
                    ),
                    ['/post-comment/index/', 'posttype' => $model->postType->id, 'post' => $model->id],
                    ['class' => 'text-info']
                );
            } ?>

            <a href="#" data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></a>
        </div>
    </div>
    <div class="box-body">
        <?= $form->field($model, 'comment_status', [
            'options' => ['class' => 'checkbox'],
            'template' => "{input}",
        ])->checkbox([
            'label' => Yii::t('writesdown', 'Allow comments on this post'),
            'checked' => true,
            'value' => 'open',
            'uncheck' => 'close',
        ]) ?>

    </div>
</div>
