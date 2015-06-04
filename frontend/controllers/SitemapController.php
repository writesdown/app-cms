<?php
/**
 * @file    SitemapController.php.
 * @date    6/4/2015
 * @time    10:19 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/* MODEL */
use common\models\Option;
use common\models\PostType;
use common\models\Post;
use common\models\Taxonomy;
use common\models\Term;
use common\models\Media;

/**
 * Class SitemapController
 *
 * @package frontend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class SitemapController extends Controller
{
    /**
     * @var int
     */
    public $pageSize = 1000;

    /**
     * @return string
     */
    public function actionIndex()
    {
        /* @var $postType PostType */
        /* @var $post Post */
        /* @var $taxonomies Taxonomy[] */
        /* @var $taxonomy Taxonomy */
        /* @var $lastMedia Media */

        $response = Yii::$app->response;
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
        $response->format = $response::FORMAT_RAW;
        $postTypes = PostType::find()->select(['id', 'post_type_slug'])->all();
        $taxonomies = Taxonomy::find()->select(['id', 'taxonomy_slug'])->all();
        $items = [];

        foreach ($postTypes as $postType) {
            if ($post = $postType->getPosts()
                ->andWhere(['post_status' => 'publish'])
                ->orderBy(['id' => SORT_DESC])
                ->one()
            ) {
                $lastmod = new \DateTime($post->post_modified, new \DateTimeZone(Option::get('time_zone')));
                $query = $postType->getPosts()->andWhere(['post_status' => 'publish']);
                $countQuery = clone $query;
                $pages = new Pagination([
                    'totalCount' => $countQuery->count(),
                    'pageSize'   => $this->pageSize
                ]);
                for ($i = 1; $i <= $pages->pageCount; $i++) {
                    $items[] = [
                        'loc'     => Yii::$app->urlManager->createAbsoluteUrl(['/sitemap/view', 'type' => 'p', 'slug' => $postType->post_type_slug, 'page' => $i]),
                        'lastmod' => $lastmod->format('r')
                    ];
                }
            }
        }

        foreach ($taxonomies as $taxonomy) {
            if ($terms = $taxonomy->terms) {
                $post = Post::find()
                    ->from(['post' => Post::tableName()])
                    ->innerJoinWith(['terms' => function ($query) {
                        /* @var $query \yii\db\ActiveQuery */
                        $query->from(['term' => Term::tableName()]);
                    }])
                    ->where(['IN', 'term.id', ArrayHelper::getColumn($terms, 'id')])
                    ->andWhere(['post.post_status' => 'publish'])
                    ->orderBy(['post.id' => SORT_DESC])
                    ->one();
                if ($post) {
                    $query = $taxonomy->getTerms();
                    $lastmod = new \DateTime($post->post_modified, new \DateTimeZone(Option::get('time_zone')));
                    $countQuery = clone $query;
                    $pages = new Pagination([
                        'totalCount' => $countQuery->count(),
                        'pageSize'   => $this->pageSize
                    ]);
                    for ($i = 1; $i <= $pages->pageCount; $i++) {
                        $items[] = [
                            'loc'     => Yii::$app->urlManager->createAbsoluteUrl(['/sitemap/view', 'type' => 'c', 'slug' => $taxonomy->taxonomy_slug, 'page' => $i]),
                            'lastmod' => $lastmod->format('r')
                        ];
                    }
                }
            }
        }

        $query = Media::find();
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => $this->pageSize
        ]);

        if ($lastMedia = $query->orderBy(['id' => SORT_DESC])->one()) {
            $lastmod = new \DateTime($lastMedia->media_modified, new \DateTimeZone(Option::get('time_zone')));
            for ($i = 1; $i <= $pages->pageCount; $i++) {
                $items[] = [
                    'loc'     => Yii::$app->urlManager->createAbsoluteUrl(['/sitemap/view', 'type' => 'm', 'slug' => 'media', 'page' => $i]),
                    'lastmod' => $lastmod->format('r')
                ];
            }
        }

        return $this->renderPartial('index', ['items' => $items]);
    }

    /**
     * @return string
     */
    public function actionStyle()
    {
        $response = Yii::$app->response;
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
        $response->format = $response::FORMAT_RAW;

        return $this->renderPartial('style');
    }

    /**
     * @param string $type
     * @param string $slug
     * @param int    $page
     *
     * @return string
     */
    public function actionView($type, $slug, $page = 1)
    {
        /* @var $taxonomy Taxonomy */
        /* @var $postType PostType */
        /* @var $posts Post[] */
        /* @var $images Media[] */
        /* @var $terms Term[] */
        /* @var $mediaSet Media[] */
        /* @var $post Post */
        $page--;
        $items = [];
        $response = Yii::$app->response;
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
        $response->format = $response::FORMAT_RAW;
        if ($type === 'h') {
            $item['loc'] = Yii::$app->urlManager->createAbsoluteUrl(['/']);
            $item['changefreq'] = 'daily';
            $item['priority'] = '1';

            return $this->renderPartial('home', ['item' => $item]);
        } else if ($type === 'p') {
            $postType = PostType::find()->where(['post_type_slug' => $slug])->one();
            $posts = $postType->getPosts()
                ->andWhere(['post_status' => 'publish'])
                ->offset($page * $this->pageSize)
                ->limit($this->pageSize)
                ->all();
            foreach ($posts as $post) {
                $lastmod = new \DateTime($post->post_modified, new \DateTimeZone(Option::get('time_zone')));
                $items[ $post->id ]['loc'] = $post->url;
                $items[ $post->id ]['lastmod'] = $lastmod->format('r');
                $items[ $post->id ]['changefreq'] = 'weekly';
                $items[ $post->id ]['priority'] = '0.6';
                if ($images = $post->getMedia()->where(['LIKE', 'media_mime_type', 'image/'])->all()) {
                    foreach ($images as $image) {
                        $metadata = $image->getMeta('metadata');
                        $items[ $post->id ]['image'][ $image->id ]['loc'] = $image->uploadUrl .
                            $metadata['media_versions']['full']['url'];
                        $items[ $post->id ]['image'][ $image->id ]['title'] = $image->media_title ?
                            $image->media_title :
                            null;
                        $items[ $post->id ]['image'][ $image->id ]['caption'] = $image->media_excerpt ?
                            $image->media_excerpt :
                            null;
                    }
                }
            }

            return $this->renderPartial('post-type', ['items' => $items]);
        } else if ($type === 'c') {
            $taxonomy = Taxonomy::find()->where(['taxonomy_slug' => $slug])->one();
            $terms = $taxonomy->getTerms()
                ->offset($page * $this->pageSize)
                ->limit($this->pageSize)
                ->all();
            foreach ($terms as $term) {
                $post = $term->getPosts()
                    ->andWhere(['post_status' => 'publish'])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if ($post) {
                    $lastmod = new \DateTime($post->post_modified, new \DateTimeZone(Option::get('time_zone')));
                    $items[ $term->id ]['loc'] = $term->url;
                    $items[ $term->id ]['lastmod'] = $lastmod->format('r');
                    $items[ $term->id ]['changefreq'] = 'weekly';
                    $items[ $term->id ]['priority'] = '0.2';
                }
            }

            return $this->renderPartial('taxonomy', ['items' => $items]);

        } else if ($type === 'm') {
            $mediaSet = Media::find()->offset($page * $this->pageSize)->limit($this->pageSize)->all();
            foreach ($mediaSet as $media) {
                $lastmod = new \DateTime($media->media_modified, new \DateTimeZone(Option::get('time_zone')));
                $items[ $media->id ]['loc'] = $media->url;
                $items[ $media->id ]['lastmod'] = $lastmod->format('r');
                $items[ $media->id ]['changefreq'] = 'weekly';
                $items[ $media->id ]['priority'] = '0.6';
            }

            return $this->renderPartial('media', ['items' => $items]);
        } else {
            return $this->redirect(['/site/not-found']);
        }
    }
} 