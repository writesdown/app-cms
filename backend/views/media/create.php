<?php
/**
 * @file      create.php.
 * @date      6/4/2015
 * @time      5:45 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\assets\MediaAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Media */

$this->title = Yii::t('writesdown', 'Add New Media');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Media'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

MediaAsset::register($this);
?>

    <div class="media-create">
        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype'  => 'multipart/form-data',
                'id'       => 'media-upload',
                'data-url' => Url::to(['/media/ajax-upload'])
            ],
            'action'  => Url::to(['/site/forbidden']),
        ]); ?>

        <noscript>
            <?= Html::hiddenInput('redirect', Url::to(['/site/forbidden'])); ?>
        </noscript>

        <div class="dropzone fade">
            <div class="dropzone-inner">
                <?= Yii::t('writesdown', 'Drop files here'); ?> <br/>
                <?= Yii::t('writesdown', 'OR'); ?><br/>
            <span class="btn btn-default btn-flat fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span><?= Yii::t('writesdown', 'Add files...'); ?></span>
                <?= $form->field($model, 'file', ['template' => '{input}', 'options' => ['class' => '']])->fileInput(['multiple' => 'multiple']); ?>
            </span>
            </div>
        </div>

        <div role="presentation" class="file-container"></div>

        <?php ActiveForm::end(); ?>

    </div>

<?= $this->render('_template-create'); ?>