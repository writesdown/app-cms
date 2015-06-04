<?php
/**
 * @file      Menu.php.
 * @date      6/4/2015
 * @time      4:39 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
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
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
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
            [['menu_location'], 'string', 'max' => 50]
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
     * @param int $parent_id
     *
     * @return array|null
     */
    public function getAvailableMenuItem($parent_id = 0)
    {
        /* @var $model \common\models\MenuItem */
        $models = $this->getMenuItems()->andWhere(['menu_parent' => $parent_id])->orderBy(['menu_order' => SORT_ASC])->indexBy('id')->all();
        if (empty($models)) {
            return null;
        }
        foreach ($models as $id => $model) {
            $models[ $id ]->items = $this->getAvailableMenuItem($model->id);
        }

        return $models;
    }

    /**
     * Get menu by location.
     * Ready to render on frontend.
     *
     * @param $menu_location
     *
     * @return array|null
     */
    public static function getMenu($menu_location)
    {
        $menu = static::getListMenuItem($menu_location);
        if ($menu) {
            return $menu;
        } else {
            return [];
        }
    }

    /**
     * List menu item by menu location;
     *
     * @param string $menu_location
     * @param int    $menu_parent
     *
     * @return array|null
     */
    protected static function getListMenuItem($menu_location, $menu_parent = 0)
    {
        $menuItem = [];

        $menuItemModel = MenuItem::find()->innerJoinWith(['menu'])->andWhere(['menu_location' => $menu_location])->andWhere(['menu_parent' => $menu_parent])->orderBy('menu_order')->all();

        if (empty($menuItemModel)) {
            return $menuItem = null;
        }

        foreach ($menuItemModel as $model) {
            $menuItem[] = [
                'id'     => $model->id,
                'label'  => $model->menu_label,
                'url'    => $model->menu_url,
                'parent' => $model->menu_parent,
                'items'  => self::getListMenuItem($menu_location, $model->id),
            ];
        }

        return $menuItem;
    }
}
