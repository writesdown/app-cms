<?php
/**
 * @file      taxonomy.php
 * @date      12/6/2015
 * @time      5:13 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
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