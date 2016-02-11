<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use common\components\TimeZoneHelper;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
/* @var $form yii\widgets\ActiveForm */
/* @var $group string */
/* @var $model object */

$this->title = Yii::t('writesdown', 'General Settings');

$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="options-form">
    <?php $form = ActiveForm::begin(['id' => 'option-general-form', 'options' => ['class' => 'form-horizontal']]) ?>

    <div class="form-group">
        <?= Html::label(
            Yii::t('writesdown', 'Site Title'),
            'option-sitetitle',
            ['class' => 'col-sm-2 control-label']
        ) ?>

        <div class="col-sm-7">
            <?= Html::textInput('Option[sitetitle][value]', $model->sitetitle->value, [
                'class' => 'form-control',
                'id' => 'option-sitetitle',
            ]) ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Tagline'), 'option-tagline', ['class' => 'col-sm-2 control-label']) ?>

        <div class="col-sm-7">
            <?= Html::textInput('Option[tagline][value]', $model->tagline->value, [
                'class' => 'form-control',
                'id' => 'option-tagline',
            ]) ?>

            <p class="description">
                <?= Yii::t('writesdown', 'In a few words, explain what this site is about.') ?>

            </p>
        </div>
    </div>
    <div class="form-group">
        <?= Html::label(
            Yii::t('writesdown', 'E-mail Address'),
            'option-admin_email',
            ['class' => 'col-sm-2 control-label']
        ) ?>

        <div class="col-sm-7">
            <?= Html::textInput('Option[admin_email][value]', $model->admin_email->value, [
                'class' => 'form-control',
                'id' => 'option-admin_email',
            ]) ?>

            <p class="description">
                <?= Yii::t('writesdown', 'This address is used for admin purposes, like new user notification.') ?>

            </p>
        </div>
    </div>
    <div class="form-group">
        <?= Html::label(
            Yii::t('writesdown', 'Allow New Membership'),
            'option-allow_signup',
            ['class' => 'col-sm-2 control-label']
        ) ?>

        <div class="col-sm-7">
            <div class="checkbox">
                <?= Html::label(
                    Html::checkbox(
                        'Option[allow_signup][value]',
                        $model->allow_signup->value,
                        ['uncheck' => 0, 'id' => 'option-allow_signup']
                    ) . Yii::t('writesdown', ' Allow guest to register on this site')
                ) ?>

            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::label(
            Yii::t('writesdown', 'New User Default Role'),
            'option-default_role',
            ['class' => 'col-sm-2 control-label']
        ) ?>

        <div class="col-sm-7">
            <?php
            $role = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
            unset($role['superadmin']);

            if (Yii::$app->user->can('administrator')
                && !Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'superadmin')
            ) {
                unset($role['administrator']);
            }

            echo Html::dropDownList('Option[default_role][value]', $model->default_role->value, $role,
                ['id' => 'option-default_role', 'class' => 'form-control'])
            ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::label(
            Yii::t('writesdown', 'Time Zone'),
            'option-time_zone',
            ['class' => 'col-sm-2 control-label']
        ) ?>

        <div class="col-sm-7">
            <?= SelectizeDropDownList::widget([
                'name' => 'Option[time_zone][value]',
                'value' => $model->time_zone->value,
                'items' => TimeZoneHelper::listTimeZone(),
                'options' => [
                    'class' => 'form-control',
                    'id' => 'option-time_zone',
                ],
            ]) ?>

            <p class="description"><?= Yii::t('writesdown', 'Choose a city in the same timezone as you.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Date Format'), null, ['class' => 'col-sm-2 control-label']) ?>

        <div class="col-sm-7">
            <?= Html::radioList('radio-date_format', $model->date_format->value, [
                'F d, Y' => date('F d, Y'),
                'M d, Y' => date('M d, Y'),
                'Y-m-d' => date('Y-m-d'),
                'm/d/Y' => date('m/d/Y'),
                'custom' => Yii::t('writesdown', 'Custom')
                    . ': '
                    . Html::textInput('Option[date_format][value]', $model->date_format->value, [
                        'class' => 'value-date_format',
                        'readonly' => 'readonly',
                    ]),
            ], [
                'separator' => '<br />',
                'encode' => false,
                'class' => 'radio',
                'itemOptions' => ['class' => 'radio-date_format'],
            ]) ?>

            <p class="description">
                <?= Html::a(
                    Yii::t('writesdown', 'Read documentation for more info.'),
                    'http://php.net/manual/en/function.date.php',
                    ['rel' => 'external, nofollow', 'target' => '_blank']
                ) ?>

            </p>
        </div>
    </div>
    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Time Format'), null, ['class' => 'col-sm-2 control-label']) ?>

        <div class="col-sm-7">
            <?= Html::radioList('radio-time_format', $model->time_format->value, [
                'g:i:s a' => date('g:i:s a'),
                'g:i:s A' => date('g:i:s A'),
                'H:i:s' => date('H:i:s'),
                'custom' => Yii::t('writesdown', 'Custom')
                    . ': '
                    . Html::textInput('Option[time_format][value]', $model->time_format->value, [
                        'class' => 'value-time_format',
                        'readonly' => 'readonly',
                    ]),
            ], [
                'separator' => '<br />',
                'encode' => false,
                'class' => 'radio',
                'itemOptions' => ['class' => 'radio-time_format'],
            ]) ?>
            <p class="description">
                <?= Html::a(
                    Yii::t('writesdown', 'Read documentation for more info.'),
                    'http://php.net/manual/en/function.date.php',
                    ['rel' => 'external, nofollow', 'target' => '_blank']
                ) ?>

            </p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton(Yii::t('writesdown', 'Save'), ['class' => 'btn btn-flat btn-success']) ?>

        </div>
    </div>
    <?php ActiveForm::end() ?>

</div>
<?php $this->registerJs('(function($){
    $(".radio-time_format").click(function(){
        if($(this).val() !== "custom"){
            $(".value-time_format").val(($(this).val()));
        }else{
            $(".value-time_format").attr("readonly", false);
        }
    });
    $(".radio-date_format").click(function(){
        if($(this).val() !== "custom"){
            $(".value-date_format").val(($(this).val()));
        }else{
            $(".value-date_format").attr("readonly", false);
        }
    });
}(jQuery));') ?>
