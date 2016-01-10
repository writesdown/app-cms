<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $option [] */
/* @var $this yii\web\View */
/* @var $postTypes common\models\PostType[] */
/* @var $taxonomies common\models\Taxonomy[] */

$changeFreq = [
    'always'  => Yii::t('sitemap', 'Always'),
    'hourly'  => Yii::t('sitemap', 'Hourly'),
    'daily'   => Yii::t('sitemap', 'Daily'),
    'weekly'  => Yii::t('sitemap', 'Weekly'),
    'monthly' => Yii::t('sitemap', 'Monthly'),
    'yearly'  => Yii::t('sitemap', 'Yearly'),
    'never'   => Yii::t('sitemap', 'Never'),
];

$priority = [
    '0.1' => '10%',
    '0.2' => '20%',
    '0.3' => '30%',
    '0.4' => '40%',
    '0.5' => '50%',
    '0.6' => '60%',
    '0.7' => '70%',
    '0.8' => '80%',
    '0.9' => '90%',
    '1.0' => '100%',
]
?>
<div class="sitemap-default-index-form">
    <?php $form = ActiveForm::begin() ?>

    <div class="nav-tabs-custom">
        <?= Nav::widget([
            'encodeLabels' => false,
            'options'      => ['class' => 'nav-tabs'],
            'items'        => [
                [
                    'label'       => '<i class="fa fa-cog"></i> <span class="hidden-xs">'
                        . Yii::t('sitemap', 'Basic') . '</span>',
                    'url'         => '#sitemap-basic-option',
                    'options'     => ['class' => 'active'],
                    'linkOptions' => ['data-toggle' => 'tab'],
                ],
                [
                    'label'       => '<i class="fa fa-home"></i> <span class="hidden-xs">'
                        . Yii::t('sitemap', 'Home') . '</span>',
                    'url'         => '#sitemap-home-option',
                    'linkOptions' => ['data-toggle' => 'tab'],
                ],
                [
                    'label'       => '<i class="fa fa-files-o"></i> <span class="hidden-xs">'
                        . Yii::t('sitemap', 'Post Types') . '</span>',
                    'url'         => '#sitemap-post-types-option',
                    'linkOptions' => ['data-toggle' => 'tab'],
                ],
                [
                    'label'       => '<i class="fa fa-tags"></i> <span class="hidden-xs">'
                        . Yii::t('sitemap', 'Taxonomies') . '</span>',
                    'url'         => '#sitemap-taxonomies-option',
                    'linkOptions' => ['data-toggle' => 'tab'],
                ],
                [
                    'label'       => '<i class="fa fa-picture-o"></i> <span class="hidden-xs">'
                        . Yii::t('sitemap', 'Media') . '</span>',
                    'url'         => '#sitemap-media-option',
                    'linkOptions' => ['data-toggle' => 'tab'],
                ],
            ],
        ]) ?>
        <div class="tab-content">
            <?= $this->render('_basic', ['option' => $option, 'priority' => $priority, 'changeFreq' => $changeFreq]) ?>
            <?= $this->render('_home', ['option' => $option, 'priority' => $priority, 'changeFreq' => $changeFreq]) ?>
            <?= $this->render('_post-types', [
                'option'     => $option,
                'priority'   => $priority,
                'changeFreq' => $changeFreq,
                'postTypes'  => $postTypes,
            ]) ?>
            <?= $this->render('_taxonomies', [
                'option'     => $option,
                'priority'   => $priority,
                'changeFreq' => $changeFreq,
                'taxonomies' => $taxonomies,
            ]) ?>
            <?= $this->render('_media', ['option' => $option, 'priority' => $priority, 'changeFreq' => $changeFreq,]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('sitemap', 'Update'), ['class' => 'btn btn-flat btn-primary']) ?>

    </div>
    <?php ActiveForm::end() ?>

</div>
