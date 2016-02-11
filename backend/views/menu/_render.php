<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use backend\widgets\RenderMenu;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $selected common\models\Menu */
?>

<?php $form = ActiveForm::begin([
    'action' => Url::to(['update', 'id' => $selected->id]),
    'options' => ['class' => 'menu-items-form'],
]) ?>

<div class="box box-primary">
    <div class="box-header">
        <h2 class="box-title"><?= $selected->title ?></h2>

        <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body clearfix">
        <?= $form->field($selected, 'title', ['template' => '{input}{error}'])->textInput([
            'id' => 'menu-update-title',
        ]) ?>

        <div class="dd">
            <?= RenderMenu::widget(['items' => $selected->getBackendItems()]) ?>

        </div>

        <?php if (isset(Yii::$app->params['menu']['location'])): ?>
            <?= $form->field($selected, 'location')->radioList(
                ArrayHelper::merge(Yii::$app->params['menu']['location'], ['none' => Yii::t('writesdown', 'None')]),
                ['separator' => '<br />', 'class' => 'radio']
            ) ?>
        <?php endif ?>

    </div>
    <div class="box-footer">
        <?= Html::hiddenInput('MenuOrder', null, ['id' => 'menu-output', 'class' => 'form-control']) ?>

        <?= Html::submitButton(Yii::t('writesdown', 'Save'), [
            'id' => 'save-menu',
            'class' => 'btn btn-flat btn-primary',
        ]) ?>

    </div>
</div>
<?php ActiveForm::end() ?>
