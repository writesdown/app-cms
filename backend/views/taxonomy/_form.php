<?php
/**
 * @file    _form.php.
 * @date    6/4/2015
 * @time    11:58 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
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

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'taxonomy_name')->textInput([
        'maxlength' => 200,
        'placeholder' => $model->getAttributeLabel('taxonomy_name')
    ])->hint( Yii::t( 'writesdown', 'Used for calling of the taxonomy. Example: category, tag, news-cat.' ) ) ?>

    <?= $form->field($model, 'taxonomy_slug')->textInput([
        'maxlength' => 200,
        'placeholder' => $model->getAttributeLabel('taxonomy_slug')
    ])->hint( Yii::t( 'writesdown', 'Used in the url of the taxonomy' ) ) ?>

    <?= $form->field($model, 'taxonomy_sn')->textInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('taxonomy_sn')
    ]) ?>

    <?= $form->field($model, 'taxonomy_pn')->textInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('taxonomy_pn')
    ]) ?>

    <?= $form->field($model, 'taxonomy_hierarchical')->checkbox(['uncheck' => 0]) ?>

    <?= $form->field($model, 'taxonomy_smb')->checkbox(['uncheck' => 0]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('writesdown', 'Save') : Yii::t('writesdown', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-flat btn-success' : 'btn btn-flat btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
