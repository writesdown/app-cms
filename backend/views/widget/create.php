<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Widget */

$this->title = Yii::t('writesdown', 'Add New Widget');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Widgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-create">
    <div id="nav-tabs-custom" class="nav-tabs-custom">
        <?= Nav::widget([
            'items'        => [
                [
                    'label'   => '<i class="fa fa-upload"></i> ' . Yii::t('writesdown', 'Upload New Widget'),
                    'options' => ['class' => 'active'],
                ],
            ],
            'encodeLabels' => false,
            'options'      => ['class' => 'nav-tabs nav-theme', 'id' => 'nav-theme'],
        ]) ?>

        <div class="tab-content">
            <?php $form = ActiveForm::begin([
                'id'      => 'widget-create-form',
                'options' => ['enctype' => 'multipart/form-data'],
            ]) ?>

            <?= $form->field($model, 'widget_file')->fileInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('writesdown', 'Upload'), ['class' => 'btn btn-flat btn-primary']) ?>

            </div>
            <?php ActiveForm::end() ?>

        </div>
    </div>
</div>
