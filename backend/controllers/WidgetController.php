<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\components\Json;
use common\models\Widget;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * WidgetController, controlling the actions for Widget model.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
class WidgetController extends Controller
{
    /**
     * @var string Path to widget directory.
     */
    private $_widgetDir;

    /**
     * @var string Path to temporary directory of widget.
     */
    private $_widgetTempDir;

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
                            'delete-widget',
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
                    'delete-widget' => ['post'],
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
        $availableWidget = [];
        $activatedWidget = [];
        $config = [];
        $widgetSpace = isset(Yii::$app->params['widget']) ? Yii::$app->params['widget'] : [];

        if (!is_dir($this->_widgetDir)) {
            FileHelper::createDirectory($this->_widgetDir);
        }

        $arrWidgets = scandir($this->_widgetDir);

        foreach ($arrWidgets as $widget) {
            if (is_dir($this->_widgetDir . $widget) && $widget !== '.' && $widget !== '..') {
                $configPath = $this->_widgetDir . $widget . '/config/main.php';
                if (is_file($configPath)) {
                    $config = require($configPath);
                    $config['widget_dir'] = $widget;
                }
                $availableWidget[$widget] = $config;
            }
        }

        foreach ($widgetSpace as $space) {
            $model = Widget::find()->where(['widget_location' => $space['location']])->orderBy(['widget_order' => SORT_ASC])->all();
            $activatedWidget[$space['location']] = $model;
        }

        return $this->render('index', [
            'activatedWidget' => $activatedWidget,
            'availableWidget' => $availableWidget,
            'widgetSpace'     => $widgetSpace,
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
        $model = new Widget(['scenario' => 'upload']);

        // Create temporary directory for widget
        if (!is_dir($this->_widgetTempDir)) {
            FileHelper::createDirectory($this->_widgetTempDir, 0755);
        }

        // Create widget directory
        if (!is_dir($this->_widgetDir)) {
            FileHelper::createDirectory($this->_widgetDir, 0755);
        }

        if (Yii::$app->request->isPost) {
            $model->widget_file = UploadedFile::getInstance($model, 'widget_file');
            // Validate widget_file
            if ($model->validate()) {
                $widgetTempPath = $this->_widgetTempDir . $model->widget_file->name;

                // Move widget (zip) to temporary directory
                if ($model->widget_file->saveAs($widgetTempPath)) {
                    $zipArchive = new \ZipArchive();
                    $zipArchive->open($widgetTempPath);

                    if ($zipArchive->extractTo($this->_widgetTempDir)) {
                        $baseDir = substr($zipArchive->getNameIndex(0), 0, strpos($zipArchive->getNameIndex(0), '/'));

                        // Close and unlink zip
                        $zipArchive->close();
                        unlink($widgetTempPath);

                        // Check if widget with the same directory already exist
                        if (is_dir($this->_widgetDir . $baseDir)) {
                            FileHelper::removeDirectory($this->_widgetTempDir);
                            Yii::$app->getSession()->setFlash(
                                'danger',
                                Yii::t('writesdown', 'Widget with the same directory already exist.')
                            );
                        } else {
                            // Move widget directory
                            if (rename($this->_widgetTempDir . $baseDir, $this->_widgetDir . $baseDir)) {
                                FileHelper::removeDirectory($this->_widgetTempDir);
                                $configPath = $this->_widgetDir . $baseDir . '/config/main.php';

                                // Require widget config if exist
                                if (is_file($configPath)) {
                                    $widgetConfig = require_once($configPath);

                                    // Check where widget config is valid or not
                                    if (isset($widgetConfig['widget_title'])
                                        && isset($widgetConfig['widget_config']['class'])
                                        && class_exists($widgetConfig['widget_config']['class'])
                                    ) {
                                        Yii::$app->getSession()->setFlash(
                                            'success',
                                            Yii::t('writesdown', 'Widget successfully installed')
                                        );

                                        return $this->redirect(['index']);
                                    } else {
                                        FileHelper::removeDirectory($this->_widgetDir . $baseDir);
                                        Yii::$app->getSession()->setFlash(
                                            'danger',
                                            Yii::t('writesdown', 'Invalid Configuration')
                                        );
                                    }
                                } else {
                                    FileHelper::removeDirectory($this->_widgetDir . $baseDir);
                                    Yii::$app->getSession()->setFlash(
                                        'danger',
                                        Yii::t('writesdown', 'File configuration does not exist.')
                                    );
                                }
                            }
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
     * Delete widget and activated widgets.
     *
     * @param $id string
     *
     * @return \yii\web\Response
     */
    public function actionDeleteWidget($id)
    {
        FileHelper::removeDirectory($this->_widgetDir . $id);
        Widget::deleteAll('widget_dir=:widget_dir', ['widget_dir' => $id]);

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
            $count = Widget::find()->where(['widget_location' => $model->widget_location])->count();

            // Require widget config
            $configPath = $this->_widgetDir . $id . '/config/main.php';
            $widgetConfig = require($configPath);

            // Set attribute of model
            $model->setAttributes($widgetConfig);
            $model->widget_dir = $id;
            $model->widget_config = Json::encode($model->widget_config);
            $model->widget_order = $count;

            if ($model->save()) {
                return $this->renderPartial('_activated', [
                    'activatedWidget' => $model,
                    'availableWidget' => [$model->widget_dir => $widgetConfig],
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
            $model->widget_config = Json::encode($model->widget_config);
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
                $this->findModel($id)->updateAttributes(['widget_order' => $order]);
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
            $this->_widgetDir = Yii::getAlias('@widgets/');
            $this->_widgetTempDir = Yii::getAlias('@common/temp/widgets/');

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
