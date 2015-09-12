<?php
/**
 * @file      Nav.php
 * @date      9/12/2015
 * @time      11:53 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace themes\writesdown\widgets;

class Nav extends \yii\bootstrap\Nav
{
    /**
     * @inheritdoc
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && $item['url'] === \Yii::$app->request->absoluteUrl) {
            return true;
        }

        return false;
    }
}