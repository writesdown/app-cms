<?php
/**
 * @file      main-sidebar.php.
 * @date      6/4/2015
 * @time      5:26 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use cebe\gravatar\Gravatar;
use codezeen\yii2\adminlte\widgets\MainSidebar;

/* MODEL */
use common\models\Option;
use common\models\PostType;

/* @var $this yii\web\View */

?>

<aside class="main-sidebar">
    <section class="sidebar">

        <?php if (!Yii::$app->user->isGuest) { ?>
            <div class="user-panel">
                <div class="pull-left image">
                    <?php echo Gravatar::widget([
                        'email'   => Yii::$app->user->identity->email,
                        'options' => [
                            'alt'   => Yii::$app->user->identity->username,
                            'class' => 'img-circle'
                        ],
                        'size'    => 45
                    ]); ?>
                </div>
                <div class="pull-left info">
                    <p><?= Yii::$app->user->identity->username; ?></p>
                    <?= Html::a('<i class="fa fa-circle text-success"></i>' . Yii::t('writesdown', 'Online'), ['/user/profile']); ?>
                </div>
            </div>
        <?php } ?>

        <?php
        $adminSiteMenu[0] = ['label' => Yii::t('writesdown', 'MAIN NAVIGATION'), 'options' => ['class' => 'header'], 'template' => '{label}'];
        $adminSiteMenu[1] = ['label' => Yii::t('writesdown', 'Dashboard'), 'icon' => 'fa fa-dashboard', 'items' => [
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Home'), 'url' => ['/site/index']],
        ]];
        $adminSiteMenu[10] = ['label' => Yii::t('writesdown', 'Media'), 'icon' => 'fa fa-picture-o', 'items' => [
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'All Media'), 'url' => ['/media/index']],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Add New Media'), 'url' => ['/media/create']],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Comments'), 'url' => ['/media-comment/index'], 'visible' => Yii::$app->user->can('editor')],
        ], 'visible'                  => Yii::$app->user->can('author')];
        $adminSiteMenu[20] = ['label' => Yii::t('writesdown', 'Appearance'), 'icon' => 'fa fa-paint-brush', 'items' => [
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Menus'), 'url' => ['/menu/index']],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Themes'), 'url' => ['/theme/index']],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Widgets'), 'url' => ['/widget/index']],
        ], 'visible'                  => Yii::$app->user->can('administrator')];
        $adminSiteMenu[23] = ['label' => Yii::t('writesdown', 'Modules'), 'icon' => 'fa fa-laptop', 'items' => [
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'All Modules'), 'url' => ['/module/index']],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Add New Module'), 'url' => ['/module/create']],
        ], 'visible'                  => Yii::$app->user->can('administrator')];
        $adminSiteMenu[30] = ['label' => Yii::t('writesdown', 'Post Types'), 'icon' => 'fa fa-files-o', 'items' => [
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'All Post Types'), 'url' => ['/post-type/index']],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Add New Post Type'), 'url' => ['/post-type/create']],
        ], 'visible'                  => Yii::$app->user->can('administrator')];
        $adminSiteMenu[40] = ['label' => Yii::t('writesdown', 'Taxonomies'), 'icon' => 'fa fa-tags', 'items' => [
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'All Taxonomies'), 'url' => ['/taxonomy/index']],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Add New Taxonomy'), 'url' => ['/taxonomy/index']],
        ], 'visible'                  => Yii::$app->user->can('administrator')];
        $adminSiteMenu[50] = ['label' => Yii::t('writesdown', 'Users'), 'icon' => 'fa fa-user', 'items' => [
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'All User'), 'url' => ['/user/index'], 'visible' => Yii::$app->user->can('administrator')],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Add New User'), 'url' => ['/user/create'], 'visible' => Yii::$app->user->can('administrator')],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'My Profile'), 'url' => ['/user/profile'], 'visible' => Yii::$app->user->can('subscriber')],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Reset Password'), 'url' => ['/user/reset-password'], 'visible' => Yii::$app->user->can('subscriber')],
        ]];
        $adminSiteMenu[70] = ['label' => Yii::t('writesdown', 'Tools'), 'icon' => 'fa fa-wrench', 'items' => [
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Export'), 'url' => ['/site/not-found'], 'visible' => Yii::$app->user->can('superadmin')],
            ['icon' => 'fa fa-circle-o', 'label' => Yii::t('writesdown', 'Import'), 'url' => ['/site/not-found'], 'visible' => Yii::$app->user->can('superadmin')],
        ]];
        $adminSiteMenu = ArrayHelper::merge($adminSiteMenu, PostType::getMenu(2));
        $adminSiteMenu = ArrayHelper::merge($adminSiteMenu, Option::getMenu(60));

        if (isset(Yii::$app->params['adminSiteMenu']) && is_array(Yii::$app->params['adminSiteMenu'])) {
            $adminSiteMenu = ArrayHelper::merge($adminSiteMenu, Yii::$app->params['adminSiteMenu']);
        }
        // Short menu by index
        ksort($adminSiteMenu);
        echo MainSidebar::widget([
            'items'           => $adminSiteMenu,
        ]);
        ?>
    </section>
</aside>