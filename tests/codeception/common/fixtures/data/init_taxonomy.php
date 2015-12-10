<?php
/**
 * @file      init_taxonomy.php
 * @date      12/6/2015
 * @time      4:13 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */

return [
    [
        'taxonomy_name'         => 'category',
        'taxonomy_slug'         => 'category',
        'taxonomy_hierarchical' => '1',
        'taxonomy_sn'           => 'Category',
        'taxonomy_pn'           => 'Categories',
        'taxonomy_smb'          => '1',
    ],
    [
        'taxonomy_name'         => 'tag',
        'taxonomy_slug'         => 'tag',
        'taxonomy_hierarchical' => '0',
        'taxonomy_sn'           => 'Tag',
        'taxonomy_pn'           => 'Tags',
        'taxonomy_smb'          => '0',
    ]
];