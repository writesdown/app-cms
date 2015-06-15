<?php
/**
 * @file      blank.php.
 * @date      6/4/2015
 * @time      5:23 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $content string */

// Favicon
$this->registerLinkTag([
    'rel'  => 'icon',
    'href' => Yii::getAlias('@web/favicon.ico'),
    'type' => 'image/x-icon'
]);

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <?= Html::csrfMetaTags() ?>
        <title>WritesDown &raquo; <?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body
        class="<?= isset(Yii::$app->params['bodyClass']) ? Yii::$app->params['bodyClass'] : "skin-blue sidebar-mini"; ?>">
    <?php $this->beginBody() ?>
    <?= $content; ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>