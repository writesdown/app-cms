<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Taxonomy */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="taxonomy-form">
    <?php $form = ActiveForm::begin(['id' => 'taxonomy-form']) ?>

    <?= $form->field($model, 'name')->textInput([
        'maxlength' => 200,
        'placeholder' => $model->getAttributeLabel('name'),
    ])->hint(Yii::t('writesdown', 'Used for calling of the taxonomy. Example: category, tag, news-cat.')) ?>

    <?= $form->field($model, 'slug')->textInput([
        'maxlength' => 200,
        'placeholder' => $model->getAttributeLabel('slug'),
    ])->hint(Yii::t('writesdown', 'Used in the url of the taxonomy')) ?>

    <?= $form->field($model, 'singular_name')->textInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('singular_name'),
    ]) ?>

    <?= $form->field($model, 'plural_name')->textInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('plural_name'),
    ]) ?>

    <?= $form->field($model, 'hierarchical')->checkbox(['uncheck' => 0]) ?>

    <?= $form->field($model, 'menu_builder')->checkbox(['uncheck' => 0]) ?>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('writesdown', 'Save') : Yii::t('writesdown', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-flat btn-success' : 'btn btn-flat btn-primary']
        ) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
