<?php
/**
 * @file      search.php
 * @date      8/23/2015
 * @time      9:04 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $s string */
/* @var $post common\models\Post */
/* @var $image common\models\Media */
/* @var $pages yii\data\Pagination */

$this->title = Yii::t('writesdown', 'Search result: {s}', ['s' => $s]);
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
    'name'    => 'robots',
    'content' => 'noindex, nofollow'
]);
?>

<div class="archive post-index">
    <header id="archive-header" class="archive-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </header>
    <?php if ($posts): ?>
        <?php foreach ($posts as $post) : ?>
            <article class="hentry">
                <header class="entry-header">
                    <h2 class="entry-title"><?= Html::a($post->post_title, $post->url); ?></h2>
                    <?php
                    $updated = new \DateTime($post->post_modified, new DateTimeZone(Yii::$app->timeZone));
                    ?>
                    <div class="entry-meta">
                        <span class="entry-date">
                            <a rel="bookmark" href="<?= $post->url; ?>">
                                <time datetime="<?= $updated->format('r'); ?>" class="entry-date">
                                    <?= Yii::$app->formatter->asDate($post->post_date); ?>
                                </time>
                            </a>
                        </span>
                        <span class="byline">
                            <span class="author vcard">
                                <a rel="author" href="<?= $post->postAuthor->url; ?>"
                                   class="url fn"><?= $post->postAuthor->display_name; ?>
                                </a>
                            </span>
                        </span>
                        <span class="comments-link">
                            <a title="<?= Yii::t('writesdown', 'Comment on {post}', ['post' => $post->post_title]); ?>"
                               href="<?= $post->url ?>#respond"><?= Yii::t('writesdown', 'Leave a comment'); ?>
                            </a>
                        </span>
                    </div>
                </header>
                <div class="media">
                    <?php
                    $image = $post->getMedia()->where(['LIKE', 'media_mime_type', 'image/'])->one();
                    if ($image) {
                        $image_metadata = $image->getMeta('metadata');
                        $image_src = $image_metadata['media_versions']['thumbnail']['url'];
                        $image_width = $image_metadata['media_versions']['thumbnail']['width'];
                        $image_height = $image_metadata['media_versions']['thumbnail']['height'];
                        echo Html::a(Html::img($image->uploadUrl . $image_src, [
                            'width'  => $image_width,
                            'height' => $image_height,
                            'alt'    => $image->media_title,
                            'class'  => 'post-thumbnail'
                        ]), $post->url, ['class' => 'media-left entry-thumbnail']);
                    }
                    ?>
                    <div class="media-body">
                        <p class="entry-summary">
                            <?= $post->post_excerpt; ?>...
                        </p>
                        <footer class="footer-meta">
                            <h3>
                                <?php
                                $tags = $post->getTerms()->innerJoinWith(['taxonomy'])->andWhere(['taxonomy_slug' => 'tag'])->all();
                                foreach ($tags as $tag) {
                                    echo Html::a($tag->term_name, $tag->url, ['class' => 'badge']) . "\n";
                                }
                                ?>
                            </h3>
                        </footer>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
        <nav id="archive-pagination">
            <?php
            // display pagination
            echo LinkPager::widget([
                'pagination'           => $pages,
                'activePageCssClass'   => 'active',
                'disabledPageCssClass' => 'disabled',
                'options'              => [
                    'class' => 'pagination'
                ]
            ]);
            ?>
        </nav>
    <?php endif; ?>
</div>
