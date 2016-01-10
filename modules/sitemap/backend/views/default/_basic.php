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

?>
<div id="sitemap-basic-option" class="sitemap-default-index-basic tab-pane active">
    <h4><?= Yii::t('sitemap', 'Enable Sitemap') ?></h4>
    <div class="form-group checkbox">
        <?= Html::hiddenInput('Option[option_value][enable_sitemap]', 0) ?>

        <label>
            <?php $checked = isset($option['enable_sitemap']) && $option['enable_sitemap'] ? true : false ?>
            <?= Html::checkbox('Option[option_value][enable_sitemap]', $checked, ['value' => 1]) ?>

            <?= Yii::t('sitemap', 'Check this checkbox to enable XML sitemap for this site') ?>

        </label>
        <p>
            <?= Yii::t('sitemap', 'You can find your XML Sitemap here: {sitemapUrl}', [
                'sitemapUrl' => Html::a(
                    Yii::t('sitemap', 'XML SITEMAP'),
                    Yii::$app->urlManagerFront->createUrl([$this->context->module->id]),
                    ['class' => 'btn btn-default btn-flat btn-sm']
                ),
            ]) ?>

        </p>
    </div>
    <br/>
    <h4><?= Yii::t('sitemap', 'Entries per page') ?></h4>
    <p>
        <?= Yii::t(
            'sitemap',
            'Please enter the maximum number of entries per sitemap page (defaults to 1000, you might want to lower this to prevent memory issues on some installs):'
        ) ?>
    </p>
    <div class="form-group">
        <?php $value = isset($option['entries_per_page']) ? $option['entries_per_page'] : 0 ?>
        <?= Html::label(Yii::t('sitemap', 'Max entries per sitemap page:'), 'sitemap-entries_per_page') ?>

        <?= Html::textInput('Option[option_value][entries_per_page]', $value, ['id' => 'sitemap-entries_per_page']); ?>

    </div>
</div>
