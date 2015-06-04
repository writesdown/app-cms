<?php
/**
 * @file      _form.php.
 * @date      6/4/2015
 * @time      12:04 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput([
        'maxlength'   => 255,
        'placeholder' => $model->getAttributeLabel('username')
    ]) ?>

    <?= $form->field($model, 'email')->input('email', [
        'maxlength'   => 255,
        'placeholder' => $model->getAttributeLabel('email')
    ])->hint(Yii::t('writesdown', 'An e-mail used for receiving notification and resetting password.')) ?>

    <?= $model->isNewRecord ? $form->field($model, 'password')->passwordInput([
        'maxlength'   => 255,
        'placeholder' => $model->getAttributeLabel('password')
    ]) : '' ?>

    <?= $form->field($model, 'full_name')->textInput([
        'maxlength'   => 255,
        'placeholder' => $model->getAttributeLabel('full_name')
    ]) ?>

    <?= $form->field($model, 'display_name')->textInput([
        'maxlength'   => 255,
        'placeholder' => $model->getAttributeLabel('display_name')
    ])->hint(Yii::t('writesdown', 'Display name will be used as your public name.')) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatus()) ?>

    <?php
    $role = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
    // Unset superadmin from dropdown
    unset($role['superadmin']);

    // Administrator can't add new administrator.
    if (Yii::$app->user->can('administrator') && !Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'superadmin')) {
        unset($role['administrator']);
    }

    echo $form->field($model, 'role')->dropDownList($role);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('writesdown', 'Save') : Yii::t('writesdown', 'Update'), ['class' => $model->isNewRecord ? 'btn-flat btn btn-success' : 'btn-flat btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>