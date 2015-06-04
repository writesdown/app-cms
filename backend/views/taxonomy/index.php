<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      11:59 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Taxonomy */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('writesdown', 'Taxonomies');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="taxonomy-index">

        <div class="form-inline grid-nav" role="form">
            <div class="form-group">
                <?= Html::dropDownList('bulk-action', null, ['delete' => 'Delete'], ['prompt' => Yii::t('writesdown', 'Bulk Action'), 'class' => 'bulk-action form-control']); ?>
                <?= Html::button(Yii::t('writesdown', 'Apply'), ['class' => 'btn btn-flat btn-warning bulk-button']); ?>
                <?= Html::a(Yii::t('writesdown', 'Add New Taxonomy'), ['create'], ['class' => 'btn btn-flat btn-primary']) ?>
                <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), ['class' => 'btn btn-flat btn-info', "data-toggle" => "collapse", "data-target" => "#taxonomy-search"]); ?>
            </div>
        </div>

        <?php Pjax::begin(); ?>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'id'           => 'taxonomy-grid-view',
            'columns'      => [
                ['class' => 'yii\grid\CheckboxColumn'],

                'taxonomy_name',
                'taxonomy_slug',
                [
                    'attribute' => 'taxonomy_hierarchical',
                    'format'    => 'boolean',
                    'filter'    => $searchModel->hierarchical
                ],
                'taxonomy_sn',
                'taxonomy_pn',
                [
                    'attribute' => 'taxonomy_smb',
                    'format'    => 'boolean',
                    'filter'    => $searchModel->smb
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>
<?php
$this->registerJs('
jQuery(".bulk-button").click(function(e){
    e.preventDefault();
    if(confirm("' . Yii::t("app", "Are you sure to do this?") . '")){
        var ids     = $("#taxonomy-grid-view").yiiGridView("getSelectedRows"); // returns an array of pkeys, and #grid is your grid element id
        var action  = $(this).parents(".form-group").find(".bulk-action").val();
        $.ajax({
            url: "' . Url::to(["/taxonomy/bulk-action"]) . '",
            data: { ids: ids, action: action, _csrf: yii.getCsrfToken() },
            type:"POST",
            success: function(response){
                $.pjax.reload({container:"#taxonomy-grid-view"});
            }
        });
    }
});'
);