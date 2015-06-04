<?php
/**
 * @file    index.php.
 * @date    6/4/2015
 * @time    6:33 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PostType */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('writesdown', 'Post Types');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="post-type-index">

        <div class="form-inline grid-nav" role="form">
            <div class="form-group">
                <?= Html::dropDownList('bulk-action', null, ['delete' => 'Delete'], ['class' => 'bulk-action form-control', 'prompt' => 'Bulk Action']); ?>
                <?= Html::button(Yii::t('writesdown', 'Apply'), ['class' => 'btn btn-flat btn-warning bulk-button']); ?>
                <?= Html::a(Yii::t('writesdown', 'Add New Post Type'), ['create'], ['class' => 'btn btn-flat btn-primary']) ?>
                <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), ['class' => 'btn btn-flat btn-info', "data-toggle" => "collapse", "data-target" => "#post-type-search"]); ?>
            </div>
        </div>

        <?php Pjax::begin(); ?>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'id'           => 'post-type-grid-view',
            'columns'      => [
                ['class' => 'yii\grid\CheckboxColumn'],

                'post_type_name',
                'post_type_slug',
                'post_type_description:ntext',
                [
                    'attribute' => 'post_type_icon',
                    'format'    => 'raw',
                    'value'     => function ($model) {
                        return Html::tag('i', '', ['class' => $model->post_type_icon]);
                    },
                    'filter'    => false
                ],
                'post_type_sn',
                'post_type_pn',
                [
                    'attribute' => 'post_type_smb',
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
    if(confirm("' . Yii::t("app", "All posts on these post types will be affected. Are you sure?") . '")){
        var ids     = $("#post-type-grid-view").yiiGridView("getSelectedRows");
        var action  = $(this).parents(".form-group").find(".bulk-action").val();
        $.ajax({
            url: "' . Url::to(["/post-type/bulk-action"]) . '",
            data: { ids: ids, action: action, _csrf: yii.getCsrfToken() },
            type:"POST",
            success: function(data){
                $.pjax.reload({container:"#post-type-grid-view"});
            }
        });
    }
});'
);