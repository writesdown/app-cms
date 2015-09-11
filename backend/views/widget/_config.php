<?php
/**
 * @file      _config.php
 * @date      9/10/2015
 * @time      5:14 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $widget common\models\Widget */
/* @var $form yii\widgets\ActiveForm */
/* @var $key string */
/* @var $label string */
/* @var $config array */

if (!is_array($config)) {
    echo '<li>';
    echo $form->field($widget, "widget_config" . $key)->textInput([
        'class'    => 'form-control input-sm',
        'value'    => $config,
        'readonly' => $label === 'class' ? 'readonly' : null,
    ])->label($label);
    echo '</li>';
} else {
    echo '<ul>';
    foreach ($config as $subKey => $subConfig) {
        echo $this->render('_config', [
            'key'    => $key . "[$subKey]",
            'config' => $subConfig,
            'form'   => $form,
            'widget' => $widget,
            'label'  => $subKey
        ]);
    }
    echo '</ul>';
}
