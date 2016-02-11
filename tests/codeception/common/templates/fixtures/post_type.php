<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'name' => $faker->name,
    'slug' => $faker->slug,
    'icon' => 'fa fa-file-o',
    'singular_name' => $faker->name,
    'plural_name' => $faker->name,
    'menu_builder' => $faker->numberBetween(0, 1),
    'permission' => 'contributor',
];
