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
/* @var $model common\models\Module */
/* @var $form yii\widgets\ActiveForm */

$moduleConfig = $model->getConfig();
?>
<div class="module-form">
    <?php $form = ActiveForm::begin(['id' => 'module-update-form']) ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <?= Html::a(Yii::t('writesdown', 'Basic'), '#basic-configuration', [
                    'class'       => 'active',
                    'data-toggle' => 'tab',
                ]) ?>

            </li>

            <?php if (isset($moduleConfig['frontend'])): ?>
                <li>
                    <?= Html::a(
                        Yii::t('writesdown', 'Frontend'),
                        '#frontend-configuration',
                        ['data-toggle' => 'tab']
                    ) ?>

                </li>
            <?php endif ?>

            <?php if (isset($moduleConfig['backend'])): ?>
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
                <?= $model->module_description
                    ? Html::tag('div', $model->module_description, ['class' => 'form-group'])
                    : '' ?>

                <?= $form->field($model, 'module_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'module_title')->textInput() ?>

                <?= $form->field($model, 'module_status')->checkbox([
                    'label'   => Yii::t('writesdown', 'Active'),
                    'checked' => true,
                    'value'   => '1',
                    'uncheck' => '0',
                ]) ?>

            </div>

            <?php if (isset($moduleConfig['frontend'])): ?>
                <div id="frontend-configuration" class="tab-pane">
                    <ul>
                        <?php foreach ($moduleConfig['frontend'] as $key => $config): ?>
                            <?= $this->render('_config', [
                                'key'    => "[frontend][$key]",
                                'config' => $config,
                                'form'   => $form,
                                'model'  => $model,
                                'label'  => $key,
                            ]) ?>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <?php if (isset($moduleConfig['backend'])): ?>
                <div id="backend-configuration" class="tab-pane">
                    <ul>
                        <?php foreach ($moduleConfig['backend'] as $key => $config): ?>
                            <?= $this->render('_config', [
                                'key'    => "[backend][$key]",
                                'config' => $config,
                                'form'   => $form,
                                'model'  => $model,
                                'label'  => $key,
                            ]) ?>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Update'), ['class' => 'btn btn-flat btn-primary']) ?>

    </div>

    <?php ActiveForm::end() ?>
</div>
