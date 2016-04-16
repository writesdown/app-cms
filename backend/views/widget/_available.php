<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $available [] */
/* @var $spaces [] */

$index = 0;
$sizeofAvailable = sizeof($available);
$divideAvailable = round($sizeofAvailable / 2);
?>
<?php foreach ($available as $directory => $widget): ?>
    <?php if ($index == 0 || $index == $divideAvailable): ?>
        <div class="col-sm-12 col-md-6">
    <?php endif ?>

    <div id="available-widget-<?= $directory ?>" class="available-widget box box-solid collapsed-box">
        <div class="box-header">
            <h3 class="box-title"><?= ArrayHelper::getValue($widget, 'title') ?></h3>

            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                <?= Html::a('<i class="fa fa-times"></i>', ['delete', 'id' => $directory], [
                    'class' => 'btn btn-box-tool',
                    'data' => [
                        'method' => 'post',
                        'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
                    ],
                ]) ?>

            </div>
        </div>

        <?php if ($widgetDescription = ArrayHelper::getValue($widget, 'description')): ?>
            <?= Html::tag('div', $widgetDescription, ['class' => 'box-body']) ?>
        <?php endif ?>

        <?php $form = ActiveForm::begin([
            'id' => 'widget-available-form-' . $directory,
            'action' => Url::to(['/site/forbidden']),
            'options' => [
                'data-url' => Url::to(['ajax-activate', 'id' => $directory]),
                'class' => 'widget-available-form box-footer',
            ],
        ]) ?>

        <div class="input-group">
            <?= Html::dropDownList(
                'Widget[location]',
                null,
                ArrayHelper::map($spaces, 'location', 'title'),
                ['class' => 'input-sm form-control widget-location']
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
