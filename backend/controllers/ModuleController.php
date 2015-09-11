<?php

namespace backend\controllers;

use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\Json;

/* MODELS */
use common\models\Module;
use common\models\search\Module as ModuleSearch;

/**
 * ModuleController implements the CRUD actions for Module model.
 */
class ModuleController extends Controller
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
                        'actions' => ['index', 'create', 'update','view', 'delete', 'bulk-action'],
                        'allow'   => true,
                        'roles'   => ['administrator']
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
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
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Module model.
     * If creation is successful, the browser will be redirected to the 'view' page. Check:
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Module();
        $moduleDir = Yii::getAlias('@modules/');
        $moduleTempDir = Yii::getAlias('@common/temp/modules/');

        if (!is_dir($moduleDir)) {
            FileHelper::createDirectory($moduleDir, 0755);
        }

        if (!is_dir($moduleTempDir)) {
            FileHelper::createDirectory($moduleTempDir, 0755);
        }

        if (Yii::$app->request->isPost) {
            $model->module_file = UploadedFile::getInstance($model, 'module_file');

            // Validate only module_file
            if ($model->validate(['module_file'])) {
                $moduleTempPath = $moduleTempDir . $model->module_file->name;

                // Move module_file to temp directory
                if ($model->module_file->saveAs($moduleTempPath)) {
                    $zipArchive = new \ZipArchive();
                    $zipArchive->open($moduleTempPath);

                    /* Extract to temp first*/
                    if ($zipArchive->extractTo($moduleTempDir)) {
                        $baseDir = substr($zipArchive->getNameIndex(0), 0, strpos($zipArchive->getNameIndex(0), '/'));

                        // Close and delete zip
                        $zipArchive->close();
                        unlink($moduleTempPath);

                        $configPath = $moduleTempDir . $baseDir . '/config/main.php';

                        if (is_file($configPath)) {
                            $moduleConfig = require($configPath);
                            $model->setAttributes($moduleConfig);
                            $model->module_dir = $baseDir;
                            $model->module_status = 0;

                            // Validate module_name and module_dir
                            if ($model->validate(['module_name', 'module_dir'])) {

                                // Move module to module directory
                                rename($moduleTempDir . $baseDir, $moduleDir . $baseDir);

                                // Check configuration
                                if (isset($model->module_config['frontend']['class']) || isset($model->module_config['backend']['class'])) {

                                    // Check class whether exist or not
                                    if (isset($model->module_config['frontend']['class']) && !class_exists($model->module_config['frontend']['class'])) {
                                        FileHelper::removeDirectory($moduleDir . $baseDir);
                                        Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', 'Invalid configuration.'));

                                        return $this->render('create', [
                                            'model' => $model,
                                        ]);
                                    }

                                    // Check class whether exist or not
                                    if (isset($model->module_config['frontend']['class']) && !class_exists($model->module_config['frontend']['class'])) {
                                        FileHelper::removeDirectory($moduleDir . $baseDir);
                                        Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', 'Invalid configuration.'));

                                        return $this->render('create', [
                                            'model' => $model,
                                        ]);
                                    }

                                    // Convert module_config to JSON
                                    $model->module_config = Json::encode($model->module_config);

                                    if ($model->validate(['module_title', 'module_config']) && $model->save(false)) {
                                        Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', 'Module successfully installed'));

                                        return $this->redirect(['index']);
                                    } else {
                                        FileHelper::removeDirectory($moduleDir . $baseDir);
                                        Yii::$app->getSession()->setFlash('danger', $model->getFirstErrors());
                                    }

                                } else {
                                    FileHelper::removeDirectory($moduleDir . $baseDir);
                                    Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', 'Invalid configuration.'));
                                }

                            } else {
                                FileHelper::removeDirectory($moduleTempDir);
                                Yii::$app->getSession()->setFlash('danger', $model->getFirstErrors());
                            }

                        } else {
                            FileHelper::removeDirectory($moduleTempDir);
                            Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', 'File configuration does not exist.'));
                        }
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Module model.
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
            $model->module_config = Json::encode($model->module_config);
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
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $modulePath = Yii::getAlias('@modules/' . $model->module_dir);

        // Delete module and its directory
        if ($model->delete()) {
            FileHelper::removeDirectory($modulePath);
        }

        return $this->redirect(['index']);
    }

    /**
     * Action bulk for modules.
     *
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionBulkAction()
    {
        if (($action = Yii::$app->request->post('action')) && ($ids = Yii::$app->request->post('ids'))) {
            if ($action === 'activated') {
                foreach ($ids as $id) {
                    $this->findModel($id)->updateAttributes(['module_status' => 1]);
                }
            } else if ($action === 'unactivated') {
                foreach ($ids as $id) {
                    $this->findModel($id)->updateAttributes(['module_status' => 0]);
                }
            } else if ($action === 'deleted') {
                foreach ($ids as $id) {
                    $model = $this->findModel($id);
                    $modulePath = Yii::getAlias('@modules/' . $model->module_dir);

                    // Delete module and its directory
                    if ($model->delete()) {
                        FileHelper::removeDirectory($modulePath);
                    }
                }
            }
        }
    }

    /**
     * Finds the Module model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Module the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Module::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
