<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $optionName string */
/* @var $option [] */
/* @var $postTypes common\models\PostType[] */
/* @var $taxonomies common\models\Taxonomy[] */

$this->title = Yii::t('sitemap', 'XML Sitemap by WritesDown');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sitemap-default-index">
    <?= $this->render('_form', [
        'option'     => $option,
        'postTypes'  => $postTypes,
        'taxonomies' => $taxonomies,
    ]) ?>
</div>
