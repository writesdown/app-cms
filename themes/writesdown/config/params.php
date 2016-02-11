<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

return [
    'backend' => [
        'menu' => [
            'location' => [
                'primary' => 'Primary',
            ],
        ],
        'postType' => [
            'post' => [
                'meta' => [
                    ['class' => 'themes\writesdown\classes\meta\Meta'],
                ],
                'support' => [],
            ],
            'page' => [
                'meta' => [
                    ['class' => 'themes\writesdown\classes\meta\Meta'],
                ],
                'support' => [],
            ],
        ],
        'widget' => [
            [
                'title' => 'Sidebar',
                'description' => 'Main sidebar that appears on the right.',
                'location' => 'sidebar',
            ],
            [
                'title' => 'Footer Left',
                'description' => 'Appears on the left of footer',
                'location' => 'footer-left',
            ],
            [
                'title' => 'Footer Middle',
                'description' => 'Appears on the middle of footer',
                'location' => 'footer-middle',
            ],
            [
                'title' => 'Footer Right',
                'description' => 'Appears on the right of footer',
                'location' => 'footer-right',
            ],
        ],
    ],
];
