<?php
/**
 * @file    index.php.
 * @date    6/4/2015
 * @time    6:15 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Post */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $postType common\models\PostType */

$this->title = $postType->post_type_pn;
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="post-index">

        <div class="form-inline grid-nav" role="form">

            <div class="form-group">

                <?= Html::dropDownList('bulk-action', null, ArrayHelper::merge($searchModel->getPostStatus(), ['delete' => 'Delete']), [
                    'class'  => 'bulk-action form-control',
                    'prompt' => 'Bulk Action',
                ]); ?>

                <?= Html::button(Yii::t('writesdown', 'Apply'), ['class' => 'btn btn-flat btn-warning bulk-button']); ?>

                <?= Html::a(Yii::t('writesdown', 'Add New {postType}', ['postType' => $postType->post_type_sn]), ['create', 'post_type' => $postType->id], ['class' => 'btn btn-flat btn-primary']) ?>

                <?php
                echo ButtonDropdown::widget([
                    'label'       => Html::tag('i', '', ['class' => 'fa fa-user']) . ' Author',
                    'dropdown'    => [
                        'items' => [
                            ['label' => 'My Posts', 'url' => ['/post/index', 'post_type' => $postType->id, 'user' => Yii::$app->user->id]],
                            ['label' => 'All Posts', 'url' => ['/post/index', 'post_type' => $postType->id]],
                        ],
                    ],
                    'encodeLabel' => false,
                    'options'     => [
                        'class' => 'btn btn-flat btn-danger'
                    ]
                ]);
                ?>

                <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), ['class' => 'btn btn-flat btn-info', "data-toggle" => "collapse", "data-target" => "#post-search"]); ?>

            </div>
        </div>

        <?php Pjax::begin(); ?>

        <?= $this->render('_search', [
            'model'    => $searchModel,
            'postType' => $postType,
            'user'     => $user
        ]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'id'           => 'post-grid-view',
            'columns'      => [
                [
                    'class'           => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        if ((!Yii::$app->user->can('editor') && $model->post_author !== Yii::$app->user->id) || !Yii::$app->user->can($model->postType->post_type_permission)) {
                            return ['disabled' => 'disabled'];
                        }

                        return ['value' => $model->id];
                    },
                ],
                [
                    'attribute' => 'username',
                    'value' => function($model){
                        /* @var $model common\models\Post */
                        return $model->postAuthor->username;
                    }
                ],
                'post_title:ntext',
                'post_date',
                [
                    'attribute' => 'post_status',
                    'filter'    => $searchModel->getPostStatus()
                ],
                [
                    'attribute' => 'post_comment_status',
                    'filter'    => $searchModel->getCommentStatus()
                ],
                'post_comment_count',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $model->url, [
                                'title' => Yii::t('yii', 'View'),
                                'data-pjax' => '0',
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            if (!$model->postType || !Yii::$app->user->can($model->postType->post_type_permission)) {
                                return '';
                            } else if (!Yii::$app->user->can('editor') && Yii::$app->user->id !== $model->post_author) {
                                return '';
                            } elseif (!Yii::$app->user->can('author') && $model->post_status !== $model::POST_STATUS_REVIEW) {
                                return '';
                            }
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            if (!$model->postType || !Yii::$app->user->can($model->postType->post_type_permission)) {
                                return '';
                            } else if (!Yii::$app->user->can('editor') && Yii::$app->user->id !== $model->post_author) {
                                return '';
                            } elseif (!Yii::$app->user->can('author') && $model->post_status !== $model::POST_STATUS_REVIEW) {
                                return '';
                            }
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
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
    if(confirm("' . Yii::t("app", "Are you sure to do this?") . '")){
        var ids     = $("#post-grid-view").yiiGridView("getSelectedRows"); // returns an array of pkeys, and #grid is your grid element id
        var action  = $(this).parents(".form-group").find(".bulk-action").val();
        $.ajax({
            url: "' . Url::to(["bulk-action"]) . '",
            data: { ids: ids, action: action, _csrf: yii.getCsrfToken() },
            type:"POST",
            success: function(data){
                $.pjax.reload({container:"#post-grid-view"});
            }
        });
    }
});'
);