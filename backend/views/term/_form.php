<?php
/**
 * @file      _form.php.
 * @date      6/4/2015
 * @time      12:00 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model common\models\Term */
/* @var $taxonomy common\models\Taxonomy */
?>

<div class="term-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'term_name')->textInput([
        'maxlength'   => 200,
        'placeholder' => $model->getAttributeLabel('term_name')
    ])->hint(Yii::t('writesdown', 'The name is how it appears on your site.')) ?>

    <?= $form->field($model, 'term_slug')->textInput([
        'maxlength'   => 200,
        'placeholder' => $model->getAttributeLabel('term_slug')
    ])->hint(Yii::t('writesdown', 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.')) ?>

    <?= $form->field($model, 'term_description')->textarea([
        'rows'        => 6,
        'placeholder' => $model->getAttributeLabel('term_description')
    ]) ?>

    <?php
    if ($taxonomy->taxonomy_hierarchical)
        echo $form->field($model, 'term_parent')->dropDownList(
            ArrayHelper::map($taxonomy->terms, 'id', 'term_name'), [
                'prompt' => ''
            ]
        )->hint(Yii::t('writesdown', 'Taxonomy hierarchical can have a hierarchy that have children, something like Parent Term and Child Term. This is optional.'));
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('writesdown', 'Save') : Yii::t('writesdown', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-flat btn-success' : 'btn btn-flat btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
