<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      10:28 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Option;

/* @var $title string */
/* @var $link string */
/* @var $description */
/* @var $lastBuildDate \DateTime */
/* @var $language string */
/* @var $generator string */
/* @var $postTypes common\models\PostType[] */
/* @var $post common\models\Post */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/">

    <channel>
        <title><?= $title ?></title>
        <atom:link href="<?= $link ?>" rel="self" type="application/rss+xml"/>
        <link><?= $link ?></link>
        <description><?= $description ?></description>
        <lastBuildDate><?= $lastBuildDate->format('r'); ?></lastBuildDate>
        <language><?= Yii::$app->language; ?></language>
        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>
        <generator><?= $generator; ?></generator>
        <?php foreach ($postTypes as $postType) {
            foreach ($postType->getPosts()->andWhere(['post_status' => 'publish'])->limit(Option::get('posts_per_rss'))->all() as $post) {
                ?>
                <item>
                    <title><?= $post->post_title; ?></title>
                    <link><![CDATA[<?= $post->url; ?>]]></link>
                    <comments><![CDATA[<?= $post->url; ?>#comments]]></comments>
                    <pubDate>
                        <?php
                        $postDate = new DateTime($post->post_date, new DateTimeZone(Option::get('time_zone')));
                        echo $postDate->format('r');
                        ?>
                    </pubDate>
                    <dc:creator><![CDATA[<?= $post->postAuthor->display_name ?>]]></dc:creator>
                    <?php foreach ($post->terms as $term){ ?>
                    <category><![CDATA[<?= $term->term_name; ?>]]></category>
                    <?php } ?>
                    <guid isPermaLink="false">
                        <![CDATA[<?= Yii::$app->urlManager->createAbsoluteUrl(['post/view', 'id' => $post->id]); ?>]]>
                    </guid>
                    <description><![CDATA[<?= $post->post_excerpt; ?>]]></description>
                    <?php if (Option::get('rss_use_excerpt')) { ?>
                        <content:encoded><![CDATA[<?= $post->post_content; ?>]]></content:encoded>
                    <?php } ?>
                    <wfw:commentRss><![CDATA[<?= $post->url; ?>]]></wfw:commentRss>
                    <slash:comments><?= $post->post_comment_count; ?></slash:comments>
                </item>
            <?php
            }
        } ?>
    </channel>
</rss>
