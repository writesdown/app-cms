<?php
/**
 * @file      main.php.
 * @date      6/4/2015
 * @time      5:24 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use codezeen\yii2\adminlte\widgets\Alert;

/* @var $this yii\web\View */
/* @var $content string */

$this->beginContent('@app/views/layouts/blank.php');
?>
    <div class="wrapper">
        <?= $this->render('main-header'); ?>
        <?= $this->render('main-sidebar'); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <?= $this->title; ?>
                </h1>
                <?= Breadcrumbs::widget([
                    'homeLink'     => [
                        'label' => Html::a('<i class="fa fa-dashboard"></i> ' . Yii::t('writesdown', 'Home'), Yii::$app->homeUrl),
                    ],
                    'encodeLabels' => false,
                    'links'        => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </section>
            <section class="content clearfix">
                <?= Alert::widget() ?>
                <?= $content; ?>
            </section>
        </div>
        <?= $this->render('main-footer'); ?>
    </div>
<?php
$this->endContent();