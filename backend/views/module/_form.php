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
/* @var $model common\models\Module */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="module-form">
    <?php $form = ActiveForm::begin(['id' => 'module-update-form']) ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <?= Html::a(Yii::t('writesdown', 'Basic'), '#basic-configuration', [
                    'class' => 'active',
                    'data-toggle' => 'tab',
                ]) ?>

            </li>

            <?php if ($frontendConfig = $model->getFrontendConfig()): ?>
                <li>
                    <?= Html::a(
                        Yii::t('writesdown', 'Frontend'),
                        '#frontend-configuration',
                        ['data-toggle' => 'tab']
                    ) ?>

                </li>
            <?php endif ?>

            <?php if ($backendConfig = $model->getBackendConfig()): ?>
                <li>
                    <?= Html::a(
                        Yii::t('writesdown', 'Backend'),
                        '#backend-configuration',
                        ['data-toggle' => 'tab']
                    ) ?>

                </li>
            <?php endif ?>
        </ul>
        <div id="module-configuration" class="tab-content">
            <div id="basic-configuration" class="tab-pane active">
                <?= $model->description ? Html::tag('div', $model->description, ['class' => 'form-group']) : '' ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'title')->textInput() ?>

                <?= $form->field($model, 'status')->checkbox([
                    'label' => Yii::t('writesdown', 'Active'),
                    'checked' => true,
                    'value' => '1',
                    'uncheck' => '0',
                ]) ?>

            </div>

            <?php if ($frontendConfig): ?>
                <div id="frontend-configuration" class="tab-pane">
                    <?= $this->render('_config', [
                        'form' => $form,
                        'model' => $model,
                        'config' => $frontendConfig,
                        'type' => 'frontend',
                    ]); ?>
                </div>
            <?php endif ?>

            <?php if ($backendConfig): ?>
                <div id="backend-configuration" class="tab-pane">
                    <?= $this->render('_config', [
                        'form' => $form,
                        'model' => $model,
                        'config' => $backendConfig,
                        'type' => 'backend',
                    ]); ?>
                </div>
            <?php endif ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Update'), ['class' => 'btn btn-flat btn-primary']) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
