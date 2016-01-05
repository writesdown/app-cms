<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Option;
use themes\writesdown\assets\ThemeAsset;
use yii\helpers\Html;

$assetBundle = ThemeAsset::register($this);

/* @var $this \yii\web\View */
/* @var $content string */

// Canonical
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->request->absoluteUrl]);

// Favicon
$this->registerLinkTag([
    'rel'  => 'icon',
    'href' => $assetBundle->baseUrl . '/img/favicon.ico',
    'type' => 'image/x-icon',
]);

// Add meta robots noindex, nofollow when option disable_site_indexing = true
if (Option::get('disable_site_indexing')) {
    $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
}

// Get site-title and tag-line
$siteTitle = Option::get('sitetitle');
$tagLine = Option::get('tagline');
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>
        <?= $this->title ?>
    </title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= $this->render('header', [
    'assetBundle' => $assetBundle,
    'siteTitle'   => $siteTitle,
    'tagLine'     => $tagLine,
]) ?>
<div class="container" id="wrapper">
    <div class="row">
        <div class="col-md-8">
            <div id="content">
                <?= $content ?>
            </div>
        </div>
        <?= $this->render('sidebar') ?>
    </div>
</div>
<?= $this->render('footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
