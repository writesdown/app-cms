<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\bootstrap\Nav;

echo Nav::widget([
    'items'        => [
        [
            'label' => '<i class="fa fa-list"></i> <span>' . Yii::t('writesdown', 'Available Themes') . '</span>',
            'url'   => ['/theme/index'],
        ],
        [
            'label' => '<i class="fa fa-upload"></i> <span>' . Yii::t('writesdown', 'Add New Theme') . '</span>',
            'url'   => ['/theme/upload'],
        ],
    ],
    'encodeLabels' => false,
    'options'      => ['class' => 'nav-tabs nav-theme', 'id' => 'nav-theme'],
]);
