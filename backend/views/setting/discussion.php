<?php
/**
 * @file      discussion.php.
 * @date      6/4/2015
 * @time      11:51 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $group string */
/* @var $model object */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('writesdown', '{group} Settings', ['group' => ucwords($group)]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="options-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

    <div class="form-group">
        <?= Html::label($model->default_comment_status->option_label, null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">
            <div class="checkbox">
                <?= Html::label(Html::checkbox('Option[default_comment_status][option_value]', $model->default_comment_status->option_value === 'open' ? true : false, ['uncheck' => 'close', 'value' => 'open']) . ' Allow people to post comments on new articles'); ?>
            </div>
            <p class="description">
                ( <?= Yii::t('writesdown', 'These settings may be overridden for individual articles.'); ?> )</p>
        </div>
    </div>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Other comment settings'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">
            <div class="checkbox">

                <?= Html::label(Html::checkbox('Option[require_name_email][option_value]', $model->require_name_email->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Comment author must fill out name and e-mail ')); ?>

                <br/>

                <?= Html::label(Html::checkbox('Option[comment_registration][option_value]', $model->comment_registration->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Users must be registered and logged in to comment ')); ?>

                <br/>

                <?= Html::label(Html::checkbox('Option[close_comments_for_old_posts][option_value]', $model->close_comments_for_old_posts->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Automatically close comments on articles older than')); ?>

                <?= Html::input('number', 'Option[close_comments_days_old][option_value]', $model->close_comments_days_old->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 50px']); ?>

                <?= Html::label(Yii::t('writesdown', ' days'), null, ['style' => 'padding-left: 0']); ?>

                <br/>

                <?= Html::label(Html::checkbox('Option[thread_comments][option_value]', $model->thread_comments->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Enable threaded (nested) comments')); ?>

                <?= Html::input('number', 'Option[thread_comments_depth][option_value]', $model->thread_comments_depth->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 50px']); ?>

                <?= Html::label(Yii::t('writesdown', 'levels deep'), null, ['style' => 'padding-left: 0']); ?>

                <br/>

                <?= Html::label(Html::checkbox('Option[page_comments][option_value]', $model->page_comments->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Break comments into pages with')); ?>

                <?= Html::input('number', 'Option[comments_per_page][option_value]', $model->comments_per_page->option_value, ['min' => 0, 'step' => 1, 'style' => 'width: 50px']); ?>

                <?= Html::label(Yii::t('writesdown', 'top level comments per page and the'), null, ['style' => 'padding-left: 0']); ?>

                <?= Html::dropDownList('Option[default_comments_page][option_value]', $model->default_comments_page->option_value, [
                    'newest' => 'last',
                    'oldest' => 'first',
                ]); ?>
                <?= Html::label(Yii::t('writesdown', 'page displayed by default Comments should be displayed with the'), null, ['style' => 'padding-left: 0']); ?>

                <?= Html::dropDownList('Option[comment_order][option_value]', $model->comment_order->option_value, [
                    'asc'  => 'older',
                    'desc' => 'newer',
                ]); ?>
                <?= Html::label(Yii::t('writesdown', 'comments at the top of each page'), null, ['style' => 'padding-left: 0']); ?>

            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'E-mail me whenever'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">
            <div class="checkbox">
                <?= Html::label(Html::checkbox('Option[comments_notify][option_value]', $model->comments_notify->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Anyone posts a comment ')); ?>
                <br/>
                <?= Html::label(Html::checkbox('Option[moderation_notify][option_value]', $model->moderation_notify->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'A comment is held for moderation')); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Before a comment appears'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">
            <div class="checkbox">
                <?= Html::label(Html::checkbox('Option[comment_moderation][option_value]', $model->comment_moderation->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Comment must be manually approved')); ?>
                <br/>
                <?= Html::label(Html::checkbox('Option[comment_whitelist][option_value]', $model->comment_whitelist->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Comment author must have a previously approved comment')); ?>
            </div>
        </div>
    </div>

    <h2><?= Yii::t('writesdown', 'Avatar'); ?></h2>

    <p><?= Yii::t('writesdown', 'An avatar is an image that follows you from weblog to weblog appearing beside your name when you comment on avatar enabled sites. Here you can enable the display of avatars for people who comment on your site.'); ?></p>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Show Avatars'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">
            <div class="checkbox">
                <?= Html::label(Html::checkbox('Option[show_avatars][option_value]', $model->show_avatars->option_value, ['uncheck' => 0]) . ' ' . Yii::t('writesdown', 'Show avatars on comments')); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Maximum Rating'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">
            <div class="radio">
                <?= Html::radioList('Option[avatar_rating][option_value]', $model->avatar_rating->option_value, [
                    'G'  => Yii::t('writesdown', 'G — Suitable for all audiences'),
                    'PG' => Yii::t('writesdown', 'PG — Possibly offensive, usually for audiences 13 and above'),
                    'R'  => Yii::t('writesdown', 'R — Intended for adult audiences above 17'),
                    'X'  => Yii::t('writesdown', 'X — Even more mature than above'),
                ], ['separator' => '<br />']); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::label(Yii::t('writesdown', 'Default Avatar'), null, ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-7">
            <div class="radio">
                <?= Html::radioList('Option[avatar_default][option_value]', $model->avatar_default->option_value, [
                    'mystery'          => Html::img('http://0.gravatar.com/avatar/0e42823c22bc734de4c13fd569cf1010?s=32&d=http%3A%2F%2F0.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D32&r=G&forcedefault=1', ['class' => 'avatar avatar-32 photo', 'width' => 32, 'height' => 32, 'alt' => '']) . ' Mystery Man',
                    'blank'            => Html::img('http://0.gravatar.com/avatar/0e42823c22bc734de4c13fd569cf1010?s=32&d=blank&r=G&forcedefault=1', ['class' => 'avatar avatar-32 photo', 'width' => 32, 'height' => 32, 'alt' => '']) . ' Blank',
                    'gravatar_default' => Html::img('http://0.gravatar.com/avatar/0e42823c22bc734de4c13fd569cf1010?s=32&d=&r=G&forcedefault=1', ['class' => 'avatar avatar-32 photo', 'width' => 32, 'height' => 32, 'alt' => '']) . ' Gravatar Logo',
                    'identicon'        => Html::img('http://0.gravatar.com/avatar/0e42823c22bc734de4c13fd569cf1010?s=32&d=identicon&r=G&forcedefault=1', ['class' => 'avatar avatar-32 photo', 'width' => 32, 'height' => 32, 'alt' => '']) . ' Identicon (Generated)',
                    'wavatar'          => Html::img('http://0.gravatar.com/avatar/0e42823c22bc734de4c13fd569cf1010?s=32&d=wavatar&r=G&forcedefault=1', ['class' => 'avatar avatar-32 photo', 'width' => 32, 'height' => 32, 'alt' => '']) . ' Watavar (Generated)',
                    'monsterid'        => Html::img('http://0.gravatar.com/avatar/0e42823c22bc734de4c13fd569cf1010?s=32&d=monsterid&r=G&forcedefault=1', ['class' => 'avatar avatar-32 photo', 'width' => 32, 'height' => 32, 'alt' => '']) . ' MonsterID (Generated)',
                    'retro'            => Html::img('http://0.gravatar.com/avatar/0e42823c22bc734de4c13fd569cf1010?s=32&d=retro&r=G&forcedefault=1', ['class' => 'avatar avatar-32 photo', 'width' => 32, 'height' => 32, 'alt' => '']) . ' Retro (Generated)',
                ], ['separator' => '<br />', 'encode' => false]); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton(Yii::t('writesdown', 'Save'), ['class' => 'btn btn-flat btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>