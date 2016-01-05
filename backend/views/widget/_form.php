<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $widget common\models\Widget */

$widgetConfig = $widget->getConfig();

foreach ($widgetConfig as $key => $config) {
    echo $this->render('_config', [
        'key'    => "[$key]",
        'config' => $config,
        'form'   => $form,
        'widget' => $widget,
        'label'  => $key,
    ]);
}
