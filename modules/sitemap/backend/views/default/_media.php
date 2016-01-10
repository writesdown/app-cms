<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $option [] */
/* @var $priority [] */
/* @var $changeFreq [] */
?>
<div id="sitemap-media-option" class="sitemap-default-index-media tab-pane">
    <h4><i class="fa fa-picture-o"></i> <?= Yii::t('sitemap', 'Media Option') ?></h4>
    <div class="form-group checkbox">
        <?= Html::hiddenInput('Option[option_value][media][enable]', 0) ?>

        <label>
            <?php
            $checked = isset($option['media']['enable']) && $option['media']['enable'] ?
                true :
                false;
            ?>
            <?= Html::checkbox("Option[option_value][media][enable]", $checked, ['value' => 1]) ?>

            <?= Yii::t('sitemap', 'Please check this checkbox if you want to include in your sitemap.') ?>

        </label>
    </div>
    <div class="form-group">
        <?php $selection = isset($option['media']['priority']) ? $option['media']['priority'] : null ?>
        <?= Html::label(
            Yii::t('sitemap', 'Priority:'),
            'option-option_value-media-priority',
            ['class' => 'form-label']
        ) ?>

        <?= Html::dropDownList(
            'Option[option_value][media][priority]',
            $selection,
            $priority,
            ['class' => 'form-control']
        ) ?>

    </div>
    <div class="form-group">
        <?php $selection = isset($option['media']['changefreq']) ? $option['media']['changefreq'] : null ?>
        <?= Html::label(
            Yii::t('sitemap', 'Change Frequency:'),
            'option-option_value-media-changefreq',
            ['class' => 'form-label']
        ) ?>

        <?= Html::dropDownList(
            'Option[option_value][media][changefreq]',
            $selection,
            $changeFreq,
            ['class' => 'form-control']
        ) ?>

    </div>
</div>
