<?php
/**
 * @file      _config.php
 * @date      9/1/2015
 * @time      8:49 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $key string */
/* @var $label string */
?>

<?php
if(!is_array($config)){
    echo '<li>';
    echo $form->field($model, "module_config" . $key)->textInput([
        'value' => $config,
        'readonly' => $label === 'class' ? 'readonly' : null,
    ])->label($label);
    echo '</li>';
}else{
    echo '<ul>';
    foreach($config as $subKey => $subConfig){
        echo $this->render('_config', [
            'key'    => $key . "[$subKey]",
            'config' => $subConfig,
            'form'   => $form,
            'model'  => $model,
            'label'  => $subKey
        ]);
    }
    echo '</ul>';
}
