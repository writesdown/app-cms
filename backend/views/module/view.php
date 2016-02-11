<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Module */

$this->title = Yii::t('writesdown', 'View Module: {title}', ['title' => $model->title]);
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
            'data' => [
                'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>

    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'title:ntext',
            'description:ntext',
            'status:boolean',
            'directory',
            'backend_bootstrap:boolean',
            'frontend_bootstrap:boolean',
            'date:datetime',
            'modified:datetime',
        ],
    ]) ?>

</div>
