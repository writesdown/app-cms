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
    'taxonomy_name'         => $faker->name,
    'taxonomy_slug'         => $faker->slug,
    'taxonomy_hierarchical' => $faker->numberBetween(0, 1),
    'taxonomy_sn'           => $faker->name,
    'taxonomy_pn'           => $faker->name,
    'taxonomy_smb'          => $faker->numberBetween(0, 1),
];
