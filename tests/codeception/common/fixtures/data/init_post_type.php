<?php
/**
 * @file      init_post_type.php
 * @date      12/6/2015
 * @time      4:18 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */

return [
    [
        'post_type_name'       => 'post',
        'post_type_slug'       => 'post',
        'post_type_icon'       => 'fa fa-thumb-tack',
        'post_type_sn'         => 'Post',
        'post_type_pn'         => 'Posts',
        'post_type_smb'        => '0',
        'post_type_permission' => 'contributor',
    ],
    [
        'post_type_name'       => 'page',
        'post_type_slug'       => 'pages',
        'post_type_icon'       => 'fa fa-file-o',
        'post_type_sn'         => 'Page',
        'post_type_pn'         => 'Pages',
        'post_type_smb'        => '1',
        'post_type_permission' => 'editor',
    ]
];