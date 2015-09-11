<?php
/**
 * @file      main.php
 * @date      9/2/2015
 * @time      1:09 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

return [
    'module_name'   => 'toolbar',
    'module_title'  => 'Toolbar',
    'module_config' => [
        'frontend' => [
            'class' => 'modules\toolbar\frontend\Module'
        ]
    ],
    'module_fb'     => 1,
    'module_active' => 1,
];