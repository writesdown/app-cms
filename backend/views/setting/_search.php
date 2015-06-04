<?php
/**
 * @file    _search.php.
 * @date    6/4/2015
 * @time    11:51 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div id="option-search" class="option-search collapse">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-6">

            <?= $form->field($model, 'option_name') ?>

            <?= $form->field($model, 'option_value') ?>

        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'option_label') ?>

            <?= $form->field($model, 'option_group') ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn btn-flat btn-primary']) ?>
        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn btn-flat btn-default']) ?>
        <?= Html::button(Html::tag('i','',['class'=>'fa fa fa-level-up']), ['class'=>'index-search-button btn btn-flat btn-default', "data-toggle"=>"collapse", "data-target"=>"#option-search"]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>