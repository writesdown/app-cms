<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%media_comment}}".
 *
 * @property integer $media_id
 *
 * @property Media $commentMedia
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class MediaComment extends BaseComment
{
    /**
     * @var string
     */
    public $media_title;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['media_id', 'required'],
            ['media_id', 'integer'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'media_id' => Yii::t('writesdown', 'Comment to'),
            'media_title' => Yii::t('writesdown', 'Media Title'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }
}
