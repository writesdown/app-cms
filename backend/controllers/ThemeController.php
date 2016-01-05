<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\models\Option;
use Yii;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * ThemeController, controlling the actions for theme.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class ThemeController extends Controller
{
    /**
     * @var string Path to theme directory.
     */
    private $_themeDir;

    /**
     * @var string Path to temporary directory of theme.
     */
    private $_themeTempDir;

    /**
     * @var string Path to thumbnail directory of theme.
     */
    private $_thumbDir;

    /**
     * @var string Base url of theme.
     */
    private $_thumbBaseUrl;

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
                        'actions' => ['index', 'upload', 'detail', 'install', 'delete', 'ajax-detail'],
                        'allow'   => true,
                        'roles'   => ['administrator'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'install' => ['post'],
                    'delete'  => ['post'],
                ],
            ],
        ];
    }

    /**
     * Scan theme directory to get list of all available themes.
     *
     * @return string
     */
    public function actionIndex()
    {
        $themes = [];
        $config = [];

        if (!is_dir($this->_themeDir)) {
            FileHelper::createDirectory($this->_themeDir, 0755);
        }

        if (!is_dir($this->_thumbDir)) {
            FileHelper::createDirectory($this->_thumbDir, 0755);
        }

        $arrThemes = scandir($this->_themeDir);

        foreach ($arrThemes as $theme) {
            if (is_dir($this->_themeDir . $theme) && $theme !== '.' && $theme !== '..') {
                $configPath = $this->_themeDir . $theme . '/config/main.php';
                if (is_file($configPath)) {
                    $config = require($configPath);
                }
                $config['info']['Thumbnail'] = is_file($this->_thumbDir . $theme . '.png')
                    ? $this->_thumbBaseUrl . $theme . '.png'
                    : Yii::getAlias('@web/img/themes.png');
                $config['info']['Dir'] = $theme;
                if (!isset($config['info']['Name'])) {
                    $config['info']['Name'] = $theme;
                }
                $themes[] = $config['info'];
            }
        }

        return $this->render('index', [
            'themes'    => $themes,
            'installed' => Option::get('theme'),
        ]);
    }

    /**
     * Register new theme.
     * Theme zip uploaded to temporary directory and extracted there.
     * Check the theme directory and move the first directory of the extracted theme.
     * If registration is successful, the browser will be redirected to the 'index' page.
     *
     * @return string
     */
    public function actionUpload()
    {
        $model = new DynamicModel([
            'theme',
        ]);

        $model
            ->addRule(['theme'], 'required')
            ->addRule(['theme'], 'file', ['extensions' => 'zip']);

        // Create temporary directory
        if (!is_dir($this->_themeTempDir)) {
            FileHelper::createDirectory($this->_themeTempDir, 0755);
        }

        // Create theme directory
        if (!is_dir($this->_themeDir)) {
            FileHelper::createDirectory($this->_themeDir, 0755);
        }

        // Create thumbnail directory
        if (!is_dir($this->_thumbDir)) {
            FileHelper::createDirectory($this->_thumbDir, 0755);
        }

        if (Yii::$app->request->isPost) {
            $model->theme = UploadedFile::getInstance($model, 'theme');

            if ($model->validate()) {
                // Theme temporary path
                $themeTempPath = $this->_themeTempDir . $model->theme->name;

                // Move theme (zip) to temporary directory
                if ($model->theme->saveAs($themeTempPath)) {
                    $zipArchive = new \ZipArchive();
                    $zipArchive->open($themeTempPath);

                    if ($zipArchive->extractTo($this->_themeTempDir)) {
                        $baseDir = substr($zipArchive->getNameIndex(0), 0, strpos($zipArchive->getNameIndex(0), '/'));
                        $zipArchive->close();
                        unlink($themeTempPath);

                        // Check theme exist in theme directory
                        if (is_dir($this->_themeDir . $baseDir)) {
                            FileHelper::removeDirectory($this->_themeTempDir);
                            Yii::$app->getSession()->setFlash(
                                'danger',
                                Yii::t('writesdown', 'Theme with the same directory already exist.')
                            );
                        } else {
                            rename($this->_themeTempDir . $baseDir, $this->_themeDir . $baseDir);
                            if (is_file($this->_themeDir . $baseDir . '/screenshot.png')) {
                                copy($this->_themeDir . $baseDir . '/screenshot.png',
                                    $this->_thumbDir . $baseDir . '.png');
                            }
                            FileHelper::removeDirectory($this->_themeTempDir);
                            $fileConfig = $this->_themeDir . $baseDir . '/config/main.php';

                            if (is_file($fileConfig)) {
                                $config = require($fileConfig);
                                if (isset($config['upload'])) {
                                    try {
                                        Yii::createObject($config['upload']);
                                    } catch (Exception $e) {
                                    }
                                }
                            }

                            Yii::$app->getSession()->setFlash(
                                'success',
                                Yii::t('writesdown', 'Theme successfully uploaded')
                            );

                            return $this->redirect(['index']);
                        }
                    }
                }
            }
        }

        return $this->render('upload', [
            'model' => $model,
        ]);
    }

    /**
     * Show theme detail based on theme dir then render theme detail view.
     *
     * @param string $theme
     *
     * @return string
     */
    public function actionDetail($theme)
    {
        $config = [];
        $fileConfig = $this->_themeDir . $theme . '/config/main.php';

        if (is_file($fileConfig)) {
            $config = require($fileConfig);
        }

        if (!isset($config['Name'])) {
            $config['info']['Name'] = $theme;
        }

        $config['info']['Thumbnail'] = is_file($this->_thumbDir . $theme . '.png')
            ? $this->_thumbBaseUrl . $theme . '.png'
            : Yii::getAlias('@web/img/themes.png');
        $config['info']['Dir'] = $theme;

        return $this->render('detail', [
            'config'    => $config['info'],
            'installed' => Option::get('theme'),
        ]);
    }

    /**
     * Install selected theme and run install and uninstall action based on config.
     *
     * @param string $theme
     *
     * @return \yii\web\Response
     */
    public function actionInstall($theme)
    {
        if (is_file($fileConfigInstalled = $this->_themeDir . Option::get('theme') . '/config/main.php')) {
            $configOld = require($fileConfigInstalled);
            if (isset($configOld['uninstall'])) {
                try {
                    Yii::createObject($configOld['uninstall']);
                } catch (Exception $e) {
                }
            }
        }

        if (is_file($fileConfigInstall = $this->_themeDir . $theme . '/config/main.php')) {
            $configNew = require($fileConfigInstall);
            if (isset($configNew['install'])) {
                try {
                    Yii::createObject($configNew['install']);
                } catch (Exception $e) {
                }
            }
        }

        if (Option::set('theme', $theme)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', "Theme successfully installed."));
        } else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', "File config must return an array."));
        }

        return $this->redirect(['index']);
    }

    /**
     * Delete and existing theme and run action based on config.
     *
     * @param string $theme
     *
     * @return \yii\web\Response
     */
    public function actionDelete($theme)
    {
        if ($theme != Option::get('theme')) {
            $fileConfig = $this->_themeDir . $theme . '/config/main.php';

            if (is_file($fileConfig)) {
                $config = require($fileConfig);
                if (isset($config['delete'])) {
                    try {
                        Yii::createObject($config['delete']);
                    } catch (Exception $e) {
                    }
                }
            }

            FileHelper::removeDirectory($this->_themeDir . $theme);
            if (is_file($this->_thumbDir . $theme . '.png')) {
                unlink($this->_thumbDir . $theme . '.png');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Detail selected theme via AJAX then show it on modal.
     *
     * @param string $theme
     *
     * @return string
     */
    public function actionAjaxDetail($theme)
    {
        $config = [];
        $fileConfig = $this->_themeDir . $theme . '/config/main.php';

        if (is_file($fileConfig)) {
            $config = require($fileConfig);
        }

        if (!isset($config['Name'])) {
            $config['info']['Name'] = $theme;
        }

        $config['info']['Thumbnail'] = is_file($this->_thumbDir . $theme . '.png')
            ? $this->_thumbBaseUrl . $theme . '.png'
            : Yii::getAlias('@web/img/themes.png');
        $config['info']['Dir'] = $theme;

        return $this->renderPartial('_theme-detail', [
            'config'    => $config['info'],
            'installed' => Option::get('theme'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->_themeDir = Yii::getAlias('@themes/');
            $this->_thumbDir = Yii::getAlias('@webroot/themes/');
            $this->_thumbBaseUrl = Yii::getAlias('@web/themes/');
            $this->_themeTempDir = Yii::getAlias('@common/temp/themes/');

            return true;
        }

        return false;
    }
}
