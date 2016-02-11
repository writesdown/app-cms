<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use common\models\Option;
use common\models\Taxonomy;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $s string */
/* @var $posts common\models\Post[] */
/* @var $tags common\models\Term[] */
/* @var $pages yii\data\Pagination */

$this->title = Html::encode(Yii::t('writesdown', 'Search Result: {s}', ['s' => $s]) . ' - ' . Option::get('sitetitle'));
$this->params['breadcrumbs'][] = Html::encode(Yii::t('writesdown', 'Search Result: {s}', ['s' => $s]));
$this->registerMetaTag([
    'name' => 'robots',
    'content' => 'noindex, nofollow',
]);
?>

<div class="archive post-index">
    <header id="archive-header" class="archive-header page-header">
        <h1><?= Html::encode(Yii::t('writesdown', 'Search Result: {s}', ['s' => $s])) ?></h1>

    </header>

    <?php if ($posts): ?>
        <?php foreach ($posts as $post): ?>
            <article class="hentry">
                <header class="entry-header page-header">
                    <h2 class="entry-title"><?= Html::a(Html::encode($post->title), $post->url) ?></h2>

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
                                'writesdown',
                                'Comment on {postTitle}',
                                ['postTitle' => $post->title]
                            ) ?>" href="<?= $post->url ?>#respond"><?= Yii::t('writesdown', 'Leave a comment') ?></a>
                        </span>
                    </div>
                </header>
                <div class="entry-summary">
                    <?= $post->excerpt ?>...
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
                                <?= Html::a($tag->name, $tag->url, [
                                    'class' => 'btn btn-xs btn-success',
                                ]) . "\n" ?>
                            <?php endforeach ?>
                        </h3>
                    <?php endif; ?>

                </footer>
            </article>
        <?php endforeach ?>
        <nav id="archive-pagination">
            <?= LinkPager::widget([
                'pagination' => $pages,
                'activePageCssClass' => 'active',
                'disabledPageCssClass' => 'disabled',
                'options' => ['class' => 'pagination'],
            ]) ?>
        </nav>
    <?php endif ?>

</div>
