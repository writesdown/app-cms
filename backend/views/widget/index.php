<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use backend\assets\WidgetAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $availableWidget [] */
/* @var $activatedWidget [] */
/* @var $widgetSpace [] */

$this->title = Yii::t('writesdown', 'Widgets');
$this->params['breadcrumbs'][] = $this->title;
WidgetAsset::register($this);
?>
<div class="row">
    <div class="col-sm-push-6 col-md-push-5 col-sm-6 col-md-7">
        <div class="row">
            <?= $this->render('_space', [
                'availableWidget' => $availableWidget,
                'activatedWidget' => $activatedWidget,
                'widgetSpace'     => $widgetSpace,
            ]) ?>
        </div>
    </div>
    <div class="col-sm-pull-6 col-md-pull-7 col-sm-6 col-md-5">
        <h4><?= Yii::t('writesdown', 'Available Widgets') ?></h4>

        <p class="description">
            <?= Yii::t('writesdown', 'To activate widget, click on + (plus), choose the location and click activate') ?>
        </p>
        <div class="row">
            <?= $this->render('_available', [
                'availableWidget' => $availableWidget,
                'widgetSpace'     => $widgetSpace,
            ]) ?>
        </div>
        <div class="form-group">
            <?= Html::a(
                Yii::t('writesdown', 'Add New Widget'),
                ['create'],
                ['class' => 'btn btn-default btn-block']
            ) ?>

        </div>
    </div>
</div>
