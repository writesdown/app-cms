<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $commentParent common\models\PostComment */
/* @var $model common\models\PostComment */

$this->title = Yii::t(
    'writesdown', 'Reply {postType} Comment {id}',
    ['postType' => $commentParent->commentPost->postType->singular_name, 'id' => $model->id]
);
$this->params['breadcrumbs'][] = [
    'label' => $commentParent->commentPost->postType->singular_name,
    'url' => ['index', 'posttype' => $commentParent->commentPost->postType->id],
];
$this->params['breadcrumbs'][] = [
    'label' => $commentParent->commentPost->id,
    'url' => ['index', 'posttype' => $commentParent->commentPost->postType->id, 'post' => $commentParent->post_id],
];
$this->params['breadcrumbs'][] = ['label' => $commentParent->id, 'url' => ['update', 'id' => $commentParent->id]];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Reply Comment');
?>
<?php $form = ActiveForm::begin(['id' => 'post-comment-reply-form']) ?>

<div class="row">
    <div class="col-md-8 post-comment-update">
        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-reply"></i>

                <h3 class="box-title"><?= Yii::t('writesdown', 'Reply to') ?></h3>
            </div>
            <div class="box-body">
                <?= $commentParent->content ?>

            </div>
        </div>
        <?= $this->render('_form-reply', [
            'model' => $model,
            'form' => $form,
        ]) ?>
    </div>
    <div class="col-md-4 post-comment-update">
        <?= DetailView::widget([
            'model' => $commentParent,
            'attributes' => [
                'id',
                [
                    'attribute' => 'post_id',
                    'value' => Html::a($commentParent->commentPost->title, [
                        '/post/update',
                        'id' => $commentParent->commentPost->id,
                    ]),
                    'format' => 'raw',
                ],
                'author:ntext',
                'email:email',
                'url:url',
                [
                    'attribute' => 'ip',
                    'value' => Html::a(
                        $commentParent->ip,
                        'http://whois.arin.net/rest/ip/' . $commentParent->ip,
                        ['target' => '_blank']
                    ),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'date',
                    'value' => date('M d, Y H:i:s', strtotime($commentParent->date)),
                    'format' => 'raw',
                ],
                'status',
                'agent',
                [
                    'attribute' => 'parent',
                    'value' => $commentParent->parent
                        ? Html::a($commentParent->parent, ['update', 'id' => $commentParent->parent])
                        : '',
                    'format' => 'raw',
                ],
                'user_id',
            ],
        ]) ?>

    </div>
</div>
<?php ActiveForm::end() ?>
