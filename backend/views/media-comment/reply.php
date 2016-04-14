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

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $commentParent common\models\MediaComment */
/* @var $model common\models\MediaComment */

$this->title = Yii::t('writesdown', 'Reply Media Comment: {id}', ['id' => $commentParent->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Media'), 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => $commentParent->commentMedia->id,
    'url' => ['index', 'media' => $commentParent->commentMedia->id],
];
$this->params['breadcrumbs'][] = ['label' => $commentParent->id, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Reply Comment');
?>
<?php $form = ActiveForm::begin(['id' => 'media-comment-reply-form']) ?>

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
                    'attribute' => 'media_id',
                    'value' => Html::a($commentParent->commentMedia->title, [
                        '/media/update',
                        'id' => $commentParent->commentMedia->id,
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
                    'value' => date('M d, Y H:i:s', strtotime($model->date)),
                    'format' => 'raw',
                ],
                'status',
                'agent',
                [
                    'attribute' => 'parent',
                    'value' => $commentParent->parent
                        ? Html::a($commentParent->parent, [
                            'update',
                            'id' => $commentParent->parent,
                        ])
                        : '',
                    'format' => 'raw',
                ],
                'user_id',
            ],
        ]) ?>

    </div>
</div>
<?php ActiveForm::end() ?>
