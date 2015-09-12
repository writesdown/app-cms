<?php
/**
 * @file      header.php
 * @date      8/23/2015
 * @time      6:50 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use themes\writesdown\widgets\Nav;

/* MODELS */
use common\models\Menu;

/* @var $this yii\web\View */
/* @var $assetBundle themes\writesdown\assets\ThemeAsset */
/* @var $siteTitle string */
/* @var $tagLine string */

?>
<nav id="social-top">
    <div class="container">
        <ul class="pull-right">
            <li class="facebook">
                <a href="#"><i class="fa fa-facebook"></i> </a>
            </li>
            <li class="twitter">
                <a href="#"><i class="fa fa-twitter"></i> </a>
            </li>
            <li class="google-plus">
                <a href="#"><i class="fa fa-google-plus"></i> </a>
            </li>
            <li class="youtube">
                <a href="#"><i class="fa fa-youtube"></i> </a>
            </li>
            <li class="rss">
                <?= Html::a('<i class="fa fa-rss"></i>', ['/feed']); ?>
            </li>
        </ul>
    </div>
</nav>
<nav id="navbar-primary" class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button aria-expanded="false" data-target="#menu-primary" data-toggle="collapse"
                    class="navbar-toggle collapsed" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <h1 class="navbar-brand">
                <?= Html::beginTag('a', ['href' => Yii::$app->homeUrl]); ?>
                <?= Html::img($assetBundle->baseUrl . '/img/logo.png', ['alt' => 'Website Logo']); ?>
                <?= Html::tag('span', $siteTitle); ?>
                <?= Html::endTag('a'); ?>
            </h1>
        </div>
        <div id="menu-primary" class="collapse navbar-collapse">
            <?= Nav::widget([
                'activateParents' => true,
                'options'         => ['class' => 'nav navbar-nav navbar-right'],
                'items'           => Menu::getMenu('primary'),
                'encodeLabels'    => false
            ]); ?>
        </div>
    </div>
</nav>
<div id="search-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <nav id="breadcrumb-primary">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </nav>
            </div>
            <div class="col-md-4">
                <?php $form = ActiveForm::begin([
                    'action'  => Url::to(['/site/search']),
                    'method'  => 'get',
                    'id'      => 'search-form-top',
                    'options' => ['class' => 'form-search']
                ]); ?>
                <div class="input-group">
                    <?= Html::textInput('s', Yii::$app->request->get('s'), ['class' => 'form-control', 'placeholder' => 'Search for...']); ?>
                    <span class="input-group-btn">
                        <?= Html::button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search', [
                            'class' => ' btn btn-default',
                            'type'  => 'submit'
                        ]); ?>
                    </span>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
