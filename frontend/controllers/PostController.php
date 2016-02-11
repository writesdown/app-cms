<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use common\models\Option;
use common\models\Post;
use common\models\PostComment as Comment;
use common\models\PostType;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class PostController
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class PostController extends Controller
{
    /**
     * @param int|null $id Post type ID
     * @param string|null $slug Post type slug.
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($id = null, $slug = null)
    {
        $render = 'index';

        if ($id) {
            $postType = $this->findPostType($id);
        } elseif ($slug) {
            $postType = $this->findPostTypeBySlug($slug);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }

        $query = $postType->getPosts()
            ->andWhere(['status' => 'publish'])
            ->andWhere(['<=', 'date', date('Y-m-d H:i:s')])
            ->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => Option::get('posts_per_page'),
        ]);
        $query->offset($pages->offset)->limit($pages->limit);
        $posts = $query->all();

        if ($posts) {
            if (is_file($this->view->theme->basePath . '/post/index-' . $postType->name . '.php')) {
                $render = 'index-' . $postType->name . '.php';
            }

            return $this->render($render, [
                'postType' => $postType,
                'posts' => $posts,
                'pages' => $pages,
            ]);
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Displays a single Post model.
     *
     * @param null $slug Post slug
     * @param integer $id Post ID
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionView($id = null, $slug = null)
    {
        $render = 'view';
        $comment = new Comment();

        if ($id) {
            $model = $this->findModel($id);
        } elseif ($slug) {
            $model = $this->findModelBySlug($slug);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            if (!$comment->parent) {
                $model->comment_count++;
            }
            if ($model->save()) {
                $this->refresh();
            }
        }

        if ($model->password && $model->password !== Yii::$app->request->post('password')) {
            return $this->render('protected', ['post' => $model]);
        }

        if (is_file($this->view->theme->basePath . '/post/view-' . $model->postType->name . '.php')) {
            $render = 'view-' . $model->postType->name . '.php';
        }

        return $this->render($render, [
            'post' => $model,
            'comment' => $comment,
        ]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Post::find()
            ->andWhere(['id' => $id, 'status' => 'publish'])
            ->andWhere(['<=', 'date', date('Y-m-d H:i:s')])
            ->one();

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }


    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $slug
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelBySlug($slug)
    {
        $model = Post::find()
            ->andWhere(['slug' => $slug, 'status' => 'publish'])
            ->andWhere(['<=', 'date', date('Y-m-d H:i:s')])
            ->one();

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     * @throws \yii\web\NotFoundHttpException
     * @return PostType the loaded model
     */
    protected function findPostType($id)
    {
        $model = PostType::findOne($id);

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $slug Post type slug
     * @throws \yii\web\NotFoundHttpException
     * @return PostType the loaded model
     */
    protected function findPostTypeBySlug($slug)
    {
        $model = PostType::findOne(['slug' => $slug]);

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
