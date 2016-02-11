<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace modules\feed\frontend\controllers;

use common\models\Option;
use common\models\Post;
use Yii;
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
     * Displaying feed.
     *
     * @return string
     */
    public function actionIndex()
    {
        /* @var $lastPost \common\models\Post */
        $response = Yii::$app->response;
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
        $response->format = $response::FORMAT_RAW;

        // Get first post and all posts
        $lastPost = Post::find()
            ->where(['status' => Post::STATUS_PUBLISH])
            ->andWhere(['<=', 'date', date('Y-m-d H:i:s')])
            ->orderBy(['id' => SORT_DESC])->one();

        $posts = Post::find()
            ->where(['status' => Post::STATUS_PUBLISH])
            ->andWhere(['<=', 'date', date('Y-m-d H:i:s')])
            ->limit(Option::get('posts_per_rss'))
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->renderPartial('index', [
            'title'         => Option::get('sitetitle'),
            'description'   => Option::get('tagline'),
            'link'          => Yii::$app->request->absoluteUrl,
            'lastBuildDate' => new \DateTime($lastPost->date, new \DateTimeZone(Option::get('time_zone'))),
            'language'      => Yii::$app->language,
            'generator'     => 'http://www.writesdown.com',
            'posts'         => $posts,
            'rssUseExcerpt' => Option::get('rss_use_excerpt'),
        ]);
    }
}
