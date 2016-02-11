<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use backend\assets\MenuAsset;

/* @var $available [] */
/* @var $selected common\models\Menu */
/* @var $postTypes common\models\PostType[] */
/* @var $taxonomies common\models\Taxonomy[] */
/* @var $model common\models\Menu */

$this->title = Yii::t('writesdown', 'Menus');
$this->params['breadcrumbs'][] = $this->title;

MenuAsset::register($this);
?>
<div class="menu-index">
    <div class="box box-primary">
        <div class="box-header">
            <i class="fa fa-list-ul"></i>
            <h2 class="box-title"><?= Yii::t('writesdown', 'Menu') ?></h2>

            <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body clearfix">
            <div class="row">
                <div class="col-md-4">
                    <?= $this->render('_create', [
                        'model' => $model,
                    ]) ?>
                </div>
                <div class="col-md-8">
                    <?= $this->render('_select', [
                        'available' => $available,
                        'selected' => $selected,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($selected): ?>
        <div class="row">
            <div class="col-md-4">
                <div id="create-menu-items" class="box-group">
                    <?= $this->render('_link', ['selected' => $selected]) ?>
                    <?= $this->render('_post-types', ['postTypes' => $postTypes, 'selected' => $selected]) ?>
                    <?= $this->render('_taxonomies', ['taxonomies' => $taxonomies, 'selected' => $selected]) ?>
                </div>
            </div>
            <div class="col-md-8">
                <?= $this->render('_render', [
                    'selected' => $selected,
                ]) ?>
            </div>
        </div>
    <?php endif ?>

</div>
