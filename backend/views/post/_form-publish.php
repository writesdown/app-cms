<?php
/**
 * @file    _form-publish.php.
 * @date    6/4/2015
 * @time    6:13 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('writesdown', 'Publish'); ?></h3>

        <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">

        <?= $form->field($model, 'post_date', ['template'=>'{input}'])->widget(DateTimePicker::className(), [
            'size' => 'sm',
            'template' => '{reset}{button}{input}',
            'pickButtonIcon' => 'glyphicon glyphicon-time',
            'options' => [
                'value'=>$model->isNewRecord? date('M d, Y h:i:s'): Yii::$app->formatter->asDatetime($model->post_date, 'php:M d, Y h:i:s')
            ],
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'M dd, yyyy hh:ii:ss',
                'todayBtn' => true,
            ]
        ]); ?>

        <?= $form->field($model, 'post_status', [
            'template'=>"{input}"
        ])->dropDownList( Yii::$app->user->can('author') ? $model->getPostStatus() : [  $model::POST_STATUS_REVIEW => 'Review' ], [
            'class' => 'form-control input-sm'
        ]); ?>

        <?= $form->field($model, 'post_password', ['template'=>"{input}"])->textInput(['maxlength' => 255, 'placeholder'=>'Password', 'class' => 'form-control input-sm']) ?>

    </div>

    <div class="box-footer">

        <?= Html::submitButton( Yii::t('writesdown', 'Publish'), ['class' => 'btn btn-sm btn-flat btn-primary']) ?>

        <?= !$model->isNewRecord ? Html::a(Yii::t('writesdown', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-wd-post btn-sm btn-flat btn-danger pull-right',
            'data' => [
                'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
            ],
        ]) : '' ?>

    </div>

</div>