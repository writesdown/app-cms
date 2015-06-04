<?php
/**
 * @file    MediaCommentController.php.
 * @date    6/4/2015
 * @time    3:49 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* MODEL */
use common\models\Media;
use common\models\MediaComment;
use common\models\search\MediaComment as MediaCommentSearch;

/**
 * Class MediaCommentController
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MediaCommentController extends Controller{
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
     * Lists all MediaComment models.
     *
     * @param null $media_id
     *
     * @return mixed
     */
    public function actionIndex($media_id = null)
    {
        $media = null;

        if ($media_id) {
            $media = $this->findMedia($media_id);
        }

        $searchModel = new MediaCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $media_id);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'media'        => $media
        ]);
    }

    /**
     * Updates an existing MediaComment model.
     * If update is successful, the browser will be redirected to the 'update' page.
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
     * Deletes an existing MediaComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $media = $model->commentMedia;
        if ($model->delete()) {
            if (!$model->comment_parent) {
                $media->media_comment_count--;
                $media->save();
            }
            MediaComment::deleteAll(['comment_parent' => $model->id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Bulk action for media comments
     */
    public function actionBulkAction()
    {
        if ($_POST['action'] === 'delete') {
            foreach ($_POST['ids'] as $id) {
                $model = $this->findModel($id);
                $media = $model->commentMedia;
                if ($model->delete()) {
                    if (!$model->comment_parent) {
                        $media->media_comment_count--;
                        $media->save();
                    }
                    MediaComment::deleteAll(['comment_parent' => $model->id]);
                }
            }
        } else if ($_POST['action'] === MediaComment::COMMENT_APPROVED) {
            foreach ($_POST['ids'] as $id) {
                $this->findModel($id)->updateAttributes(['comment_approved' => 'approved']);
            }
        } else if ($_POST['action'] === MediaComment::COMMENT_UNAPPROVED) {
            foreach ($_POST['ids'] as $id) {
                $this->findModel($id)->updateAttributes(['comment_approved' => 'unapproved']);
            }
        } else if ($_POST['action'] === MediaComment::COMMENT_TRASH) {
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
        $model = new MediaComment(['scenario' => 'reply']);

        if ($model->load(Yii::$app->request->post())) {

            $model->comment_media_id = $commentParent->comment_media_id;
            $model->comment_parent = $commentParent->id;

            if ($model->save()) {
                $this->redirect(['/media-comment/update', 'id' => $model->id]);
            }
        }

        return $this->render('reply', [
            'commentParent' => $commentParent,
            'model'         => $model,
        ]);
    }

    /**
     * Finds the MediaComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return MediaComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MediaComment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMedia($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
} 