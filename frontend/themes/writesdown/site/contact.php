<?php
/**
 * @file      contact.php
 * @date      8/23/2015
 * @time      9:08 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="single site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum
        sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
    </p>

    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

    <div class="row">
        <div class="col-md-7">

            <?= $form->field($model, 'name') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'subject') ?>

        </div>
    </div>
    <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

    <div class="row">
        <div class="col-md-6">

            <?= $form->field($model, 'verifyCode')->widget(Captcha::className()) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
