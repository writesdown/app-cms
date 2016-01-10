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
use yii\db\Expression;

/**
 * This is the model class for table "{{%widget}}".
 *
 * @property integer $id
 * @property string  $widget_title
 * @property string  $widget_config
 * @property string  $widget_location
 * @property integer $widget_order
 * @property string  $widget_dir
 * @property string  $widget_date
 * @property string  $widget_modified
 */
class Widget extends ActiveRecord
{
    /**
     * @var yii\web\UploadedFile
     */
    public $widget_file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget_title', 'widget_location', 'widget_dir'], 'required'],
            [['widget_config'], 'required', 'on' => 'create'],
            [['widget_config'], 'string'],
            [['widget_order'], 'integer'],
            [['widget_date', 'widget_modified'], 'safe'],
            [['widget_title'], 'string', 'max' => 255],
            [['widget_location', 'widget_dir'], 'string', 'max' => 128],
            [['widget_file'], 'required', 'on' => 'upload'],
            [['widget_file'], 'file', 'extensions' => 'zip'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => Yii::t('writesdown', 'ID'),
            'widget_title'    => Yii::t('writesdown', 'Title'),
            'widget_config'   => Yii::t('writesdown', 'Config'),
            'widget_location' => Yii::t('writesdown', 'Location'),
            'widget_order'    => Yii::t('writesdown', 'Order'),
            'widget_dir'      => Yii::t('writesdown', 'Directory'),
            'widget_date'     => Yii::t('writesdown', 'Assigned'),
            'widget_modified' => Yii::t('writesdown', 'Updated'),
            'widget_file'     => Yii::t('writesdown', 'Widget (ZIP)'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['upload'] = ['widget_file'];
        $scenarios['activate'] = $scenarios['default'];

        return $scenarios;
    }

    /**
     * Get widget configuration as array
     *
     * @return mixed
     */
    public function getConfig()
    {
        return Json::decode($this->widget_config);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->widget_date = new Expression('NOW()');
            }
            $this->widget_modified = new Expression('NOW()');

            return true;
        }

        return false;
    }
}
