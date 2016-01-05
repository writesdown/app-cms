<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use backend\assets\MediaPopupAsset;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $editor bool|string */
/* @var $model common\models\Media */
/* @var $post common\models\Post */

MediaPopupAsset::register($this);
?>
<div id="media-popup" class="media-popup">
    <div id="sidebar-left" class="sidebar-left">
        <?php
        $items[] = [
            'label'       => 'All Media',
            'url'         => '#',
            'options'     => ['class' => 'active'],
            'linkOptions' => ['class' => 'media-popup-nav all'],
        ];

        if (isset($post)) {
            $items[] = [
                'label'       => 'Upload to this post',
                'url'         => '#',
                'linkOptions' => ['class' => 'media-popup-nav this', 'data-post_id' => $post->id],
            ];
        }

        echo Nav::widget([
            'activateItems' => false,
            'options'       => ['class' => 'nav nav-pills nav-stacked'],
            'items'         => $items,
        ]);
        ?>

    </div>
    <div id=content-wrapper">
        <div id="nav-tabs-custom" class="nav-tabs-custom">
            <?= Nav::widget([
                'items'        => [
                    [
                        'label'       => '<i class="fa fa-plus"></i> <span>'
                            . Yii::t('writesdown', 'Add New Media')
                            . '</span>',
                        'url'         => '#add-new-media',
                        'linkOptions' => [
                            'aria-controls' => 'add-new-media',
                            'role'          => 'tab',
                            'data-toggle'   => 'tab',
                        ],
                        'options'     => [
                            'role' => 'presentation',
                        ],
                    ],
                    [
                        'label'       => '<i class="fa fa-folder-open"></i> <span>'
                            . Yii::t('writesdown', 'Media Library')
                            . '</span>',
                        'url'         => '#media-library',
                        'linkOptions' => [
                            'aria-controls' => 'media-library',
                            'role'          => 'tab',
                            'data-toggle'   => 'tab',
                        ],
                        'options'     => [
                            'role'  => 'presentation',
                            'class' => 'active',
                        ],
                    ],
                ],
                'encodeLabels' => false,
                'options'      => [
                    'class' => 'nav-tabs nav-primary',
                    'id'    => 'nav-primary',
                ],
            ]) ?>

        </div>
        <div id="content" class="tab-content">
            <div id="add-new-media" class="tab-pane">
                <?php $form = ActiveForm::begin([
                    'id'      => 'media-upload',
                    'action'  => Url::to(['/site/forbidden']),
                    'options' => [
                        'enctype'  => 'multipart/form-data',
                        'data-url' => Url::to([
                            '/media/ajax-upload',
                            'post_id' => isset($post) ? $post->id : null,
                        ]),
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
                                'options'  => ['class' => ''],
                            ])->fileInput(['multiple' => 'multiple']) ?>

                        </div>
                    </div>
                </div>
                <?php ActiveForm::end() ?>

            </div>
            <div id="media-library" class="tab-pane active">
                <?php $form = ActiveForm::begin([
                    'id'      => 'media-filter',
                    'action'  => Url::to(['/site/forbidden']),
                    'options' => [
                        'class'    => 'media-filter form-inline',
                        'data-url' => Url::to([
                            '/media/ajax-upload',
                            'post_id' => isset($post) ? $post->id : null,
                        ]),
                    ],
                ]) ?>

                <?= isset($post) ? Html::hiddenInput('id', $post->id) : '' ?>

                <div class="form-group">
                    <?= Html::textInput('title', null, [
                        'placeholder' => Yii::t('writesdown', 'Search'),
                        'class'       => 'input-sm form-control',
                    ]) ?>

                </div>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-search"></i>', [
                        'class' => 'btn btn-sm btn-default btn-flat',
                    ]) ?>

                </div>
                <?php ActiveForm::end() ?>

                <div class="content-left">
                    <ul id="file-container" class="file-container clearfix"></ul>
                    <nav id="media-pagination" class="media-pagination"></nav>
                </div>
                <div class="content-right">
                    <div id="media-detail" class="media-detail"></div>
                    <div id="media-form" class="media-form"></div>
                </div>
                <div id="content-footer" class="content-footer">
                    <?= Html::button(Yii::t('writesdown', 'Insert Media'), [
                        'id'              => 'insert-media',
                        'class'           => 'insert-media btn btn-primary pull-right btn-flat',
                        'data-insert-url' => $editor ?
                            Url::to(['/media/editor-insert']) :
                            Url::to(['/media/field-insert']),
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?= Html::tag('span', '', [
    'data' => [
        'json-url'       => Url::to(['media/get-json']),
        'pagination-url' => Url::to(['media/get-pagination']),
    ],
    'id'   => 'address',
]) ?>
<?= $this->render('_template-popup') ?>
