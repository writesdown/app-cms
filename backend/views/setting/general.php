<?php
/**
 * @file      general.php.
 * @date      6/4/2015
 * @time      11:51 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\TimeZoneHelper;
use dosamigos\selectize\SelectizeDropDownList;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
/* @var $form yii\widgets\ActiveForm */
/* @var $group string */

$this->title = Yii::t('writesdown', '{group} Settings', ['group' => ucwords($group)]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="options-form">

        <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Site Title'), 'option-sitetitle', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= Html::textInput('Option[sitetitle][option_value]', $model->sitetitle->option_value, ['class' => 'form-control', 'id' => 'option-sitetitle']); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Tagline'), 'option-tagline', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= Html::textInput('Option[tagline][option_value]', $model->tagline->option_value, ['class' => 'form-control', 'id' => 'option-tagline']); ?>
                <p class="description"><?= Yii::t('writesdown', 'In a few words, explain what this site is about.'); ?></p>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'E-mail Address'), 'option-admin_email', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= Html::textInput('Option[admin_email][option_value]', $model->admin_email->option_value, ['class' => 'form-control', 'id' => 'option-admin_email']); ?>
                <p class="description"><?= Yii::t('writesdown', 'This address is used for admin purposes, like new user notification.'); ?></p>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Allow New Membership'), 'option-allow_signup', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <div class="checkbox">
                    <?= Html::label(Html::checkbox('Option[allow_signup][option_value]', $model->allow_signup->option_value, ['uncheck' => 0, 'id' => 'option-allow_signup']) . Yii::t('writesdown', ' Allow guest to register on this site')); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'New User Default Role'), 'option-default_role', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?php
                $role = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
                unset($role['superadmin']);
                if (Yii::$app->user->can('administrator') && !Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'superadmin'))
                    unset($role['administrator']);
                ?>
                <?= Html::dropDownList('Option[default_role][option_value]', $model->default_role->option_value, $role, ['id' => 'option-default_role', 'class' => 'form-control']); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Time Zone'), 'option-time_zone', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= SelectizeDropDownList::widget([
                    'name'    => 'Option[time_zone][option_value]',
                    'value'   => $model->time_zone->option_value,
                    'items'   => TimeZoneHelper::listTimeZone(),
                    'options' => [
                        'class' => 'form-control',
                        'id'    => 'option-time_zone'
                    ]
                ]); ?>
                <p class="description"><?= Yii::t('writesdown', 'Choose a city in the same timezone as you.'); ?></p>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Date Format'), null, ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= Html::radioList('radio-date_format', $model->date_format->option_value, [
                    'F d, Y' => date('F d, Y'),
                    'M d, Y' => date('M d, Y'),
                    'Y-m-d'  => date('Y-m-d'),
                    'm/d/Y'  => date('m/d/Y'),
                    'custom' => Yii::t('writesdown', 'Custom: ') . Html::textInput('Option[date_format][option_value]', $model->date_format->option_value, ['class' => 'value-date_format', 'readonly' => 'readonly'])

                ], [
                    'separator'   => '<br />',
                    'encode'      => false,
                    'class'       => 'radio',
                    'itemOptions' => ['class' => 'radio-date_format']
                ]); ?>
                <p class="description"><?= Html::a(Yii::t('writesdown', 'What\'s this?'), 'http://php.net/manual/en/function.date.php', ['rel' => 'external, nofollow', 'target' => '_blank']); ?></p>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Time Format'), null, ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= Html::radioList('radio-time_format', $model->time_format->option_value, [
                    'g:i:s a' => date('g:i:s a'),
                    'g:i:s A' => date('g:i:s A'),
                    'H:i:s'   => date('H:i:s'),
                    'custom'  => Yii::t('writesdown', 'Custom: ') . Html::textInput('Option[time_format][option_value]', $model->time_format->option_value, ['class' => 'value-time_format', 'readonly' => 'readonly'])
                ], [
                    'separator'   => '<br />',
                    'encode'      => false,
                    'class'       => 'radio',
                    'itemOptions' => ['class' => 'radio-time_format']
                ]); ?>
                <p class="description"><?= Html::a(Yii::t('writesdown', 'What\'s this?'), 'http://php.net/manual/en/function.date.php', ['rel' => 'external, nofollow', 'target' => '_blank']); ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton(Yii::t('writesdown', 'Save'), ['class' => 'btn btn-flat btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php $this->registerJs('
(function($){
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
})(jQuery);
');