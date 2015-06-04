<?php
/**
 * @file      _navigation.php.
 * @date      6/4/2015
 * @time      12:02 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\bootstrap\Nav;
?>

<?= Nav::widget([
    'items'        => [
        [
            'label'       => '<i class="fa fa-check"></i> <span>' . Yii::t('writesdown', 'Available Theme') . '</span>',
            'url'         => ['/theme/index'],
        ],
        [
            'label'       => '<i class="fa fa-plus"></i> <span>' . Yii::t('writesdown', 'Add New Theme') . '</span>',
            'url'         => ['/theme/upload'],
        ],
    ],
    'encodeLabels' => false,
    'options'      => [
        'class' => 'nav-tabs nav-theme',
        'id'    => 'nav-theme'
    ],
]); ?>