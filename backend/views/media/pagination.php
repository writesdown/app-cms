<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\widgets\LinkPager;

/* @var $pages \yii\data\Pagination */

echo LinkPager::widget([
    'pagination'     => $pages,
    'maxButtonCount' => 7,
    'linkOptions'    => [
        'class'         => 'pagination-item',
        'data-post_id'  => Yii::$app->request->get('post_id'),
        'data-per-page' => $pages->getPageSize(),
    ],
]);
