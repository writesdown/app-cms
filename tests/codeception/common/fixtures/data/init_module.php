<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

return [
    [
        'module_name'     => 'toolbar',
        'module_title'    => 'Toolbar',
        'module_config'   => \yii\helpers\Json::encode([
            'frontend' => ['class' => 'modules\toolbar\frontend\Module'],
        ]),
        'module_status'   => '0',
        'module_dir'      => 'toolbar',
        'module_bb'       => '0',
        'module_fb'       => '1',
        'module_date'     => '2015-09-11 03:14:57',
        'module_modified' => '2015-09-11 03:14:57',
    ],
    [
        'module_name'        => 'sitemap',
        'module_title'       => 'Sitemap',
        'module_description' => 'Module for sitemap',
        'module_config'      => \yii\helpers\Json::encode([
            'backend'  => ['class' => 'modules\sitemap\backend\Module'],
            'frontend' => ['class' => 'modules\sitemap\frontend\Module'],
        ]),
        'module_status'      => '0',
        'module_dir'         => 'sitemap',
        'module_bb'          => '0',
        'module_fb'          => '1',
        'module_date'        => '2015-09-11 03:38:25',
        'module_modified'    => '2015-09-11 03:38:25',
    ],
    [
        'module_name'     => 'feed',
        'module_title'    => 'RSS Feed',
        'module_config'   => \yii\helpers\Json::encode([
            'frontend' => ['class' => 'modules\feed\frontend\Module'],
        ]),
        'module_status'   => '0',
        'module_dir'      => 'feed',
        'module_bb'       => '0',
        'module_fb'       => '0',
        'module_date'     => '2015-09-11 03:38:53',
        'module_modified' => '2015-09-11 03:38:53',
    ],
];