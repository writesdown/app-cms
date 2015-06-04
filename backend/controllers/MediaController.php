<?php
/**
 * @file      MediaController.php.
 * @date      6/4/2015
 * @time      5:02 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/* UPLOAD HANDLER */
use common\components\MediaUploadHandler;

/* MODEL */
use common\models\Post;
use common\models\Media;
use common\models\search\Media as MediaSearch;
use common\models\Option;

/**
 * MediaController implements the CRUD actions for Media model.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MediaController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'update', 'delete', 'bulk-action', 'ajax-upload',
                            'ajax-update', 'ajax-delete', 'get-json', 'get-pagination', 'popup',
                            'editor-insert', 'field-insert'
                        ],
                        'allow'   => true,
                        'roles'   => ['author']
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'        => ['post'],
                    'bulk-action'   => ['post'],
                    'ajax-upload'   => ['post'],
                    'ajax-update'   => ['post'],
                    'ajax-delete'   => ['post'],
                    'editor-insert' => ['post'],
                    'field-insert'  => ['post'],
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
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Media(['scenario' => 'upload']);

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $metadata = $model->getMeta('metadata');

        if ($model->load(Yii::$app->request->post())) {
            $model->media_date = Yii::$app->formatter->asDatetime($model->media_date, 'php:Y-m-d H:i:s');

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', 'Media successfully saved.'));

                return $this->refresh();
            }

        }

        return $this->render('update', [
            'model'    => $model,
            'metadata' => $metadata
        ]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $uploadHandler = new MediaUploadHandler(null, false);
        $uploadHandler->delete($id, false);

        return $this->redirect(['index']);
    }

    /**
     * Bulk action for Media triggered when button "Apply" is clicked.
     * The action depends on value of the dropdown beside the button.
     * Only accept POST method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') == 'delete') {
            foreach (Yii::$app->request->post('ids') as $id) {
                $uploadHandler = new MediaUploadHandler(null, false);
                $uploadHandler->delete($id, false);
            }
        }
    }

    /**
     * Upload file and save to database.
     *
     * @return array
     */
    public function actionAjaxUpload()
    {
        $versions = [
            'large'     => [
                'max_width'  => Option::get('large_width'),
                'max_height' => Option::get('large_height'),
            ],
            'medium'    => [
                'max_width'  => Option::get('medium_width'),
                'max_height' => Option::get('medium_height'),
            ],
            'thumbnail' => [
                'max_width'  => Option::get('thumbnail_width'),
                'max_height' => Option::get('thumbnail_height'),
                'crop'       => 1
            ]
        ];
        $uploadHandler = new MediaUploadHandler(['versions' => $versions], false);
        $uploadHandler->post();
    }

    /**
     * Update attribute of Media by ajax request
     */
    public function actionAjaxUpdate()
    {
        if ($model = $this->findModel(Yii::$app->request->post('id'))) {
            if (!Yii::$app->user->can('editor') && $model->media_author !== Yii::$app->user->id) {
                throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
            }
            $model->{Yii::$app->request->post('attribute')} = Yii::$app->request->post('attribute_value');
            if ($model->save()) {
                echo Yii::t('writesdown', 'Updated, {attribute}: {attribute_value}', [
                    'attribute'       => Yii::$app->request->post('attribute'),
                    'attribute_value' => Yii::$app->request->post('attribute_value')
                ]);
            };

        }
    }

    /**
     * Delete media and its files based on media primary key.
     *
     * @param $id
     *
     * @return array
     */
    public function actionAjaxDelete($id)
    {
        $uploadHandler = new MediaUploadHandler(null, false);
        $uploadHandler->delete($id);
    }

    /**
     * Get json data from Media
     *
     * @param int|null $id
     */
    public function actionGetJson($id = null)
    {
        $uploadHandler = new MediaUploadHandler(null, false);
        $uploadHandler->get($id);
    }

    /**
     * Render pagination for popup
     */
    public function actionGetPagination()
    {
        $uploadHandler = new MediaUploadHandler(null, false);
        $pages = $uploadHandler->getPages();

        return $this->renderPartial('pagination', ['pages' => $pages]);
    }

    /**
     * Render file browser for editor and file input
     *
     * @param int|null $post_id
     * @param bool     $editor
     *
     * @throws \yii\web\NotFoundHttpException
     * @return string
     */
    public function actionPopup($post_id = null, $editor = false)
    {
        $this->layout = "blank";
        $model = new Media(['scenario' => 'upload']);

        if ($post_id) {
            if ($post = Post::findOne($post_id)) {
                return $this->render('popup', [
                    'post'   => $post,
                    'model'  => $model,
                    'editor' => $editor,
                ]);
            } else {
                throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
            }
        }

        return $this->render('popup', [
            'model'  => $model,
            'editor' => $editor,
        ]);
    }

    /**
     * Insert file to editor
     */
    public function actionEditorInsert()
    {
        if (Yii::$app->request->post("media")) {
            foreach (Yii::$app->request->post("media") as $postMedia) {

                if ($postMedia['media_type'] == 'image') {
                    $result = $this->getMediaImage($postMedia);
                } else if ($postMedia['media_type'] == 'video') {
                    $result = $this->getMediaVideo($postMedia);
                } else if ($postMedia['media_type'] == 'audio') {
                    $result = $this->getMediaAudio($postMedia);
                } else
                    $result = $this->getMediaFile($postMedia);

                echo $result;
            }
        }
    }

    /**
     * Insert url of media
     */
    public function actionFieldInsert()
    {
        if ($post = Yii::$app->request->post()) {
            foreach ($post['media'] as $postMedia) {
                $media = $this->findModel($postMedia['id']);
                $metadata = $media->getMeta('metadata');
                if ($postMedia['media_type'] === 'image') {
                    echo $media->getUploadUrl() . $metadata['media_versions'][ $postMedia['media_size'] ]['url'];
                } else {
                    echo $media->getUploadUrl() . $metadata['media_versions']['full']['url'];
                }
                break;
            }
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
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param array $postMedia
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getMediaImage($postMedia)
    {
        $result = '';
        $media = $this->findModel($postMedia['id']);
        $metadata = $media->getMeta('metadata');

        $image = Html::img($media->getUploadUrl() . $metadata['media_versions'][ $postMedia['media_size'] ]['url'], [
                'class'   => $postMedia['media_alignment'] . ' media-image media-' . $media->id,
                'width'   => $metadata['media_versions'][ $postMedia['media_size'] ]['width'],
                'height'  => $metadata['media_versions'][ $postMedia['media_size'] ]['height'],
                'data-id' => $media->id,
                'alt'     => $media->media_title
            ]) . "\n";

        if ($media->media_excerpt) {
            $result .= Html::beginTag('div', [
                    'class' => $postMedia['media_alignment'],
                    'style' => 'width: ' . $metadata['media_versions'][ $postMedia['media_size'] ]['width'] . 'px'
                ]) . "\n";
        }

        if ($postMedia['media_link_to_value']) {
            $result .= Html::beginTag('a', [
                    'href'  => $postMedia['media_link_to_value'],
                    'class' => $postMedia['media_alignment'],
                ]) . "\n";
        }

        $result .= $image;

        if ($postMedia['media_link_to_value']) {
            $result .= Html::endTag('a') . "\n";
        }

        if ($media->media_excerpt) {
            $result .= Html::tag('div', $media->media_excerpt, [
                    'class' => 'media-caption-text'
                ]) . "\n";
            $result .= Html::endTag('div') . "\n";
        }

        return $result;
    }

    /**
     * @param array $postMedia
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getMediaVideo($postMedia)
    {
        $media = $this->findModel($postMedia['id']);
        $metadata = $media->getMeta('metadata');
        $result = Html::beginTag('video', [
                'controls' => true,
                'class'    => 'media-video media-' . $media->id,
            ]) . "\n";
        $result .= Html::tag('source', '', [
                'src'  => $media->getUploadUrl() . $metadata['media_versions']['full']['url'],
                'type' => $media->media_mime_type
            ]) . "\n";
        $result .= 'Your browser does not support the <code>video</code> element.' . "\n";
        $result .= Html::endTag('video') . "\n";

        return $result;
    }

    /**
     * @param array $postMedia
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getMediaAudio($postMedia)
    {
        $media = $this->findModel($postMedia['id']);
        $metadata = $media->getMeta('metadata');
        $result = Html::beginTag('audio', [
                'controls' => true,
                'class'    => 'media-audio media-' . $media->id,
            ]) . "\n";
        $result .= Html::tag('source', '', [
                'src'  => $media->getUploadUrl() . $metadata['media_versions']['full']['url'],
                'type' => $media->media_mime_type
            ]) . "\n";
        $result .= 'Your browser does not support the <code>video</code> element.' . "\n";
        $result .= Html::endTag('audio') . "\n";

        return $result;
    }

    /**
     * @param array $postMedia
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getMediaFile($postMedia)
    {
        $model = $this->findModel($postMedia['id']);
        $result = Html::a($model->media_title, $postMedia['media_link_to_value'], [
            'class' => 'media-file media-' . $model->id
        ]);

        return $result;
    }
}