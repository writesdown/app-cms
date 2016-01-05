<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Option;
use frontend\widgets\Alert;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = Yii::t('writesdown', 'Contact') . ' - ' . Option::get('sitetitle');
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Contact');
?>
<div class="single site-contact">
    <article class="hentry">
        <header class="entry-header">
            <h1 class="entry-title"><?= Html::encode($this->title) ?></h1>

        </header>
        <div class="entry-content">
            <?= Alert::widget() ?>

            <p>
                If you have business inquiries or other questions, please fill out the following form to contact us.
                Thank you.
            </p>
            <?php $form = ActiveForm::begin(['id' => 'contact-form']) ?>

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
            <?php ActiveForm::end() ?>

        </div>
    </article>
</div>
