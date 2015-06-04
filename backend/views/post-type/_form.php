<?php
/**
 * @file      _form.php.
 * @date      6/4/2015
 * @time      6:32 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\selectize\SelectizeDropDownList;
use rmrevin\yii\fontawesome\FA;

/* @var $this yii\web\View */
/* @var $model common\models\PostType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-type-form">

    <?php $form = ActiveForm::begin(['id' => 'post-type-form']); ?>

    <?= $form->field($model, 'post_type_name')->textInput([
        'maxlength'   => 64,
        'placeholder' => $model->getAttributeLabel('post_type_name')
    ])->hint(Yii::t('writesdown', 'Used for calling of the post_type. Example: post, page, news.')) ?>

    <?= $form->field($model, 'post_type_slug')->textInput([
        'maxlength'   => 64,
        'placeholder' => $model->getAttributeLabel('post_type_slug')
    ])->hint(Yii::t('writesdown', 'Used on the url of the taxonomy ( Use - instead of space for better SEO )')) ?>

    <?= $form->field($model, 'post_type_sn')->textInput([
        'maxlength'   => 255,
        'placeholder' => $model->getAttributeLabel('post_type_sn')
    ]) ?>

    <?= $form->field($model, 'post_type_pn')->textInput([
        'maxlength'   => 255,
        'placeholder' => $model->getAttributeLabel('post_type_pn')
    ]) ?>

    <?= $form->field($model, 'post_type_description')->textarea([
        'rows' => 6,
    ]) ?>

    <?= $form->field($model, 'post_type_icon')->widget(
        SelectizeDropDownList::className(), [
            'items' => Fa::getConstants(),
        ])->hint(Yii::t('writesdown', 'The icon use {FontAwesome} and appears on admin side menu', [
        'FontAwesome' => Html::a('FontAwesome', 'http://www.fontawesome.com/', [
            'target' => 'blank'
        ])
    ])) ?>

    <?php
    $role = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
    if (Yii::$app->user->can('administrator') && !Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'superadmin')) {
        unset($role['superadmin']);
    }
    echo $form->field($model, 'post_type_permission')->dropDownList($role);
    ?>

    <?= $form->field($model, 'post_type_smb')->checkbox([
        'uncheck' => 0
    ]) ?>

    <?= Html::hiddenInput('PostTypeTaxonomy[taxonomy_ids]', null, ['id' => 'posttypetaxonomy-taxonomy_ids']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('writesdown', 'Save') : Yii::t('writesdown', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-flat btn-success' : 'btn btn-flat btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>