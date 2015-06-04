<?php
/**
 * @file      pagination.php.
 * @date      6/4/2015
 * @time      5:47 AM
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
        'data-per-page' => $pages->getPageSize()
    ]
]);
