<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\models\Media;
use common\models\MediaComment;
use common\models\search\MediaComment as MediaCommentSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class MediaCommentController, controlling the actions for Media model.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class MediaCommentController extends Controller
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
     * Lists all MediaComment models.
     * If there is media, the action will generate list of all MediaComment models based on Media ID.
     *
     * @param null|integer $media Media ID
     * @return mixed
     */
    public function actionIndex($media = null)
    {
        $mediaId = null;

        if ($media) {
            $media = $this->findMedia($media);
            $mediaId = $media->id;
        }

        $searchModel = new MediaCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $mediaId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'media' => $media,
        ]);
    }

    /**
     * Updates an existing MediaComment model.
     * If update is successful, the browser will be redirected to the 'update' page.
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
     * Deletes an existing MediaComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $media = $model->commentMedia;

        if ($model->delete()) {
            if (!$model->parent) {
                $media->updateAttributes(['comment_count', --$media->comment_count]);
            }
            MediaComment::deleteAll(['parent' => $model->id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Bulk action for MediaComment triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') === MediaComment::STATUS_APPROVED) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->findModel($id)->updateAttributes(['status' => MediaComment::STATUS_APPROVED]);
            }
        } elseif (Yii::$app->request->post('action') === MediaComment::STATUS_NOT_APPROVED) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->findModel($id)->updateAttributes(['status' => MediaComment::STATUS_NOT_APPROVED]);
            }
        } elseif (Yii::$app->request->post('action') === MediaComment::STATUS_TRASHED) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->findModel($id)->updateAttributes(['status' => MediaComment::STATUS_TRASHED]);
            }
        } elseif (Yii::$app->request->post('action') === 'delete') {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $media = $model->commentMedia;
                if ($model->delete()) {
                    if (!$model->parent) {
                        $media->updateAttributes(['comment_count', --$media->comment_count]);
                    }
                    MediaComment::deleteAll(['parent' => $model->id]);
                }
            }
        }
    }

    /**
     * Reply an existing MediaComment model.
     * If reply is successful, the browser will be redirected to 'update' page.
     *
     * @param int $id Find MediaComment model based on id as its parent
     * @return string
     */
    public function actionReply($id)
    {
        $commentParent = $this->findModel($id);
        $model = new MediaComment(['scenario' => 'reply']);

        if ($model->load(Yii::$app->request->post())) {
            $model->setAttributes([
                'media_id' => $commentParent->media_id,
                'parent' => $commentParent->id,
            ]);
            if ($model->save()) {
                $this->redirect(['/media-comment/update', 'id' => $model->id]);
            }
        }

        return $this->render('reply', [
            'commentParent' => $commentParent,
            'model' => $model,
        ]);
    }

    /**
     * Finds the MediaComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return MediaComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MediaComment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMedia($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
