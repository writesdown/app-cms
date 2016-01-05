<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\components\Json;
use common\models\Module;
use common\models\search\Module as ModuleSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class ModuleController, controlling the actions for Module model.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'bulk-action'],
                        'allow'   => true,
                        'roles'   => ['administrator'],
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
     * Module zip uploaded to temporary directory and extracted there.
     * Check the module directory and move the first directory of the extracted module.
     * If the module configuration is valid, save the module, if not remove the module.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Module(['scenario' => 'create']);
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
                            $config = require($configPath);
                            $model->setAttributes($config);
                            $model->module_dir = $baseDir;
                            $model->module_status = 0;

                            // Validate module_name and module_dir
                            if ($model->validate(['module_name', 'module_dir'])) {

                                // Move module to module directory
                                rename($moduleTempDir . $baseDir, $moduleDir . $baseDir);

                                // Check configuration
                                if (isset($model->module_config['frontend']['class'])
                                    || isset($model->module_config['backend']['class'])
                                ) {
                                    // Check class whether exist or not
                                    if (isset($model->module_config['backend']['class'])
                                        && !class_exists($model->module_config['backend']['class'])
                                    ) {
                                        FileHelper::removeDirectory($moduleDir . $baseDir);
                                        Yii::$app->getSession()->setFlash(
                                            'danger',
                                            Yii::t('writesdown', 'Invalid configuration.')
                                        );

                                        return $this->render('create', [
                                            'model' => $model,
                                        ]);
                                    }

                                    // Check class whether exist or not
                                    if (isset($model->module_config['frontend']['class'])
                                        && !class_exists($model->module_config['frontend']['class'])
                                    ) {
                                        FileHelper::removeDirectory($moduleDir . $baseDir);
                                        Yii::$app->getSession()->setFlash(
                                            'danger',
                                            Yii::t('writesdown', 'Invalid configuration.')
                                        );

                                        return $this->render('create', [
                                            'model' => $model,
                                        ]);
                                    }

                                    // Encode module_config to JSON
                                    $model->module_config = Json::encode($model->module_config);

                                    if ($model->validate(['module_title', 'module_config'])
                                        && $model->save(false)
                                    ) {
                                        Yii::$app->getSession()->setFlash(
                                            'success',
                                            Yii::t('writesdown', 'Module successfully installed')
                                        );

                                        return $this->redirect(['index']);
                                    } else {
                                        FileHelper::removeDirectory($moduleDir . $baseDir);
                                        Yii::$app->getSession()->setFlash('danger', $model->getFirstErrors());
                                    }
                                } else {
                                    FileHelper::removeDirectory($moduleDir . $baseDir);
                                    Yii::$app->getSession()->setFlash(
                                        'danger',
                                        Yii::t('writesdown', 'Invalid configuration.')
                                    );
                                }
                            } else {
                                FileHelper::removeDirectory($moduleTempDir);
                                Yii::$app->getSession()->setFlash('danger', $model->getFirstErrors());
                            }
                        } else {
                            FileHelper::removeDirectory($moduleTempDir);
                            Yii::$app->getSession()->setFlash(
                                'danger',
                                Yii::t('writesdown', 'File configuration does not exist.')
                            );
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
     *
     * @param integer $id
     *
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
     * Bulk action for Module triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') === 'activated') {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->updateAttributes(['module_status' => 1]);
            }
        } elseif (Yii::$app->request->post('action') === 'unactivated') {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->updateAttributes(['module_status' => 0]);
            }
        } elseif (Yii::$app->request->post('action') === 'deleted') {
            foreach (Yii::$app->request->post('ids') as $id) {
                $model = $this->findModel($id);
                $modulePath = Yii::getAlias('@modules/' . $model->module_dir);
                if ($model->delete()) {
                    FileHelper::removeDirectory($modulePath);
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
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
