<?php
/**
 * @file      login.php.
 * @date      6/4/2015
 * @time      5:36 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use codezeen\yii2\adminlte\widgets\Alert;

/* MODEL */
use common\models\Option;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('writesdown', 'Sing In');

?>

<div class="login-box">

    <div class="login-logo">
        <h1>
            <?= Html::a( Html::img( Yii::getAlias('@web/img/logo.png'), ['alt' => 'WritesDown'] ), 'http://www.writesdown.com' ); ?>
        </h1>
    </div>

    <?= Alert::widget() ?>

    <div class="login-box-body">

        <p class="login-box-msg"><?= Yii::t('writesdown', 'Sign in to start your session'); ?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username', ['template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span></div>{error}'])->textInput(['placeholder' => $model->getAttributeLabel('username')]); ?>

        <?= $form->field($model, 'password', ['template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>{error}'])->passwordInput(['placeholder' => $model->getAttributeLabel('password')]); ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton('Signin', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'signin-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

        <?= Html::a(Yii::t('writesdown', 'Reset Password'), ['/site/request-password-reset']); ?>

        <br/>

        <?= Option::get('allow_signup') ? Html::a(Yii::t('writesdown', 'Register a new membership'), ['/site/signup']) : ""; ?>
    </div>
</div>