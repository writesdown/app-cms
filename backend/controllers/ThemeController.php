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
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/* MODEL */
use common\models\Option;
use yii\base\DynamicModel;
use yii\web\UploadedFile;

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
    private $themeDir;
    /**
     * @var string
     */
    private $thumbDir;
    /**
     * @var string
     */
    private $thumbBaseUrl;

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
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'install' => ['post'],
                    'delete' => ['post'],
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
        $themes     = [];
        $detail     = [];

        if (!is_dir($this->themeDir)) {
            mkdir($this->themeDir, 0755);
        }

        if (!is_dir($this->thumbDir)) {
            mkdir($this->thumbDir, 0755);
        }

        $arrThemes = scandir($this->themeDir);

        foreach ($arrThemes as $theme) {

            if (is_dir($this->themeDir . $theme) && $theme !== '.' && $theme !== '..') {
                $fileInfo = $this->themeDir . $theme . '/info.php';

                if (is_file($fileInfo)) {
                    $detail = require($fileInfo);
                }

                $detail['Thumbnail'] = is_file($this->thumbDir . $theme . '.png') ?
                    $this->thumbBaseUrl . $theme . '.png' :
                    Yii::getAlias('@web/img/themes.png');
                $detail['Dir'] = $theme;

                if (!isset($detail['Name'])) {
                    $detail['Name'] = $theme;
                }

                $themes[] = $detail;
            }
        }

        return $this->render('index', [
            'themes' => $themes,
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

        if (Yii::$app->request->isPost) {
            $model->theme = UploadedFile::getInstance($model, 'theme');

            if ($model->validate()) {
                // Theme path
                $themePath = $this->themeDir . $model->theme->name;

                if (!is_dir($this->themeDir)) {
                    mkdir($this->themeDir, 0755);
                }

                if (!is_dir($this->thumbDir)) {
                    mkdir($this->thumbDir, 0755);
                }

                if ($model->theme->saveAs($themePath)) {
                    $zipArchive = new \ZipArchive();
                    $zipArchive->open($themePath);

                    if ($zipArchive->extractTo($this->themeDir)) {
                        $themeName = substr($zipArchive->getNameIndex(0), 0, strpos($zipArchive->getNameIndex(0), '/'));

                        if(is_file($this->themeDir . $themeName . '/screenshot.png')){
                            copy($this->themeDir . $themeName . '/screenshot.png', $this->thumbDir . $themeName . '.png');
                        }

                        $zipArchive->close();

                        if (unlink($themePath)) {
                            $this->redirect(['index']);
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
        $detail = [];
        $fileInfo = $this->themeDir . $theme . '/info.php';

        if (is_file($fileInfo)) {
            $detail = require($fileInfo);
        }

        $detail['Thumbnail'] = is_file($this->thumbDir . $theme . '.png') ?
            $this->thumbBaseUrl . $theme . '.png' :
            Yii::getAlias('@web/img/themes.png');
        $detail['Dir'] = $theme;

        if (!isset($detail['Name'])) {
            $detail['Name'] = $theme;
        }

        return $this->render('detail', [
            'detail' => $detail,
            'installed' => Option::get('theme'),
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
        $fileConfig = $this->themeDir . $theme . '/config/main.php';
        if (is_file($fileConfig)) {
            $config = require_once $fileConfig;
            if (!is_array($config)) {
                Yii::$app->getSession()->setFlash('danger', Yii::t('writesdown', "File config must return an array."));
            } else {
                if (Option::up('theme', $theme)) {
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
            $this->deleteTheme($this->themeDir . $theme);

            if (is_file($this->thumbDir . $theme . '.png')) {
                unlink($this->thumbDir . $theme . '.png');
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
        $detail = [];
        $fileInfo = $this->themeDir . $theme . '/info.php';

        if (is_file($fileInfo)) {
            $detail = require($fileInfo);
        }

        $detail['Thumbnail'] = is_file($this->thumbDir . $theme . '.png') ?
            $this->thumbBaseUrl . $theme . '.png' :
            Yii::getAlias('@web/img/themes.png');
        $detail['Dir'] = $theme;

        if (!isset($detail['Name'])) {
            $detail['Name'] = $theme;
        }

        return $this->renderPartial('_theme-detail', [
            'detail' => $detail,
            'installed' => Option::get('theme'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->themeDir = Yii::getAlias('@themes/');
            $this->thumbDir = Yii::getAlias('@webroot/themes/');
            $this->thumbBaseUrl = Yii::getAlias('@web/themes/');

            return true;
        }

        return false;
    }


    /**
     * Delete the themes directory and its subdirectories
     *
     * @param string $themePath
     *
     * @return bool
     */
    protected function deleteTheme($themePath)
    {
        if (!file_exists($themePath)) {
            return true;
        }

        if (!is_dir($themePath)) {
            return unlink($themePath);
        }

        foreach (scandir($themePath) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteTheme($themePath . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($themePath);
    }
}