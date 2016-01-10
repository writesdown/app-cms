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
/* @var $taxonomies common\models\Taxonomy[] */
?>
<div id="sitemap-taxonomies-option" class="sitemap-default-index-taxonomies tab-pane">

    <?php foreach ($taxonomies as $taxonomy): ?>
        <h4><?= $taxonomy->taxonomy_sn ?></h4>
        <div class="form-group checkbox">
            <?= Html::hiddenInput("Option[option_value][taxonomy][$taxonomy->id][enable]", 0) ?>

            <label>
                <?php
                $checked = isset($option['taxonomy'][$taxonomy->id]['enable'])
                && $option['taxonomy'][$taxonomy->id]['enable']
                    ? true
                    : false;
                ?>
                <?= Html::checkbox("Option[option_value][taxonomy][$taxonomy->id][enable]", $checked, ['value' => 1]) ?>

                <?= Yii::t('sitemap', 'Please check this checkbox if you want to include in your sitemap.') ?>

            </label>
        </div>
        <div class="form-group">
            <?php
            $selection = isset($option['taxonomy'][$taxonomy->id]['priority'])
            && $option['taxonomy'][$taxonomy->id]['priority']
                ? $option['taxonomy'][$taxonomy->id]['priority']
                : null;
            ?>
            <?= Html::label(
                Yii::t('sitemap', 'Priority:'),
                'option-option_value-taxonomies-priority-' . $taxonomy->id,
                ['class' => 'form-label']
            ) ?>

            <?= Html::dropDownList(
                "Option[option_value][taxonomy][$taxonomy->id][priority]",
                $selection, $priority, [
                    'class' => 'form-control',
                    'id'    => 'option-option_value-taxonomies-priority-' . $taxonomy->id,
                ]
            ) ?>
        </div>

        <div class="form-group">
            <?php
            $selection = isset($option['taxonomy'][$taxonomy->id]['changefreq'])
            && $option['taxonomy'][$taxonomy->id]['changefreq']
                ? $option['taxonomy'][$taxonomy->id]['changefreq']
                : null;
            ?>
            <?= Html::label(
                Yii::t('sitemap', 'Change Frequency:'),
                'option-option_value-taxonomies-changefreq-' . $taxonomy->id, ['class' => 'form-label']
            ) ?>

            <?= Html::dropDownList(
                "Option[option_value][taxonomy][$taxonomy->id][changefreq]",
                $selection,
                $changeFreq, [
                    'class' => 'form-control',
                    'id'    => 'option-option_value-taxonomies-changefreq-' . $taxonomy->id,
                ]
            ) ?>
        </div>
        <br/>
    <?php endforeach ?>

</div>
