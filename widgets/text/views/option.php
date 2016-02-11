<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $widget common\models\Widget */

?>
<?php $config = $widget->getConfig() ?>
<?= Html::hiddenInput('Widget[config][class]', $config['class']) ?>

<div class="form-group">
    <?= Html::label('Title', 'title-' . $widget->id, ['class' => 'form-label']) ?>

    <?= Html::textInput(
        'Widget[config][title]',
        $config['title'],
        ['class' => 'form-control input-sm']
    ) ?>

</div>
<div class="form-group">
    <?= Html::label('Title', 'text-' . $widget->id, ['class' => 'form-label']) ?>

    <?= Html::textarea('Widget[config][text]', $config['text'], [
        'id' => 'text-' . $widget->id,
        'class' => 'form-control',
        'rows' => '5',
    ]) ?>

</div>
