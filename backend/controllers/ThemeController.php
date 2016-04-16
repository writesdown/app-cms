<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\models\Option;
use Yii;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * ThemeController, controlling the actions for theme.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class ThemeController extends Controller
{
    /**
     * @var string Path to theme directory.
     */
    private $_dir;

    /**
     * @var string Path to temporary directory of theme.
     */
    private $_tmp;

    /**
     * @var string Path to thumbnail directory of theme.
     */
    private $_thumbDir;

    /**
     * @var string Base url of theme thumbnail.
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
        $installed = Option::get('theme');
        $themes[] = $this->getConfig($installed);

        if (!is_dir($this->_dir)) {
            FileHelper::createDirectory($this->_dir, 0755);
        }

        if (!is_dir($this->_thumbDir)) {
            FileHelper::createDirectory($this->_thumbDir, 0755);
        }

        $arrThemes = scandir($this->_dir);

        foreach ($arrThemes as $theme) {
            if (is_dir($this->_dir . $theme) && $theme !== '.' && $theme !== '..' && $theme !== $installed) {
                $themes[] = $this->getConfig($theme);
            }
        }

        return $this->render('index', [
            'themes'    => $themes,
            'installed' => $installed,
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
        $errors = [];
        $model = new DynamicModel(['file']);
        $model->addRule(['file'], 'required')
            ->addRule(['file'], 'file', ['extensions' => 'zip']);

        if (!is_dir($this->_dir)) {
            FileHelper::createDirectory($this->_dir, 0755);
        }

        if (!is_dir($this->_tmp)) {
            FileHelper::createDirectory($this->_tmp, 0755);
        }

        if (!is_dir($this->_thumbDir)) {
            FileHelper::createDirectory($this->_thumbDir, 0755);
        }

        if (($model->file = UploadedFile::getInstance($model, 'file')) && $model->validate()) {
            $themeTempPath = $this->_tmp . $model->file->name;

            if (!$model->file->saveAs($themeTempPath)) {
                return $this->render('upload', [
                    'model' => $model,
                    'errors' => [Yii::t('writesdown', 'Failed to move uploaded file')],
                ]);
            }

            $zipArchive = new \ZipArchive();
            $zipArchive->open($themeTempPath);

            if (!$zipArchive->extractTo($this->_tmp)) {
                $zipArchive->close();
                FileHelper::removeDirectory($this->_tmp);

                return $this->render('upload', [
                    'model' => $model,
                    'errors' => [Yii::t('writesdown', 'Failed to extract file.')],
                ]);
            }

            $baseDir = substr($zipArchive->getNameIndex(0), 0, strpos($zipArchive->getNameIndex(0), '/'));
            $zipArchive->close();

            if (is_dir($this->_dir . $baseDir)) {
                FileHelper::removeDirectory($this->_tmp);
                $errors[] = Yii::t('writesdown', 'Theme with the same directory already exist.');
            } else {
                rename($this->_tmp . $baseDir, $this->_dir . $baseDir);
                FileHelper::removeDirectory($this->_tmp);

                if (is_file($this->_dir . $baseDir . '/screenshot.png')) {
                    copy($this->_dir . $baseDir . '/screenshot.png', $this->_thumbDir . $baseDir . '.png');
                }

                foreach (ArrayHelper::getValue($this->getConfig($baseDir), 'upload', []) as $type) {
                    try {
                        Yii::createObject($type);
                    } catch (Exception $e) {
                    }
                }

                Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', 'Theme successfully uploaded'));

                return $this->redirect(['index']);
            }
        }

        return $this->render('upload', [
            'model' => $model,
            'errors' => $errors,
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
        return $this->render('detail', [
            'theme' => $this->getConfig($theme),
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
        foreach (ArrayHelper::getValue($this->getConfig(Option::get('theme')), 'uninstall', []) as $type) {
            try {
                Yii::createObject($type);
            } catch (Exception $e) {
            }
        }

        foreach (ArrayHelper::getValue($this->getConfig($theme), 'install', []) as $type) {
            try {
                Yii::createObject($type);
            } catch (Exception $e) {
            }
        }

        if (Option::set('theme', $theme)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', "Theme successfully installed."));
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
        if ($theme !== Option::get('theme')) {

            foreach (ArrayHelper::getValue($this->getConfig($theme), 'delete', []) as $type) {
                try {
                    Yii::createObject($type);
                } catch (Exception $e) {
                }
            }

            FileHelper::removeDirectory($this->_dir . $theme);

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
        return $this->renderPartial('_theme-detail', [
            'theme' => $this->getConfig($theme),
            'installed' => Option::get('theme'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->_dir = Yii::getAlias('@themes/');
            $this->_tmp = Yii::getAlias('@common/tmp/themes/');
            $this->_thumbDir = Yii::getAlias('@webroot/themes/');
            $this->_thumbBaseUrl = Yii::getAlias('@web/themes/');

            return true;
        }

        return false;
    }

    /**
     * Get theme config based on theme directory;
     *
     * @param $theme
     * @return array|mixed
     */
    protected function getConfig($theme)
    {
        $config = [];
        $configPath = $this->_dir . $theme . '/config/main.php';

        if (is_file($configPath)) {
            $config = require($configPath);
        }

        $config['thumbnail'] = is_file($this->_thumbDir . $theme . '.png')
            ? $this->_thumbBaseUrl . $theme . '.png'
            : Yii::getAlias('@web/img/themes.png');
        $config['directory'] = $theme;

        if (!isset($config['info']['Name'])) {
            $config['info']['Name'] = $theme;
        }

        return $config;
    }
}
