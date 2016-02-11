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
/* @var $model common\models\search\Term */
/* @var $form yii\widgets\ActiveForm */
/* @var $taxonomy common\models\Taxonomy */
?>
<div id="term-search" class="term-search collapse">
    <?php $form = ActiveForm::begin([
        'action' => ['view', 'id' => $taxonomy->id],
        'method' => 'get',
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['id' => 'term-search-name']) ?>

    <?= $form->field($model, 'slug')->textInput(['id' => 'term-search-slug']) ?>

    <?= $form->field($model, 'description')->textInput(['id' => 'term-search-description']) ?>

    <?= $form->field($model, 'parent')->textInput(['id' => 'term-search-parent']) ?>

    <?= $form->field($model, 'count')->textInput(['id' => 'term-search-count']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn btn-flat btn-primary']) ?>

        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn btn-flat btn-default']) ?>

        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa fa-level-up']), [
            'class' => 'index-search-button btn btn-flat btn-default',
            'data-toggle' => 'collapse',
            'data-target' => '#term-search',
        ]) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
