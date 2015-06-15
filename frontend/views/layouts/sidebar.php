<?php
/**
 * @file      sidebar.php.
 * @date      6/4/2015
 * @time      10:23 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* MODEL */
use yii\bootstrap\Nav;
use common\models\Taxonomy;

/* @var $this yii\web\View */
/* @var $taxonomies common\models\Taxonomy[] */
?>
<div class="col-md-4">
    <div id="sidebar">
        <div class="widget">
            <?= $this->render('search-form'); ?>
        </div>
        <?php
        $taxonomies = Taxonomy::find()->all();
        $items = [];
        foreach ($taxonomies as $taxonomy) {
            foreach ($taxonomy->terms as $term) {
                if ($term->getPosts()->andWhere(['post_status' => 'publish'])->count()) {
                    $items[ $taxonomy->id ][ $term->id ]['label'] = $term->term_name;
                    $items[ $taxonomy->id ][ $term->id ]['url'] = $term->url;
                }
            } ?>
            <div class="widget">
                <div class="widget-title">
                    <h4><?= $taxonomy->taxonomy_pn; ?></h4>
                </div>
                <?= isset($items[ $taxonomy->id ]) ?
                    Nav::widget(['items' => $items[ $taxonomy->id ]]) : '' ?>
            </div>
        <?php
        }
        ?>
        <div class="widget">
            <div class="widget-title">
                <h4>Meta</h4>
            </div>
            <?= Nav::widget(['items' => [
                ['label' => 'Admin', 'url' => Yii::$app->urlManagerBack->createAbsoluteUrl('/site/index')],
                [
                    'label'   => 'Login',
                    'url'     => Yii::$app->urlManagerBack->createAbsoluteUrl('/site/login'),
                    'visible' => Yii::$app->user->isGuest
                ],
                [
                    'label'       => 'Logout',
                    'url'         => Yii::$app->urlManagerFront->createAbsoluteUrl('/site/logout'),
                    'visible'     => !Yii::$app->user->isGuest,
                    'linkOptions' => ['data-method' => 'post']
                ],
                ['label' => 'Entries RSS', 'url' => ['/feed']],
                ['label' => 'Sitemap', 'url' => ['/sitemap']],
            ]]); ?>
        </div>
    </div>
</div>