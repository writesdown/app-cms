<?php
/**
 * @file      ThemeController.php.
 * @date      6/4/2015
 * @time      5:12 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;

/* MODEL */
use common\models\Option;

/**
 * Class ThemeController.
 * Controller that handle theme configuration.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 */
class ThemeController extends Controller
{
    /**
     * @var string
     */
    private $_themeDir;

    /**
     * @var string
     */
    private $_themeTempDir;

    /**
     * @var string
     */
    private $_thumbDir;

    /**
     * @var string
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
     * Render index in which there are available theme
     *
     * @return string
     */
    public function actionIndex()
    {
        $themes = [];
        $themeConfig = [];

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
                    $themeConfig = require($configPath);
                }

                $themeConfig['Thumbnail'] = is_file($this->_thumbDir . $theme . '.png') ?
                    $this->_thumbBaseUrl . $theme . '.png' :
                    Yii::getAlias('@web/img/themes.png');
                $themeConfig['Dir'] = $theme;

                if (!isset($themeConfig['Name'])) {
                    $themeConfig['Name'] = $theme;
                }

                $themes[] = $themeConfig;
            }
        }

        return $this->render('index', [
            'themes'    => $themes,
            'installed' => Option::get('theme'),
        ]);
    }

    /**
     * Upload new theme to the site
     *
     * @return string
     */
    public function actionUpload()
    {
        $model = new DynamicModel([
            'theme'
        ]);

        $model->addRule(['theme'], 'required')
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
                            Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', 'Theme with the same directory already exist.'));
                        } else {
                            rename($this->_themeTempDir . $baseDir, $this->_themeDir . $baseDir);
                            if (is_file($this->_themeDir . $baseDir . '/screenshot.png')) {
                                copy($this->_themeDir . $baseDir . '/screenshot.png', $this->_thumbDir . $baseDir . '.png');
                            }
                            FileHelper::removeDirectory($this->_themeTempDir);
                            Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', 'Theme successfully uploaded'));

                            return $this->redirect(['index']);
                        }
                    }
                }
            }
        }

        return $this->render('upload', [
            'model' => $model
        ]);
    }

    /**
     * Show theme detail based on theme name then render theme detail view.
     *
     * @param string $theme
     *
     * @return string
     */
    public function actionDetail($theme)
    {
        $themeConfig = [];
        $themeInfo = $this->_themeDir . $theme . '/config/main.php';

        if (is_file($themeInfo)) {
            $themeConfig = require($themeInfo);
        }

        $themeConfig['Thumbnail'] = is_file($this->_thumbDir . $theme . '.png') ?
            $this->_thumbBaseUrl . $theme . '.png' :
            Yii::getAlias('@web/img/themes.png');
        $themeConfig['Dir'] = $theme;

        if (!isset($themeConfig['Name'])) {
            $themeConfig['Name'] = $theme;
        }

        return $this->render('detail', [
            'themeConfig' => $themeConfig,
            'installed'   => Option::get('theme'),
        ]);
    }

    /**
     * Install selected theme.
     *
     * @param string $theme
     *
     * @return \yii\web\Response
     */
    public function actionInstall($theme)
    {
        $fileConfig = $this->_themeDir . $theme . '/config/main.php';
        if (is_file($fileConfig)) {
            $config = require_once $fileConfig;
            if (!is_array($config)) {
                Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', "File config must return an array."));
            } else {
                if (Option::set('theme', $theme)) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', "Theme successfully installed."));
                }
            }
        } else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', "Theme not successfully installed. Error: invalid config file"));
        }

        // Redirect to index when the theme successfully installed
        return $this->redirect(['index']);
    }

    /**
     * Delete theme.
     *
     * @param string $theme
     *
     * @return \yii\web\Response
     */
    public function actionDelete($theme)
    {
        if ($theme != Option::get('theme')) {
            FileHelper::removeDirectory($this->_themeDir . $theme);

            if (is_file($this->_thumbDir . $theme . '.png')) {
                unlink($this->_thumbDir . $theme . '.png');
            }
        }

        // Redirect to index when theme deleted successfully
        return $this->redirect(['index']);
    }

    /**
     * Detail theme via ajax for model
     *
     * @param string $theme
     *
     * @return string
     */
    public function actionAjaxDetail($theme)
    {
        $themeConfig = [];
        $themeInfo = $this->_themeDir . $theme . '/config/main.php';

        if (is_file($themeInfo)) {
            $themeConfig = require($themeInfo);
        }

        $themeConfig['Thumbnail'] = is_file($this->_thumbDir . $theme . '.png') ?
            $this->_thumbBaseUrl . $theme . '.png' :
            Yii::getAlias('@web/img/themes.png');
        $themeConfig['Dir'] = $theme;

        if (!isset($themeConfig['Name'])) {
            $themeConfig['Name'] = $theme;
        }

        return $this->renderPartial('_theme-detail', [
            'themeConfig' => $themeConfig,
            'installed'   => Option::get('theme'),
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