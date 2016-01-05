<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use dosamigos\datetimepicker\DateTimePicker;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $metadata [] */
/* @var $model common\models\Media */

$this->title = Yii::t('writesdown', 'Update Media');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Media'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => $model->url];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Update');
?>
<?php $form = ActiveForm::begin(['id' => 'media-update-form']) ?>

<div class="media-update">
    <div class="row">
        <div class="col-md-8">
            <?= $this->render('_form', [
                'model'    => $model,
                'form'     => $form,
                'metadata' => $metadata,
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'media_author',
                        'value'     => $model->mediaAuthor->username,
                    ],
                    [
                        'attribute' => 'media_post_id',
                        'format'    => 'raw',
                        'value'     => $model->media_post_id ? Html::a($model->mediaPost->post_title,
                            ['/post/update', 'id' => $model->mediaPost->id]) : Yii::t('writesdown', 'Unattached'),
                    ],
                    [
                        'attribute' => 'media_date',
                        'value'     => Html::a(Yii::$app
                                ->formatter
                                ->asDatetime(
                                    $model->media_date,
                                    'php:M d, Y H:i:s'
                                ) . ' <i class="fa fa-pencil"></i>', '#', [
                            'data-toggle' => 'modal',
                            'id'          => 'media-date-link',
                            'data-target' => '#modal-for-date',
                        ]),
                        'format'    => 'raw',
                    ],
                    'media_modified',
                    'media_mime_type',
                    [
                        'attribute' => 'media_comment_count',
                        'format'    => 'raw',
                        'value'     => Html::a($model->media_comment_count,
                            ['/media-comment/index', 'media_id' => $model->id]),
                    ],
                ],
            ]) ?>

            <?= !$model->isNewRecord
                ? Html::a(Yii::t('writesdown', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-wd-post btn-sm btn-flat btn-danger',
                    'data'  => [
                        'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
                    ],
                ])
                : '' ?>

        </div>
    </div>
</div>
<?php Modal::begin([
    'header' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('writesdown', 'Change Media Date') . '',
    'id'     => 'modal-for-date',
]) ?>

<?= $form->field($model, 'media_date', ['template' => "{label}\n{input}"])->widget(DateTimePicker::className(), [
    'template'       => '{reset}{button}{input}',
    'pickButtonIcon' => 'glyphicon glyphicon-time',
    'options'        => [
        'value' => date('M d, Y H:i:s', strtotime($model->media_date)),
    ],
    'clientOptions'  => [
        'autoclose' => true,
        'format'    => 'M dd, yyyy hh:ii:ss',
        'todayBtn'  => true,
    ],
]) ?>

<?php Modal::end() ?>

<?php ActiveForm::end() ?>

<?php $this->registerJs('$("#modal-for-date").on("hidden.bs.modal", function () {
    $("#media-date-link").html($("#media-media_date").val() + \' <i class="fa fa-pencil"></i>\');
});') ?>
