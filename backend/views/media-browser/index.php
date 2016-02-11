<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use backend\assets\MediaBrowserAsset;
use common\components\Json;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Media */
/* @var $post integer */

MediaBrowserAsset::register($this);
?>
<div id="media-browser" class="media-browser">
    <div class="browser-menu">
        <?php
        $items[] = [
            'label' => Yii::t('writesdown', 'Insert Media'),
            'url' => Url::to(['index']),
            'options' => ['class' => 'active'],
        ];
        echo Nav::widget([
            'activateItems' => false,
            'options' => ['class' => 'nav nav-pills nav-stacked'],
            'items' => $items,
        ]);
        ?>

    </div>
    <div class="browser-content">
        <div class="browser-nav-tabs nav-tabs-custom">
            <?= Nav::widget([
                'items' => [
                    [
                        'label' => '<i class="fa fa-plus"></i> <span>'
                            . Yii::t('writesdown', 'Add New Media')
                            . '</span>',
                        'url' => '#add-new-media',
                        'linkOptions' => ['aria-controls' => 'add-new-media', 'role' => 'tab', 'data-toggle' => 'tab'],
                        'options' => ['role' => 'presentation'],
                    ],
                    [
                        'label' => '<i class="fa fa-folder-open"></i> <span>'
                            . Yii::t('writesdown', 'Media Library')
                            . '</span>',
                        'url' => '#media-library',
                        'linkOptions' => ['aria-controls' => 'media-library', 'role' => 'tab', 'data-toggle' => 'tab'],
                        'options' => [
                            'role' => 'presentation',
                            'class' => 'active',
                        ],
                    ],
                ],
                'encodeLabels' => false,
                'options' => ['class' => 'nav-tabs'],
            ]) ?>

        </div>
        <div class="browser-tab-content tab-content">
            <div id="add-new-media" class="tab-pane">
                <?php $form = ActiveForm::begin([
                    'action' => Url::to(['/site/forbidden']),
                    'options' => [
                        'class' => 'media-upload',
                        'enctype' => 'multipart/form-data',
                    ],
                ]) ?>

                <noscript><?= Html::hiddenInput('redirect', Url::to(['/site/forbidden'])) ?></noscript>
                <div class="dropzone fade">
                    <div class="dropzone-inner">
                        <?= Yii::t('writesdown', 'Drop files here') ?> <br/>
                        <?= Yii::t('writesdown', 'OR') ?><br/>
                        <div class="btn btn-default btn-flat fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span><?= Yii::t('writesdown', 'Add files...') ?></span>
                            <?= $form->field($model, 'file', [
                                'template' => '{input}',
                                'options' => ['class' => null],
                            ])->fileInput(['multiple' => 'multiple']) ?>

                        </div>
                    </div>
                </div>
                <?php ActiveForm::end() ?>

            </div>
            <div id="media-library" class="tab-pane active">
                <form method="get" action="<?php Url::to(['/site/forbidden']) ?>" class="media-filter">
                    <div class="row">
                        <div class="col-xs-4 filter-uploaded">
                            <?php
                            echo Html::dropDownList(
                                'post',
                                null,
                                isset($post) ? [$post => Yii::t('writesdown', 'Uploaded to the post')] : [],
                                ['class' => 'input-sm form-control', 'prompt' => Yii::t('writesdown', 'All media')]
                            ) ?>

                        </div>
                        <div class="col-xs-4 filter-type">
                            <?= Html::dropDownList('type', Yii::$app->request->get('type', null), [
                                'image' => Yii::t('writesdown', 'Image'),
                                'audio' => Yii::t('writesdown', 'Audio'),
                                'video' => Yii::t('writesdown', 'Video'),
                                'application' => Yii::t('writesdown', 'File'),
                            ], [
                                'class' => Yii::$app->request->get('type', false)
                                    ? 'input-sm form-control sr-only'
                                    : 'input-sm form-control',
                                'prompt' => Yii::t('writesdown', 'Select type'),
                            ]) ?>

                        </div>
                        <div class="col-xs-4 filter-keyword">
                            <div class="input-group">
                                <?= Html::textInput('keyword', null, [
                                    'placeholder' => Yii::t('writesdown', 'Search'),
                                    'class' => 'input-sm form-control',
                                ]) ?>

                                <div class="input-group-btn">
                                    <?= Html::submitButton(
                                        '<i class="fa fa-search"></i>',
                                        ['class' => 'btn btn-sm btn-default btn-flat']
                                    ) ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="library-left">
                    <ul class="media-container clearfix"></ul>
                </div>
                <div class="library-right">
                    <div class="media-details"></div>
                    <div class="media-form"></div>
                </div>
                <div class="library-footer">
                    <?= Html::button(
                        Yii::t('writesdown', 'Insert Media'),
                        ['class' => 'insert-media btn btn-primary pull-right btn-flat']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('_template-upload') ?>
<?= $this->render('_template-download') ?>
<?= $this->render('_template-details') ?>
<?= $this->render('_template-form', ['model' => $model]) ?>
<?php
$options = Json::encode([
    'url' => [
        'json' => Url::to(['get-json']),
        'update' => Url::to(['/media/ajax-update']),
        'upload' => Url::to(['/media/ajax-upload']),
        'insert' => Yii::$app->request->get('editor', false)
            ? Url::to(['editor-insert'])
            : Url::to(['field-insert']),
    ],
    'editor' => (bool)Yii::$app->request->get('editor', false),
    'multiple' => (bool)Yii::$app->request->get('multiple', false),
    'callback' => Yii::$app->request->get('callback', false),
]);
$this->registerJs('jQuery("#media-browser").mediabrowser(' . $options . ')'); ?>

