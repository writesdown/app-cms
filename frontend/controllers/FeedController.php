<?php
/**
 * @file    FeedController.php.
 * @date    6/4/2015
 * @time    10:16 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/* MODEL */
use common\models\Post;
use common\models\Option;
use common\models\PostType;

/**
 * Class FeedController
 *
 * @package frontend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class FeedController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        /* @var $lastPost \common\models\Post */
        $response = Yii::$app->response;
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
        $response->format = $response::FORMAT_RAW;
        $lastPost = Post::find()
            ->where(['post_status' => 'publish'])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        return $this->renderPartial('index', [
            'title'         => Option::get('sitetitle'),
            'description'   => Option::get('tagline'),
            'link'          => Yii::$app->request->absoluteUrl,
            'lastBuildDate' => new \DateTime($lastPost->post_date, new \DateTimeZone(Option::get('time_zone'))),
            'postTypes'     => PostType::find()->all(),
            'language'      => Yii::$app->language,
            'generator'     => 'http://www.writesdown.com',
        ]);
    }
}