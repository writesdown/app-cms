<?php
/**
 * @file      MenuItem.php.
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
 * This is the model class for table "{{%menu_item}}".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property string  $menu_label
 * @property string  $menu_url
 * @property string  $menu_description
 * @property integer $menu_order
 * @property integer $menu_parent
 * @property string  $menu_options
 *
 * @property Menu    $menu
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
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
            [['menu_id', 'menu_label', 'menu_url'], 'required'],
            [['menu_id', 'menu_order', 'menu_parent'], 'integer'],
            [['menu_url', 'menu_description', 'menu_options'], 'string'],
            [['menu_label'], 'string', 'max' => 255],
            [['menu_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => Yii::t('writesdown', 'ID'),
            'menu_id'          => Yii::t('writesdown', 'Menu ID'),
            'menu_label'       => Yii::t('writesdown', 'Label'),
            'menu_url'         => Yii::t('writesdown', 'URL'),
            'menu_description' => Yii::t('writesdown', 'escription'),
            'menu_order'       => Yii::t('writesdown', 'Order'),
            'menu_parent'      => Yii::t('writesdown', 'Parent'),
            'menu_options'     => Yii::t('writesdown', 'Options'),
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
