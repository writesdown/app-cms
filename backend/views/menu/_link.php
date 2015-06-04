<?php
/**
 * @file      _link.php.
 * @date      6/4/2015
 * @time      6:06 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $selectedMenu common\models\Menu */
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class'    => 'panel box box-primary menu-create-menu-item',
        'data-url' => Url::to(['menu/create-menu-item', 'id' => $selectedMenu->id])
    ],
    'action'  => Url::to(['/site/forbidden'])
]); ?>

    <div class="box-header with-border">
        <h4 class="box-title">
            <a href="#link" data-parent="#create-menu-items" data-toggle="collapse" aria-expanded="true">
                <?= Yii::t('writesdown', 'Link Menu'); ?>
            </a>
        </h4>
    </div>
    <div class="panel-collapse collapse in" id="link">
        <div class="box-body">
            <div class="form-group">
                <?= Html::label(Yii::t('writesdown', 'Menu Label'), 'menu_item_label', ['class' => 'form-label']); ?>
                <?= Html::textInput('MenuItem[menu_label]', null, ['class' => 'form-control', 'placeholder' => 'Label', 'maxlength' => '255', 'id' => 'menu_item_label']); ?>
            </div>
            <div class="form-group">
                <?= Html::label(Yii::t('writesdown', 'Menu URL'), 'menu_item_url', ['class' => 'form-label']); ?>
                <?= Html::textInput('MenuItem[menu_url]', null, ['class' => 'form-control', 'placeholder' => 'URL', 'maxlength' => '255', 'id' => 'menu_item_url']); ?>
            </div>
        </div>
        <div class="box-footer">
            <?= Html::hiddenInput('type', 'link'); ?>
            <?= Html::submitButton(Yii::t('writesdown', 'Add Menu'), ['class' => 'btn btn-flat btn-primary btn-create-menu-item']); ?>
        </div>
    </div>

<?php ActiveForm::end();