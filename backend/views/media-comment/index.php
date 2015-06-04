<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      6:01 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MediaComment */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $media common\models\Media|null */

$this->title = Yii::t('writesdown', 'Media Comments');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="media-comment-index">

        <div class="form-inline grid-nav" role="form">
            <div class="form-group">
                <?= Html::dropDownList('bulk-action', null, ArrayHelper::merge($searchModel->getCommentApproved(), ['delete' => 'Delete']), [
                    'class'  => 'bulk-action form-control',
                    'prompt' => Yii::t('writesdown', 'Bulk Action')
                ]); ?>
                <?= Html::button(Yii::t('writesdown', 'Apply'), ['class' => 'btn btn-flat btn-warning bulk-button']); ?>
                <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), ['class' => 'btn btn-flat btn-info', "data-toggle" => "collapse", "data-target" => "#media-comment-search"]); ?>
            </div>
        </div>

        <?php Pjax::begin(); ?>

        <?= $this->render('_search', ['model' => $searchModel, 'media' => $media]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'id'           => 'media-comment-grid-view',
            'columns'      => [
                ['class' => 'yii\grid\CheckboxColumn'],

                'comment_author:ntext',
                'comment_author_email:email',
                [
                    'attribute' => 'comment_content',
                    'format'    => 'html',
                    'value'     => function ($model) {
                        return substr(strip_tags($model->comment_content), 0, 150) . '...';
                    },
                ],
                [
                    'attribute' => 'comment_approved',
                    'filter'    => [
                        'approved'   => 'Approved',
                        'unapproved' => 'Unapproved',
                        'trash'      => 'Trash'
                    ]
                ],

                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => Yii::$app->user->can('editor') ? '{view} {update} {delete} {reply}' : '{view}',
                    'buttons'  => [
                        'view'  => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $model->commentMedia->url . '#comment' . $model->id, [
                                'title'     => Yii::t('yii', 'View'),
                                'data-pjax' => '0',
                            ]);
                        },
                        'reply' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-share-alt"></span>', ['media-comment/reply', 'id' => $model->id], [
                                'title'     => Yii::t('writesdown', 'Reply'),
                                'data-pjax' => '0',
                            ]);
                        }
                    ]
                ],
            ],

        ]); ?>

        <?php Pjax::end(); ?>

    </div>

<?php
$this->registerJs('
jQuery(".bulk-button").click(function(e){
    e.preventDefault();
    if(confirm("' . Yii::t("app", "Are you sure?") . '")){
        var ids     = $("#media-comment-grid-view").yiiGridView("getSelectedRows");
        var action  = $(this).parents(".form-group").find(".bulk-action").val();
        $.ajax({
            url: "' . Url::to(["/media-comment/bulk-action"]) . '",
            data: { ids: ids, action: action, _csrf: yii.getCsrfToken() },
            type:"POST",
            success: function(data){
                $.pjax.reload({container:"#media-comment-grid-view"});
            }
        });
    }
});'
);
