<?php
/**
 * @file      _theme-detail.php.
 * @date      6/4/2015
 * @time      12:02 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* MODEL */
use common\models\Option;

/* @var $themeConfig [] */
/* @var $installed string */

?>
<div class="row">
    <div class="col-sm-6">
        <?= Html::img($themeConfig['Thumbnail'], ['class' => 'thumbnail full-width']); ?>
    </div>
    <div class="col-sm-6">
        <table class="table table-striped table-bordered">
            <tbody>
            <?php foreach ($themeConfig as $k => $d) { ?>
                <?php if ($k === 'Thumbnail' || $k === 'Dir') {
                    continue;
                } ?>
                <tr>
                    <th><?= $k ?></th>
                    <td><?= $d ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php
        if ($themeConfig['Dir'] === $installed) {
            echo Html::tag('span', Yii::t('writesdown', 'Installed'), ['class' => 'full-width btn-block btn btn-flat btn-info']);
        } else {
            echo Html::beginTag('div', ['class' => 'btn-group']);
            echo Html::a(Yii::t('writesdown', 'Install'), ['/theme/install', 'theme' => $themeConfig['Dir']], [
                'class' => 'btn btn-flat btn-primary',
                'data' => [
                    'theme' => $themeConfig['Dir'],
                    'confirm' => Yii::t('writesdown', 'Are you wanna install this theme?'),
                    'method' => 'post',
                ]
            ]);
            echo Html::endTag('div');
            echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/theme/delete', 'theme' => $themeConfig['Dir']], [
                'class' => 'btn btn-flat btn-danger pull-right',
                'data' => [
                    'theme' => $themeConfig['Dir'],
                    'confirm' => Yii::t('writesdown', 'Are you sure wanna to delete this theme permanently?'),
                    'method' => 'post',
                ]
            ]);
        }
        ?>
    </div>
</div>