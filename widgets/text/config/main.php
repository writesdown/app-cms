<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

return [
    'widget_title'       => 'Text',
    'widget_config'      => [
        'class' => 'widgets\text\TextWidget',
        'title' => '',
        'text'  => '',
    ],
    'widget_description' => 'Simple widget to show text or HTML.',
    'widget_page'        => __DIR__ . '/../views/option.php'
];
