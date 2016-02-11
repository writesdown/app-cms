<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\models\Post;
use common\models\PostComment;
use common\models\PostType;
use common\models\search\PostComment as PostCommentSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PostCommentController, controlling the actions for PostComment model.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
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
                        'allow' => true,
                        'roles' => ['editor'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-action' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Lists all PostComment models on specific post type.
     * If there is post_id the action will generate list of all PostComment models based on post_id.
     *
     * @param integer $posttype Post type ID
     * @param null|integer $post Post ID
     * @throws \yii\web\NotFoundHttpException
     * @return string
     */
    public function actionIndex($posttype, $post = null)
    {
        $postId = null;
        $postType = $this->findPostType($posttype);

        if ($post) {
            $post = $this->findPost($post);
            $postId = $post->id;
        }

        $searchModel = new PostCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $posttype, $postId);

        return $this->render('index', [
            'post' => $post,
            'postType' => $postType,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Updates an existing PostComment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->date = Yii::$app->formatter->asDatetime($model->date, 'php:Y-m-d H:i:s');
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
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
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $post = $model->commentPost;

        if ($model->delete()) {
            if (!$model->parent) {
                $post->updateAttributes(['comment_count', --$post->comment_count]);
            }
            PostComment::deleteAll(['parent' => $model->id]);
        }

        return $this->redirect(['index', 'posttype' => $post->type]);
    }

    /**
     * Bulk action for PostComment triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') === PostComment::STATUS_APPROVED) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->findModel($id)->updateAttributes(['status' => PostComment::STATUS_APPROVED]);
            }
        } elseif (Yii::$app->request->post('action') === PostComment::STATUS_NOT_APPROVED) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->findModel($id)->updateAttributes(['status' => PostComment::STATUS_NOT_APPROVED]);
            }
        } elseif (Yii::$app->request->post('action') === PostComment::STATUS_TRASHED) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->findModel($id)->updateAttributes(['status' => PostComment::STATUS_TRASHED]);
            }
        } elseif (Yii::$app->request->post('action') === 'delete') {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $post = $model->commentPost;
                if ($model->delete()) {
                    if (!$model->parent) {
                        $post->updateAttributes(['comment_count', --$post->comment_count]);
                    }
                    PostComment::deleteAll(['parent' => $model->id]);
                }
            }
        }
    }

    /**
     * Reply an existing PostComment model.
     * If reply is successful, the browser will be redirected to 'update' page.
     *
     * @param int $id Find PostComment model based on id as parent.
     * @return string
     */
    public function actionReply($id)
    {
        $commentParent = $this->findModel($id);
        $model = new PostComment(['scenario' => 'reply']);

        if ($model->load(Yii::$app->request->post())) {
            $model->post_id = $commentParent->post_id;
            $model->parent = $commentParent->id;
            if ($model->save()) {
                $this->redirect(['post-comment/update', 'id' => $model->id]);
            }
        }

        return $this->render('reply', [
            'commentParent' => $commentParent,
            'model' => $model,
        ]);
    }

    /**
     * Finds the PostComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return PostComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostComment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the PostType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return PostType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPostType($id)
    {
        if (($model = PostType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPost($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
