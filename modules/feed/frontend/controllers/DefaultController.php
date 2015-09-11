<?php

namespace modules\feed\frontend\controllers;

use Yii;
use yii\web\Controller;

/* MODEL */
use common\models\Post;
use common\models\search\Option;

/**
 * Class DefaultController
 *
 * @package modules\feed\frontend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
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
        $lastPost = Post::find()->where(['post_status' => 'publish'])->orderBy(['id' => SORT_DESC])->one();
        $posts = Post::find()->andWhere(['post_status' => 'publish'])->limit(Option::get('posts_per_rss'))->orderBy(['id' => SORT_DESC])->all();

        return $this->renderPartial('index', [
            'title'         => Option::get('sitetitle'),
            'description'   => Option::get('tagline'),
            'link'          => Yii::$app->request->absoluteUrl,
            'lastBuildDate' => new \DateTime($lastPost->post_date, new \DateTimeZone(Option::get('time_zone'))),
            'language'      => Yii::$app->language,
            'generator'     => 'http://www.writesdown.com',
            'posts'         => $posts,
            'rssUseExcerpt' => Option::get('rss_use_excerpt')
        ]);
    }
}
