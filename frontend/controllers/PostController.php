<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
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
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class PostController extends Controller
{
    /**
     * @param int|null    $id        ID of post-type.
     * @param string|null $posttype  Slug of post-type.
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($id = null, $posttype = null)
    {
        $render = 'index';

        if ($id) {
            $postType = $this->findPostType($id);
        } elseif ($posttype) {
            $postType = $this->findPostTypeBySlug($posttype);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }

        $query = $postType->getPosts()->andWhere(['post_status' => 'publish'])->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => Option::get('posts_per_page'),
        ]);
        $query->offset($pages->offset)->limit($pages->limit);
        $posts = $query->all();

        if ($posts) {
            if (is_file($this->view->theme->basePath . '/post/index-' . $postType->post_type_slug . '.php')) {
                $render = 'index-' . $postType->post_type_slug . '.php';
            }

            return $this->render($render, [
                'postType' => $postType,
                'posts'    => $posts,
                'pages'    => $pages,
            ]);
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Displays a single Post model.
     *
     * @param null    $postslug
     *
     * @param integer $id
     *
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionView($id = null, $postslug = null)
    {
        $render = 'view';

        $comment = new Comment();

        if ($id) {
            $model = $this->findModel($id);
        } elseif ($postslug) {
            $model = $this->findModelBySlug($postslug);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            if (!$comment->comment_parent) {
                $model->post_comment_count++;
            }
            if ($model->save()) {
                $this->refresh();
            }
        }

        if ($model->post_password && $model->post_password !== Yii::$app->request->post('password')) {
            return $this->render('protected', ['post' => $model]);
        }

        if (is_file($this->view->theme->basePath . '/post/view-' . $model->postType->post_type_slug . '.php')) {
            $render = 'view-' . $model->postType->post_type_slug . '.php';
        }

        return $this->render($render, [
            'post'    => $model,
            'comment' => $comment,
        ]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Post::findOne(['id' => $id, 'post_status' => 'publish']);

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }


    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $postSlug
     *
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelBySlug($postSlug)
    {
        $model = Post::findOne(['post_slug' => $postSlug, 'post_status' => 'publish']);

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
     *
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
     * @param $postType
     *
     * @throws \yii\web\NotFoundHttpException
     * @return PostType the loaded model
     */
    protected function findPostTypeBySlug($postType)
    {
        $model = PostType::findOne(['post_type_slug' => $postType]);

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
