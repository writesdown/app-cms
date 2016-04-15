<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

return [
    'name' => 'sitemap',
    'title' => 'Sitemap',
    'description' => 'Module for sitemap',
    'config' => [
        'backend' => [
            'class' => 'modules\sitemap\backend\Module',
        ],
        'frontend' => [
            'class' => 'modules\sitemap\frontend\Module',
        ],
    ],
    'frontend_bootstrap' => 1,
];
