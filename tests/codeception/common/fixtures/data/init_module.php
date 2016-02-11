<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

return [
    [
        'name' => 'toolbar',
        'title' => 'Toolbar',
        'config' => '{"frontend":{"class":"modules\\\\toolbar\\\\frontend\\\\Module"}}',
        'status' => '0',
        'directory' => 'toolbar',
        'backend_bootstrap' => '0',
        'frontend_bootstrap' => '1',
        'date' => '2015-09-11 03:14:57',
        'modified' => '2015-09-11 03:14:57',
    ],
    [
        'name' => 'sitemap',
        'title' => 'Sitemap',
        'description' => 'Module for sitemap',
        'config' => '{"backend":{"class":"modules\\\\sitemap\\\\backend\\\\Module"},"frontend":{"class":"modules\\\\sitemap\\\\frontend\\\\Module"}}',
        'status' => '0',
        'directory' => 'sitemap',
        'backend_bootstrap' => '0',
        'frontend_bootstrap' => '1',
        'date' => '2015-09-11 03:38:25',
        'modified' => '2015-09-11 03:38:25',
    ],
    [
        'name' => 'feed',
        'title' => 'RSS Feed',
        'config' => '{"frontend":{"class":"modules\\\\feed\\\\frontend\\\\Module"}}',
        'status' => '0',
        'directory' => 'feed',
        'backend_bootstrap' => '0',
        'frontend_bootstrap' => '0',
        'date' => '2015-09-11 03:38:53',
        'modified' => '2015-09-11 03:38:53',
    ],
];
