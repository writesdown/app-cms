<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* @var $config [] */
/* @var $installed string */
?>
<div class="row">
    <div class="col-sm-6">
        <?= Html::img($config['Thumbnail'], ['class' => 'thumbnail full-width']) ?>

    </div>
    <div class="col-sm-6">
        <table class="table table-striped table-bordered">
            <tbody>

            <?php foreach ($config as $key => $value): ?>
                <?php if ($key !== 'Thumbnail' || $value !== 'Dir') : ?>
                    <tr>
                        <th><?= $key ?></th>
                        <td><?= $value ?></td>
                    </tr>
                <?php endif ?>
            <?php endforeach ?>

            </tbody>
        </table>

        <?php if ($config['Dir'] === $installed): ?>
            <span class="full-width btn-block btn btn-flat btn-info"><?= Yii::t('writesdown', 'Installed') ?></span>
        <?php else: ?>
            <div class="btn-group">
                <?= Html::a(Yii::t('writesdown', 'Install'), ['/theme/install', 'theme' => $config['Dir']], [
                    'class' => 'btn btn-flat btn-primary',
                    'data'  => [
                        'theme'   => $config['Dir'],
                        'confirm' => Yii::t('writesdown', 'Are you wanna install this theme?'),
                        'method'  => 'post',
                    ],
                ]) ?>

            </div>
            <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', [
                '/theme/delete',
                'theme' => $config['Dir'],
            ], [
                'class' => 'btn btn-flat btn-danger pull-right',
                'data'  => [
                    'theme'   => $config['Dir'],
                    'confirm' => Yii::t('writesdown', 'Are you sure wanna to delete this theme permanently?'),
                    'method'  => 'post',
                ],
            ]) ?>
        <?php endif ?>

    </div>
</div>
