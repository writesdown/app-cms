<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */


use common\models\Taxonomy;
use yii\bootstrap\Nav;

/* @var $this yii\web\View */
/* @var $taxonomies common\models\Taxonomy[] */

$taxonomies = Taxonomy::find()->all();
$items = [];
?>
<div id="sidebar">
    <div class="widget">
        <?= $this->render('search-form') ?>
    </div>

    <?php
    foreach ($taxonomies as $taxonomy) {
        foreach ($taxonomy->terms as $term) {
            if ($term->getPosts()->andWhere(['post_status' => 'publish'])->count()) {
                $items[$taxonomy->id][$term->id]['label'] = $term->term_name;
                $items[$taxonomy->id][$term->id]['url'] = $term->url;
            }
        }
        ?>
        <div class="widget">
            <div class="widget-title">
                <h4><?= $taxonomy->taxonomy_pn ?></h4>

            </div>
            <?= isset($items[$taxonomy->id])
                ? Nav::widget(['items' => $items[$taxonomy->id]])
                : '' ?>

        </div>
        <?php
    }
    ?>

</div>
