<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use codezeen\yii2\adminlte\widgets\Alert;
use common\models\Option;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\SignupForm */

$this->title = Yii::t('writesdown', 'Sing Up');

?>

<div class="register-box">
    <div class="login-logo">
        <h1>
            <a href="http://www.writesdown.com/">
                <img src="<?= Yii::getAlias('@web/img/logo.png') ?>" alt="WritesDown">
            </a>
        </h1>
    </div>

    <?= Alert::widget() ?>

    <div class="register-box-body">
        <p class="login-box-msg"><?= Yii::t('writesdown', 'Register a new membership') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'signup-form']) ?>

        <?= $form->field($model, 'username', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span></div>{error}',
        ])->textInput(['placeholder' => $model->getAttributeLabel('username'), ]) ?>

        <?= $form->field($model, 'email', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span></div>{error}',
        ])->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>

        <?= $form->field($model, 'password', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>{error}',
        ])->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'term_condition')->checkbox(['uncheck' => null])->label(Yii::t(
                    'writesdown', 'I agree to the {termLink}',
                    ['termLink' => Html::a('terms', ['/site/terms'], ['target' => '_blank'])]
                )) ?>

            </div>
            <div class="col-xs-4">
                <?= Html::submitButton('Signup', [
                    'class' => 'btn btn-primary btn-block btn-flat',
                    'name'  => 'signup-button',
                ]) ?>

            </div>
        </div>
        <?php ActiveForm::end() ?>

        <?= Html::a(Yii::t('writesdown', 'I already have a membership'), ['/site/login']) ?>

    </div>
    <br/>
    <?= Html::a(
        '<i class="fa fa-home"></i> ' . Yii::t(
            'writesdown', 'Back to {sitetitle}',
            ['sitetitle' => Option::get('sitetitle')]
        ),
        Yii::$app->urlManagerFront->createUrl(['/site/index']),
        ['class' => 'btn btn-block btn-success']
    ) ?>

</div>
