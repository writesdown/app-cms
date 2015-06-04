<?php
/**
 * @file      SiteController.php.
 * @date      6/4/2015
 * @time      5:09 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;

/* MODEL */
use common\models\User;
use common\models\Option;
use common\models\Post;
use common\models\PostComment;
use common\models\LoginForm;
use common\models\SignupForm;
use common\models\ResetPasswordForm;
use common\models\PasswordResetRequestForm;

/**
 * Site controller.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class SiteController extends Controller
{
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
                        'actions' => ['login', 'request-password-reset', 'reset-password', 'forbidden', 'not-found', 'terms'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'error'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions'       => ['signup'],
                        'allow'         => true,
                        'matchCallback' => function ($rule, $action) {
                            return Option::get('allow_signup') && Yii::$app->user->isGuest;
                        },
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Show user count, post count, post-comment count on index (dashboard).
     *
     * @return string
     */
    public function actionIndex()
    {
        $userQuery = User::find()->andWhere(['status' => '10']);
        $userCloneQuery = clone $userQuery;
        $userCount = $userCloneQuery->count();
        $users = $userQuery->limit(12)->orderBy(['id' => SORT_DESC])->all();

        $postQuery = Post::find()->andWhere(['post_status' => 'publish']);
        $postCloneQuery = clone $postQuery;
        $postCount = $postCloneQuery->count();
        $posts = $postQuery->limit(10)->orderBy(['id' => SORT_DESC])->all();

        $commentQuery = PostComment::find()->andWhere(['comment_approved' => 'approved']);
        $commentCloneQuery = clone $commentQuery;
        $commentCount = $commentCloneQuery->count();
        $comments = $commentQuery->limit(5)->orderBy(['id' => SORT_DESC])->all();

        return $this->render('index', [
            'users'        => $users,
            'posts'        => $posts,
            'comments'     => $comments,
            'userCount'    => $userCount,
            'postCount'    => $postCount,
            'commentCount' => $commentCount,
        ]);
    }

    /**
     * Show login page.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        // Change layout and bodyClass of login-page
        $this->layout = 'blank';
        Yii::$app->params['bodyClass'] = 'login-page';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout for current user and redirect to home of backend.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Show signup for guest to register on site while Option::get('allow_signup') is true.
     *
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        // Change layout and body class of register-page
        $this->layout = 'blank';
        Yii::$app->params['bodyClass'] = 'register-page';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Generate and send token to user's email for resetting password.
     *
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        // Change layout and body class of register page
        $this->layout = 'blank';
        Yii::$app->params['bodyClass'] = 'register-page';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('request-password-reset-token', [
            'model' => $model,
        ]);
    }

    /**
     * Show reset password. It requires param $token that generated on actionRequestPasswordReset which is sent to
     * user's email.
     *
     * @param $token
     *
     * @return string|\yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        // Change layout and body class of reset password page
        $this->layout = 'blank';
        Yii::$app->params['bodyClass'] = 'register-page';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    /**
     * Render term and condition
     */
    public function actionTerms()
    {
        $this->layout = 'blank';
        Yii::$app->params['bodyClass'] = 'skin-blue layout-boxed sidebar-mini';

        return $this->render('terms');
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