<?php
/**
 * @file      _reset-password.php.
 * @date      6/4/2015
 * @time      12:05 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'password_old')->passwordInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('password_old')]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('password')]) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => 255, 'placeholder' => $model->getAttributeLabel('password_repeat')]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Save my new password'), ['class' => 'btn-flat btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
