<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $availableWidget [] */
/* @var $activatedWidget [] */
/* @var $widgetSpace [] */

$index = 0;
$sizeofSpace = sizeof($widgetSpace);
$divideSpace = round($sizeofSpace / 2);
?>
<?php foreach ($widgetSpace as $space): ?>
    <?php if ($index == 0 || $index == $divideSpace): ?>
        <div class="col-sm-12 col-md-6">
    <?php endif ?>

    <div id="widget-space-<?= $space['location'] ?>" class="widget-space box collapsed-box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $space['title'] ?></h3>

            <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        <div class="box-body">

            <?php if (isset($space['description'])): ?>
                <p><?= $space['description'] ?></p>
            <?php endif ?>

            <div class="widget-order">

                <?php if (isset($activatedWidget[$space['location']])): ?>
                    <?php foreach ($activatedWidget[$space['location']] as $widget): ?>
                        <?= $this->render('_activated', [
                            'availableWidget' => $availableWidget,
                            'activatedWidget' => $widget,
                        ]) ?>
                    <?php endforeach ?>
                <?php endif ?>

            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'action'  => Url::to(['/site/forbidden']),
            'options' => [
                'class' => 'widget-order-form box-footer',
                'data'  => ['url' => Url::to(['ajax-save-order'])],
            ],
        ]) ?>

        <?= Html::hiddenInput('Widget[widget_order]', null, ['class' => 'widget-order-field']) ?>

        <?= Html::submitButton(Yii::t('writesdown', 'Save Order'), ['class' => 'btn btn-flat btn-success btn-block']) ?>

        <?php $form::end() ?>

    </div>

    <?php if ($index == $divideSpace - 1 || $index == $sizeofSpace - 1): ?>
        </div>
    <?php endif ?>

    <?php $index++ ?>

<?php endforeach ?>
