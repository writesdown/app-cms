<?php
/**
 * @file      main.php.
 * @date      6/4/2015
 * @time      10:04 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* MODEL */
use common\models\Option;
use common\models\Menu;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

// Canonical
$this->registerLinkTag([
    'rel'  => 'canonical',
    'href' => Yii::$app->request->absoluteUrl
]);

// Favicon
$this->registerLinkTag([
    'rel'  => 'icon',
    'href' => Yii::getAlias('@web/favicon.ico'),
    'type' => 'image/x-icon'
]);

// Add meta robots noindex, nofollow when option disable_site_indexing = true
if (Option::get('disable_site_indexing')) {
    $this->registerMetaTag([
        'name'    => 'robots',
        'content' => 'noindex, nofollow'
    ]);
}

// Get site-title and tag-line
$sitetitle = Option::get('sitetitle');
$tagline = Option::get('tagline');

$this->beginPage()
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>
        <?= Yii::$app->controller->route == 'site/index' ? $this->title : Option::get('sitetitle') . ' - ' . $this->title; ?>
    </title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
NavBar::begin([
    'brandLabel' => Html::img(Yii::getAlias('@web/img/logo-mini.png'), ['alt' => 'WritsDown Mini Logo']),
    'brandUrl'   => Yii::$app->homeUrl,
    'options'    => [
        'class' => 'navbar-inverse navbar-static-top',
        'id'    => 'navbar-primary'
    ],
]);
echo Nav::widget([
    'options'      => ['class' => 'navbar-nav'],
    'items'        => Menu::getMenu('primary'),
    'encodeLabels' => false
]);
NavBar::end();
?>
<header id="header-primary">
    <div class="container">
        <?= Html::a(Html::img(Yii::getAlias('@web/img/logo.png'), ['alt' => 'Writes Down Logo']), Yii::$app->homeUrl, ['id' => 'logo']); ?>
        <?php if (Yii::$app->controller->route == 'site/index') {
            echo Html::tag('h1', $sitetitle . ' - ' . $tagline, [
                'id'    => 'site-title',
                'class' => 'site-title'
            ]);
        } ?>
    </div>
</header>
<div id="breadcrumb-primary" class="hidden-xs">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>
</div>
<div class="container">
    <div id="content-wrapper">
        <div class="row">
            <div class="col-md-8">
                <div id="content">
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
            <?= $this->render('sidebar') ?>
        </div>
    </div>
</div>
<?= $this->render('footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
