<?php

use yii\db\Schema;

/**
 * Class m000000_000001_option.
 * Migration for table option.
 *
 * @author Agiel K. Saputra
 */
class m000000_000001_option extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%option}}', [
            'id'           => Schema::TYPE_PK,
            'option_name'  => Schema::TYPE_STRING . '(64) NOT NULL',
            'option_value' => Schema::TYPE_TEXT . ' NOT NULL',
            'option_label' => Schema::TYPE_STRING . '(64)',
            'option_group' => Schema::TYPE_STRING . '(64)',
        ], $tableOptions);

        $this->batchInsert('{{%option}}', ['option_name', 'option_value', 'option_label', 'option_group'], [
            ['sitetitle', 'WritesDown', 'Site Title', 'general'],
            ['tagline', 'CMS Built with Yii Framework', 'Tagline', 'general'],
            ['admin_email', 'superadmin@writesdown.com', 'E-mail Address', 'general'],
            ['allow_signup', '0', 'Membership', 'general'],
            ['default_role', 'subscriber', 'New User Default Role', 'general'],
            ['time_zone', 'Asia/Jakarta', 'Timezone', 'general'],
            ['date_format', 'F d, Y', 'Date Format', 'general'],
            ['time_format', 'g:i:s a', 'Time Format', 'general'],
            /* READING */
            ['show_on_front', 'posts', 'Front page displays', 'reading'],
            ['front_post_type', 'all', 'Post type on front page', 'reading'],
            ['front_page', '', 'Front page', 'reading'],
            ['posts_page', '', 'Posts page', 'reading'],
            ['posts_per_page', '10', 'Posts Per Page', 'reading'],
            ['posts_per_rss', '10', 'Posts Per RSS', 'reading'],
            ['rss_use_excerpt', '0', 'For each article in a feed, show ', 'reading'],
            ['disable_site_indexing', '0', 'Search Engine Visibility ', 'reading'],
            /* DISCUSSION */
            ['default_comment_status', 'open', 'Default article settings', 'discussion'],
            ['require_name_email', '1', 'Comment author must fill out name and e-mail ', 'discussion'],
            ['comment_registration', '0', 'Users must be registered and logged in to comment ', 'discussion'],
            ['close_comments_for_old_posts', '0', 'Automatically close comments on articles older', 'discussion'],
            ['close_comments_days_old', '14', 'Days when the comments of the article is closed', 'discussion'],
            ['thread_comments', '1', 'Enable threaded (nested) comments', 'discussion'],
            ['page_comments', '5', 'Break comments into pages with', 'discussion'],
            ['thread_comments_depth', '5', 'Thread Comments Depth', 'discussion'],
            ['comments_per_page', '10', 'Top level comments per page', 'discussion'],
            ['default_comments_page', 'newest', 'page displayed by default\nComments should be displayed with the', 'discussion'],
            ['comments_notify', '1', 'Notify when anyone posts a comment', 'discussion'],
            ['moderation_notify', '0', 'Notify when a comment is held for moderation', 'discussion'],
            ['comment_moderation', '1', 'Comment must be manually approved', 'discussion'],
            ['comment_whitelist', '0', 'Comment author must have a previously approved comment', 'discussion'],
            ['show_avatars', '1', 'Show Avatars', 'discussion'],
            ['avatar_rating', 'G', 'Maximum Rating', 'discussion'],
            ['avatar_default', 'mystery', 'Default Avatar', 'discussion'],
            ['comment_order', 'asc', 'comments at the top of each page', 'discussion'],
            /* MEDIA */
            ['thumbnail_width', '150', 'Width', 'media'],
            ['thumbnail_height', '150', 'Height', 'media'],
            ['thumbnail_crop', '1', 'Crop thumbnail to exact dimensions', 'media'],
            ['medium_width', '300', 'Max Width', 'media'],
            ['medium_height', '300', 'Max Height', 'media'],
            ['large_width', '1024', 'Max Width', 'media'],
            ['large_height', '1024', 'Max Height', 'media'],
            ['uploads_yearmonth_based', '1', 'Organize my uploads into month- and year-based folders', 'media'],
            ['uploads_username_based', '0', 'Organize my uploads into username-based folders', 'media'],
            /* APPEARANCE */
            ['theme', 'default', 'Theme', 'appearance']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%option}}');
    }
}