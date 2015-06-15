<?php
/**
 * @file      view.php.
 * @date      6/4/2015
 * @time      11:24 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use frontend\assets\CommentAsset;

/* @var $this yii\web\View */
/* @var $post common\models\Post */
/* @var $comment common\models\PostComment */

$this->title = $post->post_title;
$this->params['breadcrumbs'][] = ['label' => $post->postType->post_type_sn, 'url' => ['/post/index', 'id' => $post->postType->id]];
$category = $post->getTerms()->innerJoinWith(['taxonomy'])->andWhere(['taxonomy_slug' => 'category'])->one();
if ($category) {
    $this->params['breadcrumbs'][] = ['label' => $category->term_name, 'url' => $category->url];
}
$this->params['breadcrumbs'][] = $this->title;

CommentAsset::register($this);
?>
<div class="single post-view">
    <article class="hentry">
        <header class="entry-header">
            <h1 class="entry-title"><?= $post->post_title ?></h1>
            <?php $updated = new \DateTime($post->post_modified, new DateTimeZone(Yii::$app->timeZone)); ?>
            <div class="entry-meta">
                <span class="entry-date">
                    <a rel="bookmark" href="<?= $post->url; ?>">
                        <time datetime="<?= $updated->format('r'); ?>"
                              class="entry-date"><?= Yii::$app->formatter->asDate($post->post_date); ?></time>
                    </a>
                </span>
                <span class="byline">
                    <span class="author vcard">
                        <a rel="author" href="<?= $post->postAuthor->url; ?>"
                           class="url fn"><?= $post->postAuthor->display_name; ?></a>
                    </span>
                </span>
                <span class="comments-link">
                    <a title="<?= Yii::t('writesdown', 'Comment on Kombikongo Post 1'); ?>"
                       href="<?= $post->url ?>#respond"><?= Yii::t('writesdown', 'Leave a comment'); ?></a>
                </span>
            </div>
        </header>
        <div class="entry-content">
            <?= $post->post_content; ?>
        </div>
    </article>
    <nav id="single-pagination">
        <ul class="pager">
            <li class="previous"><?= $post->getPrevPostLink(true, false, '<span aria-hidden="true" class="glyphicon glyphicon-menu-left"></span> PREV'); ?></li>
            <li class="next"><?= $post->getNextPostLink(true, false, 'NEXT <span aria-hidden="true" class="glyphicon glyphicon-menu-right"></span>'); ?></a></li>
        </ul>
    </nav>
    <?= $this->render('/post-comment/comments', ['post' => $post, 'comment' => $comment]); ?>
</div>