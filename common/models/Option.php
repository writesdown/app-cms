<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use common\components\Json;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property integer $id
 * @property string  $option_name
 * @property string  $option_value
 * @property string  $option_label
 * @property string  $option_group
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
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
            [['option_name', 'option_label', 'option_group'], 'string', 'max' => 64],
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
     * @param string $optionName
     *
     * @return string|array|boolean
     */
    public static function get($optionName)
    {
        /* @var $model \common\models\Option */
        $model = static::findOne(['option_name' => $optionName]);

        if ($model) {
            if (Json::isJson($model->option_value)) {
                return Json::decode($model->option_value);
            }

            return $model->option_value;
        }

        return null;
    }

    /**
     * Add new option, required option_name and option_value.
     * If option_value is array or object, it will be converted to json with Json::encode.
     *
     * @param string       $optionName
     * @param string       $optionValue
     * @param string|array $optionLabel
     * @param string       $optionGroup
     *
     * @return bool
     */
    public static function set($optionName, $optionValue, $optionLabel = null, $optionGroup = null)
    {
        if (is_array($optionValue) || is_object($optionValue)) {
            $optionValue = Json::encode($optionValue);
        }

        if (static::get($optionName) !== null) {
            return static::up($optionName, $optionValue);
        }

        $model = new Option();
        $model->option_name = $optionName;
        $model->option_value = $optionValue;
        $model->option_label = $optionLabel;
        $model->option_group = $optionGroup;

        return $model->save();
    }

    /**
     * Update option with option_name as key.
     *
     * @param string       $optionName
     * @param string|array $optionValue
     *
     * @return bool
     */
    public static function up($optionName, $optionValue)
    {
        /* @var $model \common\models\Option */
        $model = static::findOne(['option_name' => $optionName]);

        if (is_array($optionValue) || is_object($optionValue)) {
            $model->option_value = Json::encode($optionValue);
        } else {
            $model->option_value = $optionValue;
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
        $adminSiteMenu[$position] = ['label' => Yii::t('writesdown', 'Settings'), 'icon' => 'fa fa-cogs'];
        $adminSiteMenu[$position]['items'] = static::getSubMenu();

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
        $models = static::find()
            ->groupBy('option_group')
            ->andWhere(['<>', 'option_group', ''])
            ->andWhere(['<>', 'option_group', 'appearance'])
            ->all();
        $adminSiteSubmenu = null;

        foreach ($models as $model) {
            $adminSiteSubmenu[] = [
                'icon'    => 'fa fa-circle-o',
                'label'   => Yii::t('writesdown', ucwords($model->option_group)),
                'url'     => ['/setting/group/', 'id' => strtolower($model->option_group)],
                'visible' => Yii::$app->user->can('administrator'),
            ];
        }

        return $adminSiteSubmenu;
    }
}
