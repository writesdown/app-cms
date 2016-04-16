<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $theme [] */
/* @var $installed string */
?>
<div class="row">
    <div class="col-sm-6">
        <?= Html::img(ArrayHelper::getValue($theme, 'thumbnail', ''), ['class' => 'thumbnail full-width']) ?>

    </div>
    <div class="col-sm-6">
        <table class="table table-striped table-bordered">
            <tbody>

            <?php foreach (ArrayHelper::getValue($theme, 'info', []) as $key => $value): ?>
                <tr>
                    <th><?= $key ?></th>
                    <td><?= $value ?></td>
                </tr>
            <?php endforeach ?>

            </tbody>
        </table>

        <?php if (ArrayHelper::getValue($theme, 'directory') === $installed): ?>
            <span class="full-width btn-block btn btn-flat btn-info"><?= Yii::t('writesdown', 'Installed') ?></span>
        <?php else: ?>
            <div class="btn-group">
                <?= Html::a(
                    Yii::t('writesdown', 'Install'),
                    ['install', 'theme' => ArrayHelper::getValue($theme, 'directory')],
                    [
                        'class' => 'btn btn-flat btn-primary',
                        'data' => [
                            'confirm' => Yii::t('writesdown', 'Are you want to install this theme?'),
                            'method' => 'post',
                        ],
                    ]) ?>

            </div>
            <?= Html::a(
                '<span class="glyphicon glyphicon-trash"></span>',
                ['delete', 'theme' => ArrayHelper::getValue($theme, 'directory')],
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
