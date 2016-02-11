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
/* @var $available [] */
/* @var $selected common\models\Menu */

$form = ActiveForm::begin([
    'id' => 'select-menu-form',
    'action' => Url::to(['index']),
    'method' => 'get',
]) ?>

<div class="menu-select_menu">
    <div class="input-group">
        <?= Html::dropDownList('id', isset($selected) ? $selected->id : null, $available, [
            'id' => 'select-menu-list',
            'class' => 'form-control',
        ]) ?>

        <div class="input-group-btn">
            <?= Html::submitButton(
                Yii::t('writesdown', 'Select Menu'),
                ['class' => 'btn btn-flat btn-primary submit-button']
            ) ?>

            <?= Html::button('<i class="fa fa-trash"></i>', [
                'id' => 'delete-menu',
                'class' => 'btn btn-flat btn-danger',
                'data' => [
                    'message' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
                    'url' => Url::to(['delete']),
                    'error' => Yii::t('writesdown', 'At least select one of the menu'),
                ],
            ]) ?>

        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
