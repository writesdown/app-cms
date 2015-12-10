<?php
/**
 * @file      media_comment.php
 * @date      12/6/2015
 * @time      5:35 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'comment_media_id'     => '1',
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
