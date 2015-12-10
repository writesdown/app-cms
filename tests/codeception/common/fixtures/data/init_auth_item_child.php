<?php
/**
 * @file      init_auth_item_child.php
 * @date      12/6/2015
 * @time      6:34 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */

return [
    [
        'parent' => 'superadmin',
        'child' => 'administrator',
    ],
    [
        'parent' => 'editor',
        'child' => 'author',
    ],
    [
        'parent' => 'author',
        'child' => 'contributor',
    ],
    [
        'parent' => 'administrator',
        'child' => 'editor',
    ],
    [
        'parent' => 'contributor',
        'child' => 'subscriber',
    ],
];