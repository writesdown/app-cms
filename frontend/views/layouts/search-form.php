<?php
/**
 * @file      search-form.php.
 * @date      6/4/2015
 * @time      10:24 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'action'  => Url::to(['/site/search']),
    'method'  => 'get',
    'options' => ['class' => 'form-search']
]);?>
    <div class="input-group">
        <?= Html::textInput('s', Yii::$app->request->get('s'), ['class' => 'form-control', 'placeholder' => 'Search...']); ?>
        <span class="input-group-btn">
            <?= Html::button('<span class="glyphicon glyphicon-search"></span>', [
                'class' => ' btn btn-primary',
                'type' => 'submit'
            ]); ?>
        </span>
    </div>
<?php ActiveForm::end();