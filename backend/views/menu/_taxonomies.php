<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $taxonomies common\models\Taxonomy[] */
/* @var $selectedMenu common\models\Menu */
?>
<?php foreach ($taxonomies as $taxonomy): ?>
    <?php $form = ActiveForm::begin([
        'options' => [
            'class'    => 'panel box box-primary menu-create-menu-item',
            'data-url' => Url::to(['menu/create-menu-item', 'id' => $selectedMenu->id]),
        ],
        'action'  => Url::to(['/site/forbidden']),
    ]) ?>

    <div class="box-header">
        <h4 class="box-title">
            <a href="#taxonomy-<?= $taxonomy->id ?>" data-parent="#create-menu-items" data-toggle="collapse"
               class="collapsed" aria-expanded="false">
                <?= $taxonomy->taxonomy_pn ?>

            </a>
        </h4>
    </div>
    <div class="panel-collapse collapse" id="taxonomy-<?= $taxonomy->id ?>">
        <div class="box-body">
            <?= Html::checkboxList('termIds', null, ArrayHelper::map($taxonomy->terms, 'id', 'term_name'), [
                'class'     => 'checkbox taxonomy-menu-item',
                'separator' => '<br />',
            ]) ?>

        </div>
        <div class="box-footer">
            <?= Html::hiddenInput('type', 'taxonomy') ?>

            <?= Html::submitButton(Yii::t('writesdown', 'Add Menu'), [
                'class' => 'btn btn-flat btn-primary btn-create-menu-item',
            ]) ?>

        </div>
    </div>
    <?php ActiveForm::end() ?>

<?php endforeach ?>
