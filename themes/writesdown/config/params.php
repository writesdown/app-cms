<?php
/**
 * @file      params.php
 * @date      8/19/2015
 * @time      2:34 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */
return [
    'backend'  => [
        'bodyClass' => 'skin-blue sidebar-mini',
        'menu'      => [
            'location' => [
                'primary' => 'Primary',
            ]
        ],
        'metaBox'   => [
            'post' => [
                ['class' => 'themes\writesdown\metabox\MetaBox'],
            ],
            'page' => [
                ['class' => 'themes\writesdown\metabox\MetaBox'],
            ]
        ],
        'widget'    => [
            [
                'title'       => 'Sidebar',
                'description' => 'Main sidebar that appears on the right.',
                'location'    => 'sidebar',
            ],
            [
                'title'       => 'Footer Left',
                'description' => 'Appears on the left of footer',
                'location'    => 'footer-left'
            ],
            [
                'title'       => 'Footer Middle',
                'description' => 'Appears on the middle of footer',
                'location'    => 'footer-middle'
            ],
            [
                'title'       => 'Footer Right',
                'description' => 'Appears on the right of footer',
                'location'    => 'footer-right'
            ]
        ]
    ],
    'frontend' => [
    ]
];
