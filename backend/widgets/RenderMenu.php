<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\widgets;

use common\models\MenuItem;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class MenuRenderer to render menu item in admin menu.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class RenderMenu extends Widget
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

        if ($this->items) {
            $this->renderRecursive($this->items);
        }

        echo Html::endTag('ul');
    }

    /**
     * Render menu item recursively.
     *
     * @param MenuItem[] $items
     */
    protected function renderRecursive($items)
    {
        foreach ($items as $item) {
            echo Html::beginTag('li', ['class' => 'dd-item', 'data-id' => $item->id]);
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
