<?php
/**
 * @file    UserController.php.
 * @date    6/4/2015
 * @time    10:20 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use Yii;
use common\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/* MODEL */
use common\models\Option;

/**
 * Class UserController
 *
 * @package frontend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class UserController extends Controller
{
    /**
     * Displays a single User model.
     *
     * @param null $id
     * @param      $username
     *
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id = null, $username = null)
    {
        $render = '/user/view';

        if ($id) {
            $model = $this->findModel($id);
        } else if ($username) {
            $model = $this->findModelByUsername($username);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $model->getPosts()->andWhere(['post_status' => 'publish'])->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => Option::get('posts_per_page')
        ]);
        $query->offset($pages->offset)->limit($pages->limit);
        $posts = $query->all();

        if ($posts) {
            if (is_file($this->getViewPath() . '/view-' . $model->username . '.php')) {
                $render = 'view-' . $model->username . '.php';
            }

            return $this->render($render, [
                'model' => $model,
                'posts'    => $posts,
                'pages'    => isset($pages) ? $pages : null
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $username
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelByUsername($username)
    {
        if (($model = User::find()->andWhere(['username' => $username])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}