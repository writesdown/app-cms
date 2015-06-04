<?php
/**
 * @file      ThemeControll.php.
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
use yii\base\DynamicModel;

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
        return $this->render('index');
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
        $thumbnail = Yii::getAlias('@web/img/themes.png');

        // Extract theme info based on theme name
        $detail = [
            'name'        => $theme,
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.Aenean commodo ligula eget dolor.
                            Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                            Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.
                            Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.',
            'tags'        => 'Lorem, Ipsum, Dolore, Sit, Amet',
            'author'      => 'Agiel K. Saputra',
            'version'     => '1.0.0-beta',
            'thumbnail'   => $thumbnail
        ];

        return $this->render('detail', [
            'detail' => $detail,
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
        // Install selected theme

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
        // Delete theme

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
        $thumbnail = Yii::getAlias('@web/img/themes.png');

        // Extract theme info based on theme name
        $detail = [
            'name'        => $theme,
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.Aenean commodo ligula eget dolor.
                            Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                            Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.
                            Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.',
            'tags'        => 'Lorem, Ipsum, Dolore, Sit, Amet',
            'author'      => 'Agiel K. Saputra',
            'version'     => '1.0.0-beta',
            'thumbnail'   => $thumbnail
        ];

        return $this->renderPartial('_theme-detail.php', ['detail' => $detail]);
    }
} 