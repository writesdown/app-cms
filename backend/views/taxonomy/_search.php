<?php
/**
 * @file      _search.php.
 * @date      6/4/2015
 * @time      11:58 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\Taxonomy */
/* @var $form yii\widgets\ActiveForm */
?>

<div id="taxonomy-search" class="taxonomy-search collapse">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">

        <div class="col-sm-6">

            <?= $form->field($model, 'taxonomy_name') ?>

            <?= $form->field($model, 'taxonomy_slug') ?>

            <?= $form->field($model, 'taxonomy_hierarchical')->dropDownList($model->hierarchical, ['prompt' => '']) ?>

        </div>

        <div class="col-sm-6">

            <?= $form->field($model, 'taxonomy_sn') ?>

            <?= $form->field($model, 'taxonomy_pn') ?>

            <?= $form->field($model, 'taxonomy_smb')->dropDownList($model->smb, ['prompt' => '']) ?>

        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Search'), ['class' => 'btn btn-flat btn-primary']) ?>
        <?= Html::resetButton(Yii::t('writesdown', 'Reset'), ['class' => 'btn btn-flat btn-default']) ?>
        <?= Html::button(Html::tag('i', '', ['class' => 'fa fa fa-level-up']), ['class' => 'index-search-button btn btn-flat btn-default', "data-toggle" => "collapse", "data-target" => "#taxonomy-search"]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>