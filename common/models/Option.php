<?php
/**
 * @file      Option.php.
 * @date      6/4/2015
 * @time      3:54 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\components\Json;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property integer $id
 * @property string  $option_name
 * @property string  $option_value
 * @property string  $option_label
 * @property string  $option_group
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class Option extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_name', 'option_value'], 'required'],
            [['option_value'], 'string'],
            [['option_name', 'option_label', 'option_group'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('writesdown', 'ID'),
            'option_name'  => Yii::t('writesdown', 'Name'),
            'option_value' => Yii::t('writesdown', 'Value'),
            'option_label' => Yii::t('writesdown', 'Label'),
            'option_group' => Yii::t('writesdown', 'Group'),
        ];
    }

    /**
     * Get option value.
     * The return value array|boolean|string depends on option_value.
     * If option_value is string then the return value is a string.
     * If option_value is array|object then return value is array.
     * If option_name not found in table then it will return false.
     *
     * @param string $option_name
     *
     * @return string|array|boolean
     */
    public static function get($option_name)
    {
        /* @var $model \common\models\Option */
        $model = static::find()->where(['option_name' => $option_name])->one();

        if ($model) {
            if (Json::isJson($model->option_value))
                return Json::decode($model->option_value);
            else
                return $model->option_value;
        }

        return false;
    }

    /**
     * Add new option, required option_name and option_value.
     * If option_value is array or object, it will be converted to json with Json::encode.
     *
     * @param string       $option_name
     * @param string       $option_value
     * @param string|array $option_label
     * @param string       $option_group
     *
     * @return bool
     */
    public static function set($option_name, $option_value, $option_label = null, $option_group = null)
    {
        /* @var $model \common\models\Option */
        $model = new Option();

        if (is_array($option_value) || is_object($option_value)) {
            $model->option_value = Json::encode($option_value);
        } else {
            $model->option_value = $option_value;
        }

        $model->option_name = $option_name;
        $model->option_label = $option_label;
        $model->option_group = $option_group;

        return $model->save();
    }

    /**
     * Update option with option_name as key.
     *
     * @param string       $option_name
     * @param string|array $option_value
     *
     * @return bool
     */
    public static function up($option_name, $option_value)
    {
        /* @var $model \common\models\Option */
        $model = static::find()->where(['option_name' => $option_name])->one();
        if (is_array($option_value) || is_object($option_value)) {
            $model->option_value = Json::encode($option_value);
        } else {
            $model->option_value = $option_value;
        }

        return $model->save();
    }

    /**
     * Get menu item to render in admin sidebar left.
     *
     * @param int $position
     *
     * @return array
     */
    public static function getMenu($position = 30)
    {
        $adminSiteMenu[ $position ] = ['label' => Yii::t('writesdown', 'Settings'), 'icon' => '<i class="fa fa-cogs"></i>', 'options' => ['class' => 'treeview']];
        $adminSiteMenu[ $position ]['items'] = static::getSubMenu();

        return $adminSiteMenu;
    }

    /**
     * The option will be grouped into option_group to create new submenu item.
     *
     * @return array|null
     */
    protected static function getSubMenu()
    {
        /* @var $model \common\models\Option */
        $models = static::find()->groupBy('option_group')->andWhere(['<>', 'option_group', ''])->andWhere(['<>', 'option_group', 'appearance'])->all();
        $adminSiteSubmenu = null;
        foreach ($models as $model) {
            $adminSiteSubmenu[] = ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('writesdown', ucwords($model->option_group)), 'url' => ['/setting/group/', 'id' => strtolower($model->option_group)], 'visible' => Yii::$app->user->can('administrator')];
        }

        return $adminSiteSubmenu;
    }
} 