<?php
/**
 * @file      main.php
 * @date      9/4/2015
 * @time      4:21 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

return [
    'module_name'   => 'feed',
    'module_title'  => 'RSS Feed',
    'module_config' => [
        'frontend' => [
            'class' => 'modules\feed\frontend\Module'
        ]
    ]
];
