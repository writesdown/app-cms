<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Option;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name . ' - ' . Option::get('sitetitle');
$this->params['breadcrumbs'][] = $name;
$this->registerMetaTag([
    'name'    => 'robots',
    'content' => 'noindex, nofollow',
]);
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger"><?= nl2br(Html::encode($message)) ?></div>
    <p>The above error occurred while the Web server was processing your request.</p>
    <p>Please contact us if you think this is a server error. Thank you.</p>

    <h2><?= Yii::t('writesdown', 'Search with another keyword') ?></h2>

    <?php $form = ActiveForm::begin([
        'action'  => Url::to(['/site/search']),
        'method'  => 'get',
        'id'      => 'search-form-top',
        'options' => ['class' => 'form-search'],
    ]) ?>

    <div class="input-group">
        <?= Html::textInput('s', null, ['class' => 'form-control', 'placeholder' => 'Search for...']) ?>

        <span class="input-group-btn">
            <?= Html::button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search', [
                'class' => ' btn btn-default',
                'type'  => 'submit',
            ]) ?>

        </span>
    </div>
    <?php ActiveForm::end() ?>

</div>
