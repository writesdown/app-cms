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
    'post_author'         => $faker->numberBetween(1, 6),
    'post_type'           => $faker->numberBetween(1, 2),
    'post_title'          => $faker->title,
    'post_excerpt'        => $faker->text,
    'post_content'        => $faker->text,
    'post_date'           => $faker->date('Y-m-d H:i:s'),
    'post_modified'       => $faker->date('Y-m-d H:i:s'),
    'post_status'         => 'publish',
    'post_slug'           => $faker->slug,
    'post_comment_status' => 'open',
    'post_comment_count'  => '0',
];
