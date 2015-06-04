<?php
/**
 * @file      media.php.
 * @date      6/4/2015
 * @time      11:51 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model object */
/* @var $group string */

$this->title = Yii::t('writesdown', '{group} Settings', ['group' => ucwords($group)]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="options-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

    <h2><?= Yii::t('writesdown', 'Image sizes') ?></h2>

    <p><?= Yii::t('writesdown', 'The sizes listed below determine the maximum dimensions in pixels to use when adding an image to the Media Library.') ?></p>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Thumbnail size'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">

            <div class="checkbox">

                <?= Html::label(Yii::t('writesdown', 'Width'), null, ['style' => 'padding-left: 0']); ?>
                <?= Html::input('number', 'Option[thumbnail_width][option_value]', $model->thumbnail_width->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 70px']); ?>

                <?= Html::label(Yii::t('writesdown', 'Height'), null, ['style' => 'padding-left: 0']); ?>
                <?= Html::input('number', 'Option[thumbnail_height][option_value]', $model->thumbnail_height->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 70px']); ?>

                <br/>

                <?= Html::label(Html::checkbox('Option[thumbnail_crop][option_value]', $model->thumbnail_crop->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Crop thumbnail to exact dimensions')); ?>

            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Medium size'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">

            <div class="checkbox">

                <?= Html::label(Yii::t('writesdown', 'Max Width'), null, ['style' => 'padding-left: 0']); ?>
                <?= Html::input('number', 'Option[medium_width][option_value]', $model->medium_width->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 70px']); ?>

                <?= Html::label(Yii::t('writesdown', 'Max Height'), null, ['style' => 'padding-left: 0']); ?>
                <?= Html::input('number', 'Option[medium_height][option_value]', $model->medium_height->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 70px']); ?>

            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Large size'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">

            <div class="checkbox">

                <?= Html::label(Yii::t('writesdown', 'Max Width'), null, ['style' => 'padding-left: 0']); ?>
                <?= Html::input('number', 'Option[large_width][option_value]', $model->large_width->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 70px']); ?>

                <?= Html::label(Yii::t('writesdown', 'Max Height'), null, ['style' => 'padding-left: 0']); ?>
                <?= Html::input('number', 'Option[large_height][option_value]', $model->large_height->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 70px']); ?>

            </div>
        </div>
    </div>

    <h2><?= Yii::t('writesdown', 'Uploading Files'); ?></h2>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Organizing files'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">
            <div class="checkbox">
                <?= Html::label(Html::checkbox('Option[uploads_yearmonth_based][option_value]', $model->uploads_yearmonth_based->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Organize my uploads into month- and year-based folders')); ?>
                <br/>
                <?= Html::label(Html::checkbox('Option[uploads_username_based][option_value]', $model->uploads_username_based->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Organize my uploads into username-based folders')); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton(Yii::t('writesdown', 'Save'), ['class' => 'btn btn-flat btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
