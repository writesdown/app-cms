<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* @var $form \yii\widgets\ActiveForm */
/* @var $model \common\models\Post */

$metaSeo = $model->getMeta('seo');

?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">SEO</h3>

        <div class="box-tools pull-right">
            <a class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></a>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Keyword'), 'meta-keyword') ?>

            <?= Html::textInput("meta[seo][keywords]", isset($meta['keywords']) ? $meta['keywords'] : '', [
                'id'    => 'meta-keyword',
                'class' => 'form-control',
            ]) ?>

        </div>
        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Description'), 'meta-description') ?>

            <?= Html::textarea("meta[seo][description]", isset($meta['description']) ? $meta['description'] : '', [
                'id'    => 'meta-description',
                'class' => 'form-control',
            ]) ?>

        </div>
    </div>
</div>
