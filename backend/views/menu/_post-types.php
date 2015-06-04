<?php
/**
 * @file      _post-types.php.
 * @date      6/4/2015
 * @time      6:07 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $postTypes common\models\PostType[] */
/* @var $selectedMenu common\models\Menu */

foreach ($postTypes as $postType) {
    $form = ActiveForm::begin([
        'options' => [
            'class'    => 'panel box box-primary menu-create-menu-item',
            'data-url' => Url::to(['menu/create-menu-item', 'id' => $selectedMenu->id])
        ],
        'action'  => Url::to(['/site/forbidden'])
    ]); ?>

    <div class="box-header">
        <h4 class="box-title">
            <a href="#post-type-<?= $postType->id ?>" data-parent="#create-menu-items" data-toggle="collapse"
               class="collapsed" aria-expanded="false">
                <?= $postType->post_type_pn ?>
            </a>
        </h4>
    </div>

    <div class="panel-collapse collapse post-type-menu" id="post-type-<?= $postType->id ?>">

        <div class="box-body">
            <?= Html::checkboxList('postIds', null, ArrayHelper::map($postType->posts, 'id', 'post_title'), ['class' => 'checkbox post-type-menu-item ', 'separator' => '<br />']); ?>
        </div>

        <div class="box-footer">
            <?= Html::hiddenInput('type', 'post'); ?>
            <?= Html::submitButton(Yii::t('writesdown', 'Add Menu'), ['class' => 'btn btn-flat btn-primary btn-create-menu-item']); ?>
        </div>

    </div>

    <?php ActiveForm::end();
}