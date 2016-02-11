<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\components\Json;
use common\models\Module;
use common\models\search\Module as ModuleSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class ModuleController, controlling the actions for Module model.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.2.0
 */
class ModuleController extends Controller
{
    private $_dir;
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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'bulk-action'],
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Module models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Module model.
     * Module zip uploaded to temporary directory and extracted there.
     * Check the module directory and move the first directory of the extracted module.
     * If the module configuration is valid, save the module, if not remove the module.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $errors = [];
        $model = new Module(['scenario' => 'create']);

        if (!is_dir($this->_dir)) {
            FileHelper::createDirectory($this->_dir, 0755);
        }

        if (!is_dir($this->_tmp)) {
            FileHelper::createDirectory($this->_tmp, 0755);
        }

        if (($model->file = UploadedFile::getInstance($model, 'file')) && $model->validate(['file'])) {
            $tmpPath = $this->_tmp . $model->file->name;

            if (!$model->file->saveAs($tmpPath)) {
                return $this->render('create', [
                    'model' => $model,
                    'error' => [Yii::t('writesdown', 'Failed to move uploaded file.')],
                ]);
            }

            $zipArchive = new \ZipArchive();
            $zipArchive->open($tmpPath);

            if (!$zipArchive->extractTo($this->_tmp)) {
                $zipArchive->close();
                FileHelper::removeDirectory($this->_tmp);

                return $this->render('create', [
                    'model' => $model,
                    'error' => [Yii::t('writesdown', 'Failed to extract file.')],
                ]);
            }

            $baseDir = substr($zipArchive->getNameIndex(0), 0, strpos($zipArchive->getNameIndex(0), '/'));
            $zipArchive->close();
            unlink($tmpPath);
            $configPath = $this->_tmp . $baseDir . '/config/main.php';

            if (!is_file($configPath)) {
                FileHelper::removeDirectory($this->_tmp);

                return $this->render('create', [
                    'model' => $model,
                    'error' => [Yii::t('writesdown', 'File configuration does not exist.')],
                ]);
            }

            $config = require($configPath);
            $model->setAttributes($config);
            $model->setAttributes(['directory' => $baseDir, 'status' => Module::STATUS_NOT_ACTIVE]);

            if ($model->validate(['directory'])) {
                rename($this->_tmp . $baseDir, $this->_dir . $baseDir);
            }

            FileHelper::removeDirectory($this->_tmp);

            if (!isset($model->config['frontend']['class']) && !isset($model->config['backend']['class'])) {
                $errors[] = Yii::t('writesdown', 'Invalid config.');
            }

            if (isset($model->config['backend']['class']) && !class_exists($model->config['backend']['class'])) {
                $errors[] = Yii::t('writesdown', 'Invalid backend config.');
            }

            if (isset($model->config['frontend']['class']) && !class_exists($model->config['frontend']['class'])) {
                $errors[] = Yii::t('writesdown', 'Invalid frontend config.');
            }

            $model->config = Json::encode($model->config);

            if (!$errors && $model->validate(['name', 'title', 'config', 'directory']) && $model->save(false)) {
                Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', 'Module successfully installed'));

                return $this->redirect(['index']);
            } else {
                if (!$model->hasErrors('directory')) {
                    FileHelper::removeDirectory($this->_dir . $baseDir);
                }

                $errors = ArrayHelper::merge($errors, $model->getFirstErrors());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'error' => $errors,
        ]);
    }

    /**
     * Updates an existing Module model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->config = Json::encode($model->config);
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Module model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing Module model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $path = Yii::getAlias($this->_dir . $model->directory);

        // Delete module and its directory
        if ($model->delete()) {
            FileHelper::removeDirectory($path);
        }

        return $this->redirect(['index']);
    }

    /**
     * Bulk action for Module triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') === 'active') {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->findModel($id)->updateAttributes(['status' => Module::STATUS_ACTIVE]);
            }
        } elseif (Yii::$app->request->post('action') === 'not-active') {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $this->findModel($id)->updateAttributes(['status' => Module::STATUS_NOT_ACTIVE]);
            }
        } elseif (Yii::$app->request->post('action') === 'deleted') {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $path = Yii::getAlias($this->_dir . $model->directory);
                if ($model->delete()) {
                    FileHelper::removeDirectory($path);
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->_dir = Yii::getAlias('@modules/');
            $this->_tmp = Yii::getAlias('@common/tmp/modules/');

            return true;
        }

        return false;
    }

    /**
     * Finds the Module model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Module the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Module::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
