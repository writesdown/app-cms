<?php
/**
 * @file      view.php.
 * @date      6/4/2015
 * @time      11:27 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $term common\models\Term */
/* @var $posts common\models\Post[] */
/* @var $tags common\models\Term[] */
/* @var $pages yii\data\Pagination */

$this->title = Html::encode($term->taxonomy->taxonomy_sn . ': ' . $term->term_name . ' - ' . Option::get('sitetitle'));
$this->params['breadcrumbs'][] = Html::encode($term->term_name);
?>

<div class="archive term-view">
    <header id="archive-header" class="archive-header page-header">
        <h1><?= Html::encode($term->taxonomy->taxonomy_sn . ': ' . $term->term_name) ?></h1>
        <?php
        if ($term->term_description) {
            echo Html::tag('div', $term->term_description, ['class' => 'description term-description']);
        }
        ?>
    </header>
    <?php if ($posts): ?>
        <?php foreach ($posts as $post) : ?>
            <article class="hentry">
                <header class="entry-header page-header">
                    <h2 class="entry-title"><?= Html::a(Html::encode($post->post_title), $post->url); ?></h2>
                    <?php
                    $updated = new \DateTime($post->post_modified, new DateTimeZone(Yii::$app->timeZone));
                    ?>
                    <div class="entry-meta">
                        <span class="entry-date">
                            <span aria-hidden="true" class="glyphicon glyphicon-time"></span>
                            <a rel="bookmark" href="<?= $post->url; ?>">
                                <time datetime="<?= $updated->format('c'); ?>" class="entry-date"><?= Yii::$app->formatter->asDate($post->post_date); ?></time>
                            </a>
                        </span>
                        <span class="byline">
                            <span class="author vcard">
                                <span aria-hidden="true" class="glyphicon glyphicon-user"></span>
                                <a rel="author" href="<?= $post->postAuthor->url; ?>" class="url fn"><?= $post->postAuthor->display_name; ?></a>
                            </span>
                        </span>
                        <span class="comments-link">
                            <span aria-hidden="true" class="glyphicon glyphicon-comment"></span>
                            <a title="<?= Yii::t('writesdown', 'Comment on {postTitle}', ['postTitle' => $post->post_title]); ?>" href="<?= $post->url ?>#respond"><?= Yii::t('writesdown', 'Leave a comment'); ?></a>
                        </span>
                    </div>
                </header>
                <div class="entry-summary">
                    <?= $post->post_excerpt ?>...
                </div>
                <footer class="footer-meta">
                    <h3>
                        <?php
                        $tags = $post->getTerms()->innerJoinWith(['taxonomy'])->andWhere(['taxonomy_slug' => 'tag'])->all();
                        foreach ($tags as $tag) {
                            echo Html::a($tag->term_name, $tag->url, ['class' => 'btn btn-xs btn-success']) . "\n";
                        }
                        ?>
                    </h3>
                </footer>
            </article>
        <?php endforeach; ?>
        <nav id="archive-pagination">
            <?php
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
