<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      11:51 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Option */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('writesdown', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="option-index">

    <div class="form-inline grid-nav" role="form">
        <div class="form-group">

            <?= Html::a(Yii::t('writesdown', 'Add New Setting'), ['create'], ['class' => 'btn btn-flat btn-success']) ?>
            <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), ['class' => 'btn btn-flat btn-info', "data-toggle" => "collapse", "data-target" => "#option-search"]); ?>

        </div>
    </div>

    <?php Pjax::begin(); ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'option_name',
            'option_value:ntext',
            'option_label',
            'option_group',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
