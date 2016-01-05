<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Module */

$this->title = Yii::t('writesdown', 'View Module: {module_title}', ['module_title' => $model->module_title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Modules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-view">
    <p>
        <?= Html::a(
            Yii::t('writesdown', 'Update'),
            ['update', 'id' => $model->id],
            ['class' => 'btn btn-flat btn-primary']
        ) ?>

        <?= Html::a(Yii::t('writesdown', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-flat btn-danger',
            'data'  => [
                'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
                'method'  => 'post',
            ],
        ]) ?>

    </p>
    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'module_name',
            'module_title:ntext',
            'module_description:ntext',
            'module_status:boolean',
            'module_dir',
            'module_bb:boolean',
            'module_fb:boolean',
            'module_date:datetime',
            'module_modified:datetime',
        ],
    ]) ?>

</div>
