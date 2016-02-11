<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $item common\models\MenuItem */
/* @var $wrap boolean */
?>
<?= isset($wrap) ? Html::beginTag('li', ['class' => 'dd-item', 'data-id' => $item->id]) : '' ?>

<div class="dd-handle"><?= $item->label ?></div>
<div class="menu-header clearfix">
    <?= Html::button('<i class="fa fa-caret-down"></i>', [
        'aria-controls' => 'menu-item-' . $item->id,
        'class' => 'btn btn-flat btn-default btn-detail-menu',
        'data-toggle' => 'collapse',
        'data-target' => '#menu-item-' . $item->id,
    ]) ?>

</div>
<div id="menu-item-<?= $item->id ?>" class="collapse menu-body clearfix">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <?= Html::label($item->getAttributeLabel('label'), 'menuitem-label-' . $item->id, [
                    'class' => 'form-label',
                ]) ?>

                <?= Html::textInput('MenuItem[' . $item->id . '][label]', $item->label, [
                    'id' => 'menuitem-label-' . $item->id,
                    'class' => 'menu-input form-control input-sm',
                ]) ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <?= Html::label($item->getAttributeLabel('url'), 'menuitem-url-' . $item->id, [
                    'class' => 'form-label',
                ]) ?>

                <?= Html::textInput('MenuItem[' . $item->id . '][url]', $item->url, [
                    'id' => 'menuitem-url-' . $item->id,
                    'class' => 'menu-input form-control input-sm',
                ]) ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <?= Html::label(
                    $item->getAttributeLabel('description'),
                    'menuitem-description-' . $item->id,
                    ['class' => 'form-label']
                ) ?>

                <?= Html::textarea('MenuItem[' . $item->id . '][description]', $item->description, [
                    'id' => 'menuitem-description-' . $item->id,
                    'class' => 'menu-input form-control input-sm',
                ]) ?>

            </div>
        </div>
        <div class="col-sm-12">
            <?= Html::button('<i class="fa fa-trash"></i> ' . Yii::t('writesdown', 'Remove Menu'), [
                'data' => [
                    'url' => Url::to(['delete-menu-item']),
                    'id' => $item->id,
                ],
                'class' => 'btn-flat btn btn-sm btn-danger delete-menu-item',
            ]) ?>

        </div>
    </div>
</div>
<?= isset($wrap) ? Html::endTag('li') : '' ?>
