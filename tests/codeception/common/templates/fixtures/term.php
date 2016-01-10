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
    'taxonomy_id'      => $faker->numberBetween(1,2),
    'term_name'        => $faker->name,
    'term_slug'        => $faker->slug,
    'term_description' => $faker->text,
    'term_parent'      => '0',
    'term_count'       => '0',
];
