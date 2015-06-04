<?php
/**
 * @file      _theme-thumbnail.php.
 * @date      6/4/2015
 * @time      12:03 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $i int */

?>
<div class="col-xs-6 col-sm-4">
    <div class="thumbnail theme-thumbnail">
        <?= Html::img(Yii::getAlias('@web/img/themes.png'), [
            'class' => 'theme-thumbnail'
        ]); ?>
        <h3 class="theme-name">Sample Theme WritesDown <?= $i; ?></h3>
        <?php
        if ($i == 0) {
            echo Html::tag('span', Yii::t('writesdown', 'Installed'), ['class' => 'full-width btn-block btn btn-flat btn-info']);
        } else {
            echo Html::beginTag('div', ['class' => 'btn-group']);
            echo Html::a(Yii::t('writesdown', 'Detail'), ['/theme/detail', 'theme' => $i], [
                'class' => 'btn-detail btn btn-flat btn-success',
                'data'  => [
                    'theme'       => $i,
                    'ajax-detail' => Url::to(['theme/ajax-detail'])
                ]
            ]);
            echo Html::a(Yii::t('writesdown', 'Install'), ['/theme/install', 'theme' => $i], [
                'class' => 'btn btn-flat btn-primary',
                'data'  => [
                    'theme'   => $i,
                    'confirm' => Yii::t('writesdown', 'Are you wanna install this theme?'),
                    'method'  => 'post',
                ]
            ]);
            echo Html::endTag('div');
            echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/theme/delete', 'theme' => $i], [
                'class' => 'btn btn-flat btn-danger pull-right',
                'data'  => [
                    'theme'   => $i,
                    'confirm' => Yii::t('writesdown', 'Are you sure wanna to delete this theme permanently?'),
                    'method'  => 'post',
                ]
            ]);
        }
        ?>
    </div>
</div>