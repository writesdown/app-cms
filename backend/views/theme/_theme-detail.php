<?php
/**
 * @file    _theme-detail.php.
 * @date    6/4/2015
 * @time    12:02 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* @var $detail [] */

?>
<div class="row">
    <div class="col-sm-6">
        <?= Html::img($detail['thumbnail'], ['class' => 'thumbnail full-width']); ?>
    </div>
    <div class="col-sm-6">
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <th><?= Yii::t('writesdown', 'Name'); ?></th>
                <td><?= $detail['name']; ?></td>
            </tr>
            <tr>
                <th><?= Yii::t('writesdown', 'Author'); ?></th>
                <td><?= $detail['author'] ?></td>
            </tr>
            <tr>
                <th><?= Yii::t('writesdown', 'Version'); ?></th>
                <td><?= $detail['version'] ?></td>
            </tr>
            <tr>
                <th><?= Yii::t('writesdown', 'Description'); ?></th>
                <td><?= $detail['description'] ?></td>
            </tr>
            <tr>
                <th><?= Yii::t('writesdown', 'Tags'); ?></th>
                <td><?= $detail['tags']; ?></td>
            </tr>
            </tbody>
        </table>
        <?php
        echo Html::beginTag('div', ['class'=>'btn-group']);
        echo Html::a(Yii::t('writesdown', 'Install'), ['/theme/install', 'theme' => $detail['name']], [
            'class' => 'btn btn-flat btn-primary',
            'data'  => [
                'theme'     => $detail['name'],
                'confirm'   => Yii::t('writesdown', 'Are you wanna install this theme?'),
                'method'    => 'post',
            ]
        ]);
        echo Html::endTag('div');
        echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/theme/delete', 'theme' => $detail['name']], [
            'class' => 'btn btn-flat btn-danger pull-right',
            'data'  => [
                'theme'     => $detail['name'],
                'confirm'   => Yii::t('writesdown', 'Are you sure wanna to delete this theme permanently?'),
                'method'    => 'post',
            ]
        ]);
        ?>
    </div>
</div>