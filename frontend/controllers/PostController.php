<?php
/**
 * @file      PostController.php.
 * @date      6/4/2015
 * @time      10:17 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/* MODEL */
use common\models\Option;
use common\models\PostType;
use common\models\Post;
use common\models\PostComment as Comment;

/**
 * Class PostController
 *
 * @package frontend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class PostController extends Controller
{
    /**
     * @param int|null    $id ID of post-type.
     * @param string|null $post_type Slug of post-type.
     *
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($id = null, $post_type = null)
    {
        $render = 'index';

        if ($id) {
            $postType = $this->findPostType($id);
        } else if ($post_type) {
            $postType = $this->findPostTypeBySlug($post_type);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }

        $query = $postType->getPosts()->andWhere(['post_status' => 'publish'])->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => Option::get('posts_per_page')
        ]);
        $query->offset($pages->offset)->limit($pages->limit);
        $posts = $query->all();

        if ($posts) {
            if (is_file($this->getViewPath() . '/index-' . $postType->post_type_slug . '.php')) {
                $render = 'index-' . $postType->post_type_slug . '.php';
            }

            return $this->render($render, [
                'postType' => $postType,
                'posts'    => $posts,
                'pages'    => $pages
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }
    }

    /**
     * Displays a single Post model.
     *
     * @param null    $post_type
     * @param null    $post_slug
     *
     * @param integer $id
     *
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionView($id = null, $post_slug = null, $post_type = null)
    {
        $render = 'view';

        $comment = new Comment();

        if ($id) {
            $model = $this->findModel($id);
        } else if ($post_slug) {
            $model = $this->findModelBySlug($post_slug);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if($model->post_password && $model->post_password !== Yii::$app->request->post('password')){
            return $this->render('protected', ['post' => $model]);
        }

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {

            if (!$comment->comment_parent)
                $model->post_comment_count++;

            if ($model->save()) {
                $this->refresh();
            }
        }

        if (is_file($this->getViewPath() . '/view-' . $model->postType->post_type_slug . '.php')) {
            $render = 'view-' . $model->postType->post_type_slug . '.php';
        }

        return $this->render($render, [
            'post'    => $model,
            'comment' => $comment
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
        $model = Post::find()->andWhere(['id' => $id])->andWhere(['post_status' => 'publish'])->one();
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $post_slug
     *
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelBySlug($post_slug)
    {
        $model = Post::find()->andWhere(['post_slug' => $post_slug])->andWhere(['post_status' => 'publish'])->one();
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $post_type
     *
     * @throws \yii\web\NotFoundHttpException
     * @return PostType the loaded model
     */
    protected function findPostTypeBySlug($post_type)
    {
        $model = PostType::find()->andWhere(['post_type_slug' => $post_type])->one();
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
} 