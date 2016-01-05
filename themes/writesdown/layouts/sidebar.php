<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use frontend\widgets\RenderWidget;

/* @var $this yii\web\View */
/* @var $taxonomies common\models\Taxonomy[] */
?>
<div class="col-md-4">
    <div id="sidebar">
        <?= RenderWidget::widget([
            'location' => 'sidebar',
            'config'   => [
                'beforeWidget' => '<div class="widget">',
                'afterWidget'  => '</div>',
                'beforeTitle'  => '<div class="widget-title"> <h4>',
                'afterTitle'   => '</div></h4>',
            ],
        ]) ?>

    </div>
</div>
