<?php
/**
 * @file      _search.php.
 * @date      6/4/2015
 * @time      12:05 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search collapse" id="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">

        <div class="col-sm-6">
            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'full_name') ?>

            <?= $form->field($model, 'display_name') ?>
        </div>

        <div class="col-sm-6">

            <?= $form->field($model, 'status')->dropDownList($model->getStatus(), ['prompt' => Yii::t('writesdown', 'Select Status')]) ?>

            <?= $form->field($model, 'created_at') ?>

            <?= $form->field($model, 'updated_at') ?>

            <?= $form->field($model, 'login_at') ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn btn-flat btn-primary']) ?>
        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn btn-flat btn-default']) ?>
        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa fa-level-up']), ['class' => 'index-search-button btn btn-flat btn-default', "data-toggle" => "collapse", "data-target" => "#user-search"]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
