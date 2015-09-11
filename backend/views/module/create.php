<?php
/**
 * @file      create.php
 * @date      9/1/2015
 * @time      4:09 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Module */

$this->title = Yii::t('writesdown', 'Add New Module');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Modules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="module-create">
    <div id="nav-tabs-custom" class="nav-tabs-custom">

        <?= Nav::widget([
            'items'        => [
                [
                    'label'   => '<i class="fa fa-upload"></i> ' . Yii::t('writesdown', 'Upload New Module'),
                    'options' => ['class' => 'active']
                ],
            ],
            'encodeLabels' => false,
            'options'      => [
                'class' => 'nav-tabs nav-theme',
                'id'    => 'nav-theme'
            ],
        ]); ?>

        <div class="tab-content">

            <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ]); ?>

            <?= $form->field($model, 'module_file')->fileInput(); ?>

            <p>
                <strong><?= Yii::t('writesdown', 'Caution: ') ?></strong>
                <?= Yii::t('writesdown', 'Be careful with bootstrap module (It can break your application if it is invalid).');
                ?>
            </p>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('writesdown', 'Upload'), ['class' => 'btn btn-flat btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
