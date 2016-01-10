<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

return [
    ['parent' => 'superadmin', 'child' => 'administrator'],
    ['parent' => 'editor', 'child' => 'author'],
    ['parent' => 'author', 'child' => 'contributor'],
    ['parent' => 'administrator', 'child' => 'editor'],
    ['parent' => 'contributor', 'child' => 'subscriber'],
];
