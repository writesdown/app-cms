<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace modules\toolbar\frontend;

use common\models\PostType;
use common\models\search\Option;
use common\models\Taxonomy;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * Class Module
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var string
     */
    public $controllerNamespace = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset(Yii::$app->i18n->translations['toolbar'])) {
            Yii::$app->i18n->translations['toolbar'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => Yii::$app->language,
                'basePath'       => __DIR__ . '/../messages',
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if (!$app->user->isGuest) {
            $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
                $app->getView()->on(View::EVENT_END_BODY, [$this, 'renderToolbar']);
            });
        }
    }

    /**
     * Renders mini-toolbar at the end of page body.
     *
     * @param \yii\base\Event $event
     */
    public function renderToolbar($event)
    {
        /* @var $view View */
        /* @var $urlManagerBack \yii\web\UrlManager */

        $urlManagerBack = Yii::$app->urlManagerBack;
        $view = $event->sender;
        $view->registerCss($view->renderPhpFile(__DIR__ . '/assets/toolbar.min.css'));

        NavBar::begin([
            'id'                    => 'wd-frontend-toolbar',
            'brandLabel'            => Html::img('@web/img/logo-mini.png'),
            'brandUrl'              => $urlManagerBack->baseUrl,
            'innerContainerOptions' => [
                'class' => 'container-fluid',
            ],
            'options'               => [
                'class' => 'navbar navbar-inverse navbar-fixed-top',
            ],
        ]);

        echo Nav::widget([
            'encodeLabels' => false,
            'options'      => ['class' => 'navbar-nav'],
            'items'        => [
                [
                    'label' => '<span aria-hidden="true" class="glyphicon glyphicon-dashboard"></span> ' . Option::get('sitetitle'),
                    'items' => [
                        [
                            'label' => Yii::t('toolbar', 'Dashboard'),
                            'url'   => $urlManagerBack->baseUrl,
                        ],
                        [
                            'label'   => Yii::t('toolbar', 'Themes'),
                            'url'     => $urlManagerBack->createUrl(['/theme']),
                            'visible' => Yii::$app->user->can('administrator'),
                        ],
                        [
                            'label'   => Yii::t('toolbar', 'Menus'),
                            'url'     => $urlManagerBack->createUrl(['/menu']),
                            'visible' => Yii::$app->user->can('administrator'),
                        ],
                        [
                            'label'   => Yii::t('toolbar', 'Modules'),
                            'url'     => $urlManagerBack->createUrl(['/module']),
                            'visible' => Yii::$app->user->can('administrator'),
                        ],
                        [
                            'label'   => Yii::t('toolbar', 'Widgets'),
                            'url'     => $urlManagerBack->createUrl(['/widget']),
                            'visible' => Yii::$app->user->can('administrator'),
                        ],
                    ],
                ],
                [
                    'label' => '<span aria-hidden="true" class="glyphicon glyphicon-plus"></span> ' . Yii::t('toolbar',
                            'New'),
                    'items' => $this->getAddNewMenu() ? $this->getAddNewMenu() : null,
                ],
            ],
        ]);

        echo Nav::widget([
            'encodeLabels' => false,
            'options'      => ['class' => 'navbar-nav navbar-right'],
            'items'        => [
                [
                    'label' => '<span aria-hidden="true" class="glyphicon glyphicon-user"></span> ' . Yii::$app->user->identity->username,
                    'items' => [
                        [
                            'label' => 'Profile',
                            'url'   => $urlManagerBack->createUrl(['/user/profile']),
                        ],
                        [
                            'label'       => 'Logout',
                            'url'         => ['/site/logout'],
                            'linkOptions' => [
                                'data-method' => 'post',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        NavBar::end();
    }

    /**
     * Get menu dropdown for post type.
     *
     * @return array
     */
    protected function getPostTypeMenu()
    {
        /* @var $urlManagerBack \yii\web\UrlManager */
        /* @var $postTypes \common\models\PostType[] */

        $urlManagerBack = Yii::$app->urlManagerBack;
        $menuItems = [];
        $postTypes = PostType::find()->select(['id', 'post_type_sn', 'post_type_permission'])->all();

        foreach ($postTypes as $postType) {
            $menuItems[] = [
                'label'   => $postType->post_type_sn,
                'url'     => $urlManagerBack->createUrl(['/post/create', 'post_type' => $postType->id]),
                'visible' => Yii::$app->user->can($postType->post_type_permission),
            ];
        }

        return $menuItems;
    }

    /**
     * Get dropdown menu for taxonomy.
     *
     * @return array
     */
    protected function getTaxonomyMenu()
    {
        /* @var $urlManagerBack \yii\web\UrlManager */
        /* @var $taxonomies \common\models\Taxonomy[] */

        $urlManagerBack = Yii::$app->urlManagerBack;
        $menuItems = [];

        $taxonomies = Taxonomy::find()->select(['id', 'taxonomy_sn'])->all();

        foreach ($taxonomies as $taxonomy) {
            $menuItems[] = [
                'label'   => $taxonomy->taxonomy_sn,
                'url'     => $urlManagerBack->createUrl(['/taxonomy/view', 'id' => $taxonomy->id]),
                'visible' => Yii::$app->user->can('editor'),
            ];
        }

        return $menuItems;
    }

    /**
     * Get dropdown menu for add new.
     *
     * @return array
     */
    protected function getAddNewMenu()
    {
        /* @var $urlManagerBack \yii\web\UrlManager */

        $urlManagerBack = Yii::$app->urlManagerBack;

        $postTypeMenu = $this->getPostTypeMenu();
        $taxonomyMenu = $this->getTaxonomyMenu();

        $menuItems = ArrayHelper::merge(
            $postTypeMenu,
            ['<li class="divider"></li>'],
            $taxonomyMenu,
            ['<li class="divider"></li>'],
            [
                [
                    'label'   => Yii::t('toolbar', 'Taxonomy'),
                    'url'     => $urlManagerBack->createUrl('/taxonomy/create'),
                    'visible' => Yii::$app->user->can('administrator'),
                ],
                [
                    'label'   => Yii::t('toolbar', 'Post Type'),
                    'url'     => $urlManagerBack->createUrl('/post-type/create'),
                    'visible' => Yii::$app->user->can('administrator'),
                ],
            ]
        );

        return $menuItems;
    }
}
