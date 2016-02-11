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
/* @var $model common\models\Option */

$this->title = Yii::t('writesdown', 'View Setting: {name}', ['name' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="option-view">
    <p>
        <?= Html::a(
            Yii::t('writesdown', 'Update'),
            ['update', 'id' => $model->id],
            ['class' => 'btn-flat btn btn-primary']
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
            'id',
            'name',
            'value:ntext',
            'label',
            'group',
        ],
    ]) ?>

</div>
