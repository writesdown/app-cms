<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
    'name'    => 'robots',
    'content' => 'noindex, nofollow',
]);
?>
<div class="site-error">
    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger"><?= nl2br(Html::encode($message)) ?></div>
    <p>The above error occurred while the Web server was processing your request.</p>
    <p>Please contact us if you think this is a server error. Thank you.</p>
</div>
