<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $postTypes common\models\PostType[] */
/* @var $selected common\models\Menu */
?>
<?php foreach ($postTypes as $postType): ?>
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'panel box box-primary create-menu-item',
            'data-url' => Url::to(['create-menu-item', 'id' => $selected->id]),
        ],
        'action' => Url::to(['/site/forbidden']),
    ]) ?>

    <div class="box-header">
        <h4 class="box-title">
            <a href="#post-type-<?= $postType->id ?>" data-parent="#create-menu-items" data-toggle="collapse"
               class="collapsed" aria-expanded="false">
                <?= $postType->plural_name ?>

            </a>
        </h4>
    </div>
    <div class="panel-collapse collapse post-type-menu" id="post-type-<?= $postType->id ?>">
        <div class="box-body">
            <?= Html::checkboxList('postIds', null, ArrayHelper::map($postType->posts, 'id', 'title'), [
                'class' => 'checkbox post-type-menu-item ',
                'separator' => '<br />',
            ]) ?>

        </div>
        <div class="box-footer">
            <?= Html::hiddenInput('type', 'post') ?>

            <?= Html::submitButton(Yii::t('writesdown', 'Add Menu'), [
                'class' => 'btn btn-flat btn-primary btn-create-menu-item',
            ]) ?>

        </div>
    </div>
    <?php ActiveForm::end() ?>

<?php endforeach ?>
