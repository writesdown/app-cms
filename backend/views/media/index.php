<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      5:46 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Media */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('writesdown', 'Media');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="media-index">

        <div class="form-inline grid-nav" role="form">
            <div class="form-group">
                <?= Html::dropDownList('bulk-action', null, [
                    'delete' => 'Delete Permanently',
                ], [
                    'prompt' => 'Bulk Action',
                    'class'  => 'bulk-action form-control'
                ]); ?>
                <?= Html::button(Yii::t('writesdown', 'Apply'), ['class' => 'btn btn-flat btn-warning bulk-button', 'data-type' => 'DELETE']); ?>
                <?= Html::a(Yii::t('writesdown', 'Add New Media'), ['create'], ['class' => 'btn btn-flat btn-primary']) ?>
                <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-search']), ['class' => 'btn btn-flat btn-info', "data-toggle" => "collapse", "data-target" => "#media-search"]); ?>
            </div>
        </div>

        <?php Pjax::begin(); ?>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'id'           => 'media-grid-view',
            'columns'      => [
                ['class' => 'yii\grid\CheckboxColumn'],

                [
                    'attribute' => Yii::t('writesdown', 'Preview'),
                    'format'    => 'raw',
                    'value'     => function ($model) {
                        /* @var $model common\models\Media */
                        $metadata = $model->getMeta('metadata');
                        if (preg_match('/^image\//', $model->media_mime_type)) {
                            return Html::a(Html::img($model->uploadUrl . $metadata['media_icon_url']), [
                                '/media/update', 'id' => $model->id
                            ], [
                                'class' => 'media-mime-icon'
                            ]);
                        } else {
                            return Html::a(Html::img(Yii::$app->urlManagerBack->baseUrl . '/' . $metadata['media_icon_url']), [
                                '/media/update', 'id' => $model->id
                            ], [
                                'class' => 'media-mime-icon'
                            ]);
                        }
                    },
                ],
                [
                    'label'  => Yii::t('writesdown', 'File Name'),
                    'format' => 'html',
                    'value'  => function ($model) {
                        /* @var $model common\models\Media */
                        $metadata = $model->getMeta('metadata');

                        return Html::a($metadata['media_filename'], ['/media/update', 'id' => $model->id]);
                    },
                ],
                [
                    'attribute' => 'username',
                    'value'     => function ($model) {
                        return $model->mediaAuthor->username;
                    },
                ],
                'media_date:datetime',

                [
                    'class'   => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view'   => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $model->url, [
                                'title'     => Yii::t('yii', 'View'),
                                'data-pjax' => '0',
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            if (!Yii::$app->user->can('editor') && $model->media_author !== Yii::$app->user->id) {
                                return '';
                            }

                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title'     => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            if (!Yii::$app->user->can('editor') && $model->media_author !== Yii::$app->user->id) {
                                return '';
                            }

                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title'        => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method'  => 'post',
                                'data-pjax'    => '0',
                            ]);
                        },
                    ]
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>

<?php
$this->registerJs('
jQuery(document).on("click", ".bulk-button", function(e){
    e.preventDefault();
    if(confirm("' . Yii::t('writesdown', "Are you sure to do this?") . '")){
        var ids     = $("#media-grid-view").yiiGridView("getSelectedRows"); // returns an array of pkeys, and #grid is your grid element id
        var action  = $(this).closest(".form-group").find(".bulk-action").val();
        $.ajax({
            url: "' . Url::to(["/media/bulk-action"]) . '",
            data: { ids: ids, action: action, _csrf: yii.getCsrfToken() },
            type: "POST",
            success: function(data){
                  $.pjax.reload({container:"#media-grid-view"});
            }
        });
    }
});'
);