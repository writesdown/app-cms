<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $i int */
/* @var $theme [] */
/* @var $installed string */
?>
<div class="col-xs-6 col-sm-4">
    <div class="thumbnail theme-thumbnail bg-gray">
        <?= Html::img($theme['thumbnail'], ['class' => 'theme-thumbnail']) ?>
        <h3 class="theme-name"><?= $theme['info']['Name'] ?></h3>

        <?php if ($theme['directory'] === $installed): ?>
            <span class="full-width btn-block btn btn-flat btn-info"><?= Yii::t('writesdown', 'Installed') ?></span>
        <?php else: ?>
            <div class="btn-group">
                <?= Html::a(Yii::t('writesdown', 'Detail'), ['detail', 'theme' => $theme['directory']], [
                    'class' => 'btn-detail btn btn-flat btn-success',
                    'data' => [
                        'theme' => $theme['directory'],
                        'ajax-detail' => Url::to(['ajax-detail']),
                    ],
                ]) ?>

                <?= Html::a(Yii::t('writesdown', 'Install'), ['install', 'theme' => $theme['directory']], [
                    'class' => 'btn btn-flat btn-primary',
                    'data' => [
                        'theme' => $theme['directory'],
                        'confirm' => Yii::t('writesdown', 'Are you wanna install this theme?'),
                        'method' => 'post',
                    ],
                ]) ?>

            </div>
            <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', [
                'delete',
                'theme' => $theme['directory'],
            ], [
                'class' => 'btn btn-flat btn-danger pull-right',
                'data' => [
                    'theme' => $theme['directory'],
                    'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif ?>

    </div>
</div>
