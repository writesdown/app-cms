<?php
/**
 * @file      _form.php.
 * @date      6/4/2015
 * @time      11:51 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'option_name')->textInput(['maxlength' => 64, 'placeholder' => $model->getAttributeLabel('option_name')]) ?>

    <?= $form->field($model, 'option_value')->textarea(['rows' => 6, 'placeholder' => $model->getAttributeLabel('option_value')]) ?>

    <?= $form->field($model, 'option_label')->textInput(['maxlength' => 64, 'placeholder' => $model->getAttributeLabel('option_label')]) ?>

    <?= $form->field($model, 'option_group')->textInput(['maxlength' => 64, 'placeholder' => $model->getAttributeLabel('option_group')]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('writesdown', 'Save') : Yii::t('writesdown', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-flat btn-success' : 'btn btn-flat btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
