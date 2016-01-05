<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace modules\sitemap\frontend\controllers;

use common\models\Media;
use common\models\Option;
use common\models\Post;
use common\models\PostType;
use common\models\Taxonomy;
use common\models\Term;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class DefaultController
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
class DefaultController extends Controller
{
    /**
     * @var array option for sitemap
     */
    private $_option = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'       => ['index', 'style', 'view'],
                        'allow'         => true,
                        'matchCallback' => function () {
                            $option = Option::get('sitemap');

                            return $option['enable_sitemap'];
                        },
                    ],
                ],
            ],
        ];
    }

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
            if (!isset($this->_option['post_type'][$postType->id]['enable'])
                || !$this->_option['post_type'][$postType->id]['enable']
            ) {
                continue;
            }

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
                    'pageSize'   => $this->_option['entries_per_page'],
                ]);
                for ($i = 1; $i <= $pages->pageCount; $i++) {
                    $items[] = [
                        'loc'     => Yii::$app->urlManager->hostInfo
                            . Url::to(['view', 'type' => 'p', 'slug' => $postType->post_type_slug, 'page' => $i]),
                        'lastmod' => $lastmod->format('r'),
                    ];
                }
            }
        }

        foreach ($taxonomies as $taxonomy) {
            if (!isset($this->_option['taxonomy'][$taxonomy->id]['enable'])
                || !$this->_option['taxonomy'][$taxonomy->id]['enable']
            ) {
                continue;
            }

            if ($terms = $taxonomy->terms) {
                $post = Post::find()
                    ->from(['post' => Post::tableName()])
                    ->innerJoinWith([
                        'terms' => function ($query) {
                            /* @var $query \yii\db\ActiveQuery */
                            $query->from(['term' => Term::tableName()]);
                        },
                    ])
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
                        'pageSize'   => $this->_option['entries_per_page'],
                    ]);

                    for ($i = 1; $i <= $pages->pageCount; $i++) {
                        $items[] = [
                            'loc'     => Yii::$app->urlManager->hostInfo
                                . Url::to(['view', 'type' => 'c', 'slug' => $taxonomy->taxonomy_slug, 'page' => $i]),
                            'lastmod' => $lastmod->format('r'),
                        ];
                    }
                }
            }
        }

        if (isset($this->_option['media']['enable']) && $this->_option['media']['enable']) {
            $query = Media::find();
            $countQuery = clone $query;
            $pages = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize'   => $this->_option['entries_per_page'],
            ]);

            if ($lastMedia = $query->orderBy(['id' => SORT_DESC])->one()) {
                $lastmod = new \DateTime($lastMedia->media_modified, new \DateTimeZone(Option::get('time_zone')));
                for ($i = 1; $i <= $pages->pageCount; $i++) {
                    $items[] = [
                        'loc'     => Yii::$app->urlManager->hostInfo
                            . Url::to(['view', 'type' => 'm', 'slug' => 'media', 'page' => $i]),
                        'lastmod' => $lastmod->format('r'),
                    ];
                }
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
            $item['loc'] = Yii::$app->urlManager->hostInfo . Yii::$app->urlManager->baseUrl;
            $item['changefreq'] = $this->_option['home']['changefreq'];
            $item['priority'] = $this->_option['home']['priority'];

            return $this->renderPartial('home', ['item' => $item]);
        } elseif ($type === 'p') {
            $postType = PostType::find()->where(['post_type_slug' => $slug])->one();
            $posts = $postType->getPosts()
                ->andWhere(['post_status' => 'publish'])
                ->offset($page * $this->_option['entries_per_page'])
                ->limit($this->_option['entries_per_page'])
                ->all();

            foreach ($posts as $post) {
                $lastmod = new \DateTime($post->post_modified, new \DateTimeZone(Option::get('time_zone')));
                $items[$post->id]['loc'] = $post->url;
                $items[$post->id]['lastmod'] = $lastmod->format('r');
                $items[$post->id]['changefreq'] = $this->_option['post_type'][$postType->id]['changefreq'];
                $items[$post->id]['priority'] = $this->_option['post_type'][$postType->id]['priority'];

                if ($images = $post->getMedia()->where(['LIKE', 'media_mime_type', 'image/'])->all()) {
                    foreach ($images as $image) {
                        $metadata = $image->getMeta('metadata');
                        $items[$post->id]['image'][$image->id]['loc'] = $image->uploadUrl
                            . $metadata['media_versions']['full']['url'];
                        $items[$post->id]['image'][$image->id]['title'] = $image->media_title
                            ? $image->media_title
                            : null;
                        $items[$post->id]['image'][$image->id]['caption'] = $image->media_excerpt
                            ? $image->media_excerpt
                            : null;
                    }
                }
            }

            return $this->renderPartial('post-type', ['items' => $items]);
        } elseif ($type === 'c') {
            $taxonomy = Taxonomy::find()->where(['taxonomy_slug' => $slug])->one();
            $terms = $taxonomy->getTerms()
                ->offset($page * $this->_option['entries_per_page'])
                ->limit($this->_option['entries_per_page'])
                ->all();

            foreach ($terms as $term) {
                $post = $term->getPosts()
                    ->andWhere(['post_status' => 'publish'])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();

                if ($post) {
                    $lastmod = new \DateTime($post->post_modified, new \DateTimeZone(Option::get('time_zone')));
                    $items[$term->id]['loc'] = $term->url;
                    $items[$term->id]['lastmod'] = $lastmod->format('r');
                    $items[$term->id]['changefreq'] = $this->_option['taxonomy'][$taxonomy->id]['changefreq'];
                    $items[$term->id]['priority'] = $this->_option['taxonomy'][$taxonomy->id]['priority'];
                }
            }

            return $this->renderPartial('taxonomy', ['items' => $items]);
        } elseif ($type === 'm') {
            $mediaSet = Media::find()->offset($page * $this->_option['entries_per_page'])->limit($this->_option['entries_per_page'])->all();
            foreach ($mediaSet as $media) {
                $lastmod = new \DateTime($media->media_modified, new \DateTimeZone(Option::get('time_zone')));
                $items[$media->id]['loc'] = $media->url;
                $items[$media->id]['lastmod'] = $lastmod->format('r');
                $items[$media->id]['changefreq'] = $this->_option['media']['changefreq'];
                $items[$media->id]['priority'] = $this->_option['media']['priority'];
            }

            return $this->renderPartial('media', ['items' => $items]);
        }

        return $this->redirect(['/site/not-found']);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        /*  @var $postType \common\models\PostType */
        /*  @var $taxonomy \common\models\Taxonomy */
        if (parent::beforeAction($action)) {
            $this->_option = Option::get('sitemap');

            return true;
        }

        return false;
    }
}
