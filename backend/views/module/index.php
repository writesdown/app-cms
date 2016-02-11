<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Module */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('writesdown', 'Modules');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-index">
    <div class="form-inline grid-nav" role="form">
        <div class="form-group">
            <?= Html::dropDownList('bulk-action', null, [
                'active' => Yii::t('writesdown', 'Active'),
                'not-active' => Yii::t('writesdown', 'Not Active'),
                'deleted' => Yii::t('writesdown', 'Delete Permanently'),
            ], [
                'class' => 'bulk-action form-control',
                'prompt' => Yii::t('writesdown', 'Bulk Action'),
            ]) ?>

            <?= Html::button(Yii::t('writesdown', 'Apply'), ['class' => 'btn btn-flat btn-warning bulk-button']) ?>

            <?= Html::a(
                Yii::t('writesdown', 'Add New Module'),
                ['create'],
                ['class' => 'btn btn-flat btn-primary']
            ) ?>

            <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), [
                'class' => 'btn btn-flat btn-info',
                'data-toggle' => 'collapse',
                'data-target' => '#module-search',
            ]) ?>

        </div>
    </div>
    <?php Pjax::begin() ?>
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'module-grid-view',
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],

            'name',
            'title:ntext',
            [
                'attribute' => 'status',
                'format' => 'boolean',
                'filter' => $searchModel->getStatuses(),
            ],
            'directory',
            [
                'attribute' => 'frontend_bootstrap',
                'format' => 'boolean',
                'filter' => $searchModel->getFrontendBootstraps(),
            ],
            [
                'attribute' => 'backend_bootstrap',
                'format' => 'boolean',
                'filter' => $searchModel->getBackendBootstraps(),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]) ?>

    <?php Pjax::end() ?>

</div>
<?php $this->registerJs('jQuery(document).on("click", ".bulk-button", function(e){
    e.preventDefault();
    if(confirm("' . Yii::t('writesdown', 'Are you sure?') . '")){
        var ids     = $("#module-grid-view").yiiGridView("getSelectedRows");
        var action  = $(this).closest(".form-group").find(".bulk-action").val();
        $.ajax({
            url: "' . Url::to(["bulk-action"]) . '",
            data: { ids: ids, action: action, _csrf: yii.getCsrfToken() },
            type: "POST",
            success: function(data){
                  $.pjax.reload({container:"#module-grid-view"});
            }
        });
    }
});') ?>
