<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

return [
    [
        'name' => 'post',
        'slug' => 'post',
        'icon' => 'fa fa-thumb-tack',
        'singular_name' => 'Post',
        'plural_name' => 'Posts',
        'menu_builder' => '0',
        'permission' => 'contributor',
    ],
    [
        'name' => 'page',
        'slug' => 'pages',
        'icon' => 'fa fa-file-o',
        'singular_name' => 'Page',
        'plural_name' => 'Pages',
        'menu_builder' => '1',
        'permission' => 'editor',
    ],
];
