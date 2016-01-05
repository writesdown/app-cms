<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $item common\models\MenuItem */
/* @var $wrapper boolean */
?>
<?= isset($wrapper) ? Html::beginTag('li', ['class' => 'dd-item', 'data-id' => $item->id]) : '' ?>

<div class="dd-handle"><?= $item->menu_label ?></div>
<div class="menu-header clearfix">
    <?= Html::button('<i class="fa fa-caret-down"></i>', [
        'aria-controls' => 'menu-item-' . $item->id,
        'class'         => 'btn btn-flat btn-default btn-detail-menu',
        'data-toggle'   => 'collapse',
        'data-target'   => '#menu-item-' . $item->id,
    ]) ?>

</div>
<?= Html::beginTag('div', ['id' => 'menu-item-' . $item->id, 'class' => 'collapse menu-body clearfix']) ?>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <?= Html::label($item->getAttributeLabel('menu_label'), 'menuitem-menu_label-' . $item->id, [
                'class' => 'form-label',
            ]) ?>

            <?= Html::textInput('MenuItem[' . $item->id . '][menu_label]', $item->menu_label, [
                'id'    => 'menuitem-menu_label-' . $item->id,
                'class' => 'menu-input form-control input-sm',
            ]) ?>

        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <?= Html::label($item->getAttributeLabel('menu_url'), 'menuitem-menu_url-' . $item->id, [
                'class' => 'form-label',
            ]) ?>

            <?= Html::textInput('MenuItem[' . $item->id . '][menu_url]', $item->menu_url, [
                'id'    => 'menuitem-menu_url-' . $item->id,
                'class' => 'menu-input form-control input-sm',
            ]) ?>

        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <?= Html::label(
                $item->getAttributeLabel('menu_description'),
                'menuitem-menu_description-' . $item->id,
                ['class' => 'form-label']
            ) ?>

            <?= Html::textarea('MenuItem[' . $item->id . '][menu_description]', $item->menu_description, [
                'id'    => 'menuitem-menu_description-' . $item->id,
                'class' => 'menu-input form-control input-sm',
            ]) ?>

        </div>
    </div>
    <div class="col-sm-12">
        <?= Html::button('<i class="fa fa-trash"></i> ' . Yii::t('writesdown', 'Remove Menu'), [
            'data'  => [
                'url' => Url::to(['menu/delete-menu-item']),
                'id'  => $item->id,
            ],
            'class' => 'btn-flat btn btn-sm btn-danger menu-delete-menu-item',
        ]) ?>

    </div>
</div>
<?= Html::endTag('div') ?>

<?= isset($wrapper) ? Html::endTag('li') : '' ?>
