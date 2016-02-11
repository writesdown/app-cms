<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

return [
    [
        'name' => 'sitetitle',
        'value' => 'WritesDown',
        'label' => 'Site Title',
        'group' => 'general',
    ],
    [
        'name' => 'tagline',
        'value' => 'CMS Built with Yii Framework',
        'label' => 'Tagline',
        'group' => 'general',
    ],
    [
        'name' => 'admin_email',
        'value' => 'superadmin@writesdown.com',
        'label' => 'E-mail Address',
        'group' => 'general',
    ],
    [
        'name' => 'allow_signup',
        'value' => '0',
        'label' => 'Membership',
        'group' => 'general',
    ],
    [
        'name' => 'default_role',
        'value' => 'subscriber',
        'label' => 'New User Default Role',
        'group' => 'general',
    ],
    [
        'name' => 'time_zone',
        'value' => 'Asia/Jakarta',
        'label' => 'Timezone',
        'group' => 'general',
    ],
    [
        'name' => 'date_format',
        'value' => 'F d, Y',
        'label' => 'Date Format',
        'group' => 'general',
    ],
    [
        'name' => 'time_format',
        'value' => 'g:i:s a',
        'label' => 'Time Format',
        'group' => 'general',
    ],
    [
        'name' => 'show_on_front',
        'value' => 'posts',
        'label' => 'Front page displays',
        'group' => 'reading',
    ],
    [
        'name' => 'front_post_type',
        'value' => 'all',
        'label' => 'Post type on front page',
        'group' => 'reading',
    ],
    [
        'name' => 'front_page',
        'value' => '',
        'label' => 'Front page',
        'group' => 'reading',
    ],
    [
        'name' => 'posts_page',
        'value' => '',
        'label' => 'Posts page',
        'group' => 'reading',
    ],
    [
        'name' => 'posts_per_page',
        'value' => '10',
        'label' => 'Posts Per Page',
        'group' => 'reading',
    ],
    [
        'name' => 'posts_per_rss',
        'value' => '10',
        'label' => 'Posts Per RSS',
        'group' => 'reading',
    ],
    [
        'name' => 'rss_use_excerpt',
        'value' => '0',
        'label' => 'For each article in a feed, show ',
        'group' => 'reading',
    ],
    [
        'name' => 'disable_site_indexing',
        'value' => '0',
        'label' => 'Search Engine Visibility ',
        'group' => 'reading',
    ],
    [
        'name' => 'default_comment_status',
        'value' => 'open',
        'label' => 'Default article settings',
        'group' => 'discussion',
    ],
    [
        'name' => 'require_name_email',
        'value' => '1',
        'label' => 'Comment author must fill out name and e-mail ',
        'group' => 'discussion',
    ],
    [
        'name' => 'comment_registration',
        'value' => '0',
        'label' => 'Users must be registered and logged in to comment ',
        'group' => 'discussion',
    ],
    [
        'name' => 'close_comments_for_old_posts',
        'value' => '0',
        'label' => 'Automatically close comments on articles older',
        'group' => 'discussion',
    ],
    [
        'name' => 'close_comments_days_old',
        'value' => '14',
        'label' => 'Days when the comments of the article is closed',
        'group' => 'discussion',
    ],
    [
        'name' => 'thread_comments',
        'value' => '1',
        'label' => 'Enable threaded (nested) comments',
        'group' => 'discussion',
    ],
    [
        'name' => 'page_comments',
        'value' => '5',
        'label' => 'Break comments into pages with',
        'group' => 'discussion',
    ],
    [
        'name' => 'thread_comments_depth',
        'value' => '5',
        'label' => 'Thread Comments Depth',
        'group' => 'discussion',
    ],
    [
        'name' => 'comments_per_page',
        'value' => '10',
        'label' => 'Top level comments per page',
        'group' => 'discussion',
    ],
    [
        'name' => 'default_comments_page',
        'value' => 'newest',
        'label' => 'page displayed by default\nComments should be displayed with the',
        'group' => 'discussion',
    ],
    [
        'name' => 'comments_notify',
        'value' => '1',
        'label' => 'Notify when anyone posts a comment',
        'group' => 'discussion',
    ],
    [
        'name' => 'moderation_notify',
        'value' => '0',
        'label' => 'Notify when a comment is held for moderation',
        'group' => 'discussion',
    ],
    [
        'name' => 'comment_moderation',
        'value' => '1',
        'label' => 'Comment must be manually approved',
        'group' => 'discussion',
    ],
    [
        'name' => 'comment_whitelist',
        'value' => '0',
        'label' => 'Comment author must have a previously approved comment',
        'group' => 'discussion',
    ],
    [
        'name' => 'show_avatars',
        'value' => '1',
        'label' => 'Show Avatars',
        'group' => 'discussion',
    ],
    [
        'name' => 'avatar_rating',
        'value' => 'G',
        'label' => 'Maximum Rating',
        'group' => 'discussion',
    ],
    [
        'name' => 'avatar_default',
        'value' => 'mystery',
        'label' => 'Default Avatar',
        'group' => 'discussion',
    ],
    [
        'name' => 'comment_order',
        'value' => 'asc',
        'label' => 'comments at the top of each page',
        'group' => 'discussion',
    ],
    [
        'name' => 'thumbnail_width',
        'value' => '150',
        'label' => 'Width',
        'group' => 'media',
    ],
    [
        'name' => 'thumbnail_height',
        'value' => '150',
        'label' => 'Height',
        'group' => 'media',
    ],
    [
        'name' => 'thumbnail_crop',
        'value' => '1',
        'label' => 'Crop thumbnail to exact dimensions',
        'group' => 'media',
    ],
    [
        'name' => 'medium_width',
        'value' => '300',
        'label' => 'Max Width',
        'group' => 'media',
    ],
    [
        'name' => 'medium_height',
        'value' => '300',
        'label' => 'Max Height',
        'group' => 'media',
    ],
    [
        'name' => 'large_width',
        'value' => '1024',
        'label' => 'Max Width',
        'group' => 'media',
    ],
    [
        'name' => 'large_height',
        'value' => '1024',
        'label' => 'Max Height',
        'group' => 'media',
    ],
    [
        'name' => 'uploads_yearmonth_based',
        'value' => '1',
        'label' => 'Organize my uploads into month- and year-based folders',
        'group' => 'media',
    ],
    [
        'name' => 'uploads_username_based',
        'value' => '0',
        'label' => 'Organize my uploads into username-based folders',
        'group' => 'media',
    ],
    [
        'name' => 'theme',
        'value' => 'default',
        'label' => 'Theme',
        'group' => '',
    ],
];
