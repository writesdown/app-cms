<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

return [
    'name'               => 'toolbar',
    'title'              => 'Toolbar',
    'configs'            => [
        'frontend' => [
            'class' => 'modules\toolbar\frontend\Module',
        ],
    ],
    'frontend_bootstrap' => 1,
];
