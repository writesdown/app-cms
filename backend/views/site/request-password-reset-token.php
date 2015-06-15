<?php
/**
 * @file    request-password-reset-token.php.
 * @date    6/4/2015
 * @time    5:37 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use codezeen\yii2\adminlte\widgets\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model common\models\PasswordResetRequestForm */

$this->title = 'Request password reset';
?>

<div class="login-box">

    <div class="login-logo">
        <h1>
            <?= Html::a( Html::img( Yii::getAlias('@web/img/logo.png'), ['alt' => 'WritesDown'] ), 'http://www.writesdown.com' ); ?>
        </h1>
    </div>

    <?= Alert::widget() ?>

    <div class="login-box-body">

        <p class="login-box-msg"><?= Yii::t('writesdown', 'Please fill out your email. A link to reset password will be sent there.'); ?></p>

        <?php $form = ActiveForm::begin(['id' => 'request-password-token-form']); ?>

        <?= $form->field($model, 'email', ['template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span></div>{error}'])->textInput(['placeholder' => $model->getAttributeLabel('email')]); ?>

        <div class="form-group">
            <?= Html::submitButton('Send', ['class' => 'btn btn-flat btn-primary form-control']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>