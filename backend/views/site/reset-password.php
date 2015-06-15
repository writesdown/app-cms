<?php
/**
 * @file    reset-password.php.
 * @date    6/4/2015
 * @time    5:38 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use codezeen\yii2\adminlte\widgets\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model common\models\ResetPasswordForm */

$this->title = 'Reset password';
?>

<div class="login-box">

    <div class="login-logo">
        <h1>
            <?= Html::a( Html::img( Yii::getAlias('@web/img/logo.png'), ['alt' => 'WritesDown'] ), 'http://www.writesdown.com' ); ?>
        </h1>
    </div>

    <?= Alert::widget() ?>

    <div class="login-box-body">

        <p class="login-box-msg"><?= Yii::t('writesdown', 'Please choose your new password:'); ?></p>

        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

        <?= $form->field($model, 'password', ['template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>{error}'])->passwordInput(['placeholder' => $model->getAttributeLabel('password')]); ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-flat btn-primary form-control']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>