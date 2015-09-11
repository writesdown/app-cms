<?php
/**
 * @file      _form.php
 * @date      9/1/2015
 * @time      8:48 PM
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

    <?php $form = ActiveForm::begin(); ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php
            // Basic configuration nav
            echo Html::beginTag('li', ['class' => 'active']);
            echo Html::a(Yii::t('writesdown', 'Basic'), '#basic-configuration', [
                'class'       => 'active',
                'data-toggle' => 'tab'
            ]);
            echo Html::endTag('li');

            // Frontend configuration nav
            if (isset($moduleConfig['frontend'])) {
                echo Html::beginTag('li');
                echo Html::a(Yii::t('writesdown', 'Frontend'), '#frontend-configuration', [
                    'data-toggle' => 'tab'
                ]);
                echo Html::endTag('li');
            }

            // Backend configuration nav
            if (isset($moduleConfig['backend'])) {
                echo Html::beginTag('li');
                echo Html::a(Yii::t('writesdown', 'Backend'), '#backend-configuration', [
                    'data-toggle' => 'tab'
                ]);
                echo Html::endTag('li');
            }
            ?>
        </ul>

        <div id="module-configuration" class="tab-content">
            <?php
            // Basic configuration
            echo Html::beginTag('div', ['id' => 'basic-configuration', 'class' => 'tab-pane active']);
            echo $model->module_description ? Html::tag('div', $model->module_description, ['class' => 'form-group']) : '';
            echo $form->field($model, 'module_name')->textInput(['maxlength' => true]);
            echo $form->field($model, 'module_title')->textInput();
            echo $form->field($model, 'module_status', [
            ])->checkbox([
                'label'   => Yii::t('writesdown', 'Active'),
                'checked' => true,
                'value'   => '1',
                'uncheck' => '0'
            ]);
            echo Html::endTag('div');

            // Frontend configuration
            if (isset($moduleConfig['frontend'])) {
                echo Html::beginTag('div', ['id' => 'frontend-configuration', 'class' => 'tab-pane']);
                echo '<ul>';
                foreach ($moduleConfig['frontend'] as $key => $config) {
                    echo $this->render('_config', [
                        'key'    => "[frontend][$key]",
                        'config' => $config,
                        'form'   => $form,
                        'model'  => $model,
                        'label'  => $key
                    ]);
                }
                echo '</ul>';
                echo Html::endTag('div');
            }

            // Backend configuration
            if (isset($moduleConfig['backend'])) {
                echo Html::beginTag('div', ['id' => 'backend-configuration', 'class' => 'tab-pane']);
                echo '<ul>';
                foreach ($moduleConfig['backend'] as $key => $config) {
                    echo $this->render('_config', [
                        'key'    => "[backend][$key]",
                        'config' => $config,
                        'form'   => $form,
                        'model'  => $model,
                        'label'  => $key
                    ]);
                }
                echo '</ul>';
                echo Html::endTag('div');
            }
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('writesdown', 'Create') : Yii::t('writesdown', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-flat btn-success' : 'btn btn-flat btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

