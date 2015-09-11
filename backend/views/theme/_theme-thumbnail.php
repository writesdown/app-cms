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
/* @var $theme [] */
/* @var $installed string */
?>
<div class="col-xs-6 col-sm-4">
    <div class="thumbnail theme-thumbnail bg-gray">
        <?= Html::img($theme['Thumbnail'], [
            'class' => 'theme-thumbnail'
        ]); ?>
        <h3 class="theme-name"><?= $theme['Name']; ?></h3>
        <?php
        if ($theme['Dir'] === $installed) {
            echo Html::tag('span', Yii::t('writesdown', 'Installed'), ['class' => 'full-width btn-block btn btn-flat btn-info']);
        }else{
            echo Html::beginTag('div', ['class' => 'btn-group']);
            echo Html::a(Yii::t('writesdown', 'Detail'), ['/theme/detail', 'theme' => $theme['Dir']], [
                'class' => 'btn-detail btn btn-flat btn-success',
                'data'  => [
                    'theme'       => $theme['Dir'],
                    'ajax-detail' => Url::to(['theme/ajax-detail'])
                ]
            ]);
            echo Html::a(Yii::t('writesdown', 'Install'), ['/theme/install', 'theme' => $theme['Dir']], [
                'class' => 'btn btn-flat btn-primary',
                'data'  => [
                    'theme'   => $theme['Dir'],
                    'confirm' => Yii::t('writesdown', 'Are you wanna install this theme?'),
                    'method'  => 'post',
                ]
            ]);
            echo Html::endTag('div');
            echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/theme/delete', 'theme' => $theme['Dir']], [
                'class' => 'btn btn-flat btn-danger pull-right',
                'data'  => [
                    'theme'   => $theme['Dir'],
                    'confirm' => Yii::t('writesdown', 'Are you sure wanna to delete this theme permanently?'),
                    'method'  => 'post',
                ]
            ]);
        }
        ?>
    </div>
</div>