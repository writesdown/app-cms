<?php
/**
 * @file      upload.php.
 * @date      6/4/2015
 * @time      12:04 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = Yii::t('writesdown', 'Upload New Theme');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Theme'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="theme">
    <div id="nav-tabs-custom" class="nav-tabs-custom">
        <?= $this->render('_navigation'); ?>
        <div class="tab-content">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'theme')->fileInput(); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('writesdown', 'Upload'), ['class' => 'btn btn-flat btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
