<?php
/**
 * @file      PostCommentControl.php.
 * @date      6/4/2015
 * @time      5:04 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/* MODEL */
use common\models\Post;
use common\models\PostType;
use common\models\PostComment;
use common\models\search\PostComment as PostCommentSearch;

/**
 * PostCommentController implements the CRUD actions for PostComment model.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class PostCommentController extends Controller
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
                        'actions' => ['index', 'update', 'delete', 'bulk-action', 'reply'],
                        'allow'   => true,
                        'roles'   => ['editor']
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'      => ['post'],
                    'bulk-action' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Lists all PostComment models.
     *
     * @param integer      $post_type
     * @param null|integer $post_id
     *
     * @throws \yii\web\NotFoundHttpException
     * @return string
     */
    public function actionIndex($post_type, $post_id = null)
    {
        $post = null;
        $postType = $this->findPostType($post_type);

        if ($post_id) {
            $post = $this->findPost($post_id);
        }

        $searchModel = new PostCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $post_type, $post_id);

        return $this->render('index', [
            'post'         => $post,
            'postType'     => $postType,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Updates an existing PostComment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->comment_date = Yii::$app->formatter->asDatetime($model->comment_date, 'php:Y-m-d H:i:s');
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            };
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PostComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $post = $model->commentPost;
        if ($model->delete()) {
            if (!$model->comment_parent) {
                $post->post_comment_count--;
                $post->save();
            }
            PostComment::deleteAll(['comment_parent' => $model->id]);
        }

        return $this->redirect(['index', 'post_type' => $post->post_type]);
    }

    /**
     * Bulk action for post comments
     */
    public function actionBulkAction()
    {
        if ($_POST['action'] === 'delete') {
            foreach ($_POST['ids'] as $id) {
                $model = $this->findModel($id);
                $post = $model->commentPost;
                if ($model->delete()) {
                    if (!$model->comment_parent) {
                        $post->post_comment_count--;
                        $post->save();
                    }
                    PostComment::deleteAll(['comment_parent' => $model->id]);
                }
            }
        } else if ($_POST['action'] === PostComment::COMMENT_APPROVED) {
            foreach ($_POST['ids'] as $id) {
                $this->findModel($id)->updateAttributes(['comment_approved' => 'approved']);
            }
        } else if ($_POST['action'] === PostComment::COMMENT_UNAPPROVED) {
            foreach ($_POST['ids'] as $id) {
                $this->findModel($id)->updateAttributes(['comment_approved' => 'unapproved']);
            }
        } else if ($_POST['action'] === PostComment::COMMENT_TRASH) {
            foreach ($_POST['ids'] as $id) {
                $this->findModel($id)->updateAttributes(['comment_approved' => 'trash']);
            }
        }
    }

    /**
     * Action for replying a comment.
     * It's based on comment_id
     *
     * @param int $id
     *
     * @return string
     */
    public function actionReply($id)
    {
        $commentParent = $this->findModel($id);
        $model = new PostComment(['scenario' => 'reply']);

        if ($model->load(Yii::$app->request->post())) {

            $model->comment_post_id = $commentParent->comment_post_id;
            $model->comment_parent = $commentParent->id;

            if ($model->save()) {
                $this->redirect(['post-comment/update', 'id' => $model->id]);
            }
        }

        return $this->render('reply', [
            'commentParent' => $commentParent,
            'model'         => $model,
        ]);
    }

    /**
     * Finds the PostComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return PostComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostComment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the PostType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return PostType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPostType($id)
    {
        if (($model = PostType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
    protected function findPost($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}