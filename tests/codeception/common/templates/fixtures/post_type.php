<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'post_type_name'       => $faker->name,
    'post_type_slug'       => $faker->slug,
    'post_type_icon'       => 'fa fa-file-o',
    'post_type_sn'         => $faker->name,
    'post_type_pn'         => $faker->name,
    'post_type_smb'        => $faker->numberBetween(0, 1),
    'post_type_permission' => 'contributor',
];
