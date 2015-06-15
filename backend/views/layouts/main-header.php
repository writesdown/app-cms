<?php
/**
 * @file      main-header.php.
 * @date      6/4/2015
 * @time      5:25 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use cebe\gravatar\Gravatar;

/* @var $this yii\web\View */

?>
<header class="main-header">
    <?php
    echo Html::a(
        Html::tag('span', Html::img(Yii::getAlias('@web/img/logo-mini.png')), ['class' => 'logo-mini']) .
        Html::tag('span', '<b>Writes</b>Down', ['class' => 'logo-lg']), Yii::$app->urlManagerFront->baseUrl,
        [
            'class' => 'logo'
        ]);
    ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <?php if (!Yii::$app->user->isGuest) { ?>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?= Gravatar::widget([
                                'email'   => Yii::$app->user->identity->email,
                                'options' => [
                                    'alt'   => Yii::$app->user->identity->username,
                                    'class' => 'user-image'
                                ],
                                'size'    => 25
                            ]); ?>
                            <span class="hidden-xs"><?= Yii::$app->user->identity->username; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <?= Gravatar::widget([
                                    'email'   => Yii::$app->user->identity->email,
                                    'options' => [
                                        'alt'   => Yii::$app->user->identity->username,
                                        'class' => 'img-circle'
                                    ],
                                    'size'    => 84
                                ]); ?>
                                <p>
                                    <?= Yii::$app->user->identity->username; ?>
                                    <small><?= Yii::t('writesdown', 'Member since {date}', ['date' => Yii::$app->formatter->asDate(Yii::$app->user->identity->created_at, 'php:F d, Y')]); ?></small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?= Html::a(Yii::t('writesdown', 'Profile'), ['/user/profile'], ['class' => 'btn btn-default btn-flat']); ?>
                                </div>
                                <div class="pull-right">
                                    <?= Html::a(Yii::t('writesdown', 'Sign Out'), ['/site/logout'], ['class' => 'btn btn-default btn-flat', 'data-method' => 'post']); ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

            </ul>
        </div>
    </nav>
</header>