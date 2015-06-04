<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      12:01 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel common\models\search\Term */
/* @var $taxonomy common\models\Taxonomy */

?>
    <div class="term-index">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'id'           => 'term-grid-view',
            'layout'       => "{items}\n{pager}",
            'columns'      => [
                ['class' => 'yii\grid\CheckboxColumn'],

                'term_name',
                'term_slug',
                'term_description:ntext',
                'term_count',

                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'buttons'  => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/taxonomy/update-term', 'id' => $model->taxonomy->id, 'term_id' => $model->id], [
                                'title'     => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/taxonomy/delete-term', 'id' => $model->taxonomy->id, 'term_id' => $model->id], [
                                'title'        => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method'  => 'post',
                                'data-pjax'    => '0',
                            ]);
                        }

                    ]
                ],
            ],
        ]); ?>

        <div class="form-inline grid-nav" role="form">
            <div class="form-group">
                <?= Html::dropDownList('bulk-action', null, ['delete' => 'Delete'], ['prompt' => 'Bulk Action', 'class' => 'bulk-action form-control']); ?>
                <?= Html::button(Yii::t('writesdown', 'Apply'), ['class' => 'btn btn-flat btn-warning bulk-button']); ?>
                <?= Html::a(Yii::t('writesdown', 'Add New {taxonomy}', ['taxonomy' => $taxonomy->taxonomy_sn]), ['/taxonomy/view', 'id' => $taxonomy->id], ['class' => 'btn btn-flat btn-primary']) ?>
                <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), ['class' => 'btn btn-flat btn-info', "data-toggle" => "collapse", "data-target" => "#term-search"]); ?>
            </div>
        </div>

        <?= $this->render('_search', ['model' => $searchModel, 'taxonomy' => $taxonomy]); ?>

    </div>

<?php
$this->registerJs('
jQuery(".bulk-button").click(function(e){
    e.preventDefault();
    if(confirm("' . Yii::t("app", "Are you sure to do this?") . '")){
        var ids     = $("#term-grid-view").yiiGridView("getSelectedRows"); // returns an array of pkeys, and #grid is your grid element id
        var action  = $(this).parents(".form-group").find(".bulk-action").val();
        $.ajax({
            url: "' . Url::to(["/term/bulk-action"]) . '",
            data: { ids: ids, action: action, _csrf: yii.getCsrfToken() },
            type:"POST",
            success: function(data){
                $.pjax.reload({container:"#term-grid-view"});
            }
        });
    }
});'
);