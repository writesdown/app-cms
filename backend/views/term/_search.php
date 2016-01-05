<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
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

    <?= $form->field($model, 'term_name')->textInput(['id' => 'term-search-term_name']) ?>

    <?= $form->field($model, 'term_slug')->textInput(['id' => 'term-search-term_slug']) ?>

    <?= $form->field($model, 'term_description')->textInput(['id' => 'term-search-term_description']) ?>

    <?= $form->field($model, 'term_parent')->textInput(['id' => 'term-search-term_parent']) ?>

    <?= $form->field($model, 'term_count')->textInput(['id' => 'term-search-term_count']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn btn-flat btn-primary']) ?>

        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn btn-flat btn-default']) ?>

        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa fa-level-up']), [
            'class'       => 'index-search-button btn btn-flat btn-default',
            "data-toggle" => "collapse",
            "data-target" => "#term-search",
        ]) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
