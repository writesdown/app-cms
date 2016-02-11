<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

/* @var $this \yii\web\View */
/* @var $model common\models\Module */
/* @var $form \yii\widgets\ActiveForm */
/* @var $config array */
/* @var $type string */

/**
 * Render widget config.
 *
 * @param $form    \yii\widgets\ActiveForm
 * @param $model   \common\models\Widget
 * @param $config  array
 * @param $type    string
 * @param $oldKey  null|array
 */
$renderConfig = function ($form, $model, $config, $type, $oldKey = null) use (&$renderConfig) {
    echo '<ul>';

    foreach ($config as $key => $value) {
        echo '<li>';
        if (is_array($value)) {
            $renderConfig($form, $model, $value, $type, $oldKey . "[$key]");
        } else {
            echo $form->field($model, "config" . "[$type]" . $oldKey . "[$key]")->textInput([
                'class' => 'form-control input-sm',
                'value' => $value,
                'readonly' => $key === 'class' ? 'readonly' : null,
            ])->label($key);
        }
        echo '</li>';
    }

    echo '</ul>';
};

$renderConfig($form, $model, $config, $type);
