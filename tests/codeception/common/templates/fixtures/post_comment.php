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
    'post_comment_id'      => '1',
    'comment_author'       => $faker->name,
    'comment_author_email' => $faker->email,
    'comment_author_url'   => $faker->url,
    'comment_author_ip'    => $faker->ipv4,
    'comment_date'         => $faker->date('Y-m-d H:i:s'),
    'comment_content'      => $faker->text,
    'comment_approved'     => 'approved',
    'comment_agent'        => $faker->userAgent,
    'comment_parent'       => '0',
];
