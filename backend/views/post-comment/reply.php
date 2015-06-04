<?php
/**
 * @file      reply.php.
 * @date      6/4/2015
 * @time      6:30 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $commentParent common\models\PostComment */
/* @var $model common\models\PostComment */

$this->title = Yii::t('writesdown', 'Reply {post_type} Comment', [
    'post_type' => $commentParent->commentPost->postType->post_type_sn,
]);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('writesdown', '{postType} Comment', [
        'postType' => $commentParent->commentPost->postType->post_type_sn
    ]),
    'url'   => ['index', 'post_type' => $commentParent->commentPost->postType->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $commentParent->id,
    'url'   => ['update', 'id' => $commentParent->id]
];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Reply');

?>
<?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8 post-comment-update">

            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-reply"></i>

                    <h3 class="box-title"><?= Yii::t('writesdown', 'Reply To'); ?></h3>
                </div>
                <div class="box-body">
                    <?= $commentParent->comment_content; ?>
                </div>
            </div>

            <?= $this->render('_form-reply', [
                'model' => $model,
                'form'  => $form
            ]) ?>

        </div>
        <div class="col-md-4 post-comment-update">

            <?= DetailView::widget([
                'model'      => $commentParent,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'comment_post_id',
                        'value'     => Html::a($commentParent->commentPost->post_title, ['/post/update', 'id' => $commentParent->commentPost->id]),
                        'format'    => 'raw'
                    ],
                    'comment_author:ntext',
                    'comment_author_email:email',
                    'comment_author_url:url',
                    [
                        'attribute' => 'comment_author_ip',
                        'value'     => Html::a($model->comment_author_ip, 'http://whois.arin.net/rest/ip/' . $model->comment_author_ip, ['target' => '_blank']),
                        'format'    => 'raw'
                    ],
                    [
                        'attribute' => 'comment_date',
                        'value'     => Yii::$app->formatter->asDatetime($model->comment_date, 'php:M d, Y H:i:s'),
                        'format'    => 'raw'
                    ],
                    'comment_approved',
                    'comment_agent',
                    [
                        'attribute' => 'comment_parent',
                        'value'     => $commentParent->comment_parent ? Html::a($commentParent->comment_parent, ['update', 'id' => $commentParent->comment_parent]) : '',
                        'format'    => 'raw'
                    ],
                    'comment_user_id',
                ],
            ]) ?>

        </div>
    </div>
<?php
ActiveForm::end();