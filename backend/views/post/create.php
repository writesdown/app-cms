<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $postType common\models\PostType */
/* @var $model common\models\Post */

$this->title = Yii::t('writesdown', 'Add New {postType}', ['postType' => $postType->post_type_sn]);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('writesdown', 'Posts'),
    'url'   => ['index', 'post_type' => $postType->id],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'id'      => 'post-create-form',
    'options' => ['class' => 'post-create'],
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
