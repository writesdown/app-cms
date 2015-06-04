<?php
/**
 * @file      index.php.
 * @date      6/4/2015
 * @time      6:06 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use backend\assets\MenuAsset;

/* @var $availableMenu [] */
/* @var $selectedMenu common\models\Menu */
/* @var $postTypes common\models\PostType[] */
/* @var $taxonomies common\models\Taxonomy[] */

$this->title = Yii::t('writesdown', 'Menus');
$this->params['breadcrumbs'][] = $this->title;

MenuAsset::register($this);
?>
<div class="menu-index">
    <div class="box box-primary">
        <div class="box-header">
            <i class="fa fa-list-ul"></i>

            <h2 class="box-title">
                <?php echo Yii::t('writesdown', 'Menu'); ?>
            </h2>

            <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body clearfix">
            <div class="row">
                <div class="col-md-4">

                    <?= $this->render('_create', [
                        'model' => $model
                    ]); ?>

                </div>
                <div class="col-md-8">

                    <?= $this->render('_select', [
                        'availableMenu' => $availableMenu,
                        'selectedMenu'  => $selectedMenu
                    ]); ?>

                </div>
            </div>
        </div>
    </div>

    <?php if ($selectedMenu) { ?>
        <div class="row">
            <div class="col-md-4">
                <div id="create-menu-items" class="box-group">

                    <?= $this->render('_link', ['selectedMenu' => $selectedMenu]); ?>
                    <?= $this->render('_post-types', ['postTypes' => $postTypes, 'selectedMenu' => $selectedMenu]) ?>
                    <?= $this->render('_taxonomies', ['taxonomies' => $taxonomies, 'selectedMenu' => $selectedMenu]) ?>

                </div>
            </div>
            <div class="col-md-8">

                <?= $this->render('_render', [
                    'selectedMenu' => $selectedMenu,
                ]); ?>

            </div>
        </div>
    <?php } ?>

</div>