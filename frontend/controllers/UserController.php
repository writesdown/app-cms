<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use common\models\Option;
use common\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class UserController
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class UserController extends Controller
{
    /**
     * Displays a single User model.
     *
     * @param null        $id
     * @param string|null $user
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id = null, $user = null)
    {
        $render = '/user/view';

        if ($id) {
            $model = $this->findModel($id);
        } elseif ($user) {
            $model = $this->findModelByUsername($user);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }

        $query = $model->getPosts()->andWhere(['post_status' => 'publish'])->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => Option::get('posts_per_page'),
        ]);
        $query->offset($pages->offset)->limit($pages->limit);
        $posts = $query->all();

        if ($posts) {
            if (is_file($this->view->theme->basePath . '/user/view-' . $model->username . '.php')) {
                $render = 'view-' . $model->username . '.php';
            }

            return $this->render($render, [
                'user'  => $model,
                'posts' => $posts,
                'pages' => isset($pages) ? $pages : null,
            ]);
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $username
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelByUsername($username)
    {
        if (($model = User::findOne(['username' => $username])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
