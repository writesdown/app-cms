<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Menu;
use themes\writesdown\widgets\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

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
                <?= Html::a('<i class="fa fa-rss"></i>', ['/feed']) ?>
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
            <?php $brandTag = Yii::$app->controller->route == 'site/index' ? 'h1' : 'div' ?>
            <?= Html::beginTag($brandTag, ['class' => 'navbar-brand']) ?>

            <a href="<?= Url::base(true) ?>">
                <img src="<?= $assetBundle->baseUrl . '/img/logo.png' ?>" alt="Website Logo">
                <span><?= Html::encode($siteTitle) ?></span>
            </a>
            <?= Html::endTag($brandTag) ?>

        </div>
        <div id="menu-primary" class="collapse navbar-collapse">
            <?= Nav::widget([
                'activateParents' => true,
                'options'         => ['class' => 'nav navbar-nav navbar-right'],
                'items'           => Menu::getMenu('primary'),
                'encodeLabels'    => false,
            ]) ?>

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
                    'options' => ['class' => 'form-search'],
                ]) ?>

                <div class="input-group">
                    <?= Html::textInput('s', Yii::$app->request->get('s'), [
                        'class'       => 'form-control',
                        'placeholder' => 'Search for...',
                    ]) ?>

                    <span class="input-group-btn">
                        <?= Html::submitButton(
                            '<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search',
                            ['class' => ' btn btn-default']
                        ) ?>

                    </span>
                </div>
                <?php ActiveForm::end() ?>

            </div>
        </div>
    </div>
</div>
