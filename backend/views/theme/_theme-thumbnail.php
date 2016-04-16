<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $i int */
/* @var $theme [] */
/* @var $installed string */
?>
<div class="col-xs-6 col-sm-4">
    <div class="thumbnail theme-thumbnail bg-gray">
        <?= Html::img(ArrayHelper::getValue($theme, 'thumbnail'), ['class' => 'theme-thumbnail']) ?>
        <h3 class="theme-name"><?= ArrayHelper::getValue($theme, 'info.Name') ?></h3>

        <?php if (ArrayHelper::getValue($theme, 'directory') === $installed): ?>
            <span class="full-width btn-block btn btn-flat btn-info"><?= Yii::t('writesdown', 'Installed') ?></span>
        <?php else: ?>
            <div class="btn-group">
                <?= Html::a(
                    Yii::t('writesdown', 'Detail'),
                    ['detail', 'theme' => ArrayHelper::getValue($theme, 'directory')],
                    [
                        'class' => 'btn-detail btn btn-flat btn-success',
                        'data' => [
                            'ajax-detail' => Url::to([
                                'ajax-detail',
                                'theme' => ArrayHelper::getValue($theme, 'directory'),
                            ]),
                        ],
                    ]
                ) ?>

                <?= Html::a(
                    Yii::t('writesdown', 'Install'),
                    ['install', 'theme' => ArrayHelper::getValue($theme, 'directory')],
                    [
                        'class' => 'btn btn-flat btn-primary',
                        'data' => [
                            'confirm' => Yii::t('writesdown', 'Are you wanna install this theme?'),
                            'method' => 'post',
                        ],
                    ]
                ) ?>

            </div>
            <?= Html::a(
                '<span class="glyphicon glyphicon-trash"></span>',
                ['delete', 'theme' => $theme['directory']],
                [
                    'class' => 'btn btn-flat btn-danger pull-right',
                    'data' => [
                        'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]
            ) ?>
        <?php endif ?>

    </div>
</div>
