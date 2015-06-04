<?php
/**
 * @file      create.php.
 * @date      6/4/2015
 * @time      6:14 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $postType common\models\PostType */

$this->title = Yii::t('writesdown', 'Add New {postType}', ['postType' => $postType->post_type_sn]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'post-create'
    ]
]); ?>

    <div class="row">
        <div class="col-md-8">

            <?= $this->render('_form', [
                'model' => $model,
                'form'  => $form,
            ]) ?>

            <?= $this->render('_form-comment', [
                'model' => $model,
                'form'  => $form
            ]); ?>

        </div>
        <div class="col-md-4">

            <?= $this->render('_form-publish', [
                'model' => $model,
                'form'  => $form
            ]); ?>

            <?= $this->render('_form-term', [
                'model'    => $model,
                'postType' => $postType,
                'form'     => $form
            ]) ?>

        </div>
    </div>

<?php ActiveForm::end();