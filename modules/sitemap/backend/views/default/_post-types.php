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
/* @var $postTypes common\models\PostType[] */
?>
<div id="sitemap-post-types-option" class="sitemap-default-index-post-types tab-pane">

    <?php foreach ($postTypes as $postType): ?>
        <h4><i class="<?= $postType->post_type_icon ?>"></i><?= $postType->post_type_sn ?></h4>
        <div class="form-group checkbox">
            <?= Html::hiddenInput("Option[option_value][post_type][$postType->id][enable]", 0) ?>

            <label>
                <?php $checked = isset($option['post_type'][$postType->id]['enable'])
                && $option['post_type'][$postType->id]['enable']
                    ? true
                    : false;
                ?>
                <?= Html::checkbox(
                    "Option[option_value][post_type][$postType->id][enable]",
                    $checked,
                    ['value' => 1]
                ) ?>

                <?= Yii::t('sitemap', 'Please check this checkbox if you want to include in your sitemap.') ?>

            </label>
        </div>

        <div class="form-group">
            <?php
            $selection = isset($option['post_type'][$postType->id]['priority'])
            && $option['post_type'][$postType->id]['priority']
                ? $option['post_type'][$postType->id]['priority']
                : null;
            ?>
            <?= Html::label(
                Yii::t('sitemap', 'Priority:'),
                'option-option_value-post-types-priority-' . $postType->id,
                ['class' => 'form-label']
            ) ?>

            <?= Html::dropDownList(
                "Option[option_value][post_type][$postType->id][priority]",
                $selection, $priority, [
                    'class' => 'form-control',
                    'id'    => 'option-option_value-post-types-priority-' . $postType->id,
                ]
            ) ?>

        </div>
        <div class="form-group">
            <?php
            $selection = isset($option['post_type'][$postType->id]['changefreq'])
            && $option['post_type'][$postType->id]['changefreq']
                ? $option['post_type'][$postType->id]['changefreq']
                : null;
            ?>
            <?= Html::label(
                Yii::t('sitemap', 'Change Frequency:'),
                'option-option_value-post-types-changefreq-' . $postType->id,
                ['class' => 'form-label']
            ) ?>

            <?= Html::dropDownList(
                "Option[option_value][post_type][$postType->id][changefreq]",
                $selection,
                $changeFreq, [
                    'class' => 'form-control',
                    'id'    => 'option-option_value-post-types-changefreq-' . $postType->id,
                ]
            ) ?>

        </div>
        <br/>
    <?php endforeach ?>

</div>
