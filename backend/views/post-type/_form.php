<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use dosamigos\selectize\SelectizeDropDownList;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PostType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-type-form">
    <?php $form = ActiveForm::begin(['id' => 'post-type-form']) ?>

    <?= $form->field($model, 'name')->textInput([
        'maxlength' => 64,
        'placeholder' => $model->getAttributeLabel('name'),
    ])->hint(Yii::t('writesdown', 'Used for calling of the post_type. Example: post, page, news.')) ?>

    <?= $form->field($model, 'slug')->textInput([
        'maxlength' => 64,
        'placeholder' => $model->getAttributeLabel('slug'),
    ])->hint(Yii::t('writesdown', 'Used on the url of the taxonomy ( Use - instead of space for better SEO )')) ?>

    <?= $form->field($model, 'singular_name')->textInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('singular_name'),
    ]) ?>

    <?= $form->field($model, 'plural_name')->textInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('plural_name'),
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6, ]) ?>

    <?= $form->field($model, 'icon')->widget(SelectizeDropDownList::className(), [
        'items' => Fa::getConstants(),
    ])->hint(Yii::t('writesdown', 'The icon use {FontAwesome} and appears on admin side menu', [
        'FontAwesome' => Html::a('FontAwesome', 'http://www.fontawesome.com/', ['target' => 'blank']),
    ])) ?>

    <?php
    $role = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');

    if (Yii::$app->user->can('administrator')
        && !Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'superadmin')
    ) {
        unset($role['superadmin']);
    }

    echo $form->field($model, 'permission')->dropDownList($role)
    ?>

    <?= $form->field($model, 'menu_builder')->checkbox(['uncheck' => 0, ]) ?>

    <?= Html::hiddenInput('PostTypeTaxonomy[taxonomyIds]', '[]', ['id' => 'posttypetaxonomy-taxonomy_ids']) ?>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('writesdown', 'Save') : Yii::t('writesdown', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-flat btn-success' : 'btn btn-flat btn-primary']
        ) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
