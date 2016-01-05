<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $availableWidget [] */
/* @var $widgetSpace [] */

$index = 0;
$sizeofAvailable = sizeof($availableWidget);
$divideAvailable = round($sizeofAvailable / 2);
?>
<?php foreach ($availableWidget as $dir => $widget): ?>
    <?php if ($index == 0 || $index == $divideAvailable): ?>
        <div class="col-sm-12 col-md-6">
    <?php endif ?>

    <div id="available-widget-<?= $widget['widget_dir'] ?>" class="available-widget box box-solid collapsed-box">
        <div class="box-header">
            <h3 class="box-title"><?= $widget['widget_title'] ?></h3>

            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                <?= Html::a('<i class="fa fa-times"></i>', ['delete-widget', 'id' => $widget['widget_dir']], [
                    'class' => 'btn btn-box-tool',
                    'data'  => [
                        'method'  => 'post',
                        'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this widget?'),
                    ],
                ]) ?>

            </div>
        </div>

        <?php if (isset($widget['widget_description'])): ?>
            <?= Html::tag('div', $widget['widget_description'], ['class' => 'box-body']) ?>
        <?php endif ?>

        <?php $form = ActiveForm::begin([
            'id'      => 'widget-available-form-' . $widget['widget_dir'],
            'action'  => Url::to(['/site/forbidden']),
            'options' => [
                'data-url' => Url::to(['ajax-activate', 'id' => $widget['widget_dir']]),
                'class'    => 'widget-activation-form box-footer',
            ],
        ]) ?>

        <div class="input-group">
            <?= Html::dropDownList(
                'Widget[widget_location]',
                null,
                ArrayHelper::map($widgetSpace, 'location', 'title'),
                ['class' => 'input-sm form-control widget-widget_location']
            ) ?>

            <div class="input-group-btn">
                <button type="submit" class="btn btn-flat btn-sm btn-default">
                    <?= Yii::t('writesdown', 'Activate') ?>

                </button>
            </div>
        </div>
        <?php ActiveForm::end() ?>

    </div>

    <?php if ($index == $divideAvailable - 1 || $index == $sizeofAvailable - 1): ?>
        </div>
    <?php endif ?>

    <?php $index++; ?>

<?php endforeach ?>
