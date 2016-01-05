<?php
/**
 * @link      http://www.writesdown.com/
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
            ],
        ],
        'postType'  => [
            'post' => [
                'metaBox' => [
                    ['class' => 'themes\writesdown\metabox\MetaBox'],
                ],
                'support' => [],
            ],
            'page' => [
                'metaBox' => [
                    ['class' => 'themes\writesdown\metabox\MetaBox'],
                ],
                'support' => [],
            ],
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
                'location'    => 'footer-left',
            ],
            [
                'title'       => 'Footer Middle',
                'description' => 'Appears on the middle of footer',
                'location'    => 'footer-middle',
            ],
            [
                'title'       => 'Footer Right',
                'description' => 'Appears on the right of footer',
                'location'    => 'footer-right',
            ],
        ],
    ],
    'frontend' => [
    ],
];
