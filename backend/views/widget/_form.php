<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

/* @var $this \yii\web\View */
/* @var $form \yii\bootstrap\ActiveForm */
/* @var $widget \common\models\Widget */

/**
 * Render widget config.
 *
 * @param $form   \yii\widgets\ActiveForm
 * @param $model  \common\models\Widget
 * @param $config array
 * @param $oldKey null|array
 */
$renderConfig = function ($form, $model, $config, $oldKey = null) use (&$renderConfig) {
    echo '<ul>';

    foreach ($config as $key => $value) {
        echo '<li>';
        if (is_array($value)) {
            $renderConfig($form, $model, $value, $oldKey . "[$key]");
        } else {
            echo $form->field($model, "config" . $oldKey . "[$key]")->textInput([
                'class' => 'form-control input-sm',
                'value' => $value,
                'readonly' => $key === 'class' ? 'readonly' : null,
            ])->label($key);
        }
        echo '</li>';
    }

    echo '</ul>';
};

$renderConfig($form, $widget, $widget->getConfig());
