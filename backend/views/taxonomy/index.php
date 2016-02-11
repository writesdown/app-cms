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
/* @var $searchModel common\models\search\Taxonomy */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('writesdown', 'Taxonomies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="taxonomy-index">
    <div class="form-inline grid-nav" role="form">
        <div class="form-group">
            <?= Html::dropDownList('bulk-action', null, ['deleted' => Yii::t('writesdown', 'Delete Permanently')], [
                'prompt' => Yii::t('writesdown', 'Bulk Action'),
                'class' => 'bulk-action form-control',
            ]) ?>

            <?= Html::button(Yii::t('writesdown', 'Apply'), ['class' => 'btn btn-flat btn-warning bulk-button']) ?>

            <?= Html::a(
                Yii::t('writesdown', 'Add New Taxonomy'),
                ['create'],
                ['class' => 'btn btn-flat btn-primary']
            ) ?>

            <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), [
                'class' => 'btn btn-flat btn-info',
                'data-toggle' => 'collapse',
                'data-target' => '#taxonomy-search',
            ]) ?>

        </div>
    </div>
    <?php Pjax::begin() ?>
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'taxonomy-grid-view',
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],

            'name',
            'slug',
            ['attribute' => 'hierarchical', 'format' => 'boolean', 'filter' => $searchModel->getHierarchies()],
            'singular_name',
            'plural_name',
            ['attribute' => 'menu_builder', 'format' => 'boolean', 'filter' => $searchModel->getMenuBuilders()],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]) ?>

    <?php Pjax::end() ?>

</div>
<?php $this->registerJs('jQuery(".bulk-button").click(function(e){
    e.preventDefault();
    if(confirm("' . Yii::t("app", "Are you sure?") . '")){
        var ids     = $("#taxonomy-grid-view").yiiGridView("getSelectedRows");
        var action  = $(this).parents(".form-group").find(".bulk-action").val();
        $.ajax({
            url: "' . Url::to(["bulk-action"]) . '",
            data: { ids: ids, action: action, _csrf: yii.getCsrfToken() },
            type:"POST",
            success: function(response){
                $.pjax.reload({container:"#taxonomy-grid-view"});
            }
        });
    }
});') ?>
