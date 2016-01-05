<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer    $id
 * @property string     $menu_title
 * @property string     $menu_location
 *
 * @property MenuItem[] $menuItems
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class Menu extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_title'], 'required'],
            [['menu_title'], 'string', 'max' => 255],
            [['menu_location'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('writesdown', 'ID'),
            'menu_title'    => Yii::t('writesdown', 'Title'),
            'menu_location' => Yii::t('writesdown', 'Location'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_id' => 'id']);
    }

    /**
     * Get available menu items recursively
     *
     * @param int $parentId
     *
     * @return array|null
     */
    public function getAvailableMenuItem($parentId = 0)
    {
        /* @var $model \common\models\MenuItem */
        $models = $this
            ->getMenuItems()
            ->andWhere(['menu_parent' => $parentId])
            ->orderBy(['menu_order' => SORT_ASC])
            ->indexBy('id')
            ->all();

        if (empty($models)) {
            return null;
        }

        foreach ($models as $id => $model) {
            $models[$id]->items = $this->getAvailableMenuItem($model->id);
        }

        return $models;
    }

    /**
     * Get menu by location.
     * Ready to render on frontend.
     *
     * @param $menuLocation
     *
     * @return array|null
     */
    public static function getMenu($menuLocation)
    {
        $menu = static::getListMenuItem($menuLocation);

        if ($menu) {
            return $menu;
        }

        return [];
    }

    /**
     * List menu item by menu location;
     *
     * @param string $menuLocation
     * @param int    $menuParent
     *
     * @return array|null
     */
    protected static function getListMenuItem($menuLocation, $menuParent = 0)
    {
        /* @var $menuItemModel \common\models\MenuItem[] */
        $menuItem = [];

        $menuItemModel = MenuItem::find()
            ->innerJoinWith(['menu'])
            ->andWhere(['menu_location' => $menuLocation])
            ->andWhere(['menu_parent' => $menuParent])
            ->orderBy('menu_order')
            ->all();

        if (empty($menuItemModel)) {
            return $menuItem = null;
        }

        foreach ($menuItemModel as $model) {
            $menuItem[] = [
                'id'     => $model->id,
                'label'  => $model->menu_label,
                'url'    => $model->menu_url,
                'parent' => $model->menu_parent,
                'items'  => static::getListMenuItem($menuLocation, $model->id),
            ];
        }

        return $menuItem;
    }
}
