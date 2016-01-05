<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $postType common\models\PostType */

$this->title = Yii::t('writesdown', 'Update {post_type}', ['post_type' => $model->postType->post_type_sn]);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('writesdown', 'Posts'),
    'url'   => ['index', 'post_type' => $postType->id],
];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => $model->url];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Update');
?>
<?php $form = ActiveForm::begin([
    'id'      => 'post-update-form',
    'options' => [
        'class' => 'post-update',
    ],
]) ?>

<div class="row">
    <div class="col-md-8">
        <?= $this->render('_form', [
            'model' => $model,
            'form'  => $form,
        ]) ?>
        <?= $this->render('_form-comment', [
            'model' => $model,
            'form'  => $form,
        ]) ?>
        <?= $this->render('_meta-box', [
            'model'    => $model,
            'form'     => $form,
            'postType' => $postType,
        ]) ?>
    </div>
    <div class="col-md-4">
        <?= $this->render('_form-publish', [
            'model' => $model,
            'form'  => $form,
        ]) ?>
        <?= $this->render('_form-term', [
            'model'    => $model,
            'postType' => $postType,
            'form'     => $form,
        ]) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
