<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use common\models\Option;
use common\models\Taxonomy;
use frontend\assets\CommentAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $post common\models\Post */
/* @var $comment common\models\PostComment */
/* @var $category common\models\Term */
/* @var $tags common\models\Term[] */

$this->title = Html::encode($post->title . ' - ' . Option::get('sitetitle'));
$this->params['breadcrumbs'][] = [
    'label' => Html::encode($post->postType->singular_name),
    'url' => ['/post/index', 'id' => $post->postType->id],
];
$category = $post->getTerms()
    ->innerJoinWith([
        'taxonomy' => function ($query) {
            /* @var $query \yii\db\ActiveQuery */
            $query->from(['taxonomy' => Taxonomy::tableName()]);
        },
    ])
    ->andWhere(['taxonomy.name' => 'category'])
    ->one();

if ($category) {
    $this->params['breadcrumbs'][] = ['label' => Html::encode($category->name), 'url' => $category->url];
}

$this->params['breadcrumbs'][] = Html::encode($post->title);
CommentAsset::register($this);
?>
<div class="single post-view">
    <article class="hentry">

        <?php if (Yii::$app->controller->route !== 'site/index'): ?>
            <header class="entry-header page-header">
                <h1 class="entry-title"><?= Html::encode($post->title) ?></h1>

                <?php $updated = new \DateTime($post->modified, new DateTimeZone(Yii::$app->timeZone)) ?>
                <div class="entry-meta">
                    <span class="entry-date">
                        <span aria-hidden="true" class="glyphicon glyphicon-time"></span>
                        <a rel="bookmark" href="<?= $post->url ?>">
                            <time datetime="<?= $updated->format('c') ?>" class="entry-date">
                                <?= Yii::$app->formatter->asDate($post->date) ?>
                            </time>
                        </a>
                    </span>
                    <span class="byline">
                        <span class="author vcard">
                            <span aria-hidden="true" class="glyphicon glyphicon-user"></span>
                            <a rel="author" href="<?= $post->postAuthor->url ?>" class="url fn">
                                <?= $post->postAuthor->display_name ?>
                            </a>
                        </span>
                    </span>
                    <span class="comments-link">
                        <span aria-hidden="true" class="glyphicon glyphicon-comment"></span>
                        <a title="<?= Yii::t(
                            'writesdown', 'Comment on {postTitle}',
                            ['postTitle' => $post->title]
                        ) ?>" href="<?= $post->url ?>#respond"><?= Yii::t('writesdown', 'Leave a comment') ?></a>
                    </span>
                </div>
            </header>
        <?php endif ?>

        <div class="entry-content clearfix">
            <?= $post->content ?>
        </div>
        <footer class="footer-meta">
            <?php $tags = $post->getTerms()
                ->innerJoinWith([
                    'taxonomy' => function ($query) {
                        /** @var $query \yii\db\ActiveQuery */
                        return $query->from(['taxonomy' => Taxonomy::tableName()]);
                    },
                ])
                ->where(['taxonomy.name' => 'tag'])
                ->all() ?>

            <?php if ($tags): ?>
                <h3>
                    <?php foreach ($tags as $tag): ?>
                        <?= Html::a($tag->name, $tag->url, ['class' => 'btn btn-xs btn-success']) . "\n" ?>
                    <?php endforeach ?>
                </h3>
            <?php endif ?>

        </footer>
    </article>
    <nav id="single-pagination">
        <ul class="pager">
            <li class="previous">
                <?= $post->getPrevPostLink(
                    true,
                    false,
                    '<span aria-hidden="true" class="glyphicon glyphicon-menu-left"></span> PREV'
                ) ?>

            </li>
            <li class="next">
                <?= $post->getNextPostLink(
                    true,
                    false,
                    'NEXT <span aria-hidden="true" class="glyphicon glyphicon-menu-right"></span>'
                ) ?>

            </li>
        </ul>
    </nav>
    <?= $this->render('/post-comment/comments', ['post' => $post, 'comment' => $comment]) ?>
</div>
