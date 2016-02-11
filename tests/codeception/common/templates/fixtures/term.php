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
    'taxonomy_id' => $faker->numberBetween(1, 2),
    'name' => $faker->name,
    'slug' => $faker->slug,
    'description' => $faker->text,
    'parent' => '0',
    'count' => '0',
];
