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
/* @var $form ActiveForm */
/* @var $activatedWidget common\models\Widget */
/* @var $availableWidget [] */
?>
<?php $form = ActiveForm::begin([
    'id'      => 'widget-activated-form-' . $activatedWidget->id,
    'action'  => Url::to(['/site/forbidden']),
    'options' => [
        'class'    => 'widget-activated-form box box-solid box-default collapsed-box',
        'data-url' => Url::to(['ajax-update', 'id' => $activatedWidget->id]),
        'data-id'  => $activatedWidget->id,
    ],
]) ?>

<div class="box-header with-border">
    <h3 class="box-title"><?= $activatedWidget->widget_title ?></h3>

    <div class="box-tools pull-right">
        <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-plus"></i></button>
        <?= Html::button('<i class="fa fa-times"></i>', [
            'class' => 'ajax-delete-widget-btn btn btn-box-tool',
            'data'  => ['url' => Url::to(['ajax-delete', 'id' => $activatedWidget->id])],
        ]) ?>

    </div>
</div>
<div class="box-body widget-configuration">

    <?php if (isset($availableWidget[$activatedWidget->widget_dir]['widget_page'])): ?>
        <?= $this->renderFile($availableWidget[$activatedWidget->widget_dir]['widget_page'], [
            'widget' => $activatedWidget,
            'form'   => $form,
        ]) ?>
    <?php else: ?>
        <ul>
            <?= $this->render('_form', [
                'widget' => $activatedWidget,
                'form'   => $form,
            ]) ?>
        </ul>
    <?php endif ?>

</div>
<div class="box-footer">
    <button class="btn btn-flat btn-default btn-sm" type="submit"><?= Yii::t('writesdown', 'Save') ?></button>
</div>
<?php $form::end() ?>
