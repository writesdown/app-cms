<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $widget common\models\Widget */

?>
<?php $widgetConfig = $widget->getConfig() ?>
<?= Html::hiddenInput('Widget[widget_config][class]', $widgetConfig['class']) ?>

<div class="form-group">
    <?= Html::label('Title', 'title-' . $widget->id, ['class' => 'form-label']) ?>

    <?= Html::textInput(
        'Widget[widget_config][title]',
        $widgetConfig['title'],
        ['class' => 'form-control input-sm']
    ) ?>

</div>
<div class="form-group">
    <?= Html::label('Title', 'text-' . $widget->id, ['class' => 'form-label']) ?>

    <?= Html::textarea('Widget[widget_config][text]', $widgetConfig['text'], [
        'id'    => 'text-' . $widget->id,
        'class' => 'form-control',
        'rows'  => '5'
    ]) ?>

</div>
