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
        $admin_site_menu[0] = ['label' => Yii::t('writesdown', 'MAIN NAVIGATION'), 'options' => ['class' => 'header'], 'template' => '{label}'];
        $admin_site_menu[1] = ['label' => Yii::t('writesdown', 'Dashboard'), 'icon' => '<i class="fa fa-dashboard"></i>', 'options' => ['class' => 'treeview'], 'items' => [
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Home'), 'url' => ['/site/index']],
        ]];
        $admin_site_menu[10] = ['label' => Yii::t('writesdown', 'Media'), 'icon' => '<i class="fa fa-picture-o"></i>', 'options' => ['class' => 'treeview'], 'items' => [
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'All Media'), 'url' => ['/media/index']],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Add New Media'), 'url' => ['/media/create']],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Comments'), 'url' => ['/media-comment/index'], 'visible' => Yii::$app->user->can('editor')],
        ], 'visible'                    => Yii::$app->user->can('author')];

        $admin_site_menu[20] = ['label' => Yii::t('writesdown', 'Appearance'), 'icon' => '<i class="fa fa-paint-brush"></i>', 'options' => ['class' => 'treeview'], 'items' => [
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Menus'), 'url' => ['/menu']],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Themes'), 'url' => ['/theme']],
        ], 'visible'                    => Yii::$app->user->can('administrator')];
        $admin_site_menu[23] = ['label' => Yii::t('writesdown', 'Modules'), 'icon' => '<i class="fa fa-laptop"></i>', 'options' => ['class' => 'treeview'], 'items' => [
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'All Modules'), 'url' => ['/module']],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Add New Module'), 'url' => ['/module/create']],
        ], 'visible'                    => Yii::$app->user->can('administrator')];
        $admin_site_menu[30] = ['label' => Yii::t('writesdown', 'Post Types'), 'icon' => '<i class="fa fa-files-o"></i>', 'options' => ['class' => 'treeview'], 'items' => [
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'All Post Types'), 'url' => ['/post-type/index/']],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Add New Post Type'), 'url' => ['/post-type/create/']],
        ], 'visible'                    => Yii::$app->user->can('administrator')];
        $admin_site_menu[40] = ['label' => Yii::t('writesdown', 'Taxonomies'), 'icon' => '<i class="fa fa-tags"></i>', 'options' => ['class' => 'treeview'], 'items' => [
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'All Taxonomies'), 'url' => ['/taxonomy/index/']],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Add New Taxonomy'), 'url' => ['/taxonomy/create/']],
        ], 'visible'                    => Yii::$app->user->can('administrator')];
        $admin_site_menu[50] = ['label' => Yii::t('writesdown', 'Users'), 'icon' => '<i class="fa fa-user"></i>', 'options' => ['class' => 'treeview'], 'items' => [
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'All User'), 'url' => ['/user/index/'], 'visible' => Yii::$app->user->can('administrator')],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Add New User'), 'url' => ['/user/create/'], 'visible' => Yii::$app->user->can('administrator')],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'My Profile'), 'url' => ['/user/profile/'], 'visible' => Yii::$app->user->can('subscriber')],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Reset Password'), 'url' => ['/user/reset-password/'], 'visible' => Yii::$app->user->can('subscriber')],
        ]];
        $admin_site_menu[70] = ['label' => Yii::t('writesdown', 'Tools'), 'icon' => '<i class="fa fa-wrench"></i>', 'options' => ['class' => 'treeview'], 'items' => [
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Export'), 'url' => ['/site/not-found/'], 'visible' => Yii::$app->user->can('superadmin')],
            ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', 'Import'), 'url' => ['/site/not-found/'], 'visible' => Yii::$app->user->can('superadmin')],
        ]];
        $admin_site_menu = ArrayHelper::merge($admin_site_menu, PostType::getMenu(2));
        $admin_site_menu = ArrayHelper::merge($admin_site_menu, Option::getMenu(60));
        ksort($admin_site_menu);
        echo MainSidebar::widget([
            'options'         => ['class' => 'sidebar-menu'],
            'labelTemplate'   => '<a href="#">{icon}<span>{label}</span>{right-icon}{badge}</a>',
            'linkTemplate'    => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
            'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
            'activateParents' => true,
            'items'           => $admin_site_menu,
        ]);
        ?>
    </section>
</aside>
