<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\components\MediaUploadHandler;
use common\models\Media;
use common\models\Option;
use common\models\search\Media as MediaSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * MediaController, controlling the actions for for Media model.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class MediaController extends Controller
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
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'bulk-action',
                            'ajax-upload',
                            'ajax-update',
                            'ajax-delete',
                        ],
                        'allow' => true,
                        'roles' => ['author'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-action' => ['post'],
                    'ajax-upload' => ['post'],
                    'ajax-update' => ['post'],
                    'ajax-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Media models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Media model.
     * If creation is successful, the browser will display thumbnail and options to the 'create' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->render('create', ['model' => new Media(['scenario' => 'upload'])]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->getPermission($model);
        $metadata = $model->getMeta('metadata');

        if ($model->load(Yii::$app->request->post())) {
            $model->date = Yii::$app->formatter->asDatetime($model->date, 'php:Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', 'Media successfully saved.'));

                return $this->redirect(['update', 'id' => $id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->getPermission($this->findModel($id));
        $uploadHandler = new MediaUploadHandler(null, false);
        $uploadHandler->delete($id, MediaUploadHandler::NOT_PRINT_RESPONSE);

        return $this->redirect(['index']);
    }

    /**
     * Bulk action for Media triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') == 'delete') {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->getPermission($this->findModel($id));
                $uploadHandler = new MediaUploadHandler(null, false);
                $uploadHandler->delete($id, MediaUploadHandler::NOT_PRINT_RESPONSE);
            }
        }
    }

    /**
     * Upload media file and store it to database.
     * Media versions can be set from application params.
     *
     * @return array
     */
    public function actionAjaxUpload()
    {
        $versions = [
            'large' => [
                'max_width' => Option::get('large_width'),
                'max_height' => Option::get('large_height'),
            ],
            'medium' => [
                'max_width' => Option::get('medium_width'),
                'max_height' => Option::get('medium_height'),
            ],
            'thumbnail' => [
                'max_width' => Option::get('thumbnail_width'),
                'max_height' => Option::get('thumbnail_height'),
                'crop' => 1,
            ],
        ];

        // Merge image versions with app params
        if (isset(Yii::$app->params['media']['versions']) && is_array(Yii::$app->params['media']['versions'])) {
            $versions = ArrayHelper::merge($versions, Yii::$app->params['media']['versions']);
        }

        $uploadHandler = new MediaUploadHandler([
            'versions' => $versions,
            'user_dirs' => Option::get('uploads_username_based'),
            'year_month_dirs' => Option::get('uploads_yearmonth_based'),
        ], MediaUploadHandler::NOT_PRINT_RESPONSE);
        $uploadHandler->post();
    }

    /**
     * Update attributes of Media model via AJAX request.
     */
    public function actionAjaxUpdate()
    {
        if ($model = $this->findModel(Yii::$app->request->post('id'))) {
            $this->getPermission($model);
            $model->{Yii::$app->request->post('attribute')} = Yii::$app->request->post('value');
            $model->save();
        }
    }

    /**
     * Delete Media model and its files based on media primary key.
     *
     * @param $id
     * @return array
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionAjaxDelete($id)
    {
        $this->getPermission($this->findModel($id));
        $uploadHandler = new MediaUploadHandler(null, MediaUploadHandler::NOT_PRINT_RESPONSE);
        $uploadHandler->delete($id);
    }

    /**
     * Get permission to access model by current user.
     * If the user does not obtain the permission, a 403 exeption will be thrown.
     *
     * @param $model Media
     * @throws ForbiddenHttpException
     */
    public function getPermission($model)
    {
        if (!$model->getPermission()) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
