<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use cebe\gravatar\Gravatar;
use yii\helpers\Html;

/* @var $postCount int */
/* @var $commentCount int */
/* @var $userCount int */
/* @var $users common\models\User[] */
/* @var $posts common\models\Post[] */
/* @var $comments common\models\PostComment[] */

$this->title = Yii::t('writesdown', 'Dashboard');

?>
<div class="site-index">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon info-box-icon"><i class="fa fa-github"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Github</span>
                    <span>
                        <a aria-label="Follow @writesdown on GitHub" href="https://github.com/writesdown"
                           class="github-button">@writesdown</a>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="fa fa-files-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('writesdown', 'Posts') ?></span>
                    <span><?= $postCount ?></span>
                </div>
            </div>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-comments-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('writesdown', 'Comments') ?></span>
                    <span><?= $commentCount ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('writesdown', 'Members') ?></span>
                    <span><?= $userCount ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('writesdown', 'Latest Posts') ?></h3>
                    <div class="box-tools pull-right">
                        <span class="label label-danger">
                            <?= Yii::t('writesdown', '{postCount} Posts', ['postCount' => $postCount]) ?>

                        </span>
                        <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        <button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><?= Yii::t('writesdown', 'Author') ?></th>
                            <th><?= Yii::t('writesdown', 'Content') ?></th>
                            <th><?= Yii::t('writesdown', 'Published') ?></th>
                            <th><?= Yii::t('writesdown', 'Comments') ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= $post->postAuthor->display_name ?></td>
                                <td><?= substr(strip_tags($post->excerpt), 0, 180) . '...' ?></td>
                                <td><?= Yii::$app->formatter->asDatetime($post->date) ?></td>
                                <td><?= $post->comment_count ?></td>
                                <td>
                                    <?= Html::a(
                                        '<span class="glyphicon glyphicon-eye-open"></span>',
                                        $post->url,
                                        ['title' => Yii::t('writesdown', 'View Post')]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('writesdown', 'Latest Members') ?></h3>
                    <div class="box-tools pull-right">
                        <span class="label label-warning">
                            <?= Yii::t('writesdown', '{userCount} Members', ['userCount' => $userCount]) ?>

                        </span>
                        <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        <button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="users-list clearfix">

                        <?php foreach ($users as $user): ?>
                            <li>
                                <?= Gravatar::widget([
                                    'email' => $user->email,
                                    'options' => ['alt' => $user->username],
                                    'size' => 128,
                                ]) ?>

                                <?= Html::a($user->display_name, $user->url, ['class' => 'users-list-name']) ?>

                                <?= Html::tag(
                                    'span',
                                    Yii::$app->formatter->asDate($user->created_at),
                                    ['class' => 'users-list-date']
                                ) ?>

                            </li>
                        <?php endforeach ?>

                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('writesdown', 'Latest Comments') ?></h3>
                    <div class="box-tools pull-right">
                        <span class="label label-success">
                            <?= Yii::t(
                                'writesdown',
                                '{commentCount} Comments',
                                ['commentCount' => $commentCount]
                            ) ?>

                        </span>
                        <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        <button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><?= Yii::t('writesdown', 'Author') ?></th>
                            <th><?= Yii::t('writesdown', 'Comments') ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($comments as $comment): ?>
                            <tr>
                                <td><?= $comment->author ?></td>
                                <td><?= substr(strip_tags($comment->content), 0, 180) . '...' ?></td>
                                <td>
                                    <?= Html::a(
                                        '<span class="glyphicon glyphicon-eye-open"></span>',
                                        $comment->commentPost->url . '#comment-' . $comment->id,
                                        ['title' => Yii::t('writesdown', 'View Comment')]
                                    ) ?>

                                </td>
                            </tr>
                        <?php endforeach ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
