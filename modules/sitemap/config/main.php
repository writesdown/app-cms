<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

return [
    'module_name'        => 'sitemap',
    'module_title'       => 'Site Map',
    'module_description' => 'Module for sitemap',
    'module_config'      => [
        'backend'  => [
            'class' => 'modules\sitemap\backend\Module',
        ],
        'frontend' => [
            'class' => 'modules\sitemap\frontend\Module',
        ],
    ],
    'module_fb'          => 1,
];
