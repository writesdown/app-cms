<?php
/**
 * @file      MediaMeta.php.
 * @date      6/4/2015
 * @time      4:40 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%media_meta}}".
 *
 * @property integer $id
 * @property integer $media_id
 * @property string  $meta_name
 * @property string  $meta_value
 *
 * @property Media   $media
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MediaMeta extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_meta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_id', 'meta_name', 'meta_value'], 'required'],
            [['media_id'], 'integer'],
            [['meta_value'], 'string'],
            [['meta_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('writesdown', 'ID'),
            'media_id'   => Yii::t('writesdown', 'Media ID'),
            'meta_name'  => Yii::t('writesdown', 'Meta Name'),
            'meta_value' => Yii::t('writesdown', 'Meta Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }
}
