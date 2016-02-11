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
 * This is the model class for table "{{%menu_item}}".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property string $label
 * @property string $url
 * @property string $description
 * @property integer $order
 * @property integer $parent
 * @property string $options
 *
 * @property Menu $menu
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class MenuItem extends ActiveRecord
{
    public $items;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'label', 'url'], 'required'],
            [['menu_id', 'order', 'parent'], 'integer'],
            [['url', 'description', 'options'], 'string'],
            ['label', 'string', 'max' => 255],
            ['url', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('writesdown', 'ID'),
            'menu_id' => Yii::t('writesdown', 'Menu ID'),
            'label' => Yii::t('writesdown', 'Label'),
            'url' => Yii::t('writesdown', 'URL'),
            'description' => Yii::t('writesdown', 'Description'),
            'order' => Yii::t('writesdown', 'Order'),
            'parent' => Yii::t('writesdown', 'Parent'),
            'options' => Yii::t('writesdown', 'Options'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }
}
