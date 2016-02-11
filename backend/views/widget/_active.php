<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $active common\models\Widget */
/* @var $available [] */
?>
<?php $form = ActiveForm::begin([
    'id' => 'widget-active-form-' . $active->id,
    'action' => Url::to(['/site/forbidden']),
    'options' => [
        'class' => 'widget-active-form box box-solid box-default collapsed-box',
        'data-url' => Url::to(['ajax-update', 'id' => $active->id]),
        'data-id' => $active->id,
    ],
]) ?>

<div class="box-header with-border">
    <h3 class="box-title"><?= $active->title ?></h3>

    <div class="box-tools pull-right">
        <a href="#" data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
        <?= Html::button('<i class="fa fa-times"></i>', [
            'class' => 'ajax-delete-widget-btn btn btn-box-tool',
            'data' => ['url' => Url::to(['ajax-delete', 'id' => $active->id])],
        ]) ?>

    </div>
</div>
<div class="box-body widget-configuration">

    <?php if (isset($available[$active->directory]['page'])): ?>
        <?= $this->renderFile($available[$active->directory]['page'], [
            'widget' => $active,
            'form' => $form,
        ]) ?>
    <?php else: ?>
        <?= $this->render('_form', [
            'widget' => $active,
            'form' => $form,
        ]) ?>
    <?php endif ?>

</div>
<div class="box-footer">
    <button class="btn btn-flat btn-default btn-sm" type="submit"><?= Yii::t('writesdown', 'Save') ?></button>
</div>
<?php $form::end() ?>
