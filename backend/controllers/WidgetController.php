<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\components\Json;
use common\models\Widget;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * WidgetController, controlling the actions for Widget model.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.2.0
 */
class WidgetController extends Controller
{
    /**
     * @var string Path to widget directory.
     */
    private $_dir;

    /**
     * @var string Path to temporary directory of widget.
     */
    private $_tmp;

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
                            'delete',
                            'ajax-activate',
                            'ajax-update',
                            'ajax-delete',
                            'ajax-save-order',
                        ],
                        'allow'   => true,
                        'roles'   => ['administrator'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'ajax-activate' => ['post'],
                    'ajax-update'   => ['post'],
                    'ajax-delete'   => ['post'],
                ],
            ],
        ];
    }

    /**
     * Scan widget directory to get list of all available widgets and list all active widgets.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $config = [];
        $active = [];
        $available = [];
        $spaces = isset(Yii::$app->params['widget']) ? Yii::$app->params['widget'] : [];

        if (!is_dir($this->_dir)) {
            FileHelper::createDirectory($this->_dir, 0755);
        }

        foreach (scandir($this->_dir) as $widget) {
            if (is_dir($this->_dir . $widget) && $widget !== '.' && $widget !== '..') {
                $configPath = $this->_dir . $widget . '/config/main.php';
                if (is_file($configPath)) {
                    $config = require($configPath);
                    $config['directory'] = $widget;
                }
                $available[$widget] = $config;
            }
        }

        foreach ($spaces as $space) {
            $model = Widget::find()
                ->where(['location' => $space['location']])
                ->orderBy(['order' => SORT_ASC])
                ->all();
            $active[$space['location']] = $model;
        }

        return $this->render('index', [
            'active' => $active,
            'available' => $available,
            'spaces' => $spaces,
        ]);
    }

    /**
     * Register new widget.
     * Widget zip uploaded to temporary directory and extracted there.
     * Check the widget directory and move the first directory of the extracted widget.
     * If the widget configuration is valid, save the widget, if not remove the widget.
     * If registration is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $errors = [];
        $model = new Widget(['scenario' => 'upload']);

        if (!is_dir($this->_tmp)) {
            FileHelper::createDirectory($this->_tmp, 0755);
        }

        if (!is_dir($this->_dir)) {
            FileHelper::createDirectory($this->_dir, 0755);
        }

        if (($model->file = UploadedFile::getInstance($model, 'file')) && $model->validate(['file'])) {
            $tmpPath = $this->_tmp . $model->file->name;

            if (!$model->file->saveAs($tmpPath)) {
                return $this->render('create', [
                    'model' => $model,
                    'errors' => [Yii::t('writesdown', 'Failed to move uploaded file')],
                ]);
            }

            $zipArchive = new \ZipArchive();
            $zipArchive->open($tmpPath);

            if (!$zipArchive->extractTo($this->_tmp)) {
                $zipArchive->close();
                FileHelper::removeDirectory($this->_tmp);

                return $this->render('create', [
                    'model' => $model,
                    'errors' => [Yii::t('writesdown', 'Failed to extract file.')],
                ]);
            }

            $baseDir = substr($zipArchive->getNameIndex(0), 0, strpos($zipArchive->getNameIndex(0), '/'));
            $zipArchive->close();
            $configPath = $this->_tmp . $baseDir . '/config/main.php';

            if (!is_file($configPath)) {
                FileHelper::removeDirectory($this->_tmp);

                return $this->render('create', [
                    'model' => $model,
                    'errors' => [Yii::t('writesdown', 'File configuration does not exist.')],
                ]);
            }

            $config = require($configPath);

            if (is_dir($this->_dir . $baseDir)) {
                $errors['dirExist'] = Yii::t('writesdown', 'Widget with the same directory already exist.');
            } else {
                rename($this->_tmp . $baseDir, $this->_dir . $baseDir);
            }

            FileHelper::removeDirectory($this->_tmp);

            if (!isset($config['title'])
                || !(isset($config['config']['class']) && class_exists($config['config']['class']))
            ) {
                $errors[] = Yii::t('writesdown', 'Invalid configuration.');
            }

            if (!$errors) {
                Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', 'Widget successfully installed.'));

                return $this->redirect(['index']);
            } else {
                if (!$errors['dirExist']) {
                    FileHelper::removeDirectory($this->_dir . $baseDir);
                }

                $errors = ArrayHelper::merge($errors, $model->getFirstErrors());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'errors' => $errors,
        ]);
    }

    /**
     * Delete widget and activated widgets.
     *
     * @param $id string
     *
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        FileHelper::removeDirectory($this->_dir . $id);
        Widget::deleteAll(['directory' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Activated widget via ajax
     *
     * @param $id string
     *
     * @return null|string
     */
    public function actionAjaxActivate($id)
    {
        $model = new Widget(['scenario' => 'activate']);
        if ($model->load(Yii::$app->request->post())) {
            $configPath = $this->_dir . $id . '/config/main.php';
            $config = require($configPath);

            // Set attribute of model
            $model->setAttributes($config);
            $model->setAttributes([
                'directory' => $id,
                'config' => Json::encode($model->config),
                'order' => Widget::find()->where(['location' => $model->location])->count(),
            ]);

            if ($model->save()) {
                return $this->renderPartial('_active', [
                    'active' => $model,
                    'available' => [$model->directory => $config],
                ]);
            }
        }

        return null;
    }

    /**
     * Update activated widget via ajax.
     *
     * @param $id integer
     */
    public function actionAjaxUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->config = Json::encode($model->config);
            $model->save();
        }
    }

    /**
     * Delete active widget via ajax.
     *
     * @param $id integer
     */
    public function actionAjaxDelete($id)
    {
        $this->findModel($id)->delete();
    }

    /**
     * Save order for widget.
     */
    public function actionAjaxSaveOrder()
    {
        if ($ids = Yii::$app->request->post('ids')) {
            foreach ($ids as $order => $id) {
                $this->findModel($id)->updateAttributes(['order' => $order]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (in_array($this->action->id, ['ajax-activate', 'ajax-update', 'ajax-delete', 'ajax-save-order'])) {
            $this->enableCsrfValidation = false;
        }

        if (parent::beforeAction($action)) {
            $this->_dir = Yii::getAlias('@widgets/');
            $this->_tmp = Yii::getAlias('@common/tmp/widgets/');

            return true;
        }

        return false;
    }

    /**
     * Finds the Widget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Widget the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Widget::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
