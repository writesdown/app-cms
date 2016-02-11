<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $location
 *
 * @property MenuItem[] $menuItems
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
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
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['location'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('writesdown', 'ID'),
            'title' => Yii::t('writesdown', 'Title'),
            'location' => Yii::t('writesdown', 'Location'),
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
     * Get available menu items recursively for backend purpose.
     *
     * @param int $parent
     * @return array|null
     */
    public function getBackendItems($parent = 0)
    {
        /* @var $model \common\models\MenuItem */
        $models = $this->getMenuItems()
            ->andWhere(['parent' => $parent])
            ->orderBy(['order' => SORT_ASC])
            ->indexBy('id')
            ->all();

        if (empty($models)) {
            return null;
        }

        foreach ($models as $id => $model) {
            $models[$id]->items = $this->getBackendItems($model->id);
        }

        return $models;
    }

    /**
     * Get menu by location.
     * Ready to render on frontend.
     *
     * @param $location
     * @return array|null
     */
    public static function get($location)
    {
        $menu = static::getFrontendItems($location);

        if ($menu) {
            return $menu;
        }

        return [];
    }

    /**
     * List menu item by menu location;
     *
     * @param string $location
     * @param int $parent
     * @return array|null
     */
    protected static function getFrontendItems($location, $parent = 0)
    {
        /* @var $menuItemModel \common\models\MenuItem[] */
        $menuItem = [];

        $menuItemModel = MenuItem::find()
            ->innerJoinWith(['menu'])
            ->andWhere(['location' => $location])
            ->andWhere(['parent' => $parent])
            ->orderBy('order')
            ->all();

        if (empty($menuItemModel)) {
            return $menuItem = null;
        }

        foreach ($menuItemModel as $model) {
            $menuItem[] = [
                'id' => $model->id,
                'label' => $model->label,
                'url' => $model->url,
                'parent' => $model->parent,
                'items' => static::getFrontendItems($location, $model->id),
            ];
        }

        return $menuItem;
    }
}
