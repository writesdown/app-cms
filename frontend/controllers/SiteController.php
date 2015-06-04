<?php
/**
 * @file    SiteController.php.
 * @date    6/4/2015
 * @time    10:08 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/* MODEL */
use common\models\Option;
use common\models\Post;
use common\models\PostComment;
use frontend\models\ContactForm;

/**
 * Class SiteController
 *
 * @package frontend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class SiteController extends Controller{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Render home page of the site.
     *
     * @throws \yii\web\NotFoundHttpException
     * @return string
     */
    public function actionIndex(){
        /* @var $post \common\models\Post */

        $query = Post::find()->andWhere(['post_status' => 'publish']);

        if(Option::get('show_on_front') == 'page' && $front_page = Option::get('front_page')){
            $render = '/post/view';
            $comment = new PostComment();
            $query = $query->andWhere(['id' => $front_page]);
            $post = $query->one();

            if (is_file($this->getViewPath() . DIRECTORY_SEPARATOR . '../post/view-' . $post->postType->post_type_slug . '.php')) {
                $render = '/post/view-' . $post->postType->post_type_slug;
            }

            if ($post) {
                return $this->render($render, [
                    'post'    => $post,
                    'comment' => $comment
                ]);
            } else {
                return new NotFoundHttpException();
            }
        }else{
            if(Option::get('front_post_type') !== 'all'){
                $query->innerJoinWith(['postType'])->andWhere(['post_type_name' => Option::get('front_post_type')]);
            }
            $countQuery = clone $query;
            $pages = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize'   => Option::get('posts_per_page')
            ]);
            $query->offset($pages->offset)->limit($pages->limit);
            $posts = $query->all();
            if ($posts) {
                return $this->render('index', [
                    'posts' => $posts,
                    'pages' => isset($pages) ? $pages : null
                ]);
            } else {
                throw new NotFoundHttpException(Yii::t('writesdown', 'Page not found.'));
            }
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Option::get('admin_email'))) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Search post by title and content
     *
     * @param $s
     *
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSearch($s)
    {
        $query = Post::find()
            ->orWhere(['LIKE', 'post_title', $s])
            ->orWhere(['LIKE', 'post_content', $s])
            ->andWhere(['post_status' => 'publish'])
            ->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => Option::get('posts_per_page')
        ]);
        $query->offset($pages->offset)->limit($pages->limit);
        $posts = $query->all();
        if ($posts) {
            return $this->render('/site/search', [
                'posts' => $posts,
                'pages' => $pages,
                's'     => $s
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Action to create robots.txt.
     * If file of robots.txt exist then it will be used instead.
     */
    public function actionRobots()
    {
        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'text/plain; charset=UTF-8');
        $response->format = $response::FORMAT_RAW;
        if (Option::get('site_indexing')) {
            echo "User-agent: *\n";
            echo "Disallow: /\n";
        } else {
            echo "User-agent: *\n";
            echo "Disallow: /admin\n";
            echo "Disallow: /themes\n";
        }
    }

    /**
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionForbidden()
    {
        throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
    }

    /**
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionNotFound()
    {
        throw new NotFoundHttpException(Yii::t('writesdown', 'Page not found'));
    }
} 