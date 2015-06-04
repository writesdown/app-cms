<?php
/**
 * @file      _create.php.
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
/* @var $model common\models\Menu */

$form = ActiveForm::begin([
    'action' => Url::to(['/menu/create'])
]);
?>
    <div class="input-group">
        <?= $form->field($model, 'menu_title', ['template' => '{input}'])->textInput(['placeholder' => $model->getAttributeLabel('menu_title')]) ?>
        <div class="input-group-btn">
            <?= Html::submitButton(Yii::t('writesdown', 'Add New Menu'), ['class' => 'btn btn-flat btn-primary']); ?>
        </div>
    </div>
<?php ActiveForm::end();