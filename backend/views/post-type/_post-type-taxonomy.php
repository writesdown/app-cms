<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $taxonomies [] */
/* @var $model common\models\PostType */
/* @var $taxonomy common\models\Taxonomy */

?>

<div id="post-type-taxonomy-create" class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <?= Yii::t('writesdown', 'Taxonomies') ?>
        </h3>

        <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">
        <?= Html::checkboxList('taxonomy_ids',
            $model->isNewRecord ? null : ArrayHelper::getColumn($model->taxonomies, 'id'), $taxonomies, [
                'separator' => '<br />',
                'id' => 'taxonomy-list',
                'class' => 'checkbox',
            ]) ?>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'ajax-create-taxonomy-form',
        'action' => Url::to(['/site/forbidden']),
        'options' => [
            'class' => 'ajax-create-taxonomy-create box-footer',
            'data-url' => Url::to(['taxonomy/ajax-create']),
        ],
    ]) ?>

    <div class="hint-block form-group">
        <?= Yii::t('writesdown', 'Please, fill out the form below to create new taxonomy ') ?>
    </div>

    <?= $form->field($taxonomy, 'name', ['template' => '{input}{error}'])->textInput([
        'placeholder' => $taxonomy->getAttributeLabel('name'),
    ]) ?>

    <?= $form->field($taxonomy, 'singular_name', ['template' => '{input}{error}'])->textInput([
        'placeholder' => $taxonomy->getAttributeLabel('singular_name'),
    ]) ?>

    <?= $form->field($taxonomy, 'plural_name', ['template' => '{input}{error}'])->textInput([
        'placeholder' => $taxonomy->getAttributeLabel('plural_name'),
    ]) ?>

    <?= Html::submitButton(Yii::t('writesdown', 'Add New Taxonomy'), [
        'class' => 'btn btn-flat btn-success form-control',
    ]) ?>

    <?php ActiveForm::end() ?>

</div>
