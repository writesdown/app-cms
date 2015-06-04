<?php
/**
 * @file      detail.php.
 * @date      6/4/2015
 * @time      12:03 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $detail [] */

$this->title = Yii::t('writesdown', 'Detail Theme');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Theme'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_theme-detail', ['detail' => $detail]);