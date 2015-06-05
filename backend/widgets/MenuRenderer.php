<?php
/**
 * @file    MenuRenderer.php.
 * @date    6/4/2015
 * @time    6:09 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class MenuRenderer to render menu item in admin menu.
 *
 * @package backend\widgets\menubuilder
 */
class MenuRenderer extends Widget
{
    /**
     * @var array
     */
    public $items = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::beginTag('ul', ['class' => 'list-menu dd-list']);

        if ($this->items)
            $this->renderRecursive($this->items);

        echo Html::endTag('ul');
    }

    /**
     * Render menu item recursively.
     *
     * @param $items
     */
    protected function renderRecursive($items)
    {
        /**
         * @var $item \common\models\MenuItem
         */
        foreach ($items as $item) {
            echo Html::beginTag('li', ['class' => 'dd-item', 'data-id' => $item->id]);

            // echo $this->render('render', ['item' => $item]);
            echo $this->renderFile('@app/views/menu/_render-item.php', ['item' => $item]);
            if (isset($item->items) && count($item->items)) {
                echo Html::beginTag('ul', ['class' => 'dd-list children']);
                $this->renderRecursive($item['items']);
                echo Html::endTag('ul');
            }

            echo Html::endTag('li');
        }
    }
}