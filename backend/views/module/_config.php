<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $key string */
/* @var $label string */
/* @var $model common\models\Module */
?>
<?php if (!is_array($config)): ?>
    <li>
        <?= $form->field($model, "module_config" . $key)->textInput([
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
                'model'  => $model,
                'label'  => $subKey,
            ]) ?>
        <?php endforeach ?>
    </ul>
<?php endif ?>
