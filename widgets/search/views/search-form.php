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

?>
<?php $form = ActiveForm::begin([
    'action'  => Url::to(['/site/search']),
    'method'  => 'get',
    'options' => [
        'class' => 'search-form',
    ],
]) ?>

<?= Html::textInput('s', Yii::$app->request->get('s'), [
    'class'       => 'search-form-field',
    'placeholder' => 'Search for...',
]) ?>

<?= Html::submitButton('Search', ['class' => 'search-form-btn']) ?>

<?php ActiveForm::end() ?>
