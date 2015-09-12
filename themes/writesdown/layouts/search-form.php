<?php
/**
 * @file      search-form.php
 * @date      9/12/2015
 * @time      3:39 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action'  => Url::to(['/site/search']),
    'method'  => 'get',
    'options' => ['class' => 'form-search']
]);?>
    <div class="input-group">
        <?= Html::textInput('s', null, ['class' => 'form-control', 'placeholder' => 'Search...']); ?>
        <span class="input-group-btn">
            <?= Html::button('<span class="glyphicon glyphicon-search"></span>', [
                'class' => ' btn btn-default',
                'type' => 'submit'
            ]); ?>
        </span>
    </div>
<?php ActiveForm::end();
