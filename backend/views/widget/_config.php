<?php
/**
 * @link      http://www.writesdown.com/
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

if (!is_array($config)): ?>
    <li>
        <?= $form->field($widget, "widget_config" . $key)->textInput([
            'class'    => 'form-control input-sm',
            'value'    => $config,
            'readonly' => $label === 'class' ? 'readonly' : null,
        ])->label($label) ?>
    </li>
<?php else: ?>
    <ul>
        <?php foreach ($config as $subKey => $subConfig): ?>
            <?= $this->render('_config', [
                'key'    => $key . "[$subKey]",
                'config' => $subConfig,
                'form'   => $form,
                'widget' => $widget,
                'label'  => $subKey,
            ]) ?>
        <?php endforeach ?>
    </ul>
<?php endif ?>
